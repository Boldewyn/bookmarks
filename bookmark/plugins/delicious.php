<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Save a bookmark to delicious
 */
function delicious_save($url, $title, $tags, $notes, $private) {
    if (cfg('plugins/delicious/auth', False)) {
        if (! cfg('plugins/delicious/sync', False)) {
            return False;
        }
        $fp = _delicious_connect(sprintf(
            '/v1/posts/add?url=%s&description=%s&tags=%s&shared=%s&extended=%s',
            rawurlencode($url),
            rawurlencode($title),
            rawurlencode(implode(' ', $tags)),
            ($private? 'no' : 'yes'),
            rawurlencode($notes)
            ));
        if (!$fp) {
            messages_add(__('Couldn’t connect to the Delicious API server to sync bookmarks.'), 'error');
        } else {
            $data = $fp[1];
            if (strpos($data, 'code="done"') !== False) {
                messages_add(__('The bookmark was exported to Delicious.'), 'success');
                return True;
            } else {
                messages_add(sprintf(__('There was an error exporting the bookmark to Delicious: %s'), preg_replace('/.+code="([^"]*)".+/s', '\1', $data)), 'error');
            }
        }
    } else {
        messages_add(__('You need to provide your access data for the Delicious API server to sync bookmarks.'), 'error');
    }
    return False;
}
register_for_hook('save', 'delicious_save');


/**
 * Delete a bookmark from delicious
 */
function delicious_delete($url) {
    if (cfg('plugins/delicious/auth', False)) {
        if (! cfg('plugins/delicious/sync', False)) {
            return False;
        }
        $fp = _delicious_connect('/v1/posts/delete?url=' . rawurlencode($url));
        if (!$fp) {
            messages_add(__('Couldn’t connect to the Delicious API server to sync bookmarks.'), 'error');
        } else {
            $data = $fp[1];
            if (strpos($data, 'code="done"') !== False) {
                messages_add(__('The bookmark was deleted from Delicious.'), 'success');
                return True;
            } else {
                messages_add(sprintf(__('There was an error deleting the bookmark from Delicious: %s'), preg_replace('/.+code="([^"]*)".+/s', '\1', $data)), 'error');
            }
        }
    } else {
        messages_add(__('You need to provide your access data for the Delicious API server to sync bookmarks.'), 'error');
    }
    return False;
}
register_for_hook('delete', 'delicious_delete');


/**
 * Import bookmarks from delicious
 */
function delicious_import($store) {
    $return = True;
    if (cfg('plugins/delicious/auth', False)) {
        $fp = _delicious_connect('/v1/posts/all');
        if (!$fp) {
            messages_add(__('Couldn’t connect to the Delicious API server.'),
                         'error');
            $return = False;
        } else {
            $data = $fp[1];
            try {
                $xml = simplexml_load_string($data);
                if (! $xml) {
                    throw new Exception();
                }
                $posts = $xml->xpath('/posts/post');
            } catch (Exception $e) {
                messages_add(__('Couldn’t parse the response from Delicious.'),
                             'error');
                return False;
            }
            $added = 0;
            $needs_update = 0;
            for ($i = 0; $i < count($posts); $i++) {
                $atts = array();
                foreach ($posts[$i]->attributes() as $k => $v) {
                    $atts[$k] = trim($v);
                }
                $atts += array("extended"=>"","shared"=>"yes");
                $r = $store->save(
                    $atts['href'],
                    $atts['description'],
                    explode(" ", $atts['tag']),
                    $atts['extended'],
                    ($atts['shared'] === 'no'),
                    $atts['time']
                );
                if ($r === True) {
                    $added++;
                } elseif ($r === Null) {
                    $needs_update++;
                }
            }
            if ($added > 0) {
                messages_add(sprintf(__('Added %s new bookmarks.'), $added),
                             'success');
            }
            if ($needs_update > 0) {
                messages_add(sprintf(__('%s bookmarks were already imported.'),
                                     $needs_update), 'info');
            }
            if (count($posts) > $added + $needs_update) {
                messages_add(sprintf(__('%s bookmarks could not be imported.'),
                                     count($posts) - $added -$needs_update),
                             'error');
            }
            $return = True;
        }
    } else {
        messages_add(__('You need to provide your access data for the '.
                        'Delicious API server.'), 'error');
        $return = False;
    }
    return $return;
}
register_for_hook('import', 'delicious_import');


/**
 * Utility: connect to delicious API
 */
function _delicious_connect($url) {
    $fp = fsockopen("ssl://api.delicious.com", 443);
    if ($fp) {
        $out  = "GET $url HTTP/1.1\r\n";
        $out .= "Host: api.delicious.com\r\n";
        $out .= "User-Agent: Personal Bookmarks Manager\r\n";
        $out .= "Authorization: Basic ".cfg('plugins/delicious/auth', '')."\r\n";
        $out .= "Connection: Close\r\n\r\n";
        fwrite($fp, $out);
        $data = '';
        while (!feof($fp)) {
            $data .= fgets($fp, 128);
        }
        fclose($fp);
        $data = explode("\n\n", str_replace("\r\n", "\n", $data), 2);
        $fp = $data;
    }
    return $fp;
}


//__END__

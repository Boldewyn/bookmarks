<?php defined('BOOKMARKS') or die('Access denied.');
#
# THIS IS UNTESTED, SINCE I HAVE NO PINBOARD ACCOUNT!
# IF YOU DO AND USE THIS PLUGIN, PLEASE FILE AN ISSUE AND DESCRIBE YOUR
# EXPERIENCES (EVEN IF EVERYTHING WORKS JUST FINE) AT
# http://github.com/Boldewyn/bookmarks/issues
#


/**
 * Save a bookmark to pinboard.in
 */
function pinboard_save($url, $title, $tags, $notes, $private) {
    if (cfg('plugins/pinboard/auth', False)) {
        if (! cfg('plugins/pinboard/sync', False)) {
            return False;
        }
        $fp = _pinboard_connect(sprintf(
            '/v1/posts/add?url=%s&description=%s&tags=%s&shared=%s&extended=%s',
            rawurlencode($url),
            rawurlencode($title),
            rawurlencode(implode(' ', $tags)),
            ($private? 'no' : 'yes'),
            rawurlencode($notes)
            ));
        if (!$fp) {
            messages_add(__('Couldn’t connect to the Pinboard API server to sync bookmarks.'), 'error');
        } else {
            $data = $fp[1];
            if (strpos($data, 'code="done"') !== False) {
                messages_add(__('The bookmark was exported to Pinboard.'), 'success');
                return True;
            } else {
                messages_add(sprintf(__('There was an error exporting the bookmark to Pinboard: %s'), preg_replace('/.+code="([^"]*)".+/s', '\1', $data)), 'error');
            }
        }
    } else {
        messages_add(__('You need to provide your access data for the Pinboard API server to sync bookmarks.'), 'error');
    }
    return False;
}
register_for_hook('save', 'pinboard_save');


/**
 * Delete a bookmark from pinboard
 */
function pinboard_delete($url) {
    if (cfg('plugins/pinboard/auth', False)) {
        if (! cfg('plugins/pinboard/sync', False)) {
            return False;
        }
        $fp = _pinboard_connect('/v1/posts/delete?url=' . rawurlencode($url));
        if (!$fp) {
            messages_add(__('Couldn’t connect to the Pinboard API server to sync bookmarks.'), 'error');
        } else {
            $data = $fp[1];
            if (strpos($data, 'code="done"') !== False) {
                messages_add(__('The bookmark was deleted from Pinboard.'), 'success');
                return True;
            } else {
                messages_add(sprintf(__('There was an error deleting the bookmark from Pinboard: %s'), preg_replace('/.+code="([^"]*)".+/s', '\1', $data)), 'error');
            }
        }
    } else {
        messages_add(__('You need to provide your access data for the Pinboard API server to sync bookmarks.'), 'error');
    }
    return False;
}
register_for_hook('delete', 'pinboard_delete');


/**
 * Import bookmarks from pinboard
 */
function pinboard_import($store) {
    $return = True;
    if (cfg('plugins/pinboard/auth', False)) {
        $fp = _pinboard_connect('/v1/posts/all');
        if (!$fp) {
            messages_add(__('Couldn’t connect to the Pinboard API server.'),
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
                messages_add(__('Couldn’t parse the response from Pinboard.'),
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
                messages_add(sprintf(__('Added %s new bookmarks from Pinboard.'), $added),
                             'success');
            }
            if ($needs_update > 0) {
                messages_add(sprintf(__('%s bookmarks were already imported from Pinboard.'),
                                     $needs_update), 'info');
            }
            if (count($posts) > $added + $needs_update) {
                messages_add(sprintf(__('%s bookmarks could not be imported from Pinboard.'),
                                     count($posts) - $added -$needs_update),
                             'error');
            }
            $return = True;
        }
    } else {
        messages_add(__('You need to provide your access data for the '.
                        'Pinboard API server.'), 'error');
        $return = False;
    }
    return $return;
}
register_for_hook('import', 'pinboard_import');


/**
 * Utility: connect to pinboard API
 */
function _pinboard_connect($url) {
    $fp = fsockopen("ssl://api.pinboard.in", 443);
    if ($fp) {
        $out  = "GET $url HTTP/1.1\r\n";
        $out .= "Host: api.pinboard.in\r\n";
        $out .= "User-Agent: Personal Bookmarks Manager\r\n";
        $out .= "Authorization: Basic ".cfg('plugins/pinboard/auth', '')."\r\n";
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

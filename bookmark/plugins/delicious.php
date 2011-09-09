<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Save a bookmark to delicious
 */
function delicious_save($url, $title, $tags, $private) {
    if (cfg('plugins/delicious/auth', False)) {
        $fp = fsockopen("ssl://api.delicious.com", 443);
        if (!$fp) {
            messages_add(__('Couldn’t connect to the delicious API server to sync bookmarks.'), 'error');
        } else {
            $out = sprintf("GET /v1/posts/add?url=%s&description=%s&tags=%s&shared=%s&extended=%s HTTP/1.1\r\n",
                    rawurlencode($url),
                    rawurlencode($title),
                    rawurlencode(implode(' ', $tags)),
                    ($private? 'no' : 'yes'),
                    rawurlencode(v('notes'))
                );
            $out .= "Host: api.delicious.com\r\n";
            $out .= "User-Agent: Personal Bookmarks Manager\r\n";
            $out .= "Authorization: Basic ".cfg('plugins/delicious/auth', '')."\r\n";
            $out .= "Connection: Close\r\n\r\n";
            fwrite($fp, $out);
            $data = "";
            while (!feof($fp)) {
                $data .= fgets($fp, 128);
            }
            fclose($fp);
            $data = explode("\n\n", str_replace("\r\n", "\n", $data), 2);
            $data = $data[1];
            if (strpos($data, 'code="done"') !== False) {
                messages_add(__('The bookmark was exported to delicious.'), 'success');
            } else {
                messages_add(sprintf(__('There was an error exporting the bookmark to delicious: %s'), preg_replace('/.+code="([^"]*)".+/s', '\1', $data)), 'error');
            }
        }
    } else {
        messages_add(__('You need to provide your access data for the delicious API server to sync bookmarks.'), 'error');
    }
}
register_for_hook('save', 'delicious_save');


/**
 * Import bookmarks from delicious
 */
function delicious_import($store) {
    $return = True;
    if (cfg('plugins/delicious/auth', False)) {
        if (! cfg('plugins/delicious/sync', False)) {
            return False;
        }
        $fp = fsockopen("ssl://api.delicious.com", 443);
        if (!$fp) {
            messages_add(__('Couldn’t connect to the delicious API server.'),
                         'error');
            $return = False;
        } else {
            $out  = "GET /v1/posts/all HTTP/1.1\r\n";
            $out .= "Host: api.delicious.com\r\n";
            $out .= "User-Agent: Personal Bookmarks Manager\r\n";
            $out .= 'Authorization: Basic ' .
                                  cfg('plugins/delicious/auth', '') . "\r\n";
            $out .= "Connection: Close\r\n\r\n";
            fwrite($fp, $out);
            $data = "";
            while (!feof($fp)) {
                $data .= fgets($fp, 128);
            }
            fclose($fp);
            $data = explode("\n\n", str_replace("\r\n", "\n", $data), 2);
            $data = $data[1];
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
            messages_add(sprintf(__('Added %s new bookmarks.'), $added),
                         'success');
            if ($needs_update > 0) {
                messages_add(sprintf(__('%s bookmarks were already imported.'),
                                     $needs_update), 'info');
            }
            if (count($posts) > $added + $needs_update) {
                messages_add(sprintf(__('%s bookmarks couldn’t be imported.'),
                                     count($posts) - $added -$needs_update),
                             'error');
            }
            $return = True;
        }
    } else {
        messages_add(__('You need to provide your access data for the '.
                        'delicious API server.'), 'error');
        $return = False;
    }
    return $return;
}
register_for_hook('import', 'delicious_import');


//__END__

<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Import bookmarks from delicious
 */
function import($store) {
    $status = do_login();
    if ($status !== True) {
        messages_add(__('You need to login for this.'), 'error');
        redirect('/login?next=import');
    }
    if (cfg('external/delicious/auth', False)) {
        $fp = fsockopen("ssl://api.delicious.com", 443);
        if (!$fp) {
            messages_add(__('Couldn’t connect to the delicious API server.'),
                         'error');
            redirect('/');
        } else {
            $out  = "GET /v1/posts/all HTTP/1.1\r\n";
            $out .= "Host: api.delicious.com\r\n";
            $out .= "User-Agent: Personal Bookmarks Manager\r\n";
            $out .= 'Authorization: Basic ' .
                                  cfg('external/delicious/auth', '') . "\r\n";
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
                redirect('/');
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
                    ($atts['shared'] === 'no')
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
            redirect('/');
        }
    } else {
        messages_add(__('You need to provide your access data for the delicious API server.'),
                     'error');
        redirect('/');
    }
    return '';
}


//__END__

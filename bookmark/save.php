<?php defined('BOOKMARKS') or die('Access denied.');

/* Authentication */
$status = login();
if ($status !== True) {
    messages_add(sprintf(__('A login error occurred: %s.'), $status), 'error');
    redirect('/?from=login');
}

function save($store) {
    $html = "";
    /* Main logic */
    if (! v('url') && ! v('save')):
        $html = format_template();
    elseif (! v('save')):
        $msg = '';
        $bm = $store->fetch(v('url'));
        if ($bm !== False) {
            if (! v('edit')) {
                $msg = sprintf('<p class="info">%s</p>',
                            __('This bookmark already exists.'));
            }
        } else {
            $bm = Null;
        }
        $html = format_template($bm, $msg);
    else:
        $url = v('url');
        $title = v('title', $url);
        $tags = explode(' ', preg_replace('/\s+/', ' ', v('tags')));
        $private = (bool)v('private');
        $e = $store->save($url, $title, $tags, v('notes'), $private);
        if ($e === True && defined('DELICIOUS_SYNC') && DELICIOUS_SYNC) {
            if (defined('DELICIOUS_AUTH')) {
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
                    $out .= "Authorization: Basic ".DELICIOUS_AUTH."\r\n";
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
        } elseif ($e === Null) {
            $e = $store->change($url, $title, $tags, v('notes'), $private);
        }
        if (! $e) {
            $error = $db->errorInfo();
            $msg = '<p class="error">'.sprintf(__('An error occurred: %s'), 
                                            h($error[2])).'</p>';
            $html = format_template(Null, $msg);
        } else {
            if (is_bookmarklet()) {
                $msg = '<script type="text/javascript">window.close()</script><p class="success"><a href="javascript:window.close()">'.
                        __('Successfully saved bookmark.').'</a></p>';
                $html = format_template(Null, $msg);
            } else {
                messages_add(__('Successfully saved bookmark.'), 'success');
                redirect('/?from=save');
            }
        }
    endif;
    return $html;
}

function format_template($v=Null, $msg='') {
    $title = __('Save Bookmark');
    $change = '';
    if ($v === Null) {
        $v = array(
            'url'=> v('url'),
            'title'=> v('title'),
            'notes'=> v('notes'),
            'tags'=> explode(' ', v('tags')),
            'private'=> v('private')
        );
        $button = __('Save');
    } else {
        $change = ' disabled="disabled" readonly="readonly';
        $button = __('Change');
    }
    return tpl('save', array(
        'body_id' => 'save',
        'site_title' => $title,
        'change' => $change,
        'button' => $button,
        'msg' => $msg,
        'private' => ($v['private']? 'checked="checked"' : ''),
        'tags' => join(' ', $v['tags']),
    ) + $v, array('msg'));
}

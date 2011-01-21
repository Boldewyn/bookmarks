<?php defined('BOOKMARKS') or die('Access denied.');

/* Authentication */
$status = login();
if ($status !== True) {
    messages_add(sprintf(__('A login error occurred: %s.'), $status), 'error');
    redirect('/?from=login');
}

/* Main logic */
if (! v('url') && ! v('save')):
    die(format_template());
elseif (! v('save')):
    $msg = '';
    $bm = $store->fetch(v('url'));
    if ($bm !== False) {
        if (! v('edit')) {
            $msg = sprintf('<p class="info">%s</p>',
                           __('This bookmark does already exist.'));
        }
    } else {
        $bm = Null;
    }
    die(format_template($bm, $msg));
else:
    $url = v('url');
    $title = v('title', $url);
    $tags = explode(' ', preg_replace('/\s+/', ' ', v('tags')));
    $private = (bool)v('private');
    $e = $store->save($url, $title, $tags, v('notes'), $private);
    if ($e === Null) {
        $e = $store->change($url, $title, $tags, v('notes'), $private);
    }
    if (! $e) {
        $error = $db->errorInfo();
        $msg = '<p class="error">'.sprintf(__('An error occurred: %s'), 
                                           h($error[2])).'</p>';
        die(format_template(Null, $msg));
    } else {
        if (is_bookmarklet()) {
            $msg = '<script type="text/javascript">window.close()</script><p class="success"><a href="javascript:window.close()">'.
                    __('Successfully saved bookmark.').'</a></p>';
            die(format_template(Null, $msg));
        } else {
            messages_add(__('Successfully saved bookmark.'), 'success');
            redirect('/?from=save');
        }
    }
endif;

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

<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Save a bookmark
 */
function save($store) {
    /* Authentication */
    if (logged_in() !== True) {
        messages_add(__('You need to log in to save a bookmark.'), 'error');
        redirect('/login?next=save');
    }
    /* Main logic */
    $html = '';
    $save = v('save', '', 'post');
    if (! v('url') && ! $save):
        $html = format_template();
    elseif (! $save):
        $bm = $store->fetch(v('url'));
        if ($bm !== False) {
            if (! v('edit')) {
                messages_add(__('This bookmark already exists.'), 'error');
            }
        } else {
            $bm = Null;
        }
        $html = format_template($bm);
    else:
        $url = filter_var(v('url', '', 'post'), FILTER_VALIDATE_URL);
        $title = v('title', $url, 'post');
        $tags = array_filter(array_map('trim',
                             explode(' ', v('tags', '', 'post'))));
        $notes = v('notes', '', 'post');
        $private = (bool)v('private', '', 'post');
        if (! check_csrf('save', v('ctoken', '', 'post'))) {
            messages_add(__('You cannot save this bookmark without '.
                'confirmation.'), 'error');
            $html = format_template();
        } elseif ($url === false) {
            messages_add(__('This doesn’t seem to be a valid URL.'), 'error');
            $html = format_template();
        } else {
            $e = $store->save($url, $title, $tags, $notes, $private);
            if ($e === True) {
                call_hook('save', array($url, $title, $tags, $notes, $private));
            } elseif ($e === Null) {
                $e = $store->change($url, $title, $tags, $notes, $private);
                call_hook('change', array($url, $title, $tags, $notes, $private));
            }
            if (! $e) {
                $error = get_db()->errorInfo();
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
                    redirect('/');
                }
            }
        }
    endif;
    return $html;
}


/**
 * Do some basic template formatting
 */
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


//__END__

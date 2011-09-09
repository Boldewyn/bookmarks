<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * delete a bookmark
 */
function delete($store) {
    /* Authentication */
    $status = do_login();
    if ($status !== True) {
        messages_add(sprintf(__('A login error occurred: %s.'), $status), 'error');
        redirect('/login');
    }
    /* Main logic */
    $url = v('url', '');
    $r = '';
    if ($url === '') {
        messages_add(__('No bookmark found to delete.'), 'error');
        redirect('/');
    } elseif (v('confirm', '') === '') {
        $bookmark = $store->fetch($url);
        $r = tpl('delete', array('body_id' => 'delete',
                                 'site_title' => __('Delete Bookmark'),
                                 'bookmark' => $bookmark));
    } else {
        $result = $store->delete($url);
        if ($result) {
            messages_add(__('Bookmark deleted.', 'success'));
            call_hook('delete', array($url));
        } else {
            messages_add(__('There was an error deleting this bookmark.'),
                         'error');
        }
        redirect('/');
    }
    return $r;
}


//__END__

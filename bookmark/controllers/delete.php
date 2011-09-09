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
    $html = "";
    /* Main logic */
    $r = '';
    if (v('url', '') === '') {
        messages_add(__('No bookmark found to delete.'), 'error');
        redirect('/');
    } elseif (v('confirm', '') === '') {
        $bookmark = $store->fetch(v('url'));
        $r = tpl('delete', array('body_id' => 'delete',
                                 'site_title' => __('Delete Bookmark'),
                                 'bookmark' => $bookmark));
    } else {
        $result = $store->delete(v('url'));
        if ($result) {
            messages_add(__('Bookmark deleted.', 'success'));
        } else {
            messages_add(__('There was an error deleting this bookmark.'),
                         'error');
        }
        redirect('/');
    }
    return $r;
}


//__END__

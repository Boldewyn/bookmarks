<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * delete a bookmark
 */
function delete($store) {
    /* Authentication */
    if (logged_in() !== True) {
        messages_add(__('You need to log in to delete a bookmark.'), 'error');
        redirect('/login?next=delete');
    }
    /* Main logic */
    $r = '';
    if ('' !== ($url = v('url', '')) && v('confirm', '', 'post') === '') {
        $bookmark = $store->fetch($url);
        $r = tpl('delete', array('body_id' => 'delete',
                                 'site_title' => __('Delete Bookmark'),
                                 'url' => $url,
                                 'bookmark' => $bookmark));
    } elseif ('' !== ($purl = v('url', '', 'post'))) {
        if (! check_csrf('delete', v('ctoken', '', 'post'))) {
            messages_add(__('You cannot delete this bookmark without '.
                'confirmation.'), 'error');
            refer();
        }
        $result = $store->delete($purl);
        if ($result) {
            if (v('ajax') !== '1') {
                messages_add(__('Bookmark deleted.', 'success'));
            }
            call_hook('delete', array($purl));
            if (v('ajax') === '1') {
                return 'true';
            }
        } else {
            messages_add(__('There was an error deleting this bookmark.'),
                         'error');
        }
        redirect('/');
    } else {
        messages_add(__('No bookmark found to delete.'), 'error');
        refer();
    }
    return $r;
}


//__END__

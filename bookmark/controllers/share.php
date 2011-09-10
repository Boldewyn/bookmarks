<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * share a bookmark
 */
function share($store) {
    $url = v('url');
    if (! $url) {
        messages_add(__('No URL to share given.'), 'error');
        refer();
    }
    $bookmark = $store->fetch($url);
    if ($bookmark === False) {
        messages_add(__('This URL is not bookmarked and cannot be shared.'),
            'error');
        refer();
    }
    $service = v('service', '', 'post');
    if ($service === '') {
        $r = tpl('share', array('body_id' => 'share',
            'site_title' => __('Share Bookmark'),
            'url' => $url,
            'bookmark' => $bookmark));
    }
    return $r;
}


//__END__

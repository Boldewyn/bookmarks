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
        $add_priv = False;
        if ($bookmark['private'] && v('share_private', '', 'post') !== '1') {
            messages_add(__('This is a private bookmark. Do you really want to share it?'), 'info');
            $add_priv = True;
        }
        $r = tpl('share', array('body_id' => 'share',
            'site_title' => __('Share Bookmark'),
            'add_priv' => $add_priv,
            'url' => $url,
            'bookmark' => $bookmark));
    } else {
        if (! check_csrf('share', v('ctoken', '', 'post'))) {
            messages_add(__('You cannot share this bookmark without '.
                'confirmation.'), 'error');
            refer();
        }
        call_hook('share', array($service, $bookmark));
    }
    return $r;
}


//__END__

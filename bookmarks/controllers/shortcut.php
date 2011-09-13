<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * get a shortcut and redirect
 */
function shortcut($store) {
    if (! isset($_GET['_shortcut'])) {
        messages_add(__('No shortcut given.'), 'error');
        refer();
    }
    $bookmark = $store->fetch_by_id($_GET['_shortcut']);
    if ($bookmark === False) {
        messages_add(__('This shortcut doesn’t exist.'), 'error');
        refer();
    }
    call_hook('shortcut', array($bookmark));
    header('HTTP/1.0 301 Moved Permanently');
    header('Location: '.$bookmark['url']);
    return 'Redirect to '.h($bookmark['url']);
}


//__END__

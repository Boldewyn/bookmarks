<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Show a random bookmark
 */
function show_random() {
    global $store;
    $all = $store->fetch_all(array(), 'count');
    $bookmark = $store->fetch_all(array(), mt_rand(0, $all-1), 1);
    $url = $bookmark[0]['url'];
    if (cfg('display/use_shortcut', False)) {
        $url = get_script_path().'-'.$bookmark[0]['shortcut'];
    }
    printf('<li class="random"><a href="%s" rel="external" title="%s" '.
           'style="background-image:url(%s)">%s</a></li>',
        h($url),
        h($bookmark[0]['title']),
        cfg('base_path').'static/icons/random.png',
        __('Random'));
}
register_for_hook('front_nav', 'show_random');


//__END__

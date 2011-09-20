<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Fetch bookmarks with certain tags from the database
 */
function tags($store) {
    $tags = v('tags');
    if (! $tags) {
        $tags = v('_info');
    }
    if (! $tags) {
        return tpl('tags', array('body_id' => 'tags',
            'site_title' => __('All Tags'),
            'tagcloud' => weight_tagcloud($store->fetch_all_tags()),
            'toptags' => weight_tagcloud($store->fetch_top_tags()),
            ));
    } else {
        require_once dirname(__FILE__).'/fetch.php';
        return fetch($store, $tags);
    }
}


//__END__

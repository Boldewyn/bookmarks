<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Fetch bookmarks with certain tags from the database
 */
function tags($store) {
    $tags = v('tags');
    if (! $tags) {
        $tags = v('_info');
    }
    require_once dirname(__FILE__).'/fetch.php';
    return fetch($store, $tags);
}


//__END__

<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Fetch all tags from the database
 */
function alltags($store) {
    $prefix = v('_info', '');
    $tags = $store->fetch_all_tags($prefix);
    header('Content-Type: application/json');
    return json_encode($tags);
}


//__END__

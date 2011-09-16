<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Fetch all tags from the database
 */
function alltags($store) {
    $prefix = v('_info', v('term', ''));
    $spref = preg_split('/\s+/', $prefix);
    $prefix = array_pop($spref);
    $tags = $store->fetch_all_tags($prefix);
    $stags = array();
    foreach ($tags as $tag) {
        $stags[] = $tag['tag'];
    }
    header('Content-Type: application/json');
    return json_encode($stags);
}


//__END__

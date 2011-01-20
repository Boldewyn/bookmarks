<?php defined('BOOKMARKS') or die('Access denied.');

$tags = array_filter(array_map('trim', explode(' ', $tags)));
$limit = v('n');
if (! ctype_digit($limit)) {
    $limit = 200;
} else {
    $limit = (int)$limit;
}
$bookmarks = $store->fetch_all($tags, $limit);
if ($bookmarks === Null) {
    $bookmarks = array();
}

switch (get_accept_type()) {
    case 'json':
        header('Content-Type: application/json');
        die(json_encode($bookmarks));
        break;
    case 'atom':
        header('Content-Type: application/atom+xml');
        die(tpl('atom.xml', array('bookmarks' => $bookmarks)));
        break;
    case 'rdf':
        header('Content-Type: application/rdf+xml');
        die(tpl('rdf.xml', array('bookmarks' => $bookmarks)));
        break;
    default:
        die(tpl('list', array('body_id' => 'index',
            'site_title' => __('Bookmarks'),
            'tags' => $tags,
            'bookmarks' => $bookmarks)));
        break;
}

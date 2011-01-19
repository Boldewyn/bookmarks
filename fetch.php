<?php defined('BOOKMARKS') or die('Access denied.');

#header('Content-Type: application/json');
header('Content-Type: text/plain');

$tags = explode(' ', v('tags'));
if (count($tags) == 1 && $tags[0] == "") {
    $tags = array();
}
$logged_in = False;
$limit = v('n');
if (! ctype_digit($limit)) {
    $limit = 200;
} else {
    $limit = (int)$limit;
}
$db = new PDO(DB_DSN, DB_USER, DB_PWD);
$store = new Bookmarks($db, $logged_in);
$bookmarks = $store->fetch_all($tags);
if ($bookmarks === Null) {
    $bookmarks = array();
}

die(json_encode($bookmarks));

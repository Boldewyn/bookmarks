<?php

require_once "config.php";
require_once "lib.php";
require_once "bookmarks.class.php";

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
$db = new PDO('mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME,
              DB_USER, DB_PWD);
$store = new Bookmarks($db, $logged_in);
$bookmarks = $store->fetch_all($tags);
if ($bookmarks === Null) {
    $bookmarks = array();
}

die(json_encode($bookmarks));

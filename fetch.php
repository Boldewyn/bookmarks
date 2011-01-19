<?php defined('BOOKMARKS') or die('Access denied.');

session_set_cookie_params(60*60*24*BOOKMARKS_STAY_LOGGED_IN);
session_name('Bookmarks');
session_start();

$tags = array_filter(array_map('trim', explode(' ', v('tags'))));
$logged_in = (in_array('logged_in', $_SESSION));
$limit = v('n');
if (! ctype_digit($limit)) {
    $limit = 200;
} else {
    $limit = (int)$limit;
}
$db = new PDO(DB_DSN, DB_USER, DB_PWD);
$store = new Bookmarks($db, $logged_in);
$bookmarks = $store->fetch_all($tags, $limit);
if ($bookmarks === Null) {
    $bookmarks = array();
}

$type = v('type');
if (! $type) {
    if (isset($_SERVER['HTTP_ACCEPT'])) {
        $a = $_SERVER['HTTP_ACCEPT'];
        if (strstr($a, 'application/json') !== False) {
            $type = 'json';
        } elseif (strstr($a, 'application/atom+xml') !== False) {
            $type = 'atom';
        } elseif (strstr($a, 'application/rdf+xml') !== False) {
            $type = 'rdf';
        } else {
            $type = 'html';
        }
    } elseif (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strstr($_SERVER['HTTP_X_REQUESTED_WITH'], 'XmlHttpRequest') !== False) {
        $type = 'json';
    } else {
        $type = 'html';
    }
}
switch ($type) {
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
            'bookmarks' => $bookmarks)));
        break;
}

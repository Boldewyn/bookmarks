<?php

require_once "config.php";

$tags = explode(' ', v('tags'));
$private = False;
$limit = v('n');
if (! ctype_digit($limit)) {
    $limit = '200';
}
$bookmarks = fetch($limit, $tags, $private);
if ($bookmarks === Null) {
    $bookmarks = array();
}

header('Content-Type: application/json');
die(json_encode($bookmarks));

function v($s, $default='') {
    if (array_key_exists($s, $_POST)) {
        return trim(preg_replace('/[\p{C}\\\]/u', '', $_POST[$s]));
    } elseif (array_key_exists($s, $_GET)) {
        return trim(preg_replace('/[\p{C}\\\]/u', '', $_GET[$s]));
    } else {
        return $default;
    }
}

function h($s) {
    return htmlspecialchars($s);
}

function __($s) {
    return $s;
}

function fetch($limit=200, $tags=array(), $private=False) {
    $bookmarks = array();
    try {
        $dbh = new PDO('mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME,
                        DB_USER, DB_PWD);
        if (count($tags) > 0) {
            $query = 'SELECT * FROM bookmarks b, bookmark_tags t WHERE b.href = t.href AND ';
            $where = array();
            foreach ($tags as $tag) {
                $where[] = 't.tag = "'.$dbh->quote(tag).'"';
            }
            $query .= join(' AND ', $where).' LIMIT 0,'.$limit;
        } else {
            $query = 'SELECT * FROM bookmarks LIMIT 0,'.$limit;
        }
        foreach($dbh->query($query) as $row) {
            $bookmarks[] = $row;
        }
        $dbh = null;
    } catch (PDOException $e) {
        return Null;
    }
    return $bookmarks;
}


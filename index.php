<?php

require_once "config.php";

#header('Content-Type: application/json');
header('Content-Type: text/plain');

$tags = explode(' ', v('tags'));
if (count($tags) == 1 && $tags[0] == "") {
    $tags = array();
}
$private = False;
$limit = v('n');
if (! ctype_digit($limit)) {
    $limit = 200;
} else {
    $limit = (int)$limit;
}
$bookmarks = fetch(min($limit, 1000), $tags, $private);
if ($bookmarks === Null) {
    $bookmarks = array();
}

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
            $query = 'SELECT b.href href, b.title title, b.notes notes, b.private private
                        FROM bookmarks b, bookmark_tags t
                       WHERE b.href = t.href
                         AND b.private = :private
                         AND ';
            $where = array();
            foreach ($tags as $tag) {
                $where[] = 't.tag = '.$dbh->quote($tag);
            }
            $query .= join(' AND ', $where).' LIMIT 0,:limit';
            $query = $dbh->prepare($query);
        } else {
            $query = $dbh->prepare('SELECT href, title, notes, private
                                      FROM bookmarks
                                     WHERE private = :private
                                     LIMIT 0,:limit');
        }
        $query->bindParam(':private', $private, PDO::PARAM_BOOL);
        $query->bindParam(':limit', $limit, PDO::PARAM_INT);
        $query->execute();
        $bookmarks = $query->fetchAll(PDO::FETCH_ASSOC);
        $qtags = $dbh->prepare('Select tag FROM bookmark_tags WHERE href = :href');
        $href = Null;
        for ($i = 0; $i < count($bookmarks); $i++) {
            $qtags->execute(array(':href' => $bookmarks[$i]['href']));
            $bookmarks[$i]['tags'] = $qtags->fetchAll(PDO::FETCH_COLUMN);
        }
        $query->debugDumpParams();
        $dbh = null;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    return $bookmarks;
}


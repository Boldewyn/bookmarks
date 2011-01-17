<?php

require_once "config.php";

$tpl = '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="style.css" />
    <title>'.__('Save Bookmark').'</title>
  </head>
  <body id="save">
    <div>
      <h1>'.__('Save Bookmark').'</h1>
      %s
      <form method="post" action="">
        <p>
          <label for="href">'.__('URL').'</label>
          <input type="url" name="href" id="href" value="'.h(v('href')).'" />
        </p>
        <p>
          <label for="title">'.__('Title').'</label>
          <input type="text" name="title" id="title" value="'.h(v('title')).'" />
        </p>
        <p>
          <label for="tags">'.__('Tags').'</label>
          <input type="text" name="tags" id="tags" value="'.h(v('tags')).'" />
        </p>
        <p>
          <label for="notes">'.__('Notes').'</label>
          <textarea name="notes" id="notes">'.h(v('href')).'</textarea>
        </p>
        <p>
          <label for="private">'.__('Private').'</label>
          <input type="checkbox" name="private" id="private"
            '.(v('private')? 'checked="checked"' : '').' />
        </p>
        <p>
          <button type="submit">'.__('Save').'</button>
        </p>
      </form>
    </div>
  </body>
</html>
';

if (! v('href')):
    die(sprintf($tpl, ''));
else:
    $href = v('href');
    $title = v('title', $href);
    $tags = explode(' ', preg_replace('/\s+/', ' ', v('tags')));
    $private = (bool)v('private');
    $e = store($href, $title, $tags, v('notes'));
    if ($e) {
        die(sprintf($tpl, $e));
    } else {
        die('<script type="text/javascript">window.close()</script>');
    }
endif;

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

function store($href, $title, $tags, $notes, $private) {
    try {
        $dbh = new PDO('mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME,
                        DB_USER, DB_PWD);
        $stmt = $dbh->prepare('INSERT INTO bookmarks (href, title, notes, private)
                                    VALUES (:href, :title, :notes, :private)');
        $stmt->bindParam(':href', $href);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':notes', $notes);
        $stmt->bindParam(':private', $private);
        $stmt->execute();
        foreach ($tags as $tag) {
            $stmt = $dbh->prepare('INSERT INTO bookmark_tags (href, tag) VALUES (:href, :tag)');
            $stmt->bindParam(':href', $href);
            $stmt->bindParam(':tag', $tag);
            $stmt->execute();
        }
        $dbh = null;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    return '';
}


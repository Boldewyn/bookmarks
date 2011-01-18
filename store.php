<?php

require_once "config.php";
require_once "lib.php";
require_once "bookmarks.class.php";

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
          <input type="hidden" name="store" value="1" />
          <button type="submit">'.__('Save').'</button>
        </p>
      </form>
    </div>
  </body>
</html>
';

if (! v('href') && ! v('store')):
    die(sprintf($tpl, ''));
else:
    $href = v('href');
    $title = v('title', $href);
    $tags = explode(' ', preg_replace('/\s+/', ' ', v('tags')));
    $private = (bool)v('private');
    $db = new PDO('mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME,
                  DB_USER, DB_PWD);
    $store = new Bookmarks($db);
    $e = $store->save($href, $title, $tags, v('notes'), $private);
    if (! $e) {
        $error = $db->errorInfo();
        die(sprintf($tpl, '<p class="error">'.__('An error occurred: ').$error[2].'</p>'));
    } else {
        die('<script type="text/javascript">window.close()</script><a href="javascript:window.close()">Successfully stored bookmark.</a>');
    }
endif;


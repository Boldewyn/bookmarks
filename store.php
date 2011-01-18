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
          <textarea name="notes" id="notes">'.h(v('notes')).'</textarea>
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

$db = new PDO('mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME,
                DB_USER, DB_PWD);
$store = new Bookmarks($db);

if (! v('href') && ! v('store')):
    die(format_template());
elseif (! v('store')):
    $msg = '';
    $bm = $store->fetch(v('href'));
    if ($bm !== False) {
        if (! v('edit')) {
            $msg = __('<p class="info">This bookmark does already exist.</p>');
        }
    } else {
        $bm = Null;
    }
    die(format_template($bm, $msg));
else:
    $href = v('href');
    $title = v('title', $href);
    $tags = explode(' ', preg_replace('/\s+/', ' ', v('tags')));
    $private = (bool)v('private');
    $e = $store->save($href, $title, $tags, v('notes'), $private);
    if (! $e) {
        $error = $db->errorInfo();
        $msg = '<p class="error">'.__('An error occurred: ').$error[2].'</p>';
        die(format_template(Null, $msg));
    } else {
        $msg = '<script type="text/javascript">window.close()</script><p class="success"><a href="javascript:window.close()">'.
                __('Successfully stored bookmark.').'</a></p>';
        die(format_template(Null, $msg));
    }
endif;


function format_template($v=Null, $msg='') {
    $title = __('Save Bookmark');
    $change = '';
    if ($v === Null) {
        $v = array(
            'href'=> v('href'),
            'title'=> v('title'),
            'notes'=> v('notes'),
            'tags'=> explode(' ', v('tags')),
            'private'=> v('private')
        );
        $button = __('Save');
    } else {
        $change = ' disabled="disabled" readonly="readonly';
        $button = __('Change');
    }
    return '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="style.css" />
    <title>'.$title.'</title>
  </head>
  <body id="save">
    <div>
      <h1>'.$title.'</h1>
      '.$msg.'
      <form method="post" action="">
        <p>
          <label for="href">'.__('URL').'</label>
          <input type="url" name="href" id="href" value="'.h($v['href']).'" '.$change.' />
        </p>
        <p>
          <label for="title">'.__('Title').'</label>
          <input type="text" name="title" id="title" value="'.h($v['title']).'" />
        </p>
        <p>
          <label for="tags">'.__('Tags').'</label>
          <input type="text" name="tags" id="tags" value="'.h(join(' ', $v['tags'])).'" />
        </p>
        <p>
          <label for="notes">'.__('Notes').'</label>
          <textarea name="notes" id="notes">'.h($v['notes']).'</textarea>
        </p>
        <p>
          <label for="private">'.__('Private').'</label>
          <input type="checkbox" name="private" id="private"
            '.($v['private']? 'checked="checked"' : '').' />
        </p>
        <p>
          <input type="hidden" name="store" value="1" />
          <button type="submit">'.$button.'</button>
        </p>
      </form>
    </div>
  </body>
</html>
';
}

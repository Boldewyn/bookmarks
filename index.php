<?php define('BOOKMARKS', '0.9');

require_once 'config.php';
require_once 'lib/utils.php';
require_once 'lib/bookmarks.class.php';

if (! isset($_GET['f'])) {
    require_once 'fetch.php';
} elseif ($_GET['f'] === 'store') {
    require_once 'store.php';
} elseif (substr($_GET['f'], 0, 5) === "tags/") {
    if (! isset($_GET['tags'])) {
        $_GET['tags'] = substr($_GET['f'], 5);
    }
    require_once 'fetch.php';
}

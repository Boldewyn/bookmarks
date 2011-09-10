<?php define('BOOKMARKS', '0.9');


require_once 'lib/config.php';
if (cfg('debug')) {
    error_reporting(-1);
    ini_set('display_errors', 1);
}
require_once 'lib/utils.php';
require_once 'lib/hooks.php';
require_once 'lib/session.php';
require_once 'lib/auth.php';
require_once 'lib/sql.php';
require_once 'lib/bookmarks.class.php';


load_plugins();
start_session();
$store = new Bookmarks(in_array('logged_in', $_SESSION));


$f = v('f', '');
if ($f === '') {
    if (isset($_SERVER['PATH_INFO'])) {
        $f = ltrim($_SERVER['PATH_INFO'], '/');
    }
    if ($f === '') {
        $f = 'fetch';
    }
}
if (substr($f, 0, 5) === 'tags/') {
    require_once 'controllers/fetch.php';
    $tags = v('tags');
    if (! $tags) {
        $tags = substr($f, 5);
    }
    echo fetch($store, $tags);
} elseif (substr($f, 0, 8) === 'all_tags') {
    $prefix = substr($f, 9);
    if ($prefix === False) { $prefix = ''; }
    $tags = $store->fetch_all_tags($prefix);
    header('Content-Type: application/json');
    die(json_encode($tags));
} elseif (in_array($f, array('share', 'delete', 'login', 'logout', 'fetch', 'help', 'search', 'import', 'install', 'save'))) {
    require_once 'controllers/'.$f.'.php';
    echo $f($store);
} else {
    require_once 'controllers/fetch.php';
    messages_add(sprintf(__('The site %s couldn’t be found.'), '<var>'.h(urlencode($f)).'</var>'),
        'error', True);
    header('HTTP/1.0 404 Not Found');
    echo fetch($store);
}


//__END__

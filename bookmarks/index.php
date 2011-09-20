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
if (strpos($f, '/') !== False) {
    $parts = explode('/', $f);
    $f = array_shift($parts);
    $_GET['_info'] = join('/', $parts);
}
if ($f[0] === '-') {
    $_GET['_shortcut'] = substr($f, 1);
    $f = 'shortcut';
}

if (ctype_alnum($f) && is_file("controllers/$f.php")) {
    require_once "controllers/$f.php";
    echo $f($store);
} else {
    messages_add(sprintf(__('The site %s couldn’t be found.'),
                 '<var>'.h(urlencode($f)).'</var>'), 'error', True);
    header('HTTP/1.0 404 Not Found');
    require_once 'controllers/fetch.php';
    echo fetch($store);
}


//__END__

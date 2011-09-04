<?php define('BOOKMARKS', '0.9');

require_once 'lib/config.php';
require_once 'lib/utils.php';
require_once 'lib/session.php';
require_once 'lib/auth.php';
require_once 'lib/bookmarks.class.php';

start_session();

$db = new PDO(cfg('database/dsn'),
              cfg('database/user'),
              cfg('database/password'));
$store = new Bookmarks($db, (in_array('logged_in', $_SESSION)));

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
} elseif ($f === 'login') {
    $status = login();
    if ($status !== True) {
        messages_add(sprintf(__('A login error occurred: %s.'), $status), 'error');
        redirect('/');
    } else {
        messages_add(sprintf(__('Successfully logged in. Welcome back.'), $status), 'success');
        redirect('/');
    }
} elseif ($f === 'logout') {
    $_SESSION = array();
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
    session_destroy();
    redirect('/');
} elseif (in_array($f, array('fetch', 'help', 'search', 'import', 'install', 'save'))) {
    require_once 'controllers/'.$f.'.php';
    echo $f($store);
} else {
    require_once 'controllers/fetch.php';
    messages_add(sprintf(__('The site %s couldn’t be found.'), '<var>'.h(urlencode($f)).'</var>'),
        'error', True);
    header('HTTP/1.0 404 Not Found');
    echo fetch($store);
}

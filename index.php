<?php define('BOOKMARKS', '0.9');

require_once 'config.php';
require_once 'lib/utils.php';
require_once 'lib/bookmarks.class.php';

session_set_cookie_params(60*60*24*BOOKMARKS_STAY_LOGGED_IN);
session_name('Bookmarks');
session_start();

$db = new PDO(DB_DSN, DB_USER, DB_PWD);
$store = new Bookmarks($db, (in_array('logged_in', $_SESSION)));

$f = v('f');
if ($f === '') {
    require_once 'fetch.php';
} elseif ($f === 'store') {
    require_once 'store.php';
} elseif (substr($f, 0, 5) === "tags/") {
    $tags = v('tags');
    if (! $tags) {
        $tags = substr($f, 5);
    }
    require_once 'fetch.php';
} elseif (substr($f, 0, 8) === "all_tags") {
    $prefix = substr($f, 9);
    if ($prefix === False) { $prefix = ''; }
    $tags = $store->fetch_all_tags($prefix);
    header('Content-Type: application/json');
    die(json_encode($tags));
} elseif ($f === 'login') {
    $status = login();
    if ($status !== True) {
        die(tpl('message', array('body_id' => 'error',
            'msg_class' => 'error',
            'site_title' => __('A Login Error Occurred'),
            'msg' => $status)));
    } else {
        header('Location: '.dirname($_SERVER['PHP_SELF']).'/');
        die('Redirecting');
    }
} elseif ($f === 'logout') {
    $_SESSION = array();
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
    session_destroy();
    header('Location: '.dirname($_SERVER['PHP_SELF']).'/');
    die('Redirecting');
} else {
    header('HTTP/1.0 404 Not Found');
    die(tpl('message', array('body_id' => 'error',
            'msg_class' => 'error',
            'site_title' => __('Site not Found'),
            'msg' => sprintf(__('The site %s couldn’t be found.'),
                             '<var>'.h(urlencode($f)).'</var>'
        )), array('msg')));
}

<?php define('BOOKMARKS', '0.9');

require_once 'config.php';
require_once 'lib/utils.php';
require_once 'lib/bookmarks.class.php';

if (! isset($_GET['f']) || ! $_GET['f']) {
    require_once 'fetch.php';
} elseif ($_GET['f'] === 'store') {
    require_once 'store.php';
} elseif (substr($_GET['f'], 0, 5) === "tags/") {
    if (! isset($_GET['tags'])) {
        $_GET['tags'] = substr($_GET['f'], 5);
    }
    require_once 'fetch.php';
} elseif ($_GET['f'] === 'login') {
    $status = login();
    if ($status !== True) {
        die(tpl('error', array('body_id' => 'error',
            'site_title' => __('A Login Error Occurred'),
            'msg' => $status)));
    } else {
        header('Location: '.dirname($_SERVER['PHP_SELF']).'/');
        die('Redirecting');
    }
} elseif ($_GET['f'] === 'logout') {
    session_name('Bookmarks');
    session_start();
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
    die(tpl('error', array('body_id' => 'error',
            'site_title' => __('Site not Found'),
            'msg' => sprintf(__('The site %s couldn’t be found.'),
                             '<var>'.h(urlencode($_GET['f'])).'</var>'
        )), array('msg')));
}

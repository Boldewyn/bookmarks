<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Log the user in (requires OpenID)
 */
function do_login() {
    require_once dirname(__FILE__).'/lightopenid/openid.php';
    if (! in_array('logged_in', $_SESSION) && ! cfg('auth/assume_logged_in', False)) {
        $openid = new LightOpenID;
        if(!$openid->mode) {
            $openid->identity = cfg('auth/openid');
            redirect($openid->authUrl());
        } elseif($openid->mode == 'cancel') {
            return __('User has canceled authentication');
        } elseif (! $openid->validate()) {
            return __('Login was not successful');
        } else {
            $_SESSION['logged_in'] = True;
        }
    } elseif (cfg('auth/assume_logged_in', False)) {
        $_SESSION['logged_in'] = True;
    }
    return True;
}


/**
 * Test, if the user is logged in
 */
function logged_in() {
    return in_array('logged_in', $_SESSION) ||
           cfg('auth/assume_logged_in', False);
}


/**
 * log out the user
 */
function do_logout() {
    session_regenerate_id();
    //$params = session_get_cookie_params();
    //setcookie(session_name(), '', time() - 42000,
    //    $params["path"], $params["domain"],
    //    $params["secure"], $params["httponly"]
    //);
    session_destroy();
    $_SESSION = array();
    session_start();
}


//__END__

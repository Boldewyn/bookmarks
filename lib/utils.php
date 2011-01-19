<?php defined('BOOKMARKS') or die('Access denied.');

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

function _e($s) {
    echo __($s);
}

function tpl($file, $ctx=array(), $safe=array()) {
    foreach ($ctx as $k => $v) {
        if (is_string($v) && ! in_array($k, $safe)) {
            $ctx[$k] = h($v);
        }
    }
    $ctx += array(
        'body_id' => 'default',
        'base_path' => h(dirname($_SERVER['PHP_SELF'])).'/',
        'f' => h(v('f')),
    );
    extract($ctx);
    ob_start();
    include("templates/$file.php");
    $result = ob_get_contents();
    ob_end_clean();
    return $result;
}

function login() {
    require_once 'lib/lightopenid/openid.php';

    session_set_cookie_params(60*60*24*BOOKMARKS_STAY_LOGGED_IN);
    session_name('Bookmarks');
    session_start();
    if (! in_array('logged_in', $_SESSION) && OpenID !== 'ASSUME_LOGGED_IN') {
        $openid = new LightOpenID;
        if(!$openid->mode) {
            $openid->identity = OpenID;
            header('Location: ' . $openid->authUrl());
            die('Redirecting');
        } elseif($openid->mode == 'cancel') {
            return __('User has canceled authentication!');
        } elseif (! $openid->validate()) {
            return __('Login was not successful.');
        } else {
            $_SESSION['logged_in'] = True;
        }
    }
    return True;
}


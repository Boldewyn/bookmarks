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

function h_array($a) {
    foreach ($a as $k => $v) {
        if (is_string($v)) {
            $a[$k] = h($v);
        } elseif (is_array($v)) {
            $a[$k] = h_array($v);
        }
    }
    return $a;
}

function __($s) {
    return $s;
}

function _e($s) {
    echo __($s);
}

function tpl($file, $ctx=array(), $safe=array()) {
    foreach ($ctx as $k => $v) {
        if (! in_array($k, $safe)) {
            if (is_string($v)) {
                $ctx[$k] = h($v);
            } elseif (is_array($v)) {
                $ctx[$k] = h_array($v);
            }
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

function get_accept_type($whitelist=array(), $default='html') {
    $type = v('type');
    if (! $type) {
        if (isset($_SERVER['HTTP_ACCEPT'])) {
            $a = $_SERVER['HTTP_ACCEPT'];
            if (strstr($a, 'application/json') !== False) {
                $type = 'json';
            } elseif (strstr($a, 'application/atom+xml') !== False) {
                $type = 'atom';
            } elseif (strstr($a, 'application/rdf+xml') !== False) {
                $type = 'rdf';
            } else {
                $type = 'html';
            }
        } elseif (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strstr($_SERVER['HTTP_X_REQUESTED_WITH'], 'XmlHttpRequest') !== False) {
            $type = 'json';
        } else {
            $type = $default;
        }
    }
    if (count($whitelist) > 0 && ! in_array($type, $whitelist)) {
        $type = $default;
    }
    return $type;
}

function is_bookmarklet() {
    return (isset($_GET['bookmarklet']));
}

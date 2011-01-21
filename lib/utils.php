<?php defined('BOOKMARKS') or die('Access denied.');

/**
 * Get a value: First from POST, then from GET, finally $default
 *
 * @param $s The key to look for
 * @param $default The return value, if nothing is found
 */
function v($s, $default='') {
    if (array_key_exists($s, $_POST)) {
        return trim(preg_replace('/[\p{C}\\\]/u', '', $_POST[$s]));
    } elseif (array_key_exists($s, $_GET)) {
        return trim(preg_replace('/[\p{C}\\\]/u', '', $_GET[$s]));
    } else {
        return $default;
    }
}

/**
 * Escape HTML special characters
 */
function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

/**
 * Escape all HTML specials in an array
 */
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

/**
 * Add a message to the queue
 */
function messages_add($m, $type='info', $safe=False) {
    if (! isset($_SESSION['messages'])) {
        $_SESSION['messsages'] = array();
    }
    $_SESSION['messages'][] = array(
        'type' => $type,
        'message' => $safe? $m : h($m),
    );
}

/**
 * Fetch all messages from the queue and empty it
 */
function messages_get() {
    if (! isset($_SESSION['messages'])) {
        return array();
    }
    $m = $_SESSION['messages'];
    $_SESSION['messages'] = array();
    return $m;
}

/**
 * Check, if there are messages in the queue
 */
function messages_have() {
    if (! isset($_SESSION['messages'])) {
        return 0;
    }
    return count($_SESSION['messages']);
}

/**
 * Poor man's i18n
 *
 * @todo Implement me
 */
function __($s) {
    return $s;
}

/**
 * Shortcut for printing a translated string
 */
function _e($s) {
    echo __($s);
}

/**
 * Render a template
 *
 * Templates are simple PHP files, executed in this semi-controlled
 * environment.
 * @param $file The basename without '.php' of the template
 * @param $ctx The context parameters to pass to the template
 * @param $safe Parameters that should not be HTML-escaped
 */
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

/**
 * Log the user in (requires OpenID)
 */
function login() {
    require_once 'lib/lightopenid/openid.php';
    if (! in_array('logged_in', $_SESSION) && OpenID !== 'ASSUME_LOGGED_IN') {
        $openid = new LightOpenID;
        if(!$openid->mode) {
            $openid->identity = OpenID;
            redirect($openid->authUrl());
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

/**
 * Determine the expected content type for the answer
 */
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

/**
 * Test, if we are called from a bookmarklet
 */
function is_bookmarklet() {
    return (isset($_GET['bookmarklet']));
}

/**
 * Redirect to another URL. Prepend site path, if necessary
 */
function redirect($to) {
    $base_path = dirname($_SERVER['PHP_SELF']);
    if (substr($to, 0, 1) === '/' && substr($to, 0, strlen($base_path)) !== $base_path) {
        $to = "$base_path$to";
    }
    header('Location: '.$to);
    die('Redirecting to ');
}


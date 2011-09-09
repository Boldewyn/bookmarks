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
        'base_path' => h(cfg('base_path')),
        'script_path' => h(get_script_path()),
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
 *
 */
function get_script_path() {
    $base_path = cfg('base_path');
    if (isset($_SERVER['PATH_INFO'])) {
        return $base_path.'index.php/';
    } else {
        return $base_path;
    }
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
    $base_path = get_script_path();
    if (substr($to, 0, 1) === '/' && substr($to, 0, strlen($base_path)) !== $base_path) {
        $to = $base_path . substr($to, 1);
    }
    header('Location: '.$to);
    die('Redirecting to '.h($to));
}


/**
 * update the current URL with new GET params
 */
function update_url($params) {
    $base = $_SERVER['PATH_INFO'];
    if (isset($_SERVER['ORIG_PATH_INFO'])) {
        $base = $_SERVER['ORIG_PATH_INFO'];
    }
    return $base.'?'.http_build_query(array_merge($_GET, $params));
}


//__END__

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
    );
    extract($ctx);
    ob_start();
    include("templates/$file.php");
    $result = ob_get_contents();
    ob_end_clean();
    return $result;
}


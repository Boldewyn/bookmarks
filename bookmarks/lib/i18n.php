<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Translate a string
 *
 * @todo Implement me
 */
function __($s) {
    return _gettext($s);
}


/**
 * Get the translated string
 */
function _gettext($s) {
    static $catalog = NULL;
    if ($catalog === NULL) {
        $f = dirname(__FILE__).'/../i18n/'.cfg('display/lang').'.php';
        if (! is_file($f)) {
            $catalog = array();
        } else {
            require_once $f;
        }
    }
    if (array_key_exists($s, $catalog)) {
        $tmp = $catalog[$s];
        if ($tmp !== '') {
            return $tmp;
        }
    }
    return $s;
}


/**
 * Shortcut for printing a translated string
 */
function _e($s) {
    echo __($s);
}


//__END__

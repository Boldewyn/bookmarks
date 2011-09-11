<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Call a plugin action
 */
function plugin($store) {
    $plugin = v('plugin');
    $function = v('function');
    if ($plugin && is_file(dirname(__FILE__).'/../plugins/'.$plugin.'.php')) {
        require_once dirname(__FILE__).'/../plugins/'.$plugin.'.php';
        if (function_exists($function)) {
            return $function($store);
        }
    }
    header('HTTP/1.0 404 Not Found');
    return tpl('minimal', array('content' => 'Page not found.'));
}


//__END__

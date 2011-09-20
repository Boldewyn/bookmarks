<?php defined('BOOKMARKS') or die('Access denied.');


$_HOOK_REGISTRY = array();


/**
 * Register a function for a hook
 */
function register_for_hook($hookname, $callback) {
    global $_HOOK_REGISTRY;
    if (! array_key_exists($hookname, $_HOOK_REGISTRY)) {
        $_HOOK_REGISTRY[$hookname] = array();
    }
    $_HOOK_REGISTRY[$hookname][] = $callback;
}


/**
 * Fire a hook
 */
function call_hook($hookname, $data=array()) {
    global $_HOOK_REGISTRY;
    $r = True;
    if (array_key_exists($hookname, $_HOOK_REGISTRY)) {
        foreach($_HOOK_REGISTRY[$hookname] as $f) {
            $r = call_user_func_array($f, $data) && $r;
        }
    }
    return $r;
}


/**
 * Load all activated plugins
 */
function load_plugins() {
    foreach (cfg('plugins/active', array()) as $plugin) {
        if (is_file(dirname(__FILE__).'/../plugins/'.$plugin.'.php')) {
            require_once dirname(__FILE__).'/../plugins/'.$plugin.'.php';
        } else {
            require_once dirname(__FILE__).'/../plugins/'.$plugin.'/plugin.php';
        }
        // deliberately fail, if none of the above can be found
    }
}


//__END__

<?php defined('BOOKMARKS') or die('Access denied.');


require_once 'config.php';


/**
 * Get a config setting
 */
function cfg($key, $default=NULL) {
    global $bookmark_config;
    if (strpos($key, '/') !== False) {
        $key = explode('/', $key);
    }
    if (is_array($key)) {
        $tmp = $bookmark_config;
        foreach ($key as $k) {
            if (array_key_exists($k, $tmp)) {
                $tmp = $tmp[$k];
            } else {
                return $default;
            }
        }
        return $tmp;
    } elseif (array_key_exists($key, $bookmark_config)) {
        return $bookmark_config[$key];
    }
    return $default;
}


//__END__

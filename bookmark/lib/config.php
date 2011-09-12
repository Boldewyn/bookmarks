<?php defined('BOOKMARKS') or die('Access denied.');


require_once dirname(__FILE__).'/utils.php';


/**
 * Config storage class
 */
class BookmarkConfig {

    protected static $cfg = NULL;

    public static function get() {
        if (self::$cfg === NULL) {
            self::load();
        }
        return self::$cfg;
    }

    /**
     * Load config
     */
    public static function load($from=NULL) {
        if ($from === NULL) {
            $from = dirname(__FILE__).'/../config.php';
        }
        if (! is_file($from)) {
            if (self::$cfg === NULL) {
                self::$cfg = array();
            }
            return false;
        }
        require_once $from;
        if (! isset($bookmark_config)) {
            if (self::$cfg === NULL) {
                self::$cfg = array();
            }
            return false;
        }
        self::$cfg = $bookmark_config;
        return true;
    }

    /**
     * Set a config value
     */
    public static function set($k, $v=NULL) {
        self::get();
        if (is_array($k)) {
            self::$cfg = array_merge(self::$cfg, $k);
        } else {
            self::$cfg[$k] = $v;
        }
    }

}


/**
 * Get a config setting
 */
function cfg($key, $default=NULL) {
    $bookmark_config = BookmarkConfig::get();
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
    } elseif ($key === 'base_path') {
        return rtrim(dirname($_SERVER['SCRIPT_NAME']), '/').'/';
    }
    return $default;
}


//__END__

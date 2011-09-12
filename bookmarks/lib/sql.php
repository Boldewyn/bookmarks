<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * create a db connection and do preliminary stuff
 */
function get_db() {
    static $db = False;
    if (! $db) {
        $db = new PDO(cfg('database/dsn'),
                        cfg('database/user'),
                        cfg('database/password'));
        if (cfg('debug')) {
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        }
        if ($db->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite') {
            $db->sqliteCreateFunction('regexp', 'sqlite_regexp', 2);
        }
    }
    return $db;
}


/**
 * REGEXP substitute for SQLite
 */
function sqlite_regexp($pattern, $string) {
    return preg_match(sprintf('/%s/', $pattern), $string);
}


/**
 * Get DB driver
 */
function db_type() {
    return get_db()->getAttribute(PDO::ATTR_DRIVER_NAME);
}


/**
 * Get PDO boolean type
 */
function db_bool() {
    switch (db_type()) {
        case 'sqlite':
            return PDO::PARAM_INT;
            break;
        default:
            return PDO::PARAM_BOOL;
    }
}


/**
 * Return a db-specific way of creating a unix timestamp
 */
function unix_timestamp($field) {
    switch (db_type()) {
        case 'sqlite':
            return sprintf('strftime(\'%%s\', %s)', $field);
            break;
        default:
            return sprintf('UNIX_TIMESTAMP(%s)', $field);
    }
}


/**
 * Return db-specific NOW()
 */
function db_now() {
    switch (db_type()) {
        case 'sqlite':
            return 'datetime(\'now\')';
            break;
        default:
            return 'NOW()';
    }
}


/**
 * Return db-specific auto-increment
 */
function auto_increment() {
    switch (db_type()) {
        case 'sqlite':
            return 'AUTOINCREMENT';
            break;
        default:
            return 'AUTO_INCREMENT';
    }
}


//__END__

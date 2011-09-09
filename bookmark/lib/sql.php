<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Return a db-specific way of creating a unix timestamp
 */
function unix_timestamp($field) {
    switch (strtolower(substr(cfg('database/dsn', ''), 0, 6))) {
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
    switch (strtolower(substr(cfg('database/dsn', ''), 0, 6))) {
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
    switch (strtolower(substr(cfg('database/dsn', ''), 0, 6))) {
        case 'sqlite':
            return 'AUTOINCREMENT';
            break;
        default:
            return 'AUTO_INCREMENT';
    }
}


//__END__

<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Install application
 */
function install($store) {
    global $db;
    $status = do_login();
    if ($status !== True) {
        messages_add(__('You need to login for the setup.'), 'error');
        redirect('/login?next=install');
    }
    $query = 'SHOW TABLES LIKE "'.cfg('database/prefix', '').'bookmark%"';
    if (strtolower(substr(cfg('database/dsn', ''), 0, 6)) === 'sqlite') {
        $query = 'SELECT name FROM sqlite_master WHERE type="master" AND name LIKE "'.cfg('database/prefix', '').'bookmark%"';
    }
    $test = $db->query($query);
    $not = array(cfg('database/prefix', '').'bookmarks',
                 cfg('database/prefix', '').'bookmark_tags');
    if ($test !== False) {
        $rows = $test->fetchAll(PDO::FETCH_NUM);
        $test->closeCursor();
        while ($row = next($rows)) {
            if (in_array($row[0], $not)) {
                unset($not[array_search($row[0], $not)]);
            }
        }
        if (count($not) === 2) {
            if ($store->install() === True) {
                messages_add(__('Installation successfully completed.'),
                             'success');
            } else {
                messages_add(__('There was an error trying to install the '.
                                'application.'), 'error');
            }
        } else {
            messages_add(__('There is already an installation or a clash in '.
                            'table names.'), 'error');
        }
    } else {
        messages_add(__('There was an error establishing the database connection.'),
                     'error');
    }
    redirect('/');
}


//__END__

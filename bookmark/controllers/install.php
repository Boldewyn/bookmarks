<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Install application
 */
function install($store) {
    global $db;
    $status = do_login();
    if ($status !== True) {
        messages_add(__('You need to login for this.'), 'error');
        redirect('/login?next=install');
    }
    $test = $db->query('SHOW TABLES LIKE "bookmark%"');
    $not = array('bookmarks', 'bookmark_tags');
    if ($test !== False) {
        while ($row = $test->fetch(PDO::FETCH_NUM)) {
            if (in_array($row[0], $not)) {
                unset($not[array_search($row[0], $not)]);
            }
        }
        if (count($not) === 0) {
            $store->install();
            messages_add(__('Installation successfully completed.'),
                         'success');
        } else {
            messages_add(__('There is already an installation.'), 'error');
        }
    } else {
        messages_add(__('There was an error establishing the database connection.'),
                     'error');
    }
    redirect('/');
}


//__END__

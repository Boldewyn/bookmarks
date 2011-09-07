<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * log in the user
 */
function login($store) {
    $status = do_login();
    if ($status !== True) {
        messages_add(sprintf(__('A login error occurred: %s.'), $status), 'error');
        redirect('/');
    } else {
        messages_add(__('Successfully logged in. Welcome back.'), 'success');
        redirect('/');
    }
    return '';
}


//__END__

<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * log out the user
 */
function logout($store) {
    do_logout();
    messages_add(__('Logged out. See you!'), 'success');
    redirect('/');
    return '';
}


//__END__

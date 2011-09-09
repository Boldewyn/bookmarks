<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Import bookmarks from delicious
 */
function import($store) {
    $status = do_login();
    if ($status !== True) {
        messages_add(__('You need to login for this.'), 'error');
        redirect('/login?next=import');
    }
    call_hook('import', array($store));
    redirect('/');
    return '';
}


//__END__

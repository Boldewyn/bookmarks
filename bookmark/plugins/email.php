<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Share a bookmark via email (step 1: Ask email)
 */
function email_share($service, $bookmark) {
    if ($service !== 'email') {
        return False;
    }
    if (cfg('auth/login_to_share', True) === True && logged_in() !== True) {
        messages_add(__('You need to log in to share a bookmark.'), 'error');
        redirect('/login?next=share?url%3D'.v('url'));
    }
    echo tpl('plugins/email/share', array('bookmark' => $bookmark));
    return True;
}
register_for_hook('share', 'email_share');


/**
 * Share a bookmark via email (step 2: do it)
 */
function email_do_share($store) {
    if (cfg('auth/login_to_share', True) === True && logged_in() !== True) {
        messages_add(__('You need to log in to share a bookmark.'), 'error');
        redirect('/login?next=share?url%3D'.v('url'));
    }
    if (! check_csrf('plugin/email_share', v('ctoken', '', 'post'))) {
        messages_add(__('You cannot share this bookmark without '.
            'confirmation.'), 'error');
        redirect('/');
    }
    $url = v('url', '', 'post');
    if (! $url) {
        messages_add(__('No bookmark given.'), 'error');
        redirect('/');
    }
    $bookmark = $store->fetch(v('url'));
    if (! $bookmark) {
        messages_add(__('No bookmark found.'), 'error');
        redirect('/');
    }
    $email = filter_var(v('email', '', 'post'), FILTER_VALIDATE_EMAIL);
    if (! $email) {
        messages_add(__('No email address given.'), 'error');
        email_share('email', $bookmark);
        exit();
    }
    $message = v('message', '', 'post');
    if ($message) {
        $message .= "\n\n";
    }
    // do the mailing here:
    $additional_headers= 'From: ' . cfg('plugins/email/default',
                                    'info@example.com') . "\r\n" .
                          'X-Mailer: PHP/' . phpversion();
    $status = mail($email,
        sprintf(__('Bookmark recommendation: “%s”'), $bookmark['title']),
        $message.'Bookmark: '.$bookmark['url'].'
You can find more bookmarks at
- http://'.preg_replace('/[^a-zA-Z0-9.-]/', '', $_SERVER['HTTP_HOST']).get_script_path().'

-------------------------------------
Delivered by Bookmarks
Host your own bookmarks:
http://boldewyn.github.com/bookmarks/',
        $additional_headers);
    if ($status === True) {
        messages_add(__('Bookmark successfully shared.'), 'success');
        redirect('/');
    } else {
        messages_add(__('There was an unknown error sending the email.'), 'error');
        email_share('email', $bookmark);
        exit();
    }
}


/**
 * Share a bookmark via email (description)
 */
function email_share_describe() {
    echo ''.
'<dt>'.
  '<input type="radio" name="service" value="email" id="share_email" />'.
  ' <label for="share_email">'.__('Email').'</label>'.
'</dt>'.
'<dd>'.__('Share the bookmark with an email recipient.').'</dd>';
    return True;
}
register_for_hook('share_describe', 'email_share_describe');


//__END__

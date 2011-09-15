<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Share a bookmark on Facebook
 */
function facebook_share($service, $bookmark) {
    if ($service !== 'facebook') {
        return False;
    }
    redirect('http://facebook.com/sharer.php?u='.
        rawurlencode($bookmark['url']) .
        '&t=' .
        rawurlencode($bookmark['title']));
    return True;
}
register_for_hook('share', 'facebook_share');


/**
 * Share a bookmark on Facebook (description)
 */
function facebook_share_describe() {
    echo ''.
'<dt>'.
  '<input type="radio" name="service" value="facebook" id="share_facebook" />'.
  ' <label for="share_facebook">'.__('Facebook').'</label>'.
'</dt>'.
'<dd>'.__('Share the bookmark on Facebook.').'</dd>';
    return True;
}
register_for_hook('share_describe', 'facebook_share_describe');


//__END__

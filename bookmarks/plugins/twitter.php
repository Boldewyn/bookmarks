<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Share a bookmark on Twitter
 */
function twitter_share($service, $bookmark) {
    if ($service !== 'twitter') {
        return False;
    }
    redirect('http://twitter.com/home?status='.
        rawurlencode($bookmark['title']) . ' - ' .
        rawurlencode(get_url().'-'.$bookmark['shortcut']));
    return True;
}
register_for_hook('share', 'twitter_share');


/**
 * Share a bookmark on Twitter (description)
 */
function twitter_share_describe() {
    echo ''.
'<dt>'.
  '<input type="radio" name="service" value="twitter" id="share_twitter" />'.
  ' <label for="share_twitter">'.__('Twitter').'</label>'.
'</dt>'.
'<dd>'.__('Share the bookmark on Twitter.').'</dd>';
    return True;
}
register_for_hook('share_describe', 'twitter_share_describe');


//__END__

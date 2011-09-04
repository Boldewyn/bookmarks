<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Show on-line help
 */
function help($store) {
    return tpl('help', array('body_id' => 'help',
               'site_title' => __('Help')));
}


//__END__

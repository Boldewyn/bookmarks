<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Search for bookmarks
 */
function search($store) {
    if (v('q', '') === '') {
        $r = tpl('search', array('body_id' => 'search',
                                 'site_title' => __('Search')));
    } else {
        $page = v('page');
        if (! ctype_digit($page)) {
            $page = 1;
        } else {
            $page = (int)$page;
        }
        $bookmarks = $store->search(explode(' ', v('q')), ($page-1)*cfg('display/pagination', 100),
                                    cfg('display/pagination', 100));
        if (count($bookmarks) === 0) {
            messages_add(__('There is no match for your query.'));
            $r = tpl('search', array('body_id' => 'search',
                                     'site_title' => __('Search')));
        } else {
            messages_add(sprintf(__('Your search for “%s” yields %s results.'),
                         v('q'), count($bookmarks)), 'success');
            $r = tpl('search', array('body_id' => 'search',
                                   'site_title' => __('Search'),
                                   'page' => $page,
                                   'bookmarks' => $bookmarks));
        }
    }
    return $r;
}


//__END__

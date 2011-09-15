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
        $count = $store->count_search(explode(' ', v('q')));
        if ($count === 0) {
            messages_add(__('There is no match for your query.'));
            $r = tpl('search', array('body_id' => 'search',
                                     'site_title' => __('Search')));
        } else {
            $bookmarks = $store->search(explode(' ', v('q')), ($page-1)*cfg('display/pagination', 100),
                                    cfg('display/pagination', 100));
            messages_add(sprintf(__('Your search for “%s” yields %s results.'),
                         v('q'), $count), 'success');
            $r = tpl('search', array('body_id' => 'search',
                                   'site_title' => __('Search'),
                                   'page' => $page,
                                   'pages' => (int)ceil((float)$count/(float)cfg('display/pagination', 100)),
                                   'all' => $count,
                                   'bookmarks' => $bookmarks));
        }
    }
    return $r;
}


//__END__

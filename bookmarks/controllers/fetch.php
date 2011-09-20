<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Fetch bookmarks from the database
 */
function fetch($store, $tags='') {
    $tags = array_filter(array_map('trim', explode(' ', $tags)));
    $page = v('page');
    if (! ctype_digit($page)) {
        $page = 1;
    } else {
        $page = (int)$page;
    }
    $bookmarks = $store->fetch_all($tags, ($page-1)*cfg('display/pagination', 100),
                                   cfg('display/pagination', 100));
    $all = $store->fetch_all($tags, 'count');
    if ($bookmarks === Null) {
        $bookmarks = array();
    }

    $html = '';
    switch (get_accept_type()) {
        case 'json':
            header('Content-Type: application/json');
            $html = json_encode($bookmarks);
            break;
        case 'atom':
            header('Content-Type: application/atom+xml');
            $html = tpl('atom.xml', array('bookmarks' => $bookmarks));
            break;
        case 'rdf':
            header('Content-Type: application/rdf+xml');
            $html = tpl('rdf.xml', array('bookmarks' => $bookmarks));
            break;
        default:
            $html = tpl('fetch', array('body_id' => 'index',
                'site_title' => count($tags)? sprintf(__('Bookmarks Tagged “%s”'), join(' ', $tags)) : __('All Bookmarks'),
                'tags' => $tags,
                'tagcloud' => weight_tagcloud($store->fetch_top_tags()),
                'page' => $page,
                'pages' => (int)ceil((float)$all/(float)cfg('display/pagination', 100)),
                'all' => $all,
                'bookmarks' => $bookmarks));
            break;
    }
    return $html;
}


//__END__

<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Provide an API to the world
 *
 * We try to maintain consistency with the Delicious API
 */
function api($store) {
    header('Content-Type: text/xml; charset=UTF-8');
    if (! cfg('api/enabled', False) || ! isset($_GET['_info'])) {
        _set_api_status(404);
        exit();
    }
    if (! isset($_SERVER['HTTP_AUTHORIZATION'])) {
        _set_api_status(403);
        exit();
    } elseif (base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)) !==
              cfg('api/key')) {
        _set_api_status(403);
        exit();
    }
    $info = explode('/', $_GET['_info']);
    if (count($info) < 3 || array_shift($info) !== 'v1') {
        _set_api_status(404);
        exit();
    }
    $method = array_shift($info);
    if (! function_exists('_api_'.$method)) {
        _set_api_status(404);
        exit();
    } else {
        return call_user_func('_api_'.$method, $info, $store);
    }
}


/**
 * Posts-related API calls
 */
function _api_posts($info, $store) {
    if (count($info) !== 1) {
        _set_api_status(404);
        exit();
    }
    switch ($info[0]) {
        case 'update':
            $ts = $store->fetch_newest();
            return '<update time="'.date('c', $ts).'" inboxnew="0" />';
            break;
        case 'add':
            $url = v('url', '');
            $description = v('description', '');
            $extended = v('extended', '');
            $tags = array_filter(array_map('trim',
                        explode(' ', v('tags', ''))));
            $dt = v('dt', date('c'));
            $replace = v('replace', 'no') === 'yes'? true : false;
            $shared = v('shared', 'no') === 'yes'? true : false;
            if (! $url || ! $description ||
                ! preg_match('/^[0-9]{4}-[01][0-9]-[0-3][0-9]T[0-2][0-9]:[0-5][0-9]:[0-5][0-9]Z$/', $dt)) {
                return '<result code="something went wrong" />';
            }
            $e = $store->save($url, $description, $tags, $extended, !$shared, $dt);
            if ($e === True) {
                call_hook('save', array($url, $description, $tags, $extended, !$shared));
            } elseif ($e === Null && $replace) {
                $e = $store->change($url, $description, $tags, $extended, !$shared, $dt);
                call_hook('change', array($url, $description, $tags, $extended, !$shared));
            }
            if (! $e) {
                return '<result code="something went wrong" />';
            } else {
                return '<result code="done" />';
            }
            break;
        case 'delete':
            $store->delete(v('url', ''));
            return '<result code="done" />';
            break;
        case "get":
            # TODO IMPLEMENT ME!
            break;
        case 'recent':
            # TODO IMPLEMENT ME!
            break;
        case 'dates':
            # TODO IMPLEMENT ME!
            break;
        case 'all':
            # TODO IMPLEMENT ME!
            break;
        case 'suggest':
            # TODO IMPLEMENT ME!
            $url = v('url', '');
            break;
        default:
            _set_api_status(404);
            exit();
    }
}


/**
 * Tags-related API calls
 */
function _api_tags($info, $store) {
    if (! in_array(count($info), array(1, 2))) {
        _set_api_status(404);
        exit();
    }
    switch ($info[0]) {
        case 'get':
            # TODO IMPLEMENT ME!
            break;
        case 'delete':
            # TODO IMPLEMENT ME!
            break;
        case 'rename':
            # TODO IMPLEMENT ME!
            break;
        case 'bundles':
            # TODO IMPLEMENT ME!
            break;
        default:
            _set_api_status(404);
            exit();
    }
}


/**
 * Set some regularly used stati
 */
function _set_api_status($state=404) {
    switch ($state) {
        case 403:
            header('HTTP/1.0 403 Forbidden');
            echo '<result code="access denied" />';
            break;
        case 404:
        default:
            header('HTTP/1.0 404 Not Found');
            header('Status: 404 Not Found');
            echo '<result code="invalid api" />';
    }
}


//__END__

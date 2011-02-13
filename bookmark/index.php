<?php define('BOOKMARKS', '0.9');

require_once 'fetch.php';
require_once 'config.php';
require_once 'lib/utils.php';
require_once 'lib/bookmarks.class.php';

session_set_cookie_params(60*60*24*BOOKMARKS_STAY_LOGGED_IN);
session_name('Bookmarks');
session_start();

$db = new PDO(DB_DSN, DB_USER, DB_PWD);
$store = new Bookmarks($db, (in_array('logged_in', $_SESSION)));

$f = v('f', '');
if ($f === '') {
    echo fetch($store);
} elseif ($f === 'save') {
    require_once 'save.php';
    echo save($store);
} elseif (substr($f, 0, 5) === "tags/") {
    $tags = v('tags');
    if (! $tags) {
        $tags = substr($f, 5);
    }
    echo fetch($store, $tags);
} elseif (substr($f, 0, 8) === "all_tags") {
    $prefix = substr($f, 9);
    if ($prefix === False) { $prefix = ''; }
    $tags = $store->fetch_all_tags($prefix);
    header('Content-Type: application/json');
    die(json_encode($tags));
} elseif ($f === 'login') {
    $status = login();
    if ($status !== True) {
        messages_add(sprintf(__('A login error occurred: %s.'), $status), 'error');
        redirect('/?from=login');
    } else {
        messages_add(sprintf(__('Successfully logged in. Welcome back.'), $status), 'success');
        redirect('/?from=login');
    }
} elseif ($f === 'logout') {
    $_SESSION = array();
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
    session_destroy();
    redirect('/?from=logout');
} elseif ($f === 'help') {
    echo tpl('help', array('body_id' => 'help',
        'site_title' => __('Help')));
} elseif ($f === 'search') {
    if (v('q', '') === '') {
        echo tpl('search',  array('body_id' => 'search',
            'site_title' => __('Search')));
    } else {
        $bookmarks = $store->search(explode(' ', v('q')));
        if (count($bookmarks) === 0) {
            messages_add(__('There is no match for your query.'));
            echo tpl('search',  array('body_id' => 'search',
                'site_title' => __('Search')));
        } else {
            messages_add(sprintf(__('Your search for %s yields %s results.'), v('q'), count($bookmarks)), 'success');
            echo tpl('list',  array('body_id' => 'search',
                'site_title' => __('Search'),
                'bookmarks' => $bookmarks));
        }
    }
} elseif ($f === 'import') {
    $status = login();
    if ($status !== True) {
        messages_add(__('You need to login for this.'), 'error');
        redirect('/login?from=import');
    }
    if (defined('DELICIOUS_AUTH')) {
        $fp = fsockopen("ssl://api.delicious.com", 443);
        if (!$fp) {
            messages_add(__('Couldn’t connect to the delicious API server.'), 'error');
            header('HTTP/1.0 500 Internal Server Error');
            echo fetch($store);
        } else {
            $out = "GET /v1/posts/all HTTP/1.1\r\n";
            $out .= "Host: api.delicious.com\r\n";
            $out .= "User-Agent: Personal Bookmarks Manager\r\n";
            $out .= "Authorization: Basic ".DELICIOUS_AUTH."\r\n";
            $out .= "Connection: Close\r\n\r\n";
            fwrite($fp, $out);
            $data = "";
            while (!feof($fp)) {
                $data .= fgets($fp, 128);
            }
            fclose($fp);
            $data = explode("\n\n", str_replace("\r\n", "\n", $data), 2);
            $data = $data[1];
            try {
                $xml = simplexml_load_string($data);
                if (! $xml) {
                    throw new Exception();
                }
                $posts = $xml->xpath('/posts/post');
            } catch (Exception $e) {
                messages_add(__('Couldn’t parse the response from Delicious.'), 'error');
                redirect('/');
            }
            $added = 0;
            $needs_update = 0;
            for ($i = 0; $i < count($posts); $i++) {
                $atts = array();
                foreach ($posts[$i]->attributes() as $k => $v) {
                    $atts[$k] = trim($v);
                }
                $atts += array("extended"=>"","shared"=>"yes");
                $r = $store->save(
                    $atts['href'],
                    $atts['description'],
                    explode(" ", $atts['tag']),
                    $atts['extended'],
                    ($atts['shared'] === 'no')
                );
                if ($r === True) {
                    $added++;
                } elseif ($r === Null) {
                    $needs_update++;
                }
            }
            messages_add(sprintf(__('Added %s new bookmarks.'), $added), 'success');
            if ($needs_update > 0) {
                messages_add(sprintf(__('%s bookmarks were already imported.'), $needs_update), 'info');
            }
            if (count($posts) > $added + $needs_update) {
                messages_add(sprintf(__('%s bookmarks couldn’t be imported.'), count($posts) - $added -$needs_update), 'error');
            }
            redirect('/');
        }
    } else {
        messages_add(__('You need to provide your access data for the delicious API server.'), 'error');
        header('HTTP/1.0 500 Internal Server Error');
        echo fetch($store);
    }
} elseif ($f === 'install') {
    $status = login();
    if ($status !== True) {
        messages_add(__('You need to login for this.'), 'error');
        redirect('/login?from=install');
    }
    $test = $db->query('SHOW TABLES LIKE "bookmark%"');
    $not = array('bookmarks', 'bookmark_tags');
    if ($test !== False) {
        while ($row = $test->fetch(PDO::FETCH_NUM)) {
            if (in_array($row[0], $not)) {
                unset($not[array_search($row[0], $not)]);
            }
        }
        if (count($not) === 0) {
            $store->install();
            messages_add(__('Installation successfully completed.'), 'success');
        } else {
            messages_add(__('There is already an installation.'), 'error');
        }
    } else {
        messages_add(__('There was an error establishing the database connection.'), 'error');
    }
    echo fetch($store);
} else {
    messages_add(sprintf(__('The site %s couldn’t be found.'), '<var>'.h(urlencode($f)).'</var>'),
        'error', True);
    header('HTTP/1.0 404 Not Found');
    echo fetch($store);
}

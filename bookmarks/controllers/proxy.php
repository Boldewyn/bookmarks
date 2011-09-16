<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Fetch bookmarks from the database
 */
function proxy($store) {
    /* Authentication */
    if (logged_in() !== True) {
        messages_add(__('You need to log in to access this proxy.'), 'error');
        redirect('/login?next=proxy?url='.rawurlencode(v('url', '')));
    }
    /* Main logic */
    $url = v('url', NULL);
    if (! $url) {
        header('HTTP/1.0 400 Bad Request');
        return 'URL missing.';
    }
    $header = array();
    foreach(array('ACCEPT', 'ACCEPT_CHARSET', 'ACCEPT_ENCODING',
                  'ACCEPT_LANGUAGE') as $h) {
        if (isset($_SERVER['HTTP_'.$h])) {
            $header[] = str_replace('_', '-', $h).': '.$_SERVER['HTTP_'.$h];
        }
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, (isset($_SERVER['HTTP_USER_AGENT'])?
        $_SERVER['HTTP_USER_AGENT'] :
        'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 4);
    $data = curl_exec();
    if ($data === False) {
        header('HTTP/1.0 404 Not Found');
        return 'Couldn\'t fetch resource';
    }
    if (strpos($data, "\r\n\r\n") !== False) {
        list($header, $body) = explode("\r\n\r\n", $data, 2);
        $header = explode("\r\n", $header);
    } elseif (strpos($data, "\n\n") !== False) {
        list($header, $body) = explode("\n\n", $data, 2);
        $header = explode("\n", $header);
    } else {
        $header = array_map('trim', explode("\n", $data));
        $body = '';
    }
    foreach ($header as $h) {
        list($k, $v) = array_map('trim', explode(':', $h, 2));
        switch (strtoupper($k)) {
        case 'CONTENT-ENCODING':
        case 'CONTENT-LANGUAGE':
        case 'CONTENT-TYPE':
        case 'LAST-MODIFIED':
            header($h);
            break;
        }
    }
    return $body;
}


//__END__

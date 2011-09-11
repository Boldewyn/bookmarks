<?php defined('BOOKMARKS') or die('Access denied.');


/**
 * Start a session
 */
function start_session() {
    session_set_cookie_params(60*60*24*cfg('session/days', 1),
        cfg('base_path', '/'), get_host(),
        cfg('session/secure', false), true);
    session_name('Bookmarks');
    session_start();
}


/**
 * Add a message to the queue
 */
function messages_add($m, $type='info', $safe=False) {
    if (! isset($_SESSION['messages'])) {
        $_SESSION['messages'] = array();
    }
    $_SESSION['messages'][] = array(
        'type' => $type,
        'message' => $safe? $m : h($m),
    );
}


/**
 * Fetch all messages from the queue and empty it
 */
function messages_get() {
    if (! isset($_SESSION['messages'])) {
        return array();
    }
    $m = $_SESSION['messages'];
    $_SESSION['messages'] = array();
    return $m;
}


/**
 * Check, if there are messages in the queue
 */
function messages_have() {
    if (! isset($_SESSION['messages'])) {
        return 0;
    }
    return count($_SESSION['messages']);
}


//__END__

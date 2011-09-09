<?php


$bookmark_config = array(
    'database' => array(
        'dsn' => 'mysql:host=127.0.0.1;port=3306;dbname=bookmarks',
                                // database definition
        'user' => 'root',       // your database username
        'password' => 'root',   // your database password
        'prefix' => '',         // a prefix to the bookmark tables
    ),
    'auth' => array(
        'openid' => '',         // OpenID you want to use for login
    ),
    'display' => array(
        'pagination' => 100,    // pagination length
    ),
    'session' => array(
        'days' => 365,          // Session length before login needs renewal
        'secure' => false,      // Set this to true, if you use HTTPS
    ),
    'plugins' => array(
        'active' => array(      // active plugins
            'delicious',        // allows syncing with Delicious
        ),
        'delicious' => array(   // Interact with Delicious:
            'auth' => '',       // The Delicious auth string. Set it to
                                // base64_encode('username:password')
            'sync' => True,     // whether newly created bookmarks should be
                                // synced with Delicious
        ),
    ),
);



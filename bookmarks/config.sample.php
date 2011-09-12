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
        'login_to_share' => True,
                                // whether only the logged-in user may share
    ),
    'display' => array(
        'pagination' => 100,    // pagination length
        'title' => 'Bookmarks', // site title
    ),
    'session' => array(
        'days' => 365,          // Session length before login needs renewal
        'secure' => false,      // Set this to true, if you use HTTPS
    ),
    'plugins' => array(
        'active' => array(      // active plugins
            'delicious',        // allows syncing with Delicious
            #'pinboard',        // allows syncing with Pinboard.in (THIS CODE
                                // IS UNTESTED!)
            #'email',            // allows sharing via emails
            #'twitter',          // allows sharing via Twitter
        ),
        'delicious' => array(   // Interact with Delicious:
            'auth' => '',       // The Delicious auth string. Set it to
                                // base64_encode('username:password')
            'sync' => False,    // whether newly created or deleted bookmarks
                                // should be synced with Delicious
        ),
        'pinboard' => array(    // Interact with Pinboard:
            'auth' => '',       // The Pinboard auth string. Set it to
                                // base64_encode('username:password')
            'sync' => False,    // whether newly created or deleted bookmarks
                                // should be synced with Pinboard
        ),
        'email' => array(       // Share bookmarks via email
            'default' => 'info@example.com',
                                // Email to send a shared link from. It's
                                // important to change this to a valid address
                                // or else spam filters will just swallow
                                // the emails
        ),
    ),
);



<?php

$catalog = array(
#: index.php:48
#, php-format
"The site %s couldn’t be found." => "",

#: controllers/delete.php:10
"You need to log in to delete a bookmark." => "",

#: controllers/delete.php:18
"Delete Bookmark" => "",

#: controllers/delete.php:23
"You cannot delete this bookmark without confirmation." => "",

#: controllers/delete.php:30
"Bookmark deleted." => "",

#: controllers/delete.php:37
"There was an error deleting this bookmark." => "",

#: controllers/delete.php:42
"No bookmark found to delete." => "",

#: controllers/fetch.php:38
#, php-format
"Bookmarks Tagged “%s”" => "",

#: controllers/fetch.php:38
"All Bookmarks" => "",

#: controllers/help.php:9 templates/header.php:27
"Help" => "",

#: controllers/import.php:9
"You need to login for this." => "",

#: controllers/install.php:9
"You need to login for the setup." => "",

#: controllers/install.php:29
"Installation successfully completed." => "",

#: controllers/install.php:32
"There was an error trying to install the application." => "",

#: controllers/install.php:36
"There is already an installation or a clash in table names." => "",

#: controllers/install.php:40
"There was an error establishing the database connection." => "",

#: controllers/login.php:10
#, php-format
"A login error occurred: %s." => "",

#: controllers/login.php:13
"Successfully logged in. Welcome back." => "",

#: controllers/logout.php:9
"Logged out. See you!" => "",

#: controllers/save.php:10
"You need to log in to save a bookmark." => "",

#: controllers/save.php:22
"This bookmark already exists." => "",

#: controllers/save.php:39
"You cannot save this bookmark without confirmation." => "",

#: controllers/save.php:43
"This doesn’t seem to be a valid URL." => "",

#: controllers/save.php:55
#, php-format
"An error occurred: %s" => "",

#: controllers/save.php:61 controllers/save.php:64
"Successfully saved bookmark." => "",

#: controllers/save.php:80
"Save Bookmark" => "",

#: controllers/save.php:90
"Save" => "",

#: controllers/save.php:92
"Edit Bookmark" => "",

#: controllers/save.php:94
"Change" => "",

#: controllers/search.php:10 controllers/search.php:22
#: controllers/search.php:29 templates/search.php:9
"Search" => "",

#: controllers/search.php:20
"There is no match for your query." => "",

#: controllers/search.php:26
#, php-format
"Your search for “%s” yields %s results." => "",

#: controllers/share.php:9 plugins/email.php:22
"You need to log in to share a bookmark." => "",

#: controllers/share.php:15
"No URL to share given." => "",

#: controllers/share.php:20
"This URL is not bookmarked and cannot be shared." => "",

#: controllers/share.php:28
"This is a private bookmark. Do you really want to share it?" => "",

#: controllers/share.php:32
"Share Bookmark" => "",

#: controllers/share.php:38 plugins/email.php:26
"You cannot share this bookmark without confirmation." => "",

#: controllers/shortcut.php:9
"No shortcut given." => "",

#: controllers/shortcut.php:14
"This shortcut doesn’t exist." => "",

#: controllers/tags.php:14 templates/tags.php:4
"All Tags" => "",

#: lib/auth.php:15
"User has canceled authentication" => "",

#: lib/auth.php:17
"Login was not successful" => "",

#: lib/utils.php:85 lib/utils.php:86
"Bookmarks" => "",

#: templates/aside.php:2
"Top Tags" => "",

#: templates/aside.php:2
"(show all tags)" => "",

#: templates/delete.php:4
"Do you really want to delete this bookmark?" => "",

#: templates/delete.php:10
"Delete" => "",

#: templates/delete.php:11 templates/save.php:29 templates/search.php:10
#: templates/share.php:16 plugins/email/share.php:17
"Cancel" => "",

#: templates/detail.php:3
"Title:" => "",

#: templates/detail.php:4
"URL:" => "",

#: templates/detail.php:6
"Description:" => "",

#: templates/detail.php:9
"Tags:" => "",

#: templates/detail.php:13
"Short Link:" => "",

#: templates/fetch.php:5
"remove this tag" => "",

#: templates/footer.php:3
"Feed" => "",

#: templates/header.php:26
"Log out" => "",

#: templates/header.php:29
"Log in" => "",

#: templates/header.php:33
"Overview" => "",

#: templates/header.php:35
"Edit" => "",

#: templates/header.php:35
"Create new" => "",

#: templates/header.php:36
"Import" => "",

#: templates/help.php:3
"This site is a bookmarking service. Differently to other similar \nservices out there however it is hosted completely under your control." => "",

#: templates/help.php:5
"Authentication" => "",

#: templates/help.php:6
#, php-format
"Bookmarks uses %s. This means, you can use your accounts \nfrom Google, Yahoo!, flickr, Wordpress.com and many more. You can find more \ninformation on OpenId on %s." => "",

#: templates/help.php:10
"If you have your OpenID provided in your config file, you can \nsimply click “Log In” in the upper right corner and start bookmarking." => "",

#: templates/help.php:12
"Import from Other Services" => "",

#: templates/help.php:13
"The interaction with other services is controlled by plugins, \nthat are enabled in the site’s configuration file. Bookmarks ships with \nsupport for Delicious and Pinboard.in." => "",

#: templates/help.php:16
#, php-format
" You have to place your login credentials in the config \nfile, then you can simply visit %s and all your bookmarks are automatically \nimported from these services." => "",

#: templates/help.php:19
"this page" => "",

#: templates/help.php:20
"Adding a Bookmark" => "",

#: templates/help.php:21
#, php-format
"Other than importing you can create a bookmark directly by \npressing the “%s” link in the top bar. You find a form, where you can \nenter the URL, title and a custom description. Furthermore you can add tags to \ncategorize your bookmarks." => "",

#: templates/help.php:25
"Create New" => "",

#: templates/help.php:26
"When you save the bookmark, and when you have configured the \nplugins accordingly, the freshly saved bookmark will also be put to all the \nservices, that are connected with Bookmarks (<i>e.g.</i>, Delicious)." => "",

#: templates/help.php:29
"Saving from the Browser" => "",

#: templates/help.php:30
#, php-format
"To save comfortably from within your browser, use a \nbookmarklet to open the save form quickly. You can either re-use your existing \nDelicious bookmarklet, when you edit it and change “http://delicious.com/save” \nto “%ssave”, or simply drag and drop this link to your bookmarks bar: %s." => "",

#: templates/help.php:41 templates/help.php:42
"Bookmark on Bookmarks" => "",

#: templates/help.php:43
"Editing and Deleting" => "",

#: templates/help.php:44
"If you look at the list of bookmarks, you see that every bookmark \nhas and “edit”, an “delete” and an “share” link. These do exactly what you \nwould assume." => "",

#: templates/help.php:47
"Edit:" => "",

#: templates/help.php:47
"Clicking this allows to edit \na bookmark. You will see the same form as when you saved it the first time." => "",

#: templates/help.php:50
"Delete:" => "",

#: templates/help.php:50
"Delete a bookmark. You will \nsee a confirmation screen, before the action really hits your data." => "",

#: templates/help.php:52
"Share:" => "",

#: templates/help.php:52
"This offers several services \nto share, post or publish your saved links, <i>e.g.</i> Facebook, Twitter or to \nsend it via e-mail." => "",

#: templates/help.php:56
"You have spotted an error in the Bookmarks application? There are \nthings unclear, or you don’t know what to do in a certain situation?" => "",

#: templates/help.php:58
#, php-format
"Fortunately Bookmarks is an Open Source program. Head over \nto %s, where you can find additional information, an issue tracker, a \ndocumentation wiki and ways to contact the authors of Bookmarks." => "",

#: templates/list.php:5
"j M y" => "",

#: templates/list.php:21
"edit" => "",

#: templates/list.php:22
"delete" => "",

#: templates/list.php:24
"share" => "",

#: templates/pagination.php:2
#, php-format
"%d bookmarks" => "",

#: templates/pagination.php:6
"Previous" => "",

#: templates/pagination.php:8
"First" => "",

#: templates/pagination.php:17
"Last" => "",

#: templates/pagination.php:19
"Next" => "",

#: templates/rdf.xml.php:5
"Bookmark feed" => "",

#: templates/save.php:6
"URL" => "",

#: templates/save.php:10
"Title" => "",

#: templates/save.php:14
"Tags" => "",

#: templates/save.php:18
"Notes" => "",

#: templates/save.php:23
"Private" => "",

#: templates/searchform.php:3 templates/search.php:5
"Search terms" => "",

#: templates/searchform.php:5
"search" => "",

#: templates/share.php:4
"How do you want to share this bookmark?" => "",

#: templates/share.php:15 plugins/email/share.php:16
"Share" => "",

#: templates/tags.php:8
"Top 100 Tags" => "",

#: plugins/delicious.php:21 plugins/delicious.php:49
"Couldn’t connect to the Delicious API server to sync bookmarks." => "",

#: plugins/delicious.php:25
"The bookmark was exported to Delicious." => "",

#: plugins/delicious.php:28
#, php-format
"There was an error exporting the bookmark to Delicious: %s" => "",

#: plugins/delicious.php:32 plugins/delicious.php:60
"You need to provide your access data for the Delicious API server to sync bookmarks." => "",

#: plugins/delicious.php:53
"The bookmark was deleted from Delicious." => "",

#: plugins/delicious.php:56
#, php-format
"There was an error deleting the bookmark from Delicious: %s" => "",

#: plugins/delicious.php:75
"Couldn’t connect to the Delicious API server." => "",

#: plugins/delicious.php:87
"Couldn’t parse the response from Delicious." => "",

#: plugins/delicious.php:114
#, php-format
"Added %s new bookmarks from Delicious." => "",

#: plugins/delicious.php:118
#, php-format
"%s bookmarks were already imported from Delicious." => "",

#: plugins/delicious.php:122
#, php-format
"%s bookmarks could not be imported from Delicious." => "",

#: plugins/delicious.php:129
"You need to provide your access data for the Delicious API server." => "",

#: plugins/email/share.php:4
"Please enter the email address to share with:" => "",

#: plugins/email/share.php:6
"E-Mail:" => "",

#: plugins/email/share.php:10
"Message (optional):" => "",

#: plugins/email.php:32
"No bookmark given." => "",

#: plugins/email.php:37
"No bookmark found." => "",

#: plugins/email.php:42
"No email address given." => "",

#: plugins/email.php:55
#, php-format
"Bookmark recommendation: “%s”" => "",

#: plugins/email.php:65
"Bookmark successfully shared." => "",

#: plugins/email.php:68
"There was an unknown error sending the email." => "",

#: plugins/email.php:82
"Email" => "",

#: plugins/email.php:84
"Share the bookmark with an email recipient." => "",

#: plugins/facebook.php:27
"Facebook" => "",

#: plugins/facebook.php:29
"Share the bookmark on Facebook." => "",

#: plugins/pinboard.php:27 plugins/pinboard.php:55
"Couldn’t connect to the Pinboard API server to sync bookmarks." => "",

#: plugins/pinboard.php:31
"The bookmark was exported to Pinboard." => "",

#: plugins/pinboard.php:34
#, php-format
"There was an error exporting the bookmark to Pinboard: %s" => "",

#: plugins/pinboard.php:38 plugins/pinboard.php:66
"You need to provide your access data for the Pinboard API server to sync bookmarks." => "",

#: plugins/pinboard.php:59
"The bookmark was deleted from Pinboard." => "",

#: plugins/pinboard.php:62
#, php-format
"There was an error deleting the bookmark from Pinboard: %s" => "",

#: plugins/pinboard.php:81
"Couldn’t connect to the Pinboard API server." => "",

#: plugins/pinboard.php:93
"Couldn’t parse the response from Pinboard." => "",

#: plugins/pinboard.php:120
#, php-format
"Added %s new bookmarks from Pinboard." => "",

#: plugins/pinboard.php:124
#, php-format
"%s bookmarks were already imported from Pinboard." => "",

#: plugins/pinboard.php:128
#, php-format
"%s bookmarks could not be imported from Pinboard." => "",

#: plugins/pinboard.php:135
"You need to provide your access data for the Pinboard API server." => "",

#: plugins/random.php:20
"Random" => "",

#: plugins/twitter.php:26
"Twitter" => "",

#: plugins/twitter.php:28
"Share the bookmark on Twitter." => "",
);


//__END__

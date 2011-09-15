<?php include "header.php" ?>
<h1><?php echo $site_title?></h1>
<p><?php _e('This site is a bookmarking service. Differently to other similar 
services out there however it is hosted completely under your control.')?></p>
<h2><?php _e('Authentication')?></h2>
<p><?php printf(__('Bookmarks uses %s. This means, you can use your accounts 
from Google, Yahoo!, flickr, Wordpress.com and many more. You can find more 
information on OpenId on %s.'), '<a href="http://openid.net/">OpenID</a>', 
'<a href="http://openidexplained.com/">OpenIDExplained.com</a>')?></p>
<p><?php _e('If you have your OpenID provided in your config file, you can 
simply click “Log In” in the upper right corner and start bookmarking.')?></p>
<h2><?php _e('Import from Other Services')?></h2>
<p><?php _e('The interaction with other services is controlled by plugins, 
that are enabled in the site’s configuration file. Bookmarks ships with 
support for Delicious and Pinboard.in.')?></p>
<p><?php printf(__(' You have to place your login credentials in the config 
file, then you can simply visit %s and all your bookmarks are automatically 
imported from these services.'), '<a href="'.$script_path.'import">'.
__('this page').'</a>')?></p>
<h2><?php _e('Adding a Bookmark')?></h2>
<p><?php printf(__('Other than importing you can create a bookmark directly by 
pressing the “%s” link in the top bar. You find a form, where you can 
enter the URL, title and a custom description. Furthermore you can add tags to 
categorize your bookmarks.'), '<a href="'.$script_path.'save">'.
__('Create New').'</a>')?></p>
<p><?php _e('When you save the bookmark, and when you have configured the 
plugins accordingly, the freshly saved bookmark will also be put to all the 
services, that are connected with Bookmarks (<i>e.g.</i>, Delicious).')?></p>
<h3><?php _e('Saving from the Browser')?></h3>
<p><?php printf(__('To save comfortably from within your browser, use a 
bookmarklet to open the save form quickly. You can either re-use your existing 
Delicious bookmarklet, when you edit it and change “http://delicious.com/save” 
to “%ssave”, or simply drag and drop this link to your bookmarks bar: %s.'), 
get_url(), '<a href="javascript:(function(a){a=function(){window.open(\''.
get_url().'save?url=\'+encodeURIComponent(location.href)+\'&amp;title=\'+'.
'encodeURIComponent(document.title)+\'&amp;notes=\'+encodeURIComponent(\'\'+'.
'(window.getSelection?window.getSelection():document.getSelection?documenta'.
'.getSelection():document.selection.createRange().text))+\'&noui=1\', '.
'\'bookmarksui\', \'location=yes,links=no,scrollbars=no,toolbar=no,width=550,'.
'height=550\');};if(/Firefox/.test(navigator.userAgent)){setTimeout(a,0);}'.
'else{a();}})()" title="'.__('Bookmark on Bookmarks').'">'.
__('Bookmark on Bookmarks').'</a>')?></p>
<h2><?php _e('Editing and Deleting')?></h2>
<p><?php _e('If you look at the list of bookmarks, you see that every bookmark 
has and “edit”, an “delete” and an “share” link. These do exactly what you 
would assume.')?></p>
<p><strong><?php _e('Edit:')?></strong> <?php _e('Clicking this allows to edit 
a bookmark. You will see the same form as when you saved it the first time.')
?></p>
<p><strong><?php _e('Delete:')?></strong> <?php _e('Delete a bookmark. You will 
see a confirmation screen, before the action really hits your data.')?></p>
<p><strong><?php _e('Share:')?></strong> <?php _e('This offers several services 
to share, post or publish your saved links, <i>e.g.</i> Facebook, Twitter or to 
send it via e-mail.')?></p>
<h2><?php _E('Questions or Problems?')?></h2>
<p><?php _e('You have spotted an error in the Bookmarks application? There are 
things unclear, or you don’t know what to do in a certain situation?')?></p>
<p><?php printf(__('Fortunately Bookmarks is an Open Source program. Head over 
to %s, where you can find additional information, an issue tracker, a 
documentation wiki and ways to contact the authors of Bookmarks.'),
'<a href="http://github.com/Boldewyn/bookmarks">GitHub.com/Boldewyn/bookmarks</a>')?></p>
<?php call_hook('front_help')?>
<?php include "footer.php" ?>

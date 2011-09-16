<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="<?php echo $base_path?>static/style.css" />
    <link rel="stylesheet" href="<?php echo $base_path?>static/jquery-ui.css" />
    <link rel="shortcut icon" href="<?php echo $base_path?>static/favicon.ico" />
    <!--[if lt IE 9]>
      <script src="<?php echo $base_path?>static/html5.js"></script>
    <![endif]-->
    <script>var Bookmarks={url:'<?php echo get_url()?>',script_path:'<?php echo $script_path?>'}</script>
<?php call_hook('front_head')?>
    <title><?php echo $site_title?> - <?php echo $global_site_title?></title>
  </head>
  <body id="<?php echo $body_id?>" class="<?php if (logged_in()):?>logged-in<?php else:?>anonymous<?php endif?>">
    <div id="global">
      <nav>
        <ul>
          <li class="start <?php if (! $f):?>active<?php endif?>"><a href="<?php echo $script_path?>"><?php _e('Overview')?></a></li>
          <li class="search <?php if ($f === 'search'):?>active<?php endif?>"><a href="<?php echo $script_path?>search"><?php _e('Search')?></a></li>
          <?php if (in_array('logged_in', $_SESSION)):?>
            <li class="save <?php if ($f === 'save'):?>active<?php endif?>"><a href="<?php echo $script_path?>save"><?php v('edit', False)? _e('Edit') : _e('Create new')?></a></li>
            <li class="import <?php if ($f === 'import'):?>active<?php endif?>"><a href="<?php echo $script_path?>import"><?php _e('Import')?></a></li>
            <li class="logout"><a href="<?php echo $script_path?>logout"><?php _e('Log out')?></a></li>
            <li class="help <?php if ($f === 'help'):?>active<?php endif?>"><a href="<?php echo $script_path?>help"><?php _e('Help')?></a></li>
          <?php else:?>
            <li class="login"><a href="<?php echo $script_path?>login"><?php _e('Log in')?></a></li>
          <?php endif?>
        </ul>
      </nav>
      <?php if (messages_have()):?>
        <ul id="messages">
          <?php foreach (messages_get() as $msg):?>
            <li class="<?php echo $msg['type']?>"><?php echo $msg['message']?></li>
          <?php endforeach?>
        </ul>
      <?php endif?>
      <article>

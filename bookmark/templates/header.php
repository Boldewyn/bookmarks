<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="<?php echo $base_path?>static/style.css" />
    <link rel="shortcut icon" href="<?php echo $base_path?>static/favicon.ico" />
    <!--[if lt IE 9]>
      <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <title><?php echo $site_title?></title>
  </head>
  <body id="<?php echo $body_id?>">
    <div id="global">
        <nav>
        <ul>
            <li class="start <?php if (! $f):?>active<?php endif?>"><a href="<?php echo $base_path?>"><?php _e('Overview')?></a></li>
            <?php if (in_array('logged_in', $_SESSION)):?>
                <li class="save <?php if ($f === 'save'):?>active<?php endif?>"><a href="<?php echo $base_path?>save"><?php _e('Create new')?></a></li>
                <li class="search <?php if ($f === 'search'):?>active<?php endif?>"><a href="<?php echo $base_path?>search"><?php _e('Search')?></a></li>
                <li class="logout"><a href="<?php echo $base_path?>logout"><?php _e('Log out')?></a></li>
            <?php else:?>
                <li class="login"><a href="<?php echo $base_path?>login"><?php _e('Log in')?></a></li>
            <?php endif?>
            <li class="help <?php if ($f === 'help'):?>active<?php endif?>"><a href="<?php echo $base_path?>help"><?php _e('Help')?></a></li>
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

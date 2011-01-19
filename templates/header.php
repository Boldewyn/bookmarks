<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="static/style.css" />
    <link rel="shortcut icon" href="static/favicon.ico" />
    <!--[if lt IE 9]>
      <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <title><?php echo $site_title?></title>
  </head>
  <body id="<?php echo $body_id?>">
    <nav>
      <ul>
        <li <?php if (! $f):?>class="active"<?php endif?>><a href="<?php echo $base_path?>"><?php _e('Overview')?></a></li>
        <li <?php if ($f === 'store'):?>class="active"<?php endif?>><a href="<?php echo $base_path?>store"><?php _e('Create new')?></a></li>
        <?php if (in_array('logged_in', $_SESSION)):?>
            <li><a href="<?php echo $base_path?>logout"><?php _e('Log out')?></a></li>
        <?php else:?>
            <li><a href="<?php echo $base_path?>login"><?php _e('Log in')?></a></li>
        <?php endif?>
      </ul>
    </nav>
    <article>

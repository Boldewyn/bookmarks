<?php include 'header.php'?>
<h1><?php echo $site_title?></h1>
<form method="post" action="<?php echo $script_path?>share" id="share_form">
  <p><?php _e('How do you want to share this bookmark?')?></p>
  <?php include 'detail.php'?>
  <dl>
    <?php call_hook('share_describe', array())?>
  </dl>
  <p>
    <input type="hidden" name="url" value="<?php echo $url?>" />
    <input type="hidden" name="ctoken" value="<?php echo set_csrf('share')?>" />
    <button type="submit" class="delete"><?php _e('Share')?></button>
    <button type="button" class="cancel" onclick="window.location.href='<?php echo $script_path?>'"><?php _e('Cancel')?></button>
  </p>
</form>
<?php include 'footer.php'?>

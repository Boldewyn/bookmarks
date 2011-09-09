<?php include 'header.php'?>
<h1><?php echo $site_title?></h1>
<form method="post" action="" id="delete_form">
  <p><?php _e('Do you really want to delete this bookmark?')?></p>
  <?php include 'detail.php'?>
  <p>
    <input type="hidden" name="confirm" value="1" />
    <button type="submit" class="delete"><?php _e('Delete')?></button>
    <button type="button" class="cancel" onclick="window.location.href='<?php echo $script_path?>'"><?php _e('Cancel')?></button>
  </p>
</form>
<?php include 'footer.php'?>

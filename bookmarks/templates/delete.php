<?php include 'header.php'?>
<h1><?php echo $site_title?></h1>
<form method="post" action="<?php echo $script_path?>delete" id="delete_form">
  <p><?php _e('Do you really want to delete this bookmark?')?></p>
  <?php include 'detail.php'?>
  <p>
    <input type="hidden" name="confirm" value="1" />
    <input type="hidden" name="url" value="<?php echo $url?>" />
    <input type="hidden" name="ctoken" value="<?php echo set_csrf('delete')?>" />
    <button type="submit" class="delete"><?php _e('Delete')?></button>
    <a class="ui-button cancel" href="<?php echo $script_path?>"><?php _e('Cancel')?></a>
  </p>
</form>
<?php include 'footer.php'?>

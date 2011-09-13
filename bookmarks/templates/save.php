<?php include 'header.php'?>
<h1><?php echo $site_title?></h1>
<?php echo $msg?>
<form method="post" action="<?php echo $script_path?>save" id="save_form">
  <p>
    <label for="url"><?php _e('URL')?></label>
    <input type="url" class="text" name="url" id="url" value="<?php echo $url?>" <?php echo $change?> autofocus="autofocus" />
  </p>
  <p>
    <label for="title"><?php _e('Title')?></label>
    <input type="text" class="text" name="title" id="title" value="<?php echo $title?>" />
  </p>
  <p>
    <label for="tags"><?php _e('Tags')?></label>
    <input type="text" class="text" name="tags" id="tags" value="<?php echo $tags?>" />
  </p>
  <p>
    <label for="notes"><?php _e('Notes')?></label>
    <textarea class="text" name="notes" id="notes" rows="4" cols="30"><?php echo $notes?></textarea>
  </p>
  <p>
    <label for="private"><?php _e('Private')?></label>
    <input type="checkbox" name="private" id="private" <?php echo $private?> />
  </p>
  <p>
    <input type="hidden" name="save" value="1" />
    <input type="hidden" name="ctoken" value="<?php echo set_csrf('save')?>" />
    <button type="submit"><?php echo $button?></button>
    <a class="ui-button cancel" href="<?php echo $script_path?>"><?php _e('Cancel')?></a>
  </p>
</form>
<?php include 'footer.php'?>

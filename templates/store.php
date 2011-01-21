<?php include "header.php" ?>
      <h1><?php echo $site_title?></h1>
      <?php echo $msg?>
      <form method="post" action="">
        <p>
        <label for="url"><?php _e('URL')?></label>
          <input type="url" name="url" id="url" value="<?php echo $url?>" <?php echo $change?> />
        </p>
        <p>
          <label for="title"><?php _e('Title')?></label>
          <input type="text" name="title" id="title" value="<?php echo $title?>" />
        </p>
        <p>
          <label for="tags"><?php _e('Tags')?></label>
          <input type="text" name="tags" id="tags" value="<?php echo $tags?>" />
        </p>
        <p>
          <label for="notes"><?php _e('Notes')?></label>
          <textarea name="notes" id="notes" rows="4" cols="30"><?php echo $notes?></textarea>
        </p>
        <p>
          <label for="private"><?php _e('Private')?></label>
          <input type="checkbox" name="private" id="private" <?php echo $private?> />
        </p>
        <p>
          <input type="hidden" name="store" value="1" />
          <button type="submit"><?php echo $button?></button>
        </p>
      </form>
<?php include "footer.php" ?>

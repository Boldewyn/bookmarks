<?php include "header.php" ?>
  <body id="save">
    <div>
      <h1><?php echo $site_title?></h1>
      <?php echo $msg?>
      <form method="post" action="">
        <p>
          <label for="href">URL</label>
          <input type="url" name="href" id="href" value="<?php echo $href?>" <?php echo $change?> />
        </p>
        <p>
          <label for="title">Title</label>
          <input type="text" name="title" id="title" value="<?php echo $title?>" />
        </p>
        <p>
          <label for="tags">Tags</label>
          <input type="text" name="tags" id="tags" value="<?php echo $tags?>" />
        </p>
        <p>
          <label for="notes">Notes</label>
          <textarea name="notes" id="notes" rows="4" cols="30"><?php echo $notes?></textarea>
        </p>
        <p>
          <label for="private">Private</label>
          <input type="checkbox" name="private" id="private" <?php echo $private?> />
        </p>
        <p>
          <input type="hidden" name="store" value="1" />
          <button type="submit"><?php echo $button?></button>
        </p>
      </form>
    </div>
  </body>
</html>

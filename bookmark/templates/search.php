<?php include "header.php" ?>
      <h1><?php echo $site_title?></h1>
      <form method="get" action="" id="search_form">
        <p>
          <label for="q"><?php _e('Search terms')?></label>
          <input type="text" class="text" name="q" id="q" value="<?php echo h(v('q', ''))?>" autofocus="autofocus" />
        </p>
        <p>
          <button type="submit"><?php _e('search')?></button>
          <button type="button" class="cancel" onclick="window.location.href='<?php echo $base_path?>'"><?php _e('Cancel')?></button>
        </p>
      </form>
<?php include "footer.php" ?>

<?php include 'header.php'?>
<h1><?php echo $site_title?></h1>
<form method="get" action="" id="search_form">
  <p>
    <label for="q"><?php _e('Search terms')?></label>
    <input type="text" class="text" name="q" id="q" value="<?php echo h(v('q', ''))?>" autofocus="autofocus" />
  </p>
  <p>
    <button type="submit"><?php _e('Search')?></button>
    <a class="ui-button cancel" href="<?php echo $script_path?>"><?php _e('Cancel')?></a>
  </p>
</form>
<?php include 'pagination.php'?>
<?php include 'list.php'?>
<?php include 'pagination.php'?>
<?php include 'footer.php'?>

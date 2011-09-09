<?php include 'header.php'?>
<h1><?php echo $site_title?></h1>
<?php if (isset($tags) && count($tags) > 0):?>
  <ul id="tag-list">
    <?php foreach ($tags as $tag):?>
      <li title="<?php _e('remove this tag')?>"><?php echo h($tag)?></li>
    <?php endforeach?>
  </ul>
<?php endif?>
<?php include 'list.php'?>
<?php include 'pagination.php'?>
<?php include 'footer.php'?>

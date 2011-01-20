<?php include "header.php" ?>
  <h1><?php echo $site_title?></h1>
<?php if (isset($tags) && count($tags) > 0):?>
  <ul id="tags">
    <?php foreach ($tags as $tag):?>
    <li><?php echo h($tag)?></li>
<?php endforeach?>
  </ul>
<?php endif?>
  <ul id="bookmarks">
    <?php foreach ($bookmarks as $bookmark):?>
      <li><a href="<?php echo $bookmark['href']?>"><?php echo $bookmark['title']?></a></li>
    <?php endforeach?>
  </ul>
<?php include "footer.php" ?>

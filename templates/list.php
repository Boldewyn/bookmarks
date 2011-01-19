<?php include "header.php" ?>
  <h1><?php echo $site_title?></h1>
  <ul>
    <?php foreach ($bookmarks as $bookmark):?>
      <li><a href="<?php echo $bookmark['href']?>"><?php echo $bookmark['title']?></a></li>
    <?php endforeach?>
  </ul>
<?php include "footer.php" ?>

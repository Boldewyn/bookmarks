<?php include "header.php" ?>
  <h1><?php echo $site_title?></h1>
<?php if (isset($tags) && count($tags) > 0):?>
<ul id="tags" title="<?php _e('remove this tag')?>">
    <?php foreach ($tags as $tag):?>
    <li><?php echo h($tag)?></li>
<?php endforeach?>
  </ul>
<?php endif?>
  <ul id="bookmarks">
    <?php foreach ($bookmarks as $bookmark):?>
      <li>
        <a class="url" rel="external" href="<?php echo $bookmark['url']?>"><?php echo $bookmark['title']?></a>
        <span class="tags">
          <?php foreach ($bookmark['tags'] as $tag):?>
            <a rel="tag" href="<?php echo "${base_path}tags/".rawurlencode($tag)?>"><?php echo $tag?></a>
          <?php endforeach?>
        </span>
      </li>
    <?php endforeach?>
  </ul>
<?php include "footer.php" ?>

<?php if(isset($bookmark)):?>
  <div class="bookmark-detail">
    <p><strong>URL:</strong> <span><?php echo $bookmark['url']?></span></p>
    <p><strong>Title:</strong> <span><?php echo $bookmark['title']?></span></p>
    <p><strong>Description:</strong> <span><?php echo $bookmark['notes']?></span></p>
    <p><strong>Tags:</strong> <span><?php foreach ($bookmark['tags'] as $tag):?>
      <a rel="tag" href="<?php echo "${script_path}tags/".rawurlencode($tag)?>"><?php echo $tag?></a>
    <?php endforeach ?></span></p>
  </div>
<?php endif?>

<?php if(isset($bookmark)):?>
  <div class="bookmark-detail">
    <p><strong><?php _e('Title:')?></strong> <span><?php echo $bookmark['title']?></span></p>
    <p><strong><?php _e('URL:')?></strong> <a href="<?php echo $bookmark['url']?>"><?php echo $bookmark['url']?></a></p>
    <?php if ($bookmark['notes']):?>
      <p><strong><?php _e('Description:')?></strong> <span><?php echo $bookmark['notes']?></span></p>
    <?php endif?>
    <?php if (count($bookmark['tags'])):?>
      <p><strong><?php _e('Tags:')?></strong> <span><?php foreach ($bookmark['tags'] as $tag):?>
        <a rel="tag" href="<?php echo "${script_path}tags/".rawurlencode($tag)?>"><?php echo $tag?></a>
      <?php endforeach ?></span></p>
    <?php endif?>
    <p><strong><?php _e('Short Link:')?></strong> <a href="<?php echo $script_path.'-'.$bookmark['shortcut']?>">http://<?php echo get_host().$script_path.'-'.$bookmark['shortcut']?></a></p>
  </div>
<?php endif?>

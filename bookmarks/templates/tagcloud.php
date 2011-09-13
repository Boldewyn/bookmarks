<?php if (isset($tagcloud) && is_array($tagcloud)):?>
  <ul class="tagcloud">
    <?php foreach($tagcloud as $tag):?>
    <li class="t<?php echo $tag['n']?>"><a href="<?php echo $script_path?>tags/<?php
      if (isset($tags)):
        echo str_replace('%26amp%3B', '&amp;', rawurlencode(join(' ', array_unique(array_merge($tags, array($tag['tag']))))));
      else:
        echo str_replace('%26amp%3B', '&amp;', rawurlencode($tag['tag']));
      endif;
    ?>"><?php echo $tag['tag']?></a></li>
    <?php endforeach?>
  </ul>
<?php endif?>

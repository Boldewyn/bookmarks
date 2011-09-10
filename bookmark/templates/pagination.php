<p class="pag-info"><?php printf(__('%d bookmarks'), $all)?></p>
<?php if ($pages > 1):?>
  <ol class="paginate" start="0">
    <?php if ($page > 1):?>
      <li class="prev"><a href="<?php echo h(update_url(array('page'=>$page-1)))?>"><?php _e('Previous')?></a></li>
    <?php endif?>
    <li class="first<?php if ($page === 1):?> active<?php endif?>"><a href="<?php echo h(update_url(array('page'=>1)))?>"><?php _e('First')?></a></li>
    <?php $need_ellipsis = True;?>
    <?php for ($i = 1; $i <= $pages; $i++):?>
      <?php if ($i < 4 || $i > $pages - 3 || abs($page - $i) < 4): $need_ellipsis = True?>
        <li<?php if ($i === $page):?> class="active"<?php endif?>><a href="<?php echo h(update_url(array('page'=>$i)))?>"><?php echo $i?></a></li>
      <?php elseif ($need_ellipsis): $need_ellipsis = False?>
        <li class="hellip">…</li>
      <?php endif?>
    <?php endfor?>
    <li class="last<?php if ($page === $pages):?> active<?php endif?>"><a href="<?php echo h(update_url(array('page'=>$pages)))?>"><?php _e('Last')?></a></li>
    <?php if ($page < $pages):?>
      <li class="next"><a href="<?php echo h(update_url(array('page'=>$page+1)))?>"><?php _e('Next')?></a></li>
    <?php endif?>
  </ol>
<?php endif?>

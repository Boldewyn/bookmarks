<p><?php echo $all?> bookmarks</p>
<ol class="paginate" start="0">
  <li class="first<?php if ($i === 1):?> active<?php endif?>"><a href="<?php echo h(update_url(array('page'=>1)))?>"><?php _e('First')?></a></li>
  <?php for ($i = 1; $i <= $pages; $i++):?>
    <li<?php if ($i === $page):?> class="active"<?php endif?>><a href="<?php echo h(update_url(array('page'=>$i)))?>"><?php echo $i?></a></li>
  <?php endfor?>
  <li class="last<?php if ($i === $pages):?> active<?php endif?>"><a href="<?php echo h(update_url(array('page'=>$pages)))?>"><?php _e('Last')?></a></li>
</ol>

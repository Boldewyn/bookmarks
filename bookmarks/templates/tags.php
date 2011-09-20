<?php include 'header.php'?>
<div id="tagselection">
<section>
  <h2><?php _e('All Tags')?></h2>
  <?php include 'tagcloud.php'?>
</section>
<section>
  <h2><?php _e('Top 100 Tags')?></h2>
  <?php
  $tagcloud = $toptags;
  include 'tagcloud.php'
  ?>
</section>
</div>
<?php include 'footer.php'?>

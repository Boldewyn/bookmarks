<?php include 'header.php'?>
<h1><?php echo $site_title?></h1>
<ul id="tag-list">
  <?php foreach ($tags as $tag):?>
    <li title="<?php _e('remove this tag')?>"><a href="<?php
echo get_script_path();
$nl = array();
foreach($tags as $tag2):
    if ($tag2 !== $tag):
        $nl[] = $tag2;
    endif;
endforeach;
if (count($nl)):
    echo 'tags/'.join('+', $nl);
endif;
?>"><?php echo h($tag)?></a></li>
  <?php endforeach?>
</ul>
<?php include 'pagination.php'?>
<?php include 'list.php'?>
<?php include 'pagination.php'?>
<h2><?php _e('Top Tags')?> <small><a href="<?php echo $script_path?>tags"><?php _e('(show all tags)')?></a></small></h2>
<?php include 'aside.php'?>
<?php include 'footer.php'?>

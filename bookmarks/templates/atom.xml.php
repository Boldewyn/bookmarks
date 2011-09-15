<feed xmlns="http://www.w3.org/2005/Atom">
<title>Bookmarks</title>
<updated><?php echo date('c', $bookmarks[0]['modified'])?></updated>
<id><?php echo h(get_url())?></id>
<link rel="self" href="<?php echo h(get_url()).'?type=atom'?>"/>
<?php foreach($bookmarks as $bookmark):?><entry>
  <title><?php echo $bookmark['title']?></title>
  <author><name/></author>
  <link href="<?php echo $bookmark['url']?>" />
  <link rel="edit" href="<?php echo get_url().'save?edit=1&amp;url='.rawurlencode($bookmark['url'])?>"/>
  <id><?php echo $bookmark['url']?></id>
  <updated><?php echo date('c', $bookmark['modified'])?></updated>
<?php if ($bookmark['notes'] !== ''):?>  <summary><?php echo $bookmark['notes']?></summary><?php endif?>
</entry>
<?php endforeach?></feed>

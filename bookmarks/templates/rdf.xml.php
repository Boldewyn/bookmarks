<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://purl.org/rss/1.0/">
<channel rdf:about="<?php echo h(get_url()).'?type=rdf'?>">
  <title>Bookmarks</title>
  <link><?php echo h(get_url()).'?type=rdf'?></link>
  <description><?php _e('Bookmark feed')?></description>
  <items>
    <rdf:Seq>
      <?php foreach ($bookmarks as $bookmark):?><rdf:li rdf:resource="<?php echo $bookmark['url']?>" />
      <?php endforeach?>  </rdf:Seq>
  </items>
</channel>
<?php foreach ($bookmarks as $bookmark):?><item rdf:about="<?php echo $bookmark['url']?>">
  <title><?php echo $bookmark['title']?></title>
  <link><?php echo $bookmark['url']?></link>
<?php if ($bookmark['notes'] !== ''):?>  <description><?php echo $bookmark['notes']?></description><?php endif?>
</item>
<?php endforeach?></rdf:RDF>

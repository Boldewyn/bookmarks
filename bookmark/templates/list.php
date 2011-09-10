<?php if (isset($bookmarks)):?>
  <ul id="bookmarks">
    <?php foreach ($bookmarks as $bookmark):?>
      <li>
        <a class="url" rel="external" href="<?php echo $bookmark['url']?>"><?php echo $bookmark['title']?></a>
        <span class="tags">
          <?php foreach ($bookmark['tags'] as $tag):?>
            <a rel="tag" href="<?php echo "${script_path}tags/".rawurlencode($tag)?>"><?php echo $tag?></a>
          <?php endforeach?>
        </span>
        <?php if (logged_in()):?>
          <span class="functions">
            <a class="edit" href="<?php echo $script_path?>save?edit=1&amp;url=<?php echo rawurlencode($bookmark['url'])?>"><?php _e('edit')?></a>
            <a class="edit" href="<?php echo $script_path?>delete?url=<?php echo rawurlencode($bookmark['url'])?>"><?php _e('delete')?></a>
            <a class="edit" href="<?php echo $script_path?>share?url=<?php echo rawurlencode($bookmark['url'])?>"><?php _e('share')?></a>
          </span>
        <?php endif?>
      </li>
    <?php endforeach?>
  </ul>
<?php endif?>

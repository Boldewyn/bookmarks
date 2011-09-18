<form method="get" action="<?php echo get_script_path()?>search" class="search-form">
  <p>
    <label for="q_"><?php _e('Search terms')?></label>
    <input type="text" name="q" id="q_" value="<?php echo h(v('q', ''))?>" tabindex="1" />
    <button type="submit" tabindex="2"><?php _e('search')?></button>
  </p>
</form>

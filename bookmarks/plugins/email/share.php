<?php include 'header.php'?>
<h1><?php echo $site_title?></h1>
<form method="post" action="<?php echo $script_path?>plugin?plugin=email&amp;function=email_do_share" id="share_form">
  <p><?php _e('Please enter the email address to share with:')?></p>
  <p>
    <label for="email"><?php _e('E-Mail:')?></label>
    <input type="email" name="email" id="email" value="" />
  </p>
  <p>
    <label for="message"><?php _e('Message (optional):')?></label>
    <textarea name="message" id="message" rows="5" cols="60"></textarea>
  </p>
  <p>
    <input type="hidden" name="url" value="<?php echo $bookmark['url']?>" />
    <input type="hidden" name="ctoken" value="<?php echo set_csrf('plugin/email_share')?>" />
    <button type="submit" class="delete"><?php _e('Share')?></button>
    <a class="ui-button cancel" href="<?php echo $script_path?>"><?php _e('Cancel')?></a>
  </p>
</form>
<?php include 'footer.php'?>

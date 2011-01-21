<?php include "header.php" ?>
  <h1><?php echo $site_title?></h1>
  <p class="<?php echo (isset($msg_class)? $msg_class : 'message')?>">
    <?php echo $msg?>
  </p>
<?php include "footer.php" ?>

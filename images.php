<?php session_start();
include('functions.php');
$title="Image Thumbnails";
html_top($title,true);
belau_header($title);
?>

<div id='content-container'>

  <div id='aside'>
  </div> <!-- aside -->
  <div id="content">

    <?php
    foreach (glob("pics/*.jpg") as $filename) {
      $thumb = make_thumbnail($filename);
      echo "$thumb\n";
      echo "<br style='clear:left;'>\n";
    }
    ?>
  </div> <!-- content -->
</div> <!-- content-container -->

<?php belau_footer(); ?>
</body>
</html>

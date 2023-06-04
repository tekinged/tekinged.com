<?php session_start();
include('functions.php');
$title="Audio Clips";
html_top($title,true);
belau_header($title);
?>

<div id='content-container'>

  <div id='aside'>
  </div> <!-- aside -->
  <div id="content">

    <!-- very important to put all sections that might contain audio into this div -->
    <div id="jquery_jplayer"></div><!-- jquery_jplayer -->

    <?php
    foreach (glob("mp3s/*.mp3") as $filename) {
      $nice_name = basename($filename,'.mp3');
      $play_button = audio_play_button($filename,$nice_name);
      echo $play_button . "<br>";
    }
    foreach (glob("mp3s/*.m4a") as $filename) {
      $nice_name = basename($filename,'.m4a');
      $play_button = audio_play_button($filename,$nice_name);
      echo $play_button . "<br>";
    }
    ?>
  </div> <!-- content -->
</div> <!-- content-container -->

<?php belau_footer(); ?>
</body>
</html>

<?php


function random_mp3($dir = '../uploads/mp3s/')
{
    $session_array = 'dorrenges_mp3s';
    $session_index = 'dorrenges_next';
    if ( ! isset($_SESSION[$session_var])) {
      $files = glob($dir . '/*/*/whole.mp3');
      shuffle($files);
      $_SESSION[$session_var] = $files;
      $_SESSION[$session_index] = 0;
    }
    $files = $_SESSION[$session_var];
    $file = $files[$_SESSION[$session_index]];
    $_SESSION[$session_index] = ($_SESSION[$session_index] + 1) % count($files);
    return $file;
    #$file = array_rand($files);
    #return $files[$file];
}

if ($_SESSION['dosuub_level'] == 'beginner') {
  $file = beginner_mp3();
} else {
  $file = random_mp3();
}

#header("Content-Transfer-Encoding: binary"); 
#header("Content-Type: audio/mpeg, audio/x-mpeg, audio/x-mpeg-3, audio/mpeg3");
#header("Content-Length:".filesize($file));
#readfile($file);

if(isset($_POST['action']) && !empty($_POST['action'])) {
  echo json_encode($file);
} else {
  echo "<br>Got random $file";
}

#db_connect();
#visitlog(": $file");
?>

<?php
session_start();

function mydebug($msg) {
  if(isset($_POST['action']) && !empty($_POST['action'])) {
    // being called by the real program, can't echo anything
    return;
  }
  echo $msg;
}

function random_mp3($dir = '../uploads/mp3s/')
{
    $session_var   = 'dorrenges_mp3s';
    $session_index = 'dorrenges_next';
    if ( isset($_SESSION['dosuub_reset'] ) ) {
      //echo "Resetting!\n";
    }
    if ( ! isset($_SESSION[$session_var]) or isset($_SESSION['dosuub_reset']) ) {
      $files = Array();
      mydebug( "Setting files<br>\n" );
      if ( $_SESSION['dosuub_reset'] == 'Beginner' ) { 
        // this regex words in shell but not php . . . 
        //$files = glob($dir . 'upload_sentence.palauan/{2402..2929}/whole.mp3'); 
        $list = glob($dir . 'upload_sentence.palauan/2[4-9]*/whole.mp3'); 
        foreach ($list as $f) {
          $pieces = explode( '/', $f );
          if ($pieces[4] >= 2402 and $pieces[4] <= 2929) {
            $files[] = $f; 
          }
        }
      } else {
        $files = glob($dir . '/*/*/whole.mp3');
        shuffle($files);
      }
      $_SESSION[$session_var] = $files;
      $_SESSION[$session_index] = 0;
      unset($_SESSION['dosuub_reset']);
    }
    $files = $_SESSION[$session_var];
    $file = $files[$_SESSION[$session_index]];
    $_SESSION[$session_index] = ($_SESSION[$session_index] + 1) % count($files);
    mydebug(implode('<br>',$files));
    return $file;
    #$file = array_rand($files);
    #return $files[$file];
}

$file = "../uploads/mp3s/examples.palauan/1195.mp3";
$file = random_mp3();
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

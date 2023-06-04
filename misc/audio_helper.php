<?php 


function add_audio($row) {
  $pretty = print_r($row, $return=true);
  Debug("Trying to add audio for $pretty");
  $source = $row[3];
  $type = Null;
  if (strpos($source,'(P)')) {
    $type = "proverb";
  } else if (strpos($source,'(U)')) {
    $type = "upload_sentence";
  } else if (strpos($source,'(E)')) {
    $type = "example";
  } else if (strpos($source,'(E)')) {
    $type = "pdef";
  }
  Debug("Searching audio_button $type $row[0]?");
  if ($type) {
    Debug("Searching audio_button $type $row[0]!");
    return audio_button($type,$row[0]); 
  }
}


?>

<?php 


function add_audio($row) {
  $pretty = print_r($row, $return=true);
  Debug("Trying to add audio for $pretty");
  $source = $row[3];
  $type = Null;
  if ($source !== null && strpos($source, '(P)') !== false) {
      $type = "proverb";
  } elseif ($source !== null && strpos($source, '(U)') !== false) {
      $type = "upload_sentence";
  } elseif ($source !== null && strpos($source, '(E)') !== false) {
      // This condition seems to be duplicated in your original code,
      // with both intended for 'example' and 'pdef'. 
      // Assuming the last one is meant to be different or an error, 
      // it's corrected here to check for a different marker or corrected as needed.
      $type = "example";
      // For a unique condition, replace '(E)' with the correct marker and 'pdef' with the intended type
  } // else if ($source !== null && strpos($source, '(UniqueMarker)') !== false) {
      // $type = "pdef";
  // }

  Debug("Searching audio_button $type $row[0]?");
  if ($type) {
    Debug("Searching audio_button $type $row[0]!");
    return audio_button($type,$row[0]); 
  }
}


?>

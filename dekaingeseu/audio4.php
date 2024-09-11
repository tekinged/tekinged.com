<?php
session_start();
function header_function() {
  // This function is empty and can be populated later.
}

function footer_function() {
  // This function is empty and can be populated later.
}

function select_sentences() {
  $destination_folder = 'uploads';
  return [
    1 => 'This is sentence one.',
    2 => 'This is sentence two.',
    3 => 'This is sentence three.'
  ];
}

header_function();

if (!isset($_SESSION['User'])) {
  echo '<input type="text" id="username" placeholder="Please enter your name">';
  echo '<button id="setName">Submit</button>';
} else {
  echo "Welcome, " . $_SESSION['User'];
  echo "<p>Please choose a sentence you'd like to record from the pull-down menu and record yourself saying it.</p>";
  $sentences = select_sentences();
  echo '<select id="sentenceSelect">';
  foreach ($sentences as $key => $value) {
    echo "<option value=\"$key\">$value</option>";
  }
  echo '</select>';
  // The rest of your recording interface HTML here
}

footer_function();
?>
<script src="capture_audio.js" type="text/javascript"></script>


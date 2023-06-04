<?php 
session_start();
include 'dekaingeseu.php';
include '../functions.php';

function sentence_record_task($result,$config) {
  $debug=false;
  if ($debug) {
    print "Hey, something was uploaded!<br>";
    print_r($_POST);
    print "<br>";
  }

  $p = mysqli_real_escape_string($GLOBALS['mysqli'],$_POST['pal']);
  $e = mysqli_real_escape_string($GLOBALS['mysqli'],$_POST['eng']);
  $who = $_SESSION['worker'];
  print "<h2>Thanks $who for submitting <i>$p</i>!</h2>";

  $tab = $config['table'];
  $q = "insert into $tab (palauan,eng,submitted,assignee,assigned,uploaded) VALUES ('$p','$e',now(),'$who',now(),1)";
  mysqli_query($GLOBALS['mysqli'],$q) or die("mysqli_error");
  return NULL;
}

function get_requested_word() {
  $q = "select pdef as pal from kerresel_missing order by rand() limit 1";
  $r = query_or_die($q);
  $row = $r->fetch_assoc(); 
  return $row['pal'];
}

function print_request() {
  $style = "style='background-color:blue'"; 
  $submit = submitButton("Submit",'upload',$style); 
  
  $requested = get_requested_word(); 

  echo "
    Please submit a sentence in Palauan. It can be anything you think is a natural sounding Palauan sentence that might occur in normal dialogue between friends,
    family members, in a store, restaurant, while fishing, while in the taro patch, etc.<br>
    Optionally, add an English translation.  For example<br>
    <ul><li><i>Ngdi ungil a kumekedong er kau er a tara taem?</i>
    <li>with optional english translation \"Is it OK to call you sometimes?\"
    </ul>
    If you want a suggestion, we would like a sentence using the word <b>$requested</b>.<br><br>
    <form method='post'>
    <input type='hidden' name='taskresult' >
    <table>
    <tr><td>Sentence: <td><input type='text' size='100' name='pal' placeholder='REQUIRED: Meluches a tekoi er a Belau' /><br></tr>
    <tr><td>English translation: <td><input type='text' size='64' name='eng' placeholder='Optional: English translation here.' /><br></tr>
    ";
  echo "</table>\n";
  echo $submit;
  echo "</form>\n";

  echo "<br><br><p class='tab'>To see the sentences already uploaded, click <a href=/tmp/sentences.php>here</a>.<br>\n";
}

function make_upload_request() {
  print_request();
}

function sentence_debug($msg) {
  print "DEBUG: $msg<br>\n";
}

function sentence_make_task() {
  make_upload_request();
  return Null;
}

function simple_sentence_page($table,$col) {
  $config = array();
  $config['table'] = $table;
  $config['column'] = $col;
  make_sentence_page($config);
}

function make_sentence_page($options) {
  #print_r($options);
  
  $table = 'upload_sentence';
  $title = "Uploading Sentences";
  $config = array();

  # variables that dekaingeseu needs
  $config['intro'] = "Help create and upload example sentence to help people learn Palauan as a second language."; 
  $where = "uploaded != 1";
  $config['extra_update'] = $q;
  $config['get_total'] = ""; 
  #$config['get_count'] = ""; 
  $config['table'] = $table; 
  $config['make_task'] = "sentence_make_task"; # function pointer
  $config['record_task'] = "sentence_record_task"; # function pointer
  $config['timeout'] = 1500; # this should be irrelevant... 

  # extra variables that we use ourselves
  $task = new Dekaingeseu($title,$config);
}

simple_sentence_page(Null,Null);

?>

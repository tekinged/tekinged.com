<?php 
session_start();
include 'dekaingeseu.php';
include '../functions.php';

$GLOBALS['DEBUG']=False;

function trivia_record_task($result,$config) {
  $debug=false;
  if ($debug) {
    print "Hey, something was uploaded!<br>";
    print_r($_POST);
    print "<br>";
  }

  $worker = $_SESSION['worker'];
  print "<h2>Thanks $worker for submitting a trivia question! If you're still having fun, submit another!</h2><br>";

  $tab = $config['table'];
  $a = mysqli_real_escape_string($GLOBALS['mysqli'],$_POST['a']);
  $q = mysqli_real_escape_string($GLOBALS['mysqli'],$_POST['q']);
  $i1 = mysqli_real_escape_string($GLOBALS['mysqli'],$_POST['i1']);
  $i2 = mysqli_real_escape_string($GLOBALS['mysqli'],$_POST['i2']);
  $i3 = mysqli_real_escape_string($GLOBALS['mysqli'],$_POST['i3']);
  $i4 = mysqli_real_escape_string($GLOBALS['mysqli'],$_POST['i0']);
  $who = $_SESSION['worker'];
  $q = "insert into $tab (q,a,o1,o2,o3,o4,submitted,assignee,assigned,uploaded) VALUES ('$q','$a','$i1','$i2','$i3','$i4',now(),'$who',now(),1)";
  #echo $q;
  query_or_die($q);
  return NULL;
}

function print_request() {
  $style = "style='background-color:blue'"; 
  $submit = submitButton("Submit",'upload',$style); 
  echo "
    Please submit a trivia question written in Palauan. It can be about legends, geography, language, politics, history, or whatever you think is interesting!<br>
    Add a question, the correct answer, and four incorrect answers.<br>
    <ul><li>For example, <i>Ng techa mle kot el president er a Belau?</i>.<br>
    <li>Add the correct answer of Remeliik and then four incorrect answers like Remengesau, Toribiong, Nakamura, Etpison.<br>
    <li>You can see more example questions <a href=/quiz/q_trivia.php>by clicking here</a>.
    </ul>
    <form method='post'>
    <input type='hidden' name='taskresult' >
    <table>
    <tr><td>Question: <td><input type='text' size='100' name='q' placeholder='REQUIRED: Meluches a tekoi er a Belau' /><br></tr>
    <tr><td>Correct: <td><input type='text' size='64' name='a' placeholder='REQUIRED: Correct answer here.' /><br></tr>
    ";
  for ($x = 0; $x < 4; $x++) {
    $required = ($x<=0 ? "REQUIRED" : "OPTIONAL");
    echo "<tr><td>Incorrect: <td><input type='text' size='64' name='i$x' placeholder='$required: An incorrect answer here.' /><br></tr>\n";
  }
  echo "</table>\n";
  echo $submit;
  echo "</form>\n";
}

function make_upload_request() {
  print_request();
}

function print_instructions() {
  echo "<h2>Instructions for Recording</h2>";
  echo "<ul><li>Speak naturally but clearly.  Imagine you are talking to someone who is a bit hard of hearding.
    <li>After you record and are happy with your recording, find where it says:
    <ul>
    <li><i>Happy with this recording? Click here to save >></i>
    </ul>
    <li>Click on that, then find where it says, <i>Download as mp3</i>. 
    <li>Right-click on that and select <i>Copy Link</i>. 
    <li>Then paste that link into the box and click submit.
    </ul>
  ";
}

function trivia_debug($msg) {
  print "DEBUG: $msg<br>\n";
}

function trivia_make_task() {
  make_upload_request();
  return Null;
}

function simple_trivia_page($table,$col) {
  $config = array();
  $config['table'] = $table;
  $config['column'] = $col;
  make_trivia_page($config);
}

function make_trivia_page($options) {
  #print_r($options);
  
  $table = 'upload_trivia';
  $title = "Uploading Trivia";
  $config = array();

  # variables that dekaingeseu needs
  $config['intro'] = "Help create and upload trivia questions for a trivia quiz."; 
  $where = "uploaded != 1";
  $config['extra_update'] = $q;
  $config['get_total'] = ""; 
  #$config['get_count'] = ""; 
  $config['table'] = $table; 
  $config['make_task'] = "trivia_make_task"; # function pointer
  $config['record_task'] = "trivia_record_task"; # function pointer
  $config['timeout'] = 1500; # this should be irrelevant... 

  # extra variables that we use ourselves
  $task = new Dekaingeseu($title,$config);
}

simple_trivia_page(Null,Null);

?>

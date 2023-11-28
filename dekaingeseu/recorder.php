<?php
session_start();
include '../functions.php';

$_GLOBALS['DEBUG'] = true;

$title = "Dekaingeseu : Record Sentences";
db_connect();

function query_for_sentence($config) {
  $t = $config['table'];
  $c = $config['externalcolumn'];
  $p = $config['palcolumn'];
  $e = $config['engcolumn'];

  Debug('Entering query for sentence');

  #return return_debug();
  $skips = $_POST['next'];  # sends an array of ids to skip
  $filter = "(";
  if (is_array($skips)) {
    foreach( $skips as $skip ) {
      $filter .= "id != $skip and ";
    }
  }
  $filter .= "1)";
  $orderby = "order by id";
  #$orderby = "order by rand()";
  $f = "(select externalid from upload_audio where uploaded=1 and externaltable like '" . $t . "' and externalcolumn like '" . $c . "')";
  $q = "select id,$p as pal,$e as eng from $t where length($p) > 0 and $filter and id not in $f $orderby;";
  #file_put_contents('/tmp/jb.log',$q,FILE_APPEND);
  
  echo("query is $q");
  Debug("query is $q");
  $r = query_or_die($q);
  $nr = mysqli_num_rows($r); # get all of them in order to count them
  $row = mysqli_fetch_assoc($r); # but only read the first one
  return array($row,$nr,$q);
}

function recorder_main() {

  #instructions
  echo "
    <h1>Help upload Palauan audio recordings</h1>
    <p>Please make sure you are using a recent version of Google Chrome.
    Also before you enable microphone input either plug in headphones or turn the volume down if you want to avoid ear splitting feedback!</p>
      ";

  $config = array();
  $config['engcolumn'] = 'english';
  $config['palcolumn'] = 'palauan';
  $config['table'] = 'examples';
  $config['type'] = 'example';
  $config['externalcolumn'] = $config['palcolumn']; # always same. 
    
  query_for_sentence($config);

  # recording interface
  echo "
    <h2>Record</h2>
    <div class='tab'>
      <p>
      <span id='sentencedisplay'></span>
      <button id='recordbutton' onclick='startRecording(this);'>RECORD</button>
      <button id='stopbutton' onclick='stopRecording(this);' disabled>STOP</button>
      <button id='skipbutton' onclick='skipRecording(this);'>SKIP</button>
      <button id='quitbutton' onclick='window.location.href=\"/\"'>QUIT</button>
      <p>Click RECORD to begin recording and STOP when done. Then verify and UPLOAD below.
    </div>

    <h2>Verify</h2>
    <div class='tab'>
      <ul id='recordingslist'></ul>
      <p id='recordingparagraph'></p>
    </div>

    <h2>Upload</h2>
    <div class='tab'>
      <p id='uploadparagraph'></p>
      <button id='uploadbutton' onclick='uploadRecording(this);' disabled>UPLOAD</button>
    </div>
    
    <h2>Log</h2>
    <div class='tab'>
      <pre id='log'></pre>
    </div>
  ";
}

function recorder_start_page($title) {
  belau_header($title);
  start_content_container();
  /* could put an aside here */
  start_content();
}


function requestName() {
  echo "<p>Thanks for your interest in helping to create an online Palauan dictionary.";

  echo "<form method='post'>\n";
  add_input("Enter your name so your contribution will be counted (or use anonymous if you'd like): ", 'name',False);
  echo "<input type='submit' />\n";
  echo "</form>\n";
}


function main_page() {
  if (isset($_POST['name'])) {
    $_SESSION['worker'] = $_POST['name'];
    $_SESSION['completed'] = 0;
  } 

  global $title;

  if ( ! isset($_SESSION['worker']) ) {
    html_top($title);
    recorder_start_page($title);
    requestName();
  } else {
    $jquery = 'foo';
    $extra = "<script src='$jquery'</script>
            <style type='text/css'>
            ul { list-style: none; }
            #recordingslist audio { display: block; margin-bottom: 10px; }
            </style>
          ";
/*
    $extra = "<script src='$jquery'</script>
            <script src='//tekinged.com/dekaingeseu/js/recorder.js'></script>
            <script src='//tekinged.com/dekaingeseu/js/dollai.js'></script>
            <style type='text/css'>
            ul { list-style: none; }
            #recordingslist audio { display: block; margin-bottom: 10px; }
            </style>
          ";
*/
    html_top_no_body($title,false,$extra);
    echo '<body>';
    recorder_start_page($title);
    recorder_main();
  }

  end_content();
  belau_footer(curPageURL());
}

main_page();
?>

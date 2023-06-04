<?php 
session_start();
include 'dekaingeseu.php';
include '../functions.php';

function audio_record_task($result,$config) {
  #print "Hey, something was uploaded!<br>";
  #print_r($_POST);
  #print_r($_FILES);

  $url = $_POST['mp3'];
  $id  = $_POST['wid'];
  $name = $_POST['word'];
  $table = $config['src_table'];
  $column = $config['src_col'];

  $file = "../uploads/mp3s/$table.$column/$id.mp3";
  if (strlen($url) > 0) {
    audio_debug("Will fetch $url into $file");
    if (file_put_contents($file, file_get_contents($url)) === false) {
      print "Problem uploading $url into $file....<br>\n";
      $success = 0;
    } else {
      $success = 1;
    }
  } else {
    # user hit submit without pasting a url
    $success = 0;
  }

  if ( ! file_exists($file)) {
    print "Problem uploading file $file; it does not exist.";
    $success = 0;
  }

  if ($success === 1) { 
    echo "Thanks for uploading the audio of $name [$id:$file]!<br>\n";
    $who = $_SESSION['worker'];
    $update = "uploaded=$success,assignee='$who'";
  } else {
    echo "Sorry, there was a problem uploading $name into $file...<br>\n";
    $update=Null;
  }
  Debug("Uploaded $target_file: $success");
  return $update;
}

function print_request($word,$eng,$id) {
  $style = "style='background-color:blue'"; 
  $submit = submitButtons("Upload",'upload',$style); 
  #print_r($_POST);
  echo "
    Please record yourself saying the following: <br>
      <ul><li><b>$word</b></ul>
    Some of these have english translations which may help if any of these are confusing.  If you don't like one, just skip it.  Eventually we can figure out which 
    ones are bad and fix them or delete them.
      <ul><li><i>$eng</i></ul>
    <form method='post'>
    ";
  add_input("When finished, please paste the mp3 link here and click upload.", 'mp3',False);
  echo "$submit
          <input type='hidden' name='word' value='$word'>
          <input type='hidden' name='wid' value='$id'>
          </form>
        ";
}

function make_upload_request($pal,$eng,$id) {
  echo "<table width=100%><tr><td width=70%>";
  echo '<iframe name="myframe" src="http://vocaroo.com" width=100% height=700px marginwidth=0 marginheight=0 hspace=0 vspace=0 frameborder=0 scrolling=auto></iframe>';
  echo "</td><td valign='top'>";
  print_request($pal,$eng,$id);
  echo "</td></tr></table>\n";
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

function audio_debug($msg) {
  #print "DEBUG: $msg<br>\n";
}

function get_eng($config,$id) {
  $eng = $config['src_eng'];
  $tab = $config['src_table'];
  $q = "select $eng from $tab where id=$id";
  list($res,$num_rows) = check_table($q);
  $row = mysql_fetch_row($res);
  return $row[0];
}

function audio_make_task($db_row,$config) {
  print_instructions();
  $table     = $config['table'];
  $ext_table = $config['src_table'];

  $q = "select pal,externalid,id,externaltable,externalcolumn from " . $config['table'] . " " . $config['get_count'] . " order by rand() limit 1";
  audio_debug($q);
  list($res,$num_rows) = check_table($q);
  $row = mysql_fetch_row($res);
  $pal = $row[0];
  $id  = $row[1];
  $eng = get_eng($config,$id);

  make_upload_request($pal,$eng,$id);

  $values = array();
  $values['id'] = $row[2]; 
  return $values; 
}

function simple_audio_page($table,$col,$eng) {
  $config = array();
  $config['table'] = $table;
  $config['column'] = $col;
  $config['eng'] = $eng;
  make_audio_page($config);
}

function make_audio_page($options) {
  #print_r($options);
  $src_table = $options['table'];
  $src_column = $options['column'];
  $src_eng = $options['eng'];

  $filter = "externalcolumn like '$src_column' and externaltable like '$src_table'";

  # make sure that all the words are in the upload_audio table 
  $q = "insert into upload_audio (externalid,externaltable,externalcolumn,pal) 
          (
            select id,'$src_table','$src_column',$src_column from $src_table 
            where length($src_column)>0 
            and id not in 
              (
                select externalid from upload_audio where $filter 
              )
          );
        ";
  audio_debug($q);
  
  $table = 'upload_audio';
  $title = "Uploading Audio";
  $config = array();

  # variables that dekaingeseu needs
  $config['intro'] = "Help upload audio pronounciations of Palauan Language words and sentences";
  $where = "uploaded != 1 and $filter";
  $config['extra_update'] = $q;
  $config['q_find'] = "select id,externalid from $table where $where";
  $config['get_total'] = "where $filter"; 
  $config['get_count'] = "where $where";
  $config['table'] = $table; 
  $config['make_task'] = "audio_make_task"; # function pointer
  $config['record_task'] = "audio_record_task"; # function pointer
  $config['timeout'] = 900; # give people 15 minutes 

  # extra variables that we use ourselves
  $config['src_table'] = $src_table;
  $config['src_col'] = $src_column;
  $config['src_eng'] = $src_eng;
  $task = new Dekaingeseu($title,$config);
}

?>

<?php 
session_start();
include 'dekaingeseu.php';
include '../functions.php';

function check_file($file,$sz,$filepath) {
  $valids = array('mp3', 'm4a', 'wma');
  $ext = pathinfo($file,PATHINFO_EXTENSION);
  $max_file_sz = 1048576 * 2; # 2 MB max
  $min_file_sze = 1024; # less than 1K is suspicious

  if ( ! in_array($ext,$valids) ) { 
    echo "<br><b>Sorry</b>, your file [$ext] is not a valid file type. Must be one of the following:<br>";
    print_r ($valids);
    echo "<br>";
    return 0;
  }

  if ($ext == 'wma') {
    $path_parts = pathinfo($file);
    $target = "/tmp/" . $path_parts['filename'] . ".mp3";
    $file = $target;
    $command = "ffmpeg -i $filepath -acodec libmp3lame -ab 128k $target";
    print "<br>converting to mp3 format: $command.<br>\n"; 
    exec($command);
    return check_file($target,getimagesize($target),$target);
  }

  if ($sz > $max_file_sz) {
    echo "<br><b>Sorry</b>, your file is too large.  Can you make it smaller and try again please?<br>";
    return 0;
  }

  if ($sz < $min_file_sz) {
    echo "<br><b>Sorry</b>, your file is suspiciously small. Please try again.<br>"; 
    return 0;
  }

  # it's good!
  return $filepath;
}

function audio_record_task($result,$config) {
  $debug=false;
  if ($debug) {
    print "Hey, something was uploaded!<br>";
    print_r($_POST);
    print_r($_FILES);
    print "<br>";
  }

  $file_shortcut = $_FILES['fileToUpload'];
  $file = check_file($file_shortcut['name'],$file_shortcut['size'],$file_shortcut['tmp_name']);
  if ($file !== 0) {
    print "Thanks! You uploaded a valid file!<br>\n";
  } else {
    print "<br><b>Sorry.</b> Problem with the upload: $file.<br>\n";
    return NULL;
  }

  $ids = $_POST['which'];
  list($id,$externalid) = split(':',$ids);

  $table = $config['src_table'];
  $column = $config['src_col'];

  $targetfile = "../uploads/mp3s/$table.$column/$externalid.mp3";
  audio_debug( "Renaming $file to $targetfile<br>\n" );
  if (rename($file,$targetfile) === True) {
    $_SESSION['taskid'] = $id;
    $who = $_SESSION['worker'];
    return "uploaded=1,assignee='$who'";
  } else {
    print "<br><b>Shit.</b>Rename failed from $file to $targetfile.";
    return NULL;
  }
}

function print_request($word,$id) {
  $style = "style='background-color:blue'"; 
  $submit = submitButton("Upload",'upload',$style); 
  #print_r($_POST);
  echo "
    Please record yourself saying the following: <br>
      <ul><li><b>$word</b></ul>
    <form method='post'>
    ";
  add_input("Then paste the mp3 link here and click upload.", 'mp3',False);
  echo "$submit
          <input type='hidden' name='word' value='$word'>
          <input type='hidden' name='wid' value='$id'>
          </form>
        ";
}

function make_upload_request($pal,$id) {
  echo "<table width=100%><tr><td width=70%>";
  echo '<iframe name="myframe" src="http://vocaroo.com" width=100% height=700px marginwidth=0 marginheight=0 hspace=0 vspace=0 frameborder=0 scrolling=auto></iframe>';
  echo "</td><td valign='top'>";
  print_request($pal,$id);
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

function audio_make_task2($db_row,$config) {
  print "Please choose one of the following, and upload an audio file of yourself speaking it.<br>";
  $submit = submitButton("Upload",'upload',$style); 

  # query for all not yet completed
  $q = "select pal,externalid,id,externaltable,externalcolumn from " . $config['table'] . " " . $config['get_count'] . " order by pal"; 
  list($res,$num_rows) = check_table($q);

  # add the radio choices 
  echo " <form method='post' enctype='multipart/form-data'>
          <select name='which'>
       ";
  # populate the radio
  while($row = mysql_fetch_row($res)) {
    print "<option value='$row[2]:$row[1]'>$row[0]</option>\n";
  }
  echo "</select>";

  # add the input box and the submit button, close the form
  print "
          <br>
          <input type='file' name='fileToUpload' id='fileToUpload'>
          <br>
          $submit
        </form>
        ";

  return NULL;
}

function audio_make_task($db_row,$config) {
  print_instructions();

  $q = "select pal,externalid,id,externaltable,externalcolumn from " . $config['table'] . " " . $config['get_count'] . " order by rand() limit 1";
  audio_debug("$q");
  list($res,$num_rows) = check_table($q);
  $row = mysql_fetch_row($res);

  make_upload_request($row[0],$row[1]);

  $values = array();
  $values['id'] = $row[2]; 
  return $values; 
}

function simple_audio_page($table,$col) {
  $config = array();
  $config['table'] = $table;
  $config['column'] = $col;
  make_audio_page($config);
}

function make_audio_page($options) {
  #print_r($options);
  $src_table = $options['table'];
  $src_column = $options['column'];

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
  $config['make_task'] = "audio_make_task2"; # function pointer
  $config['record_task'] = "audio_record_task"; # function pointer
  $config['timeout'] = 900; # give people 15 minutes 

  # extra variables that we use ourselves
  $config['src_table'] = $src_table;
  $config['src_col'] = $src_column;
  $task = new Dekaingeseu($title,$config);
}

?>

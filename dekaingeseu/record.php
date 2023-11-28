<?php

$GLOBALS['DEBUG'] = True;

$GLOBALS['audio_debug'] = "DEBUG: ";

function return_debug($config) {
  $r = "<br>FILES: " . print_r($_FILES,true) . "<br>POST: " . print_r($_POST,true). "<br>SESSION: " . print_r($_SESSION,true);
  get_next_sentence($config);
  echo json_encode($r);
}

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

# does a sequel query to get the next sentence
# also returns how many sentences are left
# and who the person is and how many contributions they've made
function get_next_sentence($config) 
{
  list($row,$nr,$q) = query_for_sentence($config);
  $GLOBALS['audio_debug'] .= " $row ";
  if ($GLOBALS['audio_remaining'] > 0) {
    $row['remaining'] = $GLOBALS['audio_remaining'];
  } else {
    $row['remaining'] = $nr; 
  }
  $row['who'] = $_SESSION['worker'];
  $row['uploads'] = $_SESSION['completed'];
  $row['query'] = $q; # just to help debuggin
  #$row['debug'] = $GLOBALS['audio_debug']; 
  $row['type'] = $config['type'];
  $row['table'] = $config['table'];
  $row['column'] = $config['externalcolumn'];
  echo json_encode($row);
}

function record_success() {
  Debug($_POST);
  $_SESSION['completed'] = $_SESSION['completed'] + 1;
  $i = $_POST['sid'];
  $t = $_POST['table'];
  $c = $_POST['externalcolumn'];
  $w = $_SESSION['worker'];
  $p = mysql_real_escape_string($_POST['pal']);
  $u = "insert into upload_audio (externalid,externaltable,externalcolumn,pal,assignee,uploaded,submitted) VALUES (";
  $u .= "'$i','$t','$c','$p','$w',1,now()";
  $u .= ")";
  mysql_query($u) or die(mysql_error());
  $u = "INSERT INTO dekaingeseu (name,count) VALUES ('" . $w . "',1) ON DUPLICATE KEY UPDATE count = count + 1;";
  mysql_query($u) or die(mysql_error());
}

function upload_wav($input,$config) {
  $t = $_POST['table']; 
  $y = $_POST['type'];
  $i = $_POST['sid'];
  $p = $_POST['pal'];
  list($mp3,$url) = get_mp3_paths($y,$i);
  $wav = "wavs/" . $i . ".wav";
  #$mp3 = "wavs/" . $i . ".mp3";
  if (rename($input, $wav)) {
    chmod($wav,0644);
    echo json_encode("SUCCESSFULLY uploaded <a href=http://tekinged.com/$url>$p</a>");
    flush();
    exec("ffmpeg -y -i $wav $mp3"); 
    chmod($mp3,0644);
    record_success();
  } else {
    $r = print_r($_FILES,true) . "POST: " . print_r($_POST,true);
    echo json_encode("failure? Tried to write $wav ($r)");
  }
}

function set_config($config,$type) {
  switch ($type) {
    case 'proverb':
      $config['engcolumn'] = 'english';
      $config['palcolumn'] = 'palauan';
      $config['table'] = 'proverbs';
      $config['type'] = 'proverb';
      break;
    case 'sounds':
      $config['engcolumn'] = 'english';
      $config['palcolumn'] = 'palauan';
      $config['table'] = 'sounds';
      $config['type'] = 'sounds';
      break;
    case 'kerresel':
      $config['engcolumn'] = 'eng';
      $config['palcolumn'] = 'pdef';
      $config['table'] = 'all_words3';
      $config['type'] = 'pdef';
      break;
    case 'example':
      $config['engcolumn'] = 'english';
      $config['palcolumn'] = 'palauan';
      $config['table'] = 'examples';
      $config['type'] = 'example';
      break;
    case 'sentence':
      $config['engcolumn'] = 'eng';
      $config['palcolumn'] = 'palauan';
      $config['table'] = 'upload_sentence';
      $config['type'] = 'upload_sentence';
      break;
    default:
      return_debug();
      break;
  }
  $config['externalcolumn'] = $config['palcolumn']; # always same. 
  return $config;
}

$config = array();

if ( ! isset($_SESSION['record_type']) ) {
  #echo "Setting session to proverb";
  $_SESSION['record_type'] = 'proverb';
}


if ($_SESSION['record_type'] == 'any') {
  $save_config = null;
  $GLOBALS['audio_remaining'] = 0;
  foreach ( array('sounds', 'proverb', 'example', 'sentence', 'kerresel') as $type) {
    $config=set_config($config,$type);
    list($row,$nr,$q) = query_for_sentence($config);
    $GLOBALS['audio_debug'] .= " $q -> $nr ";
    if ($nr > 0 and $save_config == null) {
      $save_config = $config;
    }
    $GLOBALS['audio_remaining'] += $nr; 
  }
  $config = $save_config;
} else {
  $type = $_SESSION['record_type'];
  $GLOBALS['audio_debug'] .= " Using $type";
  $config = set_config($config,$_SESSION['record_type']);
}


if(isset($_POST['sid'])) {
  upload_wav($_FILES['data']['tmp_name'],$config);
} else if(isset($_POST['next']) && !empty($_POST['next'])) {
  get_next_sentence($config);
} else {
  return_debug($config);
}


#db_connect();
#visitlog(": $file");
?>

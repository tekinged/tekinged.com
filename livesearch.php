<?php
include('functions2.php');

db_connect();

$GLOBALS['DEBUG'] = false;

//get the q parameter from URL
$input=$_GET["q"];
$direction=$_GET["d"];
Debug("Received $input in $direction");
$response = "";

//lookup all links from the xml file if length of q>0
if (strlen($input)>0) {
  $limit = 7;
  if (strncmp($direction,'ep', 2)==0 ) {
    $table = "eng_list";
    $field = "eng";
  } else {
    Debug("Direction $direction != ep");
    $table = "all_words3";
    $field = "pal";
  }
  $q = "select distinct($field) as f from $table where $field regexp '^$input' limit $limit"; 
  Debug($q);
  $r = query_or_die($q);
  $user_agent = $_SERVER['HTTP_USER_AGENT'];
  if (strpos( $user_agent, 'Chrome') !== false) {
    $user_agent= "chrome";
  } elseif (strpos( $user_agent, 'Safari') !== false) {
    $user_agent = "safari";
  }
  Debug("user_agent is $user_agent");
  while($row = mysql_fetch_assoc($r)) {
    extract($row);
    Debug($f."<br>\n");
    if (strpos( $user_agent, 'safari') !== false) {
      $text = button_as_link($f,$direction,strtoupper($f));
    } else {
      $text = "<option>$f</option>";
    }
    $response .= $text;
  }
}

//output the response
echo $response;
?>

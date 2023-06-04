<?php

include('functions.php');

# initial processing to see if we are in test-mode or real mode and to get the target word
$local_testing = False;
$in_word = 0;
if(isset($_POST['userid'])){
   $in_word = $_POST['userid']; # mysqli_real_escape_string($con,$_POST['userid']);
} else {
  echo "Doing a default one for testing<br>";
  $in_word = 'chull'; # just give a default one for testing
  $local_testing = True;
}

$pword = strip_punctuation($in_word);
db_connect();

# a debug function for local testing mode
function mydebug($msg,$local_testing=False) {
  if ($local_testing) {
    echo $msg . '<br>';
  }
}

# a function to return the query we use
function get_query($pword,$stem=Null) {
  $where = "a.pal like '" . $pword . "'";
  if ($stem) {
    $where .= " and a.stem = $stem";
  }
  return "select a.pal as apal, a.pos as apos, a.eng as aeng, a.pdef as apdef, a.id as aid, a.stem as astem, "
        . "b.pal as bpal, b.id as bid, b.pos as bpos, b.eng as beng, b.pdef as bpdef "
        . "from all_words3 a, all_words3 b where a.stem=b.id and $where order by a.id = a.stem desc";
}

# a helper function to actually do the query
function do_query($pword,$stem=Null) {
  global $local_testing;
  $query = get_query($pword,$stem);
  mydebug("Gonna query $query",$local_testing); 
  $result = mysqli_query($GLOBALS['mysqli'],$query);
  $num_rows = mysqli_affected_rows($GLOBALS['mysqli']);
  mydebug("$query returned $num_rows",$local_testing);
  return $result;
}

# a helper function to format a single word
function single_word($pal,$pos,$eng,$pdef) {
  $wlist = '<ul style="list-style: none;">';
  if ($eng) {
    $wlist .= "<li>$eng</li>"; 
  }
  if ($pdef) {
    $wlist .= "<li><i>$pdef</i></li>";
  }
  $wlist .= '</ul>';
  return "<b>$pal</b> <i>$pos</i> $wlist";
}

function add_spacing($msg) {
  return "<span style='display:inline-block; width: 1em;'></span>$msg ";
}

# a helper function to format an entire entry
function formatted_word($row) {
  $pretty = single_word($row['apal'],$row['apos'],$row['aeng'],$row['apdef']); 
  if ($row['apos'] == 'var.') {
    $result2 = do_query($row['aeng'],$row['astem']);
    $row2 = mysqli_fetch_assoc($result2);  
    $pretty .= add_spacing("variant form of ");
    $pretty .= formatted_word($row2); # recurse to get the variant
  } else if ( ! $row['aeng'] and $row['aid'] != $row['astem'] ) {
    # if the english def is missing, add the root word (unless this is already the root word)
    $pretty .= add_spacing("from root word ");
    $root = single_word($row['bpal'],$row['bpos'],$row['beng'],$row['bpdef']);
    $pretty .= $root;
  }
  return $pretty;
}


# now actually do the work
$result = do_query($pword);
$html = "<div>";
$count = 0;
while ($row = mysqli_fetch_assoc($result)) { 
  if ($count > 0) {
    $html .= "<hr>"; # put in an html line
  }
  $pretty = formatted_word($row);
  $html .= "<span>$pretty</span>";
  $count = $count + 1; 
}

if ($count == 0) {
  $html .= "Unfortunately, <b>$pword</b> is not in the tekinged database.";
  $html .= "<br>Please consider reporting this at github.com/tekinged/missing."; 
}

$html .= "</div>";

echo $html;

#$return_text = "<div><span>need definition of $pword ($in_word) here</span></div>";
#echo $return_text;

# log here for debugging
#$myfile = fopen("/tmp/tooltip.txt", "a") or die("Unable to open file!");
#$txt = "Tooltip Log: $pword -> $return_text";
#fwrite($myfile, "\n". $txt);
#fclose($myfile);

?>

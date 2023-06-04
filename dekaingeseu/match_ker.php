<?php 
session_start();
include '../functions.php'; 
include 'dekaingeseu.php';

$GLOBALS['DEBUG'] = false;

function add_instructions() {
  print "<p>
        We have imported a large number of dictionary entries from the Kerresel dictionary.
        In order to correctly add them to our database, we need to match them with existing
        words in the database.
        <p>
        Your task is to read the word and its definition and decide how it 
        should be added to the database.  It can either be added to an existing word or as
        a new word.  
        <ol>
        <li>Read the Palauan definition and choose the word whose English definition matches.
        <li>If none match, choose to add it as a new word.
        <li>If you see any errors with any of the words, click the button to report them.
        <li>If you don't know, just ask for a new word.
        <ul><li>No guessing!  If you aren't sure, just go to the next word.</ul>
        <li>Please look at <a href='images/match_ker.png' target='_blank'>this example</a>.
        </ol> 
        ";
}

function match_ker_record_task($result) {
  $match = $_POST['taskresult'];
  $update = "allwid='$match'";
  Debug("Finished $result: $text!");
  return $update;
}

function match_ker_make_task($db_row) {
  add_instructions();
  $tekoi  = trim($db_row['tekoi']);
  $belkul = $db_row['belkul'];
  $id     = $db_row['id'];
  $values = array();
  $values['id'] = $id;
  #print "Found task: $col<br>\n";

  # pull the potential matches from Josephs
  $where = "pal like '$tekoi'";
  $q = "select id,stem,pal,pos,eng from all_words3 where $where";
  list($res,$num_rows) = check_table($q);
  if ($num_rows <= 0) {
    return Null;
  }

  $words = get_words($q,True);
  print "<h3>
         <p id='tab'>
         <div id='dekaingesau-choice'>
          Which of the below is the best match to<br>
          <span id='tab'><ul><li><b>$tekoi</b>  <i>$belkul</i></ul></span>
         </div>
         </p>
         </h3>
        ";
  print "<form method='post'>";
  print "<table>";
  $style = "style='background-color:blue'"; 
  foreach ($words as $idx => $word) {
    $choice = array();
    $choice[] = $word;
    $button = submitButton("MATCH.<br>This is same word.",$word->id(),$style);
    print "<tr><td valign='top'>$button</td><td>\n";
    print_words($choice);
    print "</td></tr>\n";
  }
  $button = submitButton("NEW.<br>This is a new word.",0,$style);
  print "<tr><td colspan='2'>$button</td></tr>\n";
  $style = "style='background-color:red'"; 
  $button = submitButton("ERROR.<br>Something wrong with one of these.",-1,$style);
  print "<tr><td colspan='2'>$button</td></tr>\n";
  $button = skipButton("UNSURE. Give me another",$style);
  print "<tr><td colspan='2'>$button</td></tr>\n";
  $button = quitButton("QUIT. I am done for now",$style);
  print "<tr><td colspan='2'>$button</td></tr>\n";
  print "</table>";
  print " </form> ";
  return $values;
}


$title = "Match Josephs Words to their Kerresel Equivalents";
$table = 'kerresel_words';
$config = array();
$config['intro'] = "Help import Kerresel words by matching them to their Josephs equivalents.";
$where = "imported=0 && isnull(allwid)";
$config['q_find'] = "select id,tekoi,belkul from $table where $where";
$config['get_count'] = "where $where";
$config['table'] = $table; 
$config['make_task'] = "match_ker_make_task"; # function pointer
$config['record_task'] = "match_ker_record_task"; # function pointer
$config['timeout'] = 180; # give people 3 minutes 
$task = new Dekaingeseu($title,$config);

?>

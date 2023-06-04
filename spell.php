<?php session_start();
include('functions.php');
$title="Palauan Spell Checker";
html_top($title,true);
belau_header($title);

echo "
  <div id='content-container'>
    <div id='aside'>
    </div><!--aside-->
    <div id='content'>
    ";

$GLOBALS['DEBUG'] = false;
$local_debug = false;

function in_db($word,$table,$col) {
  $query = "select $col from $table where $col regexp'" . '[[:<:]]' . $word . '[[:>:]]' ."'";
  list($result,$num_rows) = check_table($query);
  return ($num_rows>0);
}

$colors = array();
function get_color($word) {
  global $colors;
  $color = $colors{$word};
  if ($color == NULL) {
    if (in_db($word,'all_words3','pal')) {
      $colors{$word} = 'black';
    } else if (in_db($word,'misspellings','b')) {
      $colors{$word} = 'blue';
    } else {
      $colors{$word} = 'red';
    }
    querylog($word,$colors{$word} == 'black' ? 1 : 0,'sc',$colors{$word});
    Debug("Set $word to " . $colors{$word} . "<br>\n");
  }
  return $colors{$word};
}

$target = $_POST['lookup'];
if($_SERVER['REQUEST_METHOD'] == "POST" && strlen($target)>0) {

    Debug( "will check all words in $target", $local_debug );
    $target = html_entity_decode($target);
    $target = strip_tags($target);
    spelllog($target);
    Debug( "will check all words in stripped $target", $local_debug );

    $checked_string = ""; # build the string that gets put back in the textbox

    $words = preg_split('#\s+#', $target);
    foreach ($words as $orig_word) {
      $correct = true;
      $check_word = strtolower($orig_word);
      $check_word = preg_replace('/[\W]+/', '', $check_word); // remove punctuation
    
      if (ctype_space($check_word) || strlen($check_word)<1) {
        // ugh, somehow the preg_split puts empty words in...
        $checked_string .= $orig_word; 
      } else {
        $color = get_color($check_word);
        $checked_string .= " <font color='$color'>$orig_word</font>";
      }
    }

    echo "<div id='content'><p>\n";
    echo "Here is your spell-checked text.  Red words were not found in the dictionary.<br><br>";
    echo $checked_string;
    echo "<br><br>If you have spelling errors, edit your text below and try again.  Note that some missing words might actually be correct.  For example, we do not include
          all forms of all words.  If you have a word like <i>blik</i> or <i>blimam</i>, try changing it to <i>blil</i>.  If you have a word like <i>chilbedeterir</i>,
          try changing it to <i>cholebedii</i>.  If you find missing words, please report them below.";


    echo "<p>";
    //addlargeinput("<code contenteditable='true'>" . $checked_string . "</code>");
    addlargeinput($target);
    echo "</div><!-- content -->\n";
} else {
  $init_msg  = "Enter Palauan text here and then click the 'Check Spelling' button below. ";
  $init_msg .= "Red words were not found in this dictionary, either because they are misspelled or because the dictionary is incomplete. ";
  $init_msg .= "Blue words were not found in the official dictionary but are common abbreviations (e.g. <i>mora</i> is abbreviation of <i>mo er a</i>).";
  $init_msg .= "If you think you've found a missing ";
  $init_msg .= "entry or a mistake in the dictionary, please email info@tekinged.com or leave a comment below to report it.";
  $init_msg .= "If your text is very long, you should be able to resize this input window by dragging the bottom right corner.";
  echo "<p>$init_msg</p>";
  addlargeinput("","Moluches a tekoi er a Belau er tiang.");
} 
echo "</div><!-- content -->\n";
echo "</div><!-- content-container -->\n";
belau_footer("/spell.php");

?>
</body></html>

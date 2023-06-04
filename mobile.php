<?php session_start();
include('functions.php');

$SESSION['direction'] = 'pe'; # default

$found = false;
$notes = "";
$target = $_POST['lookup'];
?>

<HTML>
   <HEAD>
      <TITLE>
         P-E Lookup 
      </TITLE>
        <link rel='shortcut icon' type='image/ico' href='http://tekinged.com/favicon.ico?v=2'/>
   </HEAD>
<BODY>

<?php
#if($_SERVER['REQUEST_METHOD'] == "POST" && strlen($target)>0) {
if (!empty($_GET['lookup'])) {
  $target = $_GET['lookup'];
}
if (!empty($_GET['p'])) {
  $target = $_GET['p'];
}
if (!empty($_GET['lookup'])) {
}
if ($target) {
    $direction = $_POST['direction'];
    $_SESSION['direction'] = $direction;

    db_connect();
    $entries = get_all_entries2($target,$direction);
    #print "Found " . count($entries) . " words<br>\n";

    # check and see if present or past tense action noun
    if (count($entries)==0) {
        $tenses = array('ou' => 'present', 'ulu' => 'past');
        foreach($tenses as $prefix => $tense) {
          if (NULL != $noun = check_action_noun($target,$prefix)) {
            info("<h3>$target may be a $tense test <i>action noun</i> form of the noun <i>$noun</i></h3>");
            $notes .= "action_noun ";
            $found = 1;
          }
        }
    }

    # print the found entries.  In case there is audio, add the audio div
    #echo "WORDLIST<br>\n";
    ksort($entries);
    foreach ($entries as $key => $val) {
        $found = true;
        $html = $val->toHtml(NULL,NULL,true,false);
        $html = highlight_word($target,$html);
        echo $html;
        print "<BR>";
    }

    if (!$found) {
        # check and see if it is a pronoun or a number word with an 'ng' on the end
        # for example, if they search for techang or ongerung, return possible match on techa or ongeru
        # except, now most of these are entered into variants
    }

    if (!$found) {
        # might be a plural state verb like 'mekedeb'
        # try removing the '^me' and see if it matches a state verb
        # try adding an '^me' and see if it matches a state verb
    }

    if (!$found) {
        # might be a derived action nount like 'omeluches' which means writing
        # remove a '^o' and see if it matches an imperfect verb
    }

    if (!$found) {
        info( "<br>Sorry, $target not found.  Email info@tekinged.com to request adding it.\n");
    }

    querylog($target,$found,$direction,$notes);
} else {
    add_submit($found,$_SESSION['direction']);
} 

?>
</body></html>

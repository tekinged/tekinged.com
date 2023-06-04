<?php session_start();
include('functions.php');
include('quiz/quiz.php');
$title="Palauan-English Dictionary";
html_top($title,true);
belau_header($title);

$GLOBALS['DEBUG'] = false;
$_SESSION['direction'] = 'pe'; # default

$found = false;
$notes = "";
$force_lookup = false;
$specific_target = Null;
echo "<div id='content-container'>\n";

# using GET
if (! empty($_GET) ) {
  if (isset($_GET['p'])) {
    $target = $_GET['p'];
    $direction = 'pe'; 
  } else {
    if (isset($_GET['lookup'])) {
      $target = $_GET['lookup'];
    } else {
      $target=Null;
    }
    $direction = $_GET['direction'];
    $specific_target = $_GET['lookup_id'] ?? '';
  }
  $force_lookup = true;
  # clean the target string a bit to remove punctuation and leading/trailing whitespace
  if (isset($target)) {
    $target = preg_replace('/[^\w]+/', ' ', $target);
    $target = trim($target);
  }
}

# did the user do a POST?
# we are trying to deprecate this because it causes the annoying Chrome refresh message
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $force_lookup = true;
  $direction = $_POST['direction'];
  $target = $_POST['lookup'];
  $specific_target = $_POST['lookup_id'];
  Debug("specific target is $specific_target");
  # clean the target string a bit to remove punctuation and leading/trailing whitespace
  $target = preg_replace('/[^\w]+/', ' ', $target);
  $target = trim($target);
}


# this branch shows search results
if( $force_lookup && (strlen($target)>0 || $specific_target != NULL) ) {
    if (isset($target)) {
      $target = rtrim($target); # sometimes phones add trailing whitespace
    }
    $_SESSION['direction'] = $direction;
    if (isset($_GET['debug'])) {
      $GLOBALS['DEBUG'] = true; 
    }

    # reset any running quizes to make sure no-one is trying to cheat
    reset_quiz();

    Debug("Getting words with $specific_target or $target");
    $begin = microtime(true);
    if ($specific_target != Null) {
      $query = "select id,stem,pos,pal from all_words3 where id=$specific_target";
      $entries = get_words($query,True,Null,$specific_target);
    } else {
      $entries = get_all_entries2($target,$direction);
    }
    $elapsed = microtime(true) - $begin;
    Debug("Took $elapsed");

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

    print_words($entries,$target);
    $found = (count($entries)>0);

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
        # find the fuzzy matches in both dirs
        # currently this is pretty fast.  If it slows down, just do it in one direction
        $fuzzies = find_fuzzy($target,($direction == 'pe' || $direction == 'pp' ? 'pal' : 'eng'));
        if ($direction == 'pp') {
          $opp_fuzzies = find_fuzzy($target,'pdef');
        } else {
          $opp_fuzzies = find_fuzzy($target,($direction == 'ep' ? 'pal' : 'eng'));
        }

        # add the aside window since we don't need a ton of space
        #add_aside(True);

        # now add the main content and write the "sorry, not found." message
        # first set up some strings
        if ($direction == 'ep') {
          $target_lang = 'English';
          $opp_lang = 'Palauan';
          $opp_dir = 'pe';
          $example_lang = 'palauan';
        } else if ($direction == 'pp') {
          $target_lang = 'Palauan';
          $opp_lang = '';
          $opp_dir = 'pp';
          $example_lang = 'Palauan';
        } else {
          $target_lang = 'Palauan';
          $opp_lang = 'English';
          $opp_dir = 'ep';
          $example_lang = 'Palauan';
        }
        $mail_subj = "Missing $target_lang word $target";
        $opp_link = button_as_link($opp_fuzzies[0]->getWord(),$opp_dir,strtoupper($opp_fuzzies[0]->getWord()));

        $examples_links = "";
        foreach( array('sentences','proverbs','all_paltext') as $table ) {
          $mywhere = "where $example_lang regexp '" . '\\\\b' . $target . '\\\\b' . "'";
          if (get_count($table, $mywhere) > 0) {
            $link = get_examples_link($table,$target);
            if ($table == 'all_paltext') {
              $table = 'collected examples';
            }
            $examples_links .= "<li>The word $link does appear in our $table.<br>";
            break;
            #echo "Found in $table with $mywhere<br>";
          }
        }

        # now start printing the sorry message
        echo "<div id='content'>\n";
        echo "<div class='bigtext'>\n";
        $miss_msg = "<br><h3>Sorry, no $target_lang word, <i>$target</i>, found.</h3><div class='tab'>";

        # add a list for each fuzzy
        $miss_msg .= "<ul>\n"; 
        $flinks = array();
        foreach ($fuzzies as $fuzzy) {
          $fword = $fuzzy->getWord();
          Debug("Making button for $fword");
          $link = button_as_link($fuzzy->getWord(),$direction,strtoupper($fuzzy->getWord()));
          $flinks[] = $link;
          #$miss_msg .= "<li>Did you mean $link ?</li>\n";
        }
        $miss_msg .= "<li>Did you mean " . join(", ",$flinks) . "\n"; 
        $miss_msg .= "<li>Or the $opp_lang word $opp_link</li>\n";
        if ($direction == 'pe') {
          $pdef_fuzzies = find_fuzzy($target,'pdef');
          $pdef_link = button_as_link($pdef_fuzzies[0]->getWord(), 'pp', strtoupper($pdef_fuzzies[0]->getWord()));
          $miss_msg .= "<li>Or search for $pdef_link in the Palauan definitions\n";
        }
        $miss_msg .= $examples_links;

        /*
        // turn off the in-dict page search since they are fully imported
        $dict_link = get_dict_link($target);
        $kerr_link = get_kerresel_link($target);
        $miss_msg .= "<li>Look for $target on $dict_link in the Josephs dictionary.\n";
        $miss_msg .= "<li>Look for $target on $kerr_link in the Kerresel dictionary.\n";
        */


        $miss_msg .= "</lu>\n";
        $miss_msg .= "</div><div>Or please <a href='mailto:info@tekinged.com?subject=$mail_subj'>email us</a> to request adding it.</div>\n";

        info($miss_msg . "<br><br>"); 
        echo "</div>\n";

        # now add the submit button so they can try again
        add_submit($found,$_SESSION['direction']);
        echo "</div><!-- content -->\n";

        add_aside();
    } else {
        echo "<div id='contentnomin'>\n";
        add_submit($found,$_SESSION['direction']);
        echo "</div><!-- contentnomin -->\n";
    }

    querylog($target,$found,$direction,$notes);
    $extra = ": $direction -> $target ($found)";
} else {
    echo "<div id='content' min-height=600>\n";
    echo "<div id='bothtab'>\n";
    add_submit($found,$_SESSION['direction']);
    echo "</div><!-- bothtab -->\n";
    echo "</div><!-- content -->\n";
    add_aside();
    $extra = NULL;
} 
echo "</div><!-- content-container -->\n";
belau_footer(NULL,$extra);

function add_aside($empty=False) {
  echo "<div id='aside'>\n";
  if ($empty == False) {
    $total = get_count("all_words3");
    $groups = get_count("all_words3","where stem=id or isnull(stem)");
    $ptotal = get_count("proverbs");
    $etotal = get_count("examples");
    $etotal += get_count("upload_sentence");
    $josephs = get_count("all_words3","where !isnull(josephs)");
          #<ul><li>$josephs were missing from Josephs</ul>
          #<li>$groups word groups
          #Currently cataloging<br>
    # here's a way which shows the nice word bubble....
    #echo "<center><a href=/images/logo3.png><img src=/images/logo3.png width=60%></a></center>\n";
    echo "
          <b>TEKINGED.COM</b>:
          Our Online Palauan Dictionary<br>
          <div class='tab'>
          <ul>
          <li>$total Palauan words in $groups groups
          <li>$ptotal proverbs, $etotal example sentences.
          </ul>
          Compiled from <a href=/misc/dictpage.php>Josephs</a>, <a href=/books/kerresel.php>Ramarui</a>, and <a href=/about.php>volunteers</a>.
          <br>
          <br>
          Latest additions: <br>
<ul>
<li> <a href=http://tekinged.com/quiz/q_etymology.php>Etymology Quiz</a>
<li> <a href=https://github.com/tekinged/missing/issues>Missing Words Committee</a>
<li> <a href=http://tekinged.com/books/kindle.php>Kindle Dictionary</a>
<li> <a href=http://tekinged.com/misc/pdf.php?file=sim_coordinated-investigation-of-micronesian-anthropology_1951-1953_21>1949 Anthropology Report on Palau</a>
</ul>
          </div><!-- tab -->
      ";

      /*
<li> Cheldecheduch er a Belau, Ongeru el Hong <a href=http://tekinged.com/books/epubs/cheldecheduch_2017.epub>eBook</a>
<li> Chisel a Iungs er a Belau <a href=http://tekinged.com/books/epubs/chisel_a_iungs.epub>eBook</a>
<li> Siukang er a Belau <a href=http://tekinged.com/books/epubs/siukang_2017.epub>eBook</a> 
          Also quizzes, grammar rules, and more.<br>
          <br>
          </div><!-- tab-->
          <div class='tab'>
          Checkout our latest additions: <br>
            <ul>
              <li><a href='/books/77josephs.php'>The 1977 Dictionary</a>!
              <li><a href='/dekaingeseu/'>New Dekaingeseu Requests</a>!
            </ul>
          </div><!-- tab-->
      ";
      */
  }
  echo "</div><!-- aside>\n";
}

?>

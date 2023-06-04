<?php 
require_once './quiz.php';
session_start();
include '../functions.php'; 
$title="Palauan Language Quiz";
html_top($title);
belau_header($title);

/* More question types:
1. Analogies using parts of speech
2. Palauan words to Palauan definitions
3. English definition to Palauan word
4. Sentences with mixed up er/a/el/le and choose the grammatically correct one
5. Palauan definition to English definition
6. Definition to word
*/


$GLOBALS['DEBUG'] = false;

function finish_quiz($status) {
  $correct = $status->correct();
  $total = $status->asked();

  if (isset($_POST['name'])) {
    $me=$_SERVER["REQUEST_URI"];
    $_SESSION['qstatus'] = new Quiz();
    $name = $_POST['name'];
    $perc = ($total > 0 ? 100 * ($correct/$total) : 0);
    echo "$name, your score was $correct out of $total.<br>\n";
    if ($perc >= $GLOBALS['min_hi']) {
      echo "Congrats!  You got a high score!<br>\n";
    } else {
      echo "Unfortunately you did not get a high score.  Try again!<br>\n";
    }
    echo "Click <a href='$me'>here</a> to start a new quiz.\n";
    echo "<br>If you enjoyed the quiz and you are a native speaker, please consider helping our website get even better by recording yourself speaking some example sentences: 
         <a href='/dekaingeseu/audio_examples.php'>Dekaingeseu!</a>.<br>\n";
    quizzedlog($name,$correct,$total); 
  } else {
    echo "<form method='post'>\n";
    add_input("Enter your name to see if you got a high score:", 'name',False);
    echo "</form>\n";
    quizzedlog('',$correct,$total); # remember it even if they don't submit name
  }
}

function make_question($quiz) {
  #Debug( "Time to make a question." );
  $qfilter = $quiz->get_filter();
  $nvulg = not_vulgar();
  $filter = "!isnull(pos) && length(eng)>0 && pos not like 'var.' && pos not like 'abbr.' && pos not like 'mod.' && length(pos)>1 && $nvulg && $qfilter";

  #Debug( $qfilter . "<br>\n" );

  $table = 'all_words3';
  $table = 'qz_eng';

  # first get the pos to use
  # get a count of all possible pos's, then randomly select from them weighted by how many they are
  $all_pos = array();
  $pos_q="select pos,count(*) as c from $table where $filter group by pos having c>=5";
  $r = query_or_die($pos_q);
  $total=0;
  while($row = $r->fetch_array(MYSQLI_NUM)) { 
    $all_pos[$row[0]] = $row[1] + $total;
    $total+=$row[1];
  }
  $rand=rand(0,$total);
  $pos = 'n.'; # set a default pos in case the below loop (inexplicably) doesn't work.  
  foreach($all_pos as $p => $c) {
    if ($rand <= $c) { 
      $pos = $p;
      break;
    }
  }
  Debug( "Will make a question using pos = $pos<br>\n" );

  # then get the five words
  $q_q="select pal,eng,id from $table where pos like '$pos' and $filter order by rand() limit 5;";
  #if (is_johnbent()) {
  #  $q_q="select pal,eng,id from $table where stem = 458 and length(eng)>0 order by rand() limit 5;";
  #}
  $r = query_or_die($q_q);
  $quest = new Question($r,$quiz);
  $quest->toHtml();
  $_SESSION['question'] = $quest;
}


function add_question($status) {
  echo "<br>";
  make_question($status);
  echo "<br>";
  $progress = $status->progressStr();
  echo "$progress<br>\n";
  $_SESSION['qstatus'] = $status;
}

?>

    <div id="content-container">
    <?php 
      $config = array();
      $config['type'] = 'Classic';
      print_quiz_aside($config);
    ?>
        <div id="content"> 
            <?php 
            echo "<h2>$title</h2>"; 
            echo "<p class='tab'>";
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
              if (isset($_POST['name'])) { 
                finish_quiz($_SESSION['qstatus']); 
              } else {
                #print_r($_POST);
                #print_r($_SESSION);
                $status = check_answer();
                $remaining = $status->remaining();
                if ($remaining > 0) {
                  add_question($status);
                } else {
                  finish_quiz($status); # ask them for their name
                }
              }
            } else { 
              if ( isset($_SESSION['qstatus'] ) ) {             
               $status = $_SESSION['qstatus'];
              } else {
                $status = new Quiz();
              }
              add_question($status);
            }
            ?>
            
            <p>
        </div>

        <?php 
          if(isset($status)){
            $extra = $status->getExtraMsg();
          } else {
            $extra = Null;
          } 
          belau_footer(curPageURL(),$extra); 
        ?>
    <div>

</body>
</html>

<?php

function reset_if_change($config) {
  if (isset($_SESSION['qtype'])) {
    if ($config['type'] != $_SESSION['qtype']) {
      #echo "Switching quiz type from " . $_SESSION['qtype'] . " to " . $config['type'] . "<br>\n";
      reset_quiz();
    }
  }
  $_SESSION['qtype'] = $config['type'];
}

function make_quiz($config) {
  reset_if_change($config);

  $title=$config['title'];
  html_top($title,true);
  belau_header($title);
  echo "<div id='content-container'>";
  print_quiz_aside($config);
  echo "<div id='content'>";
  # set up java which is needed for the audio quiz
  print "<div id='jquery_jplayer'></div><!-- jquery_jplayer -->\n";
  echo "<h2>$title</h2>"; 
  echo "<p class='tab'>";
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['name'])) { 
      finish_quiz($_SESSION['qstatus'],$config); 
    } else {
      #print_r($_POST);
      #print_r($_SESSION);
      $status = check_answer($config);
      $remaining = $status->remaining();
      if ($remaining > 0) {
        add_question($status,$config);
      } else {
        finish_quiz($status,$config); # ask them for their name
      }
    }
  } else { 
    if ( isset($_SESSION['qstatus'] ) ) {             
      $status = $_SESSION['qstatus'];
      # are they cheating in here and just hitting refresh to get a better question?
      if ($status->asked() > 0) {
        $status->addAnswer(0);
      } else if ($status->asked() == 0) {
        # give them a new quiz....
        $status = new Quiz();
      }
    } else {
      $status = new Quiz();
    }
    add_question($status,$config);
  }
  echo "</div>";
  $extra = (!empty($status) ? $status->getExtraMsg() : NULL);
  belau_footer(curPageURL(),$extra); 
  echo "</body></html>";
}

function add_question($status,$config) {
  echo "<br>";
  $config['make_q']($status); # call our function pointer
  echo "<br>";
  $progress = $status->progressStr();
  echo "$progress<br>\n";
  $_SESSION['qstatus'] = $status;
}


function finish_quiz($status,$config) {
  $correct = $status->correct();
  $total = $status->asked();
  $qtype = $config['type'];

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
    echo "<br>If you enjoyed the quiz, please consider helping our website get even better by adding more trivia questions: 
         <a href='/dekaingeseu/d_trivia.php'>Dekaingeseu!</a>.<br>\n";
    quizzedlog($name,$correct,$total,$qtype); 
  } else {
    echo "<form method='post'>\n";
    add_input("Enter your name to see if you got a high score:", 'name',False);
    echo "</form>\n";
    quizzedlog('',$correct,$total,$qtype); # remember it even if they don't submit name
  }
}

# takes a list of possible answers and the actual answer
# compares the actual answer to each possible answer and returns
# the percent similarity between the actual answer and the closest possible match
# returns percent / 100
function get_score($possibles,$answer) {
  $answer = trim($answer); # remove trailing whitespace
  $score = 0;
  foreach ($possibles as $possible) {
    similar_text($possible, $answer,$perc);
    $perc /= 100;
    $score = max($score,$perc);
  }
  #print "Answer is $answer; should be $correct [SCORE: $score].";
  return $score;
}

# takes the numeric score and converts it to what is needed for the answer message 
function score_to_result($score) {
  if ($score == 1) {
    return array($score,'quiz-correct','Correct!');
  } else if ($score > 0.8) {
    return array($score,'quiz-almost','Almost...');
  } else {
    return array(0,'quiz-incorrect','Sorry.');
  }
}

function quizzedlog($name,$correct,$total,$qtype='classic') {
  list($s,$p,$u,$a) = scrape_ip();
  $query = "INSERT INTO log_quizzes (name,correct,total,ip,agent,user,proxy,quiztype) VALUES ('$name','$correct','$total','$s','$a','$u','$p','$qtype')";
  $result = mysqli_query($GLOBALS['mysqli'],$query) or warn("Error in mysql_query for log_quizzes  Probably string too long...");
}

function quizlog($pword,$correct,$qtype='classic') {
  list($s,$p,$u,$a) = scrape_ip();
  $pword=mysqli_real_escape_string($GLOBALS['mysqli'],$pword);
  $query = "INSERT INTO log_quiz (query,correct,ip,agent,user,proxy,quiztype) VALUES ('$pword','$correct','$s','$a','$u','$p','$qtype')";
  $result = mysqli_query($GLOBALS['mysqli'],$query) or warn("Error in mysql_query for quizlog.  Probably string too long...");
}

Class Quiz {
  public function __construct() {
    $this->questions = 0;
    $this->correct = 0;
    $this->stems = array();
    $this->extra_msg = NULL;
    $this->total = 25;
    if (0 && is_johnbent()) {
      $this->total = 3;
    }
  }

  function addExtraMsg($word,$score) {
    $this->extra_msg = ": $word ($score)";
  }

  function getExtraMsg() {
    return $this->extra_msg;
  }

  function addAnswer($correct) {
    # put it in the loop because sometimes people refresh on the enter name page
    if ($this->remaining() > 0) {
      $this->questions++;
      $this->correct += $correct;
    }
  }

  function remaining() {
    return $this->total - $this->questions;
  }

  function asked() {
    return $this->questions;
  }

  function add_stem($stem) {
    $this->stems[] = $stem;
  }

  function get_filter($field = "id") {
    $filter = '(';
    foreach( $this->stems as $stem ) {
      $filter .= "$field != '$stem' && ";   
    }
    $filter .= " 1)";
    return $filter;
  }

  function correct() {
    return $this->correct;
  }

  function progressStr() {
    $remaining = $this->remaining();
    $correct = $this->correct;
    if ($correct != (int)$correct) {
      $correct = sprintf("%.2f",$correct);
    }
    $str = "<div id='quiz-progress'>" .
           "PROGRESS: <ul><li>" . $this->questions . "/" . $this->total . " questions answered.</li>\n" .
           "<li>" . $correct . "/" . $this->questions . " correct.</li>\n" .
           "<li>Answer $remaining more questions to finish the quiz and try to get a high score.</li>" .
           "</div><!-- quiz_progress -->"
           ;
    return $str;
  }
};

Class Question {

  public function __construct($rows,$quiz) {
    $this->answers = array();
    while($row = mysql_fetch_row($rows)) {
      $this->answers[] = $row[1];
      $this->question = $row[0];
      $this->correct = $row[1];
      $quiz->add_stem($row[2]);
    }
    $this->myid = $quiz->asked() + 1;
    shuffle($this->answers);
  }

  function getCorrect() {
    return $this->correct;
  }

  function getQuestion() {
    return $this->question;
  }

  function toHtml() {
    $core = "The Palauan word <b>" . $this->question . "</b> means which of the following:\n";
    q2html($this->myid,$core,$this->answers);
  }
}

function getHash($string) {
  return hash('md4',$string);
}

function inputq($id,$q,$extra=Null) {
  startq($id);
  echo $q;
  echo "<form method='post'>\n";
  add_input("", 'answer',False);
  echo "</form>\n";
  echo $extra;
  endq();
}

function startq($id) {
  echo "<div class='tab' id='quiz-question'>\n";
  echo "QUESTION " . $id . ": ";
}

function endq() {
  echo "</div><!-- tab -->\n";
}

function q2html($id,$core,$answers) { 
    startq($id);
    echo "$core";
    echo "<div class='tab'>\n";
    echo "<form method='post' name='answer'>\n";
    foreach ( $answers as $answer ) {
      $value = $answer[1]; 
      echo "<input type='radio' name='answer' value='$value'>&nbsp;$answer[0]<br>\n";
    }
    echo "<input type='submit'>\n";
    echo "</form>\n";
    echo "</div><!-- tab -->\n";
    endq();
}

function check_answer($config) {
    $answer   = $_POST['answer'];
    $question = $_SESSION['question'];
    $status   = $_SESSION['qstatus'];
    $correct  = $question->getCorrect();
    $pword    = $question->getQuestion();
    Debug("$answer == $correct ($correct)");
    if (array_key_exists('check_q',$config)) {
      list($score,$divclass,$msg) = $config['check_q']($question,$answer);
    } else {
      if ($answer == $correct) {
        $score = 1;
        $divclass = 'quiz-correct';
        $msg = 'Correct!';
      } else {
        $score = 0;
        $divclass = 'quiz-incorrect';
        $msg = 'Sorry.';
      }
    }
    $qmsg     = $question->getMessage();
    $status->addExtraMsg($pword,$score);
    echo "<table><tr><td><div id='$divclass'>" .
         "$msg $qmsg<br>\n";
    if ($score == 0 and array_key_exists('explanation',$config)) {
      $config['explanation']($question); 
    }
    echo "</div><!-- $divclass --></td></tr></table>";
    $status->addAnswer($score);
    quizlog($pword,$score,$config['type']);
    return $status;
}

function print_quiz_aside($config=Null) {
  if ($config) {
    $type = $config['type']; 
  } else {
    $type = Null;
  }
  $interesting = $GLOBALS['interesting']['quiz'];
  $difficult = button_as_link('quiz',NULL,ucfirst($interesting[1]),'/show_words.php');
  echo "<div id='aside'>\n";
  echo "<div class='tab'>\n";
  $GLOBALS['min_hi'] = print_high_scores($type); 
  echo "<a href='/quiz/quiz_high.php'>Top 200 Scores All Time</a><br>\n";
  echo "$difficult";
  echo "</div><!-- tab -->\n";
  echo "</div><!-- aside -->\n";
}

function print_high_scores($type='Classic') {
  $quantity = 10;
  if ($type != Null) {
    $typefilter = "quiztype like '$type' and";
    $typelabel = " $type";
  } else {
    $typelabel = "";
    $typefilter = "quiztype not like 'Audio' and";
  }
  echo "Top $quantity$typelabel Scores This Month<br><ul>\n";
  $q = "select name,correct/total*100 as perc,quiztype as type from log_quizzes where $typefilter length(name)>0 and total>=25 " .
       "and month(added)=month(now()) and year(added)=year(now()) order by perc DESC,added DESC limit $quantity";
  $r = query_or_die($q);
  $scores = 0;
  while($row = $r->fetch_array(MYSQLI_NUM)) { 
    $name=$row[0];
    $perc=$row[1];
    if ($type == Null) {
      printf("<li>%6.2f%% [%8s] %s",$perc,$row[2],$name);
    } else {
      printf("<li>%6.2f%% %s",$perc,$name);
    }
    $low_high = $perc;
    $scores++;
  }
  echo "</ul>\n";
  return ($scores < $quantity ? 0 :$low_high);
}
  

function reset_quiz() {
    $_SESSION['qstatus'] = new Quiz(); 
}

?>

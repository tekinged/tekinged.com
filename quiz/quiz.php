<?php

Class Quiz {
  /*
  var $questions;
  var $correct;
  var $stems;
  var $total;
  var $extra_msg;
  */

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

  function get_filter() {
    $filter = '(';
    foreach( $this->stems as $stem ) {
      $filter .= "stem != '$stem' && ";   
    }
    $filter .= " 1)";
    return $filter;
  }

  function correct() {
    return $this->correct;
  }

  function progressStr() {
    $remaining = $this->remaining();
    $str = "<div id='quiz-progress'>" .
           "PROGRESS: <ul><li>" . $this->questions . "/" . $this->total . " questions answered.</li>\n" .
           "<li>" . $this->correct . "/" . $this->questions . " correct.</li>\n" .
           "<li>Answer $remaining more questions to finish the quiz and try to get a high score.</li>" .
           "</div><!-- quiz_progress -->"
           ;
    return $str;
  }
};

Class Question {

  public function __construct($rows,$quiz) {
    $this->answers = array();
    while($row = $rows->fetch_array(MYSQLI_NUM)) { 
      $this->answers[] = $row[1];
      $this->question = $row[0];
      $this->correct = $row[1];
      $quiz->add_stem($row[2]);
    }
    $this->myid = $quiz->asked() + 1;
    shuffle($this->answers);
  }

  function getHash($string) {
    return hash('md4',$string);
  }

  function getCorrect() {
    return $this->correct;
  }

  function getQuestion() {
    return $this->question;
  }

  function toHtml() {
    echo "<div class='tab' id='quiz-question'>\n";
    echo "QUESTION " . $this->myid . ": ";
    echo "The Palauan word <b>" . $this->question . "</b> means which of the following:\n";
    echo "<div class='tab'>\n";
    echo "<form method='post' name='answer'>\n";
    foreach ( $this->answers as $answer ) {
      $value = $this->getHash($answer); 
      echo "<input type='radio' name='answer' value='$value'>&nbsp;$answer<br>\n";
    }
    echo "<input type='submit'>\n";
    echo "</form>\n";
    echo "</div><!-- tab -->\n";
    echo "</div><!-- tab -->\n";

  }
}

function check_answer() {
    $answer = $_POST['answer'];
    $question = $_SESSION['question'];
    $status   = $_SESSION['qstatus'];
    $correct = $question->getCorrect();
    $pword   = $question->getQuestion();
    $hcorrect = $question->getHash($correct);
    Debug("$answer == $correct ($hcorrect)");
    if ($answer == $hcorrect) {
      $score = 1;
      $divclass = 'quiz-correct';
      $msg = 'Correct!';
    } else {
      $score = 0;
      $divclass = 'quiz-incorrect';
      $msg = 'Sorry.';
    }
    $status->addExtraMsg($pword,$score);
    echo "<div id='$divclass'>" .
         "$msg <b>$pword</b> means <i>$correct</i>.<br>\n" .
         "</div><!-- $divclass -->";
    $status->addAnswer($score);
    quizlog($pword,$score);
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
  echo "<a href='/quiz/quiz_high.php'>Top 100 Scores All Time</a><br>\n";
  echo "$difficult";
  echo "</div><!-- tab -->\n";
  echo "</div><!-- aside -->\n";
}

function quizzedlog($name,$correct,$total) {
  list($s,$p,$u,$a) = scrape_ip();
  $query = "INSERT INTO log_quizzes (name,correct,total,ip,agent,user,proxy,quiztype) VALUES ('$name','$correct','$total','$s','$a','$u','$p','Classic')";
  $result = mysqli_query($GLOBALS['mysqli'],$query) or warn("Error in mysql_query for log_quizzes  Probably string too long...");
}

function quizlog($pword,$correct) {
  list($s,$p,$u,$a) = scrape_ip();
  $query = "INSERT INTO log_quiz (query,correct,ip,agent,user,proxy,quiztype) VALUES ('$pword','$correct','$s','$a','$u','$p','Classic')";
  $result = mysqli_query($GLOBALS['mysqli'],$query) or warn("Error in mysql_query for quizlog.  Probably string too long...");
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

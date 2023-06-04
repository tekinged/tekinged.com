<?php 
require_once './quiz2.php';
session_start();
include '../functions.php'; 

$GLOBALS['DEBUG'] = false;

Class RengQuestion {

  public function __construct($quiz) {

    # get the question word and its group
    $filter = $quiz->get_filter();
    $table = 'all_words3';
    $q_q = "select pal,eng,id,stem from all_words3 where not isnull(tags) and tags like 'reng' and pos like 'expr.' and $filter order by rand() limit 5";
    $r = query_or_die($q_q);

    # get the right answer and make the question
    $row = $r->fetch_assoc();
    extract($row);
    $this->question = "Which of the following is the best definition for<br><br><b>$pal</b><br><br>";
    $this->answers = array();
    list($this->correct,$this->correct_pal) = array($id,$eng); 
    array_push($this->answers,array($eng,$id));
    $quiz->add_stem($id);

    # get incorrect answers
    while($row = $r->fetch_assoc()) {
      extract($row);
      array_push($this->answers,array($eng,$id));
      $quiz->add_stem($id);
    }
    
    shuffle( $this->answers );
    $this->myid = $quiz->asked() + 1;
  }

  function getMessage() {
    return 'The correct answer was ' . strtoupper($this->correct_pal) . '.';
  }

  function getQuestion() {
    #Debug("Return " . $this->correct_pal);
    return $this->correct_pal;
    return $this->question;
  }

  function getCorrect() {
    return $this->correct;
  }

  function toHtml() {
    q2html($this->myid,$this->question,$this->answers);
  }
}

function reng_make_question($quiz) {
  $quest = new RengQuestion($quiz);
  $quest->toHtml();
  $_SESSION['question'] = $quest;
}

$config = array();
$config['title'] = "Palau Language Reng Expression Quiz"; 
$config['make_q'] = "reng_make_question"; # function pointer
$config['type'] = "Reng Expression";

make_quiz($config);
?>

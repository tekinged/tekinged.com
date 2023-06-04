<?php 
require_once './quiz2.php';
session_start();
include '../functions.php'; 

$GLOBALS['DEBUG'] = false;

Class TQuestion {
  var $answers;
  var $correct;
  var $question;
  var $myid;

  function TQuestion($rows,$quiz) {
    $row = $rows->fetch_assoc(); 
    extract($row);
    $this->question = $q; 
    $this->correct = $a; 
    $this->answers = array(array($a,$a),array($o1,$o1),array($o2,$o2),array($o3,$o3),array($o4,$o4));
    shuffle( $this->answers );
    $quiz->add_stem($id);
    $this->myid = $quiz->asked() + 1;
  }

  function getMessage() {
    return 'The correct answer was "' . $this->correct . '".';
  }

  function getQuestion() {
    return $this->question;
  }

  function getCorrect() {
    return $this->correct;
  }

  function toHtml() {
    q2html($this->myid,$this->question,$this->answers);
  }
}

function trivia_make_question($quiz) {
  #Debug( "Time to make a question." );
  $qfilter = $quiz->get_filter();
  $filter = "$qfilter";

  #Debug( $qfilter . "<br>\n" );

  $table = 'upload_trivia';

  $q_q="select * from $table where $filter order by rand() limit 1;";
  $r = query_or_die($q_q);
  $quest = new TQuestion($r,$quiz);
  $quest->toHtml();
  $_SESSION['question'] = $quest;
}

$config = array();
$config['title'] = "Palau Language Trivia Quiz"; 
$config['make_q'] = "trivia_make_question"; # function pointer
$config['type'] = "Trivia";

make_quiz($config);
?>

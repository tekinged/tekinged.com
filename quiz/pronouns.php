<?php 
require_once './quiz2.php';
session_start();
include '../functions.php'; 

$GLOBALS['DEBUG'] = false;

Class PQuestion {

  public function __construct($rows,$quiz) {
    $row = $rows->fetch_assoc(); 
    extract($row);
    $this->answer = $pal;
    $this->question = $eng; 
    $this->correct = $id;
    $quiz->add_stem($id);
    $this->myid = $quiz->asked() + 1;
  }
  
  function setAnswer($answer) {
    Debug( "Changing answer to $answer<br>\n" );
    $this->answer = $answer;
  }

  function getMessage() {
    return 'The correct pronoun was "' . $this->answer . '".';
  }

  function getQuestion() {
    return $this->question;
  }

  function getCorrect() {
    return $this->correct;
  }

  function toHtml() {
    $html = "Please type the Palauan pronoun meaning <b><i>" . $this->question . "</b></i></br>";
    inputq($this->myid,$html);
  }
}

function pronoun_check_answer($question,$answer) {
  $wid = $question->getCorrect();
  $q = "select pal from all_words3 where id=$wid or (stem=$wid and pos like 'var.')";
  $possibles = array();
  $r = query_or_die($q);
  while($row = $r->fetch_array(MYSQLI_NUM)) { 
    $possibles[] = $row[0];
  }
  $correct = implode(' or ',$possibles);
  $question->setAnswer($correct);
  $score = get_score($possibles,$answer);
  #print "Answer is $answer; should be $correct [SCORE: $score].";
  return score_to_result($score);
}

function pronoun_make_question($quiz) {
  #Debug( "Time to make a question." );
  $qfilter = $quiz->get_filter();
  $filter = "pos like 'pro.' && $qfilter";

  #Debug( $qfilter . "<br>\n" );

  $table = 'all_words3';

  $q_q="select eng,pal,id from $table where $filter order by rand() limit 1;";
  $r = query_or_die($q_q);
  $quest = new PQuestion($r,$quiz);
  $quest->toHtml();
  $_SESSION['question'] = $quest;
}

$config = array();
$config['title'] = "Palau Language Pronouns Quiz"; 
$config['make_q'] = "pronoun_make_question"; # function pointer
$config['check_q'] = "pronoun_check_answer"; # function pointer
$config['type'] = "Pronouns";

make_quiz($config);
?>

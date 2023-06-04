<?php 
require_once './quiz2.php';
session_start();
include '../functions.php'; 

$GLOBALS['DEBUG'] = false;


Class SynQuestion {

  public function __construct($quiz) {

    # get the question word and its group
    $filter = "(1)"; # can later try to make sure it doesn't ask the same thing twice
    $filter = $quiz->get_filter();
    $table = 'all_words3';
    $q_q = "select pal,eng,id,stem,origin from all_words3 where not isnull(origin) and length(origin)=1 order by rand() limit 5;";
    $r = query_or_die($q_q);

    # get the right answer and make the question
    $row = $r->fetch_assoc();
    extract($row);
    $eng = rtrim($eng,'.'); # trim any trailing period
    #$pdef = str_replace($pal,'<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>',$pdef);
    $this->question = "What is the language of origin for <b><em>$pal</em></b> ($eng)?";

    # populate the possible answers
    $this->answers = array();
    $Answers = array('English', 'German', 'Japanese', 'Malay', 'Tagalog', 'Yapese', 'Spanish');
    foreach ( $Answers as $answer ) {
      array_push($this->answers,array($answer,$answer[0]));
      if ($origin == $answer[0]) {
        $this->correct     = $origin;
        $this->correct_pal = $answer;
      }
    }
    sort( $this->answers );
    
    $this->myid = $quiz->asked() + 1;
  }

  function getMessage() {
    return 'The correct answer was ' . strtoupper($this->correct_pal) . '.';
  }

  function getQuestion() {
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

function etymology_make_question($quiz) {
  #Debug( "Time to make a question." );
  #$filter = "$qfilter";

  #Debug( $qfilter . "<br>\n" );
  $quest = new SynQuestion($quiz);
  $quest->toHtml();
  $_SESSION['question'] = $quest;
}

$config = array();
$config['title'] = "Palau Language Etymology Quiz"; 
$config['make_q'] = "etymology_make_question"; # function pointer
$config['type'] = "Etymology";

make_quiz($config);
?>

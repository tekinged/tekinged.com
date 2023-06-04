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
    $q_q = "select pal,pdef,id,stem from all_words3 where not isnull(tags) and tags rlike 'charm|cheled|tree|plant|fish' and tags not rlike 'fishing' and length(pdef) > 50 and $filter order by rand() limit 5";
    $r = query_or_die($q_q);

    # get the right answer and make the question
    $row = $r->fetch_assoc();
    extract($row);
    $pdef = str_replace($pal,'<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>',$pdef);
    $this->question = "Which of the following is the best match for the missing word?<br><br>$pdef<br><br>";
    $this->answers = array();
    list($this->correct,$this->correct_pal) = array($id,$pal); 
    array_push($this->answers,array($pal,$id));
    $quiz->add_stem($id);

    # get incorrect answers
    while($row = $r->fetch_assoc()) {
      extract($row);
      array_push($this->answers,array($pal,$id));
      $quiz->add_stem($id);
    }
    
    shuffle( $this->answers );
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

function synonym_make_question($quiz) {
  #Debug( "Time to make a question." );
  #$filter = "$qfilter";

  #Debug( $qfilter . "<br>\n" );
  $quest = new SynQuestion($quiz);
  $quest->toHtml();
  $_SESSION['question'] = $quest;
}

$config = array();
$config['title'] = "Palau Language Living Things Quiz"; 
$config['make_q'] = "synonym_make_question"; # function pointer
$config['type'] = "Living Things";

make_quiz($config);
?>

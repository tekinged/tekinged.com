<?php 
require_once './quiz2.php';
session_start();
include '../functions.php'; 

$GLOBALS['DEBUG'] = false;

Class SynQuestion {

  function getAnswer($where,$limit,$order="rand()") {
    $q = "select a.pal as pal,a.id as id ,a.pos from all_words3 a inner join "
         . "(select * from (select id,pos from synonyms_populated where $where && vulgar=0 order by rand()) T1 order by $order limit $limit) as s ON a.id = s.id;";
    $r = query_or_die($q);
    while($row =  $r->fetch_assoc()) {
      extract($row);
      array_push($this->answers,array($pal,$id));
    }
    return array($id,$pal);
  }

  public function __construct($quiz) {

    # get the question word and its group
    $filter = "(1)"; # can later try to make sure it doesn't ask the same thing twice
    $filter = $quiz->get_filter();
    $table = 'synonyms_populated';
    $q_q="select mygrouping,id as word,pos,pal from $table where $filter and vulgar=0 order by rand() limit 1;";
    $r = query_or_die($q_q);
    $row = $r->fetch_assoc(); 
    extract($row);
    $g = $mygrouping;
      
    $this->question = "Which of the following is a synonym for " . strtoupper($pal) . ":";

    # get a correct answer
    $this->answers = array();
    $where = "mygrouping = $g and id != $word";
    list($this->correct,$this->correct_pal) = $this->getAnswer($where,1);

    # get incorrect answers
    $where = "mygrouping != $g"; 
    $order = "pos not like '$pos'";
    $this->getAnswer($where,4,$order);
    
    shuffle( $this->answers );
    $quiz->add_stem($word);
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
$config['title'] = "Palau Language Synonyms Quiz"; 
$config['make_q'] = "synonym_make_question"; # function pointer
$config['type'] = "Synonyms";

make_quiz($config);
?>

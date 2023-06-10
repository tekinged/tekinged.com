<?php 
require_once './quiz2.php';
session_start();
include '../functions.php'; 

$GLOBALS['DEBUG'] = false;

Class PQuestion {

  public function __construct($rows,$quiz) {
    $row = $rows->fetch_assoc(); 
    extract($row);
    $this->pal = $pal;
    $this->pos = $pos;
    $this->answer = $answer;
    $this->eng = $eng; 
    $this->apos = $apos;
    $this->aid = $aid;
    $this->wid = $wid;
    $this->stem = $stem;
    $quiz->add_stem($wid);
    $this->myid = $quiz->asked() + 1;
    $this->correct = Null;
  }
  
  function setAnswer($answer) {
    Debug( "Changing answer to $answer<br>\n" );
    $this->answer = $answer;
  }

  function getMessage() {
    return 'The correct answer was "' . $this->answer . '".';
  }

  function getQuestion() {
    return $this->answer;
  }

  function getCorrect() {
    return $this->correct;
  }

  function getPossibleAnswers() {
    $possibles = array();
    $answer = $this->answer;
    $stem = $this->stem;
    $q = "select pal from all_words3 where stem=$stem and pal like '$answer' or (pos like 'var.' and eng like '$answer')";
    $r = query_or_die($q);
    while($row = $r->fetch_array(MYSQLI_NUM)) { 
      $possibles[] = $row[0];
    }
    return $possibles;
  }

  function explanation() {
    $wid = $this->wid;
    $aid = $this->aid;
    $q = "select id,stem from all_words3 where id=$aid or id=$wid";
    $entries = get_words($q,False);
    $use_tooltip = False;
    if ($use_tooltip) {
      echo "
      <a href='#' class='tooltip'>
      Show More Explanation
      <span>
        <img class='callout' src='/images/callout.gif' />
      ";
    }
    print_words($entries,$this->answer,True);
    if ($use_tooltip) {
      echo "
      </span>
      </a>
      ";
    }
  }

  function get_example() {
    $pos = $this->pos;
    $apos = $this->apos;
    $q = "select a.pal as one, b.pal as two from all_words3 a,all_words3 b "
       . " where a.pos like '$pos' and a.id=b.stem and b.pos like '$apos' and a.vulgar=0 and b.vulgar=0 order by rand() limit 1";
    $r = query_or_die($q);
    $row = $r->fetch_assoc(); 
    extract($row);
    $example = "<div class='tab'><ul><li>For example, <i>$two</i> is the $apos form of the $pos word <i>$one</i>.</ul></div>\n";
    return $example;
  }

  function toHtml() {
    $pal = $this->pal;
    $pos = $this->pos;
    $eng = $this->eng;
    $apos = $this->apos;
    $html = "The Palauan word <b>$pal</b> is a <i>$pos</i> meaning <b>$eng</b>.<br>";
    $html .= "Please type the <i>$apos</i> form of this word";
    $example = $this->get_example();
    inputq($this->myid,$html,$example);
  }
}

function pos_check_answer($question,$answer) {
  $possibles = $question->getPossibleAnswers();
  $correct = implode(' or ',$possibles);
  $question->setAnswer($correct);
  $score = get_score($possibles,$answer);
  return score_to_result($score);
}

function pos_make_question($quiz) {
  #Debug( "Time to make a question." );
  $field = "b.id";
  $qfilter = $quiz->get_filter($field);
  $q = "select a.pal as pal,a.id as aid,a.pos as pos,a.eng as eng,b.pal as answer,b.pos as apos,b.id as wid,b.stem as stem from all_words3 a,all_words3 b "
     . "where a.id=a.stem and b.id!=a.id and b.stem=a.id and b.pos not in ('expression', 'var.', 'cont.', 'interj.', 'expr.') "
     . "and a.pal not like b.pal and a.vulgar=0 and b.vulgar=0 and a.pos not like b.pos ";
  $q .= " and $qfilter order by rand() limit 1";
  $r = query_or_die($q);
  $quest = new PQuestion($r,$quiz);
  $quest->toHtml();
  $_SESSION['question'] = $quest;
}

function pos_extra_answer($question) {
  $question->explanation();
}

$config = array();
$config['title'] = "Palau Language Parts of Speech Quiz"; 
$config['make_q'] = "pos_make_question"; # function pointer
$config['check_q'] = "pos_check_answer"; # function pointer
$config['explanation'] = "pos_extra_answer"; # function pointer
$config['type'] = "Parts of Speech";

make_quiz($config);
?>

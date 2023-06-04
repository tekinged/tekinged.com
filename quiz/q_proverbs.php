<?php 
require_once './quiz2.php';
session_start();
include '../functions.php'; 

$GLOBALS['DEBUG'] = false;

Class AQuestion {
  var $sentence;
  var $answers;
  var $correct;
  var $correctpal;
  var $explanation;
  var $english;
  var $myid;

  function AQuestion($rows,$quiz) {
    $this->answers = array();
    while($row = $rows->fetch_assoc()) { 
      extract($row);
      # the first one that comes in is the correct one
      # but then shuffle them so it's not always the first one
      Debug( "$english - $palauan $id" );
      $this->answers[] = array($explanation,$id);
      $this->question = $explanation; 
      $this->english = $english;
      $this->explanation = $explanation; 
      $this->correct = $id;
      $this->correctpal = $palauan;
      $quiz->add_stem($id);
    }
    $this->myid = $quiz->asked() + 1;
    shuffle($this->answers);
  }

  function getMessage() {
    return 'The literal definition of <br><i>' . $this->correctpal . '</i><br> is <br>' . 
          '<ul><li>' . $this->english . '</ul>' . 'The figurative explanation is <br>' .
          '<ul><li>' . $this->question . '</ul>'; 
  }

  function getQuestion() {
    return $this->question;
  }

  function getCorrect() {
    return $this->correct;
  }

  function toHtml() {
    $button = audio_button("proverb",$this->correct); 
    $core = "<table><tr><td><span>Please choose the corrective figurative meaning for this proverb: $button:</span></td></tr></table>";
    q2html($this->myid,$core,$this->answers);
  }
}

function audio_make_question($quiz) {
  #Debug( "Time to make a question." );
  $qfilter = $quiz->get_filter();
  #$filter = "!isnull(pos) && length(eng)>0 && pos not like 'var.' && pos not like 'abbr.' && pos not like 'mod.' && length(pos)>1 && $qfilter";
  #$filter = "id in (select externalid from upload_audio where uploaded=1 and verified=1) && $qfilter";
  # removed verified requirement
  $filter = "id in (select externalid from upload_audio where uploaded=1 && externaltable like 'proverbs') && $qfilter";

  #Debug( $qfilter . "<br>\n" );

  $table = 'proverbs';

  # get five audio examples 
  $q_q="select english,palauan,id,explanation from $table where $filter order by rand() limit 5;";
  $r = query_or_die($q_q);
  $quest = new AQuestion($r,$quiz);
  $quest->toHtml();
  $_SESSION['question'] = $quest;
}

$config = array();
$config['title'] = "Palau Language Proverbs Quiz"; 
$config['make_q'] = "audio_make_question"; # function pointer
$config['type'] = "Proverbs";

make_quiz($config);
?>

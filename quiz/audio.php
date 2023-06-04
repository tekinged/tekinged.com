<?php 
require_once './quiz2.php';
session_start();
include '../functions.php'; 

$GLOBALS['DEBUG'] = false;

Class AQuestion {

  public function __construct($rows,$quiz) {
    $this->answers = array();
    while($row = $rows->fetch_assoc()) { 
      extract($row);
      # the first one that comes in is the correct one
      # but then shuffle them so it's not always the first one
      Debug( "$english - $palauan - $id" );
      $this->answers[] = array($english,$id);
      $this->question = $english; 
      $this->correct = $id;
      $this->correctpal = $palauan;
      $quiz->add_stem($id);
    }
    $this->myid = $quiz->asked() + 1;
    shuffle($this->answers);
  }

  function getMessage() {
    return 'The translation for <br><i>' . $this->correctpal . '</i><br> is <br>' . $this->question; 
  }

  function getQuestion() {
    return $this->question;
  }

  function getCorrect() {
    return $this->correct;
  }

  function toHtml() {
    $button = audio_button("example",$this->correct); 
    $core = "<table><tr><td><span>Please translate this sentence $button:</span></td></tr></table>";
    q2html($this->myid,$core,$this->answers);
  }
}

function audio_make_question($quiz) {
  #Debug( "Time to make a question." );
  $qfilter = $quiz->get_filter();
  #$filter = "!isnull(pos) && length(eng)>0 && pos not like 'var.' && pos not like 'abbr.' && pos not like 'mod.' && length(pos)>1 && $qfilter";
  #$filter = "id in (select externalid from upload_audio where uploaded=1 and verified=1) && $qfilter";
  # removed verified requirement
  $filter = "id in (select externalid from upload_audio where uploaded=1 && externaltable like 'examples') && $qfilter";

  #Debug( $qfilter . "<br>\n" );

  $table = 'examples';

  # get five audio examples 
  $q_q="select english,palauan,id from $table where $filter order by rand() limit 5;";
  $r = query_or_die($q_q);
  $quest = new AQuestion($r,$quiz);
  $quest->toHtml();
  $_SESSION['question'] = $quest;
}

$config = array();
$config['title'] = "Palau Language Audio Quiz"; 
$config['make_q'] = "audio_make_question"; # function pointer
$config['type'] = "Audio";

make_quiz($config);
?>

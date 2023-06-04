<?php 
require_once './quiz2.php';
session_start();
include '../functions.php'; 


$GLOBALS['DEBUG'] = false;

Class PQuestion {
  var $word;
  var $answers;
  var $correct;
  var $myid;

  function PQuestion($rows,$quiz) {
    $this->answers = array();
    while($row = $rows->fetch_array(MYSQLI_NUM)) { 
      # the first one that comes in is the correct one
      # but then shuffle them so it's not always the first one
      Debug( "Using picture word $row[0] $row[1]" );
      $this->answers[] = array($row[0],$row[1]);
      $this->question = $row[0];
      $this->correct = $row[1];
      $quiz->add_stem($row[1]);
    }
    $this->myid = $quiz->asked() + 1;
    shuffle($this->answers);
  }

  function getMessage() {
    return "That picture was a " . $this->question . ".";
  }

  function getQuestion() {
    return $this->question;
  }

  function getCorrect() {
    return $this->correct;
  }

  function toHtml() {
    $image = pics_dir() . "/" . $this->correct . ".jpg";
    $core = "<img width=30% src=$image> is a picture of which of the following:";
    q2html($this->myid,$core,$this->answers);
  }
}

function pic_make_question($quiz) {
  #Debug( "Time to make a question." );
  $qfilter = $quiz->get_filter();
  #$filter = "!isnull(pos) && length(eng)>0 && pos not like 'var.' && pos not like 'abbr.' && pos not like 'mod.' && length(pos)>1 && $qfilter";
  #$filter = "!isnull(tags) && $qfilter && id in (select allwid from pictures where (uploaded=1 || uploaded=2) && (verified=1 || verified=2))"; 
  # removed verified requirement
  $filter = "!isnull(tags) && $qfilter && id in (select allwid from pictures where (uploaded=1 || uploaded=2) )"; 
  #$filter = "id in (select allwid from pictures where uploaded=1) && !isnull(tags) && $qfilter";

  #Debug( $qfilter . "<br>\n" );

  $table = 'qz_pics';

  # first get the tag to use
  # get a count of all possible pos's, then randomly select from them weighted by how many they are
  $all_tags = array();
  $tag_q="select tags,count(*) as c from $table group by tags having c>=5";
  Debug($tag_q);
  $r = query_or_die($tag_q);
  $total=0;
  while($row = $r->fetch_array(MYSQLI_NUM)) { 
    Debug("Adding possible tag $row[0] [$row[1]]");
    $all_tags[$row[0]] = $row[1] + $total;
    $total+=$row[1];
  }
  $rand=rand(0,$total);
  $tag = 'fish'; # set a default pos in case the below loop (inexplicable doesn't work. 
  foreach($all_tags as $t => $c) {
    Debug ("Compare $rand to $c");
    if ($rand <= $c) { 
      $tag = $t;
      break;
    }
  }
  Debug( "Will make a question using tag = $tag<br>\n" );

  # then get the five words
  $q_q="select pal,id from $table where tags like '$tag' and $filter order by rand() limit 5;";
  Debug( $q_q );
  $r = query_or_die($q_q);
  $quest = new PQuestion($r,$quiz);
  $quest->toHtml();
  $_SESSION['question'] = $quest;
}

$config = array();
$config['title'] = "Palau Language Picture Quiz"; 
$config['make_q'] = "pic_make_question"; # function pointer
$config['type'] = "Pictures";

make_quiz($config);
?>

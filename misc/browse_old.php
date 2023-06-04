<?php 
include '../functions.php'; 

function startPage() {
  $title="Browse All Words";
  html_top($title,True);
  belau_header($title);
  start_content_container();
}

function endPage() {
  echo "<br><p>All words also available as a <a href=/misc/all_words.php>single page tabular view</a></p>";
  end_content();
  belau_footer("browse.html",": " . $_POST['range']); 
  end_body_html();
}

function makeSelector($current=NULL) {
  # start a selection pull-down
  $selector = "  
        <div id='below'> <!-- table of contents -->  
           <form method='post'>
            <h2><span itemprop='articleBody'>Browse words</span>: <select onchange='this.form.submit()' name='range' /></h2>
      ";
  $max_grp_sz = 200;
  $groups = 0;
  $begin = microtime(true);

  foreach (range('a','z') as $letter) {
    if (ord($letter) > ord('e') and ord($letter < 'i')) {
      continue; # skip f,g,h because they're grouped with E
    }
    if ($letter == 'e') {
      $next = 'i';
    } else {
      $next = chr(ord($letter) + 1);
    }
    $where = "where pal >= '$letter' and pal < '$next'";
    $entries = get_count('all_words3', $where); 
    #print "There are $entries words in letter $letter<br>\n";
    if ($entries > 0) {
      $offset = 0;
      while ($offset < $entries - 1) {
        $groups++;
        $start = get_nth($letter,$offset);
        $offset = min($offset + $max_grp_sz-1,$entries-1);
        $last = get_nth($letter,$offset);
        $offset += 1;
        $value = "$start:$last";
        $selected = ($current != NULL && $current == $value) ? "selected" : "";
        $selector .= "<option value='$value' $selected>$start - $last</option>\n";
        #print "$start - $last<br>\n";
      }
    }
  }
  $selector .= "</select>
          </form>
        </div> <!-- table of contents -->
    ";
  $elapsed = microtime(true) - $begin;
  mydebug( "$groups total groups. Took $elapsed secs.<br>\n" );
  return $selector;
}

function get_nth($start,$n) {
  $q = "select pal from all_words3 where pal >= '$start' order by pal limit 1 offset $n";
  $r = query_or_die($q);
  return mysql_result($r,0,0);
}

function mydebug($msg) {
  #echo $msg;
}

function showWords($start,$end) {
  $query = "select id,stem from all_words3 where pal >= '$start' and pal <='$end'";
  $begin = microtime(true);
  $entries = get_words($query,False);
  $collect = microtime(true) - $begin;
  $count = count($entries);
  $begin = microtime(true);
  print_words($entries);
  $elapsed = microtime(true) - $begin;
  mydebug( "$count words took $collect secs to collect; $elapsed secs to print.<br>\n" );
}

startPage();
start_content();
db_connect();
$selector = makeSelector($_POST['range']);
print $selector;
$begin = microtime(true);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  list($start,$end) = explode(":",$_POST['range']);
  showWords($start,$end);
} else {
  showWords('a','b');
}
print $selector;
$elapsed = microtime(true) - $begin;
mydebug( "Took $elapsed secs.<br>\n" );
endPage();

<?php 

include 'dekaingeseu.php';

html_top($title);
belau_header($title);
main();

function request_name() {
    echo "<p>Thanks for your interest in helping to create online Palauan language resources! 
          <p>The current task is to help verify the translation of the $book novel. 
          ";
    echo "<form method='post'>\n";
    add_input("Enter your name so your contribution will be counted: ", 'name',False);
    echo "</form>\n";
}

function add_contribution($who) {
    $u = "INSERT INTO dekaingeseu (name,count) VALUES ('" . $who . "',1) ON DUPLICATE KEY UPDATE count = count + 1;";
    mysql_query($u) or die(mysql_error());
    echo "Updated with $u";
}

function record_work() {
  $_SESSION['completed'] = $_SESSION['completed'] + 1;
  $completed = $_SESSION['completed'];
  $name = $_SESSION['name'];

  if ($completed > 1) {
    $meruul = 'remuul';
  } else {
    $meruul = 'rullii';
  }
  
  print "<h3>Ke kmal mesaul $name! Ke mla $meruul a $completed el ureor.  Ka mluut el mong!</h3><br>";
  $text = $_POST['verified'];
  $row = $_POST['row'];

  $text = utf8_encode($text); # fix any encoding
  $q = "submitted=now(),verified='$text'";
  update_table($q,$row);

  # update the contributions table
  add_contribution($_SESSION['name']); 
}

function add_instructions() {
  global $TABLE;
  print "<b>Instructions:</b>
        <p>Do your best to verify the translation of the English paragraph from the novel into Palauan.  
        <ul>
        <li>For names of places and people, don't translate and just copy them as they are.
        <li>If it is just a single number, please write the Palauan translation for that number.  This is the Chapter marker.
        <li>Try to use correct spelling and grammar but don't worry about mistakes.  Anything is better than nothing and we can fix later.
        <li>Read this <a href=http://blog.liberwriter.com/2014/03/01/translating-your-book-is-it-worth-it/ target='_blank'>blog post</a> for general book translation suggestions.
        </ul>
        <p>Check progress and the complete novel <a href=/books/translation.php?book=$TABLE target='_blank'>here</a>.
        ";
}

function make_work() {
  if (isset($_POST['verified'])) {
    record_work();
  } else {
    $name = $_SESSION['name'];
    print "<h3>Thanks $name!</h3><br>";
  }

  if (tasks_remaining() > 0) {
    add_instructions();
  }
  next_task();
  add_contributions();
  add_progress();
}

function add_progress() {
  $total = my_get_count("where !isnull(palauan) and isnull(verified)");
  $done = my_get_count("where !isnull(verified)");
  $perc = sprintf("%.2f", 100*($done/$total));
  print "PROGRESS: $perc percent done ($done completed paragraphs out of $total).\n";
}

function update_table($update,$col,$w=NULL) {
  global $TABLE;
  $q = "update $TABLE set $update";
  if ($w == NULL) {
    $q .= " where id='$col'";
  } else {
    $q .= " $w";
  }
  Debug("Updating with $q");
  mysql_query($q) or die(mysql_error());
  $affected = mysql_affected_rows();
  Debug("Affected rows: $affected");

  #print "<h3>MIKO: Updating with $q</h3><br>\n";
  #print "<h3>MIKO: $affected rows updated.</h3><br>\n";
  #print "<h3>MIKO" . mysql_errno($link) . ": " . mysql_error($link). "\n";
}

function assign_task($name,$col) {
  $q = "assignee='$name', assigned=now()";
  update_table($q,$col);
}

function tasks_remaining() {
  global $TABLE;
  $q = "select id,english from $TABLE where isnull(palauan) ORDER BY id limit 1";
  list($res,$num_rows) = check_table($q);
  return $num_rows;
}

function next_task() {
  global $TABLE;
  $q = "select id,english,palauan from $TABLE where isnull(verified) ORDER BY id limit 1";
  list($res,$num_rows) = check_table($q);
  if ($num_rows <= 0) {
    print "No more work to be done.  Thanks for helping!<br>\n";
    return;
  }
  $row = mysql_fetch_assoc($res);
  $id = $row['id'];
  $pal = $row['palauan'];

  # get the text and clean up some simple stuff
  $text = str_replace("'","",$row['english']);
  $text = str_replace("|","l",$text);
  $text = str_replace("é","e",$text);
  $text = str_replace("”",'"',$text);

  #assign_task($_SESSION['name'],$id);

  print "<form method='post'>";
  print "<table><tr>
          <td align='top'>Please edit the translation of this text: <br>$text</td>
          <td align='top'><textarea class='kerresel' rows='5' cols='50' name='verified' >$pal</textarea></td>
          </tr></table><br>\n";
  print "<button style='background-color:red' name='row' type='submit' value='$id'>Submit After Translating</button>\n";
  print "</form>";
}

function unassign_old() {
  # if anyone accepted work but didn't complete it in 60 minutes, then return it to the queue

  $u = "assigned=NULL";
  $w = "where !isnull(assigned) and isnull(submitted) and unix_timestamp(now()) - unix_timestamp(assigned) > 3600";
  update_table($u,NULL,$w);
}

function my_get_contributions() {
  $contributions = array();
  global $TABLE;
  $q = "select assignee,count(*) as c from $TABLE where !isnull(submitted) group by assignee order by c;";
  $r = query_or_die($q);
  while($row = mysql_fetch_assoc($r)) {
    $who = $row['assignee'];
    $count = $row['c'];
    $contributions[$who] = $count;
  }
  arsort($contributions);
  return $contributions;
}

function my_get_count($w=NULL) {
  global $TABLE;
  $q = "select count(*) as c from $TABLE $w";
  $r = query_or_die($q);
  $row = mysql_fetch_assoc($r);
  return $row['c'];
}

function add_contributions() {
  $contributors = my_get_contributions();
  $contributions = array();
  foreach ( $contributors as $who => $count ) {
    $contributions[] = "$who: $count";
  }
  $txt = join("; ",$contributions);
  print "<br>Contributions so far: $txt.<br>\n";
}

function my_print_help_aside() {
  $contributors = my_get_contributions();
  
  echo "<div id='aside'>\n";
  echo "<div class='tab'>\n";
  echo "Top Contributors:\n"; 
  echo "<ol type='1'>";
  foreach ( $contributors as $who => $count ) {
    print "<li>$who : $count\n";
  }
  echo "</ol>";
  add_progress();
  echo "</div><!-- tab -->\n";
  echo "</div><!-- aside -->\n";
}

function main() {
  start_content_container();

  # has name been entered
  if (isset($_POST['name'])) {
    $name = $_POST['name'];
    $_SESSION['name'] = $name;
    $_SESSION['completed'] = 0;
  }
  
  if ($_SESSION['name'] == Null) {
    # need to do aside before start_content
    my_print_help_aside();
  }

  start_content();
  unassign_old();


  if ($_SESSION['name'] == Null) {
    request_name();
  } else {
    make_work();
  }
  end_content();

  $name = $_SESSION['name'];
  $comp = $_SESSION['completed'];
  belau_footer(curPageURL(),": $name: $comp");
  end_body_html();
}

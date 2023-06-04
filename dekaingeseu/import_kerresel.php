<?php 
session_start();
include '../functions.php'; 
$title="Dekaingeseu";
html_top($title);
belau_header($title);

$GLOBALS['DEBUG'] = false;
$TABLE = "kerresel_columns";

function request_name() {
    echo "<p>Thanks for your interest in helping to create an online Palauan dictionary.  On this page, we will occasionally post requests for help.  If you don't feel comfortable with 
         the current request, please check back in later to see if there is something else.  Or email us at info@tekinged.com to ask how you can contribute!
          <p>The current task is to help digitize the <a href='/books/kerresel.php'>Kerresel A Klechibelau dictionary</a>.  So far, we've managed to scan the book, turn it into 
          digital images, and automatically extract most of the text.  However, the automatic extraction process is not perfect and we are asking for volunteers to check the 
          extracted text, edit any errors, and then submit the correct text to us.  Once this is done, we'll be able to add all of these beautiful words to our database so they
          will be available for future word searches on our main search page.
          <p>Here is more <a href='/books/kerresel/instructions.png' target='_blank'>detailed instructions and an example</a>.
          Or just go ahead and get started!  Hopefully, the work is mostly self-explanatory.  Email us, or comment below, if you
          have any questions.
          ";
    echo "<form method='post'>\n";
    add_input("Enter your name so your contribution will be counted: ", 'name',False);
    echo "</form>\n";
}

function record_work() {
  $_SESSION['completed'] = $_SESSION['completed'] + 1;
  $completed = $_SESSION['completed'];
  $name = $_SESSION['name'];
  
  print "<h3>Ke kmal mesaul $name! Ke mla rullii a $completed el ureor.  Kem luut el mong!</h3><br>";
  $column = $_POST['column'];
  $text = $_POST['newtext'];

  $text = utf8_encode($text); # fix any encoding
  $q = "submitted=now(),ctext_copy='$text'";
  update_table($q,$column);
}

function add_instructions() {
  print "<b>Instructions:</b>
        <ul><li>Using the image on the left, edit the text in the box on the right to match.  
        <ul>
        <li>Read each word in the image, then read it in the text, and correct any mistakes.
        <li>For some images, there may not be any mistakes.  Some might have several.
        <li>The pronunciation text in parentheses should already be removed.  If it isn't, then please remove it.
        <li>If there are multiple entries for the same word, the image will show numbers like (1) and (2) for those words.
        <ul><li>We do not want these numbers in the edited text.  They should already be removed.  If not, please remove.</ul>
        </ul>
        <li>Please fix any errors and remove any weird extra stuff at the top or the bottom.  
        <ul>
        <li>If there is a guide word at the top, please delete it.  We don't need it.  Same with page number at the bottom.
        <li>Weird punctuation marks definitely must be removed. Otherwise, your work may be lost.
        </ul>
        <li>When you are done checking the image and fixing any mistakes, click the red Submit button at the bottom.
        <ul>
        <li>Click <a href='/books/kerresel/instructions.png' target='_blank'>here</a> for more detailed instructions and an example.
        <li>If the image is not clear or there are letters cut off, click on the image to open a larger view of it.
        <li>Thanks!
        </ul>
        </ul>\n";
}

function make_work() {
  if (isset($_POST['column'])) {
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
  $total = get_count();
  $done = get_count("where !isnull(submitted)");
  $perc = sprintf("%.2f", 100*($done/$total));
  print "PROGRESS: $perc percent done ($done completed columns out of $total).\n";
}

function update_table($update,$col,$w=NULL) {
  global $TABLE;
  $q = "update $TABLE set $update";
  if ($w == NULL) {
    $q .= " where kcol='$col'";
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
  $q = "select kcol,ctext_copy as ctext from $TABLE where isnull(assigned) ORDER BY rand() limit 1";
  list($res,$num_rows) = check_table($q);
  return $num_rows;
}

function next_task() {
  global $TABLE;
  $q = "select kcol,ctext_copy as ctext from $TABLE where isnull(assigned) ORDER BY rand() limit 1";
  list($res,$num_rows) = check_table($q);
  if ($num_rows <= 0) {
    print "No more work to be done.  Thanks for helping!<br>\n";
    return;
  }
  $row = mysql_fetch_assoc($res);

  # get the text and clean up some simple stuff
  $text = str_replace("'","",$row['ctext']);
  $text = str_replace("|","l",$text);
  $text = str_replace("é","e",$text);
  $text = str_replace("”",'"',$text);

  # get the column and turn it into a URL for the individual column and the whole page
  $col  = $row['kcol'];
  $image = "/books/kerresel/images/columns/" . str_replace(".txt","",$col);
  $page  = str_replace("columns/","",$image);
  $page  = str_replace("_right","",$page);
  $page  = str_replace("_left","",$page);

  assign_task($_SESSION['name'],$col);

  print "<form method='post'>";
  print "<table><tr>
          <td align='top'><a href=$page target='_blank'><img src=$image width=400/></a></td>
          <td align='top'><textarea class='kerresel' rows='55' cols='50' name='newtext'>$text</textarea></td>
          </tr></table><br>\n";
  print "<button style='background-color:red' name='column' type='submit' value='$col'>Submit After Editing</button>\n";
  print "</form>";
}

function unassign_old() {
  # if anyone accepted work but didn't complete it in 60 minutes, then return it to the queue

  $u = "assigned=NULL";
  $w = "where !isnull(assigned) and isnull(submitted) and unix_timestamp(now()) - unix_timestamp(assigned) > 3600";
  update_table($u,NULL,$w);
}

function get_contributions() {
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

function get_count($w=NULL) {
  global $TABLE;
  $q = "select count(*) as c from $TABLE $w";
  $r = query_or_die($q);
  $row = mysql_fetch_assoc($r);
  return $row['c'];
}

function add_contributions() {
  $contributors = get_contributions();
  $contributions = array();
  foreach ( $contributors as $who => $count ) {
    $contributions[] = "$who: $count";
  }
  $txt = join("; ",$contributions);
  print "<br>Contributions so far: $txt.<br>\n";
}

function print_help_aside() {
  $contributors = get_contributions();
  
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
    print_help_aside();
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
  belau_footer("/dekaingeseu.php",": $name: $comp");
  end_body_html();
}

main();

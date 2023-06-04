<?php 
session_start();
include '../functions.php'; 
include 'dekaingeseu.php';

$GLOBALS['DEBUG'] = false;

function add_instructions() {
  print "<p>George Keate's account of his 1783 shipwreck on Ulong.  Translated into Palauan by Daniel Ngirairikl.";
  print "<p><ul><li>Copy the page on the left into the textbox on the right.
                <li>Don't add newlines within paragraphs.  Just wrap the text.
                <li>Do add newlines between paragraphs.  Add as many blank lines between paragraphs as you want to make it easy for you to copy.
                <li>Don't copy the page numbers.
                <li>If there are hypens because a word was split, remove the hypen and just type the word together.
                <li>If there are obvious mistakes, please fix them.
                <li>If the image is hard to read, try to use the Zoom feature in your browser.
                <li>Read the story and check progress so far <a href=/books/translation.php?book=translate_keate>here</a>.
         </ul>";
}

function copy_keate_record_task($result) {
  $text = $_POST['newtext'];
  $text = mysql_real_escape_string($text);
  $who = $_SESSION['worker']; 
  $update = "pal='$text'";
  Debug("Finished $result: $text!");
  return $update;
}

function copy_keate_make_task($db_row) {
  add_instructions();
  $page = $db_row['page'];
  $id   = $db_row['id'];
  #print "Found task: $col<br>\n";

  $where = "page like '$page'";
  $q = "select id,pal from dekaingeseu_keate where $where";
  list($res,$num_rows) = check_table($q);
  if ($num_rows <= 0) {
    return Null;
  }
  $values = array();
  $row = mysql_fetch_assoc($res);
  $values['id'] = $row['id']; 

  # get the text and clean up some simple stuff
  $text = str_replace("'","",$row['pal']);
  $text = str_replace("|","l",$text);
  $text = str_replace("é","e",$text);
  $text = str_replace("”",'"',$text);

  # get the page and turn it into a URL for the individual column and the whole page
  $img_path = "/books/keate.images/page-"; 
  $image = sprintf("%s%03d.png", $img_path, $page);

  $page = $image;

  $style = "style='background-color:red'"; 
  $submit = submitButtons("Submit After Copying",$col,$style); 
  print "<form method='post'>
            <input type='hidden' name='taskid' value='$id'>
           <table><tr>
            <td align='top'><a href=$page target='_blank'><img src=$image width=600/></a></td>
            <td align='top'><textarea class='kerresel' rows='30' cols='50' name='newtext'>$text</textarea></td>
            </tr></table><br>
          $submit
        </form>
        ";
  return $values;
}


$title = "Help digitize CHARLOTTE EL BUBUU";
$config = array();
$config['intro'] = "Help digitize CHARLOTTE EL BUBUU.";
$where = "(isnull(assigned) or length(assignee)<1)";
$config['q_find'] = "select id,page from dekaingeseu_keate where $where";
$config['order'] = 'id';
$config['get_count'] = "where $where";
$config['table'] = 'dekaingeseu_keate';
$config['make_task'] = "copy_keate_make_task"; # function pointer
$config['record_task'] = "copy_keate_record_task"; # function pointer
$config['timeout'] = 7200; # give people 2 hours 7200 
$task = new Dekaingeseu($title,$config);

?>

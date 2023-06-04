<?php 
session_start();
include '../functions.php'; 
include 'dekaingeseu.php';

$GLOBALS['DEBUG'] = false;

function add_instructions() {
  print "<p><ul><li>Copy the column on the left into the textbox on the right.  Ignore guide words.  That's it!  Thanks!
                <li>Please put an extra empty line between each word entry.
                <li>Actually, this dekaingeseu page isn't quite ready.  Contribute if you want but no guarantees that we'll be able to actually use your work...
         </ul>";
}

function copy_jos75_record_task($result) {
  $text = $_POST['newtext'];
  $update = "ctext='$text'";
  Debug("Finished $result: $text!");
  return $update;
}

function copy_jos75_make_task($db_row) {
  add_instructions();
  $col = $db_row['Col'];
  #print "Found task: $col<br>\n";

  $where = "Col like '$col'";
  $q = "select ctext,id from dict_columns where $where";
  list($res,$num_rows) = check_table($q);
  if ($num_rows <= 0) {
    return Null;
  }
  $values = array();
  $row = mysql_fetch_assoc($res);
  $values['id'] = $row['id']; 

  # get the text and clean up some simple stuff
  $text = str_replace("'","",$row['ctext']);
  $text = str_replace("|","l",$text);
  $text = str_replace("é","e",$text);
  $text = str_replace("”",'"',$text);

  # get the column and turn it into a URL for the individual column and the whole page
  $image = "/books//90josephs/columns/$col"; 
  $page = $image;

  $style = "style='background-color:red'"; 
  $submit = submitButtons("Submit After Editing",$col,$style); 
  print "<form method='post'>
           <table><tr>
            <td align='top'><a href=$page target='_blank'><img src=$image width=400/></a></td>
            <td align='top'><textarea class='kerresel' rows='55' cols='50' name='newtext'>$text</textarea></td>
            </tr></table><br>
          $submit
        </form>
        ";
  return $values;
}


$title = "Copying Josephs Dictionary Columns";
$config = array();
$config['intro'] = "Help copy text off images of Josephs dictionary columns.";
$where = "isnull(imported) and isnull(ctext)";
$config['q_find'] = "select Col from dict_columns where $where";
$config['get_count'] = "where $where";
$config['table'] = 'dict_columns';
$config['make_task'] = "copy_jos75_make_task"; # function pointer
$config['record_task'] = "copy_jos75_record_task"; # function pointer
$config['timeout'] = 7200; # give people 2 hours 7200 
$task = new Dekaingeseu($title,$config);

?>

<?php session_start();
include('functions.php');
$title="Word Groups";
html_top($title,true);
belau_header($title);

$GLOBALS['DEBUG'] = false;

function add_thumb($row) {
  if (is_numeric($row[0]) ) {
    $pdir = $_SERVER['DOCUMENT_ROOT'] . pics_dir();
    $id = $row[0]; 
    $pic = $pdir . '/' . $id . '.jpg';
    if (file_exists($pic)) {
      $url = pics_dir() . '/' . $id . ".jpg";
      Debug( "Using url $url" );
      return make_thumbnail($url);
    } else {
      return ""; 
    }
  } else {
    return $row[0];
  }
}


function post_results($target) {
    echo "<div id='content' min-height=200>\n";
    $lookup = $GLOBALS['interesting'][$target][0];
    if (!$lookup) {
      die("WTF: No lookup");
    }
    $label = $GLOBALS['interesting'][$target][1];
    echo "<h2>Word List: $label</h2><br>\n";
    $method = $GLOBALS['interesting'][$target][2];
    Debug("Using $lookup : $method");

    if ($method=='words') {
      $query = "select id,stem,pos,pal,eng,pdef from all_words3 where $lookup;";
      $begin = microtime(true);
      $entries = find_entries($query,False,$target);
      $elapsed = microtime(true) - $begin;
      Debug("Took $elapsed");
      print_words($entries);
    } else {
      $custom = $GLOBALS['interesting'][$target][3] ?? '';
      if ($custom) {
        Debug("Using $custom");
        $query = $custom;
      } else {
        $query = "select a.id as Pic, a.pal as Palauan,a.pos as Type,a.eng as English,a.pdef as Omesodel,b.pal as 'Root Word' from all_words3 a,all_words3 b where $lookup and a.stem=b.id order by a.pal"; 
      }
      Debug($query);
      print_table($query,true,false,true,"add_thumb");
    }
    $extra = $_POST['lookup'] ?? '';
}

function add_form($interesting,$choosen) {
    echo "  
            <div id='below'> <!-- interesting selection -->  
               <form method='post'>
                <h2><span itemprop='articleBody'>Show all</span>: <select name='lookup' /></h2>
          ";
    foreach ($interesting as $i => $a) {
      $dmsg .= "$i =?= $choosen\n";
      $selected = ($choosen != NULL && $choosen == $i) ? "selected" : "";
      echo "<option $selected value='$i'>$a[1]</option>";
    }
    echo "
                </select>
                <input type='submit' value='Show all'/>
              </form>
            </div> <!-- interesting selection -->
        ";
    #echo "$dmsg";
}

# did the user do a POST?
$extra = '';
echo "<div id='content-container'>\n";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  post_results($_POST['lookup']);
} else if (isset($_GET['lookup'])) {
  post_results($_GET['lookup']);
} else {
    echo "<div id='aside'>\n";
    echo "&nbsp;\n";
    echo "<meta itemprop='image' content='http://tekinged.com/images/palau-flag.png'>\n";
    echo "</div>\n";
    echo "<div id='content' min-height=200>\n";
    $lookup = NULL;
} 
add_form($GLOBALS['interesting'],$extra); 
echo "</div></span><!-- content -->\n";
echo "</div><!-- content-container -->\n";
echo "<br>\n";
$lookup = $_POST['lookup'] ?? '';
belau_footer("words_" . $lookup,": " . $extra);

?>
</body></html>

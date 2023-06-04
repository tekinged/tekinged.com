<?php 
include '../functions.php'; 

$title = "Ingsel a Demul";
$table = "translate_ingsel";
$q = "select id,english as e, palauan as p, NULL as i from $table order by id";
$color = "red";

$current_image = 1; # some books might have images
$image_base = NULL;

#$_GET['book'] = "translate_charlotte"; # cheat for a second to download into MS Word
$_GET['book'] = "translate_rubak"; # cheat for a second to download into MS Word
if (isset ($_GET['book'])) {
  $book = $_GET['book'];
  if ($book == "translate_rubak") {
    $title = "Chimol Rubak ma Daob";
    $table = "translate_rubak";
    $q = "select id,english as e, palauan as p, NULL as i from $table order by id";
  } else if ($book == "translate_charlotte") {
    $title = "Charlotte el Bubuu";
    $table = "dekaingeseu_charlotte";
    $q = "select id,NULL as e, pal as p,image as i from $table order by id";
    $image_base = "/books/charlotte.images/pictures/image";
    $color = "black";
  }
}

function get_translation($table,$q) {
  global $current_image;
  global $image_base;
  Debug("Using query $q<br>\n");
  $r = query_or_die($q);
  while($row = mysql_fetch_assoc($r)) {
    extract($row);
    $translation .= "<p>$e\n";
    if ($p != NULL and strlen($p)>0) {
      $p = preg_replace("/(\r?\n){1,}/", "<br><br>\n", $p);
      $translation .= "<div class='tab'><font color=$color><p>$p</font></div>\n";
    } else {
      if ($p == NULL) {
        $translation .= "<div class='tab'><font color=red><p>Page $id is either blank or not yet done.</font></div>\n";
      }
    }
    if ($i != NULL) {
      $image = sprintf("%s-%02d.png", $image_base, $current_image);
      $current_image = $current_image+1;
      $translation .= "<img src=$image>\n";
    }
  }
  return $translation;
}

$config = array();
$config['title'] = "Translation of $title";
start_simple_page($config);
echo "<h3>" . strtoupper($title) . "</h3>";
$translation = get_translation($table,$q);
echo $translation;
end_simple_page(": $title");
?>

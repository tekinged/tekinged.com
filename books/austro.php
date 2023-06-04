<?php 
session_start();
include '../functions.php'; 

function make_link($url,$label) {
  $link = NULL;
  if ($url != NULL) {
    $link = "<a href=$url>$label</a>";
  }
  return $link;
}

function make_row($fields) {
  $row = "<tr>";
  foreach($fields as $field) {
    $row .= "<td>$field</td>";
  }
  $row .= "</tr>\n";
  return $row;
}

function sort_link($field,$label) {
  $cur = strtok($_SERVER["REQUEST_URI"],'?');
  return "<a href=$cur?sort=$field>$label</a>";
}

$config = array();
$config['title'] = "Austronesian Language Books, Articles, and Websites";
start_simple_page($config);

echo "<h2>" . $config['title'] . "</h2>";

echo "<p>tekinged.com is very happy to provide the following Austronesia language materials for Austronesian languages other than Palauan.  Palauan language materials can be found
      <a href=/books/books.php?filter=-->here</a>.
      ";

echo "<p>Additionally, we maintain a page on which researchers can simultaneously search all of our dictionaries (including Palauan):<br>
     <a href=/books/research.php>Multi-Austronesian Search</a>.
     ";

echo "<p>Due to the hard work of one of our volunteers, Aleksandr Kuznetsov, we also have a partial clone of tekinged for the Woleaian language:<br>
     <a href=http://woleai.tekinged.com>Online Woleaian-English Dictionary</a>.
     ";

echo "<p>Note that since our focus is on the Palauan language, we do not actively maintain this
      page nor seek new materials for it.  However, we are very happy to add any new materials which do come our way.  If you have materials you would like added, please email them to
      us at info@tekinged.com or post a link in the comments below."; 


if (isset($_GET['sort'])) {
  $sort = $_GET['sort'];
} else {
  $sort = 'title';
}

if (isset($_GET['filter'])) {
  $filter = $_GET['filter'];
  if ($filter == '--') {
    $filter = NULL;
  }
  $_SESSION['filter'] = $filter;
} else {
  $_SESSION['filter'] = ''; 
}
$filter = $_SESSION['filter'];

if (in_array($sort,array('title','author','category'))) {
  $order = 'ASC';
} else {
  $order = 'DESC';
}

$categories = Array();

$q = "select * from austronesian order by $sort $order,title ASC";
$r = query_or_die($q);
$table = '<table class="TFtable">';


$table .= make_row(array(sort_link('title','TITLE'),
                sort_link('author','AUTHOR(S)'),
                sort_link('year','YEAR'),
                sort_link('category','CATEGORY'),
                sort_link('html','BROWSE'),
                sort_link('pdf','PDF'),
                sort_link('ebook','EBOOK'),
                sort_link('purchase','PURCHASE')));
while($row = mysqli_fetch_assoc($r)) {
  extract($row);

  # build full list of categories
  if (! in_array($category,$categories)) {
    $categories[] = $category;
  }

  # skip if not category
  if ($filter != NULL and $filter != $category) {
    continue;
  }

  if ($pdf != NULL) {
    $pdf = "http://tekinged.com/misc/pdf.php?file=$pdf";
    $pdf = make_link($pdf,'PDF');
  }
  $ebook = make_link($ebook,'ePub');
  $purchase = make_link($purchase,'Hard Bound');
  $browse = make_link($html,'Web');
  $table .= make_row(array($title,$author,$year,$category,$browse,$pdf,$ebook,$purchase));
}
$table .= "</table>\n";

# add the filter form
echo "
  <div class='tab'><!-- filter box -->
    <h3 style='display: inline;' >Filter by category: </h3>
  <form method='get' style='display: inline;'>
    <select name='filter' onchange='this.form.submit();'>
      <option >--</option>
     ";
sort($categories);
foreach ($categories as $category) {
  if ($category == $filter) {
    $selected = 'selected';
  } else {
    $selected = NULL;
  }
  echo "<option $selected>$category</option>\n";
}

echo "
  </select>
  </form>
  </div> <!-- filter box -->
  <br>
    ";

print $table;

echo "<h2>Please suggest more in the comments below!</h2>\n";

end_simple_page(": $filter");
?>

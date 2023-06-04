<?php 
session_start();
include '../functions.php'; 

function make_link($url,$label,$target='') {
  $link = NULL;
  if ($url != NULL) {
    $link = "<a href=$url $target>$label</a>";
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
$config['title'] = "Palauan Language Reading Materials";
start_simple_page($config);

echo "<h2>" . $config['title'] . "</h2>";

if (isset($_GET['sort'])) {
  $sort = $_GET['sort'];
} else {
  $sort = 'title';
}

if (isset($_GET['filter'])) {
  $filter = $_GET['filter'];
  debug("filter from GET is $filter\n");
  if ($filter == '--') {
    $filter = NULL;
  }
  $_SESSION['filter'] = $filter;
}
$filter = $_SESSION['filter'] ?? NULL; 
debug("filter is $filter\n");

if (in_array($sort,array('title','author','category','grade'))) {
  $order = 'ASC';
} else {
  $order = 'DESC';
}

$categories = Array();

$q = "select * from books order by $sort $order,title ASC";
$r = query_or_die($q);
$table = '<table class="TFtable">';


$table .= make_row(array(sort_link('title','TITLE'),
                sort_link('author','AUTHOR(S)'),
                sort_link('year','YEAR'),
                sort_link('category','CATEGORY'),
                sort_link('grade','GRADE'),
                sort_link('html','BROWSE'),
                sort_link('pdf','PDF'),
                sort_link('ebook','EBOOK'),
                sort_link('purchase','PURCHASE')));
while($row = $r->fetch_assoc()){ 
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
  $table .= make_row(array($title,$author,$year,$category,$grade,$browse,$pdf,$ebook,$purchase));
}
$table .= "</table>\n";


echo "<p class='tab'>tekinged.com is very happy to provide all of the following books for free. If you are aware of any other books that should be in this list, 
      please add a comment in the below comments section or email us at info@tekinged.com to let us know.";

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

echo "<p class='tab'>All books available for purchase at lulu.com are conveniently grouped <a href=http://www.lulu.com/spotlight/tekinged>here</a>.  
      Please note that tekinged.com does not now, nor 
      will ever, make any money from these purchases; all of the money used to purchase these books goes directly to the printing company lulu.com.<br>\n";
echo "<p class='tab'>Some additional reading such as smaller Palauan language documents and academic linguistic articles can be found on our <a href=/links.php>links page</a>.";
echo "<h2>Please suggest more books in the comments below!</h2>\n";

end_simple_page(": $filter");
?>

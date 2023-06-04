<?php 
include '../functions.php'; 

function containsWord($str, $word)
{
  #return strpos($str,$word);
  return !!preg_match('#\b' . preg_quote($word, '#') . '\b#i', $str);
}

function print_group($g,$filter) {
  $show_group = True;

  $group = "<h3>Group $g</h3>\n";
  $group .= "<div class='tab'>\n";
  $group .= table_to_string("select upper(a.pal) as Word,a.pos as POS,a.eng as Eng,a.pdef as Pal from all_words3 a,synonyms c
                  where c.word=a.id and c.grouping = $g 
                  order by c.grouping,a.pal;");
  $group .= "</div>\n";

  echo $group;
} 

function add_form() {
  $filter = $filter ?? NULL;
  echo "
     <form method='post' class='inl'><br>
      <span class='tab'>Search for synonyms: <input type='text' name='filter' value='$filter' /></span>
    </form>
    <p>
  ";
}

$config = array();
$config['title'] = "Palauan Thesaurus";
start_simple_page($config);

echo "<h2>" . $config['title'] . "</h2>";
add_form();

$filter = $_POST['filter'] ?? NULL;
#print "filter is $filter";

$q = "select distinct(grouping),count(*) as c from synonyms having c>1";
if (strlen($filter)>1) {
  $q = "select grouping as g from synonyms where word in (select id from all_words3 where pal like '$filter')";
} else {
  $q = "select distinct(grouping) as g,count(*) as c from synonyms group by grouping having c>1 order by grouping;";
}
$r = query_or_die($q);

echo "<div class='tab'>\n";
while($row = $r->fetch_assoc()) { 
  extract($row);
  print_group($g,$filter);
}
echo "</div>\n";

echo "<h2>Please suggest more in the comments below!</h2>\n";

end_simple_page();
?>

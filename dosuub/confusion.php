<?php 
include '../functions.php'; 

$config = array();
$config['title'] = "Commonly Confused Palauan Words";
start_simple_page($config);

echo "<h2>" . $config['title'] . "</h2>";

$q = "select max(grouping) from confusion";
$r = query_or_die($q);
$w = $r->fetch_array(MYSQLI_NUM); 
$max = $w[0];

echo "<div class='tab'>\n";
for ($x = 1; $x <= $max; $x++) {
    echo "<h3>Group $x</h3>\n";
    echo "<div class='tab'>\n";
      print_table("select upper(a.pal) as Word,a.pos as POS,concat(upper(b.pal),': ',b.eng) as 'Root Word' from all_words3 a,confusion c,all_words3 b 
                    where c.word=a.id and a.stem=b.id and c.grouping = $x 
                    order by c.grouping,a.pal;");
    echo "</div>\n";
} 
echo "</div>\n";

echo "<h2>Please suggest more in the comments below!</h2>\n";

end_simple_page();
?>

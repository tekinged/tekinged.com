<?php 
include '../functions.php'; 

$GLOBALS['DEBUG'] = False;

$config = array();
$config['title'] = "Palauan Synonyms";
start_simple_page($config);

echo "<h2>" . $config['title'] . "</h2>";



$q = "select * from synonyms";
$r = query_or_die($q);
$i = 1;
while($row = mysql_fetch_assoc($r)) {
    extract($row);
    #print "<h3>Synonym set $i"; $i++;
    if ($i > 1) {
      echo "<hr>";
    }
    $i++;
    echo "<div class='tab'>\n";
    print_table("select upper(pal),pos,eng from all_words3 where id = $a or id = $b",False);
    #show_words("select * from all_words3 where id = $a or id = $b", False,Null,False);
    echo "</div>\n";
} 
echo "</div>\n";

echo "<h2>Please suggest more in the comments below!</h2>\n";

end_simple_page();
?>

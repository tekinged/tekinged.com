<?php 
include '../functions.php'; 

$config = array();
$config['title'] = "Palauan Pronounciation Guide";
start_simple_page($config);
echo "<p id='tab'>
     This page shows example words and some explanations for Palauan pronunciations of the Palauan letters and various blends.
      <br>
     For more thorough explanations, please refer to the <a href='/books/malsol.php'>Malsol</a> and <a href='/books/handbook1.php'>Josephs</a> books.
      </p>";
print_table("select letters as Letters, palauan as 'Example Words', english as Explanation from sounds order by letters"); 
end_simple_page();
?>

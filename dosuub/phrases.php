<?php 
include '../functions.php'; 

$config = array();
$config['title'] = "Common Palauan Phrases";
start_simple_page($config);

echo "<h2>" . $config['title'] . " and Beginner Conversational Palauan With Audio</h2>";

$query = "select id as 'Audio&nbsp\;&nbsp\;&nbsp\;&nbsp\;',Palauan ,English from sentences where Source rlike 'Debbie' order by id";

function add_audio($row) {
  return audio_button('upload_sentence',$row[0]); 
}

print "<div class='bothtab'>\n";
print "<div id='jquery_jplayer'></div><!-- jquery_jplayer -->\n";
print_table($query,True,False,False,"add_audio");
Print "</div><!-- bothtab -->\n";

end_simple_page();
?>

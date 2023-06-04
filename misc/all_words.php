<?php 
include '../functions.php'; 

$config = array();
$title="All Palauan Words with Definitions in English and Palauan";
$config['title'] = $title;
$config['html'] = "includes/all_words.html";
$config['intro'] = "<h4>This page shows the full set of palauan words in tekinged.com along with their English and Palauan definitions.
                  It is useful for some purposes but not very easily readable for casual browsers who may be more interested in:
                  <ul><li>the <a href=/>main search page</a> 
                  <li>the <a href=/misc/browse.php>word browser</a>.
                  <li>or the <a href=/random.php>random words</a> page.
                  </ul>
                  </h4>
                  ";
#$config['aside'] = "$title";

html_page($config);

<?php 
include '../functions.php'; 
include 'book.php';
include 'dicts.php';

$GLOBALS['DEBUG'] = false;

list($title,$search,$esearch,$img_base) = get_dictionary('ponapean'); 

$intro = "<p>
          Kenneth L. Rehg and Damian G. Sohl's $title.  Their 1981 Ponapean Grammar Reference is available <a href=ponapean_grammar.php>here</a>.

          <p>
          Although it is searchable through the tekinged.com search functionality, the search for Ponapean words may not work perfectly due to the
          fact that the Ponapean order of alphabetization is not standard.

          <p>
          Although tekinged.com is dedicated to the Palauan language, it is happy to provide this book as well due to the close
          ties between the Palauan and Ponapean languages. 
          ";

$thm='/books/ponapean_dict/images/page-000.png';
$big='/books/ponapean_dict/images/page-000.png';
$aside = "<center>
              <a href='$big'>
                <img src='$thm' width=80%/>
              </a>
              </center>
              "; 


$toc = array();
$toc['Cover'] = 0;
$toc['Preface'] = 8;
$toc['Introduction'] = 10;
$toc['Grammatical Labels'] = 13;
$toc['Locating Affixed Words'] = 17;
$toc['Orthography'] = 19;
$toc['Alphabet'] = 20;
$toc['About'] = 21;
$toc['Appreviations and Symbols'] = 24;
$toc['Ponapean A-Words'] = 28;
$toc['English A-Words'] = 150;
$toc['Back Cover'] = 281;
$lastpage = 281;
$base = "/books/ponapean_dict";
$url  = "$base.php";
$img_base = "$base/images/page-";
$config['search'] = $search; 
$config['esearch'] = $esearch; 
$book = new Book($title,$toc,$lastpage,$aside,$intro,$url,$img_base,$config);

?>

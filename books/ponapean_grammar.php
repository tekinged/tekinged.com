<?php 
include '../functions.php'; 
include 'book.php';

$GLOBALS['DEBUG'] = false;

$intro = "<p>
          Kenneth L. Rehg and Damian G. Sohl's 1981 Ponapean Grammar Reference.  Their 1979 Ponapean-English Dictionary is available <a href=ponapean_dict.php>here</a>.

          <p>
          Although tekinged.com is dedicated to the Palauan language, it is happy to provide this book as well due to the close
          ties between the Palauan and Ponapean languages. 
          ";

$thm='/books/ponapean_grammar/images/page-000.jpg';
$big='/books/ponapean_grammar/images/page-000.jpg';
$aside = "<center>
              <a href='$big'>
                <img src='$thm' width=80%/>
              </a>
              </center>
              "; 


$title = "Ponapean Grammar Reference";
$toc = array();
$toc['Cover'] = 0;
$toc['Preface'] = 5;
$toc['Introduction'] = 7;
$toc['Sounds'] = 17;
$toc['Words'] = 40;
$toc['Nouns'] = 65;
$toc['Verbs'] = 103;
$toc['Sentences'] = 145;
$toc['Honorific'] = 186;
$toc['Appendix'] = 195;
$toc['Bibliography'] = 198;
$toc['Index'] = 200;
$lastpage = 204;
$base = "/books/ponapean_grammar";
$url  = "$base.php";
$img_base = "$base/images/page-";
$book = new Book($title,$toc,$lastpage,$aside,$intro,$url,$img_base);

?>

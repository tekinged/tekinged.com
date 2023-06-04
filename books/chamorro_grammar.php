<?php 
include '../functions.php'; 
include 'book.php';

$GLOBALS['DEBUG'] = false;

$intro = "<p>
          The 1973 Chamorro Reference Grammar by Donald M. Topping with the assistance of Bernadita C. Dungca.

          <p>
          Although tekinged.com is dedicated to the Palauan language, it is happy to provide this book as well due to the close
          ties between Austronesian languages. 
          ";

$thm='/books/chamorro_grammar/cover.jpg';
$big='/books/chamorro_grammar/cover.jpg';
$aside = "<center>
              <a href='$big'>
                <img src='$thm' width=80%/>
              </a>
              </center>
              "; 


$title = "Chamorro Grammar Reference";
$toc = array();
$toc['Preface'] = 4;
$toc['Introduction'] = 5;
$toc['Sounds'] = 10;
$toc['Morphology'] = 40;
$toc['Syntax'] = 107;
$toc['Linguistics Glossary'] = 147;
$toc['Bibliography'] = 151;
$toc['Index'] = 152;
$lastpage = 156;
$base = "/books/chamorro_grammar";
$url  = "$base.php";
$img_base = "$base/images/page-";
$book = new Book($title,$toc,$lastpage,$aside,$intro,$url,$img_base);

?>

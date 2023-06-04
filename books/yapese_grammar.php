<?php 
include '../functions.php'; 
include 'book.php';

$GLOBALS['DEBUG'] = false;

$intro = "<p>
          The 1977 Yapese Reference Grammar written by John Thayer Jensen with the assistance of John Baptist Iou, Raphael Defeg, and Leo David Pugram.

          <p>
          Please note that the 1977 Yapese-English Dictionary is available <a href=/books/yapese_dict.php>here</a>.

          <p>
          Although tekinged.com is dedicated to the Palauan language, it is happy to provide this book as well due to the close
          ties between Austronesian languages. 
          ";

$thm='/books/yapese_grammar/cover.jpg';
$big='/books/yapese_grammar/cover.jpg';
$aside = "<center>
              <a href='$big'>
                <img src='$thm' width=80%/>
              </a>
              </center>
              "; 


$title = "Yapese Grammar Reference";
$toc = array();
$toc['Acknowledgments'] = 2;
$toc['Table of Contents'] = 3;
$toc['Forward'] = 7;
$toc['Preface'] = 8;
$toc['Introduction'] = 10;
$toc['Phonology'] = 12;
$toc['Morphology'] = 60;
$toc['Noun Phrases'] = 85;
$toc['Verb Phrases'] = 106;
$toc['Adverbial Phrases'] = 125;
$toc['Sentence Types'] = 140;
$toc['Linguistics Glossary'] = 171;
$toc['Index'] = 176;
$lastpage = 179;
$base = "/books/yapese_grammar";
$url  = "$base.php";
$img_base = "$base/images/page-";
$book = new Book($title,$toc,$lastpage,$aside,$intro,$url,$img_base);

?>

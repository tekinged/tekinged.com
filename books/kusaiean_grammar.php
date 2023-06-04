<?php 
include '../functions.php'; 
include 'book.php';

$GLOBALS['DEBUG'] = false;

$intro = "<p>
          The 1975 Kusaiean Reference Grammar by Kee-dong Lee with the assistance of Lyndon Cornelius and Elmer Asher.

          <p>
          Although tekinged.com is dedicated to the Palauan language, it is happy to provide this book as well due to the close
          ties between Austronesian languages. 
          ";

$thm='/books/kusaiean_grammar/cover.jpg';
$big='/books/kusaiean_grammar/cover.jpg';
$aside = "<center>
              <a href='$big'>
                <img src='$thm' width=80%/>
              </a>
              </center>
              "; 


$title = "Kusaiean Grammar Reference";
$toc = array();
$toc['Table of Contents'] = 0;
$toc['Preface'] = 5;
$toc['Introduction'] = 6;
$toc['Sounds'] = 9;
$toc['Structure'] = 26;
$toc['Parts of Speech'] = 30;
$toc['Nouns'] = 31;
$toc['Verbs'] = 42;
$toc['Adjectives'] = 52;
$toc['Pronouns'] = 55;
$toc['Word Formation'] = 94;
$toc['Sentences'] = 122;
$toc['Negation'] = 161;
$toc['Word Order'] = 164;
$toc['Interrogatives'] = 170;
$toc['Complex Sentences'] = 177;
$toc['Cleft Sentences'] = 189;
$toc['Ellipsis'] = 193;
$toc['Linguistic Relationships'] = 198;
$toc['Index'] = 212;
$lastpage = 214;
$base = "/books/kusaiean_grammar";
$url  = "$base.php";
$img_base = "$base/images/page-";
$book = new Book($title,$toc,$lastpage,$aside,$intro,$url,$img_base);

?>

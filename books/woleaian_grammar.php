<?php 
include '../functions.php'; 
include 'book.php';

$GLOBALS['DEBUG'] = false;

$intro = "<p>
          This is the 1975 Woleaian Reference Grammar written by <a href='http://www.hawaii.edu/eall/korean-faculty/ho-min-sohn/'>Ho-Min Sohn</a>
          with assistance from Anthony Tawerilman, Professor Topping, and Arlene Koike, and funding provided by the Trust Territories and the
          University of Hawaii.

          <p>The 1975 companion WOLEAIAN REFERENCE GRAMMAR is located <a href=/books/woleaian_grammar.php>here</a>.  A list of Woleaian resources
          is <a href=/links.php#woleai>here</a>.

          <p>
          Although tekinged.com is dedicated to the Palauan language, it is happy to provide this book as well due to the close
          ties between the Palauan and Woleaian languages. 
          ";
$thm='/books/woleaian_grammar/cover.jpg';
$big='/books/woleaian_grammar/cover.jpg';
$aside = "<center>
              <a href='$big'>
                <img src='$thm' width=80%/>
              </a>
              </center>
              "; 


$title = "1975 Woleaian Reference Grammar";
$toc = array();
$toc['Preface'] = 7;
$toc['Introduction'] = 9;
$toc['Speech Sounds'] = 15;
$toc['Orthography'] = 50;
$toc['Word Classification'] = 60;
$toc['Nouns'] = 66;
$toc['Pronouns'] = 78;
$toc['Verbs'] = 81;
$toc['Numerals'] = 87;
$toc['Demonstratives'] = 89;
$toc['Aspects'] = 92;
$toc['Adverbs'] = 95;
$toc['Directionals'] = 98;
$toc['Subjectives'] = 101;
$toc['Prepositions'] = 102;
$toc['Conjunctions'] = 105;
$toc['Word Formation'] = 108;
$toc['Sentence Patterns'] = 145;
$toc['Noun Phrases'] = 174;
$toc['Verb Phrases'] = 217;
$toc['Adjuncts'] = 269;
$toc['Complex Sentences'] = 296;
$toc['Index'] = 317;

$lastpage = 325;
$base = "/books/woleaian_grammar";
$url  = "$base.php";
$img_base = "$base/images/page-";
$book = new Book($title,$toc,$lastpage,$aside,$intro,$url,$img_base);

?>

<?php 
include '../functions.php'; 
include 'book.php';
include 'dicts.php';

$GLOBALS['DEBUG'] = false;

list($title,$search,$esearch) = get_dictionary('yapese'); 

$intro = "<p>
          $title written by John Thayer Jensen with the assistance of John Baptist Iou, Raphael Defeg, and Leo David Pugram.

          <p>
          Please note that the 1977 Yapese Reference Grammar is available <a href=/books/yapese_grammar.php>here</a>.

          <p>
          A larger more browsable Yapese dictionary is available at <a href=http://www.trussel2.com/yap/yap-d.htm>trussel</a>. 

          <p>
          Although tekinged.com is dedicated to the Palauan language, it is happy to provide this book as well due to the close
          ties within Austronesian languages. 
          ";

$thm='/books/yapese_dict/images/page-000.png';
$big='/books/yapese_dict/images/page-000.png';
$aside = "<center>
              <a href='$big'>
                <img src='$thm' width=80%/>
              </a>
              </center>
              "; 


$title = "Yapese-English Dictionary";
$toc = array();
$toc['Cover'] = 0;
$toc['Preface'] = 6;
$toc['Acknowledgements'] = 8;
$toc['Introduction'] = 10;
$toc['B Words'] = 21;
$toc['Ch Words'] = 27;
$toc['D Words'] = 31;
$toc['F Words'] = 35;
$toc['G Words'] = 39;
$toc['H-J Words'] = 46;
$toc['K Words'] = 47;
$toc['L Words'] = 50;
$toc['M Words'] = 55;
$toc['N Words'] = 64;
$toc['P Words'] = 67;
$toc['Q Words'] = 72;
$toc['R Words'] = 78;
$toc['S Words'] = 80;
$toc['T Words'] = 83;
$toc['U,W Words'] = 90;
$toc['Y Words'] = 93;
$toc['English Word List'] = 97;
$toc['Back Cover'] = 199;
$lastpage = 199;
$base = "/books/yapese_dict";
$url  = "$base.php";
$img_base = "$base/images/page-";
$config['search'] = $search; 
$config['esearch'] = $esearch; 
$book = new Book($title,$toc,$lastpage,$aside,$intro,$url,$img_base,$config);

?>

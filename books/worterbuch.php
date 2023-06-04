<?php 
include '../functions.php'; 
include 'book.php';
include 'dicts.php';

$GLOBALS['DEBUG'] = false;

list($title,$search,$esearch) = get_dictionary('worterbuch'); 

$intro = "<p>
          $title by Walleser, Salvator, Bp.
          ";

$thm='/books/worterbuch/page-001.png';
$big=$thm;
$aside = "<center>
              <a href='$big'>
                <img src='$thm' width=80%/>
              </a>
              </center>
              "; 


#$title = "Palau Worterbuch; I. Palau-Deutsch, II. Deutsch-Palau.";
$toc = array();
$toc['Vorwort'] = 3;
$toc['Schreibweise und Aussprache'] = 5;
$toc['Gebrauchsanweisung'] = 6;
$toc['Palauan A-words'] = 7;
$toc['German A-words'] = 172;
$toc['Anhang'] = 251;
$lastpage = 268;
$base = "/books/worterbuch";
$url  = "$base.php";
$img_base = "$base/page-";
$config['search'] = $search; 
$config['esearch'] = $esearch; 
$book = new Book($title,$toc,$lastpage,$aside,$intro,$url,$img_base,$config);

?>

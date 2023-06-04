<?php 
include '../functions.php'; 
include 'book.php';
include 'dicts.php';

$GLOBALS['DEBUG'] = false;
list($title,$search,$esearch,$img_base) = get_dictionary('68mcmanus'); 

$bio = dict_link(8,"here");
$intro = "Father McManus and David Ramarui wrote the <a href='mcmanus.php'>first Palauan-English dictionary</a> in 1948.  On this page is the second much improved edition, $title,
               which was 
              written in 1968.  It contains a beatiful forward from Father McManus written in Palauan for the Palauan people whom he loved his entire life. This dictionary probably
              should not be used as a language reference since it is very different from, and has been superseded by, the Josephs 1990 <a href='/misc/dictpage.php'>Dictionary</a>, and
              the <a href='/books/75josephs.php'>1975 Reference Grammar</a> and the 1999 Handbook of Palauan Grammar.  However, it 
              remains a fascinating historical reference.
              <ul>
              <li>Read a bio of Father McManus $bio or <a href='https://www.manresa-sj.org/stamps/1_McManus.htm'>here</a>.
              </ul>\n";
$pic='/books/mcmanus/mcmanus.jpg';
$big='/books/mcmanus/McManus.jpg';
$aside = "<center>
              <a href='$big'>
                <img src='$pic' />
              </a>
              </center>
              "; 


$title = "1968 McManus Palauan Dictionary";
$toc = array();
$toc['Forward'] = 0;
$toc['Alphabet'] = 1;
$toc['Parts of Speech'] = 2;
$toc['Grammar'] = 8;
$toc['Palauan A-B'] = 13;
$toc['Palauan Ch'] = 23;
$toc['Palauan D'] = 36;
$toc['Palauan E-K'] = 44;
$toc['Palauan L-M'] = 56;
$toc['Palauan Ng'] = 115;
$toc['Palauan O'] = 118;
$toc['Palauan P-S'] = 158;
$toc['Palauan T'] = 168;
$toc['Palauan U'] = 175;
$toc['Palauan W-Y'] = 181;
$toc['English A-F'] = 183;
$toc['List of Sea Life'] = 207;
$toc['English G-N'] = 214;
$toc['English O-T'] = 230;
$toc['List of Trees'] = 253;
$toc['English U-Z'] = 256;
$toc['Corrections'] = 261;

$config['search'] = $search;
$config['esearch'] = $esearch; 

$lastpage = 262;
$base = "/books/68mcmanus";
$url  = "$base.php";
$book = new Book($title,$toc,$lastpage,$aside,$intro,$url,$img_base,$config);

?>

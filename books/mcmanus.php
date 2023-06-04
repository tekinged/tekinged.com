<?php 
include '../functions.php'; 
include 'book.php';
include 'dicts.php';

$GLOBALS['DEBUG'] = false;

$bio = dict_link(8,"here");
$intro = "Father McManus and David Ramarui wrote the first Palauan-English dictionary in 1948, $title, which was typewritten by Cecilia Hendricks in 1950.  
              A <a href='68mcmanus.php'>second edition</a>
              was produced in 1968. This dictionary probably
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

#$config['search'] = 'select page,last from dict_pages_68mcmanus'; 
$config['esearch'] = $esearch; 

$toc = array();
$toc['Preface'] = 0;
$toc['Introduction'] = 2;
$toc['English to Palauan'] = 3;
$toc['Grammar Notes'] = 63;
$toc['Possessive Nouns'] = 66;
$toc['Useful Phrases'] = 68;
$toc['Some Perfective Verbs'] = 73;
$lastpage = 73;
$base = "/books/mcmanus";
$url  = "$base.php";
list($title,$search,$esearch,$img_base) = get_dictionary('mcmanus'); 
$book = new Book($title,$toc,$lastpage,$aside,$intro,$url,$img_base,$config);

?>

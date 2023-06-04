<?php 
include '../functions.php'; 
include 'book.php';

$GLOBALS['DEBUG'] = false;

$intro = "In December 1999, Yosko J. Malsol from Ngeremlengui compiled a Palauan Language Workbook.  
          It contains pronounciation guides, example conversations, grammar rules, explanations of the 
          linking words like <i>er, a,</i> and <i>me</i>, and much more.
          In July 2015, tekinged.com received official approval from Yosko Malso, the Palau Language Commission,
          and the Palau Ministry of Education to publish these materials. <br>
          <p>
          <p>
          A print bound version is available for purchase at 
          <a href=http://www.lulu.com/content/paperback-book/malsol-palauan-language-workbook/16533509>lulu.com</a>.
          Note that tekinged.com receives no money from this sale nor from anything else; we are dedicated to providing
          free access to Palauan language materials.
          <p>
          Or download a <a href='/misc/pdf.php?file=malsol'>free PDF version</a> for self-printing or to add to a tablet or other digital reader. 
          ";
$thm='/books/malsol/iungs.png';
$big='/books/malsol/iungs.jpg';
$aside = "<center>
              <a href='$big'>
                <img src='$thm' />
              </a>
              </center>
              "; 


$title = "1999 Yosko Malso Palauan Language Workbook";
$toc = array();
$toc['Title Page'] = 0;
$toc['Sounds'] = 2;
$toc['Greetings'] = 10;
$toc['Numbers'] = 12;
$toc['Colors'] = 15;
$toc['Days, Months, Events, Time'] = 17;
$toc["Conjunction 'el'"] = 20;
$toc['Connectives'] = 25;
$toc["The Word 'a'"] = 31;
$toc["The Word 'er'"] = 34;
$toc['Nouns'] = 38;
$toc['Noun Possession'] = 41;
$toc['Pronouns'] = 44;
$toc['Object Pronouns'] = 47;
$toc['Questions'] = 50;
$toc['Verbs'] = 54;
$toc['Dialogues'] = 57;
$toc['Glossary'] = 77;

$lastpage = 83;
$base = "/books/malsol";
$url  = "$base.php";
$img_base = "$base/images/page-";
$book = new Book($title,$toc,$lastpage,$aside,$intro,$url,$img_base);

?>

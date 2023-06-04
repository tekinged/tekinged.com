<?php 
include '../functions.php'; 
include 'book.php';
include 'dicts.php';

$GLOBALS['DEBUG'] = false;
list($title,$search,$esearch,$img_base) = get_dictionary('kerresel'); 

$full_title = "$title, Tekoi er a Belau me a Omesodel";
$base = "/books/kerresel";
$url  = "$base.php";

$BNM="<a href='http://www.belaunationalmuseum.net'>Belau National Museum</a>";

$config = array();
$config['header'] = "<br><b>$full_title</b><br>
                    <ul><li>by <i>Augusta N. Ramarui</i> and <i>Melii K. Temael</i></li>
                    <li>Available courtesy of $BNM where beautifully bound print editions are available for purchase.</li>
                    </ul>
                    ";
$config['search'] = $search; 

$intro = "<h2>$full_title</h2><br>
          <i>Augusta N. Ramarui, Melii K. Temael</i><br>
          <ul><li>Beautifully bound print editions available for purchase at the $BNM.</li></ul>
          <p>This dictionary was written by Augusta N. Ramurai and Melii K. Temael in 2000 with the cooperation of the $BNM.  It is the only Palauan-Palauan dictionary currently available.
          More infortmation about it can be found in a <a href='/misc/pdf.php?file=gibson-ker'>highly positive review </a>written by Robert Gibson, retired professor of linguistics, UH.
          <p>
          This online viewer may only be used to look up individual words and is not available for download.  To acquire a copy, it must be purchased at the $BNM.</p>
         ";
$aside = "<center>
          <img src='$base/images/page-000.png' />
          $title
          </center>
          ";
$pic="$base/images/cover_thumb.png";
$big="$base/images/page-000.png";
$aside = "<center>
              <a href='$big'>
                <img src='$pic'>
              </a>
              <br>
              Kerresel A Klechibelau
              by Ramarui and Temael 
              </center>
              "; 


$toc = array();
$toc['Cover']   =   0;
$toc['A words'] =   1;
$toc['B words'] =   3;
$toc['C words'] =  37;
$toc['D words'] =  87;
$toc['E words'] = 117;
$toc['H words'] = 123;
$toc['I words'] = 125;
$toc['K words'] = 133;
$toc['L words'] = 197;
$toc['M words'] = 205;
$toc['N words'] = 297;
$toc['O words'] = 317;
$toc['R words'] = 415;
$toc['S words'] = 431;
$toc['T words'] = 455;
$toc['U words'] = 483;
$toc['Imuul er a Bai'] = 518;
$toc['Klumech'] = 520;
$toc['Beldeklel a Tekoi'] = 521;
$toc['Cheselsir a Rengelekel a Milad'] = 522;
$toc['Llecheklel Belau'] = 523;
$toc['Omesodel'] = 524;
$toc['Ngeruuchel'] = 525;
$toc['Klumech er a Augusta N. Ramarui'] = 526;
$toc['Klumech er a Melii K. Temael'] = 527;
$toc['Siasing er a Augusta me a Melii'] = 528;
$toc['Omereng el Saul'] = 529;
$toc['Klisichel a Ngor'] = 530;
$toc['Llechukl er a Belau'] = 531;
$lastpage = 532;

$book = new Book($title,$toc,$lastpage,$aside,$intro,$url,$img_base,$config);

?>

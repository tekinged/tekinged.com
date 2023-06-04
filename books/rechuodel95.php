<?php 
include '../functions.php'; 
include 'book.php';
include './rechuodel95/toc.php';

$GLOBALS['DEBUG'] = false;

$intro = "<p>
          This webpage contains both Volumes 1 and 2 of RECHUODEL written in 1995 by the Palau Society of Historians as commissioned by the Division 
          of Cultural Affairs in the Ministry of Community and Cultural Affairs.  It contains a huge amount of fascinating discussions of
          all things unique about Palauan culture.  From ceremonies to traditional governance to fishing and betelnut and everything in
          between.

          <p>
          These are the historians who participated in this amazing work:<br>
          Ngiraibuuch Malsol, Wataru Elbelau, Johanes Ngirakesau, Yosko O. Ngiratumerang, Eledui Omeliakl, Cristoballdip, Telmetang Orkerk, 
          Melaitau Tebei, Ngirngeterang Iechad, Rimat Ngiramechelbang, Paulus O. Sked, Edeluchel Eungel, Retechang Meduu, Chiokai Kloulubak, and Augustine Smau.
                    ";
$thm='/books/rechuodel95/images2/page-000.png';
$big=$thm;
$aside = "<center>
              <a href='$big'>
                <img src='$thm' width=80%/>
              </a>
              </center>
              "; 


$title = "Rechuodel Volumes 1 and 2";
$toc = get_toc(); 

$lastpage = 185;
$base = "/books/rechuodel95";
$url  = "$base.php";
$img_base = "$base/images2/page-";
$book = new Book($title,$toc,$lastpage,$aside,$intro,$url,$img_base);

?>

<?php 
include '../functions.php'; 
include 'book.php';
include 'handbook.php';

$GLOBALS['DEBUG'] = false;

$toc = array();
$toc['Preface'] = 22;
$toc['Spelling'] = 24; # 1 
$toc['Nouns'] = 78;  # 55
$toc['Noun Possession'] = 112;
$toc['Pronouns'] = 156;
$toc['Verbs'] = 208;
$toc['Verb Marker and Related Forms'] = 252;
$toc['State Verbs'] = 286;
$toc['Complex Nouns'] = 316;  
$toc['Causative Verbs'] = 342;
$toc['Reciprocal Verbs'] = 362;
$toc['Reduplication'] = 382;
$toc['Additional Verb Suffixes'] = 418;
$toc['Index'] = 430;

$lastpage = 449;
$base = "/books/handbook1";
$vol = 'vol1';
make_handbook($toc,$vol,$lastpage,$base);
?>

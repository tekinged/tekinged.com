<?php 
include '../functions.php'; 
include 'book.php';
include 'handbook.php';

$GLOBALS['DEBUG'] = false;

$vol = 'tea1';
$toc = array();
$toc['Cover'] = 0; # 1 
$toc['Spelling'] = 9; # 1 
$toc['Nouns'] = 35;  # 55
$toc['Noun Possession'] = 63;
$toc['Pronouns'] = 91;
$toc['Verbs'] = 119;
$toc['Verb Marker and Related Forms'] = 141;
$toc['State Verbs'] = 155;
$toc['Complex Nouns'] = 169;  
$toc['Causative Verbs'] = 185;
$toc['Reciprocal Verbs'] = 199;
$toc['Reduplication'] = 215;
$toc['Additional Verb Suffixes'] = 239;

$lastpage = 246;
$base = "/books/teach1";
make_handbook($toc,$vol,$lastpage,$base);
?>

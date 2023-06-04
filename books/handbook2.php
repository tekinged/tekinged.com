<?php 
include '../functions.php'; 
include 'book.php';
include 'handbook.php';

$GLOBALS['DEBUG'] = false;

$vol = 'vol2';
$toc = array();
$toc['Preface'] = 16;
$toc['Relational Phrases'] = 18; # 1 
$toc['Dependent Clauses'] = 64;  
$toc['Sentence Formation'] = 108;
$toc['Negation'] = 152;
$toc['Prefix Pronoun Predicates'] = 182;
$toc['Questions'] = 228;
$toc['Direct & Indirection Quotation'] = 252;
$toc['Reason, Result, & Time Clauses'] = 268;  
$toc['Relative Clauses'] = 288;
$toc['Modifiers'] = 302;
$toc['Connecting Words'] = 326;
$toc['Impact of Foreign Languages'] = 342;
$toc['Index'] = 406;

$lastpage = 437;
$base = "/books/handbook2";
make_handbook($toc,$vol,$lastpage,$base);
?>

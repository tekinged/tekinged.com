<?php 
include '../functions.php'; 
include 'book.php';
include 'handbook.php';

$GLOBALS['DEBUG'] = false;

$vol = 'tea2';
$toc = array();
$toc['Cover'] = 0; # 1 
$toc['Relational Phrases'] = 9; # 1 
$toc['Dependent Clauses'] = 29;  
$toc['Sentence Formation'] = 53;
$toc['Negation'] = 83;
$toc['Prefix Pronoun Predicates'] = 105;
$toc['Questions'] = 133;
$toc['Direct & Indirection Quotation'] = 147;
$toc['Reason, Result, & Time Clauses'] = 157;  
$toc['Relative Clauses'] = 167;
$toc['Modifiers'] = 175;
$toc['Connecting Words'] = 187;
$toc['Impact of Foreign Languages'] = 195;

$lastpage = 215;
$base = "/books/teach2";
make_handbook($toc,$vol,$lastpage,$base);
?>

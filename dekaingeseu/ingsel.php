<?php 
session_start();
include '../functions.php'; 

$title="Translate Ingsel a Demul";
$GLOBALS['DEBUG'] = false;
$TABLE = "translate_ingsel";
$book = "Island of the Blue Dolphin";

include 'translate.php';

<?php 
session_start();
include 'tags.php';

$GLOBALS['DEBUG'] = false;
simple_tag_page('shape');
?>

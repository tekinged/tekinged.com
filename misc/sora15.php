<?php 
include '../functions.php'; 

$config = array();
$config['title'] = "President Remengesau's 2015 State of the Republic Address";
$config['html'] = "includes/sora15.html";
$img = "<img src=includes/tommy.jpg width=80%>";
$utube = "Listen to the speech in Palauan on <a href=https://www.youtube.com/watch?t=14&v=WnbjQjIs1UI>Youtube</a>."; 
$config['aside'] = "<center>$img<br>$utube</center>";

html_page($config);

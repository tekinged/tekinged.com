<?php 
include '../functions.php'; 

$limit = 30;
$config = array();
$config['title'] = "$limit Most Recently Added Words";
$config['where'] = "id > 25000 order by added DESC limit $limit";

words_page($config);

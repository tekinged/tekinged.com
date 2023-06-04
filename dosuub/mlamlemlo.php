<?php 
include '../functions.php'; 

$config = array();
$config['title'] = "Example mla/mle/mlo sentences";
$config['where'] = "select palauan,english from examples where id >= 2097 and id <= 2099;";
$config['add_count'] = false;

table_page($config);

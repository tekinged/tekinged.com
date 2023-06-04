<?php
session_start();
$_SESSION['record_type'] = 'sentence';
include('recorder.php');
#print_r($_SESSION);
?>

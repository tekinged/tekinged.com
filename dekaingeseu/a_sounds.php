<?php
session_start();
$_SESSION['record_type'] = 'sounds';
include('recorder.php');
#print_r($_SESSION);
?>

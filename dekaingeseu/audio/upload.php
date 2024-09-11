<?php
session_start();

$audio_upload_dir = "./uploads/";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $audioBlob = $_FILES['audio']['tmp_name'];
    $username = $_SESSION['username'];
    $sentenceNumber = $_POST['sentenceNumber'];  // Assumes you pass this from frontend

    $filePath = $audio_upload_dir . $sentenceNumber . ".mp3";

    if (move_uploaded_file($audioBlob, $filePath)) {
        $_SESSION['submissionCount'] += 1;
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }
}


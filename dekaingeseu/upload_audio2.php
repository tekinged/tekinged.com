<?php
session_start();

if (isset($_FILES['audio_data']) && isset($_SESSION['User']) && isset($_SESSION['destination_folder'])) {
    $audio = $_FILES['audio_data']['tmp_name'];
    $destination_folder = $_SESSION['destination_folder'];

    // assuming the sentence number is passed as POST data
    $sentence_num = $_POST['sentence_num'];
    $filename = $destination_folder . "/" . $sentence_num . ".mp3";
    
    if (move_uploaded_file($audio, $filename)) {
        $_SESSION['submission_count'] = isset($_SESSION['submission_count']) ? $_SESSION['submission_count'] + 1 : 1;
        echo "Thanks {$_SESSION['User']} for your {$_SESSION['submission_count']} uploaded sentence";
    }
}
?>


<?php
// Check if the audio file was uploaded successfully
if (isset($_FILES['audio']) && $_FILES['audio']['error'] === UPLOAD_ERR_OK) {
    $tempFile = $_FILES['audio']['tmp_name'];
    $targetFile = 'uploads/foo.mp3'; // Set the target directory and file name

    // Move the uploaded file to the target location
    if (move_uploaded_file($tempFile, $targetFile)) {
        // Provide a success response
        http_response_code(200);
        echo 'Audio uploaded successfully!';
    } else {
        // Provide an error response
        http_response_code(500);
        echo 'Error moving the uploaded file.';
    }
} else {
    // Provide an error response if the file upload failed
    http_response_code(400);
    echo 'Error uploading the audio file.';
}
?>


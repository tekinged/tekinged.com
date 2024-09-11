<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['username'] = $_POST['username'];
}
$username = $_SESSION['username'] ?? '';

function select_sentences() {
    return [
        1 => 'Alii! Ke kmal mesisiich e ng mle kekerei?',
        2 => 'Ng techa a chomulsa a delal?',
        3 => 'Ng soak a ududek.'
    ];
}

function add_header() {
    // Will be filled in later
}

function add_footer() {
    // Will be filled in later
}

add_header();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Simple Audio Recorder</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <?php if (!$username): ?>
        <form action="record.php" method="post">
            <label for="username">Enter your name:</label>
            <input type="text" id="username" name="username">
            <input type="submit" value="Submit">
        </form>
    <?php else: ?>
        <div id="recorder">
            <h1>Thanks for making N submissions so far, <?= $username ?></h1>
            <p>Please choose the sentence you'd like to record yourself saying and use the interface below to record yourself.</p>
            <select id="sentenceSelector">
                <?php foreach (select_sentences() as $key => $sentence): ?>
                    <option value="<?= $key ?>"><?= $sentence ?></option>
                <?php endforeach; ?>
            </select>

            <div id="controls">
                <span class="icon record-icon" id="recordButton"></span>
                <span class="icon stop-icon" id="stopButton"></span>
                <span class="icon play-icon" id="playButton"></span>
                <span class="icon trash-icon" id="trashButton"></span>
                <span class="icon upload-icon" id="uploadButton"></span>
            </div>
            
            <div id="audioBox">
                <div id="progressBar"></div>
                <div id="timer"></div>
            </div>
        </div>
    <?php endif; ?>
    
    <script src="audio.js"></script>

    <?php add_footer(); ?>

</body>
</html>


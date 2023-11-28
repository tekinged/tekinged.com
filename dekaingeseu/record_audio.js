// Define variables
let mediaRecorder;
let audioChunks = [];
let isRecording = false;
let timerInterval;
let version = 3; // Manually update this version number when making changes

// Get DOM elements
const recordButton = document.getElementById('recordButton');
const playButton = document.getElementById('playButton');
const submitButton = document.getElementById('submitButton');
const timerElement = document.getElementById('timer');
const audioPlayer = document.getElementById('audioPlayer');

// ...

// Function to start recording
function startRecording() {
    // Clear the audioChunks array to start a new recording
    audioChunks = [];

    // Change Play button to Stop button while recording
    playButton.textContent = 'Stop';
    playButton.disabled = false;

    // ... rest of the code remains the same
}

// Function to stop recording
function stopRecording() {
    if (isRecording) {
        mediaRecorder.stop();
        isRecording = false;
        playButton.textContent = 'Play'; // Change Stop button back to Play
    }
}

// ...

// Event listener for 'Re-Record' button
recordButton.addEventListener('click', function() {
    if (!isRecording) {
        startRecording();
    } else {
        stopRecording();
        resetRecording(); // Call resetRecording() to clear audioChunks
    }
});

// ...

// Event listener for 'Play' button
playButton.addEventListener('click', function() {
    if (isRecording) {
        stopRecording();
    } else {
        audioPlayer.play();
    }
});

// ...

// Function to reset buttons and clear audioChunks
function resetRecording() {
    recordButton.textContent = 'Record';
    playButton.textContent = 'Play'; // Reset Play button text
    playButton.disabled = true; // Disable Play button initially
    submitButton.disabled = true;
    timerElement.textContent = '0:00';
    audioChunks = []; // Clear the audioChunks array
}

// ...

// Function to handle successful submission
function handleSubmissionSuccess() {
    // Generate a unique filename using the Unix epoch timestamp
    const timestamp = Math.floor(Date.now() / 1000); // Unix epoch in seconds
    const audioBlob = new Blob(audioChunks, { type: 'audio/mpeg' });
    const formData = new FormData();
    formData.append('audio', audioBlob, `foo.${timestamp}.mp3`); // Set the filename

    fetch(`upload_audio.php?v=${version}`, { // Add version as query parameter
        method: 'POST',
        body: formData
    })
    .then(function(response) {
        if (response.ok) {
            alert('Audio uploaded successfully!');
            console.log('Audio uploaded successfully');
            resetRecording(); // Reset buttons after successful submission
        } else {
            alert('Error uploading audio.');
            console.error('Error uploading audio');
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
    });
}

// Print the current version number
console.log(`Version ${version}`);


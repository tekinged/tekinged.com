console.log("audio.js Version: 1.0.2");

let audioBlob;
let audioUrl;
let audio = new Audio();

function setUsername() {
    const username = document.getElementById("username").value;
    if (username) {
        document.getElementById("usernameForm").style.display = "none";
        document.getElementById("recordingArea").style.display = "block";
    }
}

function startRecording() {
    navigator.mediaDevices.getUserMedia({ audio: true })
        .then(stream => {
            const mediaRecorder = new MediaRecorder(stream);
            mediaRecorder.start();

            const audioChunks = [];
            mediaRecorder.addEventListener("dataavailable", event => {
                audioChunks.push(event.data);
            });

            mediaRecorder.addEventListener("stop", () => {
                audioBlob = new Blob(audioChunks, {type: "audio/wav"});
                audioUrl = URL.createObjectURL(audioBlob);
                audio.src = audioUrl;
            });
        });

    // Update UI
    document.getElementById("stopButton").disabled = false;
    document.getElementById("recordButton").disabled = true;
}

function stopRecording() {
    // Assuming mediaRecorder is in scope and has been initialized
    mediaRecorder.stop();

    // Update UI
    document.getElementById("stopButton").disabled = true;
    document.getElementById("playButton").disabled = false;
    document.getElementById("submitButton").disabled = false;
    document.getElementById("recordButton").disabled = false;
}

function playAudio() {
    audio.play();
}

function uploadAudio() {
    const formData = new FormData();
    formData.append("audio", audioBlob);

    fetch("upload.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log("Upload successful");
        } else {
            console.log("Upload failed");
        }
    })
    .catch(error => {
        console.log("Upload error", error);
    });
}


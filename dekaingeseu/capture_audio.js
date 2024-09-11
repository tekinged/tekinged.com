var version = "1.4";
console.log("Version: " + version);

let audioChunks = [];
let rec;
let stream;
let timerInterval;

document.addEventListener("DOMContentLoaded", function() {
  document.getElementById("start").addEventListener("click", startRecording);
  document.getElementById("stop").addEventListener("click", stopRecording);
  document.getElementById("submit").addEventListener("click", submitRecording);
});

async function startRecording() {
  stream = await navigator.mediaDevices.getUserMedia({ audio: true });
  rec = new MediaRecorder(stream);

  rec.ondataavailable = event => audioChunks.push(event.data);
  rec.onstop = () => {
    const audioBlob = new Blob(audioChunks, { type: "audio/wav" });
    const audioUrl = URL.createObjectURL(audioBlob);
    document.querySelector("#audioPlayer").src = audioUrl;
    document.getElementById("submit").disabled = false;
  };

  rec.start();
  document.getElementById("start").disabled = true;
  document.getElementById("stop").disabled = false;

  let time = 0;
  timerInterval = setInterval(() => {
    time++;
    document.getElementById("timer").innerText = time + "s";
  }, 1000);
}

function stopRecording() {
  rec.stop();
  clearInterval(timerInterval);
  stream.getAudioTracks()[0].stop();
  document.getElementById("start").disabled = false;
  document.getElementById("stop").disabled = true;
}

function submitRecording() {
  // Logic to submit the recording
}


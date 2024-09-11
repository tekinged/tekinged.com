// Function to convert Blob to MP3 format
function convertBlobToMP3(blob) {
  return new Promise((resolve, reject) => {
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    const reader = new FileReader();

    reader.onload = function() {
      audioContext.decodeAudioData(reader.result, (buffer) => {
        const audioBuffer = audioContext.createBufferSource();
        audioBuffer.buffer = buffer;

        const mp3Encoder = new Mp3LameEncoder(audioContext.sampleRate, 128);
        mp3Encoder.encode([audioBuffer], (mp3Blob) => {
          resolve(mp3Blob);
        });
      });
    };

    reader.onerror = function(error) {
      console.error('Error reading Blob:', error);
      reject(error);
    };

    reader.readAsArrayBuffer(blob);
  });
}

// Play the audio
playButton.addEventListener('click', async () => {
  if (recordedAudioBlob) {
    const mp3Blob = await convertBlobToMP3(recordedAudioBlob);
    if (mp3Blob) {
      const audioUrl = URL.createObjectURL(mp3Blob);
      const audio = new Audio(audioUrl);

      audio.onplay = () => {
        console.log('Audio is playing');
      };

      audio.onended = () => {
        console.log('Audio playback ended');
      };

      audio.play()
        .then(() => {
          console.log('Audio playback initiated');
        })
        .catch(error => {
          console.error('Audio playback error:', error);
        });
    }
  }
});

// Rest of your code for recording, buttons, etc.


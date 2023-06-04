<?php
include '../functions.php';
db_connect();
?>
<!doctype html>
<html>
<head>
    <title>Dorrenges e Dosuub</title>
    <script src="jquery-2.1.4.min.js"></script>
    <script type="text/javascript">

        function mp3_to_url(mp3) {
          mp3 = '<a href=' + mp3 + '>' + mp3 + '</a>'; 
          return mp3;
        }

        function tekinged_log(msg) {
          console.log('trying to add msg to screen');
          document.getElementById('log').innerHTML += msg.concat('<br>');
        }

        function returnwasset(){
            console.log('getting asked for next mp3');
            var mp3 = '';
            $.ajax({
              type: "POST",
              url: "word.php",
              data: {action: 'test'},
              async: false,
              dataType:'JSON', 
              success: function(response){
                  console.log('successfully got response: '.concat(response));
                  // put on console what server sent back...
                  mp3 = response;
                  return mp3;
              },
              error: function(response){
                  console.log('mother fucking error" '.concat(response));
                  // put on console what server sent back...
                  mp3 = '../uploads/mp3s/examples.palauan/131.mp3';
              }
              /*
              error: function(response){
                  console.log('mother fucking error: '.concat(response));
                  mp3 = '../uploads/mp3s/examples.palauan/131.mp3';
              }
              */
            });
            mp3 = mp3.trim();
            console.log('got random mp3: '.concat(mp3));
            console.log(mp3);
            url=mp3_to_url(mp3);
            tekinged_log(url);
            return mp3;
        }

        function randomMp3() {
          var mp3 = '';
          mp3 = '../uploads/mp3s/examples.palauan/1448.mp3';
          /*
          $.post("word.php", function(result){
            mp3 = result;  
          });
          */
          //console.log('got pseudo-random');
          //tekinged_log(mp3);
          console.log(mp3);
          return mp3;
        } 

        // listener function changes src
        function myNewSrc() {
            var myAudio = document.getElementsByTagName('audio')[0];
            //myAudio.src = "../uploads/mp3s/examples.palauan/1260.mp3";
            myAudio.src = returnwasset();
            //myAudio.src = randomMp3();
            myAudio.load();
            myAudio.play();
        }

        // add a listener function to the ended event
        function myAddListener(){
            var myAudio = document.getElementsByTagName('audio')[0];
            myAudio.addEventListener('ended', myNewSrc, false);
        }
    </script>
    <link rel='stylesheet' type='text/css' href='/css/belau.css' />
</head>
<body onload="myAddListener()">
<?php
  $title="Dorrenges e Dosuub!";
  belau_header($title);
  start_content_container();
    echo "<div id='aside'>\n";
    echo "<div class='tab'>\n";
    echo "<center><h2>$title</h2></center>";
    echo "</div><!-- tab -->\n";
    echo "</div><!-- aside -->\n";
  start_content();
?>

    <p>tekinged.com is happy to provide this service to help English-speakers learn Palauan.</p>
    <br>
    <audio controls
       src="../uploads/mp3s/begin.mp3"
        autoplay
    >
    </audio>
    <br>
    <p><div id="log"></div>

<?php
  end_content();
  belau_footer(curPageURL());
?>


<?php 
include '../functions.php'; 

function startPage() {
  $title="upload audio";
  html_top($title,True);
  belau_header($title);
  start_content_container();
}

function endPage() {
  end_content();
  belau_footer("audio.html",": " . $_POST['range']); 
  end_body_html();
}

function print_request($word,$id) {
  echo "
    Please record yourself saying the following: <br>
      <ul><li><b>$word</b></ul>
    <form method='post'>
    ";
    add_input("Then paste the mp3 link here and click upload.", 'mp3',False);
    echo "<input type='submit' value='Upload' />
          <input type='hidden' name='word' value='$word'>
          <input type='hidden' name='wid' value='$id'>
          </form>
        ";
}

function print_aside() {
  echo "<div id='aside'>n";
  echo "<div class='tab'>n";
  echo "</div></div>n";
}

function print_instructions() {
  echo "<h2>Instructions for Recording</h2>";
  echo "<ul><li>Speak naturally but clearly.  Imagine you are talking to someone who is a bit hard of hearding.
    <li>After you record and are happy with your recording, find where it says:
    <ul>
    <li><i>Happy with this recording? Click here to save >></i>
    </ul>
    <li>Click on that, then find where it says, <i>Download as mp3</i>. 
    <li>Right-click on that and select <i>Copy Link</i>. 
    <li>Then paste that link into the box and click submit.
    </ul>
  ";
}

function get_upload() {
  #print_r($_POST);
  $url = $_POST['mp3']; 
  $id = $_POST['wid'];
  $name = $_POST['name'];
  echo "<br>Fetching $url...<br>";
  $file = "./mp3s/words/$id.mp3";
  file_put_contents($file, file_get_contents($url));
  $thispage = $_SERVER["REQUEST_URI"];
  echo "Thanks for uploading $name.  Click <a href='$thispage'>here</a> to do another.</br>";
}

function make_upload_request() {
  echo "<table width=100%><tr><td width=70%>";
  echo '<iframe name="myframe" src="http://vocaroo.com" width=100% height=700px marginwidth=0 marginheight=0 hspace=0 vspace=0 frameborder=0 scrolling=auto></iframe>';
  echo "</td><td valign='top'>";
  print_request("lbolb",5092);
  echo "</td></tr></table>n";
}

function make_page() {
  startPage();
  print_aside();
  start_content();
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    get_upload();
  } else {
    make_upload_request();
  }
  endPage();
}

make_page();

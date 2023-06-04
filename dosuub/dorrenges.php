<?php
session_start();
include '../functions.php';
$extra = '<script src="js/jquery-2.1.4.min.js"></script>
          <script src="js/dorrenges.js" type="text/javascript"></script>';
$title="Dorrenges e Dosuub!";
html_top_no_body($title,false,$extra);
db_connect();
echo '<body onload="myAddListener()">';
belau_header($title);
start_content_container();
echo "<div id='aside'>\n";
echo "<div class='tab'>\n";
echo "<center><h2>$title</h2></center>";
echo "</div><!-- tab -->\n";
echo "</div><!-- aside -->\n";
start_content();

if (isset($_POST['submit'])) {
  $_SESSION['dosuub_reset'] = $_POST['submit'];
  //echo "Resetting.\n";
  //print_r($_POST);
  //print_r($_SESSION);
}
?>

    <p>tekinged.com is happy to provide this service to help English-speakers learn Palauan.</p>
    <br>
    <audio controls
       src="../uploads/mp3s/begin.mp3"
        autoplay
    >
    </audio>
    <br>
      <form method='post'>
      <input type="hidden" name="action" value="submit" />
      <input id = 'begin'   name='submit' type='submit' value='Beginner'>
      <input id = 'advance' name='submit' type='submit' value='Advanced'>
      <input id = 'reset'   name='submit' type='submit' value='Reset'>
      </form>
    <br>
    <p><div id="log"></div>

<?php
  end_content();
  belau_footer(curPageURL());
?>


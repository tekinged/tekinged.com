<?php 
include '../functions.php'; 
$title="Online Palau Language Trivia Quiz"; 
html_top($title);
belau_header($title);

function show_questions() {
  $current = get_count("upload_trivia");
  $needed = 200;
  echo "<p>Unfortunately, we don't yet have enough questions to make a quiz.  However, please help us by adding some questions:
          <a href='/dekaingeseu/d_trivia.php'>Dekaingeseu!</a>
        <p>Currently we have <b>$current</b> questions.  The quiz will go live once we get <b>$needed</b>!";
  echo "<p>In the meantime, here is a list of the trivia questions that have been submitted thus far:<br>"; 

  echo "<ol>\n";
  $q = "select * from upload_trivia order by id";
  $r = query_or_die($q);
  while($row = mysql_fetch_assoc($r)) {
    extract($row);
    $incorrect = implode(', ', array($o1,$o2,$o3,$o4));
            #<li>$a [$incorrect]
    echo "<li>$q
          <ul>
            <li>Want to see the answer to this question?<br>Click <a href=/dekaingeseu/d_trivia.php>HERE</a> to help unlock the quiz so you can see the answers!
          </ul>
          ";
  }
  echo "</ol>\n";

  echo "Help us get this quiz working by <a href=/dekaingeseu/d_trivia.php>adding your own questions</a>!";
}

?>

    <div id="content-container">

        <div id="aside">
          <div class='tab'>
          Unfortunately, we don't yet have enough questions to make a quiz.  
          <p>
          <p>
          However, please help us by adding some questions:
          <a href='/dekaingeseu/d_trivia.php'>Dekaingeseu!</a>
          </div>
        </div>
        <div id="content"> 
            <?php echo "<h2>$title</h2>" ?>

          <?php show_questions(); ?>

        </div>

        <?php belau_footer(); ?>
    <div>

</body>
</html>

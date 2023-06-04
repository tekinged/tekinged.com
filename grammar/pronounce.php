<?php 
include '../functions.php'; 
$title = "Palauan Sounds";
html_top($title,true);
belau_header($title);

$GLOBALS['DEBUG'] = false;

function add_audio($row) {
  return audio_button("sounds",$row[0]); 
}

?>

    <div id="content-container">
    <div class='bothtab'>

        <?php

          echo "<br><br>
              <p id='tab'>
             This page shows example words and some explanations for Palauan pronunciations of the Palauan letters and various blends.
              <br>
             For more thorough explanations, please refer to the <a href='/books/malsol.php'>Malsol</a> and <a href='/books/handbook1.php'>Josephs</a> books.
              </p>";
            $filter = $_POST['filter'] ?? '';
            echo "<div>&nbsp;</div>\n";
            echo "<h2 class='inl'>$title</h2> 
               <form method='post' class='inl'><br>
                <span class='tab'>Filter results: <input type='text' name='filter' value='$filter' /></span>
              </form>
            ";
            $query = "select id as 'Audio&nbsp\;&nbsp\;&nbsp\;&nbsp\;',letters as Letters, palauan as Palauan,english as Explanation from sounds";
            if (strlen($filter)>0) {
              $query .= " where letters rlike '$filter' ";
            }
            $query .= " order by letters"; 
            Debug($query);
            # print the found entries.  In case there is audio, add the audio div
            print "<div id='jquery_jplayer'></div><!-- jquery_jplayer -->\n";
            print_table($query,True,False,False,"add_audio");
            #print_table($query,True,False,False);
            $result = mysqli_query($GLOBALS['mysqli'],$query);
            $num_rows = $result->num_rows; 
            if (!$result) {
                die("Query to show fields from table failed");
            }
            if ($num_rows == 0) {
              echo "<p>Filter $filter overly restrictive.  No results found.  Try a different filter or clear it altogether.</p>\n";
            }      
            if (strlen($filter)>0) {
              querylog($filter,$num_rows==0?0:1,'sn','sounds');
            }
        ?>

        <?php belau_footer("/misc/pronounce.php", ": $filter"); ?>
    </div>
    </div>

</body>
</html>

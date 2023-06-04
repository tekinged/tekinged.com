<?php 
include '../functions.php'; 
$title = "Palauan Proverbs";
html_top($title,true);
belau_header($title);

$GLOBALS['DEBUG'] = false;

function add_audio($row) {
  return audio_button("proverb",$row[0]); 
}

?>

    <div id="content-container">
    <div class='bothtab'>

        <?php
            $filter = $_POST['filter'] ?? '';
            echo "<div>&nbsp;</div>\n";
            echo "<h2 class='inl'>$title</h2> 
               <form method='post' class='inl'><br>
                <span class='tab'>Filter results: <input type='text' name='filter' value='$filter' /></span>
              </form>
            ";
            $query = "select id as 'Audio&nbsp\;&nbsp\;&nbsp\;&nbsp\;',palauan as Palauan,english as English,explanation as Explanation from proverbs";
            if (strlen($filter)>0) {
              $query .= " where palauan rlike '$filter' or english rlike '$filter' or explanation rlike '$filter'";
            }
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
              querylog($filter,$num_rows==0?0:1,'pr','proverbs');
            }
        ?>

        <div id='vizbreak'>Most of these proverbs are taken from <a href='/misc/pdf.php?file=mcknight'>McKnight's 1968 paper</a>.  Some are borrowed from 
            <a href='http://www.oocities.org/southbeach/palms/6757/proverbs.html'>YDKDY's defunct geocities page</a>.
            If you know of any more, please email them to <a href=mailto:info@tekinged.com>info@tekinged.com</a> or add them in the comments below.</div>
        <div>&nbsp;</div>

        <?php belau_footer("/misc/proverbs.php", ": $filter"); ?>
    </div>
    </div>

</body>
</html>

<?php 
include '../functions.php'; 
include 'audio_helper.php';
$title = "Palauan Example Text";
html_top($title,true);
belau_header($title);

$GLOBALS['DEBUG'] = false;
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
            $query = "select id as 'Audio&nbsp\;&nbsp\;&nbsp\;&nbsp\;',Palauan ,English ,Source from sentences";
            if (strlen($filter ?? '') > 0) {
              $query .= ' where Palauan regexp "\\\\b'.$filter.'\\\\b" or English regexp "\\\\b'.$filter.'\\\\b" order by Palauan';
            }
            Debug($query);
            # print the found entries.  In case there is audio, add the audio div
            print "<div id='jquery_jplayer'></div><!-- jquery_jplayer -->\n";
            print_table($query,True,False,False,"add_audio");
            $result = mysql_query($query);
            $num_rows = mysql_num_rows($result);
            if (!$result) {
                die("Query to show fields from table failed");
            }
            if ($num_rows == 0) {
              echo "<p>Filter $filter overly restrictive.  No results found.  Try a different filter or clear it altogether.</p>\n";
            }      
            if (strlen($filter)>0) {
              querylog($filter,$num_rows==0?0:1,'ex','examples');
            }
        ?>

        <div id='vizbreak'>Many of these examples are taken from the 1990 <a href='/misc/dictpage.php'>Josephs dictionary</a> 
            or from Justin Nuger's <a href="http://ju-st.in//pdf/nuger-dissertation.pdf">dissertation</a> or  &nbsp; 
            were uploaded by volunteers.  The sentences marked with '(E)' are more likely to use correct spelling and orthography than those marked with '(U)'.
            <br>
            If you would like to add any more, please use the <a href=/dekaingeseu/d_sentence.php>upload page</a>.  
        </div>
        <div>&nbsp;</div>

        <?php belau_footer("/misc/examples.php", ": $filter"); ?>
    </div>
    </div>

</body>
</html>

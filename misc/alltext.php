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
            if(isset($_POST['filter'])){
              $filter = $_POST['filter'];
            } else {
              $filter = '';
            }
            echo "<div>&nbsp;</div>\n";
            echo "<h2 class='inl'>$title</h2> 
               <form method='post' class='inl'><br>
                <span class='tab'>Filter results: <input type='text' name='filter' value='$filter' /></span>
              </form>
            ";
            $query = "select id as 'Audio&nbsp\;&nbsp\;&nbsp\;&nbsp\;',palauan as Palauan,english as English, source as Source from all_paltext";
            if (strlen($filter)>0) {
              # $query .= ' where Palauan regexp "[[:<:]]'.$filter.'[[:>:]]" or English regexp "[[:<:]]'.$filter.'[[:>:]]" order by Palauan';
              # mysql broke this regexp: https://stackoverflow.com/questions/59998409/error-code-3685-illegal-argument-to-a-regular-expression
              # regexp broke: rather than using [[:<:]] and [[:>:]], you can now use \b
              $query .= ' where Palauan regexp "\\\\b'.$filter.'\\\\b" or English regexp "\\\\b'.$filter.'\\\\b" order by Palauan';
            } else {
              $query .= " order by rand(),Palauan limit 50";
            }
            Debug($query);
            # print the found entries.  In case there is audio, add the audio div
            print "<div id='jquery_jplayer'></div><!-- jquery_jplayer -->\n";
            $table = table_to_string($query,True,False,False,"add_audio");
            $table = highlight_word($filter,$table);
            echo $table;
            $result = query_or_die($query);
            $num_rows = mysqli_num_rows($result);
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
            or from Justin Nuger's <a href="http://ju-st.in//pdf/nuger-dissertation.pdf">dissertation</a> or  
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

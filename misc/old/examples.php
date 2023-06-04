<?php 
include '../functions.php'; 
$title = "Palauan Examples";
html_top($title);
belau_header($title);

$GLOBALS['DEBUG'] = false;
?>

    <div id="content-container">
    <div class='bothtab'>

        <?php
            $filter = $_POST['filter'];
            echo "<div>&nbsp;</div>\n";
            echo "<h2 class='inl'>$title</h2> 
               <form method='post' class='inl'><br>
                <span class='tab'>Filter results: <input type='text' name='filter' value='$filter' /></span>
              </form>
            ";
            $query = "select palauan as Palauan,english as English,source as Source,id as ID from examples";
            if (strlen($filter)>0) {
              $query .= " where palauan rlike '$filter' or english rlike '$filter'";
            }
            Debug($query);
            print_table($query,True,False);
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

        <div id='vizbreak'>Most of these examples are taken from the 1990 <a href='/misc/dictpage.php'>Josephs dictionary</a> 
            or from Justin Nuger's <a href="http://ju-st.in//pdf/nuger-dissertation.pdf">dissertation</a> or
            from others as marked.  
            If you would like to add any more, please email them to <a href=mailto:info@tekinged.com>info@tekinged.com</a> or add them in the comments below.</div>
        <div>&nbsp;</div>

        <?php belau_footer("/misc/examples.php", ": $filter"); ?>
    </div>
    </div>

</body>
</html>

<?php 
include 'functions.php'; 
$title="Five Random Palauan Words";
html_top($title);
belau_header($title);
?>

    <div id="content-container">

        <div id="aside">
            </ul>
        </div>
        <div id="content"> 
            <?php 
            echo "<h2>$title</h2>"; 
            echo "<p class='tab'>";
            $nvulg = not_vulgar();
            print_table("select pal as Palauan,eng as English from all_words3 where length(eng)>0 and pos not like 'var.' and $nvulg order by rand() limit 5"); 
            ?>
            
            <p>
            <a href="random.php">Click for five new random words</a>
        </div>

        <?php belau_footer("/random.php"); ?>
    <div>

</body>
</html>

<?php 
include '../functions.php'; 
require_once './quiz2.php';
$title = "Top 200 Quiz Scores";
html_top($title);
belau_header($title);
?>

    <div id="content-container">

        <?php print_quiz_aside(); ?>
        <div id="content"> 
            <?php echo "<h2>$title</h2>"; ?>
            <p>
            <?php
                print_table("select * from quiz_high where type not like 'Audio' limit 200",True,False,True);
            ?>
             
        </div>
        <?php belau_footer("/misc/quiz_high.php"); ?>
    </div>

</body>
</html>

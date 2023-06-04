<?php 
include '../functions.php'; 
$title = "Palauan Baby Words";
html_top($title);
belau_header($title);
?>

    <div id="content-container">

        <div id="aside">
        </div>
        <div id="content"> 
            <?php echo "<h2>$title</h2>"; ?>
            <p>
            The Palauan language has a  
            few words reserved for babies. 
            This may be motivated by the fact that the human brain is capable of language
            before the human palate is capable of articulation.  The Palauan baby words are
            much simpler to pronounce than their adult counterparts and as such allow Palauan
            babies to communicate earlier than if these words did not exist.  Note that this
            is similar to the <a href="http://en.wikipedia.org/wiki/Baby_sign_language">baby sign language</a>
            that has been popular recently in America. 
            </p>
            <p>Here is a list of the Palauan specialized baby words and their adult equivalents:</p>
            <?php
                print_table("select * from babywords");
            ?>
             
        </div>
        <?php belau_footer("/grammar/baby.php"); ?>
    </div>

</body>
</html>

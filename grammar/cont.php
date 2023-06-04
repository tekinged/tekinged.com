<?php 
include '../functions.php'; 
$title = "Palauan Contractions";
html_top($title);
belau_header($title);
$count = get_count('all_words3',"where pos like 'cont.'");
?>

    <div id="content-container">

        <div id="aside">
          <center>
            <div class='tab'>
              <h3>
              <?php echo "Unofficial List of $count Common Naturally Occurring Palauan Contractions" ?>
              </h3>
            </div>
          </center>
        </div><!-- aside -->

        <div id="content"> 
        <?php echo "<h2>$title</h2>"; ?>
        <p>
        The latest version of the <a href='books/handbook1.php'>Lewis Grammar Handbooks</a> does not officially recognize, nor discuss, contractions.
        However, Palauans very commonly use contractions both in spoken and written Palauan.  Some people consider it unfortunate that the "official"
        grammar rules do not recognize contractions because this causes natural sounding Palauan to be in violation of the grammar rules.

        <p>
        Therefore, tekinged.com is currently working with the <a href='http://palaulanguagecommission.blogspot.com'>Palau Language Commission</a> to
        officially recognize many of these naturally occurring contractions.  The following are all of the 
        <?php echo $count ?>
        contractions currently entered into the tekinged.com
        database; please be aware however that these are not yet "officially" recognized to be grammatically correct Palauan words.

        <p class='tab'>
        <div>
        <?php
            show_words("select id,stem from all_words3 where pos like 'cont.'",False);
            #print_table("select * from verb_adjs order by rand() limit 7");
        ?>
        </div>
        </p>

        </div><!-- content -->
        <?php belau_footer("/grammar/cont.php"); ?>
    </div><!-- content-container -->

</body>
</html>

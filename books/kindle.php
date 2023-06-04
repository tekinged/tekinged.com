<?php 
include '../functions.php'; 
$title="Tekinged on your Kindle";
html_top($title);
belau_header($title);

?>
    <div id="content-container">

        <div id='aside'>
          <center>
            <img src=/uploads/pics/kindle.png width=80%>
          </center>
        </div><!-- aside-->
       
        <div id="content"> 
            <?php echo "<h2>$title</h2>" ?>
        <div class='tab'>

            <br>
            <p>
            tekinged.com is thrilled to be able to provide its Palauan dictionary as a built-in Kindle Dictionary. 
             
            Please note that this is just a dictionary and not the full tekinged.com website.  

            <p>To install it, download the Kindle Dictionary file below and then email it to your kindle and then go into settings to set it as your default
            dictionary. Note that it appears as an English dictionary which is because Kindle unfortunately does not currently support the 
            Palauan language. 

            <p>Download link:
            <ul>
            <li><a href="belau.mobi">Kindle Dictionary</a> 
            </ul>

            <p>To load Palauan language books onto your Kindle, download the ePubs from our <a href="books.php">Books page</a> and similarly email them to your Kindle email. 
                Note that you might need to convert them to .mobi format first.

            <p>
            Please provide any comments, bug reports, or other feedback in the comments section at the bottom of this page. The source code
            used to generate this dictionary can be found in our <a href="https://github.com/tekinged/tekinged.com/blob/main/scripts/mk_kindle_dict.py">GitHub repository</a>.

        </div><!-- tab -->
        </div><!-- content -->

        <?php belau_footer("kindle.php"); ?>
    <div><!-- content-container -->

</body>
</html>

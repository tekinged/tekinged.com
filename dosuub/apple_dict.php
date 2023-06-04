<?php 
include '../functions.php'; 
$title="Tekinged on your Mac";
html_top($title);
belau_header($title);

?>
    <div id="content-container">

        <div id='aside'>
          <center>
            <img src=/uploads/pics/app/apple_dictionary.png width=80%>
          </center>
        </div><!-- aside-->
       
        <div id="content"> 
            <?php echo "<h2>$title</h2>" ?>
        <div class='tab'>

            <br>
            <p>
            tekinged.com is thrilled to be able to provide its Palauan dictionary as a built-in Apple Dictionary.  This is for your Mac computers only; if
            you are looking for the dictionary for your phone or tablet, please click <a href=app.php>here</a>.  If you would like spell-checking to work
            in Microsoft office, please click <a href=/spell2.php>here</a>.
             
            Please note that this is just a dictionary and not the full tekinged.com website.  

            <p>This provides all the functionality that Apple OS X provides.  You can use the built-in dictionary and you can also select words in other
            apps and look them up.

            <p>To install it, either download the light-weight version that doesn't include pictures or the larger version with pictures.  
            Then follow the instructions on <a href=http://clasquin-johnson.co.za/michel/mac-os-x-dictionaries/>this page</a> to install it.
            You can also try the simpler instructions in the featured comment at the bottom of this page. 

            <p>Download links:
            <ul>
            <li><a href="/gd/Palauan Light.dictionary.zip">Lightweight Version without Pictures</a> (4.4 MB)
            <li><a href="/gd/Palauan.dictionary.zip">Full Version with Pictures</a> (68M)
            </ul>

            <p>
            Please provide any comments, bug reports, or other feedback in the comments section at the bottom of this page.

        </div><!-- tab -->
        </div><!-- content -->

        <?php belau_footer("apple_dict.php"); ?>
    <div><!-- content-container -->

</body>
</html>

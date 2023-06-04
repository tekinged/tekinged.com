<?php 
include '../functions.php'; 
$title="Palauan Dictionary Tablet and Phone App";
html_top($title);
belau_header($title);

function add_step($step, $instructions, $picture) {
  $pic_url="/uploads/pics/app/$picture.jpg";
  echo "<h3>Step $step</h3>
        <div class='tab'>
        <table><tr>
        <td><a href=$pic_url><img height=25% src=$pic_url></a></td>
        <td>$instructions</td>
        </tr></table>
        </div>
      ";
}

?>

    <div id="content-container">

        <div id='aside'>
          <center>
          <a href=http://itunes.com/apps/gurudic/><img src=/uploads/pics/app/11406555_10104388928454077_6888554447997086887_o.jpg width=80%><br>Gurudic App at the Apple App Store</a>
          </center>
        </div><!-- aside-->
        <div id="content"> 
            <?php echo "<h2>$title</h2>" ?>
        <div class='tab'>

            <br>
            <p>
            tekinged.com is thrilled to be able to provide its Palauan dictionary as an iOS app for both iOS and Droid devices. 
            Please note that this is just a dictionary and not the full tekinged.com website.  The iPhone version costs $0.99 but the droid version is free.

            <br>
            <br>
            [ANDROID users please click <a href=#android>here</a>.]


            <?php
              $step = 1;
              $instructions = "
                Use this <a href= http://itunes.com/apps/gurudic>direct link</a> or search for gurudic in the App Store.  Install the app which will require a $0.99 purchase*.
                <br>
                <br>
                * Disclaimer: tekinged.com, nor any of its volunteers, makes any money from this purchase.  Gurudic is a third party app, independent from tekinged.com, whose 
                developer, Kyun-Sang Song, has kindly worked with tekinged.com to import our words database into his application. 
              ";
              add_step($step++, $instructions, "10827894_10104388928319347_4332125576639481113_o");
  
              $instructions = '
                After installing and opening the app, you should see this screen; paste this <a href=/gd/palauanJun2017.zip>link</a> into the "INSTALL BY URL" box for the full 
                version with pictures and audio, or use this <a href=/gd/palauanJun2017.lite.zip>link</a> for the light version without pictures and audio.  Or you can manually type
                in either of these URLS: http://tekinged.com/gd/palauanJun2017.zip or http://tekinged.com/gd/palauanJun2017.lite.zip
                <br>The click the "Start Install" button.
                ';
              add_step($step++, $instructions, "11538115_10104388928314357_4240939360214899709_o");

              /*
              add_step($step++, "If you see this screen, just tap 'OK'", "10928876_10104388928324337_8277542605358838131_o");

              $instructions = "
                Choose one of the two Palauan - English dictionaries.  They both contain the same exact words and definitions.  The only difference is that
                the second includes pictures and audio clips for some of the words and is therefore much larger.  If you have a lot of space on your phone and a good 
                internet connection, choose the second one.  Otherwise, or if you don't care about pictures and audio, choose the first.
              ";
              add_step($step++, $instructions, "11174706_10104388928334317_6938150340606219660_o");

              add_step($step++, "Now tap 'Start Install'", "11417762_10104388928329327_1213870519365665221_o");
              */

              add_step($step++, "Wait for the 'Download & Install' to finish; the blue bar under where it says 'Please wait' shows progress.
                                 When it finishes, tap on Back in the upper left corner.", 
                        "11406615_10104389149066967_8746791692870808492_o");

              add_step($step++, "Now tap on dictionaries in the bottom left.",
                        "10296205_10104389156671727_8124187916088356266_o");

              add_step($step++, "Tap on the checkbox to select the palauan dictionary you just installed. Then tap on Search in the bottom row.",
                       "11406516_10104389164685667_8249385618996883092_o"); 

              add_step($step++, "Happy Searching!",
                       "11406555_10104388928454077_6888554447997086887_o"); 
            ?>

            <p>
            Please provide any comments, bug reports, or other feedback in the comments section at the bottom of this page.

            <a id='android'>
            <p>
            <!--
            ANDROID users: We are sorry but we do not currently have a solution conveniently packaged for you. In the future, we may have something.  In the meantime, however,
            you may be able to get this working on your own.  If you do, please let us know in the comments section below or by sending an email to info@tekinged.com or by
            contacting us through or facebook or twitter accounts.
            <br>
            To try to get this working yourself on an android device, look for applications such as 
            <a href=https://play.google.com/store/apps/details?id=com.socialnmobile.colordict&hl=en">ColorDict Dictionary</a>.  Within that app, there should be an option to
            provide a URL for a downloadable dictionary.  
            <br>
            -->
        <h1>Android instructions</h1>
            Good news!  On July 22, 2015, we were able to install this successfully on two droid apps.  Download the free 
            <a href=https://play.google.com/store/apps/details?id=com.socialnmobile.colordict&hl=en">ColorDict Dictionary</a> app.
            <br>
            Use <a href=/gd/palauanJun2017.lite.zip>this link</a> for the tekinged dictionary that 
            only includes the words and <a href=/gd/palauanJun2017.zip>this one</a> for the one that also includes pictures and audio. 
            <br>
            Note that we have only successfully tried this so far with the lite version that doesn't include pictures or audio.

        </div><!-- tab -->
        </div><!-- content -->

        <?php belau_footer("app.php"); ?>
    <div><!-- content-container -->

</body>
</html>

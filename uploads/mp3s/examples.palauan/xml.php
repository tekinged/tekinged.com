<?php
// PHP file that renders perfect Dynamic XML for mp3 Player applications
// Script written by Adam Khoury @ www.developphp.com - April 05, 2010
// View the video that is tied to this script for maximum understanding
// -------------------------------------------------------------------
header("Content-Type: text/xml"); // set the content type to xml
// Initialize the xmlOutput variable
$xmlBody = '<?xml version="1.0" encoding="ISO-8859-1"?>';
$dir = "./"; // Specify Directory where mp3 files are 
$xmlBody .= "<XML>"; // Start XMLBody output
// open specified directory using opendir() the function
$dirHandle = opendir($dir); 
// Create incremental counter variable if needed
$i = 0;
while ($file = readdir($dirHandle)) { 
      // if file is not a folder and if file name contains the string '.mp3'  
      if(!is_dir($file) && strpos($file, '.mp3')){
         $i++; // increment $i by one each pass in the loop
         $xmlBody .= '
<Song>
    <songNum>' . $i . '</songNum>
    <songURL>' . $dir . '' . $file . '</songURL>
</Song>';
      } // close the if statement
} // End while loop
closedir($dirHandle); // close the open directory
$xmlBody .= "</XML>";
echo $xmlBody; // output the gallery data as XML file for flash
?>

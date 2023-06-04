<?php 

$filename = $_GET['file'] . '.pdf';
$filepath = $_SERVER['DOCUMENT_ROOT'] . "/misc/pdfs/$filename";
  
// Header content type
header('Content-type: application/pdf');
header('Content-Disposition: inline; filename="' . $filename . '"');
header('Content-Transfer-Encoding: binary');
header('Accept-Ranges: bytes');
@readfile($filepath);

# this was the way I used to do it to have it autodownload
#header("Content-Type: application/octet-stream");
#header("Content-Disposition: attachment; filename=" . urlencode(basename($file)));
#header("Content-Type: application/octet-stream");
#header("Content-Type: application/download");
#header("Content-Description: File Transfer");            
#header("Content-Length: " . filesize($file));
#ob_clean();
#flush();
#@readfile($file);

include '../functions.php'; 
db_connect();
visitlog(": $name");

#flush(); // this doesn't really matter.
#$fp = fopen($file, "r");
#while (!feof($fp))
#{
#    echo fread($fp, 65536);
#    flush(); // this is essential for large downloads
#} 
#fclose($fp);

?>

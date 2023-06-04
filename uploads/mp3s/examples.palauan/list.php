<?php
$dir="./";
if (is_dir($dir)) {
$fd = @opendir($dir);
while (($part = @readdir($fd)) == true) {
if ($part != "." && $part != "..") {
$file_array[]=$part;
}
}
if ($fd == true) {
closedir($fd);
}
}
sort($file_array);
reset($file_array);
for($i=0;$i<count($file_array);$i++) {
$npart=$file_array[$i];
if (strstr($npart,".mp3")) {
$name=str_replace(".mp3","",$npart);
$fsize=filesize($npart)/1000;
print("<tr><td><a href=\"$npart\">$name</a>");
print("<td>$fsize</td></tr>");
}
}
?>


<?php
/* =============================================================================
File : 
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 6.
================================================================================
Desc.
	Image File을 가져와 보여준다.
============================================================================= */

$myPath = "/home/medcbt/www/html/images/testimages";
$file =  $_GET["myFile"];
//"P1000337.JPG";

$filename = $myPath . "/" . $file;


header("Content-type: image/jpeg");
header("Content-length: " . filesize($filename));
$fp = fopen($filename, "rb");
fpassthru($fp);
/*
$im = imagecreatefromjpeg($filename);
imagejpeg($im);
imagedestroy($im);
exit;
*/
?>

<?php
// Force download of image file specified in URL query string and which
// is in the same directory as the download.php script.

if(empty($_GET['img'])) {
   header("HTTP/1.0 404 Not Found");
   return;
}

session_start();

$id = session_id();

$basename = basename($_GET['img']);
$filename = __DIR__ . '/working/' . $id . "/" . $basename; 

$mime = ($mime = getimagesize($filename)) ? $mime['mime'] : $mime;
$size = filesize($filename);
$fp   = fopen($filename, "rb");
if (!($mime && $size && $fp)) {
  // Error.
  return;
}

header("Content-type: " . $mime);
header("Content-Length: " . $size);

header("Content-Disposition: attachment; filename=" . $basename);
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
fpassthru($fp);
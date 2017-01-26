<?php
// Code from: http://stackoverflow.com/a/11090338

// Force download of image file specified in URL query string and which
// is in the same directory as the download.php script.

// If the image GET variable is empty or not set, return a 404 error telling the user that the file they are reqesting has not been found.
if(empty($_GET['img'])) {
   header("HTTP/1.0 404 Not Found");
   return;
}

// Start the session.
session_start();

// Get the session id and set it to a variable for later use.
$id = session_id();

// Get the file name from a path using the basename function.
$basename = basename($_GET['img']);

// Use the name above and add it to the working directory that stores the images, also adding in the session ID. 
$filename = __DIR__ . '/working/' . $id . "/" . $basename; 

// Get some information about the image and assign the data to variables.
$mime = ($mime = getimagesize($filename)) ? $mime['mime'] : $mime;
$size = filesize($filename);
$fp   = fopen($filename, "rb");

// Check all the above information
if (!($mime && $size && $fp)) {
  // Error.
  return;
}

// Set the headers.

// Content type using the mime type of the requested image.
header("Content-type: " . $mime);
// Content length, using the file size of the requested image.
header("Content-Length: " . $size);
// Setting other headers to start a download.
header("Content-Disposition: attachment; filename=" . $basename);
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
fpassthru($fp);

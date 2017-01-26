<?php
    // Exports just the selected image.

    // Starts the PHP session.
    session_start();

    // Function which converts the hex value from the HTML form to an RGB value which is required for the image functions.
    function hex2rgb($hex) {
        // Remove the # from the start of the hex value as it's unneeded for RGB.
        $hex = str_replace("#", "", $hex);

        // If the length of the value is now equal to 3.
        if(strlen($hex) == 3) {
            // Splits the hex string into three vars using the substr function. 
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            // Splits the hex string also, but because the length of the string is longer use different start and length arugments for the substr function.
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        
        // Get the RGB values from above and place them within an array.
        $rgb = array($r, $g, $b);
        
        // Return the rgb array.
        return $rgb;
    }

    // Disable notices if the web server is set in development mode.
    ini_set('display_errors', 0);

    // Gets the posted information from the options form.
    //TODO: Escape this information posted, as it's not safe to be used and is vunable to XSS. 
    $text = $_POST['userText'];
    $image = $_POST['image'];
    $mode = $_POST['mode'];
    $pos = $_POST['pos'];
    $sizeText = $_POST['size'];
    $textColour = $_POST['textColour'];

    // Get the colour that was passed from the form colour picker and then convert the hex value to RGB.
    $colour = hex2rgb($textColour);

    // Define a new image which has been created from the jpeg image passed. 
    $im = imagecreatefromjpeg($image);

    // Allocates the colour that will used for the text on the image. 
    $text_color = imagecolorallocate($im, $colour[0], $colour[1], $colour[2]);

    // The font that will be used.
    // TODO: Change this to allow for a number of different fonts to be used.
    $font = 'Roboto.ttf';

    // Get the size of the image.
    $size = getimagesize ($image);

    // Switch based on the position option selected by the user.
    // TODO: Redo this whole switch code, the logic behind the positioning is not correct. 
    switch ($pos) {
        // Bottom right option
        case 'Bottom Right':
            $x = $size[0] - (strlen($text) * $sizeText) + 150;
            $y = $size[1] - 100;       
            break;
        // Bottom left option
        case 'Bottom Left':
            $x = 150;
            $y = $size[1] - 100;       
            break;
        // Top right option
        case 'Top Right':
            $x = $size[0] - (strlen($text) * $sizeText) + 150;
            $y = 200;      
            break;
        // Top Left option
        case 'Top Left':
            $x = (strlen($text) / $sizeText) + 150;
            $y = 200;       
            break;
        // If for whatever reason an option isn't selected use the default 
        default:
            $x = $size[0] - ($size[0] / 2) - (strlen($text) * 25);
            $y = $size[1] - ($size[1] / 2); 
            break;
    }

    /// Get the ID of the session.
    $id = session_id();

    // If the working directory for the session doesn't exist create it. 
    if (!file_exists("./working/" . $id)) {
        mkdir("./working/" . $id, 0755);
    };

    // Gets the location in which images should be saved too.
    $location = './working/' . $id . '/';

    // Generates a new image name random number.
    $imageName = $number = rand(1, 99999999);

    // Adds text to the image.
    imagettftext($im, $sizeText, 0, $x, $y, $text_color, $font, $text);

    // If the mode is set to preview echo out the image ready to be previewed to the user.
    if ($mode == "preview") {
        // Create the image and saves it to the location with the _preview suffix.
        imagejpeg($im, $location . $imageName . "_preview.jpg");
        // Destorys the created image object as it's no longer needed 
        imagedestroy($im);
        // Echo out the image path to be used by the AJAX call. 
        echo $location . $imageName . "_preview.jpg";
    } else {
        // Create the image but with the watermark suffix
        imagejpeg($im, $location . $imageName . "_watermark.jpg");
        // Destory the created image object as it's no longer needed.
        imagedestroy($im);
        // Echo out the download path which is then used by the AJAX call. 
        echo './download.php?img=' . $imageName . "_watermark.jpg";
    };
?>

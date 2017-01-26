<?PHP
    // Exports all images in a queue

    // Starts the PHP session.
    session_start();

    // Create an empty array for storing all the finished images.
    $finishedImages = array();

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
    $pos = $_POST['pos'];
    $sizeText = $_POST['size'];
    $textColour = $_POST['textColour'];

    // Get the colour that was passed from the form colour picker and then convert the hex value to RGB.
    $colour = hex2rgb($textColour);

    // Get the array of images currently in the queue from the session variable.
    $file_ary = $_SESSION['images'];

    // Get the ID of the session.
    $id = session_id();
    
    // If the working directory for the session doesn't exist create it. 
    if (!file_exists("./working/" . $id . "/" . time())) {
        mkdir("./working/" . $id . "/" . time(), 0755);
    };

     // Sets the location in which images should be saved too.
    $location = "./working/" . $id . "/" . time() . "/";

    // For each loop that cunts for each file within the session array.
    foreach ($file_ary as $file) {
        // Define a new image which has been created from the jpeg image passed. 
        $im = imagecreatefromjpeg($file);

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

        // Generates a new image name random number.
        $imageName = $number = rand(1, 99999999);

        // Adds text to the image.
        imagettftext($im, $sizeText, 0, $x, $y, $text_color, $font, $text);

        // Create the image and saves it to the location with the watermark suffix.
        imagejpeg($im, $location . $imageName . "_watermark.jpg");

        // Destory the created image object as it's no longer needed.
        imagedestroy($im);

        // Adds to the end of the finishedimages array the processed image data.
        array_push($finishedImages, $imageName . "_watermark.jpg");
    };

    // Creates a new object of ZipArchive
    $zip = new ZipArchive;
    // Creates a new ZIP. 
    if ($zip->open($location . 'download.zip', ZipArchive::CREATE|ZipArchive::OVERWRITE) === TRUE) {
        // For each finished image
        foreach ($finishedImages as $image) {
            // Add file to the zip
            $zip->addFile($location . "/" .  $image, $image);
        }
        
        // Close the ZIP and save.
        $zip->close();

        // Echo out the path of the new ZIP for the AJAX call to use.
        echo $location . 'download.zip';
    } else {
        // If the ZIP cannot be created, echo out ERROR for AJAX to process.
        echo 'ERROR';
    };
?>

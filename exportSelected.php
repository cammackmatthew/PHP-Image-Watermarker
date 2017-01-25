<?php
    session_start();

    function hex2rgb($hex) {
        $hex = str_replace("#", "", $hex);

        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        $rgb = array($r, $g, $b);
        return $rgb;
    }

    ini_set('display_errors', 0);

    $text = $_POST['userText'];
    $image = $_POST['image'];
    $mode = $_POST['mode'];
    $pos = $_POST['pos'];
    $sizeText = $_POST['size'];
    $textColour = $_POST['textColour'];

    $colour = hex2rgb($textColour);

    $im = imagecreatefromjpeg($image);

    $text_color = imagecolorallocate($im, $colour[0], $colour[1], $colour[2]);

    $font = 'Roboto.ttf';

    $size = getimagesize ($image);

    switch ($pos) {
        case 'Bottom Right':
            $x = $size[0] - (strlen($text) * $sizeText) + 150;
            $y = $size[1] - 100;       
            break;

        case 'Bottom Left':
            $x = 150;
            $y = $size[1] - 100;       
            break;

        case 'Top Right':
            $x = $size[0] - (strlen($text) * $sizeText) + 150;
            $y = 200;      
            break;

        case 'Top Left':
            $x = (strlen($text) / $sizeText) + 150;
            $y = 200;       
            break;
        
        default:
            $x = $size[0] - ($size[0] / 2) - (strlen($text) * 25);
            $y = $size[1] - ($size[1] / 2); 
            break;
    }

    $id = session_id();
    if (!file_exists("./working/" . $id)) {
        mkdir("./working/" . $id, 0755);
    };
    $location = './working/' . $id . '/';

    $imageName = $number = rand(1, 99999999);

    imagettftext($im, $sizeText, 0, $x, $y, $text_color, $font, $text);

    if ($mode == "preview") {
        imagejpeg($im, $location . $imageName . "_preview.jpg");

        imagedestroy($im);

        echo $location . $imageName . "_preview.jpg";
    } else {
        imagejpeg($im, $location . $imageName . "_whitemark.jpg");

        imagedestroy($im);

        echo './download.php?img=' . $imageName . "_whitemark.jpg";
    };
?>
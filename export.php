<?PHP
    session_start();

    $finishedImages = array();

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
        
        return $rgb; // returns an array with the rgb values
    }

    ini_set('display_errors', 0);

    $text = $_POST['userText'];
    $image = $_POST['image'];
    $pos = $_POST['pos'];
    $sizeText = $_POST['size'];
    $textColour = $_POST['textColour'];

    $colour = hex2rgb($textColour);

    $file_ary = $_SESSION['images'];

    $id = session_id();
    if (!file_exists("./working/" . $id . "/" . time())) {
        mkdir("./working/" . $id . "/" . time(), 0755);
    };
    $location = "./working/" . $id . "/" . time() . "/";

    foreach ($file_ary as $file) {

        $im = imagecreatefromjpeg($file);

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

        $imageName = $number = rand(1, 99999999);

        imagettftext($im, $sizeText, 0, $x, $y, $text_color, $font, $text);

        imagejpeg($im, $location . $imageName . "_whitemark.jpg");

        imagedestroy($im);

        array_push($finishedImages, $imageName . "_whitemark.jpg");
    };

    $zip = new ZipArchive;
    if ($zip->open($location . 'download.zip', ZipArchive::CREATE|ZipArchive::OVERWRITE) === TRUE) {
        foreach ($finishedImages as $image) {
            $zip->addFile($location . "/" .  $image, $image);
        }
        $zip->close();

        echo $location . 'download.zip';
    } else {
        echo 'ERROR';
    };
?>
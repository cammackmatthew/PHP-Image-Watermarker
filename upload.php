<?php
    session_start();

    ini_set("post_max_size", 200000);
    ini_set("upload_max_filesize", 10000);
    ini_set('memory_limit', '256M');

    $array = $_SESSION['images'];

    function reArrayFiles(&$file_post) {

        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }

        return $file_ary;
    };

    $file_ary = reArrayFiles($_FILES['files']);

    foreach ($file_ary as $file) {
        $type = $file["type"];
        $mname = $file["name"];
        $tmp_name = $file["tmp_name"];
        $pi = pathinfo($mname);
        $txt = $pi['filename'];
        $ext = $pi['extension'];
        $number = rand(1, 9999);
        $newname = $txt . "_" . $number . "." . $ext;

        $id = session_id();
        if (!file_exists("./working/" . $id)) {
            mkdir("./working/" . $id, 0755);
        };
        $location = './working/' . $id . '/';

        if (isset($mname)) {
            move_uploaded_file($tmp_name, $location . $newname);
        };

        array_push($array, $location . $newname);
    };

    $_SESSION['images'] = $array;

    header('Location: ./index.php');
?>
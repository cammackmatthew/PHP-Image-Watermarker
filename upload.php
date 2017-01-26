<?php
    // Starts the PHP session.
    session_start();

    // Sets from PHP configuration options for the uploading to stop larger images from not uploading.
    // Max size of data that can be POSTed from a form.
    ini_set("post_max_size", 200000);
    // Max uploaded file size.
    ini_set("upload_max_filesize", 10000);
    // The maximum memory that can be used by PHP. 
    ini_set('memory_limit', '256M');

    // Get the SESSION images array and then assign it to the $array variable. 
    $array = $_SESSION['images'];

    // Function that takes an argument containing the information on the POST file upload.  
    function reArrayFiles(&$file_post) {
        // Defines a new array for storing into.
        $file_ary = array();
        // Gets the number of files that have been posted.
        $file_count = count($file_post['name']);
        // Gets the array element keys for each of the posted files. 
        $file_keys = array_keys($file_post);

        // For loop, which starts at i = 0 and loops till I is greater than the number of posted files. Adds 1 to i every loop.
        for ($i=0; $i<$file_count; $i++) {
            // For each of the array keys, select one and assign it to the $key variable. 
            foreach ($file_keys as $key) {
                // Sets the file data to the new array with the correct formatting. 
                /*
                [
                    [0, "key"],
                    [1, "key"]
                ]
                */
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }

        // Returns the new correctly formatted image array.
        return $file_ary;
    };

    // Calls the above reArrayFiles function, 
    $file_ary = reArrayFiles($_FILES['files']);

    // For each file within the file array that was created in the above function call. 
    foreach ($file_ary as $file) {
        // Get some basic information about the file to be used.
        $type = $file["type"];
        $mname = $file["name"];
        $tmp_name = $file["tmp_name"];
        $pi = pathinfo($mname);
        $txt = $pi['filename'];
        $ext = $pi['extension'];
        // Generate a new name for the image to avoid conflicts.
        $number = rand(1, 9999);
        $newname = $txt . "_" . $number . "." . $ext;

        // Get's the session ID
        $id = session_id();
        
        // If the working folder (for image uploads) for this session hasn't been created, create it. 
        if (!file_exists("./working/" . $id)) {
            mkdir("./working/" . $id, 0755);
        };
        
        // The location to be used later within the script.
        $location = './working/' . $id . '/';

        // Check if the file name is set incase of any issues during posting, if so move the uploaded file to the new location with the new name.
        if (isset($mname)) {
            move_uploaded_file($tmp_name, $location . $newname);
        };

        // Add to the end of the array with the image location and file name.
        array_push($array, $location . $newname);
    };

    // Set the session array of images to include the newly added ones. 
    $_SESSION['images'] = $array;

    // Return the user back to the index.
    header('Location: ./index.php');
?>

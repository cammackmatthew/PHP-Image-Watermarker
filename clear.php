<?php
    // Starts the PHP session.
    session_start();
    
    // Gets the session ID.
    $id = session_id();
    
    // Function that deletes files and subfolders of a passed target.
    function DelDir($target) {
        // If the target is a directory (folder)
        if(is_dir($target)) {
            // Get all the files within the target folder.
            $files = glob( $target . '*', GLOB_MARK );
            
            // Foreach file within the files array.
            foreach( $files as $file )
            {
                // Call the function again for each file, instead it's a directory also.
                DelDir( $file );      
            }
            
            // If the folder exists delete it.
            if (file_exists($target)) {
                rmdir( $target );
            };
            
        // Else if the target is a file
        } elseif(is_file($target)) {
            // Unlink / delete the file.
            unlink( $target );  
        }
    }

    // Call the above function on the working file for this session. 
    DelDir("./working/" . $id);

    // If it still exists try remove it with rmdir.
    if (file_exists("./working/" . $id)) {
        rmdir("./working/" . $id);
    };
    
    // Clear and destory the session. 
    session_unset();
    session_destroy();
    session_write_close();
    setcookie(session_name(),'',0,'/');
    session_regenerate_id(true);

    // Redirect user back to the index.
    header('Location: ./index.php');
?>

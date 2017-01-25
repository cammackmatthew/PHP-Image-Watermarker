<?php
    session_start();

    $id = session_id();

    function DelDir($target) {
        if(is_dir($target)) {
            $files = glob( $target . '*', GLOB_MARK );

            foreach( $files as $file )
            {
                DelDir( $file );      
            }
            if (file_exists($target)) {
                rmdir( $target );
            };
        } elseif(is_file($target)) {
            unlink( $target );  
        }
    }

    DelDir("./working/" . $id);

    if (file_exists("./working/" . $id)) {
        rmdir("./working/" . $id);
    };

    session_unset();
    session_destroy();
    session_write_close();
    setcookie(session_name(),'',0,'/');
    session_regenerate_id(true);

    header('Location: ./index.php');
?>
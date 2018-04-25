<?php session_start();
    $filename = $_GET['file'];
    $file_extension = strtolower(substr(strrchr($filename, "."), 1));

    switch ($file_extension) {
        case "gif":
            $ctype = "image/gif";
            break;
        case "png":
            $ctype = "image/png";
            break;
        case "jpeg":
        case "jpg":
            $ctype = "image/jpeg";
            break;
        default:
    }

    header('Content-type: ' . $ctype);

    ob_clean();
    flush();

    readfile($filename);
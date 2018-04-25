<?php include 'connection.php';

if (!empty($_FILES)) {
    $ds = "/";
    $storeFolder = 'files';
    $tempFile = $_FILES['file']['tmp_name'];

    $targetPath = $storeFolder . $ds;

    $targetFile =  $targetPath . $_FILES['file']['name'];

    move_uploaded_file($tempFile, $targetFile);

}



?>
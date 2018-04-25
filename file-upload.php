<?php session_start(); 
	$userid = $_SESSION['userid'];
	include 'scripts/connection.php';

if (!empty($_FILES)) {
	if($_GET['path'] == '/' OR $_GET['path'] == '') {
		$path = substr("files/" . $_SESSION['userid'] . "/" . $_GET['path'], 0, -1);
		$ds = "/";
		$tempFile = $_FILES['file']['tmp_name'];
		$targetPath = $path . $ds;
	} else {
		$path = "files/" . $_SESSION['userid'] . "/" . $_GET['path'];
		$ds = "/";
		$tempFile = $_FILES['file']['tmp_name'];
		$targetPath = $path . $ds;
	}
    

	$filename = $_FILES['file']['name'];
	$timestamp = time();
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
	$hashname = sha1($timestamp . $filename) . "." . $ext;
	$mimetype = $_FILES['file']['type'];
	$filesize = $_FILES['file']['size'];
	
	$insert_data = "INSERT INTO `files` (`name`, `hashname`, `created`, `mimetype`, `filesize`, `user`, `folderpath`) VALUES ('$filename', '$hashname', '$timestamp', '$mimetype', '$filesize', '$userid', '$path')";
	$insert_data_query = mysqli_query($db, $insert_data);
	
	$targetFile = $targetPath . $hashname;
	
    move_uploaded_file($tempFile, $targetFile);
    
    header("Location: /");
}
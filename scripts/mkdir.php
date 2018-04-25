<?php 
	session_start(); 
	$userid = $_SESSION['userid'];
	include 'connection.php';
	$foldername = $_POST['name'];
	
	if($_GET['path'] == '/' OR $_GET['path'] == '') {
		$path = substr("files/" . $_SESSION['userid'] . "/" . $_GET['path'], 0, -1);
	    $ds = "/";
	    $targetPath = $path . $ds;
	} else {
		$path = "files/" . $_SESSION['userid'] . "/" . $_GET['path'];
		$ds          = "/";
		$targetPath = $path . $ds;
	}
    
	$timestamp = time();
	$hashname = sha1($timestamp . $foldername);

	$select_folder_name = "SELECT * FROM `files` WHERE `name` = '$foldername' AND `folderpath` = '$path' AND `user` = '$userid'";
	$select_folder_name_query = mysqli_query($db, $select_folder_name);
	if(mysqli_num_rows($select_folder_name_query) == 1) {
		var_dump(http_response_code(404));
		exit();
	} else {
	$insert_data = "INSERT INTO `files` (`name`, `hashname`, `created`, `mimetype`, `filesize`, `user`, `folderpath`) VALUES ('$foldername', '$foldername', '$timestamp', 'folder', '', '$userid', '$path')";
	$insert_data_query = mysqli_query($db, $insert_data);
	
	mkdir("../" . $targetPath . $foldername);
	}
<?php 
	session_start(); 
	$userid = $_SESSION['userid'];
	include 'connection.php';
	$id = $_GET['id'];
	
	
    
	$timestamp = time();
	$hashname = sha1($timestamp . "|" . $id . "|" . $userid);

	$select_file = "SELECT * FROM `files` WHERE `id` = '$id' AND `user` = '$userid'";
	$select_file_query = mysqli_query($db, $select_file);
	if(mysqli_num_rows($select_file_query) == 1) {
		$share_this_file = "UPDATE `files` SET `sharehash` = '$hashname' WHERE `id` = '$id' AND `user` = '$userid'";
		$share_this_file_query = mysqli_query($db, $share_this_file);
		
		$sharelink = $hashname;
		echo 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/share.php?file='. $sharelink;
	} else {
		var_dump(http_response_code(404));
		exit();
	}
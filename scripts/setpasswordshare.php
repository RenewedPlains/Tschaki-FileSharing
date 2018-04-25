<?php 
	session_start(); 
	$userid = $_SESSION['userid'];
	include 'connection.php';
	$id = $_GET['id'];
	$sharepassword = $_POST['password'];

	$hashedpass = SHA1($sharepassword);
	$select_file = "SELECT * FROM `files` WHERE `id` = '$id' AND `user` = '$userid'";
	$select_file_query = mysqli_query($db, $select_file);
	if(mysqli_num_rows($select_file_query) == 1 AND $sharepassword != '') {
		$share_this_file = "UPDATE `files` SET `sharepass` = '$hashedpass' WHERE `id` = '$id' AND `user` = '$userid'";
		$share_this_file_query = mysqli_query($db, $share_this_file);
	} else {
		var_dump(http_response_code(404));
		exit();
	}
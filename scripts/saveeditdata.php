<?php 
	session_start(); 
	$userid = $_SESSION['userid'];
	include 'connection.php';
	$id = $_GET['id'];
	$name = addslashes($_POST['name']);
	$keywords = addslashes($_POST['keywords']);
	$desc = addslashes($_POST['desc']);

	$select_data = "SELECT * FROM `files` WHERE `id` = '$id' AND `user` = '$userid'";
	$select_data_query = mysqli_query($db, $select_data);

	if(mysqli_num_rows($select_data_query) == 1) { 
		$update_data = "UPDATE `files` SET `name` = '$name', `keywords` = '$keywords', `content` = '$desc' WHERE `id` = '$id' AND `user` = '$userid'";
		$update_data_query = mysqli_query($db, $update_data);
	} else {
		var_dump(http_response_code(404));
		exit();
	}
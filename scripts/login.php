<?php include 'connection.php';
	
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	$passwordhash = sha1($password);
	
	$select_user = "SELECT * FROM `users` WHERE `email` = '$username' AND `password` = '$passwordhash' AND `activate` = '' OR `email` = '$username' AND `password` = '$passwordhash' AND `activate` != '' AND `lastlogin` != ''";
	$select_user_query = mysqli_query($db, $select_user);
	
	if(mysqli_num_rows($select_user_query) == 1) {
		session_start();
		$pull_user = mysqli_fetch_array($select_user_query);
		$userid = $pull_user['id'];
		$_SESSION['userid'] = $pull_user['id'];
		
		$timestamp = time();
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		$update_user = "UPDATE `users` SET `lastlogin` = '$timestamp', `lastip` = '$ipaddr' WHERE `id` = '$userid'";
		$update_user_query = mysqli_query($db, $update_user);
	} else {
		session_start();
		$_SESSION['email_login'] = $username;
		$_SESSION['login'] = 'failed';
	}
	header("Location: /");
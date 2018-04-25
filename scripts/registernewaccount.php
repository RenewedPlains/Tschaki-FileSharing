<?php
include 'connection.php';
	$fullname = $_POST['fullname'];
	$email = $_POST['email'];
	$epassword = $_POST['epassword'];
	$repassword = $_POST['repassword'];
	if($epassword == $repassword) {
		$selectmail = "SELECT * FROM `users` WHERE `email` = '$email'";
		$selectmail_query = mysqli_query($db, $selectmail);
		$checkmail = mysqli_num_rows($selectmail_query);
		if($checkmail == 0) {
			echo 'Thank you for your signup. You will receive a email in a few moments.';
			$select_data = "SELECT * FROM `settings` WHERE `id` = '3'";
			$select_data_query = mysqli_query($db, $select_data);
			$pullsize = mysqli_fetch_array($select_data_query);
			$spacesize = $pullsize['value'];
			$timestamp = time();
			$activehash = sha1($timestamp);
			$hashedpassword = sha1($epassword);
			$lastip = $_SERVER['REMOTE_ADDR'];
			$insertnewuser = "INSERT INTO `users` (`email`, `name`, `perm`, `created`, `updated`, `password`, `lastip`, `hostspace`, `activate`) VALUES ('$email', '$fullname', '3', '$timestamp', '$timestamp', '$hashedpassword', '$lastip', '$spacesize', '$activehash')";
			$insertnewuser_query = mysqli_query($db, $insertnewuser);
			$selectmail = "SELECT * FROM `users` WHERE `email` = '$email'";
			$selectmail_query = mysqli_query($db, $selectmail);
			$selectid = mysqli_fetch_array($selectmail_query);
			include 'registermail.php';
			$select_sender = "SELECT * FROM `settings` WHERE `id` = '4'";
			$select_sender_query = mysqli_query($db, $select_sender);
			$pullsender = mysqli_fetch_array($select_sender_query);
			$send = $pullsender['value'];
			$select_uri = "SELECT * FROM `settings` WHERE `id` = '5'";
			$select_uri_query = mysqli_query($db, $select_uri);
			$pulluri = mysqli_fetch_array($select_uri_query);
			$pageuri = $pulluri['value'];
			$select_name = "SELECT * FROM `settings` WHERE `id` = '6'";
			$select_name_query = mysqli_query($db, $select_name);
			$pullname = mysqli_fetch_array($select_name_query);
			$pagename = $pullname['value'];
			registermail($email, $send, $pagename, $pageuri, $fullname, $activehash);
			mkdir('../files/' . $selectid['id']);
		} else {
			echo 'This email is already in our database.';
			exit();
		}
	} else {
		echo 'Please repeat your password correctly.';
		exit();
	}
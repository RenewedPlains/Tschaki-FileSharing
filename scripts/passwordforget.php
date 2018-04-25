<?php
include 'connection.php';

$forgetmail = $_POST['name'];
$selectuser = "SELECT * FROM `users` WHERE `email` = '$forgetmail'";
$selectuser_query = mysqli_query($db, $selectuser);
if(mysqli_num_rows($selectuser_query) == 1) {
	include 'forgetmail.php';
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

	$selectmail = "SELECT * FROM `users` WHERE `email` = '$email'";
	$selectmail_query = mysqli_query($db, $selectmail);
	$selectname = mysqli_fetch_array($selectmail_query);
	$fullname = $selectname['name'];
	$timestamp = time();
	$activehash = sha1($timestamp);
	$updateuser = "UPDATE `users` SET `updated` = '$timestamp', `activate` = '$activehash' WHERE `email` = '$forgetmail'";
	$updateuser_query = mysqli_query($db, $updateuser);
	forgetmail($forgetmail, $send, $pagename, $pageuri, $fullname, $activehash);

	echo 'Your password reset instructions are sent to your mail.';
}
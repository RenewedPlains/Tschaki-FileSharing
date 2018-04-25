<?php session_start();
	$searchtag = $_GET['searchtag'];
	$userid = $_SESSION['userid'];
	$timestamp = time();
	if($searchtag == '') {} else {
		include 'connection.php';
		$insertsearchstring       = "INSERT INTO `searchtags` (`tag`, `user`, `created`) VALUES ('$searchtag', '$userid', '$timestamp')";
		$insertsearchstring_query = mysqli_query( $db, $insertsearchstring );
	}
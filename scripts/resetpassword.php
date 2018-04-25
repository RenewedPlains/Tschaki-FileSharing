<?php if($_POST['hash']) {
	$hash = $_POST['hash'];
	$password = $_POST['epasswordforget'];
	$repassword = $_POST['repasswordforget'];

	if($password != $repassword) {
		echo 'Please insert the same password in both fields.<br /><br />';
	} else {
		include 'connection.php';
		$passwordhash = sha1($password);
		echo 'Your password is now resetet.';
		$timestamp = time();
		$updateuser = "UPDATE `users` SET `activate` = '', `updated` = '$timestamp', `password` = '$passwordhash' WHERE `activate` = '$hash'";
		$updateuser_query = mysqli_query($db, $updateuser);
	}
} else { ?>
	<label class="left" for="epasswordforget">Password</label>
	<input type="password" id="epasswordforget" class="popupinput" name="password" value="" /><br /><br />
	<label class="left" for="repasswordforget">Repeat password</label>
	<input type="password" id="repasswordforget" class="popupinput" name="repassword" value="" /><br /><br />
<?php } ?>
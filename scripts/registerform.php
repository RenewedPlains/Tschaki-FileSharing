<?php
session_start();
$userid = $_SESSION['userid'];
include 'connection.php';
$id = $_GET['id'];

$select_data = "SELECT * FROM `settings` WHERE `id` = '2' AND `value` = 'true'";
$select_data_query = mysqli_query($db, $select_data);

if(mysqli_num_rows($select_data_query) == 1) {
	$output = mysqli_fetch_array($select_data_query);
	?>

	<label class="left" for="fullname">Full name</label>
	<input type="text" class="popupinput" id="fullname" name="fullname" value="" /><br /><br />
	<label class="left" for="epassword">Password</label>
	<input type="password" id="epassword" class="popupinput" name="password" value="" /><br /><br />
	<label class="left" for="repassword">Repeat password</label>
	<input type="password" id="repassword" class="popupinput" name="repassword" value="" /><br /><br />
	<label class="left" for="email">E-Mailaddress</label>
	<input type="email" id="email" class="popupinput" name="email" value="" /><br /><br />

<?php } else {
	http_response_code(404);
	exit();
}
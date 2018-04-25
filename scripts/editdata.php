<?php 
	session_start(); 
	$userid = $_SESSION['userid'];
	include 'connection.php';
	$id = $_GET['id'];
	
	$select_data = "SELECT * FROM `files` WHERE `id` = '$id' AND `user` = '$userid'";
	$select_data_query = mysqli_query($db, $select_data);
	
	if(mysqli_num_rows($select_data_query) == 1) { 
		$output = mysqli_fetch_array($select_data_query);
	?>
	
	<label class="left" for="title">Title</label>
	<input type="text" class="popupinput" id="title" name="title" value="<?php echo $output['name']; ?>" /><br /><br />
	<label class="left" for="keywords">Keywords</label>
	<input type="text" id="keywords" class="popupinput" name="keywords" value="<?php echo $output['keywords']; ?>" /><br /><br />
	<label class="left" for="desc">Description</label>
	<textarea id="desc" class="popuparea" name="desc"><?php echo $output['content']; ?></textarea>
	<?php } else {
		var_dump(http_response_code(404));
		exit();
	}
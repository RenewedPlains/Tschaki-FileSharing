<?php 
	session_start(); 
	$userid = $_SESSION['userid'];
	include 'connection.php';
	$id = $_GET['id'];
	
	$select_data = "SELECT * FROM `files` WHERE `id` = '$id' AND `user` = '$userid'";
	$select_data_query = mysqli_query($db, $select_data);
	
	if(mysqli_num_rows($select_data_query) == 1) { 
		$dataout = mysqli_fetch_array($select_data_query);
	?>
	<label for="title">Title</label><br />
	<?php echo $dataout['name']; ?>
	<br /><br />
	<label for="content">Content</label><br />
	<?php if($dataout['content'] == '') { echo '-'; } else { echo nl2br($dataout['content']); } ?>
	<br /><br />
	<label for="keywords">Keywords</label><br />
	<?php if($dataout['keywords'] == '') { echo '-'; } else { echo $dataout['keywords']; } ?>
	<br /><br />
	<label for="mimetype">Mimetype</label><br />
	<?php echo $dataout['mimetype']; ?>
	<br /><br />
	<label for="donwloads">Ext. Downloads</label><br />
	<?php if($dataout['downloadcounter'] == '') { echo '0'; } else { echo $dataout['downloadcounter']; } ?>
	<?php } else {
		var_dump(http_response_code(404));
		exit();
	}
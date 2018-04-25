<?php session_start();
	$userid = $_SESSION['userid'];
	include 'connection.php';
	
	
	$values = explode(',', $_POST['value']);
	foreach ($values as $value) {
		$select_file_value = "SELECT * FROM `files` WHERE `id` = '$value' AND `user` = '$userid'";
		$select_file_value_query = mysqli_query($db, $select_file_value);
		$fetchfolderpath = mysqli_fetch_array($select_file_value_query);
		$foldername = "/".$fetchfolderpath['name'];
		$select_file_valuer = "SELECT * FROM `files` WHERE `id` = '$value' AND `user` = '$userid' OR `folderpath` LIKE '$fetchfolderpath[folderpath]$foldername%' AND `user` = '$userid'";
		$select_file_valuer_query = mysqli_query($db, $select_file_valuer);
		while($value_out = mysqli_fetch_assoc($select_file_valuer_query)) {
			$oldpath = "../" . $value_out['folderpath'] . "/" . $value_out['hashname'];
			$newpath = "../" . $_POST['newpath'] . "/" . $value_out['hashname'];
			rename($oldpath, $newpath);
			$fileid = $value_out['id'];
			if($value == $value_out['id']) {
				$update_oldpath = "UPDATE `files` SET `folderpath` = '$_POST[newpath]' WHERE `id` = '$fileid' AND `user` = '$userid'";
			} else {
				$update_oldpath = "UPDATE `files` SET `folderpath` = '$_POST[newpath]/$fetchfolderpath[hashname]' WHERE `id` = '$fileid' AND `user` = '$userid'";
			}
			$update_oldpath_query = mysqli_query($db, $update_oldpath);
		}
	}
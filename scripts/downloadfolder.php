<?php 
	include 'connection.php';
	$userid = $_SESSION['userid'];
	$id = $_GET['id'];
	$sharehash = $_GET['sharehash'];
	$select_this_folder = "SELECT * FROM `files` WHERE `id` = '$id' AND `sharehash` = '$sharehash'";
	$select_this_folder_query = mysqli_query($db, $select_this_folder);
	$output_folder = mysqli_fetch_array($select_this_folder_query);
	$folder = "../" . $output_folder['folderpath'] . "/" . $output_folder['name'];
	$folderpath = $output_folder['folderpath'] . "/" . $output_folder['name'];
	$uploadname = $output_folder['name'];
	$mimetype = $output_folder['mimetype'];
	$ownid = $output_folder['user'];
	$downloadcounter = $output_folder['downloadcounter'];
	$downloadcounter++;
	$update_counter = "UPDATE `files` SET `downloadcounter` = '$downloadcounter' WHERE `sharehash` = '$sharehash'";
	$update_counter_query = mysqli_query($db, $update_counter);
	
	$rootPath = $folder;
	$timestamp = time();
	$zipfile = $timestamp . '_' . $output_folder['name'] . '.zip';

	$zip = new ZipArchive();
	$zip->open($zipfile, ZipArchive::CREATE);

	// pull all files from db
	$select_fill_files = "SELECT * FROM `files` WHERE `folderpath` LIKE '$folderpath%' AND `user` = '$ownid'";
	$select_fill_files_query = mysqli_query($db, $select_fill_files);
	
	while($fill_files = mysqli_fetch_assoc($select_fill_files_query)) {
	
	    if ($fill_files['mimetype'] != 'folder') {
	        $absolute = "../" . $fill_files['folderpath'] . "/" . $fill_files['hashname'];
	        $relativePath = $fill_files['name'];
	        $zip->addFile($absolute, $relativePath);
	    }
	}
	$zip->close();
	echo $zipfile;
	exit();
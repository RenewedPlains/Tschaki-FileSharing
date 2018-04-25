<?php include 'connection.php';
	$fileid = $_GET['fileid'];
	$sharehash = $_GET['sharehash'];
	
	$select_this_file = "SELECT * FROM `files` WHERE `id` = '$fileid' AND `sharehash` = '$sharehash'";
	$select_this_file_query = mysqli_query($db, $select_this_file);
	if(mysqli_num_rows($select_this_file_query) == 1) {
	$out_file = mysqli_fetch_array($select_this_file_query);
	$downloadcounter = $outfile['downloadcounter'];
	$downloadcounter++;
	$update_counter = "UPDATE `files` SET `downloadcounter` = '$downloadcounter' WHERE `sharehash` = '$sharehash'";
	$update_counter_query = mysqli_query($db, $update_counter);
	$folderpath = "../" . $out_file['folderpath'] . "/" . $out_file['hashname'];
		header('Content-Description: File Transfer');
		header('Content-Type: '.$out_file['mimetype']);
		header('Content-Disposition: attachment; filename="'.basename($out_file['name']).'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . $out_file['filesize']);
		ob_clean();
		flush();
		readfile($folderpath);
	} else {
		var_dump(http_response_code(404));
		exit();
	}
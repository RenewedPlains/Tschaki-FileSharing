<?php session_start();
	include 'scripts/connection.php';
	$userid = $_SESSION['userid'];
	$filename = $_GET['file'];
	$select_this_file = "SELECT * FROM `files` WHERE `hashname` = '$filename' AND `user` = '$userid'";
	$select_this_file_query = mysqli_query($db, $select_this_file);
	$output_file = mysqli_fetch_array($select_this_file_query);
	$file = $output_file['folderpath'] . "/" . $_GET['file'];
	$uploadname = $output_file['name'];
	$mimetype = $output_file['mimetype'];
if(is_dir($file)) {
	echo 'Datei ist ein Folder :(';
} else {

	if (file_exists($file)) {
		header('Content-Description: File Transfer');
		header('Content-Type: ' . $mimetype);
		header('Content-Disposition: attachment; filename="'.basename($uploadname).'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		ob_clean();
		flush();
		readfile($file);
	}
}
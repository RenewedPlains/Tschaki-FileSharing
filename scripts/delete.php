<?php
session_start();
$userid = $_SESSION['userid'];
include 'connection.php';
$id = $_GET['id'];



$timestamp = time();
$hashname = sha1($timestamp . $foldername);

$select_folder = "SELECT * FROM `files` WHERE `id` = '$id' AND `user` = '$userid'";
$select_folder_query = mysqli_query($db, $select_folder);
if(mysqli_num_rows($select_folder_query) == 1) {
	$fetch_data = mysqli_fetch_array( $select_folder_query );
	$thefolder  = "../" . $fetch_data['folderpath'] . "/" . $fetch_data['name'];
	$foldername = $fetch_data['name'];
	$mimetype   = $fetch_data['mimetype'];
	if ( $mimetype == 'folder' ) {
		$select_file_valuer       = "SELECT * FROM `files` WHERE `id` = '$id' AND `user` = '$userid' OR `folderpath` LIKE '$fetch_data[folderpath]$foldername%' AND `user` = '$userid'";
		$select_file_valuer_query = mysqli_query( $db, $select_file_valuer );
		while ( $value_out = mysqli_fetch_assoc( $select_file_valuer_query ) ) {
			function rrmdir( $dir ) {
				if ( is_dir( $dir ) ) {
					$objects = scandir( $dir );
					foreach ( $objects as $object ) {
						if ( $object != "." && $object != ".." ) {
							if ( filetype( $dir . "/" . $object ) == "dir" ) {
								rrmdir( $dir . "/" . $object );
							} else {
								unlink( $dir . "/" . $object );
							}
						}
					}
					reset( $objects );
					rmdir( $dir );
				}
			}
			$foldsub                  = $fetch_data['folderpath'] . "/" . $fetch_data['name'];
			$delete_this_folder       = "DELETE FROM `files` WHERE `folderpath` LIKE '$foldsub%'";
			$delete_this_folder_query = mysqli_query( $db, $delete_this_folder );
			rrmdir( $thefolder );
			$delete_this_folder       = "DELETE FROM `files` WHERE `id` = '$id'";
			$delete_this_folder_query = mysqli_query( $db, $delete_this_folder );
		}

} else {
	$select_file = "SELECT * FROM `files` WHERE `id` = '$id' AND `user` = '$userid'";
	$select_file_query = mysqli_query($db, $select_file);
	if(mysqli_num_rows($select_file_query) == 1) {
		$delete_this_file = "DELETE FROM `files` WHERE `id` = '$id'";
		$delete_this_file_query = mysqli_query($db, $delete_this_file);
		$fetch_data = mysqli_fetch_array($select_file_query);
		$thefile = "../" . $fetch_data['folderpath'] . "/" . $fetch_data['hashname'];
		unlink($thefile);
	} else {
		var_dump(http_response_code(404));
		exit();
	}
}} else {
	var_dump( http_response_code( 404 ) );
	exit();
}
<?php include 'connection.php';
	function username() {
		include 'connection.php';
		$userid = $_SESSION['userid'];

		$selectme = "SELECT * FROM `users` WHERE `id` = '$userid'";
		$selectme_query = mysqli_query($db, $selectme);
		$fetchuser = mysqli_fetch_array($selectme_query);
		echo $fetchuser['name'];
	}

	function is_image($path) {
		$a = getimagesize($path);
		$image_type = $a[2];
		
		if(in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP))) {
			return true;
		}
		return false;
	}
	
	function format_bytes($bytes, $precision = 2) { 
		$units = array('B', 'KB', 'MB', 'GB', 'TB'); 
		$bytes = max($bytes, 0); 
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
		$pow = min($pow, count($units) - 1); 
	
		 // Uncomment one of the following alternatives
		 	$bytes /= pow(1024, $pow);
		  //$bytes /= (1 << (10 * $pow)); 
	
		 return round($bytes, $precision) . ' ' . $units[$pow]; 
	}

	function usedspace() {
		include 'connection.php';
		$userid = $_SESSION['userid'];

		$select_data = "SELECT * FROM `files` WHERE `user` = '$userid' AND `filesize` != ''";
		$select_data_query = mysqli_query($db, $select_data);

		$filesizes = [];
		while($files = mysqli_fetch_assoc($select_data_query)) {
			array_push($filesizes, $files['filesize']);
		}

		return format_bytes(array_sum($filesizes), 2);
	}

	function rawusedspace() {
		include 'connection.php';
		$userid = $_SESSION['userid'];

		$select_data = "SELECT * FROM `files` WHERE `user` = '$userid' AND `filesize` != ''";
		$select_data_query = mysqli_query($db, $select_data);

		$filesizes2 = [];
		while($files2 = mysqli_fetch_assoc($select_data_query)) {
			array_push($filesizes2, $files2['filesize']);
		}
		$adding = array_sum($filesizes2);
		return $adding;
	}

	function rawtotalspace($userid) {
		include 'connection.php';

		$select_data3 = "SELECT * FROM `users` WHERE `id` = '$userid'";
		$select_data_query2 = mysqli_query($db, $select_data3);
		$fullspace = mysqli_fetch_array($select_data_query2);
		$rawspace = $fullspace['hostspace'];
		return $rawspace;
	}

	function showlastuploads() {
		include 'connection.php';
		$userid = $_SESSION['userid'];

		$selectlastuploads = "SELECT * FROM `files` WHERE `user` = '$userid' AND `mimetype` != 'folder' ORDER BY `created` DESC LIMIT 0, 5";
		$selectlastuploads_query = mysqli_query($db, $selectlastuploads);
		if(mysqli_num_rows($selectlastuploads_query) == 0) {
			echo '<h4>No elements to show.</h4>';
		} else {
			while ( $fetchupload = mysqli_fetch_assoc( $selectlastuploads_query ) ) {
				$elementid   = $fetchupload['id'];
				$elementname = $fetchupload['name'];
				$elementpath = $fetchupload['folderpath'];
				$folderpathedit = str_replace('files/'.$userid, "", "$elementpath");
				echo '<div class="searchelement folderpath" data-id="' . $elementid . '" data-path="'.substr($folderpathedit, 1).'">';
				echo '<h4>' . $elementname . '</h4>';
				echo '</div>';
			}
		}
	}

	function showlastsearch() {
		include 'connection.php';
		$userid = $_SESSION['userid'];

		$selectlastuploads = "SELECT * FROM `searchtags` WHERE `user` = '$userid' AND `tag` != '' ORDER BY `created` DESC LIMIT 0, 5";
		$selectlastuploads_query = mysqli_query($db, $selectlastuploads);
		if(mysqli_num_rows($selectlastuploads_query) == 0) {
			echo '<h4>No elements to show.</h4>';
		} else {
			while ( $fetchupload = mysqli_fetch_assoc( $selectlastuploads_query ) ) {
				$elementid   = $fetchupload['id'];
				$elementname = $fetchupload['tag'];
				echo '<div class="searchelement lastsearchrequest" data-id="' . $elementid . '">';
				echo '<h4>' . $elementname . '</h4>';
				echo '</div>';
			}
		}
	}
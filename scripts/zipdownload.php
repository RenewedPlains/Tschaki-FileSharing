<?php
	$zipfile = $_GET['zipname'];
		header('Content-Description: File Transfer');
		header('Content-Type: application/zip');
		header('Content-Disposition: attachment; filename="'.basename($zipfile).'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($zipfile));
		ob_clean();
		flush();
		readfile($zipfile);
		unlink($zipfile);
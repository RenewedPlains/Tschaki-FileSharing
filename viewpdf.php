<?php session_start();
$filename = $_GET['file'];
$file_extension = strtolower(substr(strrchr($filename, "."), 1));

switch ($file_extension) {
	case "pdf":
		$ctype = "application/pdf";
		break;
	default:
}

header('Content-type: ' . $ctype);

ob_clean();
flush();

readfile($filename);
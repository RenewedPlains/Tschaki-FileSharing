<?php session_start();
$filename = $_GET['file'];
$file_extension = strtolower(substr(strrchr($filename, "."), 1));

switch ($file_extension) {
	case "webm":
		$ctype = "video/webm";
		break;
	case "ogv":
		$ctype = "video/ogg";
		break;
	case "mp4":
		$ctype = "video/mp4";
		break;
	case "m4v":
		$ctype = "video/x-m4v";
		break;
	default:
}

header('Content-type: ' . $ctype);

ob_clean();
flush();

readfile($filename);
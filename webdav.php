<?php
	include 'Sabre/autoload.php';
	$settings = array( 'baseUri' => 'https://tschaki.com/files', 'userName' => 'mayo', 'password' => 'test92' );
	$dav = new Sabre_DAV_Client($settings);
	$dav->request('MKCOL', 'test/');
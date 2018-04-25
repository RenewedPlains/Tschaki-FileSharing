<?php
function forgetmail($receive, $send, $pagename, $pageuri, $receivename, $activehash) {
	$empfaenger = $send . ', ';

	// Betreff
	$betreff = 'Your password on '.$pagename;

	// Nachricht
	$nachricht = '
		Thanks for using '.$pagename.'<br /><br />
		Please click on the following link to reset your password: '.$pageuri.'/?reset='. $activehash;

	// für HTML-E-Mails muss der 'Content-type'-Header gesetzt werden
	$header = 'MIME-Version: 1.0' . "\r\n";
	$header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// zusätzliche Header
	$header .= 'To: '.$receivename.' <'.$receive.'>' . "\r\n";
	$header .= 'From: '.$pagename.' <'.$send.'>' . "\r\n";
	mail( $empfaenger, $betreff, $nachricht, $header );
}
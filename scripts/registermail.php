<?php
	function registermail($receive, $send, $pagename, $pageuri, $receivename, $activehash) {
		$empfaenger = $send . ', ';

		// Betreff
		$betreff = 'Your registration on '.$pagename;

		// Nachricht
		$nachricht = '
		Thanks for your signup on '.$pagename.'<br /><br />
		Please click on the following link to activate your account: '.$pageuri.'/?activate='. $activehash;

		// für HTML-E-Mails muss der 'Content-type'-Header gesetzt werden
		$header = 'MIME-Version: 1.0' . "\r\n";
		$header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// zusätzliche Header
		$header .= 'To: '.$receivename.' <'.$receive.'>' . "\r\n";
		$header .= 'From: '.$pagename.' <'.$send.'>' . "\r\n";
		mail( $empfaenger, $betreff, $nachricht, $header );
	}
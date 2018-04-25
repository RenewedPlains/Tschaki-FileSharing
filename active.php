<?php include 'header.php'; 
	if($_GET['hash'] && $_GET['email']) {
		$selectuser = "SELECT * FROM `users` WHERE `activate` = '".$_GET['hash']."' AND `email` = '".$_GET['email']."'";
		$selectuser_query = mysqli_query($db, $selectuser);
		if(mysqli_num_rows($selectuser_query) == 1) {
			$updateuser = "UPDATE `users` SET `activate` = 'yes' WHERE `activate` = '".$_GET['hash']."' AND `email` = '".$_GET['email']."'";
			$updateuser_query = mysqli_query($db, $updateuser); ?>
<div class="row">
	<div class="col-md-8">
		<h1>Dein Account wurde erfolgreich aktiviert!</h1>
		<p>Dein Account für Tschaki.com wurde soeben erfolgreich aktiviert. Wir haben uns die Freiheit genommen, dich bereits in deinen Account einzuloggen, damit du sofort loslegen kannst, andere Spiele Schachmatt zu setzen.<br /><br /> Viel Spass!</p><br /><br />
	</div>
<?php	
		} else {
		header("Location: /");
	}
	} else {
		header("Location: /");
	}
?>

<?php include 'footer.php'; ?>
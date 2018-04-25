<?php session_start(); $file = $_GET['file']; include './scripts/functions.php'; include './scripts/connection.php';
	?><!DOCTYPE html>
    <head>
        <title></title>
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <meta name="author" content="Mario Freuler" />
        <meta name="viewport" content="width=device-width, user-scalable=no" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="https://fonts.googleapis.com/css?family=Droid+Sans|Open+Sans:400,700" rel="stylesheet">

	<script src="/js/dropzone.js"></script>
	<link rel="stylesheet" href="/css/dropzone.css" />
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
	<link rel="stylesheet" href="/css/jquery.sweet-modal.min.css" />

    </head>
    <body>
	    <header>
	    </header>
	    <article id="content">
		    <div id="innercontent"><div id="breadcrumb">
				</span><?php /* if($_GET['path'] == '/' OR $_GET['path'] == '') { ?><ul id="quicksearch"><li class="picturefilter"><img src="images/icons/picture.svg" class="selecticon" /><div class="arrow-up"></div></li><li class="documentfilter"><img src="images/icons/document.svg" class="selecticon" /><div class="arrow-up"></div></li><li class="musicfilter"><img src="images/icons/music.svg" class="selecticon" /><div class="arrow-up"></div></li></ul><?php } */ ?></div>
				<div id="filebox">
					<div id="innerfilebox">
						<div id="filescroller">
						<br /><br /><br />
						</div>
					</div>
				</div>
				<div id="grid">

					<?php 
						if($file != '') {
							$select_file = "SELECT * FROM `files` WHERE `sharehash` = '$file'";
							$select_file_query = mysqli_query($db, $select_file);

							if(mysqli_num_rows($select_file_query) == 1) {

								$thedata = mysqli_fetch_array($select_file_query);
								$folderpath = $thedata['folderpath'];
								$filehash = $thedata['hashname'];
								$filename = $thedata['name'];
								$fileid = $thedata['id'];
								$createstamp = $thedata['created'];
								$filesize = $thedata['filesize'];
								$ownid = $thedata['user'];
								$description = $thedata['content'];
								$downloadcounter = $thedata['downloadcounter'];
								$sharehash = $thedata['sharehash'];
								$sharepass = $thedata['sharepass'];
								if($sharepass == '') { $_SESSION['passworded'] = ''; }
								if(!isset($_SESSION['passworded'])) {
								if($sharepass != '') { 
									if($_POST['password']) {
										$passhash = sha1($_POST['password']);
										if($sharepass == $passhash) {
											$_SESSION['passworded'] = $passhash;
											header("Location: /share.php?file=" . $_GET['file']);
										} else { ?>
											<div class="falsebadge">
												Password is incorrect.
	    									</div>
	    									
										<?php }
									} ?>
										<form action="" method="POST">
										<label for="passwordprotect">This content is password protected:</label>
										<input id="sharepassword" placeholder="Password" name="password" type="password">
										<input type="submit" class="bluebutton" value="Send" />
										</form>
								<?php 
								} 
								} else {
								if($_SESSION['passworded'] == $sharepass) {
								if($thedata['mimetype'] == 'folder') {
									$foldersize = [];
									$elementcounter = 0;

									$folder = $folderpath . "/" . $filename;

									$select_folderfiles = "SELECT * FROM `files` WHERE `folderpath` LIKE '$folder%'";
									$select_folderfiles_query = mysqli_query($db, $select_folderfiles);
									while($countfilesize = mysqli_fetch_assoc($select_folderfiles_query)) {
										array_push($foldersize, $countfilesize['filesize']);
										$elementcounter++;
									}
									$countedfoldersize = array_sum($foldersize);
									echo '<div class="box" data-id="' . $fileid . '"><div class="mime" data-folder="' . $pathalt . '"><img src="/images/icons/mime/folder.svg" alt="Folder" /></div><div class="titlewrapper" data-folder="' . $pathalt . '"><p class="title">' . $filename . '</p></div><p class="filesize">' . format_bytes($countedfoldersize, 1) . '<span class="none selected"> – selected</span><span class="right">' . $elementcounter . ' elements</span></p>
									<div id="downloadable"><button class="downloadbutton" data-hash="' . $sharehash . '" data-mime="folder" data-id="' . $fileid . '">Download .zip</button></div>
									</div>';
								} else {
								if(is_image("./" . $folderpath . "/" . $filehash)){
									echo '<div class="box" data-id="' . $fileid . '"><img src="/images/icons/check.png" class="check none" /><div class="preview" data-imagesource="/viewimage.php?file=' . $folderpath . "/" . $filehash .'"><br /><br /><br /><br /></div><div class="titlewrapper"><p class="title">' . $filename;
	if($sharehash != '') { echo '<img src="/images/icons/globe.png" class="shareicons isshared" alt="Shared" title="Shared" />'; if($sharepass != '') { echo '<img src="/images/icons/lock.png" class="shareicons lock" alt="Password protected" title="Password protected" />'; } }	echo '</p></div>
									<p class="filesize">' . format_bytes($filesize, 1) . '<span class="none"> – selected</span></p>
									<div id="downloadable"><button class="downloadbutton" data-hash="' . $sharehash . '" data-mime="image" data-id="' . $fileid . '">Download image</button></div></div>';
								} else {
									echo '<div class="box" data-id="' . $fileid . '"><img src="/images/icons/check.png" class="check none" /><div class="mime"></div><div class="titlewrapper"><p class="title">' . $filename;
	if($sharehash != '') { echo '<img src="/images/icons/globe.png" class="shareicons isshared" alt="Shared" title="Shared" />'; if($sharepass != '') { echo '<img src="/images/icons/lock.png" class="shareicons lock" alt="Password protected" title="Password protected" />'; } }	echo '</p></div><p class="filesize">' . format_bytes($filesize, 1) . '<span class="none"> – selected</span></p>
									<div id="downloadable"><button class="downloadbutton" data-hash="' . $sharehash . '" data-mime="file" data-id="' . $fileid . '">Download file</button></div></div>';
								}
							} 
							$select_user = "SELECT * FROM `users` WHERE `id` = '$ownid'";
							$select_user_query = mysqli_query($db, $select_user);
							$out_user = mysqli_fetch_array($select_user_query);
							
							$select_settings = "SELECT * FROM `settings` WHERE `meta` = 'dateformat'";
							$select_settings_query = mysqli_query($db, $select_settings);
							$out_settings = mysqli_fetch_array($select_settings_query);

							?>
							<div class="" style="background-color: white; float: right; padding: 10px; width: calc(100% - 270px);">
								<h2>Downloadable content</h2>
								<p class="shared">User <?php echo $out_user['name']; ?> has shared content with you.<br /><br /><br />
									<strong>Upload date: </strong><?php echo date($out_settings['value'], $createstamp); ?><br />
									<strong>Password: </strong><?php if($sharepass == '') { echo 'No'; } else { echo 'Yes'; } ?><br />
									<strong>Downloaded: </strong><?php if($downloadcounter == '') { echo '0'; } else { echo $downloadcounter; } ?> times
									<?php if($description != '') { ?><br /><strong>Description: </strong><?php echo nl2br($description); } ?>
								</p>
							</div>
							<?php  } else { unset($_SESSION['passworded']); header("Location: /share.php?file=" . $_GET['file']); } } } else { ?>
							<div class="falsebadge">
								Es wurde keine Datei gefunden. Bitte versuchen Sie es erneut.
	    					</div>
						<?php } 
						} else { ?>
							<div class="falsebadge">
								Es wurde keine Datei gefunden. Bitte versuchen Sie es erneut.
	    					</div>
						<?php } ?>
				</div>
		    </div>
			
	    </article>
	    <footer>
		    <div id="innerfooter">
			    <div class="left">
				    <div id="logowrapper">
				    	<a href="/"><img src="images/tschaki.svg" id="logo" alt="Logo" /></a>
				    </div>
			    </div>
			    <div class="right">
				    
			    </div>
			    <div class="clear"></div>
		    </div>
	    </footer>
	<script src="/js/jquery.min.js"></script>
	<script src="/js/backstretch.min.js"></script>
	<script src="/js/jquery.sweet-modal.min.js"></script>
	<script src="/js/tschaki.js"></script>
    </body>
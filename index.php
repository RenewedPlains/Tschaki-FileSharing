<?php session_start(); $id = $_SESSION['userid']; include './scripts/functions.php';
	?><!DOCTYPE html>
    <head>
        <title></title>
        <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <meta name="author" content="Mario Freuler" />
        <meta name="viewport" content="width=device-width, user-scalable=no" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="https://fonts.googleapis.com/css?family=Droid+Sans|Open+Sans:400,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Yantramanav:100" rel="stylesheet">
        <script src="/js/dropzone.js"></script>
        <link rel="stylesheet" href="/css/dropzone.css" />
        <link rel="stylesheet" type="text/css" href="/css/style.css" />
        <link rel="stylesheet" href="/css/jquery.sweet-modal.min.css" />
        <link rel="stylesheet" href="./css/bootstrap.min.css" />
        <link rel="stylesheet" href="./flowplayer/skin/skin.css">
    </head>
    <body>
    <?php
    if($_GET['reset'] != '') {
        echo '
	    <script src="/js/jquery.min.js"></script>
	    <script>
	        $(function() {
	            $.ajax({
                    url: "scripts/resetpassword.php",
                    method: "post"
	            }).done(function(html) {
                $.sweetModal.confirm("Set your new password", html, function(event) {
                    var epasswordforget = $("#epasswordforget").val();
                    var repasswordforget = $("#repasswordforget").val();
                    var hash = "'.$_GET['reset'].'";
                    $.ajax({
                        url: "scripts/resetpassword.php",
                        method: "post",
                        data: { hash : hash, epasswordforget : epasswordforget, repasswordforget : repasswordforget }
                    }).done(function(html1) {
                         if(html1 == "Your password is now resetet.") {
					$.sweetModal({
						content: html1,
						icon: $.sweetModal.ICON_SUCCESS,
						onClose: function (sweetModal) {
							$(".dark-modal").remove();
						}
					});
				} else {
					$.sweetModal({
						content: html1,
						icon: $.sweetModal.ICON_ERROR,
						onClose: function (sweetModal) {
							$(".dark-modal").remove();
						}
					});
				}       
                    });                            
                }); 
	        });
	            });
	    </script>';
    }
    if($_GET['activate'] != '') {
        include 'scripts/connection.php';
        $selectthisuser = "SELECT * FROM `users` WHERE `activate` = '$_GET[activate]'";
        $selectthisuser_query = mysqli_query($db, $selectthisuser);
        if(mysqli_num_rows($selectthisuser_query) == 1) {
            $updateuser = "UPDATE `users` SET `activate` = '' WHERE `activate` = '$_GET[activate]'";
            $updateuser_query = mysqli_query($db, $updateuser);
            echo '<div class="successbadge">
		            Your account is now activated. Please login.
	            </div>';
        }
    } ?>
	    <?php if($_SESSION['login'] == 'failed') { ?>
	    <div class="falsebadge">
		    Login schlug aufgrund falschen Benutzerdaten fehl. Versuchen Sie es erneut.
	    </div>
	    <?php unset($_SESSION['login']); ?>
	    <?php } ?>
    <div id="sitewrapper">
	    <header>
		    <?php if($_SESSION['userid']) { ?>

		    <div id="innerheader">
                <div id="sideexpand">
                    <div id="nav-icon1">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
			    <input type="text" value="" id="domainsearch" placeholder="Browse my files..." />
                <div id="searchfield">
                    <a href="#" class="close closesearch">&nbsp;</a>
                    <div id="searchfieldinner">
                        <div class="row">
                            <div class="col-md-12 aftersearch">

                            </div>
                            <div class="col-md-6 beforesearch">
                                <h3>Last uploads</h3>
                                <?php showlastuploads(); ?>
                            </div>
                            <div class="col-md-6 beforesearch">
                                <h3>Last searchqueries</h3>
	                            <?php showlastsearch(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
		    </div>
                <div id="sidebar">
                    <div class="innersidebar">
                        <h3>
                            Welcome, <?php username(); ?>!
                        </h3>
					    <?php $restspace = rawtotalspace($id) - rawusedspace();
					    $perc = $restspace / rawtotalspace($id);
					    $percentstate = 1 - $perc; ?>
                        <div id="container" data-totalspace="<?php if($percentstate < 0.01) { echo '0.01'; } else { echo $percentstate; } ?>" data-usedspace="<?php if($percentstate < 0.01) { echo usedspace(); } else { echo usedspace(); } ?>"></div>
                        <span class="oftotal">of <?php echo format_bytes(rawtotalspace($id), 2); ?> filled</span>
                        <div class="whitesep"></div>
                        <form action="/file-upload.php?path=<?php echo $_GET['path']; ?>"  method="POST" class="dropzoner">
                        </form>
                    </div>
                </div>
			<?php } else { ?>
			<?php } ?>
	    </header>
	    <article id="content">
		    <div id="innercontent">
			    <?php if($_SESSION['userid']) { ?>
			    <div id="breadcrumb"><img src="/images/icons/back.svg" class="goback" alt="Back" title="Back" /><img src="/images/icons/next.svg" class="goforward" alt="Next" title="Next" /><img src="/images/icons/loading.svg" class="refresh" alt="Refresh" title="Refresh" />
				<?php if($_GET['path'] == '/' OR $_GET['path'] == '') { } else { ?><a href="/"><img src="/images/icons/home.svg" class="home" alt="Home" title="Home" /></a><?php } ?><span class="crumbtext">You are here: <?php if($_GET['path'] == '/' OR $_GET['path'] == '') { echo '/'; } ?><?php echo $_GET['path']; ?></span><?php /* if($_GET['path'] == '/' OR $_GET['path'] == '') { ?><ul id="quicksearch"><li class="picturefilter"><img src="images/icons/picture.svg" class="selecticon" /><div class="arrow-up"></div></li><li class="documentfilter"><img src="images/icons/document.svg" class="selecticon" /><div class="arrow-up"></div></li><li class="musicfilter"><img src="images/icons/music.svg" class="selecticon" /><div class="arrow-up"></div></li></ul><?php } */ ?>
                    <div class="selectall">select all</div>
                </div>
                    <div id="filebox">
					<div id="innerfilebox">
						<div id="filescroller">
						<br /><br /><br />
						</div>
					</div>
				</div>

				<div id="grid" data-path="<?php echo $_GET['path']; ?>">

				</div>
				<?php } else { ?>
				<div id="login">
					<form action="/scripts/login.php" method="POST">
						<label for="username">E-Mail</label>
						<input id="username" type="text" value="<?php echo $_SESSION['email_login']; ?>" name="username" class="standinput" required="required" /><br />
						<label for="password">Password</label>
						<input id="password" type="password" value="" name="password" class="standinput" required="required" /><br />
						<div class="loginlinks">
                            <a href="#" class="register">Register</a><br />
                            <a href="#" class="passwordforget">Password forget</a>
                        </div>
                        <input type="submit" class="submitbutton" value="Login" />
					</form>
				</div>
				<?php } ?>
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
				    <?php if($_SESSION['userid']) { ?>
				    <ul id="navicons">
					    <li>
							<div class="tooltip">Upload</div>
					    	<img src="/images/icons/upload.png" class="uploadbutton uploaddeactive" alt="Upload" title="Upload" />
					    </li>
					     <li>
					     	<div class="tooltip">New folder</div>
					    	<img class="addfolder" src="/images/icons/folder.png" alt="New Folder" title="New Folder" />
					    </li>
					    <li>
					    	<div class="tooltip">Shared files</div>
					    	<img class="sharedcontent" src="/images/icons/shared.png" alt="Shared Files" title="Shared Files" />
					    </li>
					    <li>
					    	<div class="tooltip">Settings</div>
					    	<img class="settings" src="/images/icons/tools.png" alt="Settings" title="Settings" />
					    </li>
					    <li>
					    	<div class="tooltip">Logout</div>
					    	<a href="/scripts/logout.php"><img src="/images/icons/logout.png" alt="Logout" title="Logout" /></a>
					    </li>
				    </ul>
				    <?php } ?>
			    </div>
			    <div class="clear"></div>
		    </div>
	    </footer>
	    <div id="selectinfo">
		    <div class="innerselectinfo">
		    	<div class="left selectinfobar">
		    	</div>
		    	<div class="right selectinfobar">
			    	<div><input type="hidden" value="" id="hiddenboxid" />
				    	<img class="moveallmarked" src="/images/icons/move_white.png" alt="Move selected items" title="Move selected items" />
				    	<img class="deleteallmarked" src="/images/icons/trash_white.png" alt="Delete selected items" title="Delete selected items" />
			    	</div>
		    	</div>
		    </div>
	    </div>
    </div>
	<script src="/js/jquery.min.js"></script>
	<script src="/js/backstretch.min.js"></script>
    <script src="/js/progressbar.js"></script>
	<script src="/js/jquery.sweet-modal.min.js"></script>
	<script src="/js/ext.js"></script>
    <script src="/js/tschaki.js"></script>
    </body>
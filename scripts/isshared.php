<?php 
	session_start(); 
	$userid = $_SESSION['userid'];
	include 'connection.php';
	$id = $_GET['id'];
?>
	<script>
		function loadfiles() {
			var path = $('#grid').attr('data-path');
			$.ajax({
				url: "/datagrid.php?path=" + path
			})
			.done(function(html) {
				$("#grid").html(html);
	  		});
		}
		
		function removeshare(boxid) {
		$.ajax({
				url: "/scripts/removeshare.php?id=" + boxid,
				method: "post"
			})
			.done(function(html) {
					loadfiles();
					$.sweetModal({
						content: 'Sharing was stopped.',
						icon: $.sweetModal.ICON_SUCCESS,
						onClose: function(sweetModal) {
							$('.dark-modal').remove();
						}
					});
  			});
		}
	</script>
<?php
	$select_file = "SELECT * FROM `files` WHERE `id` = '$id' AND `user` = '$userid'";
	$select_file_query = mysqli_query($db, $select_file);
	if(mysqli_num_rows($select_file_query) == 1) {
		
		$hashname = mysqli_fetch_array($select_file_query);
		$passwordprotect = $hashname['sharepass'];
		if($passwordprotect != '') { 
			$setpass = ' with password protection';
		}
		echo 'This content has been shared via link'. $setpass .'.<br /><br />';
		$sharelink = $hashname['sharehash'];
		echo '<a class="sharelink" href="http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/share.php?file=' . $sharelink .'" target="_blank">http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/share.php?file=' . $sharelink .'</a><br /><br />
		<a onclick="removeshare('.$id.')" class="cancelbutton">Cancel sharing</a>';
	} else {
		var_dump(http_response_code(404));
		exit();
	}
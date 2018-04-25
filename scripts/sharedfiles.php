<?php session_start(); $userid = $_SESSION['userid']; include 'connection.php';
	$select_shared_files = "SELECT * FROM `files` WHERE `user` = '$userid' AND `sharehash` != ''";
	$select_shared_files_query = mysqli_query($db, $select_shared_files);
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
<table style="width: 100%;">
	<tr>
		<td>
			<p><strong>Title</strong></p><br />
		</td>
		<td>
			<p><strong>Location</strong></p><br />
		</td>
		<td>
			<p><strong>Downloads</strong></p><br />
		</td>
		<td>
			<p><strong>Password</strong></p><br />
		</td>
		<td>
			<p><strong>Options</strong></p><br />
		</td>
	</tr>
	<?php 
		if(mysqli_num_rows($select_shared_files_query) == 0) { ?>
			<tr>
				<td>
					<strong>No data is shared.</strong>
				</td>
			</tr>
		<?php } else {
		 while ($myshares = mysqli_fetch_assoc($select_shared_files_query)) { ?>
	<tr>
		<td>
			<?php echo $myshares['name']; ?>
		</td>
		<td>
			<?php 
				$rootpath = str_replace('files/'. $userid, "", $myshares['folderpath']);
				if($rootpath == '') { $rootpath = '/'; }
				echo $rootpath; ?>
		</td>
		<td>
			<?php if($myshares['downloadcounter'] == '' OR $myshares['downloadcounter'] == '0') { echo '0'; } else { echo $myshares['downloadcounter']; } ?> downloads
		</td>
		<td>
			<?php if($myshares['sharepass'] == '') { echo 'No'; } else { echo 'Yes'; } ?>
		</td>
		<td>
			<div class="optionsbutton left" onclick="removeshare(<?php echo $myshares['id']; ?>)">
				Cancel  sharing
			</div>
			<div class="optionsbutton">
				Open link
			</div>
		</td>
	</tr>
	<?php } } ?>
		</table>
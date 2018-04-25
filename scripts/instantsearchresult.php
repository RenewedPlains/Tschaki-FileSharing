<?php session_start();

echo '<script src="/js/tschaki.js"></script>
      <script src="/js/grid.js"></script>
      <script>
      $(".folderpath").click(function() {
        var folder = $(this).attr("data-path");
        window.location.href = "./?path="+folder;
    });
</script>';
$searchtag = $_GET['searchtag'];
$userid = $_SESSION['userid'];
$timestamp = time();
if($searchtag == '') {

} else {
	include 'connection.php';
	$insertsearchstring       = "SELECT * FROM `files` WHERE `name` LIKE '%$searchtag%' AND `user` = '$userid' AND `mimetype` != 'folder' OR  `hashname` LIKE '%$searchtag%' AND `user` = '$userid' AND `mimetype` != 'folder' OR  `keywords` LIKE '%$searchtag%' AND `user` = '$userid' AND `mimetype` != 'folder' OR `content` LIKE '%$searchtag%' AND `user` = '$userid' AND `mimetype` != 'folder' OR  `mimetype` LIKE '%$searchtag%' AND `user` = '$userid' AND `mimetype` != 'folder'";
	$insertsearchstring_query = mysqli_query( $db, $insertsearchstring );
	if(mysqli_num_rows($insertsearchstring_query) == 0) {
		echo '<h4>No files to show.</h4>';
	}
	while ( $fetchupload = mysqli_fetch_assoc( $insertsearchstring_query ) ) {
		$elementid   = $fetchupload['id'];
		$elementname = $fetchupload['name'];
		$folderpath = $fetchupload['folderpath'];
		$file       = $fetchupload['hashname'];
		$filename   = $fetchupload['name'];
		$fileid     = $fetchupload['id'];
		$filesize   = $fetchupload['filesize'];
		$sharehash  = $fetchupload['sharehash'];
		$sharepass  = $fetchupload['sharepass'];
		$folderpathedit = str_replace('files/'.$userid, "", "$folderpath");
		if($folderpathedit == '') { $folderpathedit = '/'; }
		echo '<div class="searchelement box" data-checker="" data-id="' . $elementid . '">';
		echo '<h4>' . $elementname . '<br />
			 <span class="folderpath" data-path="'. substr($folderpathedit, 1) .'">'. $folderpathedit .'</span></h4>';
		echo '<div class="iconbarsearch"><div class="boxicons boxiconssearch">
				<div class="left">
					<ul>
						<li>
							<input type="checkbox" data-clicked="" data-id="' . $fileid . '" name="" class="selectersearch" />
						</li>
						<li>
							<a href="/download.php?file=' . $file . '">
								<img src="/images/icons/download_white.png" alt="Download" title="Download" />
							</a>
						</li>
					</ul>
				</div>
				<div class="right">
					<ul>
						<li>
							<img src="/images/icons/delete_white.png" class="delete" alt="Delete" title="Delete" />
						</li>
						<li>
							<img src="/images/icons/';
		if ( $sharehash != '' ) {
			echo 'share_activ.png';
		} else {
			echo 'share_white.png';
		}
		echo '" class="';
		if ( $sharehash != '' ) {
			echo 'isshared';
		} else {
			echo 'sharethis';
		}
		echo '" alt="Share" title="Share" />
						</li>
						<li>
							<img class="editbutton" src="/images/icons/edit_white.png" alt="Edit" title="Edit" />
						</li>
						<li>
							<img class="infobutton" src="/images/icons/info_white.png" alt="Info" title="Info" />
						</li>
					</ul>
				</div>
			</div>
		</div><div class="clear"></div></div>';
	}
}
<?php session_start();
	include 'scripts/functions.php';
					$id = $_SESSION['userid'];
					include 'scripts/connection.php';
					
					if($_GET['content'] == 'picture') {
						$path = "files/" . $id;
						$select_files = "SELECT * FROM `files` WHERE `user` = '$id' ORDER BY `name`, `id`";
						$select_files_query = mysqli_query($db, $select_files);
					echo '<div class="left" id="measure">'; 
						while($select_root_files = mysqli_fetch_assoc($select_files_query)) {
							$folderpath = $select_root_files['folderpath'];
							$file = $select_root_files['hashname'];
							$filename = $select_root_files['name'];
							$fileid = $select_root_files['id'];
							$filesize = $select_root_files['filesize'];
							if($select_root_files['mimetype'] != 'folder') {
								if(is_image("./" . $folderpath . "/" . $file)) {
									echo '<div class="box" data-id="' . $fileid . '"><img src="/images/icons/check.png" class="check none" /><div class="preview" data-imagesource="/viewimage.php?file=' . $folderpath . "/" . $file .'"><br /><br /><br /><br /></div><div class="titlewrapper"><p class="title">' . $filename .'</p></div>
									<p class="filesize">' . format_bytes($filesize, 1) . '<span class="none"> – ausgewählt</span></p>
									<div class="iconbar"><div class="boxicons">
										<div class="left">
											<ul>
												<li>
													<input type="checkbox" data-clicked="" data-id="' . $fileid .'" name="" class="selecter" />
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
													<img src="/images/icons/share_white.png" alt="Share" title="Share" />
												</li>
												<li>
													<img src="/images/icons/edit_white.png" alt="Edit" title="Edit" />
												</li>
												<li>
													<img src="/images/icons/info_white.png" alt="Info" title="Info" />
												</li>
											</ul>
										</div>
										<div class="clear"></div>
										</div>
									</div></div>';
								} 
							}
						}
					}
					echo '</div>';
					?>
					<div class="clear"></div>
	<script src="/js/jquery.min.js"></script>
	<script src="/js/backstretch.min.js"></script>
	<script src="/js/jquery.sweet-modal.min.js"></script>
	<script src="/js/grid.js"></script>
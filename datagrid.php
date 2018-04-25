<?php session_start();
	include 'scripts/functions.php';
					$id = $_SESSION['userid'];
					include 'scripts/connection.php';
					
					if($_GET['path'] == '/' OR $_GET['path'] == '') {
						$path = "files/" . $id;
						$select_files = "SELECT * FROM `files` WHERE `user` = '$id' AND `folderpath` = '$path'  ORDER BY `name`, `id`";
						$select_files_query = mysqli_query($db, $select_files);
					} else {
						$path = "files/" . $id . "/" . $_GET['path'];
						$select_files = "SELECT * FROM `files` WHERE `user` = '$id' AND `folderpath` = '$path' ORDER BY `name`, `id`";
						$select_files_query = mysqli_query($db, $select_files);
					}
					if(mysqli_num_rows($select_files_query) == 0) {
                        echo '<div class="no-files">
                                  <h3>There are no files to show.</h3>
                              </div>';
                    } else {
						while ( $select_root_files = mysqli_fetch_assoc( $select_files_query ) ) {
							$folderpath = $select_root_files['folderpath'];
							$file       = $select_root_files['hashname'];
							$filename   = $select_root_files['name'];
							$fileid     = $select_root_files['id'];
							$filesize   = $select_root_files['filesize'];
							$sharehash  = $select_root_files['sharehash'];
							$sharepass  = $select_root_files['sharepass'];
							if ( $select_root_files['mimetype'] == 'folder' ) {
								if ( $_GET['path'] == '/' OR $_GET['path'] == '' ) {
									$folder = "files/" . $id . $_GET['path'] . "/" . $filename;
								} else {
									$folder = "files/" . $id . "/" . $_GET['path'] . "/" . $filename;
								}
								$select_folderfiles       = "SELECT * FROM `files` WHERE `folderpath` LIKE '$folder%' AND `user` = '$id'";
								$select_folderfiles_query = mysqli_query( $db, $select_folderfiles );

								$foldersize     = [];
								$elementcounter = 0;
								while ( $countfilesize = mysqli_fetch_assoc( $select_folderfiles_query ) ) {
									array_push( $foldersize, $countfilesize['filesize'] );
									$elementcounter ++;
								}
								$countedfoldersize = array_sum( $foldersize );
								if ( $_GET['path'] == '/' OR $_GET['path'] == '' ) {
									$pathalt = substr( $_GET['path'] . "/" . $file, 1 );
								} else {
									$pathalt = $_GET['path'] . "/" . $file;
								}
								echo '<div class="box folderclick" data-folder="' . $pathalt . '" data-id="' . $fileid . '"><div class="mime" data-folder="' . $pathalt . '"><img src="/images/icons/mime/folder.svg" alt="Folder" /></div><div class="titlewrapper" data-folder="' . $pathalt . '"><p class="title">' . $filename;
								if ( $sharehash != '' ) {
									echo '<img src="/images/icons/globe.png" class="shareicons isshared" alt="Shared" title="Shared" />';
									if ( $sharepass != '' ) {
										echo '<img src="/images/icons/lock.png" class="shareicons lock" alt="Password protected" title="Password protected" />';
									}
								}
								echo '</p></div><p class="filesize">' . format_bytes( $countedfoldersize, 1 ) . '<span class="none selected"> – selected</span><span class="right">' . $elementcounter . ' elements</span></p>
								
									<div class="iconbar"><div class="boxicons">
										<div class="left">
											<ul>
											    <li>
													<input type="checkbox" data-clicked="" data-id="' . $fileid . '" name="" class="selecter" />
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
													<img src="/images/icons/delete_white.png" class="deletefolder" alt="Delete" title="Delete" />
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
										<div class="clear"></div>
										</div>
									</div></div>';
							} else {
								if ( is_image( "./" . $folderpath . "/" . $file ) ) {
									echo '<div class="box" data-id="' . $fileid . '"><img src="/images/icons/check.png" class="check none" /><div class="preview" data-imagesource="/viewimage.php?file=' . $folderpath . "/" . $file . '"><br /><br /><br /><br /></div><div class="titlewrapper"><p class="title">' . $filename;
									if ( $sharehash != '' ) {
										echo '<img src="/images/icons/globe.png" class="shareicons isshared" alt="Shared" title="Shared" />';
										if ( $sharepass != '' ) {
											echo '<img src="/images/icons/lock.png" class="shareicons lock" alt="Password protected" title="Password protected" />';
										}
									}
									echo '</p></div>
									<p class="filesize">' . format_bytes( $filesize, 1 ) . '<span class="none"> – selected</span></p>
									<div class="iconbar"><div class="boxicons">
										<div class="left">
											<ul>
												<li>
													<input type="checkbox" data-clicked="" data-id="' . $fileid . '" name="" class="selecter" />
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
										<div class="clear"></div>
										</div>
									</div></div>';
								} else {
									$addclass        = '';
									$additionaldata1 = '';
									$additionaldata2 = '';
									if ( $select_root_files['mimetype'] == 'text/html' ) {
										$thumbmime = '/images/icons/mime/html.png';
									} elseif ( $select_root_files['mimetype'] == 'application/zip' ) {
										$thumbmime = '/images/icons/mime/archive.png';
									} elseif ( $select_root_files['mimetype'] == 'image/gif' ) {
										$thumbmime = '/images/icons/mime/gif.png';
									} elseif ( $select_root_files['mimetype'] == 'application/msword' || $select_root_files['mimetype'] == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' || $select_root_files['mimetype'] == 'application/vnd.openxmlformats-officedocument.wordprocessingml.template' || $select_root_files['mimetype'] == 'application/vnd.ms-word.document.macroEnabled.12' || $select_root_files['mimetype'] == 'application/vnd.ms-word.template.macroEnabled.12' ) {
										$thumbmime = '/images/icons/mime/word.png';
									} elseif ( $select_root_files['mimetype'] == 'application/vnd.ms-excel' || $select_root_files['mimetype'] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $select_root_files['mimetype'] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.template' || $select_root_files['mimetype'] == 'application/vnd.ms-excel.sheet.macroEnabled.12' || $select_root_files['mimetype'] == 'application/vnd.ms-excel.template.macroEnabled.12' ) {
										$thumbmime = '/images/icons/mime/excel.png';
									} elseif ( $select_root_files['mimetype'] == 'application/vnd.ms-powerpoint' || $select_root_files['mimetype'] == 'application/vnd.openxmlformats-officedocument.presentationml.presentation' || $select_root_files['mimetype'] == 'application/vnd.openxmlformats-officedocument.presentationml.template' || $select_root_files['mimetype'] == 'application/vnd.openxmlformats-officedocument.presentationml.slideshow' || $select_root_files['mimetype'] == 'application/vnd.ms-powerpoint.addin.macroEnabled.12' ) {
										$thumbmime = '/images/icons/mime/powerpoint.png';
									} elseif ( $select_root_files['mimetype'] == 'text/php' ) {
										$thumbmime = '/images/icons/mime/php.png';
									} elseif ( $select_root_files['mimetype'] == 'application/pdf' ) {
										$thumbmime       = '/images/icons/mime/acrobat.png';
										$addclass        = ' pdf';
										$additionaldata1 = ' data-pdftitle="' . $filename . '"';
										$additionaldata2 = ' data-pdfpath="/viewpdf.php?file=' . $folderpath . "/" . $file . '"';
									} elseif ( $select_root_files['mimetype'] == 'text/css' ) {
										$thumbmime = '/images/icons/mime/css.png';
									} elseif ( $select_root_files['mimetype'] == 'text/javascript' ) {
										$thumbmime = '/images/icons/mime/js.png';
									} elseif ( $select_root_files['mimetype'] == 'image/svg+xml' ) {
										$thumbmime = '/images/icons/mime/svg.png';
									} elseif ( $select_root_files['mimetype'] == 'text/plain' ) {
										$thumbmime = '/images/icons/mime/txt.png';
									} elseif ( $select_root_files['mimetype'] == 'video/webm' || $select_root_files['mimetype'] == 'video/ogg' || $select_root_files['mimetype'] == 'video/mp4' || $select_root_files['mimetype'] == 'video/x-m4v' ) {
										$thumbmime       = '/images/icons/mime/video.png';
										$addclass        = ' video';
										$additionaldata1 = ' data-videotitle="' . $filename . '"';
										$additionaldata2 = ' data-videopath="/viewvideo.php?file=' . $folderpath . "/" . $file . '"';
										$additionaldata3 = ' data-mimetype="' . $select_root_files['mimetype'] . '"';
									} else {
										$thumbmime = '/images/icons/mime/torrent.png';
									}
									echo '<div class="box" data-id="' . $fileid . '"><img src="/images/icons/check.png" class="check none" /><div' . $additionaldata1 . '' . $additionaldata2 . '' . $additionaldata3 . ' class="mime' . $addclass . '"><img src="' . $thumbmime . '" alt=""/></div><div class="titlewrapper"><p class="title">' . $filename;
									if ( $sharehash != '' ) {
										echo '<img src="/images/icons/globe.png" class="shareicons isshared" alt="Shared" title="Shared" />';
										if ( $sharepass != '' ) {
											echo '<img src="/images/icons/lock.png" class="shareicons lock" alt="Password protected" title="Password protected" />';
										}
									}
									echo '</p></div><p class="filesize">' . format_bytes( $filesize, 1 ) . '<span class="none"> – selected</span></p>
									<div class="iconbar"><div class="boxicons">
										<div class="left">
											<ul>
												<li>
													<input type="checkbox" data-clicked="" data-id="' . $fileid . '" name="" class="selecter" />
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
										<div class="clear"></div>
										</div>
									</div></div>';
								}
							}
						}
					}
					?>
					<div class="clear"></div>
	<script src="./js/jquery.min.js"></script>
    <script src="./js/pdf.js"></script>
	<script src="./js/backstretch.min.js"></script>
	<script src="./js/jquery.sweet-modal.min.js"></script>
    <script src="./js/popcorn.js"></script>

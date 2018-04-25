$(function() {
    function loadfiles() {
        var path = $('#grid').attr('data-path');
        $.ajax({
            url: "/datagrid.php?path=" + path
        }).done(function(html) {
            $("#grid").html(html);
            $('#selectinfo').animate({'marginBottom': '-60px'}, 200);
            var searchrequest = $('#domainsearch').val();
            $.ajax({
                url: './scripts/instantsearchresult.php?searchtag=' + searchrequest,
                type: 'POST',
                success: function(html1) {
                    $('.aftersearch').html(html1);
                },
            });
        });
    }

	$(".box .preview").each(function() {
	    var previewsource = $(this).attr('data-imagesource');
	    $(this).backstretch(previewsource);
	});

	$('.box').mouseover(function() {
        $(this).children('.iconbar').stop().fadeIn(150);
	}).mouseout(function() {
        $('.box').each(function() {
            var clickchecker = $(this).children('.iconbar').hasClass('marked');
            if(clickchecker === true) {
                $(this).children('.iconbar').stop().fadeIn(150);
            } else {
                $(this).children('.iconbar').stop().fadeOut(150);
            }
        });
    });

	$('.selecter').click(function() {
		if($(this).is(':checked')) {
            $(this).parents('.iconbar').addClass('marked');
            var generallen = $(".selecter:checked").length;
            if(generallen > 0) {
                $("#selectinfo").animate({'marginBottom': '60px'}, 200);
                $(".innerselectinfo .left").text('Selected items: ' + generallen);
            } else {
                $(".innerselectinfo .left").text('');
            }
        } else {
            $(this).parents('.iconbar').removeClass('marked');
            var generallen = $(".selecter:checked").length;
            $(this).attr('data-checker', '');
            if(generallen > 0) {
                $(".innerselectinfo .left").text('Selected items: ' + generallen);
            } else {
                $(".innerselectinfo .left").text('');
            }
            if(generallen == 0) {
                $('#selectinfo').animate({'marginBottom': '-60px'}, 200);
            }
		}
	});

    $('.selectersearch').click(function() {
        if($(this).is(':checked')) {
            $(this).addClass('marked');
            $(this).attr('data-checker', 'mark');
            $('#selectinfo').animate({'marginBottom': '60px'}, 200);
            var generallen2 = $(".selectersearch:checked").length;
            if(generallen2>0) {
                $(".innerselectinfo .left").text('Selected items: ' + generallen2);
            } else {
                $(".innerselectinfo .left").text('');
            }
        } else {
            var generallen2 = $(".selectersearch:checked").length;
            if(generallen2>0) {
                $(".innerselectinfo .left").text('Selected items: ' + generallen2);
            } else {
                $(".innerselectinfo .left").text('');
            }
            $(this).attr('data-checker', '');

            if(generallen2 == 0) {
                $('#selectinfo').animate({'marginBottom': '-60px'}, 200);
            }
        }
    });

    $('.searchelement').mouseover(function() {
        var leftPos = $(this).find('h4').scrollLeft();
        $(this).find("h4").stop().animate({scrollLeft: leftPos + 400}, 6000).animate({scrollLeft: 0}, 1000);
        $(this).find("h4").stop().animate({scrollLeft: 0}, 1000);
    }).mouseout(function() {
        $(this).find("h4").stop().animate({scrollLeft: 0}, 1000);
    });
	
	$('.moveallmarked').click(function() {
		$(".marked .selecter, .selectersearch.marked").each(function( ) {
			var boxid = $(this).attr('data-id');
			$('#hiddenboxid').append(boxid+',');
        });
		var boxids = $('#hiddenboxid').html();
		$.ajax({
			url: "/scripts/move.php?val=" + boxids,
			method: "post"
		}).done(function(html) {
			$.sweetModal({
				title: 'Where do you want to move the files?',
				content: html,
				onClose: function(sweetModal) {
                    $('.dark-modal').remove();
				}
			});
            loadfiles();
        });
	});

	$('.deleteallmarked').click(function() {
		$.sweetModal.confirm('Delete', 'Are you sure you want to delete this files?', function(event) {
		    $(".marked .selecter, .selectersearch.marked").each(function( ) {
			    var boxid = $(this).attr('data-id');
			$.ajax({
				url: "/scripts/delete.php?id=" + boxid,
				method: "post"
			});
		});
		    $.sweetModal({
				content: 'Files deleted successfully.',
				icon: $.sweetModal.ICON_SUCCESS,
				onClose: function(sweetModal) {
					$('.dark-modal').remove();
					loadfiles();
					$('#selectinfo').animate({'marginBottom': '-60px'}, 200);
				}
			});
  			event.preventDefault();
		});
	});


	$('.pdf').click(function() {
		var pdftitle = $(this).attr('data-pdftitle');
		var url = $(this).attr('data-pdfpath');
        showpdf(url);
        $.sweetModal({
            content: '<h3>' + pdftitle + '</h3><br />' +
            			'<canvas data-url="' + url + '" id="the-canvas"></canvas>',
            onClose: function(sweetModal) {
                $('.dark-modal').remove();
            }
        });
	});

    $('.video').click(function() {
        var videotitle = $(this).attr('data-videotitle');
        var url = $(this).attr('data-videopath');
        var mimetype = $(this).attr('data-mimetype');
        $.sweetModal({
            content: '<h3>' + videotitle + '</h3><br />' +
            '<video id="video" class="flowplayer" controls>' +
            '<source src="' + url + '">' +
            'Your browser does not support the video tag.' +
        '</video>',
            onClose: function(sweetModal) {
                $('.dark-modal').remove();
            }
        });
    });
	
	$('.folderclick .mime, .folderclick .titlewrapper').click(function() {
	    var path1 = $(this).attr('data-folder');
	    window.location.href = "/?path=" + path1;
    });
	
	$('.sharethis').click(function() {
		var boxid = $(this).parents('.box').attr('data-id');
		$.sweetModal.confirm('Share', 'Sind Sie sicher, dass Sie diese Inhalte teilen möchten?<br />Ein Link wird generiert, worüber die Inhalte heruntergeladen werden können.', function(event) {
		    $.ajax({
				url: "/scripts/sharethis.php?id=" + boxid,
				method: "post"
			})
			.done(function(html) {
					loadfiles();
					$.sweetModal({
						content: 'Content is shared.<br />Share Link: <a class="sharelink" href="' + html + '" target="_blank">' + html + '</a>',
						icon: $.sweetModal.ICON_SUCCESS,
						onClose: function(sweetModal) {
							$('.dark-modal').remove();
						}
					});
  			})
  			.fail(function(html) {
	  				loadfiles();
  					$.sweetModal({
						content: 'Folder created was not successfully.',
						icon: $.sweetModal.ICON_ERROR,
						onClose: function(sweetModal) {
							$('.dark-modal').remove();
						}
					});
  				});
  			event.preventDefault();
		});
	});
	
	$('.isshared').click(function() {
		var boxid = $(this).parents('.box').attr('data-id');
		
		
		function passwordset() {
			var password = $('#sharepassword').val();
			$.ajax({
				url: "/scripts/setpasswordshare.php?id=" + boxid,
				method: "post",
				data: { password : password }
			})
			.done(function(html) {
					loadfiles();
  			});
		}
		
		$.ajax({
				url: "/scripts/isshared.php?id=" + boxid,
				method: "post"
			})
			.done(function(html) {
				var issharedcontent = html;
				$.sweetModal({
			title: {
				tab1: {
					label: 'General'
				},
		
				tab2: {
					label: 'Password'
				}
			},
		
			content: {
				tab1: issharedcontent,
				tab2: '<input type="password" id="sharepassword" placeholder="Password" name="password" /><br />'
			},
		
			buttons: {
				someOtherAction: {
					label: 'Cancel',
					classes: 'button redB bordered flat',
					action: function() {
						$('.dark-modal').remove();
					}
				},
		
				someAction: {
					label: 'Save',
					classes: '',
					action: function() {
						return passwordset();
					}
				},
			}
		});

  			});
			});
    
	$('.delete').click(function() {
		var boxid = $(this).parents('.box').attr('data-id');
		$.sweetModal.confirm('Delete', 'Are you sure you want to delete this file?', function(event) {
		    $.ajax({
				url: "/scripts/delete.php?id=" + boxid,
				method: "post"
			})
			.done(function(html) {
					loadfiles();
					$.sweetModal({
						content: 'File deleted successfully.',
						icon: $.sweetModal.ICON_SUCCESS,
						onClose: function(sweetModal) {
							$('.dark-modal').remove();
						}
					});
  			})
  			.fail(function(html) {
	  				loadfiles();
  					$.sweetModal({
						content: 'Folder created was not successfully.',
						icon: $.sweetModal.ICON_ERROR,
						onClose: function(sweetModal) {
							$('.dark-modal').remove();
						}
					});
  				});
  			event.preventDefault();
		});
	});
	$('.deletefolder').click(function() {
		var boxid = $(this).parents('.box').attr('data-id');
		$.sweetModal.confirm('Delete', 'Are you sure you want to delete this folder?', function(event) {
		    $.ajax({
				url: "/scripts/delete-folder.php?id=" + boxid,
				method: "post"
			})
			.done(function(html) {
					loadfiles();
					$.sweetModal({
						content: 'Folder deleted successfully.',
						icon: $.sweetModal.ICON_SUCCESS,
						onClose: function(sweetModal) {
							$('.dark-modal').remove();
						}
					});
  			})
  			.fail(function(html) {
	  				loadfiles();
  					$.sweetModal({
						content: 'Folder delete was not successfully.',
						icon: $.sweetModal.ICON_ERROR,
						onClose: function(sweetModal) {
							$('.dark-modal').remove();
						}
					});
  				});
  			event.preventDefault();
		});
	});
	
	$('.editbutton').click(function() {
		var boxid = $(this).parents('.box').attr('data-id');
		$.ajax({
				url: "/scripts/editdata.php?id=" + boxid,
				method: "post"
			})
			.done(function(html) {
					loadfiles();
					$.sweetModal.confirm('Edit content', html, function(event) {
						var name = $('#title').val();
						var keywords = $('#keywords').val();
						var desc = $('#desc').val();
						
					 $.ajax({
						
						url: "/scripts/saveeditdata.php?id=" + boxid,
						method: "post",
						data: { name : name, keywords : keywords, desc : desc }
					})
					.done(function(html) {
							loadfiles();
							$.sweetModal({
								content: 'Data edited successfully.',
								icon: $.sweetModal.ICON_SUCCESS,
								onClose: function(sweetModal) {
									$('.dark-modal').remove();
								}
							});
		  			})
		  			.fail(function(html) {
			  				loadfiles();
		  					$.sweetModal({
								content: 'Data edit was not successfully.',
								icon: $.sweetModal.ICON_ERROR,
								onClose: function(sweetModal) {
									$('.dark-modal').remove();
								}
							});
		  				});
		  			event.preventDefault();
				});
  			})
  			.fail(function(html) {
	  				loadfiles();
  					$.sweetModal({
						content: 'Content edit was not successfully.',
						icon: $.sweetModal.ICON_ERROR,
						onClose: function(sweetModal) {
							$('.dark-modal').remove();
						}
					});
  				});
  			event.preventDefault();
		});
	
		$('.infobutton').click(function() {
		var boxid = $(this).parents('.box').attr('data-id');
		$.ajax({
				url: "/scripts/viewdata.php?id=" + boxid,
				method: "post"
			})
			.done(function(html) {
					loadfiles();
					$.sweetModal('View content', html, function(event) {
						var name = $('#title').val();
						var keywords = $('#keywords').val();
						var desc = $('#desc').val();
					});
			})
  			.fail(function(html) {
	  				loadfiles();
  					$.sweetModal({
						content: 'Content view was not successfully.',
						icon: $.sweetModal.ICON_ERROR,
						onClose: function(sweetModal) {
							$('.dark-modal').remove();
						}
					});
  				});
  			event.preventDefault();
			
		});

    // header on that server.
	$('.titlewrapper').mouseover(function() {
		var titlewidth = $(this).children('.title').width();
        $(this).stop().animate({scrollLeft: titlewidth}, 2500);
    }).mouseout(function() {
        var titlewidth = $(this).children('.title').width();
        $(this).stop().animate({scrollLeft: '-' + titlewidth}, 2500);
	});

    $('.dz-filename').click(function() {
        var titlewidth = $('.dz-filename').width();
        $(this).stop().animate({scrollLeft: titlewidth}, 2500);
	});



});

function showpdf(url) {
    PDFJS.workerSrc = './js/pdf.worker.js';
    var loadingTask = PDFJS.getDocument(url);
    loadingTask.promise.then(function(pdf) {
        console.log('PDF loaded');

        // Fetch the first page
        var pageNumber = 1;
        pdf.getPage(pageNumber).then(function(page) {
            console.log('Page loaded');

            var scale = 1.0;
            var viewport = page.getViewport(scale);

            // Prepare canvas using PDF page dimensions
            var canvas = document.getElementById('the-canvas');
            var context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            // Render PDF page into canvas context
            var renderContext = {
                canvasContext: context,
                viewport: viewport
            };
            var renderTask = page.render(renderContext);
            renderTask.then(function () {
                console.log('Page rendered');
            });
        });
    }, function (reason) {
        // PDF loading error
        console.error(reason);
    });
}

function loadfiles() {
    var path = $('#grid').attr('data-path');
    $.ajax({
        url: "/datagrid.php?path=" + path
    }).done(function(html) {
		$("#grid").html(html);
		$('#selectinfo').animate({'marginBottom': '-60px'}, 200);
		var searchrequest = $('#domainsearch').val();
		$.ajax({
			url: './scripts/instantsearchresult.php?searchtag=' + searchrequest,
			type: 'POST',
			success: function(html1) {
				$('.aftersearch').html(html1);
			},
		});
    });
}
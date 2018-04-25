$(function() {
	$('.falsebadge, .successbadge').animate({'right': '40px', 'opacity': 1}, 500).delay(8000).animate({'right': '-373px', 'opacity': 0}, 500);
	$('.falsebadge, .successbadge').click(function() {
		$(this).delay(6000).animate({'right': '-373px', 'opacity': 0}, 500);
	});

	$('.passwordforget').click(function() {
        $.sweetModal.prompt('Password forget', 'Your E-Mail', '', function(event) {
            var foldernameinput = $('.sweet-modal-prompt input').val();
            $.ajax({
                url: "/scripts/passwordforget.php",
                method: "post",
                data: { name : foldernameinput }
            }).done(function(html) {
				if(html == 'Your password reset instructions are sent to your mail.') {
					$.sweetModal({
						content: html,
						icon: $.sweetModal.ICON_SUCCESS,
						onClose: function (sweetModal) {
							$('.dark-modal').remove();
						}
					});
				} else {
					$.sweetModal({
						content: html,
						icon: $.sweetModal.ICON_ERROR,
						onClose: function (sweetModal) {
							$('.dark-modal').remove();
						}
					});
				}
			});
        });
	});

    $('.register').click(function() {
        $.ajax({
            url: "/scripts/registerform.php",
            method: "post"
        })
            .done(function(html) {
                $.sweetModal.confirm('Register for a new account', html, function(event) {
                    var fullname = $('#fullname').val();
                    var epassword = $('#epassword').val();
                    var repassword = $('#repassword').val();
                    var email = $('#email').val();
                        $.ajax({
                            url: "/scripts/registernewaccount.php",
                            method: "post",
                            data: { fullname : fullname, epassword : epassword, repassword : repassword, email : email }
                        })
                            .done(function(html1) {
                                if(html1 == 'Thank you for your signup. You will receive a email in a few moments.') {
                                $.sweetModal({
                                    content: html1,
                                    icon: $.sweetModal.ICON_SUCCESS,
                                    onClose: function(sweetModal) {
                                        $('.dark-modal').remove();
                                    }
                                });
                                } else {
                                    $.sweetModal({
                                        content: html1,
                                        icon: $.sweetModal.ICON_ERROR,
                                        onClose: function(sweetModal) {
                                            $('.dark-modal').remove();
                                        }
                                    });
								}
                            })
                            .fail(function(html2) {
                                $.sweetModal({
                                    content: html2,
                                    icon: $.sweetModal.ICON_ERROR,
                                    onClose: function(sweetModal) {
                                        $('.dark-modal').remove();
                                    }
                                });
                            });
                });
            })
            .fail(function(html) {
                $.sweetModal({
                    content: 'Content edit was not successfully.',
                    icon: $.sweetModal.ICON_ERROR,
                    onClose: function(sweetModal) {
                        $('.dark-modal').remove();
                    }
                });
            });
    });
	
	$('.submitbutton').mouseover(function() {
		$(this).stop().animate({'opacity': '0.6'}, 150);
	}).mouseleave(function() {
		$(this).stop().animate({'opacity': '1.0'}, 150);
	});

    $( ".box .preview" ).each(function( index, element ) {
        var previewsource = $(this).attr('data-imagesource');
        $(this).backstretch(previewsource);
    });
	
	$('.picturefilter').click(function() {
		$('.arrow-up').hide();
		$('#filebox, .picturefilter .arrow-up').stop().slideToggle(function() {
			loadpicturefilter();
		});
			$(this).addClass('removepicturefilter').removeClass('picturefilter');
	});
	
	$('.removepicturefilter').click(function() {
		$(this).addClass('picturefilter').removeClass('removepicturefilter');
		$('#filebox, .picturefilter .arrow-up').stop().slideToggle();
		$('#innerfilebox').html('');
	});
	
	
	function loaddocumentfilter() {
		$.ajax({
			url: "/quickfilter.php?content=document"
		})
		.done(function(html) {
			var width = $('#measure').width();
			$("#filescroller, #innerfilebox").width(width);
			$("#filescroller").html(html);
  		});
	}
	
	$('.documentfilter').click(function() {
		$('.arrow-up').hide();
		$('#filebox, .documentfilter .arrow-up').stop().slideToggle(function() {
			$(this).addClass('removedocumentfilter').removeClass('documentfilter');
			loaddocumentfilter();
		});
	});
	
	$('.removedocumentfilter').click(function() {
		$(this).addClass('documentfilter').removeClass('removedocumentfilter');
		$('#filebox, .documentfilter .arrow-up').stop().slideToggle();
		$('#innerfilebox').html('');
	});
	
	function loadmusicfilter() {
		$.ajax({
			url: "/quickfilter.php?content=music"
		})
		.done(function(html) {
			var width = $('#measure').width();
			$("#filescroller, #innerfilebox").width(width);
			$("#filescroller").html(html);
  		});
	}
	
	$('.musicfilter').click(function() {
		$('.arrow-up').hide();
		$('#filebox, .musicfilter .arrow-up').stop().slideToggle(function() {
			$(this).addClass('removemusicfilter').removeClass('musicfilter');
			loadpicturefilter();
		});
	});
	
	$('.removemusicfilter').click(function() {
		$(this).addClass('musicfilter').removeClass('removemusicfilter');
		$('#filebox, .musicfilter .arrow-up').stop().slideToggle();
		$('#innerfilebox').html('');
	});

	$('.downloadbutton').click(function() {
		var mime = $(this).attr('data-mime');
		var id = $(this).attr('data-id');
		var sharehash = $(this).attr('data-hash');
		if(mime == 'folder') {
			$.ajax({
				url: "/scripts/downloadfolder.php?id=" + id + "&sharehash=" + sharehash
			})
			.done(function(html) {
				var zipname = html;
				window.location.href = "/scripts/zipdownload.php?zipname=" + zipname;
				$.sweetModal({
					content: 'Your download starts in a few seconds.',
					icon: $.sweetModal.ICON_SUCCESS,
					onClose: function(sweetModal) {
						$('.dark-modal').remove();
					}
				});
  			});
  		} else if(mime == 'file' || mime == 'image') {
			window.location.href = "/scripts/downloadfile.php?fileid=" + id + "&sharehash=" + sharehash;
			$.sweetModal({
				content: 'Your download starts in a few seconds.',
				icon: $.sweetModal.ICON_SUCCESS,
				onClose: function(sweetModal) {
					$('.dark-modal').remove();
				}
			});
  		}
	});

	var dropzoner = new Dropzone('.dropzoner');
	dropzoner.on("complete", function(file) {
		loadfiles();
	});
	
	Dropzone.options.dropzoner = {
		maxFilesize: 4000
	}
	
	setTimeout(function() {
		loadfiles();
	}, 0);
	
	$('.refresh').click(function() {
		loadfiles();
	});
	
	$(".goback").click(function(event) {
	    event.preventDefault();
	    history.back(1);
	});
	
	$(".goforward").click(function(event) {
	    event.preventDefault();
	    history.go(1);
	});
	
	$('.addfolder').click(function() {
	    $.sweetModal.prompt('Create a new folder', 'Foldername', '', function(event) {
		    var foldernameinput = $('.sweet-modal-prompt input').val();
		    var path = $('#grid').attr('data-path');
		    $.ajax({
				url: "/scripts/mkdir.php?path=" + path,
				method: "post",
				data: { name : foldernameinput }
			})
			.done(function(html) {
					loadfiles();
					$.sweetModal({
						content: 'Folder created successfully.',
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


    $('#nav-icon1').click(function(){
        if($('#sidebar').hasClass('sidebarexpanded')) {
			$('#sidebar').addClass('sidebarcolapsed');
            $('#sitewrapper').addClass('bodyunstash');
        } else {
            $('#sidebar').removeClass('sidebarcolapsed');
            $('#sitewrapper').removeClass('bodyunstash');
		}
        $(this).toggleClass('open');
        $('#sidebar').toggleClass('sidebarexpanded');
        $('#sitewrapper').addClass('bodystash');
    });
    var bar = new ProgressBar.SemiCircle(container, {
        strokeWidth: 6,
        color: '#FFEA82',
        trailColor: '#eee',
        trailWidth: 6,
        easing: 'easeInOut',
        duration: 1400,
        svgStyle: null,
        text: {
            value: '',
            alignToBottom: true
        },
        from: {color: '#3695b7'},
        to: {color: '#3695b7'},
        // Set default step function for all animate calls
        step: (state, bar) => {
        bar.path.setAttribute('stroke', state.color);
    var value = Math.round(bar.value() * 100);
    if (value === 0) {
        bar.setText('');
    } else {
        var mbtotal = $('#container').attr('data-mbtotal');
        var mbused = $('#container').attr('data-usedspace');
        bar.setText('<div class="wrapset"><span class="set">'+ value + '%</span><span class="useddisk"></span></div>');
        $('.useddisk').html(mbused + ' verwendet');
    }

    bar.text.style.color = state.color;
	}
	});
    bar.text.style.fontFamily = '"Yantramanav", Helvetica, sans-serif';
    bar.text.style.fontSize = '2rem';
    var totalvalue = $('#container').attr('data-totalspace');
    bar.animate(totalvalue);

    $('#domainsearch, #searchfieldinner').click(function() {
		$('#searchfield').fadeIn(function() {
			$('#searchfield').animate({'height': '190px'}, 500, function() {
                $('#searchfieldinner, .close').fadeIn();
			});
		});
	});

    $('article, .closesearch').click(function() {
        $('#searchfieldinner, .close').stop().fadeOut(500, function() {
			$('#searchfield').stop().animate({'height': '1px'}, 500, function() {
				$('#searchfield').stop().fadeOut();
			});
        });
    });

    $('.lastsearchrequest').click(function() {
		var lastsearch = $(this).children('h4').html();
		$('#domainsearch').val(lastsearch);
        var searchrequest = lastsearch;

        $.ajax({
            url: './scripts/instantsearchresult.php?searchtag=' + searchrequest,
            type: 'POST',
            success: function(html) {
                $('.beforesearch').fadeOut(500, function() {
                    $('.aftersearch').html(html);
                    $('.aftersearch').fadeIn();
                });
            },
        });
	});

    $('#domainsearch').bind("enterKey",function(e){
    });
    $('#domainsearch').keyup(function(e){
    	var domainsearchlength = $('#domainsearch').val().length;
    	if(domainsearchlength == 0) {
            $('.aftersearch').fadeOut(500, function() {
                $('.beforesearch').fadeIn();
            });
    	}
        if(e.keyCode == 13)
        {
            $(this).trigger("enterKey");
            var searchrequest = $(this).val();
            $.ajax({
                url: './scripts/insertsearchresult.php?searchtag=' + searchrequest,
                type: 'POST',
                context: this,
                success: function() {

				},
            });
            $.ajax({
                url: './scripts/instantsearchresult.php?searchtag=' + searchrequest,
                type: 'POST',
                success: function(html) {
                    $('.beforesearch').fadeOut(500, function(event) {
                        $('.aftersearch').html(html);
                        $('.aftersearch').fadeIn();

                    });
                },
            });
        }
    });

});

function loadfiles() {
    var path = $('#grid').attr('data-path');
    $.ajax({
        url: "/datagrid.php?path=" + path
    })
        .done(function(html) {
            $("#grid").html(html);
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

function loadpicturefilter() {
    $.ajax({
        url: "/quickfilter.php?content=picture"
    })
        .done(function(html) {
            var width = $('#measure').width();
            $("#filescroller, #innerfilebox").width(width);
            $("#filescroller").html(html);
        });
}
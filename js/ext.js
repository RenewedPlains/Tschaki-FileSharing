$(function() {
    $('.uploadbutton').bind( "click", function( event ) {
        if($('#sidebar').hasClass('sidebarexpanded')) {
            $('#sidebar').addClass('sidebarcolapsed');
            $('#sitewrapper').addClass('bodyunstash');
        } else {
            $('#sidebar').removeClass('sidebarcolapsed');
            $('#sitewrapper').removeClass('bodyunstash');
        }
        $('#nav-icon1').toggleClass('open');
        $('#sidebar').toggleClass('sidebarexpanded');
        $('#sitewrapper').addClass('bodystash');
    });


    $('body').on({'dragover dragenter': function(e) {
        if ($('#sidebar').hasClass('sidebarexpanded')) {
        } else {
            $('#sidebar').removeClass('sidebarcolapsed');
            $('#sitewrapper').removeClass('bodyunstash');
        }
        $('#nav-icon1').addClass('open');
        $('#sidebar').addClass('sidebarexpanded');
        $('#sitewrapper').addClass('bodystash');
        e.preventDefault();
        e.stopPropagation();
    }
    });

    $('.sharedcontent').click(function() {
        $.ajax({
            url: "/scripts/sharedfiles.php?id="
        }).done(function(html) {
            $.sweetModal({
                title: 'Your shared files & folders',
                content: html,
                onClose: function(sweetModal) {
                    $('.dark-modal').remove();
                }
            });
        });
    });

    $('.selectall').click(function() {
        if($('.selecter:checked').length != 0) {
            $('.selecter').each(function (index) {
                $(this).prop('checked', false);
                $(this).parents('.iconbar').removeClass('marked');
                $(this).attr('data-checker', '');
                $('#selectinfo').animate({'marginBottom': '-60px'}, 200);
                $('.iconbar').fadeOut();
            });
        } else {
            $('.selecter').each(function (index) {
                $(this).prop('checked', true);
                $(this).parents('.iconbar').addClass('marked');
                $('#selectinfo').animate({'marginBottom': '60px'}, 200);
                $(".innerselectinfo .left").text('Selected items: ' + index);
                $(this).attr('data-checker', 'mark');
                $('.iconbar').fadeIn();
            });
        }
    });

    $('.settings').click(function() {
        $.ajax({
            url: "../scripts/accountsettings.php",
            method: "post"
        }).done(function(html) {
            var issharedcontent = html;
                $.ajax({
                    url: "../scripts/generalsettings.php",
                    method: "post"
                }).done(function (html1) {
                    gene = html1;
                });
                $.ajax({
                    url: "../scripts/userview.php",
                    method: "post"
                }).done(function (html2) {
                    user = html2;
                });

                $.sweetModal({
                    title: {
                        tab1: {
                            label: 'Account'
                        },

                        tab2: {
                            label: 'General'
                        },

                        tab3: {
                            label: 'Users'
                        }
                    },

                    content: {
                        tab1: issharedcontent,
                        tab2: gene,
                        tab3: user
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
                                var epasswordforget = $("#epasswordforget").val();
                                var repasswordforget = $("#repasswordforget").val();
                                var hash = 'usersetting';
                                $.ajax({
                                    url: "scripts/accountsettings.php",
                                    method: "post",
                                    data: { hash : hash, epasswordforget : epasswordforget, repasswordforget : repasswordforget }
                                }).done(function(html1) {
                                    if (html1 == "Your password is now resetet.") {
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

                                });}
                        },
                    }
                });

            });

    });
});
jQuery(document).ready(function($){

	$('#view-format a').on( 'click', function() {
		if ( $(this).hasClass('active') ) return;
		new_style = $(this).data('style');
		$.cookie('largo_homepage_style', new_style, { expires: 365, path: '/' });
		$('#view-format a').removeClass('active');
		$(this).addClass('active');
		changeStyle( new_style );
		return false;
	});

	//fetch the cookie if present
	var largo_homepage_style = $.cookie('largo_homepage_style');
	if ( largo_homepage_style ) {
		$("#view-format a[data-style='"+largo_homepage_style+"']").trigger('click');
	}

	function changeStyle( style ) {
		//show/hide stuff on the page based on the layout style
		$('body').toggleClass('view-list');
		switch ( style ) {
			case "top":
				$('#home-river:visible').fadeOut('fast');
				$('.home-top, #home-secondary').filter(':hidden').slideDown('fast');
				break;
			case "list":
				$('#home-river:hidden').fadeIn('fast');
				$('.home-top, #home-secondary').filter(':visible').slideUp('fast');
				break;
		}
	}

	//height setting for the main div. This is awkward
	function setFeaturedHeight() {
		$('.full-hero, .home #dark-top .row-fluid').removeAttr('style');

		if ( $(window).width() > 480 ) {
			var col_height = $('.home #dark-top .row-fluid').height(),
				pane_height = $('#homepage-featured').height();

			if ( col_height +25 > pane_height ) $('.full-hero').height( col_height + 25 );
			else {
				$('.home #dark-top .row-fluid').height( pane_height );
			}
		}
	}

	$(window).on('resize', function() {
		setFeaturedHeight();
	});

	$(window).trigger('resize');

});
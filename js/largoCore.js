jQuery(document).ready(function($) {

	//html5 placeholders
	$("input[placeholder]").textPlaceholder();

	// dim sidebar
	$(window).scroll(function(){
		if (($(window).scrollTop() > 300)) {
			$('.showey-hidey').animate({opacity: '0.5'}, 100);
		} else if (($(window).scrollTop() < 300)) {
			$('.showey-hidey').animate({opacity: '1'}, 100);
		}
	});

	// make the sidebar visible if you hover over it
	$('.showey-hidey').hover(function() {
		$(this).animate({opacity: '1'}, 100);
		}, function() {
			if (($(window).scrollTop() < 300)) {
				$(this).animate({opacity: '1'}, 100);
			} else {
			    $(this).animate({opacity: '0.5'}, 100);
			};
	});

	$('.header_img').attr('src', banner_img_src);

	$(window).resize(function() {
		$('.header_img').attr('src', whichHeader());
	});

	$('.carousel').carousel({
	  interval: 6000
	});

});
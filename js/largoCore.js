jQuery(document).ready(function($) {

	//html5 placeholders
	$('input[placeholder], textarea[placeholder]').placeholder();

	// dim sidebar
	$(window).scroll(function(){
		if (($(this).scrollTop() > 300) && (document.documentElement.clientWidth >= 767)) {
			$('.showey-hidey').animate({opacity: '0.5'}, 100);
		} else if (($(this).scrollTop() < 300)) {
			$('.showey-hidey').animate({opacity: '1'}, 100);
		}
	});

	// make the sidebar visible if you hover over it
	if (document.documentElement.clientWidth >= 767) {
		$('.showey-hidey').hover(function() {
			$(this).animate({opacity: '1'}, 100);
			}, function() {
				if ($(window).scrollTop() < 300) {
					$(this).animate({opacity: '1'}, 100);
				} else {
				    $(this).animate({opacity: '0.5'}, 100);
				}
		});
	}

	//get the correct sized image for the header, replace it with a new one if the window is resized
	$('.header_img').attr('src', banner_img_src);
	$(window).resize(function() {
		$('.header_img').attr('src', whichHeader());
	});

	//the homepage carousel, make sure we don't load this unless .carousel is defined
	if($().carousel) {
		$('.carousel').carousel({
			interval: 6000
		});
	}

	//enable "clean read" functionality
	$('a.clean-read').on('click', function() {
		$('body').addClass('clean-read').append('<a class="clean-read-close" href="#">Exit "Clean Read" mode</a>');
		$('.clean-read-container').append('<a class="clean-read-close" href="#">Exit "Clean Read" mode</a>');
		$('a.clean-read').hide();
		return false;
	});

	//close "clean read"
	$(document).on('click', '.clean-read-close', function() {
		$('body').removeClass('clean-read');
		$('a.clean-read').show();
		$('.clean-read-close').remove();
		return false;
	});

	//ESC triggers "clean read" close
	$(document).keyup(function(e) {
    if (e.keyCode == 27 && $('body').hasClass('clean-read')) $('.clean-read-close').trigger('click');
  });

  //GA event tracking for image-widget items
  $('a.image-click-track').on('click', function() {
	  if (typeof _gaq == 'object') _gaq.push(['_trackEvent', 'Click', 'Image Widget', this.getAttribute('title')]);
  });

});
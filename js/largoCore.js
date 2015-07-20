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

	// get the correct sized image for the header, replace it with a new one if the window is resized.
	// if `banner_img_src` is empty, remove it and its parent container
	if (banner_img_src) {
		$('.header_img').attr('src', banner_img_src);
		$(window).resize(function() {
			$('.header_img').attr('src', whichHeader());
		});
	} else {
		$('.header_img').parent().remove();
	}

	//the homepage carousel, make sure we don't load this unless .carousel is defined
	if($().carousel) {
		$('.carousel').carousel({
			interval: 6000
		});
	}

	//enable "clean read" functionality
	$('a.clean-read').on('click', function() {
		$('body').addClass('clean-read').find(".sticky-footer-container").append('<a class="clean-read-close" href="#">Exit "Clean Read" mode</a>');
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

  // Touch enable the drop-down menus
  (function() {
    if (Modernizr.touch) {
      // iOS Safari works with touchstart, the rest work with click
      var mobileEvent = /Mobile\/.+Safari/.test(navigator.userAgent) ? 'touchstart' : 'click',
          // Open the drop down
          openMenu = false;

      // Handle the tap for the drop down
      $('ul.nav').on(mobileEvent + '.largo', 'li', function(event) {
        var li = $(event.currentTarget);

        if (!li.hasClass('dropdown')) {
          window.location.href = li.find('a').attr('href');
          event.preventDefault();
          event.stopPropagation();
          return false;
        }

        if (!li.is('.open')) {
          // The link when the menu is closed
          closeOpenMenu();
          li.addClass('open');
          openMenu = li;

          event.preventDefault();
          event.stopPropagation();
        } else if ($(event.target).is('b.caret')) {
          // The caret when the menu is open
          li.removeClass('open');
          openMenu = false;

          event.preventDefault();
          event.stopPropagation();
        }
      });

      // Call this to call the open menu
      var closeOpenMenu = function() {
        if (openMenu) {
          openMenu.removeClass('open');
          openMenu = false;
        }
      }

      // Close the open menu when the user taps elsewhere
      $('body').on(mobileEvent, closeOpenMenu);
    }
  })();

	// Sticky header and footer
	(function(){
		var stickyNavEl = $('.sticky-nav-holder');
		var mainEl = $('.home #main');

		if (mainEl.length) {
			mainEl.waypoint(function(direction) {
				if ($(window).width() <= 768)
					return false;

				stickyNavEl.toggleClass('show', direction == 'down');
				stickyNavEl.data('hideAtTop', true);
			}, {
				offset: $('#wpadminbar').height() + parseInt(mainEl.css('marginTop'))
			});

			if ($(window).width() <= 768) {
				stickyNavEl.addClass('show');
				stickyNavEl.data('hideAtTop', false);
			}

			$(window).on('resize', function() {
				if ($(window).width() <= 768) {
					stickyNavEl.addClass('show');
					stickyNavEl.data('hideAtTop', false);
				} else {
					if ($(window).scrollTop() <= mainEl.offset().top)
						stickyNavEl.removeClass('show');

					stickyNavEl.data('hideAtTop', true);
				}
			});
		}

		// Account for sticky nav with fixed position at top of page
		var stickyNavWrapper = $('.sticky-nav-wrapper');
		if (stickyNavWrapper.length && !$('body').hasClass('home'))
			stickyNavWrapper.height(stickyNavEl.outerHeight());

		// Check if there is a sticky footer
		var stickyFooterEl = $( '.sticky-footer-holder' );
		if ( stickyFooterEl.length ) {
			// Show the sticky footer by default
			stickyFooterEl.addClass( 'show' );

			$('#site-footer').waypoint( function( direction ) {
				stickyFooterEl.toggleClass( 'show', direction == 'up' );
			}, { offset: '100%' } );

			$('.dismiss a').on( 'click', function() {
				stickyFooterEl.remove();	//so it never comes back
				return false;
			});
		}
	})();


	// Custom share buttons
	(function() {
		var sharer = {
			// Initialize the singleton object
			init: function() {
				this.buttons = $('.custom-share-button');

				if ( this.buttons.length == 0 ) {
					// Abort if no buttons
					return;
				}

				this.buttons.on( 'click', $.proxy( this, 'onClick' ) );
			},

			// Get the url, title, and description of the page
			getPageData: function() {
				if ( !this._data ) {
					// Cache the data after the first get
					var metaElements = $('meta');

					this._data = {};
					this._data.title = metaElements.filter('[property="og:title"]').attr('content') || document.title;
					this._data.url = metaElements.filter('[property="og:url"]').attr('content') || document.location;
					this._data.description = metaElements.filter('[property="og:description"]').attr('content') || metaElements.filter('[name="description"]').attr('content');
					// this._data.siteName = ; // Print the value as a Javascript var
				}

				return this._data;
			},

			// Event handler for the share buttons
			onClick: function( event ) {
				var button = $(event.target);
				var service = button.data('service');

				if ( this['do_'+service] ) {
					this['do_'+service]( this.getPageData() );
				}

				return false;
			},

			// Handle the Twitter service
			do_twitter: function( data ) {

				var url = 'https://twitter.com/intent/tweet?' + $.param({
					original_referer: document.title,
					text: data.title,
					url: data.url
				});

				this.popup({
					url: url,
					name: 'twitter_share'
				});
			},

			// Handle the Facebook service
			do_facebook: function( data ) {
				var url = 'https://www.facebook.com/sharer/sharer.php?' + $.param({
					u: data.url
				});

				this.popup({
					url: url,
					name: 'facebook_share'
				});
			},

			// Handle the email service
			do_email: function( data ) {
				var url = 'mailto:friend@example.com?' + $.param({
					subject: data.title,
					body: data.description + "\n" + data.url
				});

				this.popup({
					url: url,
					name: 'email_share'
				});
			},

			// Handle the Google+ service
			do_googleplus: function( data ) {
				var url = 'https://plus.google.com/share?' + $.param({
					url: data.url
				});

				this.popup({
					url: url,
					name: 'googleplus_share'
				});
			},

			// Handle the LinkedIn service
			do_linkedin: function( data ) {
				var url = 'http://www.linkedin.com/shareArticle?' + $.param({
					mini: 'true',
					url: data.url,
					title: data.title,
					summary: data.description
					// source: data.siteName
				});

				this.popup({
					url: url,
					name: 'linkedin_share'
				});
			},

			// Create and open a popup
			popup: function( data ) {
				if ( !data.url ) {
					return;
				}

				$.extend( data, {
					name: '_blank',
					height: 608,
					width: 845,
					menubar: 'no',
					status: 'no',
					toolbar: 'no',
					resizable: 'yes',
					left: Math.floor(screen.width/2 - 845/2),
					top: Math.floor(screen.height/2 - 608/2)
				});

				var specNames = 'height width menubar status toolbar resizable left top'.split( ' ' );
				var specs = [];
				for( var i=0; i<specNames.length; ++i ) {
					specs.push( specNames[i] + '=' + data[specNames[i]] );
				}
				return window.open( data.url, data.name, specs.join(',') );
			}
		};

		sharer.init();
	})();

	// Search slide out for mobile
    (function() {
        var searchForm = $('.sticky-nav-holder .form-search');
        var toggle = searchForm.parent().find('.toggle');
        toggle.on('click', function() {
            searchForm.parent().toggleClass('show');
            return false;
        });
    })();

	// Responsive navigation
	$('.navbar .toggle-nav-bar').each(function() {
		var toggleButton = $(this);
		var navbar = toggleButton.closest('.navbar');

		// Support both touch and click events
		toggleButton.on('touchstart.toggleNav', function() {
			// If it is a touch event, get rid of the click events.
			toggleButton.off('click.toggleNav');
			navbar.toggleClass('open');

			// Close all the open sub navigation upon closing the menu
			if (!navbar.hasClass('open'))
				navbar.find('.nav-shelf li.open').removeClass('open');
		});

		toggleButton.on('click.toggleNav', function() {
			navbar.toggleClass('open');
		});

		// Secondary nav
		navbar.on('touchstart.toggleNav click.toggleNav', '.nav-shelf .dropdown-toggle', function(event) {
			// Only handle when
			if (toggleButton.css('display') == 'none')
				return;

			if (event.type == 'touchstart')
				navbar.off('click.toggleNav', '.nav-shelf .dropdown-toggle');

			var li = $( event.target ).closest('li');

			// Close the others if we are opening
			if (!li.hasClass('open'))
				navbar.find('.nav-shelf li.open').removeClass('open');

			// Open ours
			li.toggleClass('open');
			event.preventDefault();
		});
	});
});

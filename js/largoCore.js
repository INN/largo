jQuery(document).ready(function($) {

  //html5 placeholders
  $('input[placeholder], textarea[placeholder]').placeholder();

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

  //GA event tracking for image-widget items
  $('a.image-click-track').on('click', function() {
    if (typeof _gaq == 'object') _gaq.push(['_trackEvent', 'Click', 'Image Widget', this.getAttribute('title')]);
  });

  // Custom share buttons
  (function() {
    window.largo_sharer = {
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
        var button = $(event.currentTarget);
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

    window.largo_sharer.init();
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

  // Popovers
  $('body').on('click', '.popover-toggle', function(event) {
    event.stopPropagation();

    var popover = $(this).siblings('.popover');

    if (popover.css('display') == 'none') {
      $(this).addClass('popped');
      popover.show();
    } else {
      $(this).removeClass('popped');
      popover.hide();
    }

    return false;
  });

  $('html').click(function() {
    $('.popover').each(function() {
      if ($(this).css('display') != 'none') {
        $(this).hide();
      }
    });
    $('.popped').removeClass('popped');
  });

  // Utilities
  var debounce = function(func, wait, immediate) {
    var timeout;
    return function() {
      var context = this, args = arguments;
      var later = function() {
        timeout = null;
        if (!immediate) func.apply(context, args);
      };
      var callNow = immediate && !timeout;
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
      if (callNow) func.apply(context, args);
    };
  };

  if (typeof window.debounce == 'undefined')
    window.debounce = debounce;
});

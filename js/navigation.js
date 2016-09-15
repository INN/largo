(function() {
  var $ = jQuery;

  var Navigation = function() {
    this.scrollTop = $(window).scrollTop();
    this.previousScroll = null;
    this.initialLoad = true;
    return this.init();
  };

  Navigation.prototype.init = function() {
    // Dropdowns on touch screens
    this.enableMobileDropdowns();

    // Stick navigation
    this.stickyNavEl = $('.sticky-nav-holder');
    this.stickyNavWrapper = $('.sticky-nav-wrapper');
    this.mainEl = $('#main');
    this.mainNavEl = $('#main-nav');

    if ($(window).width() > 768) {
      this.stickyNavTransition();
    }

    // Bind events
    this.bindEvents();

    // Deal with long/wrapping navs
    setTimeout(this.navOverflow.bind(this), 0);

    // Sticky nav on small viewports
    this.responsiveNavigation();

    return this;
  };

  Navigation.prototype.enableMobileDropdowns = function () {
    // Touch enable the drop-down menus
    if (Modernizr.touch) {
      // iOS Safari works with touchstart, the rest work with click
      var mobileEvent = /Mobile\/.+Safari/.test(navigator.userAgent) ? 'touchstart' : 'click',
      // Open the drop down
      openMenu = false;

      // Call this to close the open menu
      var closeOpenMenu = function() {
        if (openMenu) {
          openMenu.removeClass('open');
          openMenu = false;
        }
      }

      // Close the open menu when the user taps elsewhere
      $('body').on(mobileEvent, closeOpenMenu);
    }
  };

  Navigation.prototype.bindEvents = function() {
    $(window).resize(this.navOverflow.bind(this));
    this.bindStickyNavEvents();
  };

  Navigation.prototype.bindStickyNavEvents = function() {
    var self = this;

    // This is so that we may apply styles to the navbar based on what options are set
    // This is used with some styles in less/inc/navbar-sticky.less
    $.each(Largo.sticky_nav_options, function(idx, opt) {
      if (opt)
        self.stickyNavEl.addClass(idx);
    });

    $(window).on('scroll', this.stickyNavScrollCallback.bind(this));
    $(window).on('resize', this.stickyNavResizeCallback.bind(this));

    this.stickyNavResizeCallback();
    this.stickyNavSetOffset();
  };

  // Hide the sticky nav if we're too close to the top of the page
  Navigation.prototype.stickyNavScrollTopHide = function() {
    if ($(window).scrollTop() <= this.mainEl.offset().top && this.mainNavEl.is(':visible')) {
      this.stickyNavEl.removeClass('show');
      clearTimeout(this.scrollTimeout);
      return;
    }
  }

  Navigation.prototype.stickyNavResizeCallback = function() {
    if (
      $(window).width() <= 768 ||
      ( Largo.sticky_nav_options.main_nav_hide_article && ($('body').hasClass('single') || $('body').hasClass('page')) )
    ) {
      this.stickyNavEl.addClass('show');
      this.stickyNavEl.parent().css('height', this.stickyNavEl.outerHeight());
    } else if (
      Largo.sticky_nav_options.sticky_nav_display
    ) {
      this.stickyNavScrollTopHide();
      this.stickyNavEl.parent().css('height', '');
    } else {
      this.stickyNavEl.parent().css('height', '');
    }
    this.stickyNavSetOffset();
    this.stickyNavTransitionDone();
  };

  Navigation.prototype.stickyNavScrollCallback = function(event) {
    if ($(window).scrollTop() < 0 || ($(window).scrollTop() + $(window).outerHeight()) >= $(document).outerHeight()) {
      return;
    }

    var self = this,
        direction = this.scrollDirection(),
        callback, wait;

    this.stickyNavScrollTopHide();

    this.stickyNavSetOffset();

    // Abort if the scroll direction is the same as it was, or if the page has not been scrolled.
    if (this.previousScroll == direction || !this.previousScroll ) {
      this.previousScroll = direction;
      return;
    }

    clearTimeout(this.scrollTimeout);

    if (direction == 'up') {
      callback = this.stickyNavEl.addClass.bind(this.stickyNavEl, 'show'),
      wait = 250;
    } else if (direction == 'down') {
      callback = this.stickyNavEl.removeClass.bind(this.stickyNavEl, 'show');
      wait = 500;
    }

    this.scrollTimeout = setTimeout(callback, wait);
    this.previousScroll = direction;
  };

  Navigation.prototype.scrollDirection = function() {
    var scrollTop = $(window).scrollTop(),
        direction;

    if (scrollTop > this.scrollTop)
      direction = 'down';
    else
      direction = 'up';

    this.scrollTop = scrollTop;
    return direction;
  };

  Navigation.prototype.stickyNavSetOffset = function() {
    if ($('body').hasClass('admin-bar')) {
      if ($(window).scrollTop() <= $('#wpadminbar').outerHeight()) {
        this.stickyNavEl.css('top', $('#wpadminbar').outerHeight());
      } else {
        this.stickyNavEl.css('top', '');
      }
    }
  };

  Navigation.prototype.responsiveNavigation = function() {
    var self = this;

    // Responsive navigation
    $('.navbar .toggle-nav-bar').each(function() {
      var toggleButton = $(this),
          navbar = toggleButton.closest('.navbar');

      // Support both touch and click events
      toggleButton.on('touchstart.toggleNav click.toggleNav', function(event) {
        // If it is a touch event, get rid of the click events.
        if (event.type == 'touchstart') {
          toggleButton.off('click.toggleNav');
        }

        navbar.toggleClass('open');
        $('html').addClass('nav-open');
        self.stickyNavSetOffset();
        navbar.find('.nav-shelf').css({
          top: self.stickyNavEl.position().top + self.stickyNavEl.outerHeight()
        });

        if (!navbar.hasClass('open')) {
          navbar.find('.nav-shelf li.open').removeClass('open');
          $('html').removeClass('nav-open');
        }

        return false;
      });

      // Secondary nav
      navbar.on('touchstart.toggleNav click.toggleNav', '.nav-shelf .caret', function(event) {
        if (toggleButton.css('display') == 'none')
          return false;

        if (event.type == 'touchstart') {
          navbar.off('click.toggleNav', '.nav-shelf .dropdown-toggle');
        }

        var li = $( event.target ).closest('li');

        if (!li.hasClass('open')) {
          navbar.find('.nav-shelf li.open').removeClass('open');
        }

        li.toggleClass('open');
        return false;
      });
    });
  };

  /**
   * On window resize, make sure nav doesn't overflow.
   * Put stuff in the overflow nav if it does.
   *
   * Event should fire enough that we can do one at a time
   * and be ok.
   *
   * @since Largo 0.5.1
   */
  Navigation.prototype.navOverflow = function() {
    var nav = $('#sticky-nav');

    if (!nav.is(':visible') || $(window).width() <= 768) {
      this.revertOverflow();
      return;
    }

    var shelf = nav.find('.nav-shelf'),
        button = nav.find('.toggle-nav-bar'),
        right = nav.find('.nav-right'),
        shelfWidth = shelf.outerWidth(),
        rightWidth = right.outerWidth(),
        caretWidth = nav.find('.caret').first().outerWidth(),
        windowWidth = $(window).width(),
        isMobile = button.is(':visible');

    if (!isMobile) {
      if (!this.stickyNavEl.hasClass('transitioning')) {
        this.stickyNavTransition();
      }

      /*
       * Calculate the width of the nav
       */
      var navWidth = 0;
      shelf.find('ul.nav > li').each( function() {
        if ($(this).is(':visible'))
          navWidth += $(this).outerWidth();
      });

      var overflow = shelf.find('ul.nav > li#menu-overflow.menu-item-has-children').last();

      if (!isMobile && navWidth > shelfWidth - rightWidth - caretWidth) {
        /*
         * If there is no "overflow" menu item, create one
         *
         * This is where you change the word from "More" to something else.
         */
        if (overflow.length == 0) {
          var overflowmenu ='<li id="menu-overflow" class="menu-item-has-children dropdown">' +
            '<a href="#" class="dropdown-toggle">' + Largo.sticky_nav_options.nav_overflow_label + '<b class="caret"></b></a>' +
            '<ul id="menu-more-1" class="dropdown-menu"></ul></li>';
          overflow = $(overflowmenu);
          overflow.find('a').click(function() { return false; });
          shelf.find('ul.nav > li.menu-item').last().after(overflow);
        }

        var li = shelf.find('ul.nav > li.menu-item').last();

        overflow.find('ul#menu-more-1').prepend(li);
        li.addClass('overflowed');
        li.data('shelfwidth', shelfWidth);
      } else if (overflow.length) {
        /*
         * Put items back on the main sticky menu and empty out the overflow nav menu if necessary.
         */
        var li = overflow.find('li').first();

        if (li.hasClass('overflowed')) {
          if (li.data('shelfwidth') < shelfWidth) {
            shelf.find('ul.nav > li.menu-item').last().after(li);

            // Remove the "More" menu if there are no items in it.
            if (overflow.find('ul li').length == 0) {
              overflow.remove();
            }
          }
        }
      }

      /*
       * Re-calculate the width of the nav after adding/removing overflow items.
       *
       * If the nav is still wrapping, call navOverflow again.
       */
      var navWidth = 0;
      shelf.find('ul.nav > li').each( function() {
        if ($(this).is(':visible'))
          navWidth += $(this).outerWidth();
      });
      shelfWidth = shelf.outerWidth(),
      rightWidth = right.outerWidth();

      if (!isMobile && navWidth > shelfWidth - rightWidth - caretWidth) {
        if (typeof this.navOverflowTimeout !== 'undefined')
          clearTimeout(this.navOverflowTimeout);
        this.navOverflowTimeout = setTimeout(this.navOverflow.bind(this), 0);
        return;
      }
    }

    this.stickyNavTransitionDone();
  };

  Navigation.prototype.stickyNavTransition = function() {
    if (!this.stickyNavEl.hasClass('transitioning')) {
      this.stickyNavEl.addClass('transitioning');
    }
  };

  Navigation.prototype.stickyNavTransitionDone = function() {
    var self = this;

    if (typeof this.stickyNavTransitionTimeout !== 'undefined')
      clearTimeout(this.stickyNavTransitionTimeout);

    this.stickyNavTransitionTimeout = setTimeout(function() {
      if (self.stickyNavEl.hasClass('transitioning'))
        self.stickyNavEl.removeClass('transitioning');
    }, 500);
  };

  Navigation.prototype.revertOverflow = function() {
    var nav = $('#sticky-nav'),
        self = shelf = nav.find('.nav-shelf'),
        overflow = shelf.find('ul.nav > li#menu-overflow.menu-item-has-children').last();

    overflow.find('li.overflowed').each(function(idx, li) {
      shelf.find('ul.nav > li.menu-item').last().after(li);
    });

    if (overflow.find('ul li').length == 0 ) {
      overflow.remove();
    }
  };

  if (typeof window.Navigation == 'undefined')
    window.Navigation = Navigation;

  $(document).ready(function() {
    new Navigation();
  });

})();

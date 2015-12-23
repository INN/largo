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
  };

  Navigation.prototype.bindEvents = function() {
    $(window).resize(this.navOverflow.bind(this));
    this.bindStickyNavEvents();
  };

  Navigation.prototype.bindStickyNavEvents = function() {
    var self = this;

    $.each(Largo.sticky_nav_options, function(idx, opt) {
      if (opt)
        self.stickyNavEl.addClass(idx);
    });

    $(window).on('scroll', this.stickyNavScrollCallback.bind(this));
    $(window).on('resize', this.stickyNavResizeCallback.bind(this));

    this.stickyNavResizeCallback();
    this.stickyNavSetOffset();
  };

  Navigation.prototype.stickyNavResizeCallback = function() {
    if ($(window).width() <= 768 || (Largo.sticky_nav_options.main_nav_hide_article && $('body').hasClass('single'))) {
      this.stickyNavEl.addClass('show');
      this.stickyNavEl.parent().css('height', this.stickyNavEl.outerHeight());
    } else {
      this.stickyNavEl.parent().css('height', '');
    }
    this.stickyNavSetOffset();
    this.stickyNavTransitionDone();
  };

  Navigation.prototype.stickyNavScrollCallback = function(event) {
    var self = this,
        direction = this.scrollDirection(),
        callback, wait;

    if ($(window).scrollTop() <= this.mainEl.offset().top && this.mainNavEl.is(':visible')) {
      this.stickyNavEl.removeClass('show');
      clearTimeout(this.scrollTimeout);
      return;
    }

    this.stickyNavSetOffset();

    if (this.previousScroll == direction) {
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
      navbar.on('touchstart.toggleNav click.toggleNav', '.nav-shelf .caret', function(event) {
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

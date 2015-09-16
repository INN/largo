<?php
/**
 * Sticky Navigation Menu
 *
 * Applied on all pages after a user scrolls past the Main Navigation or affixed
 * to the top of most pages that aren't the home page.
 *
 * @package Largo
 *
 */
 ?>
<div class="sticky-nav-wrapper nocontent">
	<div class="sticky-nav-holder
	          <?php echo (is_front_page() || is_home()) ? '' : 'show'; ?>"
		data-hide-at-top="
		<?php echo (is_front_page() || is_home()) ? 'true' : 'false'; ?>">

		<?php
		/**
		 * Allow hooking of sticky Navigation Container
		 *
		 * Use add_action( 'largo_before_sticky_nav_container', 'function_to_add');
		 *
		 * @link https://codex.wordpress.org/Function_Reference/add_action
		 */

		do_action( 'largo_before_sticky_nav_container' ); ?>

		<div class="sticky-nav-container">
			<nav id="sticky-nav" class="sticky-navbar navbar clearfix">
				<div class="container">
					<div class="nav-right">
					<?php
					/* Display social icons. Enabled by default, toggle in Theme Options
					 * under the Basic Settings tab under Menu Options.
					 *
					 * @link https://largo.readthedocs.org/users/themeoptions.html
					 */
					if ( of_get_option( 'show_header_social') ) { ?>
						<ul id="header-social" class="social-icons visible-desktop">
							<?php largo_social_links(); ?>
						</ul>
					<?php } ?>

						<ul id="header-extras">
							<?php
							/* Display Donate button. Change button text and URL in Theme
							 * Options under the Basic Settings tab under Donate Button.
							 *
							 * @link https://largo.readthedocs.org/users/themeoptions.html
							 */
							if ( of_get_option( 'show_donate_button') ) {
								if ($donate_link = of_get_option('donate_link')) { ?>
								<li class="donate">
									<a class="donate-link"
									   href="<?php echo esc_url($donate_link); ?>">
										<span><i class="icon-heart"></i>
											<?php echo esc_html(
											           of_get_option('donate_button_text')); ?>
									  </span>
									</a>
								</li>
						<?php
								}
							} ?>
							<li id="sticky-nav-search">
								<a href="#" class="toggle">
									<i class="icon-search"
									   title="<?php esc_attr_e('Search', 'largo'); ?>"
										 role="button"></i>
								</a>
								<form class="form-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
									<div class="input-append">
										<span class="text-input-wrapper">
											<input type="text" placeholder="<?php esc_attr_e('Search', 'largo'); ?>"
												class="input-medium appendedInputButton search-query" value="" name="s" />
										</span>
										<button type="submit" class="search-submit btn"><?php _e('Go', 'largo'); ?></button>
									</div>
								</form>
							</li>
						</ul>

					</div>

					<!-- .btn-navbar is the toggle for collapsed navbar content -->
					<a class="btn btn-navbar toggle-nav-bar"
					   title="<?php esc_attr_e('More', 'largo'); ?>">
						<div class="bars">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</div>
					</a>
        <!-- BEGIN MOBILE MENU (hidden on desktop) -->
					<div class="nav-left">
					 <?php
              /* IF the Show Site Name in Sticky Nav option is enabled
               * in Theme Options under Basic Settings tab (Menu
               * Options section).
               * THEN check opt for logo.
               * OTHERWISE use Home Icon.
               */
						if ( of_get_option( 'show_sitename_in_sticky_nav', 1 ) ) { ?>
								<li class="site-name">
                  <a href="/"><?php get_bloginfo('name'); ?></a>
                </li>
						<?php } else if ( of_get_option( 'sticky_header_logo' ) == '' ) { ?>
								<li class="home-link">
                  <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                      <?php largo_home_icon( 'icon-white' ); ?>
                  </a>
                </li>
						<?php } else { ?>
								<li class="home-icon">
                  <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <?php largo_home_icon( 'icon-white' , 'medium' ); ?>
                  </a>
                </li>
					 <?php } ?>
					</div>
        <!-- END MOBILE MENU -->
        <!-- BEGIN DESKTOP MENU -->
					<div class="nav-shelf">
					<ul class="nav">
						<li class="<?php echo (of_get_option('sticky_header_logo') == '' ? 'home-link' : 'home-icon' ) ?>">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
								<?php
								if ( of_get_option( 'sticky_header_logo' ) == '' )
									largo_home_icon( 'icon-white' );
								else
									largo_home_icon( 'icon-white' , 'orig' );
								?>
							</a>
						</li>
						<?php
            /* Build the mobile off-canvas menu
             * - Check if sitename is shown in sticky nav
             */
							if ( of_get_option( 'show_sitename_in_sticky_nav', 1 ) )
								echo '<li class="site-name"><a href="/">' . get_bloginfo('name') . '</a></li>';

            /* Build Main Navigation using Boostrap_Walker_Nav_Menu() */
							$args = array(
							  'theme_location' => 'main-nav',
							  'depth'		       => 0,
							  'container'	     => false,
							  'items_wrap'     => '%3$s',
							  'menu_class'     => 'nav',
							  'walker'	       => new Bootstrap_Walker_Nav_Menu()
							);
							largo_nav_menu($args);

              /* Check if Donate button should display */
							if ( of_get_option( 'show_donate_button') ) {
								if ($donate_link = of_get_option('donate_link')) { ?>
								<li class="donate">
									<a class="donate-link" href="<?php echo esc_url($donate_link); ?>">
										<span><?php echo esc_html(of_get_option('donate_button_text')); ?></span>
									</a>
								</li><?php
								}
							}

              /* Check for a Global Navigation Menu */
							if (has_nav_menu('global-nav')) {
								$args = array(
									'theme_location' => 'global-nav',
									'depth'		       => 1,
									'container'	     => false,
									'menu_class'     => 'dropdown-menu',
									'echo'           => false
								);
								$global_nav = largo_nav_menu($args);

                /* If no Global Navigation exists, return About Page */
								if (!empty($global_nav)) { ?>
									<li class="menu-item-has-childen dropdown">
										<a href="javascript:void(0);" class="dropdown-toggle"><?php
											//try to get the menu name from global-nav
											$menus = get_nav_menu_locations();
											$menu_title = wp_get_nav_menu_object($menus['global-nav'])->name;
											echo ( $menu_title ) ? $menu_title : __('About', 'largo');
											?> <b class="caret"></b>
										</a>
										<?php echo $global_nav; ?>
									</li>
								<?php } ?>
							<?php } ?>
						</ul>
					</div>
          <!-- END DESKTOP MENU -->
          
				</div>
			</nav>
		</div>
	</div>
</div>

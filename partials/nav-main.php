<?php
/*
 * Main Navigation
 *
 * This is the primary navigation for "verticals" on a homepage. Other pages use
 * sticky navigation if enabled.
 *
 * @package Largo
 */

if ( ! is_single() && ! is_singular() || ! of_get_option( 'main_nav_hide_article', false ) ) {
?>
<nav id="main-nav" class="navbar clearfix">
	<div class="navbar-inner">
		<div class="container">
			<!-- .btn-navbar is used as the toggle for collapsed navbar content -->
			<a class="btn btn-navbar toggle-nav-bar"  title="<?php esc_attr_e('More', 'largo'); ?>">
				<!-- BEGIN Mobile off-canvas menu button -->
				<div class="bars">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</div>
				<!-- END Mobile off-canvas menu button -->
			</a>

			<div class="nav-shelf">
				<ul class="nav">
					<?php
					/*
					 * Generate the Main Navigation shown mainly on homepages
					 *
					 * A Bootstrap Navbar is generated from a walker.
					 *
					 * @see inc/nav-menus.php
					 */
					$args = array(
						'theme_location' => 'main-nav',
						'depth' => 0,
						'container' => false,
						'items_wrap' => '%3$s',
						'menu_class' => 'nav',
						'walker' => new Bootstrap_Walker_Nav_Menu()
					);
					largo_nav_menu( $args );
				?>
				</ul>

				<!-- BEGIN Mobile-Only Menu -->
				<ul class="nav visible-phone">
				<?php if ( has_nav_menu( 'global-nav' ) ) { ?>
					<li class="menu-item-has-childen dropdown">
						<a href="javascript:void(0);" class="dropdown-toggle">
						<?php
							/* try to get the menu name from global-nav */
							$menus = get_nav_menu_locations();
							$menu_title = wp_get_nav_menu_object( $menus['global-nav'] )->name;
							echo ( $menu_title ) ? $menu_title : __( 'About', 'largo' );
						?>
							<b class="caret"></b>
						</a>
						<?php
							$args = array(
								'theme_location' => 'global-nav',
								'depth' => 1,
								'container' => false,
								'menu_class' => 'dropdown-menu'
							);
							largo_nav_menu( $args );
						?>
					</li>
				<?php } ?>
				</ul>
				<!-- END Mobile-Only Menu -->
			</div>
		</div>
	</div>
</nav>
<?php }

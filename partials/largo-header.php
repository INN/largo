<?php
/*
 * Largo Header
 *
 * Calls largo_header() output function and displays print header
 *
 * @package Largo
 * @see inc/header-footer.php
 */
if ( ! is_single() && ! is_singular() || ! of_get_option( 'main_nav_hide_article', false ) ) {
?>
<header id="site-header" class="clearfix nocontent" itemscope itemtype="http://schema.org/Organization">
	<?php /* docs in inc/header-footer.php */
	largo_header(); ?>
</header>
<header class="print-header nocontent">
	<p>
		<strong><?php echo esc_html( get_bloginfo( 'name' ) ); ?></strong>
		(<?php /* Documented in inc/helpers.php */
			echo esc_url( largo_get_current_url() ); ?>)
	</p>
</header>
<?php }

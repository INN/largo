<?php
/**
 * Largo Header
 *
 * Simple markup to generate the theme header and fallback header for printing pages.
 *
 * @package Largo
 */ 
?>
<header id="site-header" class="clearfix nocontent" itemscope itemtype="http://schema.org/Organization">
	<?php 
	/**
	 * Largo Header Output
	 *
	 * Found in the inc/header-footer.php partial, largo_header() is pluggable in Child Themes
	 * if you need to override the default output.
	 * 
	 * @see inc/header-footer.php
	 * @package Largo
	 */
	 largo_header(); 
	 ?>
</header>

<!-- BEGIN Print-Only Header -->
<header class="print-header nocontent">
	<p><strong><?php echo esc_html( get_bloginfo( 'name' ) ); ?></strong> (<?php echo esc_url( largo_get_current_url() ); ?>)</p>
</header>
<!-- END Print-Only Header -->

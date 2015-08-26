<?php if( SHOW_STICKY_NAV === FALSE && is_front_page() || is_home() ) { ?>
<header id="site-header" class="clearfix nocontent" itemscope itemtype="http://schema.org/Organization">
	<?php largo_header(); ?>
</header>
<?php } ?>
<header class="print-header nocontent">
	<p><strong><?php echo esc_html( get_bloginfo( 'name' ) ); ?></strong> (<?php echo esc_url( largo_get_current_url() ); ?>)</p>
</header>

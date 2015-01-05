<?php global $current_url; ?>
<header id="site-header" class="clearfix" itemscope itemtype="http://schema.org/Organization">
	<?php largo_header(); ?>
</header>
<header class="print-header">
	<p><strong><?php echo esc_html( get_bloginfo( 'name' ) ); ?></strong> (<?php echo esc_url( $current_url ); ?>)</p>
</header>
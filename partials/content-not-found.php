<?php
$apologies = __( 'Apologies, but no results were found. Perhaps searching will help.', 'largo' );

if ( is_404() ) {
	if ( of_get_option( '404_message' ) ) {
		$apologies = of_get_option( '404_message' );
	} else {
		$apologies = sprintf(
			__( 'Apologies, but <code>%s</code> was not found. Perhaps searching will help.', 'largo' ),
			wp_kses($_SERVER['REQUEST_URI'], array()) // The url, sanitized
		);
	}

	$title = '<h1 class="entry-title">' . __( 'Page Not Found', 'largo' ) . '</h1>';
} else if ( is_search() ) {
	$apologies = __( 'Apologies, but no results were found. Perhaps searching for something else will help.', 'largo' );
	$title = '';
}

?>
<article id="post-0" class="post no-results not-found">
	<header class="entry-header">
		<?php echo $title ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php echo wpautop( $apologies ); ?></p>
		<?php get_search_form(); ?>
	</div><!-- .entry-content -->
</article><!-- #post-0 -->

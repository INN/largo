<?php
/**
 * The Template for displaying all single posts.
 */
if ( of_get_option( 'single_template' ) == 'classic' )
	get_template_part( 'single-two-column' );
else
	get_template_part( 'single-one-column' );

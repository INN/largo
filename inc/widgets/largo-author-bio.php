<?php
/*
 * Author Bio Widget
 *
 * archive.php uses this widget to create the header of the Author Archive page
 */
class largo_author_widget extends WP_Widget {

	/**
	 * Set up the widget
	 */
	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'largo-author',
			'description'	=> __('Show the author bio in a widget', 'largo')
		);
		parent::__construct( 'largo-author-widget', __('Largo Author Bio', 'largo'), $widget_ops);
	}

	/**
	 * Render the widget output
	 */
	function widget( $args, $instance ) {

		global $post;

		extract( $args );

		$authors = array();
		$bios = '';

		if( get_post_meta( $post->ID, 'largo_byline_text' ) )
			$byline_text = esc_attr( get_post_meta( $post->ID, 'largo_byline_text', true ) );

		$is_series_landing = ( function_exists( 'largo_is_series_landing') ) ? largo_is_series_landing( $post ) : false;

		if( (is_singular() || is_author() || $is_series_landing) && empty($byline_text) ) {
			if ( is_singular() || $is_series_landing ) {
				if ( function_exists( 'get_coauthors' ) ) {
					$authors = get_coauthors( get_queried_object_id() );
				} else {
					$authors = array( get_user_by( 'id', get_queried_object()->post_author ) );
				}
			} else if ( is_author() ) {
				$authors = array( get_queried_object() );
			}

			// make sure we have at least one bio before we show the widget
			foreach ( $authors as $key => $author ) {
				$bio = trim( $author->description );
				if ( !is_author() && empty( $bio ) ) {
					unset( $authors[$key] );
				} else {
					$bios .= $bio;
				}
			}
		}

		if ( is_author() || ! empty( $bios ) ) {
			echo $before_widget;

			foreach( $authors as $author_obj ) {
				$context = array('author_obj' => $author_obj); ?>

					<div class="author-box row-fluid author vcard clearfix">
						<?php largo_render_template( 'partials/author-bio', 'description', $context ); ?>
						<?php largo_render_template( 'partials/author-bio', 'social-links', $context ); ?>
					</div>
			<?php }

			echo $after_widget;
		}
	}

	/**
	 * Widget update function: sanitizes title.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		return $instance;
	}

	/**
	 * This widget has no configuration
	 */
	function form( $instance ) {
		return true;
	}
}

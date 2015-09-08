<?php
/*
 * Author Bio Widget
 */
class largo_author_widget extends WP_Widget {

	function largo_author_widget() {
		$widget_ops = array(
			'classname' 	=> 'largo-author',
			'description'	=> __('Show the author bio in a widget', 'largo')
		);
		$this->WP_Widget( 'largo-author-widget', __('Largo Author Bio', 'largo'), $widget_ops);
	}

	function widget( $args, $instance ) {

		global $post;

		extract( $args );

		echo $before_widget;

		$authors = array();
		$bios = '';

		if( get_post_meta( $post->ID, 'largo_byline_text' ) )
			$byline_text = esc_attr( get_post_meta( $post->ID, 'largo_byline_text', true ) );

		$is_series_landing = ( function_exists( 'largo_is_series_landing') ) ? largo_is_series_landing( $post ) : false;

		if( (is_singular() || is_author() || $is_series_landing) && empty($byline_text) ):

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
				if ( !is_author() && empty( $bios ) ) {
					return;
				}

				foreach( $authors as $author_obj ) {
					$ctx = array('author_obj' => $author_obj); ?>

				<div class="author-box row-fluid author vcard clearfix">
					<?php largo_render_template('partials/author-bio', 'description', $ctx); ?>
					<?php largo_render_template('partials/author-bio', 'social-links', $ctx); ?>
				</div>

				<?php }  // foreach
		elseif ( empty($byline_text) ):
			_e( 'Not a valid author context' );
		endif;

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		return $instance;
	}

	function form( $instance ) {
		return true;
	}
}

<?php
/*
 * About this site
 */
class largo_disclaimer_widget extends WP_Widget {

	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'largo-disclaimer',
			'description'	=> __('Show the article disclaimer', 'largo')
		);
		parent::__construct( 'largo-disclaimer-widget', __('Largo Disclaimer', 'largo'), $widget_ops);
	}

	function widget( $args, $instance ) {
		if ( !is_single() ) {
			return;
		}

		extract( $args );

		echo $before_widget;
		?>
			<?php if ( get_post_meta(get_the_ID(), 'disclaimer', true ) ): ?>
				<?php echo get_post_meta(get_the_ID(), 'disclaimer', true ); ?>
			<?php elseif ( of_get_option( 'default_disclaimer' ) ) : ?>
        <?php echo of_get_option( 'default_disclaimer' ); ?>
			<?php else: ?>
    			<p class="error"><strong><?php _e('You have not set a disclaimer for your site.</strong> Add a site disclaimer by visiting the Largo Theme Options page.', 'largo'); ?></p>
      <?php endif; ?>

		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'title' => __('About ' . get_bloginfo('name'), 'largo') );
		$instance = wp_parse_args( (array) $instance, $defaults );
	}
}
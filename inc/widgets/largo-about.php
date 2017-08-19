<?php
/*
 * About this site
 */

/**
 * Class largo_about_widget
 */
class largo_about_widget extends WP_Widget {

	/**
	 * largo_about_widget constructor.
	 */
	function __construct() {
		$widget_ops = array(
			'classname'   => 'largo-about',
			'description' => __( 'Show the site description from your theme options page', 'largo' )
		);
		parent::__construct( 'largo-about-widget', __( 'Largo About Site', 'largo' ), $widget_ops );
	}

	/**
	 * Echos output for the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	function widget( $args, $instance ) {

		$args = wp_parse_args( $args, array(
			'before_widget' => '',
			'before_title'  => '',
			'after_title'   => '',
			'after_widget'  => '',
		));

		/*
		 * Set default title
		 */
		$instance = $this->_instance_defaults( $instance );

		echo $args[ 'before_widget' ];

		$title = apply_filters( 'widget_title', $instance[ 'title' ], $instance, $this->id_base );

		if ( $title ) {
			echo "{$args[ 'before_title' ]}{$title}{$args[ 'after_title' ]}";
		}

		if ( of_get_option( 'site_blurb' ) ) {
			echo '<p>' . of_get_option( 'site_blurb' ) . '</p>';
		} else {
			echo '<p class="error"><strong>';
			_e( 'You have not set a description for your site.</strong> Add a site description by visiting the Largo Theme Options page.', 'largo' );
			echo '</p>';
		}

		echo $args[ 'after_widget' ];
	}

	/**
	 * @param array $new_instance The new widget instance
	 * @param array $instance     The old Widget instance
	 *
	 * @return array
	 */
	function update( $new_instance, $instance ) {
		$new_instance = $this->_instance_defaults( $new_instance );
		$instance[ 'title' ] = sanitize_text_field( $new_instance[ 'title' ] );
		return $instance;
	}

	/**
	 * Display form to allow updating title
	 * @param array $instance
	 * @return string Default return is 'noform'.
	 */
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->_instance_defaults( $instance ) );
		$title_field_id = $this->get_field_id( 'title' );
		$title_field_name = $this->get_field_name( 'title' );
		?>
        <p>
            <label for="<?php echo esc_attr( $title_field_id ); ?>"><?php _e( 'Title', 'largo' ); ?>:</label>
            <input id="<?php echo esc_attr( $title_field_id ); ?>" name="<?php echo esc_attr( $title_field_name ); ?>" value="<?php echo esc_attr( $instance[ 'title' ] ); ?>" style="width:90%;" />
        </p>
		<?php
	}

	/**
	 * Returns defaults for an instance, or ensures an instance has a title
     *
	 * @param array $instance     The widget instance info
	 * @return array
	 */
	private function _instance_defaults( $instance = array() ) {
		return wp_parse_args( $instance, array(
			'title' => sprintf( __( 'About %s', 'largo' ), get_bloginfo( 'name' ) ),
		));
	}
}
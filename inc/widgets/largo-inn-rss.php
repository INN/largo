<?php
/**
 * Class and related functionas for the Largo INN RSS widget
 *
 * @since Largo 0.1 (2012, early post-Argo code)
 */

/**
 * A customized version of the wp_rss_widget hard-coded to show INN member stories.
 *
 * @since Largo 0.1 (2012, early post-Argo code)
 */
class largo_INN_RSS_widget extends WP_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'largo-INN-RSS',
			'description' => __( 'An RSS feed of recent stories from INN members', 'largo' ),
		);
		parent::__construct( 'largo_INN_RSS', __( 'INN Member Stories', 'largo' ), $widget_ops );
	}

	/**
	 * The Widget output
	 *
	 * @param Array $args The sidebar arguments.
	 * @param Array $instance The widget instance arguments.
	 */
	public function widget( $args, $instance ) {

		$rss = fetch_feed( 'http://feeds.feedburner.com/INNMemberInvestigations' );
		$title = __( 'Stories From Other INN Members', 'largo' );
		$desc = __( 'View more recent stories from members of INN', 'largo' );
		$link = 'http://inn.org/network-content';

		$title = "<a class='rsswidget' href='$link' title='$desc'>$title</a>";

		$title = apply_filters( 'widget_title', empty( $title ) ? __( 'Stories From Other INN Members', 'largo' ) : $title, $instance, $this->id_base );

		if ( empty( $rss ) || is_wp_error( $rss ) ) {
			echo sprintf(
				'<!-- %1$s -->',
				esc_html__( 'The INN Members RSS feed is not returning a valid RSS feed at this point in time.', 'largo' )
			);
			if ( WP_DEBUG || LARGO_DEBUG ) {
				echo sprintf(
					'<!-- %1$s -->',
					esc_html( var_export( $rss, true ) )
				);
			}

			return;
		}

		echo $args['before_widget'];

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		largo_widget_rss_output( $rss, $instance ); ?>

		<p class="morelink"><a href="<?php echo esc_url( $link ); ?>"><?php esc_html_e( 'More Stories From INN Members', 'largo' ); ?>&nbsp;&raquo;</a></p>

		<?php echo $args['after_widget'];

		unset( $rss );
	}

	/**
	 * Save the widget's options
	 *
	 * @param Array $new_instance Updated instance vars.
	 * @param Array $old_instance Previous instance vars.
	 * @return Array Sanitized instance variables
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['num_posts'] = abs( intval( $new_instance['num_posts'] ) );
		$instance['show_excerpt'] = ! empty( $new_instance['show_excerpt'] ) ? 1 : 0;
		return $instance;
	}

	/**
	 * Widget form
	 *
	 * @param Array $instance The saved widget options.
	 */
	public function form( $instance ) {
		$defaults = array(
			'num_posts'    => 3,
			'show_excerpt' => '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$show_excerpt = $instance['show_excerpt'] ? 'checked="checked"' : '';
		?>
			<p>
				<input class="checkbox" type="checkbox" <?php echo esc_attr( $show_excerpt ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_excerpt' ) ); ?>" /> <label for="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>"><?php esc_html_e( 'Show excerpts?', 'largo' ); ?></label>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'num_posts' ) ); ?>"><?php esc_html_e( 'Number of stories to show', 'largo' ); ?>:</label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'num_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'num_posts' ) ); ?>" value="<?php echo (int) $instance['num_posts']; ?>" style="width:90%;" />
			</p>
		<?php
	}
}

/**
 * Helper function to format the RSS output
 *
 * @param something|WP_Error $rss the Rss feed.
 * @param Array $args Arguments for this function?
 *     - show_excerpt: 1 or null
 *     - num_posts: integer => 1
 */
function largo_widget_rss_output( $rss, $args = array() ) {
	echo '<ul>';
	foreach ( $rss->get_items( 0, $args['num_posts'] ) as $item ) {
		$link = $item->get_link();
		while ( stristr( $link, 'http' ) !== $link ) {
			$link = substr( $link, 1 );
		}
		$link = esc_url( strip_tags( $link ) );
		$title = esc_attr( strip_tags( $item->get_title() ) );

		if ( isset( $args['show_excerpt'] ) && 1 === $args['show_excerpt'] ) {
			$desc = str_replace(
				array( "\n", "\r" ),
				' ',
				esc_attr( strip_tags( @html_entity_decode( $item->get_description(), ENT_QUOTES, get_option( 'blog_charset' ) ) ) )
			);
			$desc = largo_trim_sentences( $desc, 2 );
			$summary = "<p class='rssSummary'>$desc</p>";
		} else {
			$summary = '';
		}

		$date = '';
		$date = $item->get_date( 'U' );
		if ( $date ) {
			$date = ' <span class="rss-date">' . date_i18n( get_option( 'date_format' ), $date ) . '</span>';
		}

		$author = ' <cite>' . esc_html( strip_tags( $item->data['child']['']['source'][0]['data'] ) ) . '</cite>';

		if ( empty( $link ) ) {
			printf(
				'<li><h5>%1$s</h5><p class=\"byline\">%2$s | %3$s </p> %4$s</li>',
				wp_kses_post( $title ),
				wp_kses_post( $author ),
				wp_kses_post( $date ),
				wp_kses_post( $summary )
			);
		} else {
			printf(
				'<li><h5><a class="rsswidget" href="%1$s" title="%1$s">%3$s</a></h5><p class=\"byline\">%4$s | %5$s</p>%6$s</li>',
				esc_attr( $link ),
				esc_attr( $title ),
				esc_html( $title ),
				wp_kses_post( $author ),
				wp_kses_post( $date ),
				wp_kses_post( $summary )
			);
		}
	}
	echo '</ul>';
	$rss->__destruct();
	unset( $rss );
}

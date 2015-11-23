<?php
/*
 * List all of the terms in a custom taxonomy
 */
class largo_taxonomy_list_widget extends WP_Widget {

	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'largo-taxonomy-list',
			'description' 	=> __('List all of the terms in a custom taxonomy with links', 'largo')
		);
		parent::__construct( 'largo-taxonomy-list-widget', __('Largo Taxonomy List', 'largo'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Categories', 'largo' ) : $instance['title'], $instance, $this->id_base);
		$is_dropdown = ! empty( $instance['dropdown'] ) ? '1' : '0';

		/*
		 * Before the widget
		 */
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		/*
		 * The widget
		 */
		$cat_args = array(
			'orderby' => 'name',
			'taxonomy' => $instance['taxonomy'],
			'number' => $instance['count'],
			'include' => $instance['include'],
		);

		if ( $is_dropdown ) {
			$cats = get_categories($cat_args); ?>

			<select id="taxonomy-list-widget">
				<option value=""><?php printf( __('Select %s', 'largo'), ucwords($instance['taxonomy']) ); ?></option>
			<?php foreach ($cats as $cat) : ?>
				<option value="<?php echo get_term_link($cat, $cat->taxonomy) ?>"><?php echo $cat->name ?></option>
			<?php endforeach; ?>
			</select>

			<script>
				jQuery(document).ready(function() {
				    jQuery('#taxonomy-list-widget').change(function() {
				        window.location = jQuery(this).val();
				    });
				});
			</script>

		<?php } else { 
			echo '<ul class="' . $instance['taxonomy'] . '">';

			$cat_args['title_li'] = '';
			$tax_items = get_categories($cat_args);

			switch ($instance['taxonomy']) {
				case 'series':
					$this->render_series_list($tax_items, $instance);
					break;
				case 'category':
					$this->render_cat_list($tax_items, $instance);
					break;
				case 'post_tag':
					$this->render_tag_list($tax_items, $instance);
					break;
				default:
					$this->render_term_list($tax_items, $instance);
			}

			echo '</ul>';
		}

		echo $after_widget;
	}

	/**
	 * Helper to render an li
	 *
	 * If $thumbnail is empty, then there is simply no image output.
	 * If there is no thumbnail, class .no-thumbnail is added to the <li>.
	 * If there is a thumbnail, class .has-thumbnail is added to the <li>.
	 *
	 * @param Object $item a wordpress taxonomy object
	 * @param str $thumbnail the HTML for the thumbnail image
	 * @param str $headline the HTML for the headline
	 * @private
	 * @since 0.5.3
	 */
	private function render_li($item, $thumbnail = '', $headline = '') {
		echo sprintf(
			'<li class="%s-%s %s"><a href="%s">%s <h5>%s</h5></a> %s</li>',
			$item->taxonomy,
			$item->term_id,
			( $thumbnail != '' ) ? "has-thumbnail" : "no-thumbnail" ,
			get_term_link($item),
			$thumbnail, // the image for the series
			$item->cat_name,
			$headline
		);
	}

	/**
	 * For a series, find a thumbnail in the landing pages or the posts, and create an <li>
	 *
	 * @private
	 * @uses largo_taxonomy_list_widget::render_li
	 * @uses largo_featured_thumbnail_in_post_array
	 * @uses largo_first_headline_in_post_array
	 */
	private function render_series_list($tax_items, $instance) {
		foreach ($tax_items as $item) {
			$thumbnail = '';
			$headline = '';
			$posts = array();

			if ($instance['thumbnails'] == '1' || $instance['use_headline'] == '1') {
				$query_args = array(
					'tax_query' => array(
						array(
							'taxonomy' => $instance['taxonomy'],
							'field' => 'term_id',
							'terms' => $item->term_id,
						),
					),
				);
				$posts = get_posts($query_args);
			}

			if ($instance['thumbnails'] == '1' && largo_is_series_landing_enabled() ) {
				$landing_array = largo_get_series_landing_page_by_series($item);
				
				// Thumbnail shall be the one for the landing page post
				foreach ($landing_array as $post) {
					$thumbnail = get_the_post_thumbnail($post->ID);
				}
			}

			if ($thumbnail == '') {
				$thumbnail = largo_featured_thumbnail_in_post_array($posts);
			}

			if ($instance['use_headline'] == '1') {
				$headline = largo_first_headline_in_post_array($posts);
			}

			$this->render_li($item, $thumbnail, $headline);
		}
	}

	/**
	 * Find the first thumbnailed post in the category and create an <li>
	 *
	 * @private
	 * @uses largo_taxonomy_list_widget::render_li
	 * @uses largo_featured_thumbnail_in_post_array
	 * @uses largo_first_headline_in_post_array
	 */
	private function render_cat_list($tax_items, $instance) {
		foreach ($tax_items as $item) {
			$headline = '';
			$thumbnail = '';
			$posts = array();

			// Only get posts if we're going to use them.
			if ($instance['thumbnails'] == '1' || $instance['use_headline'] == '1') {
				$posts = get_posts(array(
					'category_name' => $item->name,
				));
			}
			if ($instance['thumbnails'] == '1') {
				$thumbnail = largo_featured_thumbnail_in_post_array($posts);
			}
			if ($instance['use_headline'] == '1') {
				$headline = largo_first_headline_in_post_array($posts);
			}
			$this->render_li($item, $thumbnaili, $headline);
		}
	}

	/**
	 * For a tag, find the first thumbnailed post and create an <li>
	 *
	 * @private
	 * @uses largo_taxonomy_list_widget::render_li
	 * @uses largo_featured_thumbnail_in_post_array
	 * @uses largo_first_headline_in_post_array
	 */
	private function render_tag_list($tax_items, $instance) {
		foreach ($tax_items as $item) {
			$headline = '';
			$thumbnail = '';
			$posts = array();

			// Only get posts if we're going to use them.
			if ($instance['thumbnails'] == '1' || $instance['use_headline'] == '1') {
				$posts = get_posts(array(
					'tag' => $item->slug,
				));
			}
			if ($instance['thumbnails'] == '1') {
				$thumbnail = largo_featured_thumbnail_in_post_array($posts);
			}
			if ($instance['use_headline'] == '1') {
				$headline = largo_first_headline_in_post_array($posts);
			}
			$this->render_li($item, $thumbnaili, $headline);
		}
	}

	/**
	 * For a generic term in a taxonomy, find the first thumbnailed post in the term and create an <li>
	 *
	 * @private
	 * @uses largo_taxonomy_list_widget::render_li
	 * @uses largo_featured_thumbnail_in_post_array
	 * @uses largo_first_headline_in_post_array
	 */
	private function render_term_list($tax_items, $instance) {
		foreach ($tax_items as $item) {
			$thumbnail = '';
			$headline = '';
			$posts = array();

			// Only get posts if we're going to use them.
			if ($instance['thumbnails'] == '1' || $instance['use_headline'] == '1') {
				$query_args = array(
					'tax_query' => array(
						array(
							'taxonomy' => $instance['taxonomy'],
							'field' => 'term_id',
							'terms' => $item->term_id,
						),
					),
				);
				$posts = get_posts($query_args);
			}
			if ($instance['thumbnails'] == '1') {
				$thumbnail = largo_featured_thumbnail_in_post_array($posts);
			}
			if ($instance['use_headline'] == '1') {
				$headline = largo_first_headline_in_post_array($posts);
			}
			$this->render_li($item, $thumbnaili, $headline);
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['taxonomy'] = strtolower(strip_tags($new_instance['taxonomy']));
		$instance['count'] = sanitize_text_field($new_instance['count']);
		if ($instance['count'] == '' ) {
			$instance['count'] = '';
		} else if ($instance['count'] < 1) {
			$instance['count'] = 1;
		}

		$instance['include'] = sanitize_text_field($new_instance['include']);
		$instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;
		$instance['thumbnails'] = !empty($new_instance['thumbnails']) ? 1 : 0;
		$instance['use_headline'] = !empty($new_instance['use_headline']) ? 1 : 0;

		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'taxonomy' => '' ) );
		$title = esc_attr( $instance['title'] );
		$taxonomy = esc_attr( $instance['taxonomy'] );
		$count = $instance['count'];
		$include = $instance['include'];
		$dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
		$thumbnails = isset( $instance['thumbnails'] ) ? (bool) $instance['thumbnails'] : false;
		$use_headline = isset( $instance['use_headline'] ) ? (bool) $instance['use_headline'] : false;

		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'largo' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p>
			<label for="<?php echo $this->get_field_id('include'); ?>"><?php _e('Only display the terms in this comma-separated list of term IDs:', 'largo'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('include'); ?>" name="<?php echo $this->get_field_name('include'); ?>" type="text" value="<?php echo $include; ?>" />
			<small><?php _e('Find term IDs by examining the URL of the taxonomy when you click the "edit" button in the list', 'largo'); ?>.</small>
		</p>

		<p>
			<label for"<?php echo $this->get_field_id('count'); ?>"><?php _e('Count: (leave blank to receive all items)', 'largo'); ?></label>
			<input id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count')?>" type="number" value="<?php echo $count; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php _e('Taxonomy:', 'largo'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('taxonomy'); ?>" name="<?php echo $this->get_field_name('taxonomy'); ?>" type="text" value="<?php echo $taxonomy; ?>" />
		</p>

		<p><input type="checkbox" class="checkbox ltlw-dropdown" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked( $dropdown ); ?> />
			<label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e( 'Display as dropdown', 'largo' ); ?></label>
		</p>

		<p><input type="checkbox" class="checkbox ltlw-thumbnails" id="<?php echo $this->get_field_id('thumbnails'); ?>" name="<?php echo $this->get_field_name('thumbnails'); ?>"<?php checked( $thumbnails ); ?> <?php echo (largo_is_series_landing_enabled()) ? '' : 'disabled' ; ?> />
			<label for="<?php echo $this->get_field_id('thumbnails'); ?>"><?php _e( 'Display thumbnails?', 'largo' ); ?> <?php echo (largo_is_series_landing_enabled()) ? '' : __('To use this function, enable Series and Series Landing Pages in Appearance > Theme Options > Advanced.', 'largo') ; ?> </label>
		</p>

		<p><input type="checkbox" class="checkbox ltlw-headline" id="<?php echo $this->get_field_id('use_headline'); ?>" name="<?php echo $this->get_field_name('use_headline'); ?>"<?php checked( $use_headline ); ?> />
			<label for="<?php echo $this->get_field_id('use_headline'); ?>"><?php _e( 'Display headline of most-recent post in taxonomy?', 'largo' ); ?></label>
		</p>

		<script>
			jQuery(document).ready(function($) {
				$('.ltlw-dropdown').click(function() {
					$(this).parent().parent().find('.ltlw-thumbnails').removeAttr('checked');
					$(this).parent().parent().find('.ltlw-headline').removeAttr('checked');
				});
				$('.ltlw-thumbnails').click(function() {
					$(this).parent().parent().find('.ltlw-dropdown').removeAttr('checked');
				});
				$('.ltlw-headline').click(function() {
					$(this).parent().parent().find('.ltlw-dropdown').removeAttr('checked');
				});
			});
		</script>

	<?php
	}
}

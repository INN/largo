<?php


/**
 * Creates the admin UI page
 */
class FeedInput_AdminPage {
	var $feed_urls;

	function __construct() {
		add_action( 'admin_menu', array(&$this, 'admin_menu') );
		add_action( 'admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts') );
		add_action( 'admin_enqueue_scripts', array(&$this, 'enqueue_dashboard_widget_scripts') );
		add_action( 'init', array(&$this, 'register_feedset') );
		add_action( 'feedinput_convert_to_post-feedinput_admin', array(&$this, 'convert_post_action'), 3, 10 );
		add_action( 'init', array(&$this, 'register_taxonomy') );
		add_action( 'wp_dashboard_setup', array( &$this, 'register_dashboard_widget' ) );
		add_action( 'wp_ajax_feedinput_dashboard_convert_item', array(&$this, 'dashboard_widget_ajax_convert_item') );
		add_action( 'wp_ajax_feedinput_dashboard_remove_item', array(&$this, 'dashboard_widget_ajax_remove_item') );
		add_action( 'wp_ajax_feedinput_dashboard_page', array(&$this, 'dashboard_widget_ajax_page') );


	}


	/**
	 * Add our admin page
	 */
	function admin_menu() {
		$this->hook_suffix = add_options_page('Syndicated Sources','Syndicated Sources','manage_options','syndicated_sources', array($this, 'page_content') );
		add_action( 'load-'.$this->hook_suffix, array(&$this, 'process') );
	}


	/**
	 * Output the content of the page
	 */
	function page_content() {
		$feed_urls = $this->get_feed_urls();
		$map = $this->get_taxonomy_map();
		?>
		<div class="wrap">
			<h2><?php _e('Syndicated Sources', 'feedinput'); ?></h2>

			<form method="POST" action="options-general.php?page=syndicated_sources">
				<?php wp_nonce_field( 'update_feed_urls', 'feedinput_nonce' ); ?>
				<input type="hidden" name="feedinput" value="1" />
				<label for="feed_urls"><?php _e('Feed URLs', 'feedinput'); ?></label>
				<p><?php _e('Enter each feed URL on a separate line. You can prefix a URL with the <a href="' . admin_url('edit-tags.php?taxonomy=media-sources') .  '">media source</a> term name to assign the converted posts. Example: Arnold Times | http://arnoldtimesonline.com/feed', 'feedinput'); ?></p>
				<textarea id="feed_urls" name="feed_urls" style="width: 100%" rows="10"><?php
					$lines = array();
					foreach ( $feed_urls as $url ) {
						if ( !empty( $map[$url] ) ) {
							$lines[] = $map[$url] . ' | ' . $url;
						} else {
							$lines[] = $url;
						}
					}
					echo implode( "\n", $lines );
				?></textarea>

				<div>
					<button type="submit" class="button-primary"><?php _e('Save'); ?></button>
				</div>
			</form>
		</div>
		<?php
	}


	/**
	 * Add our JS and CSS for the admin page
	 */
	function admin_enqueue_scripts( $hook_suffix ) {
		if ( $hook_suffix != $this->hook_suffix ) {
			return;
		}

	}


	/**
	 * Attempts to process the form submission
	 */
	function process() {
		$current_screen = get_current_screen();

		if ( $current_screen->id == 'settings_page_syndicated_sources' && filter_input(INPUT_POST, 'feedinput', FILTER_VALIDATE_BOOLEAN) ) {
			if ( empty($_POST['feedinput_nonce']) || !wp_verify_nonce($_POST['feedinput_nonce'],'update_feed_urls') ) {
				// The nonce did not match
				return;
			}

			$old_feed_urls = $this->get_feed_urls();

			// Get input
			$feed_urls_raw = filter_input( INPUT_POST, 'feed_urls', FILTER_SANITIZE_STRING );
			$lines = explode( "\n", $feed_urls_raw );
			$urls  = array();
			$taxonomy_map = array();

			foreach ( $lines as $line ) {
				preg_match( '#[\s|]+([^\s|]+)\s*$#', $line, $matches );
				if ( isset( $matches[1] ) ) {
					$urls[] = $matches[1];
					$taxonomy_map[$matches[1]] = str_replace( $matches[0], '', $line );
				} else {
					$urls[] = trim($line);
				}
			}

			$feed_urls = array();
			foreach ( $urls as $url ) {
				$url = filter_var( $url, FILTER_SANITIZE_URL );

				if ( $url != false ) {
					$feed_urls[] = $url;
				}
			}

			// Save the feeds
			$this->set_feed_urls( $feed_urls );
			$this->set_taxonomy_map( $taxonomy_map );
			$this->register_feedset();

			// Check if there are new feeds added, if not then force an initial update
			$new_feed_urls = array_diff( $feed_urls, $old_feed_urls );
			if ( count( $new_feed_urls ) != 0 ) {
				feedinput_force_update_feed( 'feedinput_admin' );
			}

			add_action('admin_notices', array( $this , 'notify_change') );
		}
	}

	function notify_change() {
		echo '<div class="updated"><p>Sources updated.</p></div>';
	}


	/**
	 * Init hook
	 */
	function register_feedset() {
		$feed_urls = $this->get_feed_urls();

		if ( count( $feed_urls ) > 0 ) {
			$options = array(
				'convert_to_post' => false,
				'convert' => array(
					'post' => array(),
					'meta' => array(
						'largo_byline_text' => array( 'type' => 'field', 'value' => array('authors', 0, 'name') ),
						'largo_byline_link' => array( 'type' => 'field', 'value' => array('authors', 0, 'link') ),
					),
				)
			);
			feedinput_register_feed( 'feedinput_admin', $feed_urls, $options );
		}
	}


	function get_feed_urls() {
		if ( !is_array( $this->feed_urls ) ) {
			$this->feed_urls = get_option( 'feedinput_feeds', array() );
		}
		return $this->feed_urls;
	}


	function set_feed_urls( $feed_urls ) {
		$this->feed_urls = $feed_urls;
		update_option( 'feedinput_feeds', $feed_urls );
	}

	function get_taxonomy_map() {
		if ( !isset( $this->taxonomy_map ) || !is_array( $this->taxonomy_map ) ) {
			$this->taxonomy_map = get_option( 'feedinput_taxonomy_map', array() );
		}
		return $this->taxonomy_map;
	}

	function set_taxonomy_map( $taxonomy_map ) {
		$this->taxonomy_map = $taxonomy_map;
		update_option( 'feedinput_taxonomy_map', $taxonomy_map );
	}

	/**
	 * Action for converting a post to add custom Largo taxonomy
	 */
	function convert_post_action( $post, $data, $feedset ) {
		$map = $this->get_taxonomy_map();
		$feed_url = $data['feed_url'];

		if ( !empty( $map[$feed_url] ) ) {
			wp_set_post_terms( $post->ID, $map[$feed_url], 'media-sources', true );
		}
	}

	/**
	 * Register a custom taxonomy for the named media source
	 */
	function register_taxonomy() {
		register_taxonomy('media-sources',
			array( 'post'),
		 	array(
		 		'labels' => array(
		 			'singular_name' => __('Media Source', 'feedinput'),
		 			'name' => __( 'Media Sources', 'feedinput' ),
		 		),
		 		'public' => true,
		 	) );
	}


	//
	// Dashboard Widget
	//

	/**
	 * Register the dashboard
	 */
	function register_dashboard_widget() {
		if ( current_user_can( 'edit_pages' ) ) {
			wp_add_dashboard_widget('feedinput_dashboard_widget', 'Syndicated Items', array(&$this, 'dashboard_widget') );
		}
	}


	/**
	 * Output the dashboard content
	 */
	function dashboard_widget() {

		echo '<ul class="item-list">';
		$this->dashboard_widget_items( 1 );
		echo '<li class="feedinput-paging-status-indicator">';
		echo '</li>';
		echo '</ul>';
	}


	function dashboard_widget_items( $page ) {
		$feed_set = feedinput_get_feed( 'feedinput_admin' );
		$items = $feed_set->get_items( 20, $page );

		foreach ( $items as $item ) {
			$converted = get_post_meta( $item->post->ID, 'converted_posts', true );
			echo '<li data-id="', htmlentities($item->data['uid']),'">';
			echo '<div class="feedinput-item-title">', $item->data['title'], '</div>';
			echo '<div class="feedinput-item-meta">';
			$shorten_url = $item->data['permalink'];
			$shorten_url = strlen($shorten_url) > 50 ? esc_url(substr($shorten_url, 0, 50)).'&hellip;' : esc_url($shorten_url);
			echo $item->data['feed_title'], ' | ', '<a href="', esc_url($item->data['permalink']), '" title="', esc_url($item->data['permalink']), '">', $shorten_url,'</a>';
			echo '</div>';
			echo '<div class="feedinput-item-actions">';
			if ( empty($converted['feedinput_admin'] ) ) {
				echo '<a data-action="convert-item" data-id="', htmlentities($item->data['uid']),'">', __( 'Convert To Post', 'feedinput'), '</a>';
			} else {
				edit_post_link( __('Edit Post'), '', '', $item->post->ID );
			}

			echo ' | <a data-action="trash-item" data-id="', htmlentities($item->data['uid']), '">', __( 'Remove Item'), '</a>';
			echo '</div>';
			echo '</li>';
		}
	}

	/**
	 *
	 */
	function enqueue_dashboard_widget_scripts() {
		$screen = get_current_screen();

		if( $screen->base == 'dashboard' ) {
			$url = get_template_directory_uri();
			wp_enqueue_script( 'spinjs', $url.'/inc/feed-input/spin.js', array('jquery'), '1.3.0', true );
			wp_enqueue_script( 'feedinput-dashboard', $url.'/inc/feed-input/dashboard.js', array('jquery', 'spinjs'), '0.0.1', true );
			wp_enqueue_style( 'feedinput-dashboard', $url.'/inc/feed-input/dashboard.css' );
		}
	}


	/**
	 * AJAX handler for converting an item
	 */
	function dashboard_widget_ajax_convert_item() {
		if ( !current_user_can( 'edit_pages' ) ) {
			return json_encode("Insufficient permissions");
		}

		$id = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_STRING );

		if ( empty( $id ) ) {
			return json_encode("Provided ID was empty");
		}

		$feed_set = feedinput_get_feed( 'feedinput_admin' );
		$post = $feed_set->convert_item_to_post( $id );
		if ( !empty( $post->ID ) ) {
			edit_post_link( __('Edit Post'), '', '', $post->ID );
		}
		exit;
	}


	/**
	 * AJAX handler for converting an item
	 */
	function dashboard_widget_ajax_remove_item() {
		if ( !current_user_can( 'edit_pages' ) ) {
			return;
		}

		$id = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_STRING );

		if ( empty( $id ) ) {
			return;
		}

		$feed_set = feedinput_get_feed( 'feedinput_admin' );
		$feed_set->remove_item( $id );

		echo 'removed';
		exit;
	}

	function dashboard_widget_ajax_page() {
		if ( !current_user_can( 'edit_pages' ) ) {
			return;
		}

		$page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT );

		if ( $page < 1 ) {
			return;
		}

		echo '<ul>';
		$this->dashboard_widget_items( $page );
		echo '</ul>';

		exit;
	}


}

// Kickoff
new FeedInput_AdminPage;
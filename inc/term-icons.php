<?php
/**
 * Creates $largo['term-icons'] using the Largo_Term_Icons class defined herein
 *
 * Display the fields for selecting icons for terms in the "post-type" taxonomy
 *
 * @global $largo
 *
 */
class Largo_Term_Icons {

	function __construct() {
		global $wp_filesystem;

		if (empty($wp_filesystem)) {
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			WP_Filesystem();
		}

		add_action( 'edit_category_form_fields', array( $this, 'display_fields' ) );
		add_action( 'edit_tag_form_fields', array( $this, 'display_fields' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts') );
		add_action( 'edit_terms', array( $this, 'edit_terms' ) );
		add_action( 'create_term', array( $this, 'edit_terms' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );
	}


	/**
	 * Register the taxonomy post-type
	 */
	function register_taxonomy() {
		register_taxonomy( 'post-type', array( 'post' ), array(
			'label' => __( 'Post Types', 'largo' ),
			'labels' => array(
				'name' => __( 'Post Types', 'largo' ),
				'singular_name' => __( 'Post Type', 'largo' ),
				'all_items' => __( 'All Post Types', 'largo' ),
				'edit_item' => __( 'Edit Post Type', 'largo' ),
				'update_item' => __( 'Update Post Type', 'largo' ),
				'view_item' => __( 'View Post Type', 'largo' ),
				'add_new_item' => __( 'Add New Post Type', 'largo' ),
				'new_item_name' => __( 'New Post Type Name', 'largo' ),
				'search_items' => __( 'Search Post Type'),
			),
			'public' => true,
			'show_admin_column' => true,
			'hierarchical' => true,
		) );
	}

	/**
	 * Retrieves the Fontello config.json information about the glyphs
	 *
	 * @global $wp_filesystem
	 */
	function get_icons_config() {
		global $wp_filesystem;

		if ( !empty( $this->_icons_config ) ) {
			return $this->_icons_config;
		}

		if ( is_file( get_stylesheet_directory() . '/fonts/fontello/config.json' ) ) {
			$config = json_decode( $wp_filesystem->get_contents( get_stylesheet_directory() . '/fonts/fontello/config.json' ) );
			$css_file = get_stylesheet_directory_uri() . '/fonts/fontello/css/fontello.css';
		} else {
			$config = json_decode( $wp_filesystem->get_contents( get_template_directory() . '/fonts/fontello/config.json' ) );
			$css_file = get_template_directory_uri() . '/fonts/fontello/css/fontello.css';
		}

		$this->_icons_config = $config;
		$this->_css_file = $css_file;


		return $this->_icons_config;
	}

	/**
	 *
	 */
	function get_icon_taxonomies() {
		if ( empty($this->_icon_taxonomies) ) {
			$this->_icon_taxonomies = apply_filters( 'largo_get_icon_taxonomies', array( 'post-type' ) );
		}
		return $this->_icon_taxonomies;
	}

	/**
	 * Renders the form fields on the term edit page
	 *
	 * @param object $term A taxonomy term
	 */
	function display_fields( $term ) {
		if ( !in_array( $term->taxonomy, $this->get_icon_taxonomies() ) ) {
			// abort if the term doesn't belong to the taxonomies to have icons
			return;
		}

		$config = $this->get_icons_config();
		$current_value = largo_get_term_meta( $term->taxonomy, $term->term_id, 'associated_icon_uid', true );
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="associated_icon"><?php _e('Term Icon', 'largo'); ?></label></th>
			<td>
				<select name="associated_icon" id="associated_icon" style="min-width: 300px;">
					<option value="" <?php selected( $current_value, '' ); ?>><?php _e( 'No Icon', 'largo'); ?></option>
					<?php foreach( $config->glyphs as $glyph ) {
						$name = ucwords( str_replace( '-', ' ', $glyph->css ) );
						echo '<option value="', esc_attr( $glyph->uid ), '" data-css="', esc_attr($config->css_prefix_text . $glyph->css), '" ', selected( $glyph->uid, $current_value ) ,'>',esc_html($name),'</option>';
					} ?>
				</select>
				<br/>
				<p class="description"><?php _e('The icon the theme may use with the term.', 'largo'); ?></p>
				<?php
				wp_nonce_field( 'associated_icon-'.$term->term_id, '_associated_icon_nonce' );
				?>
			</td>
		</tr>
		<?php
	}

	function display_add_new_field( $taxonomy ) {
		$config = $this->get_icons_config();
		?>
		<div class="form-field">
			<label for="associated_icon"><?php _e('Term Icon', 'largo'); ?></label>
			<select name="associated_icon" id="associated_icon" style="min-width: 300px;">
				<option value=""><?php _e( 'No Icon', 'largo'); ?></option>
				<?php foreach( $config->glyphs as $glyph ) {
					$name = ucwords( str_replace( '-', ' ', $glyph->css ) );
					echo '<option value="', esc_attr( $glyph->uid ), '" data-css="', esc_attr($config->css_prefix_text . $glyph->css), '">',esc_html($name),'</option>';
				} ?>
			</select>
			<p class="description"><?php _e('The icon the theme may use with the term.', 'largo'); ?></p>
			<?php wp_nonce_field( 'associated_icon-new', '_associated_icon_nonce' ); ?>
		</div>
		<?php
	}

	/**
	 * Attach the Javascript and Stylesheets to the term edit page
	 *
	 * @param string $hook_suffix
	 * @global LARGO_DEBUG
	 * @global $_REQUEST
	 */
	function admin_enqueue_scripts( $hook_suffix ) {

		if ( $hook_suffix == 'edit-tags.php' && !empty($_REQUEST['taxonomy']) ) {
			if ( !in_array( $_REQUEST['taxonomy'], $this->get_icon_taxonomies() ) ) {
				// abort if the term doesn't belong to the taxonomies to have icons
				return;
			}

			add_action( $_REQUEST['taxonomy'].'_add_form_fields', array( $this, 'display_add_new_field' ) );

			$this->get_icons_config();

			wp_enqueue_style( 'fontello', $this->_css_file );

			$path = get_template_directory();
			$dir = get_template_directory_uri();
			$locale = explode( '_', get_locale() );

			wp_enqueue_style( 'select2', $dir.'/js/select2/select2.css' );
			wp_enqueue_script( 'select2', $dir.'/js/select2/select2.min.js', array( 'jquery' ) );

			if ( is_file( $path . '/js/select2/select2_locale_' . implode( '-', $locale ) . '.js' ) ) {
				wp_enqueue_script( 'select2-locale-'. implode( '-', $locale ), $dir . '/js/select2/select2_locale_' . implode( '-', $locale ) . '.js' );
			} elseif ( is_file( $path . '/js/select2/select2_locale_' . $locale[0] . '.js' ) ) {
				wp_enqueue_script( 'select2-locale-'. $locale[0], $dir . '/js/select2/select2_locale_' . $locale[0] . '.js' );
			}

			$suffix = (LARGO_DEBUG)? '' : '.min';
			wp_enqueue_script( 'custom-term-icons', $dir.'/js/custom-term-icons' . $suffix . '.js' );

		}
	}

	/**
	 * Save the results from the term edit page
	 *
	 * @global $post
	 * @param string $term_id
	 */
	function edit_terms( $term_id ) {
		if (isset($_POST['action']) && $_POST['action'] == 'add-tag')
			$nonce_action = 'associated_icon-new';
		else
			$nonce_action = 'associated_icon-' . $term_id;

		if ( isset($_POST['_associated_icon_nonce']) && wp_verify_nonce($_POST['_associated_icon_nonce'], $nonce_action ) ) {
			$taxonomy = $_REQUEST['taxonomy'];
			largo_update_term_meta( $taxonomy, $term_id, 'associated_icon_uid', $_POST['associated_icon'] );
		}
	}

	/**
	 * Retrieve the icon information for a term
	 *
	 * @param term|string $taxonomy_or_term - the term object of the taxonomy name
	 * @param int $term_id - the term id when the first parameter is the taxonomy name
	 */
	function get_icon( $taxonomy_or_term, $term_id='' ) {
		if ( is_object( $taxonomy_or_term ) ) {
			$term = $taxonomy_or_term;
		} else {
			$term = get_term( $taxonomy_or_term, $term_id );
		}

		$uid = largo_get_term_meta( $term->taxonomy, $term->term_id, 'associated_icon_uid', true );
		$config = $this->get_icons_config();

		foreach( $config->glyphs as $glyph ) {
			if ( $glyph->uid == $uid ) {
				return $glyph;
			}
		}

		return false;
	}

	/**
	 * Output the icon for a term
	 *
	 * @param term|string $taxonomy_or_term - the term object of the taxonomy name
	 * @param int $term_id - the term id when the first parameter is the taxonomy name
	 * @param string $tag - the HTML element that shall be used for the icon
	 */
	function the_icon( $taxonomy_or_term, $term_id='i', $tag='i' ) {
		if ( is_object( $taxonomy_or_term ) ) {
			$icon = $this->get_icon( $taxonomy_or_term );
			$tag = $term_id;
		} else {
			$icon = $this->get_icon( $taxonomy_or_term, $term_id );
		}

		if ( $icon == false ) {
			return;
		}

		$config = $this->get_icons_config();

		echo "<{$tag} class='{$config->css_prefix_text}{$icon->css}'></{$tag}>";
	}
}

$largo['term-icons'] = new Largo_Term_Icons();

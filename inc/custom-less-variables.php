<?php
/**
 * Functionality for converting the variable.less into
 * theme options page that will recompile into new CSS.
 *
 * To debug the generated CSS, add the following to your wp-config.php:
 * define( 'JCLV_UNCOMPRESSED', true );
 */


//
// Default settings
//

/**
 *  Setup which LESS files compiled into CSS files
 */
add_action( 'largo_custom_less_variables_init', 'largo_custom_less_variables_init', 1 );
function largo_custom_less_variables_init() {
	largo_clv_register_files( array( 'inc/carousel.less', 'editor-style.less', 'style.less', 'top-stories.less' ) );
	largo_clv_register_directory_paths( get_template_directory() . '/less/', get_template_directory_uri() . '/css/' );
	largo_clv_register_variables_less_file( 'inc/variables.less' );
}


//
// API Functions
//

/**
 * Register which Less files are to be compiled into CSS
 * for the user customized values to override variables.less.
 *
 * Example:
 *
 * largo_clv_register_files( array( 'style.less', 'editor.less' ) );
 *
 * @param array $files - list of filenames in the less directory
 */
function largo_clv_register_files( $files ) {
	Largo_Custom_Less_Variables::register_files( $files );
}


/**
 * Set the file path for the directory with the LESS files and
 * URI for the directory with the outputted CSS.
 *
 * @param string $less_dir
 * @param string $css_dir_uri
 */
function largo_clv_register_directory_paths( $less_dir, $css_dir_uri ) {
	Largo_Custom_Less_Variables::register_directory_paths( $less_dir, $css_dir_uri );
}


/**
 * Set the filename of the variables file.
 *
 * @param string $variables_less_file - 'variables.less'
 */
function largo_clv_register_variables_less_file( $variables_less_file ) {
	Largo_Custom_Less_Variables::register_variables_less_file( $variables_less_file );
}



/**
 * Class to contain the logic
 */
class Largo_Custom_Less_Variables {

	// Variables
	static $less_files = array();
	static $css_files = array();
	static $field_type_callbacks = array();
	static $less_dir;
	static $css_dir_uri;
	static $variables_less_file = 'variables.less';

	const CACHE_DURATION = WEEK_IN_SECONDS;
	const POST_TYPE = 'largo_less_variables';

	/**
	 * Initialize the plugin
	 */
	static function init() {
		// Alters the URL for the CSS files that are recompiled with the custom variables
		add_filter( 'style_loader_src', array( __CLASS__, 'style_loader_src' ), 10, 2 );

		// Used to output the rendered CSS for the customized LESS
		add_action( 'template_redirect', array( __CLASS__, 'template_redirect') );

		// Add our admin page
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu') );

		// Register post type for saving the data to
		register_post_type( self::POST_TYPE, array( 'public' => false, 'supports' => array( 'revisions' ) ));

		self::$less_dir    = get_template_directory() . '/less/';
		self::$css_dir_uri = get_template_directory_uri() . '/css/';

		// Allow others to alter the settings
		do_action( 'largo_custom_less_variables_init' );

		// Check if this page load is result of a save
		if ( is_admin() && isset( $_POST['customlessvariables'] ) && false != strstr( $_SERVER[ 'REQUEST_URI' ], 'themes.php' ) ) {
			check_admin_referer( 'customlessvariables', 'customlessvariables' );

			if ( isset( $_POST['field'] ) && is_array( $_POST['field'] ) && isset( $_POST['submit-action'] ) && $_POST['submit-action'] == __( 'Reset All', 'largo' )) {
			// Reset all values
				self::reset_all();
				add_action( 'admin_notices', array( __CLASS__, 'reset_admin_notices' ) );
			// Update fields
			} else if ( isset( $_POST['field'] ) && is_array( $_POST['field'] ) ) {
				self::update_custom_values( $_POST['field'] );
				add_action( 'admin_notices', array( __CLASS__, 'success_admin_notices' ) );
			} else {
				self::update_custom_values( array() );
				add_action( 'admin_notices', array( __CLASS__, 'success_admin_notices' ) );	//we updated even without getting anything
			}
		}
	}

	/**
	* Write a file to disk.
	*
	* @param string $file - path of file to write
	* @param string $contents - the content to be written to the file
	*/
	protected function put_contents($file, $contents) {
		global $wp_filesystem;

		if (empty($wp_filesystem)) {
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			WP_Filesystem();
		}

		return $wp_filesystem->put_contents($file, $contents);
	}

	/**
	* Read a file's contents.
	*
	* @param string $file - path of file to read
	*/
	protected function get_contents($file) {
		global $wp_filesystem;

		if (empty($wp_filesystem)) {
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			WP_Filesystem();
		}

		return $wp_filesystem->get_contents($file);
	}

	/**
	 * Register the Less files to compile into CSS files
	 *
	 * @param array $files - the LESS files to compile into CSS
	 * @global bool LARGO_DEBUG - if false, minified CSS assets will be used by Largo, and these should be replaced with the custom-compiled assets.
	 */
	static function register_files( $files ) {
		self::$less_files = (array) $files;
		$suffix = (LARGO_DEBUG)? '.css' : '.min.css';

		// Keep a copy list with the '.css' extension
		$css_files = array();
		foreach ($files as $key => $file ) {
			$css_files[$key] = preg_replace( '#\.less$#', $suffix, $file );
		}
		self::$css_files = $css_files;
	}

	/**
	 * Set the file path for the directory with the LESS files and
	 * URI for the directory with the outputted CSS.
	 *
	 * @param string $less_dir
	 * @param string $css_dir_uri
	 */
	static function register_directory_paths( $less_dir, $css_dir_uri ) {
		self::$less_dir = $less_dir;
		self::$css_dir_uri  = $css_dir_uri;
	}

	/**
	 * Set the variables.less file
	 *
	 * @param string $variables_less_file - example 'variables.less'
	 */
	static function register_variables_less_file( $variables_less_file ) {
		self::$variables_less_file = $variables_less_file;
	}

	/**
	 * Get the compiled CSS for a LESS file.
	 *
	 * It will retrieved it from saved generated CSS or go
	 * ahead and compile it.
	 *
	 * @param string $less_file - the LESS file to compile
	 *
	 * @return string the generated CSS
	 */
	static function get_css( $less_file, $variables ) {
		// Use the cached version saved to the DB
		if ( !empty( $variables['meta']->ID ) ) {
			$css = get_post_meta( $variables['meta']->ID, $less_file );

			if ( !empty( $css ) ) {
				$css = $css[0];
			} else {
				$css = self::compile_less( $less_file, $variables['variables'] );
				add_post_meta( $variables['meta']->ID, $less_file, addslashes( $css ) );

			}

			return $css;
		}

		return self::compile_less( $less_file, $variables['variables'] );
	}


	/**
	 * Compile a LESS file with our custom variables
	 *
	 * @param $string $less_file - 'style.less'
	 *
	 * @return string - the resulting CSS
	 */
	static function compile_less( $less_file, $variables ) {
		$variables = array_map(function($var) { return stripslashes($var); }, $variables);

		// Load LESS compiler if loaded
		if ( !class_exists('lessc') ) {
			require( dirname( __FILE__ ) . '/../lib/lessc.inc.php' );
		}

		$compiler = new lessc();
		// Set to compressed mode unless SCRIPT_DEBUG is true
		if ( !defined( 'JCLV_UNCOMPRESSED' ) || !JCLV_UNCOMPRESSED ) {
			$compiler->setFormatter("compressed");
		}
		$compiler->addImportDir( self::$less_dir );

		try {
			// Get the Less file and then replace variables.less with the update version
			$less = self::get_contents( self::$less_dir . $less_file );
			$less = self::replace_with_custom_variables( $less, $variables );

			$css = $compiler->compile( $less );
			$css = self::fix_urls( $css );

			/*
			 * Make all URLs protocol-relative by replacing https:// and http:// with //
			 */
			$css = str_replace(
				array(
					'http:',
					'https:'
				),
				'',
				$css
			);
			return $css;
		} catch ( Exception $e ) {
			return $less;
		}

	}

	/**
	 * Get the variable.less file path
	 */
	static function variable_file_path() {
		return self::$less_dir . '/' . self::$variables_less_file;
	}

	/**
	 * Replace the include for the variable file with a modified version
	 * with the custom values.
	 */
	static function replace_with_custom_variables( $less, $variables ) {

		// First, take variables.less and replace the values of the over-ridden variables.
		$variables_less = self::get_contents( self::variable_file_path() );

		// Parse out the variables. Each is defined per line in format: @<varName>: <varValue>;
		preg_match_all( '#^\s*@(?P<name>[\w-_]+):\s*(?P<value>[^;]*);#m', $variables_less, $matches );

		foreach ( $matches[0] as $index => $rule ) {
			$name = $matches['name'][$index];

			if ( !empty( $variables[$name] ) ) {
				$replacement_rule = "@{$name}: {$variables[$name]};";
				$variables_less = str_replace( $rule, $replacement_rule, $variables_less);
			}
		}

		// Second, replace the import statements for variables.less with our output
		$filename = str_replace( '\.less', '(\.less)?', preg_quote( self::$variables_less_file ) );
		$less = preg_replace( '#^@import ["\']'.$filename.'["\'];#m', $variables_less, $less );

		return $less;
	}


	/**
	 *
	 */
	static function fix_urls( $css ) {
		preg_match_all('#url\(([^)]+)\)#', $css, $matches );

		$find = array();
		$replace = array();

		foreach ( $matches[1] as $raw_url ) {
			$url = trim( $raw_url, " \t\n\r\0\x0B'\"" );

			// Don't replace for URLs with domain name, starting at the root, or just a fragment
			if ( 0 == preg_match( '@^(\w://|//|/|#)@', $url ) ) {
				$find[] = 'url('.$raw_url.')';
				$replace[] = 'url(' . self::$css_dir_uri . $url . ')';
			}
		}

		$css = str_replace( $find, $replace, $css );

		return $css;
	}

	/**
	 * Change the URL for the stylesheets that are the output of the LESS files.
	 */
	static function style_loader_src( $src, $handle ) {
		$base_url = get_template_directory_uri() . '/css/';
		$base_url_escape = preg_quote( $base_url );

		// Check if the src is one of our to replace with LESS intercept
		foreach ( self::$css_files as $key => $filename ) {
			if ( preg_match( '!^'.$base_url_escape. preg_quote( $filename ) .'(?<extra>[#\?].*)?$!', $src, $matches ) ) {
				$variables = self::get_custom_values();
				if ( is_null( $variables['meta'] ) ) {
					$variables['meta'] = (object) array('post_modified_gmt' => 0);
				}
				$query_args = array(
						'largo_custom_less_variable' => 1,
						'css_file' => $filename,
						'timestamp' => $variables['meta']->post_modified_gmt,
					);

				if ( isset( $_REQUEST['wp_customize'] ) && 'on' == $_REQUEST['wp_customize'] ) {
					$query_args['doing_customizer'] = 1;
				}

				return add_query_arg(
					$query_args,
					home_url( $matches['extra'] )
				);
			}
		}
		return $src;
	}

	/**
	 * Intercept the loading of the page to determine if we output the rendered CSS
	 */
	static function template_redirect() {
		// Exit if not our call
		if ( !filter_input( INPUT_GET, 'largo_custom_less_variable', FILTER_VALIDATE_BOOLEAN ) ) {
			return;
		}

		$css_file = filter_input( INPUT_GET, 'css_file', FILTER_SANITIZE_STRING );

		header( 'Content-Type: text/css', true, 200 );
		header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + 31536000) . ' GMT' ); // 1 year

		// Echo nothing if the file is missing
		if ( empty( $css_file ) ) {
			echo '';
			exit;
		}

		// Get the array index for $css_files because it matches $less_files
		$key = array_search( $css_file, self::$css_files );

		// Echo nothing if file is not registered
		if ( $key===false ) {
			echo '';
			exit;
		}

		if ( isset( $_REQUEST['doing_customizer'] ) && 1 == $_REQUEST['doing_customizer'] ) {
			$variables = get_transient( 'largo_customizer_less_variables' );
		}
		if ( empty( $variables ) ) {
			$variables = self::get_custom_values();
		}
		echo "/* Custom LESS Variables {$variables['meta']->post_modified_gmt} */\n";
		echo self::get_css( self::$less_files[$key], $variables );

		exit;
	}

	/**
	 * Display a success message
	 */
	static function success_admin_notices() {
		echo '<div id="message" class="updated fade"><p><strong>' . __( 'CSS custom variables saved.', 'largo' ) . '</strong></p></div>';
	}

	/**
	 * Display a success message
	 */
	static function reset_admin_notices() {
		echo '<div id="message" class="error fade"><p><strong>' . __( 'Values reset to defaults.', 'largo' ) . '</strong></p></div>';
	}

	/**
	 * Register the admin page
	 */
	static function admin_menu() {
		$parent = 'themes.php';
		$title = __( 'CSS Variables', 'largo' );
		$hook = add_theme_page( $title, $title, 'edit_theme_options', 'largo_custom_less_variables', array( __CLASS__, 'admin' ) );

		add_action( "admin_head-$hook", array( __CLASS__, 'admin_head' ) );
		//add_action( "load-revision.php", array( 'Jetpack_Custom_CSS', 'prettify_post_revisions' ) );
		//add_action( "load-$hook", array( 'Largo_Custom_Less_Variables', 'update_title' ) );
	}

	/**
	 * Render the admin page content
	 */
	static function admin() {
		$revision = filter_input( INPUT_GET, 'revision', FILTER_SANITIZE_NUMBER_INT );

		add_meta_box( 'submitdiv', __( 'Publishing Options', 'largo' ), array( __CLASS__, 'publish_box' ), 'customlessvariables', 'side' );

		//wp_delete_post_revision
		$post = self::get_post();
		if ( !empty( $post ) && wp_get_post_revisions( $post->ID ) ) {
			add_meta_box( 'revisionsdiv', __( 'CSS Variables Revisions', 'largo' ), array( __CLASS__, 'revisions_meta_box' ), 'customlessvariables', 'side' );
		}

		?>
		<div class="wrap columns-2">
			<h2><?php _e( 'CSS Variables', 'largo' ); ?></h2>
			<form id="custom-css-variables" action="themes.php?page=largo_custom_less_variables" method="post">
				<?php wp_nonce_field( 'customlessvariables', 'customlessvariables' ) ?>
				<?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>
				<?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
				<input type="hidden" name="action" value="save" />
				<div id="poststuff" class="metabox-holder has-right-sidebar">
					<p class="css-support"><?php echo apply_filters( 'largo_custom_less_variables_intro', __( 'Customize the appearance of this theme by changing key LESS used for generating CSS.', 'largo' ) ); ?></p>
					<div id="postbox-container-1" class="inner-sidebar">
						<?php do_meta_boxes( 'customlessvariables', 'side', array() ); ?>
					</div>
					<div id="post-body">
						<div id="post-body-content">
							<div class="custom-less-variables">
								<?php
								$field_groups = self::get_editable_variables();

								$group_names = apply_filters( 'largo_custom_less_variables_group_order', array_keys( $field_groups ) );

								// Setup the field callbacks
								$field_type_callbacks = array(
									'color' => array( __CLASS__, 'color_type_field' ),
									'pixels' => array( __CLASS__, 'pixels_field' ),
									'dropdown' => array( __CLASS__, 'dropdown_field' ),
								);
								$field_type_callbacks = apply_filters( 'largo_custom_less_variables_types_callbacks', $field_type_callbacks );

								$values = self::get_custom_values( null, $revision );

								foreach ( $group_names as $group_name ) {
									if ( $group_name != '_default' ) {
										echo '<h3>', esc_html( $group_name ), '</h3>';
									}

									foreach ( $field_groups[$group_name] as $field_name => $field ) {
										echo '<div class="field field-', esc_attr($field['type']), '" id="field-',$field_name,'-row">';
										$form_name = 'field['.$field_name.']';
										$form_id = 'field-'.$field_name;
										$value = empty( $values['variables'][$field_name] ) ? trim( $field['default_value'] ) : $values['variables'][$field_name];
										echo '<label id="',$form_id,'">', $field['label'], '</label> ';

										if ( isset( $field_type_callbacks[$field['type']] ) ) {
											call_user_func_array( $field_type_callbacks[$field['type']], array( $field, $value, $field['default_value'], $form_name, $form_id ) );
										} else {
											echo '<input type="text" name="', $form_name, '" id="', $form_id, '" size="40" value="', esc_attr($value),'" />';
										}

										echo '</div>';
									}
								}

								?>
							</div>
						</div>
					</div>
					<br class="clear" />
				</div>
			</form>
		</div>
		<?php
	}


	/**
	 * Register Javascript files and stylesheets.
	 */
	static function admin_head() {
		wp_enqueue_script( 'iris' ); // Colorpicker
		wp_enqueue_script( 'largo_custom_less_variable', get_template_directory_uri().'/js/custom-less-variables.js', array( 'jquery', 'iris' ), '20130405', true );
		wp_enqueue_style( 'largo_custom_less_variable', get_template_directory_uri().'/css/custom-less-variables.css', '20130405' );
		do_action( 'largo_custom_less_variable_head' );
	}

	/**
	 * Revision meta box
	 */
	static function revisions_meta_box() {
		$post = self::get_post();
		$revisions = wp_get_post_revisions( $post->ID );
		$current_revision = filter_input( INPUT_GET, 'revision', FILTER_SANITIZE_NUMBER_INT );

		?>
		<ol>
			<li>
				<?php if ( empty($current_revision) ): ?>
					<strong><?php echo mysql2date( 'j F, Y @ H:m:s', $post->post_modified ); ?></strong>
				<?php else: ?>
					<a href="themes.php?page=largo_custom_less_variables"><?php echo mysql2date( 'j F, Y @ H:m:s', $post->post_date ); ?></a>
				<?php endif; ?>
				<?php _e( '[Live]', 'largo' ); ?>
			</li>

			<?php foreach ( $revisions as $revision ): ?>
			<li>
				<?php if ( $current_revision == $revision->ID ): ?>
					<strong><?php echo mysql2date( 'j F, Y @ H:m:s', $revision->post_date ); ?></strong>
				<?php else: ?>
					<a href="themes.php?page=largo_custom_less_variables&amp;revision=<?php echo esc_attr($revision->ID); ?>"><?php echo mysql2date( 'j F, Y @ H:m:s', $revision->post_date ); ?></a>
				<?php endif; ?>
			</li>
			<?php endforeach; ?>
		</ol>
		<?php
	}

	/**
	 * Render the publish meta box
	 */
	static function publish_box() {
		?>
		<div id="minor-publishing">
			<!-- div id="misc-publishing-actions">
				<?php /* // $safecss_post = Jetpack_Custom_CSS::get_current_revision();
				<?php do_action( 'largo_custom_less_variables_submitbox_misc_actions' ); ?> */ ?>
				<p><a data-action="reset" class="button">Reset to defaults</a> <br/></p>
			</div -->
		</div>
		<div id="major-publishing-actions">
			<?php // <input type="button" class="button" id="preview" name="preview" value="<?php esc_attr_e( 'Preview', 'jetpack' ) " />
			?>
			<div id="publishing-action">
				<input type="submit" name="submit-action" value="<?php esc_attr_e( 'Reset All', 'largo' ); ?>" class="button" />
				<input type="submit" class="button-primary" id="save" name="save" value="<?php esc_attr_e( 'Save Variables', 'largo' ); ?>" />
			</div>
			<div class="clear"></div>
		</div>


		<?php
	}

	/**
	 * Get the custom values
	 *
	 * @param string $theme optional - the folder name of the theme, defaults to active theme
	 * @param int $revision optional - the revision ID, defaults to the current version
	 *
	 * @return associated array of values
	 */
	static function get_custom_values( $theme=null, $revision=null ) {
		if ( empty( $theme ) ) {
			$theme_data = wp_get_theme();
			$theme = $theme_data->get_stylesheet();
		}

		// Try to retrieve cached values
		$cache_key = 'customlessvars_' . $theme . '_' . ( empty( $revision ) ? 'current' : $revision );
		$cached = get_transient( $cache_key );
		if ( $cached !== false ) {
			return $cached;
		}

		// Need the current version of the settings
		$post = self::get_post();

		if ( empty( $post ) ) {
			$data = array( 'meta' => null, 'variables' => array() );
			set_transient( $cache_key, $data, self::CACHE_DURATION );
			return $data;
		}

		$post_version = $post;


		// If a current revision is defined
		if ( !empty($revision) && $post->ID != $revision ) {
			$post_version = get_post( $revision );

			if ( empty($post_version) || $post_version->post_parent != $post->ID || $post_version->post_type != 'revision' ) {
				$post_version = null;
			}

			if ( empty( $post_version ) ) {
				$data = array( 'meta' => null, 'variables' => array() );
				set_transient( $cache_key, $data, self::CACHE_DURATION );
				return $data;
			}
		}

		// Get the values
		$values = json_decode( $post_version->post_content, true );

		if ( empty( $values ) || !is_array( $values ) ) {
			$data = array( 'meta' => null, 'variables' => array() );
		} else {
			$data = array( 'meta' => $post_version, 'variables' => $values );
		}
		set_transient( $cache_key, $data, self::CACHE_DURATION );
		return $data;
	}

	/**
	 * Get the post the data is saved to
	 */
	static function get_post() {

		$theme_data = wp_get_theme();

		$post = get_posts( array(
			'post_type'      => self::POST_TYPE,
			'post_name'      => sanitize_title( $theme_data->get_stylesheet() ),
			'posts_per_page' => 1,
		));

		if ( count( $post ) == 0 ) {
			return null;
		}
		return $post[0];
	}

	/**
	 * Delete all custom variables saved
	 */
	static function reset_all() {

		$theme_data = wp_get_theme();
		$theme = $theme_data->get_stylesheet();

		//delete from posts
		$clv_posts = get_posts('numberposts=-1&post_type='.self::POST_TYPE.'&post_status=any');
		foreach ($clv_posts as $clv_post) {
			$revisions = wp_get_post_revisions( $clv_post->ID );
			wp_delete_post( $clv_post->ID, true );

			// Delete the transient
			$cache_key = 'customlessvars_'.$theme.'_current';
			delete_transient( $cache_key );

			foreach ( $revisions as $revision ) {
				$cache_key = 'customlessvars_'.$theme.'_'.$revision->ID;
				delete_transient( $cache_key );
			}
		}
	}

	/**
	 * Save or update custom values
	 *
	 * @param array $values - an associative array of values
	 * @param string $theme optional - the theme name, defaults to the active the theme
	 */
	static function update_custom_values( $values, $theme = null ) {

		if ( empty( $theme ) ) {
			$theme_data = wp_get_theme();
			$theme = $theme_data->get_stylesheet();
		}

		// Need the current version of the settings
		if ( $post = self::get_post() ) {
			$post_id = $post->ID;
		} else {
			$post_id = false;
		}

		if ( !is_array( $values ) ) {
			$values = array();
		} else {
			foreach ($values as $field => $value) {
				//fix the pixels ones
				if (strpos($field, "-pixels")) {
					$values[ str_replace("-pixels", "", $field) ] = $value . "px";
					unset($values[$field]);
				}
			}
		}

		$post_data = array(
			'post_content' => json_encode( $values ),
			'post_name'   => $theme,
			'post_type'   => self::POST_TYPE,
			'post_status' => 'publish'
		);

		if ( empty( $post_id ) ) {
			$post_id = wp_insert_post( $post_data );
		} else {
			$post_data['ID'] = $post_id;
			wp_update_post( $post_data );

			// Clear out meta data
			$meta_keys = get_post_custom_keys( $post_id );
			if (count($meta_keys)) {
				foreach ( $meta_keys as $meta_key ) {
					delete_post_meta( $post_id, $meta_key );
				}
			}

			// Delete revisions past the five most recent
			$revisions = wp_get_post_revisions( $post_id );
			$revisions = array_slice( $revisions, 5 );
			foreach ( $revisions as $revision ) {
				wp_delete_post_revision( $revision->ID );
			}

		}

		// clear cache
		$cache_key = 'customlessvars_'.$theme.'_current';
		delete_transient( $cache_key );

		// Regenerate and cache
		foreach( self::$less_files as $less_file ) {
			if ( $compiled = self::compile_less( $less_file, $values ) ) {
				update_post_meta( $post_id, $less_file, addslashes( $compiled ) );
			}
		}

	}

	/**
	 * Parse the variable.less to retrieve the editable values
	 */
	static function get_editable_variables() {
		$variable_groups = array(
			'_default' => array()
		);

		$less = self::get_contents( self::variable_file_path() );

		// Parse
		$pattern = '#/\*\*\s+(?<comment>.*)\s+\*/\s*@(?P<name>[\w-_]+):\s*(?P<value>[^;]*);#Us';
		$comment_pattern = '#^\s*\*\s*@(?P<prop>\w+)\s+(?P<value>.*)$#mU';

		preg_match_all( $pattern, $less, $matches );

		foreach ( $matches['comment'] as $key => $comment ) {
			$name = $matches['name'][$key];
			$value = $matches['value'][$key];
			$props = array();

			// Parse out the properties in the comment block
			preg_match_all( $comment_pattern, $comment, $comment_matches );

			foreach ( $comment_matches['prop'] as $pkey => $prop ) {
				$props[$prop] = trim( $comment_matches['value'][$pkey] );
			}

			// Only add those with a type defined
			if ( isset( $props['type'] ) ) {

				// Ensure there is a group to add the variable to
				$group = empty( $props['group'] ) ? '_default' : $props['group'];
				if ( !isset( $variable_groups[$group] ) ) {
					$variable_groups[$group] = array();
				}

				// Ensure there is a label
				$label = empty( $props['label'] ) ? ucwords( str_replace( array('-', '_'), ' ', preg_replace( '/([a-z])([A-Z])/', '$1 $2', $name ) ) ) : $props['label'];

				// Ensure there is a default value
				$default_value = empty( $props['default_value'] ) ? $value : $props['default_value'];

				$variable_groups[$group][$name] = array(
					'name' => $name,
					'default_value' => $default_value,
					'properties' => $props,
					'label' => $label,
					'type' => $props['type'],
				);
			}
		}

		return $variable_groups;
	}


	/**
	 * Render the color field in the admin
	 */
	static function color_type_field( $field, $value, $default_value, $name, $id ) {
		echo '<input name="', $name, '" id="', $id, '" data-widget="colorpicker" value="', esc_attr($value), '" data-default-value="', $default_value,'" />';
	}

	/**
	 * Render a pixels field in the admin
	 */
	static function pixels_field( $field, $value, $default_value, $name, $id ) {
		$display_value = esc_attr(rtrim($value, 'px'));	//strip out "px", will be added back in before save
		echo '<input name="', str_replace("]","-pixels]", $name), '" id="', $id, '" type="number" step="1" value="', $display_value, '" data-default-value="', $default_value,'" /> pixels';
	}

	/**
	 * Render a dropdown in the admin
	 */
	static function dropdown_field( $field, $value, $default_value, $name, $id ) {
		$options = explode('|', $field['properties']['options']);
		echo '<select name="', $name, '" id="', $id, '" data-default-value="', $default_value,'">';
		foreach ($options as $opt) {
			echo '<option value="', esc_attr($opt), '"', selected($opt, $value, 0), '>', $opt, "</option>\n";
		}
		echo '</select>';
	}

}

Largo_Custom_Less_Variables::init();

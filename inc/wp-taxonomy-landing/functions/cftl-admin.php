<?php

/**
 * @package taxonomy-landing
 *
 * This file was originally part of Taxonomy Landing for WordPress
 * https://github.com/crowdfavorite/wp-taxonomy-landing
 *
 */

if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }

define('CFTL_SELF_DIR', get_template_directory_uri() . '/inc/wp-taxonomy-landing/');

/**
 * Registers the taxonomy-landing custom post type
 */
function cftl_register_taxonomy_landing() {
	register_post_type('cftl-tax-landing', array(
		'labels' => array(
			'name' => __('Landing Pages', 'cf-tax-landing'),
			'singular_name' => __('Landing Page', 'cf-tax-landing'),
			'add_new' => _x('Add New', 'cftl-tax-landing', 'cf-tax-landing'),
			'add_new_item' => __('Add New Landing Page', 'cf-tax-landing'),
			'edit_item' => __('Edit Landing Page', 'cf-tax-landing'),
			'new_item' => __('New Landing Page', 'cf-tax-landing'),
			'all_items' => __('All Landing Pages', 'cf-tax-landing'),
			'view_item' => __('View Landing Page', 'cf-tax-landing'),
			'search_items' => __('Search Landing Pages', 'cf-tax-landing'),
			'not_found' =>  __('No Landing Pages found', 'cf-tax-landing'),
			'not_found_in_trash' => __('No Landing Pages found in Trash', 'cf-tax-landing'),
			'parent_item_colon' => '',
			'menu_name' => __('Landing Pages', 'cf-tax-landing')
		),
		'supports' => array(
			'title',
			'thumbnail',
		),
		'public' => false,
		'exclude_from_search' => true,
		'show_in_nav_menus' => false,
		'show_ui' => true,
		'show_in_menu' => true,
		'hierarchical' => true,
	));
}

add_action('init', 'cftl_register_taxonomy_landing');

add_post_type_support( 'cftl-tax-landing', 'author' );

/**
 * Remove Permalink display / edit controls
 */
function cftl_get_sample_permalink_html($return, $id, $new_title, $new_slug) {
	$post = get_post($id);
	if (!empty($post) && $post->post_type == 'cftl-tax-landing') {
		return '';
	}
	return $return;
}

add_filter('get_sample_permalink_html', 'cftl_get_sample_permalink_html', 10, 4);

/**
 * Remove Categories and Tags submenu items
 */
function cftl_remove_submenu_items() {
	global $wp_taxonomies;
	foreach ($wp_taxonomies as $tax) {
		remove_submenu_page('edit.php?post_type=cftl-tax-landing', 'edit-tags.php?taxonomy=' . $tax->name . '&amp;post_type=cftl-tax-landing');
	}
}

add_action('admin_menu', 'cftl_remove_submenu_items');

function cftl_register_taxonomies_to_tax_landing() {
	global $wp_taxonomies;
	foreach ($wp_taxonomies as $taxonomy) {
		register_taxonomy_for_object_type($taxonomy->name, 'cftl-tax-landing');
	}
}

add_action('init', 'cftl_register_taxonomies_to_tax_landing', 99999);

function cftl_register_taxonomy_landing_filter($types) {
	$types = array_merge($types, array('post', 'cftl-tax-landing'));
	return $types;
}

add_filter('cftl-build-enabled-post-types', 'cftl_register_taxonomy_landing_filter');

function cftl_set_tax_landing_title_admin($title) {
	global $post;
	if (!$post) {
		return $title;
	}
	if ($post->post_type != 'cftl-tax-landing' || !is_admin()) {
		return $title;
	}
	$title_array = array();
	foreach (get_the_taxonomies($post) as $tax_name => $tax_list) {
		$title_array[] = strip_tags($tax_list);
	}
	if (empty($title_array)) {
		return sprintf(__('%s - No specified taxonomies.', 'cf-tax-landing'), $title);
	}
	return $title . ' - ' . implode(' ', $title_array);
}

function cftl_set_tax_landing_title($title) {
	global $post, $cftl_tax_landing;
	if (!$post) {
		return $title;
	}
	if ($post->post_type != 'cftl-tax-landing') {
		return $title;
	}

	$substitutions = array(
		'[archives]' => __('Archives', 'cf-tax-landing'),
		'[tax-title]' => $cftl_tax_landing['original_title'],
		);
	$title = str_replace(array_keys($substitutions), array_values($substitutions), $title);

	return $title;
}

if (is_admin()) {
	add_filter('the_title', 'cftl_set_tax_landing_title_admin');
}
else {
	add_filter('the_title', 'cftl_set_tax_landing_title');
}

function cftl_tax_landing_messages($messages) {
	global $post;
	$taxonomy_array = get_the_taxonomies($post);
	if (!empty($taxonomy_array)) {
		$taxonomy_links = implode(' ', $taxonomy_array);
	}
	else {
		$taxonomy_links = __("No taxonomies specified.", 'cf-tax-landing');
	}

	$messages['cftl-tax-landing'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf(__('Landing Page updated.  %s', 'cf-tax-landing'), $taxonomy_links),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Landing Page updated.', 'cf-tax-landing'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf(__('Landing Page restored to revision from %s', 'cf-tax-landing'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
		6 => sprintf(__('Landing Page published.  %s', 'cf-tax-landing'), $taxonomy_links),
		7 => __('Landing Page saved.', 'cf-tax-landing'),
		8 => sprintf(__('Landing Page submitted.  %s', 'cf-tax-landing'), $taxonomy_links),
		9 => sprintf(__('Landing Page scheduled for: <strong>%1$s</strong>.', 'cf-tax-landing'),
			// translators: Publish box date format, see http://php.net/date
			date_i18n(__('M j, Y @ G:i'), strtotime($post->post_date))),
		10 => sprintf(__('Landing Page updated.', 'cf-tax-landing')),
	);

	return $messages;
}

add_filter('post_updated_messages', 'cftl_tax_landing_messages');


function cftl_tax_landing_help($contextual_help, $screen_id, $screen) {
	// $contextual_help .= var_dump($screen); // use this to help determine $screen->id
	if ('cftl-tax-landing' == $screen->id) {
	$contextual_help =
		'<p>' . __('When adding or editing a Landing Page:', 'cf-tax-landing') . '</p>' .
		'<ul>' .
		'<li>' . __('Specify at least one category, tag, or custom taxonomy.') . '</li>' .
		'<li>' . __('When a taxonomy archive is requested, such as a category page, the first Landing Page with that taxonomy (if any) is used.') . '</li>' .
		'</ul>';
	}
	elseif ('edit-cftl-tax-landing' == $screen->id) {
		$contextual_help = '<p>' . __('Landing Pages are used to override the default category, tag, and custom Landing Pages with a Build-layout page.', 'cf-tax-landing') . '</p>';
	}
	return $contextual_help;
}
add_action('contextual_help', 'cftl_tax_landing_help', 10, 3);

function cftl_tax_landing_add_extras_box() {

	//add the 'Extras' box
	if (0 != count(get_page_templates())) {
		add_meta_box(
			'cftl_tax_landing_extras',
			__('Extras', 'largo'),
			'cftl_tax_landing_extras_box',
			'cftl-tax-landing',
			'side',
			'default'
		);
	}

	//add the 'Header' box
	add_meta_box(
		'cftl_tax_landing_header',
		__('Header', 'largo'),
		'cftl_tax_landing_header',
		'cftl-tax-landing',
		'normal',
		'high'
	);

	//add the 'Main' box
	add_meta_box(
		'cftl_tax_landing_main',
		__('Main', 'largo'),
		'cftl_tax_landing_main',
		'cftl-tax-landing',
		'normal',
		'high'
	);

	//add the 'Footer' box
	add_meta_box(
		'cftl_tax_landing_footer',
		__('Footer', 'largo'),
		'cftl_tax_landing_footer',
		'cftl-tax-landing',
		'normal',
		'default'
	);

	//remove various Largo meta boxes we don't need
	$boxen = array('tagsdiv-post_tag', 'wpbdm-categorydiv', 'tagsdiv-wpbdm-tags', 'prominencediv', 'categorydiv', 'pageparentdiv', 'tagsdiv-media-sources', 'tagsdiv-argo-link-tags');
	foreach ($boxen as $box_name) {
		remove_meta_box($box_name, 'cftl-tax-landing', 'side');
	}
}
add_action('add_meta_boxes', 'cftl_tax_landing_add_extras_box', 99);	//do this late so we can remove other meta boxes


function cftl_tax_landing_extras_box($post) {
	wp_nonce_field(plugin_basename(__FILE__), 'cftl_tax_landing_extras_nonce');

	$taxonomy_array = get_the_taxonomies($post);
	if (!empty($taxonomy_array)) {
		$taxonomy_links = implode('<br/>', $taxonomy_array);
	}
	else {
		$taxonomy_links = __("No taxonomies specified.", 'cf-tax-landing');
	}

	$page_template = get_post_meta($post->ID, '_wp_page_template', true);
	?>
<div class="form-field">
	<label for="cftl_page_template"><?php _e('Page Template', 'cf-tax-landing') ?></label>
	<select name="cftl_page_template" id="cftl_page_template">
		<option value=""<?php echo empty($page_template) ? ' selected="selected"' : '' ?>><?php _e('No Template (Use Post Templates)', 'cf-tax-landing'); ?></option>
		<option value="default"<?php echo ("default" == $page_template) ? ' selected="selected"' : ''?>><?php _e('Default (Page) Template', 'cf-tax-landing'); ?></option>
		<?php page_template_dropdown($page_template); ?>
	</select>
</div>
<div class="form-field">
	<label><?php _e('Current taxonomy links:', 'cf-tax-landing') ?></label><br/>
	<?php echo $taxonomy_links; ?>
</div>
<?php
}

function cftl_tax_landing_save_extras($post_id) {
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if (!isset($_POST['post_type']) || $_POST['post_type'] != 'cftl-tax-landing') return;
	if (!current_user_can('edit_post', $post_id)) return;

	if (!isset($_POST['cftl_tax_landing_extras_nonce']) || !wp_verify_nonce($_POST['cftl_tax_landing_extras_nonce'], plugin_basename(__FILE__))) {
		return;
	}

	$page_template = isset($_POST['cftl_page_template']) ? $_POST['cftl_page_template'] : null;
	$page_templates = get_page_templates();
	if (empty($page_template) || ('default' != $page_template && !in_array($page_template, $page_templates))) {
		delete_post_meta($post_id, '_wp_page_template');
	} else {
		update_post_meta($post_id, '_wp_page_template', $page_template);
	}
}
add_action('save_post', 'cftl_tax_landing_save_extras');

function cftl_post_type_link($post_link, $post) {
	if ($post->post_type != "cftl-tax-landing") {
		return $post_link;
	}
	$tax = get_object_taxonomies($post, 'object');
	$term = null;
	foreach ($tax as $label => $tax_obj) {
		$terms = get_object_term_cache($post->ID, $label);
		if (!empty($terms)) {
			$term = array_shift($terms);
			break;
		}
	}
	if (!empty($term)) {
		return get_term_link($term);
	}
	return $post_link;
}
add_filter('post_type_link', 'cftl_post_type_link', 10, 2);


function cftl_tax_landing_header($post) {
	wp_nonce_field(plugin_basename(__FILE__), 'cftl_tax_landing_header');
	$fields = ($post->post_title) ? get_post_custom( $post->ID ) : cftl_field_defaults();
	?>
<div class="form-field-enable">
	<h4>Enabled?</h4>
	<div>
	<label for="cftl_header_enabled">
		<input type="checkbox" id="cftl_header_enabled" name="header_enabled" <?php checked( $fields['header_enabled'][0], 1) ?> value="1" />
		Yes, display header
	</label>
	</div>
</div>
<div class="form-field">
	<h4>Show Series Byline?</h4>
	<div>
	<label for="cftl_show_series_byline">
		<input type="checkbox" id="cftl_show_series_byline" name="show_series_byline" <?php checked( $fields['show_series_byline'][0], 1) ?> value="1" />
	</label>
	</div>
</div>
<div class="form-field">
	<h4>Show Social Media Sharing Links?</h4>
	<div>
	<label for="cftl_show_sharebar">
		<input type="checkbox" id="cftl_show_sharebar" name="show_sharebar" <?php checked( $fields['show_sharebar'][0], 1) ?> value="1" />
	</label>
	</div>
</div>
<div class="form-field-radios-stacked">
	<h4>Layout Style</h4>
	<div>
		<input type="radio" name="header_style" id="header_style_standard" value="standard" <?php checked( $fields['header_style'][0], 'standard') ?> />
		<label for="header_style_standard">Standard</label>
		<div class="description">Uses title, description and featured image</div>

		<input type="radio" name="header_style" id="header_style_alternate" value="alternate" <?php checked( $fields['header_style'][0], 'alternate') ?> />
		<label for="header_style_alternate">Alternate</label>
		<div class="description">Uses title, description and custom HTML</div>
	</div>
</div>
<div class="form-field">
	<h4>Description</h4>
	<div>
		<textarea name="excerpt" id="excerpt"><?php echo $post->post_excerpt; ?></textarea>
	</div>
</div>
<div class="form-field-wysiwyg" id="header-html" <?php if ($fields['header_style'][0] != 'alternate') echo 'style="display:none;"'; ?>>
	<h4>Custom HTML</h4>
	<div>
		<?php wp_editor( $post->post_content, 'content', array(
			'wpautop' => false,
			'textarea_rows' => 5,
			'teeny' => true,
		)); ?>
	</div>
</div>
<?php
}

function cftl_tax_landing_main($post) {
	wp_nonce_field(plugin_basename(__FILE__), 'cftl_tax_landing_main');
	$fields = ($post->post_title) ? get_post_custom( $post->ID ) : cftl_field_defaults();
	$fields['show'] = maybe_unserialize($fields['show'][0]);
	?>
<div class="form-field-radios">
	<h4>Layout</h4>
	<div class="options">
		<div>
			<input type="radio" name="cftl_layout" id="layout_two_column" value="two-column" <?php checked( $fields['cftl_layout'][0], 'two-column') ?> />
			<label for="layout_two_column" class="cols" id="two-col">Two Column</label>
		</div>
		<div>
			<input type="radio" name="cftl_layout" id="layout_three_column" value="three-column" <?php checked( $fields['cftl_layout'][0], 'three-column') ?> />
			<label for="layout_three_column" class="cols" id="three-col">Three Column</label>
		</div>
		<div>
			<input type="radio" name="cftl_layout" id="layout_one_column" value="one-column" <?php checked( $fields['cftl_layout'][0], 'one-column') ?> />
			<label for="layout_one_column" class="cols" id="one-col">One Column</label>
		</div>
		<div id="explainer" class="<?php echo $fields['cftl_layout'][0]; ?>">
			<span class="one-column">No regions: posts take up full width </span>
			<span class="two-column">One widget region, called "Series <?php echo cftl_title($post); ?>: Right"</span>
			<span class="three-column">Two widget regions, called "Series <?php echo cftl_title($post); ?>: Left" and "Series <?php echo cftl_title($post); ?>: Right"</span>
		</div>
	</div>
</div>
<div class="form-field-select">
	<h4>Posts Per Page</h4>
	<div>
		<select name="per_page">
			<?php
				$options = array("5", "10", "15", "20", "30", "all");
				foreach ($options as $opt) {
					echo '<option value="', $opt, '"', selected( $fields['per_page'][0], $opt), '>', $opt, "</option>\n";
				}
			?>
		</select>
	</div>
</div>
<div class="form-field-select">
	<h4>Post Order</h4>
	<div>
		<?php
			//allow 'custom' if we have a single term
			$terms = get_the_terms( $post->ID, 'series');
			if (count($terms) == 1) $series_id = $terms[0]->term_taxonomy_id;
		?>
		<select name="post_order">
			<?php
				$options = array(
					"Newest first" => 'DESC',
					"Oldest first" => 'ASC',
					"Featured, then newest first" => 'featured, DESC',
					"Featured, then oldest first" => 'featured, ASC',
				);
				if ($series_id) $options["Custom"] = "custom";
				foreach ($options as $label => $opt) {
					echo '<option value="', $opt, '"', selected( $fields['post_order'][0], $opt), '>', $label, "</option>\n";
				}
			?>
		</select>
		<?php if ($series_id) :
			add_thickbox();
		?>
		<a class="thickbox custom" href="#TB_inline?width=600&height=550&inlineId=custom-order">Manage</a>
		<div id="custom-order">
			<div>
				<div id="post-list">
					<p><?php _e('Drag and drop to reorder posts. Newly-added posts will appear at the bottom', 'largo'); ?></p>
					<ul id="postsort" data-series-id="<?php echo $series_id; ?>">
						<?php cftl_load_posts( $series_id ); ?>
					</ul>
					<div class="publishing">
						<input type="submit" name="cancel" value="<?php esc_attr_e( 'Cancel', 'largo' ); ?>" class="button" />
						<input type="submit" class="button-primary" id="save-order" value="<?php esc_attr_e( 'Save & Close', 'largo' ); ?>" />
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>
<div class="form-field-checkboxes">
	<h4>Show</h4>
	<div>
		<label for="show-image">
			<input type="checkbox" id="show-image" name="show[image]" value="1" <?php checked ($fields['show']['image'], 1 ) ?> /> Featured Image
		</label>
		<label for="show-date">
			<input type="checkbox" id="show-excerpt" name="show[excerpt]" value="1" <?php checked ($fields['show']['excerpt'], 1 ) ?> /> Excerpt
		</label>
		<label for="show-byline">
			<input type="checkbox" id="show-byline" name="show[byline]" value="1" <?php checked ($fields['show']['byline'], 1 ) ?> /> Byline
		</label>
		<label for="show-tags">
			<input type="checkbox" id="show-tags" name="show[tags]" value="1" <?php checked ($fields['show']['tags'], 1 ) ?> /> Categories/Tags
		</label>
	</div>
</div>
<?php
}

function cftl_tax_landing_footer ( $post ) {
	wp_nonce_field( plugin_basename(__FILE__), 'cftl_tax_landing_footer' );
	$fields = ($post->post_title) ? get_post_custom( $post->ID ) : cftl_field_defaults();
	?>
<div class="form-field-radios-stacked">
	<h4>Layout Style</h4>
	<div>
		<input type="radio" name="footer_style" id="footer_style_none" value="none" <?php checked( $fields['footer_style'][0], 'none') ?> />
	    <label for="footer_style_none">None</label>
	    <div class="description">Do not display a footer</div>

	    <input type="radio" name="footer_style" id="footer_style_widget" value="widget" <?php checked( $fields['footer_style'][0], 'widget') ?> />
	    <label for="footer_style_widget">Use Widget</label>
	    <div class="description">Implements a "Series <?php echo cftl_title($post); ?>: Bottom" widget</div>

	    <input type="radio" name="footer_style" id="footer_style_custom" value="custom" <?php checked( $fields['footer_style'][0], 'custom') ?> />
	    <label for="footer_style_custom">Custom HTML</label>
	    <div class="description">Implements custom HTML entered below</div>
	</div>
</div>
<div class="form-field-wysiwyg" id="footer-html" <?php if ($fields['footer_style'][0] != 'custom') echo 'style="display:none;"'; ?>>
	<h4>Custom HTML</h4>
	<div>
		<?php wp_editor( $fields['footerhtml'][0], 'footerhtml', array(
			'wpautop' => false,
			'textarea_rows' => 5,
			'teeny' => true,
		)); ?>
	</div>

</div>
<?php
}

function cftl_field_defaults( ) {
	return array(
		'header_enabled' => array(1),
		'show_series_byline' => array(1),
		'show_sharebar' => array(1),
		'header_style' => array('standard'),
		'cftl_layout' => array('two-column'),
		'per_page' => array('10'),
		'post_order' => array('DESC'),
		'show' => array('image' => 1, 'excerpt' => 1, 'byline' => 1, 'tags' => 0),
		'footer_enabled' => array(1),
	);
}

function cftl_title( $post ) {
	if (!empty ($post->post_title)) return $post->post_title;
	return '[post title]';
}


function cftl_tax_landing_save_layout($post_id) {
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if (!isset($_POST['post_type']) || $_POST['post_type'] != 'cftl-tax-landing') return;
	if (!current_user_can('edit_post', $post_id)) return;

	if (!isset($_POST['cftl_tax_landing_header']) || !wp_verify_nonce($_POST['cftl_tax_landing_header'], plugin_basename(__FILE__))) {
		return;	//TO DO: verify main and footer nonces too?
	}

	//update all the post meta stuff
	$layout_fields = array(
		'header_enabled',
		'show_series_byline',
		'show_sharebar',
		'header_style',
		'cftl_layout', //needs to instantiate widget regions
		'per_page',
		'post_order',
		'show',	//maybe serialize these four?
		'footer_style',
		'footerhtml'	//instantiate another widget region
	);

	foreach ($layout_fields as $field_name) {
		update_post_meta($post_id, $field_name, $_POST[$field_name]);	//do I need to sanitize this?
	}
}
add_action('save_post', 'cftl_tax_landing_save_layout');


/**
 * Instantiate all our necessary widget regions
 */
function cftl_custom_sidebars() {
	//get all the left ones and the titles they connect to
	$left_widgets = cftl_get_meta_values( 'cftl_layout', 'three-column' );
	foreach ($left_widgets as $widget ) {
		$sidebar_slug = largo_make_slug( $widget->post_title );
		if ( $sidebar_slug ) {
			register_sidebar( array(
				'name' 			=> __( 'Series ' . $widget->post_title . ": Left", 'largo' ),
				'id' 			=> $sidebar_slug . "_left",
				'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
				'after_widget' 	=> '</aside>',
				'before_title' 	=> '<h3 class="widgettitle">',
				'after_title' 	=> '</h3>'
			) );
			register_sidebar( array(
				'name' 			=> __( 'Series ' . $widget->post_title . ": Right", 'largo' ),
				'id' 			=> $sidebar_slug . "_right",
				'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
				'after_widget' 	=> '</aside>',
				'before_title' 	=> '<h3 class="widgettitle">',
				'after_title' 	=> '</h3>'
			) );
		}
	}

	//get all the right ones and the titles they connect to
	$right_widgets = cftl_get_meta_values( 'cftl_layout', 'two-column' );
	foreach ($right_widgets as $widget ) {
		$sidebar_slug = largo_make_slug( $widget->post_title );
		if ( $sidebar_slug ) {
			register_sidebar( array(
				'name' 			=> __( 'Series ' . $widget->post_title . ": Right", 'largo' ),
				'id' 			=> $sidebar_slug . "_right",
				'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
				'after_widget' 	=> '</aside>',
				'before_title' 	=> '<h3 class="widgettitle">',
				'after_title' 	=> '</h3>'
			) );
		}
	}

	//get all the footer ones and the titles they connect to
	$footer_widgets = cftl_get_meta_values( 'footer_style', 'widget' );
	foreach ($footer_widgets as $widget ) {
		$sidebar_slug = largo_make_slug( $widget->post_title );
		if ( $sidebar_slug ) {
			register_sidebar( array(
				'name'       	=> __( 'Series ' . $widget->post_title . ": Footer", 'largo' ),
				'id' 			=> $sidebar_slug . "_footer",
				'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
				'after_widget' 	=> '</aside>',
				'before_title' 	=> '<h3 class="widgettitle">',
				'after_title' 	=> '</h3>'
			) );
		}
	}
}
add_action( 'widgets_init', 'cftl_custom_sidebars' );

function cftl_get_meta_values( $key = '', $value = '', $type = 'cftl-tax-landing', $status = 'publish' ) {
  global $wpdb;
  if( empty( $key ) )
      return;
  $r = $wpdb->get_results( $wpdb->prepare( "
      SELECT DISTINCT pm.meta_value, p.post_title
      FROM {$wpdb->postmeta} pm
      LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
      WHERE pm.meta_key = '%s'
      AND pm.meta_value = '%s'
      AND p.post_status = '%s'
      AND p.post_type = '%s'
  ", $key, $value, $status, $type ) );
  return $r;
}

/**
 * Load CSS and JS we need
 */
function cftl_admin_scripts() {
	$screen = get_current_screen();

	if( $screen->base == 'post' && $screen->post_type == 'cftl-tax-landing') {
		$url = get_template_directory_uri();
		wp_enqueue_script( 'series', $url.'/inc/wp-taxonomy-landing/series.js', array('jquery', 'jquery-ui-sortable'), '0.0.1', true );
		wp_enqueue_style( 'series', $url.'/inc/wp-taxonomy-landing/series.css' );
	}
}
add_action( 'admin_enqueue_scripts', 'cftl_admin_scripts');


/**
 * Helper function for loading in posts for custom post manager
 */
function cftl_load_posts( $series_id ) {
	global $wpdb;
	$post_list = $wpdb->get_results("
SELECT p.ID, p.post_title FROM $wpdb->posts AS p
INNER JOIN $wpdb->term_relationships AS tr ON (p.ID = tr.object_id)
LEFT JOIN $wpdb->postmeta AS mt2 ON (p.ID = mt2.post_id AND mt2.meta_key = 'series_{$series_id}_order')
WHERE ( tr.term_taxonomy_id IN ({$series_id}) )
AND p.post_type = 'post'
AND (p.post_status = 'publish' OR p.post_status = 'private')
GROUP BY p.ID
ORDER BY ISNULL(mt2.meta_value+0) ASC, mt2.meta_value+0 ASC, p.post_date DESC");

	foreach($post_list as $p) {
		echo '<li id="pid_', $p->ID, '">', $p->post_title, "</li>";
	}

}


/**
 * Handle AJAX request for post order
 */
add_action('wp_ajax_series_sort', 'cftl_order_save');
function cftl_order_save() {
	$meta_key = "series_" . $_POST['series_id'] . "_order";
	for ($i = 1; $i <= count($_POST['pid']); $i++ ) {
		update_post_meta( $_POST['pid'][$i-1], $meta_key, $i);
	}
	echo "updated";
}
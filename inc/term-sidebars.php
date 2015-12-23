<?php

/**
 * Display the fields for selecting icons for terms in the "post-type" taxonomy
 */
class Largo_Term_Sidebars {

	function __construct() {
		add_action( 'edit_category_form_fields', array( $this, 'display_fields' ) );
		add_action( 'edit_tag_form_fields', array( $this, 'display_fields' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts') );
		add_action( 'edit_terms', array( $this, 'edit_terms' ) );
		add_action( 'create_term', array( $this, 'edit_terms' ) );
	}

	/**
	 *
	 */
	function get_sidebar_taxonomies() {
		if ( empty($this->_sidebar_taxonomies) ) {
			$this->_sidebar_taxonomies = apply_filters( 'largo_get_sidebar_taxonomies', array( 'category', 'post_tag', 'series' ) );
		}
		return $this->_sidebar_taxonomies;
	}

	/**
	 * Renders the form fields on the term's edit page
	 */
	function display_fields( $term ) {
		if ( !in_array( $term->taxonomy, $this->get_sidebar_taxonomies() ) ) {
			// abort if the term doesn't belong to the taxonomies to have icons
			return;
		}

		//get the proxy post id
		$post_id = largo_get_term_meta_post( $term->taxonomy, $term->term_id );
		$current_value = largo_get_term_meta( $term->taxonomy, $term->term_id, 'custom_sidebar', true );
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="custom_sidebar"><?php _e('Archive Sidebar', 'largo'); ?></label></th>
			<td>
				<select name="custom_sidebar" id="custom_sidebar" style="min-width: 300px;">
					<?php largo_custom_sidebars_dropdown( $current_value, false, $post_id ); //get the options ?>
				</select>
				<br/>
				<p class="description"><?php _e("The sidebar to display on this term's archive page.", 'largo'); ?></p>
				<?php
				wp_nonce_field( 'custom_sidebar-'.$term->term_id, '_custom_sidebar_nonce' );
				?>
			</td>
		</tr>
		<?php
	}

	/**
	 * Renders the form fields for the new form creation on the term listing paga
	 */
	function display_add_new_field( $taxonomy ) {
		?>
		<div class="form-field">
			<label for="custom_sidebar"><?php _e('Archive Sidebar', 'largo'); ?></label>
			<select name="custom_sidebar" id="custom_sidebar" style="min-width: 300px;">
				<?php largo_custom_sidebars_dropdown( '', false, 0 ); //get the options ?>
			</select>
			<p class="description"><?php _e('The sidebar to show on this term\'s archive.', 'largo'); ?></p>
			<?php wp_nonce_field( 'custom_sidebar-new', '_custom_sidebar_nonce' ); ?>
		</div>
		<?php
	}

	/**
	 * Attach the Javascript and Stylesheets to the term edit page
	 */
	function admin_enqueue_scripts( $hook_suffix ) {

		if ( $hook_suffix == 'edit-tags.php' && !empty($_REQUEST['taxonomy']) ) {
			if ( !in_array( $_REQUEST['taxonomy'], $this->get_sidebar_taxonomies() ) ) {
				// abort if the term doesn't belong to the taxonomies to have icons
				return;
			}

			add_action( $_REQUEST['taxonomy'].'_add_form_fields', array( $this, 'display_add_new_field' ) );
		}
	}

	/**
	 * Save the results from the term edit page
	 */
	function edit_terms( $term_id ) {
		if (isset($_POST['action']) && $_POST['action'] == 'add-tag')
			$nonce_action = 'custom_sidebar-new';
		else
			$nonce_action = 'custom_sidebar-' . $term_id;

		if ( isset($_POST['_custom_sidebar_nonce']) && wp_verify_nonce($_POST['_custom_sidebar_nonce'], $nonce_action ) ) {
			$taxonomy = $_REQUEST['taxonomy'];
			largo_update_term_meta( $taxonomy, $term_id, 'custom_sidebar', $_POST['custom_sidebar'] );
		}
	}
}

$largo['term-sidebars'] = new Largo_Term_Sidebars();

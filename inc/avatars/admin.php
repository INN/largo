<?php

function largo_load_avatar_js() {
	wp_enqueue_script('largo_avatar_js', get_template_directory_uri() . '/inc/avatars/js/avatars.js', array('jquery'));
	wp_localize_script('largo_avatar_js', 'largo_avatar_js_L10n', array(
		'update_text' => __('Click "Update Profile" to save your avatar.', 'largo')
	));
}
add_action('admin_enqueue_scripts', 'largo_load_avatar_js');

// Changes to the user profile edit page
function largo_add_edit_form_multipart_encoding() {
	echo ' enctype="multipart/form-data"';
}
add_action('user_edit_form_tag', 'largo_add_edit_form_multipart_encoding');

function largo_add_avatar_field($user) {
	$image_src = largo_get_avatar_src($user->ID, '128');

	largo_print_avatar_admin_css();
?>
	<h3>User avatar</h3>

	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="<?php echo LARGO_AVATAR_INPUT_NAME; ?>"><?php _e('Current avatar', 'largo'); ?></label></th>
				<td>
					<p id="largo-avatar-display">
						<?php if (!empty($image_src)) { ?>
							<img src="<?php echo $image_src[0]; ?>" width="<?php echo $image_src[1]; ?>" height="<?php echo $image_src[2]; ?>" /><br />
							<a href="<?php echo get_edit_post_link(largo_get_user_avatar_id($user->ID)); ?>"><?php _e('Edit', 'largo'); ?></a> | <a id="largo-remove-avatar" href="#"><?php _e('Remove', 'largo'); ?></a>
						<?php }

						if (empty($image_src) && largo_has_gravatar($user->user_email)) {
								echo get_avatar($user->ID);
								echo '<br />';
								_e('Currently using Gravatar. Change at <a href="http://gravatar.com/">gravatar.com</a> or choose a different image below.', 'largo');
						} ?>
					</p>

					<p id="largo-avatar-input" <?php if (!empty($image_src)) { ?>style="display:none;"<?php } ?>>
						<input type="file" name="<?php echo LARGO_AVATAR_INPUT_NAME; ?>" id="<?php echo LARGO_AVATAR_INPUT_NAME; ?>" />
					</p>
				</td>
			</tr>
		</tbody>
	</table>
<?php
}
add_action('edit_user_profile',  'largo_add_avatar_field');
add_action('show_user_profile',  'largo_add_avatar_field');

function largo_save_avatar_field($user_id) {
	if (has_files_to_upload(LARGO_AVATAR_INPUT_NAME)) {
		if (isset( $_FILES[LARGO_AVATAR_INPUT_NAME])) {
			$file = wp_upload_bits(
				$_FILES[LARGO_AVATAR_INPUT_NAME]['name'], null,
				@file_get_contents($_FILES[LARGO_AVATAR_INPUT_NAME]['tmp_name'])
			);

			if (FALSE === $file['error']) {
				$mime_type = wp_check_filetype($file['file']);
				$args = array(
					'guid' => $file['url'],
					'post_mime_type' => $mime_type['type'],
					'post_title' => preg_replace('/\.[^.]+$/', '', basename($file['file'])),
					'post_content' => '',
					'post_status' => 'inherit'
				);
				$id = wp_insert_attachment($args, $file['file']);

				if (!is_wp_error($id)) {
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					// Generate the metadata for the attachment, and update the database record.
					$metadata = wp_generate_attachment_metadata($id, $file['file']);
					$update = wp_update_attachment_metadata($id, $metadata);
					largo_associate_avatar_with_user($user_id, $id);
				}
			}
		}
	}
}
add_action('edit_user_profile_update', 'largo_save_avatar_field');
add_action('personal_options_update', 'largo_save_avatar_field');

// AJAX functions
function largo_remove_avatar() {
	$user_id = false;

	if (!empty($_POST['user_id']))
		$user_id = $_POST['user_id'];

	$ret = largo_remove_user_avatar($user_id);
	if (!empty($ret))
		echo json_encode(array('success' => true));
	else
		echo json_encode(array('success' => false));

	wp_die();
}
add_action('wp_ajax_largo_remove_avatar', 'largo_remove_avatar');

// Utilities
function has_files_to_upload($id) {
	return (!empty($_FILES)) && isset($_FILES[$id]);
}

function largo_associate_avatar_with_user($user_id, $avatar_id) {
	return update_user_meta($user_id, LARGO_AVATAR_META_NAME, $avatar_id);
}

function largo_remove_user_avatar($user_id=false) {
	if (empty($user_id)) {
		$user = wp_get_current_user();
		$user_id = $user->ID;
	}

	return update_user_meta($user_id, LARGO_AVATAR_META_NAME, false);
}

function largo_print_avatar_admin_css() { ?>
<style type="text/css">
	#largo-avatar-display img {
		padding: 4px;
		border: 1px solid #ccc;
		background: #fff;
	}
	#largo-avatar-input {
		display: inline-block;
		margin: 10px 0;
		padding: 8px;
		background: #fff;
		border: 1px solid #ccc;
		border-radius: 4px;
	}
</style>
<?php
}

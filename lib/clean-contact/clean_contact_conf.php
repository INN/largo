<?php ?>

<div class="wrap">

	<h2><?php _e( 'Clean Contact Settings', 'largo' ); ?></h2>

	<?php if ( ! empty( $_GET['message'] ) && 'updated' == $_GET['message'] ) : ?>

		<div id="message" class="updated fade"><p><?php _e( 'Settings saved.', 'largo' ); ?></p></div>

	<?php endif; ?>

	<form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
		<input type="hidden" name="action" value="clean_contact_settings" />
		<?php wp_nonce_field( 'clean-contact', 'nonce' ); ?>

		<table class="form-table">
			<tbody>

				<tr valign="top">
					<th scope="row"><label for="clean_contact_email"><?php _e( 'To E-mail address', 'largo' )?>:</label></th>
					<td><input name="clean_contact_email" id="clean_contact_email" value="<?php echo esc_attr( cc_get_option('clean_contact_email' ) ) ?>" class="regular-text" type="text" />
				</tr>

				<tr valign="top">

					<th scope="row"><label for="clean_contact_cc"><?php _e( 'CC E-mail address', 'largo' ) ?>:</label></th>
					<td><input name="clean_contact_cc" id="clean_contact_cc" value="<?php echo esc_attr( cc_get_option( 'clean_contact_cc') ); ?>" class="regular-text" type="text" />
				</tr>

				<tr valign="top">
					<th scope="row"><label for="clean_contact_bcc"><?php _e( 'BCC E-mail address', 'largo' )?>:</label></th>
					<td><input name="clean_contact_bcc" id="clean_contact_bcc" value="<?php echo esc_attr( cc_get_option( 'clean_contact_bcc' ) ); ?>" class="regular-text" type="text" />
				</tr>

				<tr valign="top">
					<th scope="row"><label for="clean_contact_router"><?php echo __( 'Contact Reasons & Recipients', 'largo' )?>:</label></th>
					<td><textarea name="clean_contact_router" id="clean_contact_router" class="regular-text" style="width:25em;" rows="4"><?php echo esc_textarea( cc_get_option('clean_contact_router') ); ?></textarea>
					<div class="description">Separate reasons and recipients with |, one per line, e.g. Zombie Invasion|shaun@inn.org</div>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="clean_contact_prefix"><?php _e( 'Subject prefix', 'largo' ) ?>:</label></th>
					<td><input name="clean_contact_prefix" id="clean_contact_prefix" value="<?php echo esc_attr( cc_get_option( 'clean_contact_prefix' ) ); ?>" class="regular-text" type="text" />
				</tr>

				<tr valign="top">
					<th scope="row"><label for="clean_contact_thanks"><?php _e( 'Thank you message', 'largo' ) ?>:</label></th>
					<td><input name="clean_contact_thanks" id="clean_contact_thanks" value="<?php echo esc_attr( cc_get_option( 'clean_contact_thanks' ) ) ?>" class="regular-text" type="text" />
				</tr>

				<tr valign="top">
					<th scope="row"><label for="clean_contact_thanks_url"><?php _e( 'Thank you url', 'largo' ) ?>:</label></th>
					<td><input name="clean_contact_thanks_url" id="clean_contact_thanks_url" value="<?php echo esc_attr( cc_get_option( 'clean_contact_thanks_url' ) ); ?>" class="regular-text" type="text" /><br /><span class="setting-description"><?php _e( 'Optional url to redirect to after send (overrides thank you message).', 'largo' ); ?></span> </td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="clean_contact_from_email"><?php _e( 'From address', 'largo' ) ?>:</label></th>
					<td><input name="clean_contact_from_email" id="clean_contact_from_email" value="<?php echo esc_attr( cc_get_option( 'clean_contact_from_email' ) ); ?>" class="regular-text" type="text" /><br /><span class="setting-description"><?php _e( 'Optional from address to use when sending email.  Unset, the users e-mail address will be used.', 'largo' ); ?></span> </td>
				</tr>

				<tr valign="top">
					<td colspan="2"><input type="checkbox" id="clean_contact_akismet" name="clean_contact_akismet" value="1" <?php checked( cc_get_option( 'clean_contact_akismet' ) ) ?> /><strong> <?php echo __( 'Filter messages for SPAM through the', 'largo' )?> <a href="?page=akismet-key-config">Akismet plugin</a></strong></td>
				</tr>

				<tr valign="top">
					<td colspan="2"><input type="checkbox" id="clean_contact_nocss" name="clean_contact_nocss" value="1" <?php checked( cc_get_option( 'clean_contact_nocss' ) ) ?> /><strong> <?php _e( "Don't use default CSS", 'largo' ) ?></td>
				</tr>

			</tbody>

		</table>

		<?php submit_button(); ?>

		<p><?php _e( 'Invoke the Clean Contact e-mail form on and page or post with the shortcode', 'largo' )?>:</a> <code><strong>[clean-contact]</strong></code></p>

		<p><?php _e( 'You can override all or any of the settings above in the shortcode', 'largo' )?></p>

		<code style="font-size: 1.1em"><strong>[clean-contact</strong> <strong>email="</strong><?php echo cc_get_option('clean_contact_email') ?><strong>"</strong> <strong>prefix="</strong><?php echo cc_get_option('clean_contact_prefix') ?><strong>"</strong> <strong>bcc=""</strong> <strong>subject=</strong>"<?php echo __( 'Greetings', 'largo' )?>" <strong>thanks_url=</strong>"<?php echo cc_get_option('clean_contact_thanks_url') ?><strong>"]</strong></code>

	</form>

</div>

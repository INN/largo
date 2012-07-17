<?php
/* 
 * ARGO THEME SETTINGS
 */
function argo_add_options_page() {
    add_theme_page( 'Argo', 'Theme Options', 'manage_options', 'argo', 'argo_options_page' );
}
add_action( 'admin_menu', 'argo_add_options_page' );

function argo_options_page() {
?>
    <div class="wrap">
    	<?php screen_icon(); ?>
    	<h2>Argo Theme Options</h2>
        <form action="options.php" method="post">
            <?php
            	settings_fields( 'argo' );
            	do_settings_sections( 'argo' );
            	submit_button();
            ?>
        </form>
    </div>
<?php
}

function argo_settings_init() {
    add_settings_section( 'argo_settings', false, 'argo_settings_section_callback', 'argo' );

    add_settings_field( 'donate_link', 'Donate Link',
		'argo_donate_link_callback', 'argo', 'argo_settings' );

	register_setting( 'argo', 'donate_link', 'esc_url_raw' );
    
    add_settings_field( 'show_related_content', 'Related Content',
		'argo_show_related_content_callback', 'argo', 'argo_settings' );

	register_setting( 'argo', 'show_related_content', 'absint' );

	add_settings_field( 'copyright_msg', 'Copyright and Credit',
		'argo_copyright_msg_callback', 'argo', 'argo_settings' );

	register_setting( 'argo', 'copyright_msg', 'sanitize_text_field' );
    
    add_settings_field( 'ga_id', 'Google Analytics ID<br />(UA-XXXXXXXX-X)',
		'argo_ga_id_callback', 'argo', 'argo_settings' );

	register_setting( 'argo', 'ga_id', 'sanitize_text_field' );
    
	add_settings_field( 'site_blurb', 'About This Site',
		'argo_site_blurb_callback', 'argo', 'argo_settings' );

	register_setting( 'argo', 'site_blurb', 'sanitize_text_field' );

	add_settings_section( 'argo_links', 'Header and Footer Links', '__return_false', 'argo' );

	$fields = array(
		'facebook' => 'Link to Facebook Profile',
		'twitter' => 'Link to Twitter Page',
		'youtube' => 'Link to YouTube Page',
		'flickr' => 'Link to Flickr Page',
		'gplus' => 'Link to Google Plus Page',
		'podcast' => 'Link to Podcast Feed',
	);

	foreach ( $fields as $field => $title ) {
		$field = $field . '_link';
		add_settings_field( $field, $title, 'argo_settings_field_link_callback',
			'argo', 'argo_links', array( 'field' => $field ) );
		register_setting( 'argo', $field, 'esc_url_raw' );
	}
}
add_action( 'admin_init', 'argo_settings_init' );

function argo_settings_section_callback() {
    echo '<p>The following fields are <strong>optional</strong>, but you may use them to add additional information about your site.</p>
    	<p>Note: To change social media settings for a user, view their edit profile screen.</p>';
}

function argo_show_related_content_callback() {
    $option = (bool) get_option( 'show_related_content', true ); ?>
	<label for="show_related_content"><input type="checkbox" value="1" name="show_related_content"<?php checked( $option, true ); ?> /> Show related posts on post pages?</label>
    <?php
}

function argo_copyright_msg_callback() {
    $option = esc_textarea( get_option( 'copyright_msg' ) );
    echo "<textarea name='copyright_msg' class='large-text' rows='3'>$option</textarea>
    	<br /><span class='description'>Appears in the footer. You can use <code>%d</code> and it will be replaced the current year.</span>";
}

function argo_ga_id_callback() {
    $option = esc_textarea( get_option( 'ga_id' ) );
	echo "<input type='text' name='ga_id' value='$option' class='regular-text' />
		<br /><span class='description'>If you use Google Analytics enter your ID here and the code will be included in the footer.</span>";
}

function argo_site_blurb_callback() {
    $option = esc_textarea( get_option( 'site_blurb' ) );
    echo "<textarea name='site_blurb' class='large-text' rows='3'>$option</textarea>
    	<br /><span class='description'>Appears in a widget.</span>";
}

function argo_settings_field_link_callback( $args ) {
	$field = $args['field'];
	echo '<input type="text" value="' . esc_url( get_option( $field ) ) . '" name="' . esc_attr( $field ) . '" class="regular-text" />';
}

function argo_copyright_message() {
    $msg = get_option( 'copyright_msg' );
    if ( ! $msg )
    	$msg = 'Copyright %s';
    printf( $msg, date( 'Y' ) );
}

?>
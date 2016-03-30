<?php
/**
 * Largo Mailchimp Signup Form widget
 *
 * Copies heavily form the WordPress Text Widget
 *
 * @package Largo
 * @since 5.5.0
 */

/**
 * Core class used to implement a Text widget.
 *
 * @since 2.8.0
 *
 * @see WP_Widget
 */
class largo_mailchimp_signup_widget extends WP_Widget {

	/**
	 * Sets up a new Text widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array('classname' => 'largo-mailchimp-signup', 'description' => __('A Mailchimp signup form with bot prevention and the ability to add custom fields'));
		parent::__construct('largo-mailchimp-signup', __('Largo Mailchimp Signup'), $widget_ops);
	}

	/**
	 * Outputs the content for the current Text widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Text widget instance.
	 */
	public function widget( $args, $instance ) {

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		$text = ! empty( $instance['text'] ) ? $instance['text'] : '';
		$forms = ! empty( $instance['forms'] ) ? $instance['forms'] : '';
		$anti_bot = ! empty( $instance['anti_bot'] ) ? $instance['anti_bot'] : '';
		$form_url = ! empty( $instance['form_url'] ) ? $instance['form_url'] : '';

		$text = ! empty( $form_url ) ? $text : 'Please configure this widget in the Dashboard.';

		echo $args['before_widget'];
		ob_start();
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
			<form action="<?php echo $form_url; ?>" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
				<?php echo $text; ?>
				<input type="email" value="email address" name="EMAIL" class="required email" id="mce-EMAIL">
				<?php echo $forms; ?>
				<div style="position: absolute; left: -5000px;"><input type="text" name="<?php echo $anti_bot; ?>" tabindex="-1" value=""></div>
				<input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn">
			</form>
		<?php
		$output = ob_get_clean();
		echo apply_filters( 'largo_mailchimp_signup_output', $output );
		echo $args['after_widget'];
	}

	/**
	 * Handles updating settings for the current Text widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['anti_bot'] = esc_attr( sanitize_text_field( $new_instance['anti_bot'] ) );
		$instance['form_url'] = esc_attr( sanitize_text_field( $new_instance['form_url'] ) );
		$instance['text'] = stripslashes( $new_instance['text'] );
		$instance['forms'] = stripslashes( $new_instance['forms'] );

		return $instance;
	}

	/**
	 * Outputs the Text widget settings form.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => __('Sign Up', 'largo'), 'text' => '' ) );
		$title = sanitize_text_field( $instance['title'] );
		$forms = sanitize_text_field( $instance['forms'] );
		$anti_bot = sanitize_text_field( $instance['anti_bot'] );
		$form_url = sanitize_text_field( $instance['form_url'] );
		?>

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'largo'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Call-to-action text', 'largo' ); ?></label>
		<textarea class="widefat" rows="2" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo esc_textarea( $instance['text'] ); ?></textarea></p>

		<p><label for="<?php echo $this->get_field_id('form_url'); ?>"><?php _e('Form submit URL', 'largo'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('form_url'); ?>" name="<?php echo $this->get_field_name('form_url'); ?>" type="text" value="<?php echo esc_attr($form_url); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'forms' ); ?>"><?php _e( 'Additional markup', 'largo' ); ?></label>
		<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('forms'); ?>" name="<?php echo $this->get_field_name('forms'); ?>"><?php echo esc_textarea( $instance['forms'] ); ?></textarea></p>

		<p><label for="<?php echo $this->get_field_id('anti_bot'); ?>"><?php _e('Anti-bot field ID', 'largo'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('anti_bot'); ?>" name="<?php echo $this->get_field_name('anti_bot'); ?>" type="text" value="<?php echo esc_attr($anti_bot); ?>" /></p>

		<?php
	}
}

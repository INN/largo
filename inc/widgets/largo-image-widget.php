<?php
/*
Plugin: Largo Image Widget
Description: A simple image widget that uses the native WordPress media manager to add image widgets to your site, adapted from Modern Tribe's Image Widget
*/

// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');

/**
 * Largo_Image_Widget class
 **/
class largo_image_widget extends WP_Widget {

	const VERSION = '4.0.7';

	const CUSTOM_IMAGE_SIZE_SLUG = 'largo_image_widget_custom';

	/**
	 * Largo Image Widget constructor
	 *
	 * @author Modern Tribe, Inc.
	 */
	function Largo_Image_Widget() {
		$widget_ops = array( 'classname' => 'widget_sp_image', 'description' => __( 'Showcase a single image with a Title, URL, and a Description', 'largo' ) );
		$control_ops = array( 'id_base' => 'widget_sp_image' );
		$this->WP_Widget('widget_sp_image', __('Image Widget', 'largo'), $widget_ops, $control_ops);
		add_action( 'sidebar_admin_setup', array( $this, 'admin_setup' ) );
		add_action( 'admin_head-widgets.php', array( $this, 'admin_head' ) );
	}

	/**
	 * Enqueue all the javascript.
	 */
	function admin_setup() {
		wp_enqueue_media();
		wp_enqueue_script( 'largo-image-widget', get_template_directory_uri() . '/js/image-widget.js', array( 'jquery', 'media-upload', 'media-views' ), self::VERSION );
		wp_localize_script( 'largo-image-widget', 'LargoImageWidget', array(
			'frame_title' => __( 'Select an Image', 'largo' ),
			'button_title' => __( 'Insert Into Widget', 'largo' ),
		) );
	}

	/**
	 * Widget frontend output
	 *
	 * @param array $args
	 * @param array $instance
	 * @author Modern Tribe, Inc.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		$instance = wp_parse_args( (array) $instance, self::get_defaults() );
		if ( !empty( $instance['imageurl'] ) || !empty( $instance['attachment_id'] ) ) {

			$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'] );
			$instance['description'] = apply_filters( 'widget_text', $instance['description'], $args, $instance );
			$instance['link'] = apply_filters( 'image_widget_image_link', esc_url( $instance['link'] ), $args, $instance );
			$instance['linktarget'] = apply_filters( 'image_widget_image_link_target', esc_attr( $instance['linktarget'] ), $args, $instance );
			$instance['width'] = apply_filters( 'image_widget_image_width', abs( $instance['width'] ), $args, $instance );
			$instance['height'] = apply_filters( 'image_widget_image_height', abs( $instance['height'] ), $args, $instance );
			$instance['align'] = apply_filters( 'image_widget_image_align', esc_attr( $instance['align'] ), $args, $instance );
			$instance['alt'] = apply_filters( 'image_widget_image_alt', esc_attr( $instance['alt'] ), $args, $instance );
			$instance['track'] = ($instance['track'] == "true") ? true : false;

			if ( !defined( 'IMAGE_WIDGET_COMPATIBILITY_TEST' ) ) {
				$instance['attachment_id'] = ( $instance['attachment_id'] > 0 ) ? $instance['attachment_id'] : $instance['image'];
				$instance['attachment_id'] = apply_filters( 'image_widget_image_attachment_id', abs( $instance['attachment_id'] ), $args, $instance );
				$instance['size'] = apply_filters( 'image_widget_image_size', esc_attr( $instance['size'] ), $args, $instance );
			}
			$instance['imageurl'] = apply_filters( 'image_widget_image_url', esc_url( $instance['imageurl'] ), $args, $instance );

			// Using extracted vars now
			extract( $instance );

			$title = apply_filters('widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base);

			//output the widget
			echo $before_widget;

			if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }

			echo self::get_image_html( $instance, true );

			if ( !empty( $description ) ) {
				echo '<div class="' . $this->widget_options['classname'] . '-description" >';
				echo wpautop( $description );
				echo "</div>";
			}
			echo $after_widget;
		}
	}

	/**
	 * Update widget options
	 *
	 * @param object $new_instance Widget Instance
	 * @param object $old_instance Widget Instance
	 * @return object
	 * @author Modern Tribe, Inc.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, self::get_defaults() );
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		if ( current_user_can('unfiltered_html') ) {
			$instance['description'] = $new_instance['description'];
		} else {
			$instance['description'] = wp_filter_post_kses( $new_instance['description'] );
		}
		$instance['link'] = esc_url_raw( $new_instance['link'] );
		$instance['linktarget'] = sanitize_key( $new_instance['linktarget'] );
		$instance['width'] = abs( $new_instance['width'] );
		$instance['height'] =abs( $new_instance['height'] );
		if ( !defined( 'IMAGE_WIDGET_COMPATIBILITY_TEST' ) ) {
			$instance['size'] = sanitize_key( $new_instance['size'] );
		}
		$instance['align'] = sanitize_key( $new_instance['align'] );
		$instance['alt'] = sanitize_text_field( $new_instance['alt'] );
		$instance['track'] = ! empty( $new_instance['track'] ) ? 1 : 0;

		// Reverse compatibility with $image, now called $attachement_id
		if ( !defined( 'IMAGE_WIDGET_COMPATIBILITY_TEST' ) && $new_instance['attachment_id'] > 0 ) {
			$instance['attachment_id'] = abs( $new_instance['attachment_id'] );
		} elseif ( $new_instance['image'] > 0 ) {
			$instance['attachment_id'] = $instance['image'] = abs( $new_instance['image'] );
		}
		$instance['imageurl'] = $new_instance['imageurl']; // deprecated
		$instance['aspect_ratio'] = $this->get_image_aspect_ratio( $instance );

		return $instance;
	}

	/**
	 * Form UI
	 *
	 * @param object $instance Widget Instance
	 * @author Modern Tribe, Inc.
	 */
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, self::get_defaults() );
		$id_prefix = $this->get_field_id(''); ?>
	<div class="uploader">
		<input type="submit" class="button" name="<?php echo $this->get_field_name('uploader_button'); ?>" id="<?php echo $this->get_field_id('uploader_button'); ?>" value="<?php _e('Select an Image', 'largo'); ?>" onclick="imageWidget.uploader( '<?php echo $this->id; ?>', '<?php echo $id_prefix; ?>' ); return false;" />
		<div class="largo_preview" id="<?php echo $this->get_field_id('preview'); ?>">
			<?php echo $this->get_image_html($instance, false); ?>
		</div>
		<input type="hidden" id="<?php echo $this->get_field_id('attachment_id'); ?>" name="<?php echo $this->get_field_name('attachment_id'); ?>" value="<?php echo abs($instance['attachment_id']); ?>" />
		<input type="hidden" id="<?php echo $this->get_field_id('imageurl'); ?>" name="<?php echo $this->get_field_name('imageurl'); ?>" value="<?php echo $instance['imageurl']; ?>" />
	</div>
	<br clear="all" />

	<div id="<?php echo $this->get_field_id('fields'); ?>" <?php if ( empty($instance['attachment_id']) && empty($instance['imageurl']) ) { ?>style="display:none;"<?php } ?>>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'largo'); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr(strip_tags($instance['title'])); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('alt'); ?>"><?php _e('Alternate Text', 'largo'); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('alt'); ?>" name="<?php echo $this->get_field_name('alt'); ?>" type="text" value="<?php echo esc_attr(strip_tags($instance['alt'])); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Caption', 'largo'); ?>:</label>
		<textarea rows="5" class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>"><?php echo format_to_edit($instance['description']); ?></textarea></p>

		<p><label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link', 'largo'); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo esc_attr(strip_tags($instance['link'])); ?>" /><br />
		<select name="<?php echo $this->get_field_name('linktarget'); ?>" id="<?php echo $this->get_field_id('linktarget'); ?>">
			<option value="_self"<?php selected( $instance['linktarget'], '_self' ); ?>><?php _e('Stay in Window', 'largo'); ?></option>
			<option value="_blank"<?php selected( $instance['linktarget'], '_blank' ); ?>><?php _e('Open New Window', 'largo'); ?></option>
		</select></p>

		<p><input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('track'); ?>" name="<?php echo $this->get_field_name('track'); ?>" value="true" <?php checked( $instance['track'], 'true' ); ?>>
		<label for="<?php echo $this->get_field_id('track'); ?>"><?php _e('Track clicks in Google Analytics', 'largo'); ?></label>
		</p>

		<?php
		// Backwards compatibility prior to storing attachment ids
		?>
		<div id="<?php echo $this->get_field_id('custom_size_selector'); ?>" <?php if ( empty($instance['attachment_id']) && !empty($instance['imageurl']) ) { $instance['size'] = self::CUSTOM_IMAGE_SIZE_SLUG; ?>style="display:none;"<?php } ?>>
			<p><label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Size', 'largo'); ?>:</label>
				<select name="<?php echo $this->get_field_name('size'); ?>" id="<?php echo $this->get_field_id('size'); ?>" onChange="imageWidget.toggleSizes( '<?php echo $this->id; ?>', '<?php echo $id_prefix; ?>' );">
					<?php
					// Note: this is dumb. We shouldn't need to have to do this. There should really be a centralized function in core code for this.
					$possible_sizes = apply_filters( 'image_size_names_choose', array(
						'full'      => __('Full Size', 'largo'),
						'thumbnail' => __('Thumbnail', 'largo'),
						'medium'    => __('Medium', 'largo'),
						'large'     => __('Large', 'largo'),
					) );
					$possible_sizes[self::CUSTOM_IMAGE_SIZE_SLUG] = __('Custom', 'largo');

					foreach( $possible_sizes as $size_key => $size_label ) { ?>
						<option value="<?php echo $size_key; ?>"<?php selected( $instance['size'], $size_key ); ?>><?php echo $size_label; ?></option>
						<?php } ?>
				</select>
			</p>
		</div>
		<div id="<?php echo $this->get_field_id('custom_size_fields'); ?>" <?php if ( empty($instance['size']) || $instance['size']!=self::CUSTOM_IMAGE_SIZE_SLUG ) { ?>style="display:none;"<?php } ?>>

			<input type="hidden" id="<?php echo $this->get_field_id('aspect_ratio'); ?>" name="<?php echo $this->get_field_name('aspect_ratio'); ?>" value="<?php echo $this->get_image_aspect_ratio( $instance ); ?>" />

			<p><label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width', 'largo'); ?>:</label>
			<input id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo esc_attr(strip_tags($instance['width'])); ?>" onchange="imageWidget.changeImgWidth( '<?php echo $this->id; ?>', '<?php echo $id_prefix; ?>' )" size="3" /></p>

			<p><label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height', 'largo'); ?>:</label>
			<input id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo esc_attr(strip_tags($instance['height'])); ?>" onchange="imageWidget.changeImgHeight( '<?php echo $this->id; ?>', '<?php echo $id_prefix; ?>' )" size="3" /></p>

		</div>

		<p><label for="<?php echo $this->get_field_id('align'); ?>"><?php _e('Align', 'largo'); ?>:</label>
		<select name="<?php echo $this->get_field_name('align'); ?>" id="<?php echo $this->get_field_id('align'); ?>">
			<option value="none"<?php selected( $instance['align'], 'none' ); ?>><?php _e('none', 'largo'); ?></option>
			<option value="left"<?php selected( $instance['align'], 'left' ); ?>><?php _e('left', 'largo'); ?></option>
			<option value="center"<?php selected( $instance['align'], 'center' ); ?>><?php _e('center', 'largo'); ?></option>
			<option value="right"<?php selected( $instance['align'], 'right' ); ?>><?php _e('right', 'largo'); ?></option>
		</select></p>
	</div>
<?php
	}

	/**
	 * Admin header css
	 *
	 * @author Modern Tribe, Inc.
	 */
	function admin_head() {
		?>
	<style type="text/css">
		.uploader input.button {
			width: 100%;
			height: 34px;
			line-height: 33px;
		}
		.largo_preview .aligncenter {
			display: block;
			margin-left: auto !important;
			margin-right: auto !important;
		}
		.largo_preview {
			overflow: hidden;
			max-height: 300px;
		}
		.largo_preview img {
			margin: 10px 0;
		}
	</style>
	<?php
	}

	/**
	 * Render an array of default values.
	 *
	 * @return array default values
	 */
	private static function get_defaults() {

		$defaults = array(
			'title' => '',
			'description' => '',
			'link' => '',
			'linktarget' => '',
			'width' => 0,
			'height' => 0,
			'image' => 0, // reverse compatible - now attachement_id
			'imageurl' => '', // reverse compatible.
			'align' => 'none',
			'alt' => '',
			'track' => 0
		);

		if ( !defined( 'IMAGE_WIDGET_COMPATIBILITY_TEST' ) ) {
			$defaults['size'] = self::CUSTOM_IMAGE_SIZE_SLUG;
			$defaults['attachment_id'] = 0;
		}

		return $defaults;
	}

	/**
	 * Render the image html output.
	 *
	 * @param array $instance
	 * @param bool $include_link will only render the link if this is set to true. Otherwise link is ignored.
	 * @return string image html
	 */
	private function get_image_html( $instance, $include_link = true ) {

		// Backwards compatible image display.
		if ( $instance['attachment_id'] == 0 && $instance['image'] > 0 ) {
			$instance['attachment_id'] = $instance['image'];
		}

		$output = '';

		if ( $include_link && !empty( $instance['link'] ) ) {
			$attr = array(
				'href' => $instance['link'],
				'target' => $instance['linktarget'],
				'class' => 	$this->widget_options['classname'].'-image-link',
				'title' => ( !empty( $instance['alt'] ) ) ? $instance['alt'] : $instance['title'],
			);
			if ($instance['track']) $attr['class'] .= ' image-click-track';
			$attr = apply_filters('image_widget_link_attributes', $attr, $instance );
			$attr = array_map( 'esc_attr', $attr );
			$output = '<a';
			foreach ( $attr as $name => $value ) {
				$output .= sprintf( ' %s="%s"', $name, $value );
			}
			$output .= '>';
		}

		$size = $this->get_image_size( $instance );
		if ( is_array( $size ) ) {
			$instance['width'] = $size[0];
			$instance['height'] = $size[1];
		} elseif ( !empty( $instance['attachment_id'] ) ) {
			$image_details = wp_get_attachment_image_src( $instance['attachment_id'], $size );
			if ($image_details) {
				$instance['imageurl'] = $image_details[0];
				$instance['width'] = $image_details[1];
				$instance['height'] = $image_details[2];
			}
		}
		$instance['width'] = abs( $instance['width'] );
		$instance['height'] = abs( $instance['height'] );

		$attr = array();
		$attr['alt'] = $instance['title'];
		if (is_array($size)) {
			$attr['class'] = 'attachment-'.join('x',$size);
		} else {
			$attr['class'] = 'attachment-'.$size;
		}
		$attr['style'] = '';
		if (!empty($instance['width'])) {
			$attr['style'] .= "max-width: {$instance['width']}px;";
		}
		if (!empty($instance['height'])) {
			$attr['style'] .= "max-height: {$instance['height']}px;";
		}
		if (!empty($instance['align']) && $instance['align'] != 'none') {
			$attr['class'] .= " align{$instance['align']}";
		}
		$attr = apply_filters( 'image_widget_image_attributes', $attr, $instance );

		// If there is an imageurl, use it to render the image. Eventually we should kill this and simply rely on attachment_ids.
		if ( !empty( $instance['imageurl'] ) ) {
			// If all we have is an image src url we can still render an image.
			$attr['src'] = $instance['imageurl'];
			$attr = array_map( 'esc_attr', $attr );
			$hwstring = image_hwstring( $instance['width'], $instance['height'] );
			$output .= rtrim("<img $hwstring");
			foreach ( $attr as $name => $value ) {
				$output .= sprintf( ' %s="%s"', $name, $value );
			}
			$output .= ' />';
		} elseif( abs( $instance['attachment_id'] ) > 0 ) {
			$output .= wp_get_attachment_image($instance['attachment_id'], $size, false, $attr);
		}

		if ( $include_link && !empty( $instance['link'] ) ) {
			$output .= '</a>';
		}

		return $output;
	}

	/**
	 * Assesses the image size in case it has not been set or in case there is a mismatch.
	 *
	 * @param $instance
	 * @return array|string
	 */
	private function get_image_size( $instance ) {
		if ( !empty( $instance['size'] ) && $instance['size'] != self::CUSTOM_IMAGE_SIZE_SLUG ) {
			$size = $instance['size'];
		} elseif ( isset( $instance['width'] ) && is_numeric($instance['width']) && isset( $instance['height'] ) && is_numeric($instance['height']) ) {
			$size = array(abs($instance['width']),abs($instance['height']));
		} else {
			$size = 'full';
		}
		return $size;
	}

	/**
	 * Establish the aspect ratio of the image.
	 *
	 * @param $instance
	 * @return float|number
	 */
	private function get_image_aspect_ratio( $instance ) {
		if ( !empty( $instance['aspect_ratio'] ) ) {
			return abs( $instance['aspect_ratio'] );
		} else {
			$attachment_id = ( !empty($instance['attachment_id']) ) ? $instance['attachment_id'] : $instance['image'];
			if ( !empty($attachment_id) ) {
				$image_details = wp_get_attachment_image_src( $attachment_id, 'full' );
				if ($image_details) {
					return ( $image_details[1]/$image_details[2] );
				}
			}
		}
	}
}
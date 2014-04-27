<?php

class Largo_WP_Customize_Rich_Radio_Control extends WP_Customize_Control {

	public $type = 'rich_radio';

	public function render_content() {
		$name = '_customize-rich-radio-' . $this->id;

		?>
		<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<?php
		$is_first = true;
		foreach ( $this->choices as $value => $options ) :

			if ( $is_first ) {
				$is_first = false;
			} else {
				echo '<hr />';
			}
			?>
			<label>
				<input type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> /><strong><?php echo esc_html( $options['label'] ); ?></strong>
				<?php if ( ! empty( $options['img'] ) ) : ?>
				<img src="<?php echo esc_url( $options['img'] ); ?>" /><br />
				<?php endif; ?>
			</label>
			<?php
		endforeach;
	}

}
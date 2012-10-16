<?php

/*
 * A customized version of the wp_rss_widget hard-coded to show INN member stories.
 */

class largo_INN_RSS_widget extends WP_Widget {

	function __construct() {
		$widget_ops = array(
			'classname' => 'largo-INN-RSS',
			'description' => 'An RSS feed of recent stories from INN members',
		);
		parent::__construct( 'largo_INN_RSS', __('INN Member Stories'), $widget_ops );
	}

	function widget($args, $instance) {

		extract($args);

		$rss = fetch_feed('http://www.investigativenewsnetwork.org/member-feed-items.rss');
		$title = 'Recent stories from INN members';
		$desc = '';
		$link = 'http://www.investigativenewsnetwork.org/';
		$url = esc_url(strip_tags($url));

		$title = "<a class='rsswidget' href='$link' title='$desc'>$title</a>";

		$widget_class = !empty($instance['widget_class']) ? $instance['widget_class'] : '';
		if ($instance['hidden_tablet'] === 1)
			$widget_class .= ' hidden-tablet';
		if ($instance['hidden_phone'] === 1)
			$widget_class .= ' hidden-phone';
		/* Add the widget class to $before widget, used as a style hook */
		if( strpos($before_widget, 'class') === false ) {
			$before_widget = str_replace('>', 'class="'. $widget_class . '"', $before_widget);
		}
		else {
			$before_widget = str_replace('class="', 'class="'. $widget_class . ' ', $before_widget);
		}

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		largo_widget_rss_output( $rss, $instance );
		echo $after_widget;

		unset($rss);
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['widget_class'] = $new_instance['widget_class'];
		$instance['hidden_tablet'] = $new_instance['hidden_tablet'] ? 1 : 0;
		$instance['hidden_phone'] = $new_instance['hidden_phone'] ? 1 : 0;
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array(
			'widget_class' => 'default',
			'hidden_tablet' => '',
			'hidden_phone'	=> ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$tablet = $instance['hidden_tablet'] ? 'checked="checked"' : '';
		$phone = $instance['hidden_phone'] ? 'checked="checked"' : '';
		?>

		<label for="<?php echo $this->get_field_id( 'widget_class' ); ?>"><?php _e('Widget Background', 'largo-INN-RSS'); ?></label>
		<select id="<?php echo $this->get_field_id('widget_class'); ?>" name="<?php echo $this->get_field_name('widget_class'); ?>" class="widefat" style="width:90%;">
		    <option <?php selected( $instance['widget_class'], 'default'); ?> value="default">Default</option>
		    <option <?php selected( $instance['widget_class'], 'rev'); ?> value="rev">Reverse</option>
		    <option <?php selected( $instance['widget_class'], 'no-bg'); ?> value="no-bg">No Background</option>
		</select>

		<p style="margin:15px 0 10px 5px">
			<input class="checkbox" type="checkbox" <?php echo $tablet; ?> id="<?php echo $this->get_field_id('hidden_tablet'); ?>" name="<?php echo $this->get_field_name('hidden_tablet'); ?>" /> <label for="<?php echo $this->get_field_id('hidden_tablet'); ?>"><?php _e('Hide on Tablets?'); ?></label>
			<br />
			<input class="checkbox" type="checkbox" <?php echo $phone; ?> id="<?php echo $this->get_field_id('hidden_phone'); ?>" name="<?php echo $this->get_field_name('hidden_phone'); ?>" /> <label for="<?php echo $this->get_field_id('hidden_phone'); ?>"><?php _e('Hide on Phones?'); ?></label>
		</p>
	<?php
	}

}

function largo_widget_rss_output( $rss, $args = array() ) {

	echo '<ul>';
	foreach ( $rss->get_items(0, 5) as $item ) {
		$link = $item->get_link();
		while ( stristr($link, 'http') != $link )
			$link = substr($link, 1);
		$link = esc_url(strip_tags($link));
		$title = esc_attr(strip_tags($item->get_title()));

		$desc = str_replace( array("\n", "\r"), ' ', esc_attr( strip_tags( @html_entity_decode( $item->get_description(), ENT_QUOTES, get_option('blog_charset') ) ) ) );

		//various cleanup unique to our particular feed
		$desc = str_replace(array("read more", "Read More", "È"), "", $desc);
		$desc = preg_replace('/[^a-zA-Z0-9;_ %\[\]\.\(\)%&-]/s', '', $desc);
		$desc = trim(str_replace('&039;', '\'', $desc));

		$summary = "<p class='rssSummary'>$desc</p>";

		$date = '';
		$date = $item->get_date( 'U' );
		if ( $date ) {
			$date = ' <span class="rss-date">' . date_i18n( get_option( 'date_format' ), $date ) . '</span>';
		}

		$author = '';
		$author = $item->get_author();
		if ( is_object($author) ) {
			$author = $author->get_name();
			$author = ' <cite>' . esc_html( strip_tags( $author ) ) . '</cite>';
		}

		if ( $link == '' ) {
			echo "<li><h5>$title</h5><p class=\"byline\">{$author} | {$date}</p>{$summary}</li>";
		} else {
			echo "<li><h5><a class='rsswidget' href='$link' title='$desc'>$title</a></h5><p class=\"byline\">{$author} | {$date}</p>{$summary}</li>";
		}
	}
	echo '</ul>';
	$rss->__destruct();
	unset($rss);
}

?>
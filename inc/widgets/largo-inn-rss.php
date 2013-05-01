<?php

/*
 * A customized version of the wp_rss_widget hard-coded to show INN member stories.
 */

class largo_INN_RSS_widget extends WP_Widget {

	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'largo-INN-RSS',
			'description' 	=> __('An RSS feed of recent stories from INN members', 'largo'),
		);
		parent::__construct( 'largo_INN_RSS', __('INN Member Stories', 'largo'), $widget_ops );
	}

	function widget($args, $instance) {

		extract($args);
		$rss = fetch_feed('http://investigativenewsnetwork.org/all-member-news.rss');
		$title = __('Stories From Other INN Members', 'largo');
		$desc = __('View more recent stories from members of the Investigative News Network', 'largo');
		$link = 'http://www.investigativenewsnetwork.org/';

		$title = "<a class='rsswidget' href='$link' title='$desc'>$title</a>";

		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;
		largo_widget_rss_output( $rss, $instance ); ?>

		<p class="morelink"><a href="<?php echo $link; ?>">More Stories From INN Members&nbsp;&raquo;</a></p>

		<?php echo $after_widget;

		unset($rss);
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['num_posts'] = strip_tags( $new_instance['num_posts'] );
		$instance['show_excerpt'] = $new_instance['show_excerpt'] ? 1 : 0;
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'num_posts'		=> 3,
			'show_excerpt' 	=> ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$show_excerpt = $instance['show_excerpt'] ? 'checked="checked"' : '';
		?>
			<p>
				<input class="checkbox" type="checkbox" <?php echo $show_excerpt; ?> id="<?php echo $this->get_field_id('show_excerpt'); ?>" name="<?php echo $this->get_field_name('show_excerpt'); ?>" /> <label for="<?php echo $this->get_field_id('show_excerpt'); ?>"><?php _e('Show excerpts?', 'largo'); ?></label>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'num_posts' ); ?>"><?php _e('Number of stories to show:', 'largo'); ?></label>
				<input id="<?php echo $this->get_field_id( 'num_posts' ); ?>" name="<?php echo $this->get_field_name( 'num_posts' ); ?>" value="<?php echo $instance['num_posts']; ?>" style="width:90%;" />
			</p>
		<?php
	}
}


function largo_widget_rss_output( $rss, $args = array() ) {

	echo '<ul>';
	foreach ( $rss->get_items(0, $args['num_posts']) as $item ) {
		$link = $item->get_link();
		while ( stristr($link, 'http') != $link )
			$link = substr($link, 1);
		$link = esc_url(strip_tags($link));
		$title = esc_attr(strip_tags($item->get_title()));

		if ( $args['show_excerpt'] === 1 ) {
			$desc = str_replace( array("\n", "\r"), ' ', esc_attr( strip_tags( @html_entity_decode( $item->get_description(), ENT_QUOTES, get_option('blog_charset') ) ) ) );
			$desc = largo_trim_sentences($desc, 2);
			$summary = "<p class='rssSummary'>$desc</p>";
		} else {
			$summary = '';
		}

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
			echo "<li><h5><a class='rsswidget' href='$link' title='$title'>$title</a></h5><p class=\"byline\">{$author} | {$date}</p>{$summary}</li>";
		}
	}
	echo '</ul>';
	$rss->__destruct();
	unset($rss);
}

?>
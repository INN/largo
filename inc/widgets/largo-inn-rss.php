<?php

/*
 * A customized version of the wp_rss_widget hard-coded to show INN member stories.
 */

class largo_INN_RSS_widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'description' => __('An RSS feed of recent stories from INN members') );
		$control_ops = array( 'width' => 400, 'height' => 200 );
		parent::__construct( 'rss', __('INN Member Stories'), $widget_ops, $control_ops );
	}

	function widget($args, $instance) {

		if ( isset($instance['error']) && $instance['error'] )
			return;

		extract($args, EXTR_SKIP);

		$url = 'http://www.investigativenewsnetwork.org/member-feed-items.rss';
		while ( stristr($url, 'http') != $url )
			$url = substr($url, 1);

		// self-url destruction sequence
		if ( in_array( untrailingslashit( $url ), array( site_url(), home_url() ) ) )
			return;

		$rss = fetch_feed($url);
		$title = 'Recent stories from INN members';
		$desc = '';
		$link = 'http://www.investigativenewsnetwork.org/';

		$title = apply_filters('widget_title', $title, $instance, $this->id_base);
		$url = esc_url(strip_tags($url));
		$icon = includes_url('images/rss.png');
		if ( $title )
			$title = "<a class='rsswidget' href='$link' title='$desc'>$title</a>";

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		largo_widget_rss_output( $rss, $instance );
		echo $after_widget;

		if ( ! is_wp_error($rss) )
			$rss->__destruct();
		unset($rss);
	}

}

function largo_widget_rss_output( $rss, $args = array() ) {
	if ( is_string( $rss ) ) {
		$rss = fetch_feed($rss);
	} elseif ( is_array($rss) && isset($rss['url']) ) {
		$args = $rss;
		$rss = fetch_feed($rss['url']);
	} elseif ( !is_object($rss) ) {
		return;
	}

	if ( is_wp_error($rss) ) {
		if ( is_admin() || current_user_can('manage_options') )
			echo '<p>' . sprintf( __('<strong>RSS Error</strong>: %s'), $rss->get_error_message() ) . '</p>';
		return;
	}

	$items = 5;
	$show_summary  = 1;
	$show_author   = 1;
	$show_date     = 1;

	if ( !$rss->get_item_quantity() ) {
		echo '<ul><li>' . __( 'An error has occurred; the feed is probably down. Try again later.' ) . '</li></ul>';
		$rss->__destruct();
		unset($rss);
		return;
	}

	echo '<ul>';
	foreach ( $rss->get_items(0, $items) as $item ) {
		$link = $item->get_link();
		while ( stristr($link, 'http') != $link )
			$link = substr($link, 1);
		$link = esc_url(strip_tags($link));
		$title = esc_attr(strip_tags($item->get_title()));
		if ( empty($title) )
			$title = __('Untitled');

		$desc = str_replace( array("\n", "\r"), ' ', esc_attr( strip_tags( @html_entity_decode( $item->get_description(), ENT_QUOTES, get_option('blog_charset') ) ) ) );

		$desc = wp_html_excerpt( $desc, 240 );

		// Append ellipsis. Change existing [...] to [&hellip;].
		if ( '[...]' == substr( $desc, -5 ) )
			$desc = substr( $desc, 0, -5 ) . '[&hellip;]';
		elseif ( '[&hellip;]' != substr( $desc, -10 ) )
			$desc .= ' [&hellip;]';

		$desc = esc_html( $desc );
		$desc = str_replace(array("read more", "Read More", "È"), "", $desc);
		$desc = preg_replace('/[^a-zA-Z0-9;_ %\[\]\.\(\)%&-]/s', '', $desc);
		$desc = str_replace('&039;', '\'', $desc);
		$desc = trim($desc);

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

function largo_widget_rss_process( $widget_rss, $check_feed = true ) {
	$items = 5;
	$url           = esc_url_raw(strip_tags( $widget_rss['url'] ));
	$title         = trim(strip_tags( $widget_rss['title'] ));
	$show_summary  = isset($widget_rss['show_summary']) ? (int) $widget_rss['show_summary'] : 0;
	$show_author   = isset($widget_rss['show_author']) ? (int) $widget_rss['show_author'] : 0;
	$show_date     = isset($widget_rss['show_date']) ? (int) $widget_rss['show_date'] : 0;

	if ( $check_feed ) {
		$rss = fetch_feed($url);
		$error = false;
		$link = '';
		if ( is_wp_error($rss) ) {
			$error = $rss->get_error_message();
		} else {
			$link = esc_url(strip_tags($rss->get_permalink()));
			while ( stristr($link, 'http') != $link )
				$link = substr($link, 1);

			$rss->__destruct();
			unset($rss);
		}
	}

	return compact( 'title', 'url', 'link', 'items', 'error', 'show_summary', 'show_author', 'show_date' );
}

?>
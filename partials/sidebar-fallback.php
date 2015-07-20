<?php
/**
 * Used to populate the sidebar when the first-choice widget area is empty,
 * but an empty sidebar is inappropriate.
 */
the_widget('largo_about_widget', array(
	'title' => __('About This Site', 'largo'))
);

the_widget('largo_follow_widget', array(
	'title' => __('Follow Us', 'largo'))
);

if (of_get_option('donate_link')) {
	the_widget('largo_donate_widget', array(
		'title' => __('Support ' . get_bloginfo('name'), 'largo'),
		'cta_text' => __('We depend on your support. A generous gift in any amount helps us continue to bring you this service.', 'largo'),
		'button_text' => __('Donate Now', 'largo'),
		'button_url' => esc_url( of_get_option( 'donate_link' ) ),
		'widget_class' => 'default'
		)
	);
}

the_widget( 'largo_sidebar_featured_widget', array(
		'title' => __('We Recommend', 'largo'),
		'num_posts' => 5,
		'num_sentences' => 2,
		'widget_class' => 'default'
	)
);

if (is_home() && INN_MEMBER === TRUE) {
	the_widget('largo_INN_RSS_widget', array(
			'title' => __('Stories From Other INN Members', 'largo'),
			'num_posts' => 3
		)
	);
}

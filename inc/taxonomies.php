<?php

function largo_custom_taxonomies() {
    // PROMINENCE
    if ( ! taxonomy_exists( 'prominence' ) ) {
        register_taxonomy( 'prominence', 'post', array(
            'hierarchical' => true,
            'label' => 'Post Prominence',
            'query_var' => true,
            'rewrite' => true,
        ) );
        wp_insert_term( 'Footer Featured Widget', 'prominence' );
    }

    // FEATURES
    if ( ! taxonomy_exists( 'series' ) ) {
        register_taxonomy( 'series', 'post', array(
            'hierarchical' => true,
            'label' => 'Series',
            'query_var' => true,
            'rewrite' => true,
        ) );
    }
}
add_action( 'init', 'largo_custom_taxonomies' );
?>
inc/post-metaboxes.php
======================

.. php:function:: largo_remove_default_post_screen_metaboxes()

   Hide some of the less commonly used metaboxes to cleanup the post and page edit screens

   :since: 0.3

.. php:function:: largo_remove_other_post_screen_metaboxes()

   Remove meta boxes that are generated automatically by WordPress (i.e. for custom taxonomies)
   or other non-default WordPress meta boxes that we want to hide or customize.

   :since: 0.4

.. php:function:: largo_change_default_hidden_metaboxes()

   Show all of the other metaboxes by default (particularly to show the excerpt)

   :since: 0.3

.. php:function:: largo_add_custom_prominence_meta_box()

   Add our prominence taxonomy meta box with custom behavior.

   :param array $largoProminenceTerms: list of prominence terms

   :see: largo_custom_taxonomies

.. php:function:: largo_byline_meta_box_display()

   Contents for the 'byline' metabox

   Allows user to set a custom byline text and link.

   :global: $post

.. php:function:: largo_layout_meta_box_display()

   Contents for the Layout Options metabox

   Allows user to choose:
   - the post template used by the post, if the current post is not a page
   - the sidebar used by this post

   :global: $post

.. php:function:: largo_custom_sidebar_js()

   Load JS for custom sidebar choice dropdown

   :global: $typenow

   :global: $wp_registered_sidebars

   :global: LARGO_DEBUG

.. php:function:: largo_custom_related_meta_box_display()

   Custom related meta box option

   Allows the user to set custom related posts for a post.

   :global: $post

.. php:function:: largo_custom_disclaimer_meta_box_display()

   Disclaimer text area for the Additional Options metabox

   If the post's disclaimer field is empty, then the default disclaimer
   is the option set in the theme options.

   :global: $post

.. php:function:: largo_top_tag_display()

   Metabox option to choose the top tag for the post

   :global: $post

.. php:function:: largo_top_terms_js()

   Load JS for our top-terms select

   :global: LARGO_DEBUG

   :global: $typenow

.. php:function:: largo_prominence_meta_box()

   Callback function to draw our custom meta box for the prominence taxonomy
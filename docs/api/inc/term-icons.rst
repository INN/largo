inc/term-icons.php
==================

.. php:class:: Largo_Term_Icons

      Creates $largo['term-icons'] using the Largo_Term_Icons class defined herein

      Display the fields for selecting icons for terms in the "post-type" taxonomy

      :global: $largo

   .. php:method:: Largo_Term_Icons::register_taxonomy()

      Register the taxonomy post-type

   .. php:method:: Largo_Term_Icons::get_icons_config()

      Retrieves the Fontello config.json information about the glyphs

      :global: $wp_filesystem

   .. php:method:: Largo_Term_Icons::display_fields()

      Renders the form fields on the term edit page

      :param object $term: A taxonomy term

   .. php:method:: Largo_Term_Icons::admin_enqueue_scripts()

      Attach the Javascript and Stylesheets to the term edit page

      :param string $hook_suffix:

      :global: LARGO_DEBUG

      :global: $_REQUEST

   .. php:method:: Largo_Term_Icons::edit_terms()

      Save the results from the term edit page

      :global: $post
      :param string $term_id:

   .. php:method:: Largo_Term_Icons::get_icon()

      Retrieve the icon information for a term

      :param term|string $taxonomy_or_term: - the term object of the taxonomy name
      :param int $term_id: - the term id when the first parameter is the taxonomy name

   .. php:method:: Largo_Term_Icons::the_icon()

      Output the icon for a term

      :param term|string $taxonomy_or_term: - the term object of the taxonomy name
      :param int $term_id: - the term id when the first parameter is the taxonomy name
      :param string $tag: - the HTML element that shall be used for the icon
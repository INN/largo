inc/update.php
==============

.. php:function:: largo_perform_update()

   Performs various update functions and set a new verion number.

   This acts as a main() for applying database updates when the update ajax is
   called.

   :since: 0.3

.. php:function:: largo_version()

   Returns current version of largo as set in stylesheet.

   :since: 0.3

.. php:function:: largo_need_updates()

   Checks if updates need to be run.

   :since: 0.3

   :returns: boolean $f updates need to be run

.. php:function:: largo_update_widgets()

   Puts new widgets into sidebars as appropriate based on old theme options

   :since: 0.4

.. php:function:: largo_update_prominence_term_descriptions()

   Updates post prominence term descriptions iff they use the old language

   This function can be added to the `init` action to force an update of prominence term descriptions:
      add_action('init', 'largo_update_prominence_term_descriptions');

   This function does not touch custom prominence term descriptions, except those that are identical to the descriptions of current or 0.3 prominence term descriptions.

   :since: 0.4

   :uses: largo_update_prominence_term_description_single

.. php:function:: largo_update_prominence_term_description_single()

   Compares an array containing an old and new prominence term description and the appropriate slug and name to an array of current term descriptions. For each term whose current description matches the old description, the function updates the current description to the new description.

   This function contains commented-out logic that will allow you to from description to olddescription

   :since: 0.4
   :param array $update: The new details for the prominence tax term to be updated
   :param array $term_descriptions: Array of prominence terms, each prominence term as an associative array with keys: name, description, olddescription, slug

   :uses: var_log

   :uses: wp_update_term

   :uses: clean_term_cache

.. php:function:: largo_force_settings_update()

   Update miscellaneous settings

   :since: 0.4

.. php:function:: largo_enable_if_series()

   Enable series if series have been created.

   :since: 0.4

   :returns: bool $f series were enabled by this function

.. php:function:: largo_enable_series_if_landing_page()

   Enable the series taxonomy if the series landing pages are in use.

   :since: 0.4

   :returns: bool $f series landing pages (and series) were enabled by this function.

.. php:function:: largo_update_custom_less_variables()

   Make sure custom CSS is regenerated if we're using custom LESS variables

.. php:function:: largo_check_deprecated_widgets()

   Checks for use of deprecated widgets and posts an alert

.. php:function:: largo_deprecated_footer_widget()

   Admin notices of older widgets

.. php:function:: largo_instantiate_widget()

   Inserts a widget programmatically.
   This is slightly dangerous as it makes some assumptions about existing plugins
   if $instance_settings are wrong, bad things might happen

   :since: 0.5
   :param String $kind.: Kind of widget to instantiate.
   :param Array $instance_settings.: Settings for that array.
   :param String $region.: Sidebar region to add to.

.. php:function:: largo_register_update_page()

   Register an admin page for updates.

   :since: 0.3

.. php:function:: largo_update_page_view()

   DOM for admin page for updates.

   :since: 0.3

.. php:function:: largo_update_page_enqueue_js()

   Enqueues javascript used on the Largo Update page

   :since: 0.3

   :global: LARGO_DEBUG

   :global: $_GET

.. php:function:: largo_ajax_update_database()

   Ajax handler for when update is applied from the updates page.

   :since: 0.3

   :global: LARGO_DEBUG

   :global: $_GET
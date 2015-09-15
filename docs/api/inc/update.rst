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

.. php:function:: largo_home_transition()

   Convert old theme option of 'homepage_top' to new option of 'home_template'

   :since: 0.4

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

.. php:function:: largo_remove_topstory_prominence_term()

   Remove "top-story" prominence term to avoid conflicts with homepages that will register it

   :returns: array $f deleted prominence terms

.. php:function:: largo_set_new_option_defaults()

   Save default values for any newly introduced options to the database

   Note: this must be called before any other update function calls `of_set_option`,
   as `of_set_uption` defaults all values to null.

   :since: 0.5.1

.. php:function:: largo_update_custom_less_variables()

   Make sure custom CSS is regenerated if we're using custom LESS variables

.. php:function:: largo_check_deprecated_widgets()

   Checks for use of deprecated widgets and posts an alert

.. php:function:: largo_deprecated_footer_widget()

   Admin notices of older widgets

.. php:function:: largo_replace_deprecated_widgets()

   Replace deprecated widgets with new widgets

   To add widgets to this list of widgets to be upgraded:
     - Add the deprecated widget class and its replacement to $upgrades

   :uses: largo_get_widget_basename

   :uses: largo_get_widget_number

   :since: 0.5.3

.. php:function:: largo_widget_in_region()

   Checks to see if a given widget is in a given region already

   :since: 0.5.2

   :returns: bool $hether or not the widget was found.

.. php:function:: largo_instantiate_widget()

   Inserts a widget programmatically.
   This is slightly dangerous as it makes some assumptions about existing plugins
   if $instance_settings are wrong, bad things might happen

   :since: 0.5
   :param String $kind.: Kind of widget to instantiate.
   :param Array $instance_settings.: Settings for that array.
   :param String $region.: Sidebar region to add to.

   :returns: Array $'id' => the id with number of the new widget , 'place' => the index of the id in its region )

.. php:function:: largo_get_widget_basename()

   Utility function to get the basename of a widget from the widget's slug

   :since: 0.5.3

.. php:function:: largo_get_widget_number()

   Utility function to get the number of a widget from the widget's slug

   :since: 0.5.3

.. php:function:: largo_update_admin_notice()

   Add an admin notice if largo needs to be updated.

   :since: 0.3

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

.. php:class:: LargoPreviousOptions

      A singleton utility class for preserving and retrieving previous Largo options

      :since: 0.5.3

   .. php:method:: LargoPreviousOptions::preserve()

      Call this method before saving theme options for the first time after updating Largo
      to preserve the state of theme options for the previous version.

   .. php:method:: LargoPreviousOptions::retrieve()

      Retrieve the theme options for the version of Largo that immediately preceeded the
      currently-deployed version.

      Optionally, retrieve a previous set of theme options by passing a version string to the
      method.

      :param string $largo_version: for example '0.5.2'

.. php:function:: largo_preserve_previous_options()

   Convenience function for storing the theme options for the version of the theme that immediately
   preceeded the currently-deployed version.

   :since: 0.5.3

.. php:function:: largo_retrieve_previous_options()

   Convenience function for retrieving the theme options for the version of the theme that immediately
   preceeded the currently-deployed version.

.. php:function:: largo_block_theme_options_for_update()

   If Largo needs to be updated, prevent the user from access the Theme Options edit page.

   :since: 0.5.3

.. php:function:: largo_block_theme_options()

   Displays a message indicating the user should update their Largo install before
   attempting to edit Theme Options

   :since: 0.5.3
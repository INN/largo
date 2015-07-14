inc/customizer/customizer.php
=============================

.. php:class:: Largo_Customizer

      The Largo_Customizer gives a visually responsive setup interface.

   .. php:method:: Largo_Customizer::get_instance()

      Get the instance of the Largo Customizer

   .. php:method:: Largo_Customizer::action_customize_register()

      Register our customizer options

   .. php:method:: Largo_Customizer::action_customize_preview_init()

      Add contextual information when the Customizer is loaded

   .. php:method:: Largo_Customizer::action_customize_controls_enqueue_scripts()

      Enqueue scripts and styles specific to the Largo Customizer

   .. php:method:: Largo_Customizer::action_preview_wp_footer()

      Customizer settings based on context

   .. php:method:: Largo_Customizer::filter_customize_value()

      Filter customizer values to use our existing settings framework

      :param mixed $default: Default registered value for the setting

      :returns: mixed

   .. php:method:: Largo_Customizer::action_customize_update()

      Handle an update to one of our Customizer settings

      :param mixed $value:

   .. php:method:: Largo_Customizer::action_customize_preview()

      Handle the preview of one of our setting values

   .. php:method:: Largo_Customizer::action_customize_save_fetch_less_variables()

      Cache all of the LESS variables to a class variable for updating

   .. php:method:: Largo_Customizer::action_customize_save_after_save_less_variables()

      If the values have changed, save and regenerate the stylesheet
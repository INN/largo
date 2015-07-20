functions.php
=============

.. php:class:: Largo

      A class to represent the one true Largo theme instance

   .. php:method:: Largo::is_less_enabled()

      Is the LESS feature enabled?

   .. php:method:: Largo::is_plugin_active()

      Is a given plugin active?

      :param string $plugin_slug:

      :returns: bool

.. php:function:: largo_php_warning()

   Prints an admin warning if php is out of date.

.. php:function:: largo_setup()

   Sets up theme defaults and registers support for various WordPress features.

   Note that this function is hooked into the after_setup_theme hook, which runs
   before the init hook. The init hook is too late for some features, such as indicating
   support post thumbnails.

   To override largo_setup() in a child theme, add your own largo_setup() to your child theme's
   functions.php file.

.. php:function:: of_set_option()

   Helper for setting specific theme options (optionsframework).

   Would be nice if optionsframework included this natively
   See https://github.com/devinsays/options-framework-plugin/issues/167
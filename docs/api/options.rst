options.php
===========

.. php:function:: optionsframework_option_name()

   A unique identifier is defined to store the options in the database and reference them from the theme.
   By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
   If the identifier changes, it'll appear as if the options have been reset.

.. php:function:: optionsframework_options()

   Defines an array of options that will be used to generate the settings page and be saved in the database.
   When creating the 'id' fields, make sure to use all lowercase and no spaces.

   If you are making your theme translatable, you should replace 'options_framework_theme'
   with the actual text domain for your theme.  Read more:
   http://codex.wordpress.org/Function_Reference/load_theme_textdomain

.. php:function:: optionsframework_custom_scripts()

   This function prints Javascript on the Theme Options admin page to control the behavior
   of certain options that depend or require other options.

   For example, you can not use Custom Landing Pages unless Series taxonomy is enabled. So,
   this script will hide the Custom Landing Pages option until the Series taxonomy checkbox
   is enabled.
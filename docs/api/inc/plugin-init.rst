inc/plugin-init.php
===================

.. php:function:: largo_register_required_plugins()

   Register the required plugins for this theme.

   In this example, we register two plugins - one included with the TGMPA library
   and one from the .org repo.

   The variable passed to tgmpa_register_plugins() should be an array of plugin
   arrays.

   This function is hooked into tgmpa_init, which is fired within the
   TGM_Plugin_Activation class constructor.
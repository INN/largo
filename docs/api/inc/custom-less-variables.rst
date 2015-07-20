inc/custom-less-variables.php
=============================

.. php:function:: largo_clv_register_files()

   Register which Less files are to be compiled into CSS
   for the user customized values to override variables.less.

   Example:

   largo_clv_register_files( array( 'style.less', 'editor.less' ) );

   :param array $files: - list of filenames in the less directory

.. php:function:: largo_clv_register_directory_paths()

   Set the file path for the directory with the LESS files and
   URI for the directory with the outputted CSS.

   :param string $less_dir:
   :param string $css_dir_uri:

.. php:function:: largo_clv_register_variables_less_file()

   Set the filename of the variables file.

   :param string $variables_less_file: - 'variables.less'

.. php:class:: Largo_Custom_Less_Variables

      Class to contain the logic

   .. php:method:: Largo_Custom_Less_Variables::init()

      Initialize the plugin

   .. php:method:: Largo_Custom_Less_Variables::put_contents()

      Write a file to disk.

      :param string $file: - path of file to write
      :param string $contents: - the content to be written to the file

   .. php:method:: Largo_Custom_Less_Variables::get_contents()

      Read a file's contents.

      :param string $file: - path of file to read

   .. php:method:: Largo_Custom_Less_Variables::register_files()

      Register the Less files to compile into CSS files

      :param array $files: - the LESS files to compile into CSS

      :global: bool $ARGO_DEBUG - if false, minified CSS assets will be used by Largo, and these should be replaced with the custom-compiled assets.

   .. php:method:: Largo_Custom_Less_Variables::register_directory_paths()

      Set the file path for the directory with the LESS files and
      URI for the directory with the outputted CSS.

      :param string $less_dir:
      :param string $css_dir_uri:

   .. php:method:: Largo_Custom_Less_Variables::register_variables_less_file()

      Set the variables.less file

      :param string $variables_less_file: - example 'variables.less'

   .. php:method:: Largo_Custom_Less_Variables::get_css()

      Get the compiled CSS for a LESS file.

      It will retrieved it from saved generated CSS or go
      ahead and compile it.

      :param string $less_file: - the LESS file to compile

      :returns: string $he generated CSS

   .. php:method:: Largo_Custom_Less_Variables::compile_less()

      Compile a LESS file with our custom variables

      :param $string $less_file: - 'style.less'

      :returns: string $ the resulting CSS

   .. php:method:: Largo_Custom_Less_Variables::variable_file_path()

      Get the variable.less file path

   .. php:method:: Largo_Custom_Less_Variables::replace_with_custom_variables()

      Replace the include for the variable file with a modified version
      with the custom values.

   .. php:method:: Largo_Custom_Less_Variables::style_loader_src()

      Change the URL for the stylesheets that are the output of the LESS files.

   .. php:method:: Largo_Custom_Less_Variables::template_redirect()

      Intercept the loading of the page to determine if we output the rendered CSS

   .. php:method:: Largo_Custom_Less_Variables::success_admin_notices()

      Display a success message

   .. php:method:: Largo_Custom_Less_Variables::reset_admin_notices()

      Display a success message

   .. php:method:: Largo_Custom_Less_Variables::admin_menu()

      Register the admin page

   .. php:method:: Largo_Custom_Less_Variables::admin()

      Render the admin page content

   .. php:method:: Largo_Custom_Less_Variables::admin_head()

      Register Javascript files and stylesheets.

   .. php:method:: Largo_Custom_Less_Variables::revisions_meta_box()

      Revision meta box

   .. php:method:: Largo_Custom_Less_Variables::publish_box()

      Render the publish meta box

   .. php:method:: Largo_Custom_Less_Variables::get_custom_values()

      Get the custom values

      :param string $theme: optional - the folder name of the theme, defaults to active theme
      :param int $revision: optional - the revision ID, defaults to the current version

      :returns: associated $rray of values

   .. php:method:: Largo_Custom_Less_Variables::get_post()

      Get the post the data is saved to

   .. php:method:: Largo_Custom_Less_Variables::reset_all()

      Delete all custom variables saved

   .. php:method:: Largo_Custom_Less_Variables::update_custom_values()

      Save or update custom values

      :param array $values: - an associative array of values
      :param string $theme: optional - the theme name, defaults to the active the theme

   .. php:method:: Largo_Custom_Less_Variables::get_editable_variables()

      Parse the variable.less to retrieve the editable values

   .. php:method:: Largo_Custom_Less_Variables::color_type_field()

      Render the color field in the admin

   .. php:method:: Largo_Custom_Less_Variables::pixels_field()

      Render a pixels field in the admin

   .. php:method:: Largo_Custom_Less_Variables::dropdown_field()

      Render a dropdown in the admin
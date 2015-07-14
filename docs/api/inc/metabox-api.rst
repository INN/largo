inc/metabox-api.php
===================

.. php:function:: largo_add_meta_box()

   Call this function to define a metabox container

   :since: 0.2
   :param string $id: Required. HTML 'id' attribute of edit screen section. Corresponds to first argument of add_meta_box()
   :param string $title: Required. Title of the metabox, visible to user
   :param array|string $screens: Optional. Name of post type(s) this box should appear on. Values correspond to $post_type argument of add_meta_box(). Defaults to 'post'
   :param string $context: Optional. The context within the page where the boxes should show ('normal', 'advanced', 'side'). Defaults to 'advanced'
   :param string $priority: Optional. The priority within the context where the boxes should show ('high', 'low', 'core', 'default'). Defaults to 'default'

.. php:function:: largo_add_meta_content()

   Call this function to add a field to an (existing) metabox container

   TODO: Implement some sort of weighting protocol to control ordering of fields within a metabox (right now it's just FIFO)

   :since: 0.2
   :param string $callback: Required. Function that outputs the markup for this field
   :param string $box_id: Required. HTML 'id' attribute of the box this field goes into

.. php:function:: largo_register_meta_input()

   Call this function from within a largo_add_meta_field callback to register an input as a post meta field

   TODO: Include a validation parameter so meta fields can be validated easily.

   :since: 0.2
   :param string|array $input_names: Name of a single input or array of input names to add as meta fields

.. php:function:: _largo_metaboxes_generate()

   Private function to actually generate the metaboxes

.. php:function:: _largo_metaboxes_content()

   Private function to generate fields/markup within largo metaboxes
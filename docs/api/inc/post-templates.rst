inc/post-templates.php
======================

.. php:function:: is_post_template()

   Modelled on is_page_template, determine if we are in a single post template.
   You can optionally provide a template name and then the check will be
   specific to that template.

   :since: 1.0

   :uses: $wp_query
   :param string $template: The specific template name if specific matching is required.

   :returns: bool $rue on success, false on failure.

.. php:function:: largo_remove_hero()

   Remove potentially duplicated hero image after upgrade to v0.4

   The changes to the content in this function should eventually be made
   perminant in the database. (@see https://github.com/INN/Largo/issues/354)

   :since: v0.4
   :param String $content: the post content passed in by WordPress filter

   :returns: String $iltered post content.

.. php:function:: largo_url_to_attachmentid()

   Retrieves the attachment ID from the file URL
   (or that of any thumbnail image)

   :since: v0.4

   :see: https://pippinsplugins.com/retrieve-attachment-id-from-image-url/

   :returns: Int $D of post attachment (or false if not found)
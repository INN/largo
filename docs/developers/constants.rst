Largo Constants
===============

The image constants
-------------------

Largo's image constants are used to define the crop and scaling sizes that WordPress automatically chops your image into.

Width:

  - ``FULL_WIDTH`` (default: 1170): the largest width for the largest image size
  - ``LARGE_WIDTH`` (default: 771): medium image crop width
  - ``MEDIUM_WIDTH`` (default: 336): small image crop width

Height:

Largo does not impose any height limits on crop sizes. Thus the defaults are set to 9999.

  - ``FULL_HEIGHT`` (default: 9999): full image crop height
  - ``LARGE_HEIGHT`` (default: 9999): medium image crop height
  - ``MEDIUM_HEIGHT`` (default: 9999): small image crop height

For more information about how Largo handles image sizes, see `Image Sizes <imagesizes.html>`_.

The other constants
-------------------

.. php:const:: INN_HOSTED

	``INN_HOSTED`` indicates whether or not a WordPress instance is hosted by INN. This setting should be set in ``wp-config.php``, but there is no reason for you to set this.

	If ``INN_HOSTED`` is true, then ``INN_MEMBER`` below is also true.

.. php:const:: INN_MEMBER

	``INN_MEMBER`` indicates whether or not a WordPress site belongs to `a member of the Institute for Nonprofit News <http://inn.org/members/>`_. 

	``INN_MEMBER`` is defined as true in ``functions.php`` if it is not otherwise defined and if ``INN_HOSTED`` is true. If ``INN_HOSTED`` is false, then ``INN_MEMBER`` will also be false unless ``INN_MEMBER`` is explicitly defined in ``wp_config.php`` or in the ``functions.php`` of a child theme.

.. php:const:: LARGO_DEBUG

	``LARGO_DEBUG`` should be set to ``true`` in development environments. It controls many behaviors:

	- in ``inc/enqueue.php``, ``LARGO_DEBUG`` controls whether or not minified versions of the following files are used:
		- ``css/style.css``
		- ``js/largoCore.js``
		- ``css/widgets-php.css``
		- ``js/widgets-php.js``
	- in ``inc/custom-less-variables.php``, ``LARGO_DEBUG`` controls whether or not minified versions of the recompiled files are used.
	- in ``inc/featured-media.php``, ``LARGO_DEBUG`` controls whether or not minified versions of the following files are used:
		- ``js/featured-media.js``
	- in ``inc/post-metaboxes.php``, ``LARGO_DEBUG`` controls whether or not minified versions of the following files are used:
		- ``js/custom-sidebar.js``
		- ``js/top-terms.js``
	- in ``inc/term-icons.php``, ``LARGO_DEBUG`` controls whether or not minified versions of the following files are used:
		- ``js/custom-term-icons.js``
	- in ``inc/update.php``, 
		- ``js/update-page.js``

	Define ``LARGO_DEBUG`` to ``true`` in your ``wp-config.php`` with the following line: ::

		define( 'LARGO_DEBUG', TRUE);

	Please be careful with ``LARGO_DEBUG``-related functionality, as it is difficult to write tests for functions including constants.

.. php:const:: OPTIONS_FRAMEWORK_DIRECTORY

	This constant represents the URI of the options framework, defined as ``get_template_directory_uri() . '/lib/options-framework/'`` in ``functions.php``. This path is used to enqueue the options framework CSS, color picker CSS, jquery-dependent color picker, iris.min.js, the options framework scripts, and the options framework media library uploader.

.. php:const:: SHOW_GLOBAL_NAV

	The Global Nav is a thin blck bar displayed in the header of Largo, goverened by ``SHOW_GLOBAL_NAV``. ``SHOW_GLOBAL_NAV`` defaults to true, but child themes can set it to false with ``define( 'SHOW_GLOBAL_NAV', FALSE );`` in their theme ``functions.php``.

.. php:const:: SHOW_STICKY_NAV

	The sticky nav appears on the homepage and all internal pages, and on mobile devices, governed by ``SHOW_STICKY_NAV``. ``SHOW_STICKY_NAV`` my be defined to be true or false, but the easiest way to set it is to check "Show the Sticky Nav" in the Theme Options section of Largo.

.. php:const:: SHOW_MAIN_NAV

	The main navigation appears on the homepage and all internal pages, but not on mobile devices, governed by ``SHOW_MAIN_NAV``. ``SHOW_MAIN_NAV`` defaults to true, but child themes can set it to false with ``define( 'SHOW_GLOBAL_NAV', FALSE );`` in their theme ``functions.

.. php:const:: SHOW_SECONDARY_NAV

.. php:const:: SHOW_CATEGORY_RELATED_TOPICS

.. php:const:: LARGO_AVATAR_META_NAME

.. php:const:: LARGO_AVATAR_ACTION_NAME

.. php:const:: LARGO_AVATAR_INPUT_NAME

.. php:const:: JCLV_UNCOMPRESSED

.. php:const:: DOING_AUTOSAVE

.. php:const:: PICTUREFILL_WP_PATH

.. php:const:: PICTUREFILL_WP_URL

.. php:const:: PICTUREFILL_WP_VERSION

.. php:const:: CFTL_SELF_DIR

.. php:const:: LARGO_TEMPLATE_LANDING_VERSION

.. php:const:: MEDIA_CREDIT_POSTMETA_KEY

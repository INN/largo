Largo Image Sizes and Constants
===============================

Largo defines several image sizes for use in various parts of the theme. All sizes are based on a set of constants defined in `functions.php <../api/functions.html>`_. Child themes can override these presets to change the size of images throughout the theme.

Image Sizes
-----------

The following image sizes are registered in Largo, using the constants defined below.

  - ``60x60``
      - 60x60px image crop.
  - ``rect_thumb``
      - 800x600px image crop
      - Used for cat/tax archive pages.
  - ``medium``
      - Image size defined by constants: ``MEDIUM_WIDTHxMEDIUM_HEIGHT``
      - Default is 336px wide by a flexible height.
  - ``large``
      - Image size defined by constants: ``LARGE_WIDTHxLARGE_HEIGHT``
      - Default is 771px wide by a flexible height.
  - ``full``
      - Image size defined by constants: ``FULL_WIDTHxFULL_HEIGHT``
      - Default is 1170px wide by a flexible height.
  - ``third-full``
      - Image size defined by: ``(FULL_WIDTH/3)xFULL_HEIGHT``
      - Default is 390px wide by a flexible height.
  - ``two-third-full``
      - Image size defined by: ``(FULL_WIDTH*2/3)xFULL_HEIGHT``
      - Default is 780px wide by a flexible height.

Constants
---------

Width:

  - ``FULL_WIDTH`` (default: 1170): the largest width for the largest image size
  - ``LARGE_WIDTH`` (default: 771): medium image crop width
  - ``MEDIUM_WIDTH`` (default: 336): small image crop width

Height:

Largo does not impose any height limits on crop sizes. Thus the defaults are set to 9999.

  - ``FULL_HEIGHT`` (default: 9999): full image crop height
  - ``LARGE_HEIGHT`` (default: 9999): medium image crop height
  - ``MEDIUM_HEIGHT`` (default: 9999): small image crop height

See also `Largo's list of constants <constants.html>`_.

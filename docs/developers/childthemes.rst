Using Child Themes
==================

Child themes are `a feature of WordPress <https://largo.readthedocs.org/en/write-the-docs/developers/childthemes.html>`_ that allow you to extend and override the parent theme that the child theme is based upon. We encourage you to create a child theme for your project.

Changing Basic Styling
----------------------

Largo has a built-in way to change some basic styling options.

To enable this option, from the *Appearance* > *Theme Options* screen click on the "Advanced" tab and check the box labelled "Enable Custom LESS to CSS For Theme Customization" and then save the settings.

.. image:: http://assets.apps.inn.org/largo-docs/enable-less-css.png

You will now see an additional menu item under the Appearance menu labelled "CSS Variables". From this menu you will be able to change some basic styling attributes of your Largo site, including the color scheme, fonts and font-sizes.

.. image:: http://assets.apps.inn.org/largo-docs/less-css-menu.png

This option is only intended for making some basic changes to your site's styles. For anything more complex you will need to create a child theme.

Advanced Theme Development and Modification
-------------------------------------------

We've created a `Largo-Sample-Child-Theme repository <https://github.com/INN/Largo-Sample-Child-Theme>`_ to illustrate how we organize child themes that extend Largo.

Visit the repository page to learn more about the following as they pertain to Largo-based child themes:

- `Unit Tests and Continuous Integration <https://github.com/INN/Largo-Sample-Child-Theme/blob/master/tests/readme.md>`_
- `Stylesheets (LESS and CSS) <https://github.com/INN/Largo-Sample-Child-Theme/blob/master/less/readme.md>`_
- `Theme Directory Layout <https://github.com/INN/Largo-Sample-Child-Theme#theme-directory-structure>`_
- `Custom Theme Javascript or CSS <https://github.com/INN/Largo-Sample-Child-Theme#removing-or-replacing-largo-javascript-or-css>`_
- `Removing or replacing Largo Javascript or CSS <https://github.com/INN/Largo-Sample-Child-Theme#removing-or-replacing-largo-javascript-or-css>`_

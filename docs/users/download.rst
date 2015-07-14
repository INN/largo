Getting Started
===============

Download and Install WordPress
------------------------------

The Largo parent theme and plugins have been tested with the latest version of WordPress. Some hosting providers offer one-click installation of WordPress and that should get you the latest version. If that does not work for you, download the latest version from the `WordPress downloads <https://wordpress.org/download/>`_  page and follow their `instructions <http://codex.wordpress.org/Installing_WordPress>`_ to get it setup.

Download and Install Largo
--------------------------

The latest stable version of the Largo parent theme is available for download from `the project repository on github <https://github.com/INN/Largo>`_ on github. The master branch (`download link <https://github.com/INN/Largo/archive/master.zip>`_) is always the latest stable release although you may sometimes want to also keep an eye on `the develop branch <https://github.com/inn/largo/tree/develop>`_ which contains our work on the next release of Largo. Note that we do not recommend using the develop branch on a production site.

Once you have downloaded the Largo theme you'll need to unzip it, and will typically want to rename the resulting folder to just "largo" (github will include the name of the branch in the name of the folder, i.e. - largo-master, but to avoid potential problems with the following instructions using "largo" as the name of the folder will make your life a little easier.

Now you'll need to upload the "largo" theme folder to your hosting provider in the /wp-content/themes/ folder of your WordPress installation.

If you don't plan to modify the Largo theme beyond what is possible from the dashboard then you can just activate the theme under Appearance > Themes and skip the following section about creating child themes.

Creating Child Themes
---------------------

In order to make it easier to upgrade to future versions of the Largo parent theme, you will want to add any customizations that are unique to your site by creating a child theme. WordPress has a `tutorial you can follow <http://codex.wordpress.org/Child_Themes>`_ that explains how to create and configure a child theme.

NEVER modify the Largo parent theme directly, even to make small changes. It will make your life much harder when we release a new version because your changes are highly likely to be overwritten.

To create a child theme, at a minimum you will need to create a new theme folder (call it something like "largo-child" in your wp-content/themes directory and add one file to it called style.css. That file would be used to add any custom CSS unique to your child theme, but it also tells WordPress where to find the parent theme.

At the very top of the file you need to add at least the following:

.. code::

    /*
    Theme Name:     Your Child Theme's Name
    Theme URI:      Your Site URL
    Description:    Your Theme Description
    Author:         Your Name
    Author URI:     Your Author URL
    Template:       largo
    Version:        0.1.0
    \*/

The line starting with "Template" must include the name of the folder that contains the parent theme files (this should be "largo" unless you name the parent theme folder something different).

If you would prefer, we have created `an example child theme <https://github.com/INN/Largo-Sample-Child-Theme>`_ that you can start from where we document our preferred structure and how to modify the behavior, look and feel of Largo, create custom templates, etc.

To use this child theme, `simply download and unzip it <https://github.com/INN/Largo-Sample-Child-Theme/archive/master.zip>`_ just as you did the Largo parent theme above, modify the header block in the style.css as described above and then upload the folder to your wp-content/themes directory along with the Largo parent theme. The sample child theme contains a number of additional files and documentation that you may not need so you might consider removing the elements you do not intend to use.

Now that you have a child theme created, login to your WordPress site and go to Appearance > Themes. Find your child theme, click "activate" and then you should see your new child theme in action on your site and can begin customizing.

**More:** `Using Child Themes <../developers/childthemes.html>`_.

Configure Theme Options
-----------------------

Now that you have activated your theme you will want to configure some of the built-in options that come with Largo. You can find these under Appearance > Theme Options.

There are tabs across the top of the page that allow you to access different sections of the Theme Options.

- **Basic Settings** allows you to enter some basic information about your site such as a short description and social media links and to enable/disable and configure some of the built-in functionality in the Largo theme such as related content at the bottom of single articles and a number of menu areas that you will configure in the next step.

- **Theme Images** is where you will upload a number of images that the Largo theme requires. These include a thumbnail-sized logo image and three banner images that are used as the site's top banner on different screen sizes.

- **Layout Options** allows you to choose between different homepage layouts and customize other elements of the site's layout.

- **Advanced** contains less frequently used functionality that can be toggled on/off and some other advanced settings.

**More:** `Theme Options Detailed Documentation <themeoptions.html>`_

Configure Menus And Sidebars
----------------------------

Once you setup your theme options, you will likely want to configure the menu areas on your site and possibly configure the content of the default sidebars that are included as part of the Largo parent theme.

These are configured from the Appearance > Menus and Appearance > Widgets menus, respectively.

**More:**

- `Menus Detailed Documentation <menus.html>`_
- `Sidebars and Widgets Detailed Documentation <sidebarswidgets.html>`_

Install Plugins
---------------

The Largo theme comes packaged with a number of plugins developed by NPR as part of their Project Argo plus some additional recommended plugins that add various functionality to your Largo site. We have a complete list of these plugins and what they are used for on our plugins page.

**More:** `Plugins Detailed Documentation <plugins.html>`_

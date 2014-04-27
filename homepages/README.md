Largo Homepage Template Documentation
=====================================

This document explains the homepage template system implemented in Largo. You can leverage this system in your child theme.

TL;DR
-----
1. In a homepages directory of your theme, create a PHP file.
2. Similar to how WP page templates work, add comments giving your template a name (required) and description
3. Optionally instantiate new sidebar regions, separated by pipes (|) with descriptions in parentheticals
4. Deactivate the standard Largo rail by specifying Right rail: none
5. To incorporate CSS or JS specific to you template, add css/js files with the same name as your template inside their respective subdirectories in homepages/
6. We recommend you also include a .png thumbnail of your layout

Overview
--------

The Largo homepage template system allows you to customize the upper portion of the site homepage, integrating with other Largo theme options to give significant control within child themes to the layout of the homepage.

Specifically, the template system controls what comes just beneath the horizontal navigation bar, and the region identified in the Theme Options controls as "homepage bottom." With this system you can load custom JavaScript and CSS along with your template, if it requires them.

Assets for homepage templates should be stored in a "homepages" directory in the root of your theme. Thus is your Largo child theme is at "wp-content/themes/kiddo", your homepage template assets should be in "wp-content/themes/kiddo/homepages"

Template File
-------------

The only requirement for creating a new template is a PHP file within the homepages directory that has the necessary PHP comments to be recognized by the system. It works much like declaring WordPress page templates; attributes are named and described in colon-separated pairs. The attributes are as follows:

Home Template: (required) This is the name of your template, e.g. "Kiddo Feature"
Description: This is a brief description of yoru template, which appears on the Theme Options page where site administrators select the active template
Sidebars: If you would like to use widget regions specific to your template, you can specify them here. Sidebars are pipe-separated and can optionally include descriptions in parentheses, e.g.:
	Sidebar name (a helpful description) | Other Sidebar Name | Kiddo Homepage Region Three
Right Rail: If you wish to suppress the righthand rail that typically appears on Largo pages, you can disable it by passing "none" as the value for this

Related Assets
--------------

The Largo Home Template system will automatically enqueue CSS and JS files for your template if they share the same file name as your template (preceeding the extension) and are placed in css and js subfolders, respectively. For example, if your homepage template is located at homepages/sample-template.php, the system will look for and automatically load homepages/js/sample-template.js and/or homepages/css/sample-template.css if either are present.

Thumbnail
---------

It is strongly recommended that you include a 240x180 pixel PNG screenshot of your template in addition to a name and description to help site administrators understand what your template displays. This file should be located in the same directory as your template and share the same name, except the file extension will of course need to be png instead of php.

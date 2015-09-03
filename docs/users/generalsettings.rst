The WordPress Settings Menu
===========================

Before you get started with the Largo-specific documentation, you may want to review the basic `WordPress documentation <http://codex.wordpress.org/First_Steps_With_WordPress>`_. Particularly if you are new to WordPress it will answer many of your questions about setting up your site, writing posts, uploading photos, and creating users. We try, wherever possible, to build things "the WordPress way" and not get in the way of the things WordPress core does really well. As a result, often, a quick search will help you out with basic questions or problems that are not specific to Largo.

That said, here are some of the items under the Settings menu in the WordPress dashboard that you will want to be aware of as you are setting up your site.

General
-------

Clicking on Settings > General will take you to a page where you can setup many of the general options for your site.

This is where you set your site name, tagline, address, general admin user address, time format, and member permissions.

Your site description is set under Appearance > Theme Options > Basic Settings.

The site name, tagline and description are used in a number of places in Largo for SEO purposes (as the title tag on the homepage, for meta descriptions, as a fallback for pages where a title or description is not specified, etc.) so you'll want to make sure these are set.

The admin email address is also important because this is the email address that will be used for any notification emails WordPress sends, such as when a new user registers or when a new comment is posted. It is also used as the default address for your site's `contact form <contactform.html>`_.

This tab also allows you to set the timezone and language for your site. If you set the language to something other than English, both WordPress and Largo do incorporate multi-lingual support. You can find some details on how that works in the `WordPress documentation <http://codex.wordpress.org/Translating_WordPress>`_.

Writing
-------

The most important setting here is the Default Post Category. Typically you should consider setting the default category to something other than *Uncategorized*.

Reading
-------

Largo does not use most of the settings in this menu, instead setting things like the homepage layout in the Appearance > Theme Options menu.

The main setting to be aware of here controls whether or not search engines will be discouraged from crawling your site and displaying it in search results. Enabling this checkbox inserts ``<meta name="robots" content="noindex,follow">`` tag into your site's header which will discourage search engines from indexing your site. This is commonly used while sites are still under development to ensure that the site is not indexed and "visible" to search engines and users until you're ready to make it live.

Discussion
----------

The settings found in Settings > Discussion control commenting, comment moderation and notifications.

For an in-depth discussion of what these options do see the `WP Codex entry on this screen <http://codex.wordpress.org/Settings_Discussion_Screen>`_.

These settings will apply to sites which use the `Epoch <https://wordpress.org/plugins/epoch/>`_ commenting system.

Note that many sites use a third-party system or plugin such as `Disqus <https://wordpress.org/plugins/disqus-comment-system/>`_ or `Facebook <https://wordpress.org/plugins/facebook-comments-plugin/>`_ to replace the default WordPress comment engine. If you use one of these third-party systems it may or may not respect the settings from this menu and you should consult that plugin's documentation to be sure.

Media
-----

Largo sets the media sizes required by the theme programmatically so you should not modify the settings in this menu.

Permalinks
----------

The permalinks menu controls the format used for the URLs on your site.

To avoid confusing users and search engines you will typically want to set this once and then not change it. WordPress does try to rewrite URLs if the format is changed so that links will not be broken but it is not 100% reliable.

Note that for your content to be included in Google News the URLs for your articles `must contain at least three digits <https://support.google.com/news/publisher/answer/40787?hl=en>`_. To meet this requirement many news sites will choose to use either the "month and name" or the "day and name" permalink option.

For more information about permalinks, see the `WordPress Codex entry on permalinks <http://codex.wordpress.org/Using_Permalinks>`_.

Other
-----

Additionally, many plugins will add sub-items to the Settings menu with settings that are specific to that plugin. Those settings will typically be covered in the documentation for that plugin.

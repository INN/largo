Plugins
=======

Overview
--------

WordPress plugins are modules that extend the functionality of WordPress. Anyone can write them and WordPress maintains a `directory of available plugins <https://wordpress.org/plugins/>`_. Since anyone can write them, this means that plugins vary widely in quality so we've curated a list of plugins that add functionality commonly needed by news organizations and that we have tested to ensure they work well together and do not cause compatibility issues that sometimes crop up when using less-trusted plugins.

Plugins From Project Argo
-------------------------

The Largo theme comes packaged with a number of plugins original created by NPR for `Project Argo <http://argoproject.org>`_. In some cases we have made modifications to these plugins to ensure compatibility with the Largo theme so we recommend using the included versions (which are also available from our team's `github account <https://github.com/INN>`_). When you activate the Largo theme you should receive a message at the top of the WordPress admin dashboard that prompts you to install recommended plugins.

Documentation for all of the Argo plugins, most of which remains applicable for our modified versions of the plugins, `can be found here <http://argoproject.org/plugin.php>`_.

- As of version 0.4 the **Argo Media Credit** and **Slideshow** plugins have been folded directly into the Largo theme so they do not need to be installed separately. Documentation for the media credit plugin and slideshow plugin can be found on the Project Argo site `here <http://argoproject.org/media-credit.php>`_ and `here <http://argoproject.org/slideshow.php>`_, respectively.

- Additionally, version 0.4 now includes the **Clean Contact** plugin to allow you to easily add simple contact forms to pages on your site. You can find `documentation for this plugin <https://wordpress.org/plugins/clean-contact/>`_ in the WordPress plugin directory.

- We have also modified the **Navis DocumentCloud** plugin to make it work a bit better within a responsive layout. We recommend using the version of the plugin that comes packaged with the Largo theme, but the instructions for `how to add a DocumentCloud document <http://argoproject.org/documentcloud.php>`_ to a post from the Project Argo site are still valid.

- The **Argo Links** plugin is useful for collecting links from around the web and for creating link roundup posts. We have made some modifications to this plugin, most notably to add a sidebar widget to display your recently saved links, but for the most part it will work exactly as `documented on the Project Argo site <http://argoproject.org/argo-links.php>`_.

The are two other plugins developed by NPR as part of Project Argo that we have not updated and we are not, at this point, 100% certain that they will work with the latest versions of WordPress and Largo so we do not necessarily recommend using them. That said, here's what they do in case you'd like to give them a try:

- **Argo Audio Player** enables you to add embedded audio to your posts. Depending on your specific needs you might prefer using a plugin called `PowerPress <https://wordpress.org/plugins/powerpress/>`_ to support podcasting, or you might want to instead host your audio on SoundCloud and use their embedded player. If you want to give the Argo Audio Player a try, documentation `can be found here <http://argoproject.org/audio.php>`_.

- **Argo Jiffy Posts** enables support for embed.ly's oEmbed API. This allows you to easily embed content in WordPress posts using nothing more than a URL. Note that embed.ly is not a free service (`pricing information here <http://embed.ly/cards>`_) but might be worth considering if your organization plans to embed a lot of third party content and rich media on your site.

Other Recommended Plugins
-------------------------

In addition to the Argo plugins, we have included a curated list of other plugins that will enable various functionality that many news organizations might find useful. Note that while we have tested these plugins with the latest version of Largo and WordPress, they are developed and maintained by their respective third-party developers.

- `Akismet <http://akismet.com/>`_ is a widely used WordPress plugin to protect your site from comment and trackback spam. Activation of this plugin requires an Akismet API key which you can obtain for free from `Askimet <http://akismet.com/wordpress/>`_ .

- `Better WordPress Google XML Sitemaps <https://wordpress.org/plugins/bwp-google-xml-sitemaps/>`_ will create a sitemap index of your site that you can then submit to Google (and Google News) and other search engines to maximize the visibility of your content. Documentation for this plugin can be found on the `plug in website <http://betterwp.net/wordpress-plugins/google-xml-sitemaps/>`_

- `Disqus Comment System <https://wordpress.org/plugins/disqus-comment-system/>`_ replaces the default WordPress comment system with a more robust alternative that includes more community features and enhanced moderation capability. This plugin requires a Disqus account to activate but the setup process will walk you through creating one and adding your site. Documentation can be found `on the Disqus website <https://disqus.com/>`_.

- `Edit Flow <http://editflow.org/>`_ adds advanced workflow features to WordPress including the ability to manage additional user roles, editorial comments and an editorial calendar. Documentation can be found `on the Edit Flow website <http://editflow.org/>`_.

- `W3 Total Cache <https://wordpress.org/plugins/w3-total-cache/>`_ is a caching plugin for WordPress that will improve the load time of your site and help your server to better deal with large traffic spikes. Caching is heavily dependent on your server setup, and while `W3 Total Cache <https://wordpress.org/plugins/w3-total-cache/>`_ is one of the most powerful (and flexible) WordPress caching solutions out there, if it does not work for you `WP Super Cache <https://wordpress.org/plugins/wp-super-cache/>`_ is another popular alternative. Note that this is not relevant for sites that we host for INN members as our hosting company, WP Engine, has their own caching system.

- `DoubleClick for WordPress <https://github.com/INN/DoubleClick-for-WordPress/>`_ is used to insert standard ad units into Largo. Read its `documentation for more details <dfw.readthedocs.org/en/latest/>`_.

Complete List of Plugins Available
----------------------------------

For hosted INN member sites using Largo, this is a complete list of the plugins that are currently installed and available in our production environment.

Note that since we use WordPress multisite, individual site admins are not able to install and activate plugins; This needs to be done at the network level.

If you would like to have a plugin added to this list or installed and activated for your site we prefer that you contact us through the Largo help desk and describe the functionality you are trying to add rather than asking if we'll install Plugin X for you. In some cases what you are after might already be possible in Largo and/or we might know of a better plugin to accomplish the desired result.

We strive to maintain a secure environment for all of our hosted Largo sites and to avoid potential compatibility issues, so in some cases we may refuse to install a particular plugin if we believe it might cause problems for you or for us. We will, however, always try to work with you and recommend an alternative.

Here is a complete list of the plugins currently installed and available.

**Recommended/curated plugins:**

- `Akismet <http://akismet.com/wordpress/>`_ - Spam prevention
- `Better WordPress Google XML Sitemaps <https://wordpress.org/plugins/bwp-google-xml-sitemaps/>`_ - Create and manage sitemaps for submission to Google and Google News
- `Breadcrumb NavXT <https://wordpress.org/plugins/breadcrumb-navxt/>`_ - Used by some sites to add breadcrumb navigation
- `Caspio Deploy2 <https://wordpress.org/plugins/caspio-deploy2/>`_ - Enables ShortCode placeholders for use with the Caspio cloud computing database application service.
- `Chartbeat <https://wordpress.org/plugins/chartbeat/>`_ - Adds Chartbeat pinging to Wordpress.
- `Co-Authors Plus <https://wordpress.org/plugins/co-authors-plus/>`_ - Allows multiple authors to be assigned to a post.
- `Constant Contact Plugin <https://wordpress.org/plugins/constant-contact-api/>`_ - Adds integration for the Constant Contact email marketing service
- `Disqus Comment System <https://wordpress.org/plugins/disqus-comment-system/>`_ - The Disqus comment system replaces your WordPress comment system with your comments hosted and powered by Disqus.
- `DoubleClick for WordPress <https://github.com/INN/DoubleClick-for-WordPress>`_ - DoubleClick ad integration, maintained by INN.
- `Edit Flow <https://wordpress.org/plugins/edit-flow/>`_ - Adds better editorial workflow options to the WordPress admin
- `Facebook Comments <https://wordpress.org/plugins/facebook-comments-plugin/>`_ - Replaces the default WordPress comment system with Facebook comments
- `Liveblog <https://wordpress.org/plugins/liveblog/>`_ - A simple way to add live blogs to your site.
- `Navis DocumentCloud <https://wordpress.org/plugins/navis-documentcloud/>`_ - Embed DocumentCloud documents that won't be eaten by the visual editor
- `News Quizzes <https://github.com/INN/news-quiz>`_ - A WordPress wrapper for Mother Jones' news quiz tool
- `Redirection <https://wordpress.org/plugins/redirection/>`_ - Manage all your 301 redirects and monitor 404 errors
- `Simple Tags <https://wordpress.org/plugins/simple-tags/>`_ - Extended Tagging for WordPress 4.0.x : Suggested Tags, Mass edit tags, Auto-tags, Autocompletion, Related Posts etc.
- `Slack <https://wordpress.org/plugins/slack/>`_ - Adds Slack notifications when you publish posts, receive new comments, etc.
- `TablePress <https://wordpress.org/plugins/tablepress/>`_ - TablePress enables you to create and manage tables in your posts and pages, without having to write HTML code. Also installed are the DataTables Counter Column, DataTables Sorting plugins and Pagination Length Change "All" entry extensions.
- `Tweetable Text <https://wordpress.org/plugins/tweetable-text/>`_ - Make your posts more shareable. Add a Tweet and Buffer button to key sentences right inside each blog post with a simple [tweetable] tag.
- `TinyMCE Advanced <https://wordpress.org/plugins/tinymce-advanced/>`_ - Enables advanced features and plugins in TinyMCE, the visual editor in WordPress.
- `WP DS NPR API <https://github.com/npr/WP-DS-NPR-API>`_ - A collection of tools for reusing content from NPR.org supplied by NPR Digital Services.

**Premium plugins** we've bought a site license for INN member sites:

- `Business Directory Plugin <https://wordpress.org/plugins/business-directory-plugin/>`_ - Provides the ability to maintain a free or paid business directory on your WordPress powered site. We also have a license for the Paypal Gateway Module.
- `Gravity Forms <https://wordpress.org/plugins/gravity-forms-addons/>`_ - Easily create web forms and manage form entries within the WordPress admin. We also have a license for the Gravity Forms PayPal Add-On.
- `The Events Calendar Pro <https://wordpress.org/plugins/the-events-calendar/>`_ - The Events Calendar PRO, a premium add-on to the open source The Events Calendar plugin (required), enables recurring events, custom attributes, venue pages, new widgets and a host of other premium features.
- `WPJobBoard <http://wpjobboard.net/>`_ - Adds a job board to your site.

**Plugins from Project Argo:**

- `Argo Audio Player <http://argoproject.org/audio.php>`_ - No longer updated/maintained, we recommend using an alternative service such as `SoundCloud <https://wordpress.org/plugins/soundcloud-shortcode/>`_ for embedding audio in posts
- `Argo Links <http://argoproject.org/argo-links.php>`_ - Curate links and display them in a sidebar widget or create link roundup posts
- `Navis Jiffy Posts <http://argoproject.org/jiffy-post.php>`_ - Makes it easy to quickly create a post from a URL
- `Navis Slideshows <http://argoproject.org/slideshow.php>`_ - Slideshows that take advantage of the Slides jQuery plugin

**Utilities:**

- `Categories to Tags Converter <https://wordpress.org/plugins/wpcat2tag-importer/>`_ - Convert existing categories to tags or tags to categories, selectively.
- `CodeStyling Localization <https://wordpress.org/plugins/codestyling-localization/>`_ - a utility for generating translation files from within the WordPress dashboard.
- `Core Control <https://wordpress.org/plugins/core-control/>`_ - Core Control is a set of plugin modules which can be used to control certain aspects of the WordPress control.
- `Empty Tags Remover <https://wordpress.org/plugins/empty-tags-remover/>`_ - Removes the empty tags, tags with no posts attached.
- `Regenerate Thumbnails <https://wordpress.org/plugins/regenerate-thumbnails/>`_ - Allows you to regenerate all thumbnails after changing the thumbnail sizes.
- `Taxonomy Converter <https://wordpress.org/plugins/taxonomy-converter/>`_ - Copy or convert terms between taxonomies.
- `Term Management Tools <https://wordpress.org/plugins/term-management-tools/>`_ - Allows you to merge terms and set term parents in bulk
- `Vice Versa <https://wordpress.org/plugins/vice-versa/>`_ - Convert Pages to Posts and Vice Versa
- `Theme Check <https://wordpress.org/plugins/theme-check/>`_ - A simple and easy way to test your theme for all the latest WordPress standards and practices.
- `WordPress Importer <https://wordpress.org/plugins/wordpress-importer/>`_ - Import posts, pages, comments, custom fields, categories, tags and more from a WordPress export file.
- `WP Maintenance Mode <https://wordpress.org/plugins/wp-maintenance-mode/>`_ - Adds a splash page to your site that lets visitors know your site is down for maintenance.

Plugins that we have reluctantly installed for and are in-use by typically one site that **we do not necessarily endorse or recommend** using:

- **Advanced Custom Fields** including the Options Page and Repeater Field add-ons.
- **Charity Thermometer**
- **iframe**
- **Membership Premium**
- **Pippity**
- **WooDojo**
- **WP-Member**

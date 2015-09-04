Theme Options
==============================

When you first activate the Largo theme, there are a number options that need to be configured before you make the site live. The majority of these can be found in the **Appearance** > **Theme Options** menu which you can access from the left sidebar of the WordPress administration interface.

The Theme Options page is organized into four sections which you can access by clicking on the relevant tab across the top of the page: Basic Settings, Theme Images, Layout Options and Homepage Options.

.. _basic-settings:

Basic Settings
--------------

**Site Description** - This is a short description of your site that is used in a sidebar or footer widget. Ideally this should just be a short paragraph (1-2 sentences in length) and you can include HTML in this box to link to the "About" page of your site so that visitors can read more about your organization.

**Feed URL** - This is a link to the main RSS feed for your site. By default this will be http://yoursite.com/feed but if you use a feed management and/or analytics tool (such as Feedburner), you can replace the default link here and it will be used in all relevant places on your Largo site.

**Donate Button** - The Largo theme includes an optional donate button in the top header. To enable the donate button, make sure the checkbox to show the donate button is checked and then add the link to your donation page, form or external payment processor here. Optionally, you can change the text of the button. The Largo theme also includes a sidebar widget that you can use to link to a donation page in addition to or instead of the link in the top header.

**Don't Miss Menu** - Under the main navbar on your Largo site is an optional secondary navigation menu that, by default, is called "Don't Miss". To enable this menu make sure the checkbox to show the Don't Miss Menu is checked. Optionally, you can customize the label that appears at the beginning of the menu or remove it entirely. To add or remove links from this menu visit **Appearance > Menus** from the left sidebar in the WordPress administration interface.

**Footer Nav Menu** - On the left side of the site footer is a configurable menu area. By default the label for this menu will be your site name, but you can change it here. As with the Don't Miss menu area, to add or remove links from this menu visit **Appearance > Menus** from the left sidebar in the WordPress administration interface.

**Sticky Nav** - The sticky navigation bar remains at the top of the reader's window as they scroll. The default is to show the sticky navbar, but you may want to hide it. You also have the option to show your site name in the sticky navbar, which is useful on smaller screens.

**Copyright Message** - By default this will display "Copyright [YEAR], Your Site Name" but you can enter your own message here if you would like. You can include HTML in this area to link to external sites and use &copy; to display a copyright symbol or ``%d`` to display the current year.

**Google Analytics** - The Largo theme has built in support for Google Analytics. Enter your Google Analytics ID here and the relevant Google Analytics code will be automatically added to all of the pages on your site. Note that it is configured to not track logged in users to ensure accurate reporting.

**Word to use for "Posts"** - By default, WordPress calls single article pages "posts" but you might prefer to use another name. You can specify the singular and plural forms separately.

**Social Media Links** - These are links to relevant social media profiles for your site or organization. These are used in a sidebar widget to provide links to like or follow your organization on Facebook and Twitter, in bylines to attribute content to your organization and in special code in the header to make sure your content appears optimally when shared by other users on these social networks.

Be careful to note the recommended format for each link type for maximum compatibility. These icons are by default used in the footer of your site's pages, but you have the option to show them in the header.

Note that authors can add links to their own social media profiles by editing their user profile.

**Single Post Social Icons** - By default a box showing social media links will be shown at the top of single article pages. You can choose to hide this if you prefer. You can also choose which share icons should be displayed here, the verb used on Facebook buttons, whether or not your Twitter share count is displayed, and where the "Clean Read" link should be displayed on the page.

In previous versions of Largo, the option to have the author's bio and social media links occupied this slot, and also governed the visibility of share icons and the author bio at the end of the article. The article-bottom author bio and social links are now available as widgets.

.. _theme-images:

Theme Images
------------

This section allows you to upload images that are used primarily in the top header of your Largo site. To add your images:

- Make sure you have created all six image sizes as outlined in our :doc:`prelaunchchecklist` and then click "Upload" by the image you want to add.

	- 200x200px Square default thumbnail image
	- 16x16px Favicon
	- 768px-wide Small banner image
	- 980px-wide Medium banner image
	- 1170px-wide Large banner image
	- 100px-tall site logo for use in the sticky header

- Drag and drop the image onto the uploader (or click "Select Files" and find the correct image on your computer). When the image has finished uploading, selected the full size image option, and then click the button at the bottom of the uploader that says "Use This Image".

- If the image has uploaded correctly, you should see a thumbnail version of it and the link to the photo in the field next to the "upload" button.

- Once you have uploaded all six images, be sure to click "Save Options" to save your changes.

There is one additional option in this section, a checkbox that allows you to use text in the place of the banner images. It is unlikely that you will use this option, but if you do, selecting this checkbox will use the Site Title and Description from your **Settings** > **General** menu in the place of the the header images so make sure that you have set those options before you enable this feature.

.. _pre-launch checklist: :doc:`./prelaunchchecklist.rst`

.. _layout-options:

Layout Options
--------------

**Home Template** - This choice determines what the top of your website's main page looks like. The Largo theme currently offers five homepage templates:

- **Blog** - This displays your most recent posts in reverse chronological order (newest at the top) with the ability to make a post "sticky" so it stays at the top of the homepage.
- **Big story, full-width image**: Prominently features the top story by itself, with a full-width image, headline and excerpt.
- **One Big Story and list of featured stories**: Prominently features the top story along with three other 'Featured on Homepage' items, or by itself if none are specified. Best with Homepage Bottom set to 'blank'
- **One big story and list of stories from the same series**: Prominently features the top story along with other posts in its series along the right side. Requires the :ref:`series-tax` taxonomy to be enabled.
- **Top Stories** - If you select this layout you will want to ensure that you are adding featuring images and excerpts for all of your posts and that you have at least six posts at all times set to "Homepage Featured" in the Post Prominence taxonomy and at least one post (that MUST have a featured image set) marked as the "Top Story", also in the Post Prominence taxonomy.

**Sticky Posts** - If enabled, the top sticky post will displayed in between the Homepage Template and the Homepage Bottom, below the tag "Featured". Posts can be marked sticky by opening the post editor, going to the "Publish" metabox, clicking "Edit" next to "Visibility", then choosing "Public" and "Stick this post to the front page."

**Homepage bottom templates** - Largo supports three options for the bottom of the homepage:

- A single-column list of recent posts with photos and excerpts
- A two-column widget area: This creates a new widget area in **Appearance > Widgets** that can be filled with widgets. It appears empty until widgets have been added to this area.
- Nothing whatsoever

**Category and Tag Display** - Largo can display:

- a single category or tag above the headline for each story
- a list of tags below the story's excerpt
- nothing at all

**Number of posts** - The number of posts displayed on the main area of the homepage, not counting posts in the top area of the homepage or in the sticky box. The default is 10.

**Categories to include or exclude** - Enter a comma-separated list of category ID numbers here to exclude them from the front-page listing. in the main loop on the homepage (comma-separated list of values, see http://codex.wordpress.org/Class_Reference/WP_Query for correct format). The general approach is:

	``news,sports,12,13,press-releases,blog``

**Single Article Template** - Starting with version 0.3, Largo introduced a new single-post template that more prominently highlights article content, which is the default. For backward compatibility, the pre-0.3 version is also available, which by default includes a sidebar. The new template optionally includes a sidebar of your choice.

**Category Options** - Starting with version 0.5.3, Largo allows you to disable the standard featured posts area on top of category archive pages. The five posts that are normally displayed on top of the page become part of the main column of posts.

**Sidebar Options** - These affect the presentation of the sidebar to the reader.

- Add a third sidebar used only on archive pages (category, tag, author and series pages), configurable in Appearance > Widgets
- An additional widget region just above the site footer region, configurable in Appearance > Widgets
- Fade the sidebar out on single story pages as the reader scrolls

You can also enter a list of additional sidebar regions that should be created, one on each line of the text box.

**Footer Layout** - The default footer is a 3 column footer with a wide center column. Alternatively you can choose to have 3 or 4 equal columns. Each column is a widget area that can be configured under the **Appearance > Widgets** menu, where they will be labeled "Footer 1" through "Footer 3" or "Footer 4."

.. _advanced-options:

Advanced Options
----------------

**Custom LESS** - Enabling this will let you change the theme's colors and fonts in **Appearance > CSS Variables**.

**Enable Series** - Some sites may create a multi-part series or project that is only published for a set amount of time and then should fall into the archive or appear on a “projects” archive page. To support this and also to allow for the creation of custom landing pages, Largo adds an optional “series” taxonomy. When you create a new series, you can add a term to this taxonomy and then make sure all of the posts in that series have this label applied. This will enable the Largo theme to surface related posts in that series in at the bottom of a post (if you are using the “read next” widget) and, in some cases, also on the homepage (depending on the homepage layout you have selected). Largo also adds the ability to create custom sidebars and landing pages for series archive pages, replacing the default series archive template in WordPress. For more information, see :ref:`series-tax`.

**Enable Custom Landing Pages** - Requires Series to be enabled. Series landing pages allow you to summarize a series of posts or tie a project together. For one example, see http://inewsnetwork.org/series/hit-and-run: the project page begins with a summary of the series, followed by posts within the series. 
For more information on creating a series landing page, see :doc:`landingpages`.

**Enable Optional Leaderboard Ad Zone** - This creates a widget area above your site's header that can be used to display ads. For more about this area, see :doc:`./ads`.

**Enable Post Types** - :ref:`This taxonomy <post-types-tax>` allows you to organize posts by content type, such as “Article,” Photo Gallery,” “Data,” etc. When you create a new post type you can assign it an icon, which will be used in certain places in the theme. Each post type also has its own archive so that you can add links to your navigation to a page containing all of your “data” projects, for example. In the future, we plan to add custom templates specific to each content type to make them easier to manage and more optimal when displayed to users on your public-facing site.`

.. _landing-pages-sidebars-option:

**Sidebars for Landing Pages** - These set the default sidebars for custom landing pages, and can be overridden by the individual landing page. For more information, see :doc:`landingpages`.

**Disclaimer** - If checked, you can enter a default disclaimer that will be displayed on all posts.

**Search Options** - Google Custom Search generally returns better search results than WordPress' included search engine. If you would like to enable Google Custom Search, go to https://www.google.com/cse/create/new to set it up, then paste your search engine ID in the settings box and enable the checkbox.

Be sure and use the "Results only" layout listed in the `Google Custom Search dashboard under "Look and feel." <https://developers.google.com/custom-search/docs/ui#setting-the-search-element-layout>`_

**Site Verification**:

- Twitter Account ID: This is a 9-digit ID number used for verifying your site to Twitter Analytics
- Google site verification meta tag: This will be a long string of numbers and letters. For more information, see `Google's documentation <https://support.google.com/webmasters/answer/35659?hl=en>`_.
- Facebook admins meta tag: This is a comma-separated list of numerical FB user IDs you want to allow to access Facebook insights for your site.
- Facebook app ID meta tag: This is a numerical app ID that will allow Facebook to capture insights for any social plugins active on your site and display them in your Facebook app/page insights. For more information, see `Facebook's documentation <https://developers.facebook.com/docs/platforminsights/domains>`_
- Bitly site verification: This is a string of numbers and letters used to verify your site with bitly analytics. For more information, `contact bitly <http://support.bitly.com/knowledgebase/articles/103260-what-is-a-tracking-domain>`_.

**SEO Options** - You may choose to ask search engines to not index archive pages in addition to date archives.

**INN Options** - If `INN_MEMBER` is defined as `true` in your site's `wp-config.php` or in your child theme, then you will have the option to add the year that your organization joined INN. This will be displayed in the footer next to the INN logo.


Deprecated Options
------------------

The following homepage layout templates are no longer included in Largo:

- **Slider**: An animated carousel of featured stories with large images. This should be automatically updated to the "Blog" template after upgrading Largo.


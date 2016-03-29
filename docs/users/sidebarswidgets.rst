====================
Sidebars and Widgets
====================

Overview
========

Largo adds a number of widget areas and custom widgets to allow for easy, drag and drop management of content blocks on your site. You can access and edit any of these widget areas from the **Appearance > Widgets** menu in the WordPress Dashboard.

To add a content block to a widget area, simply drag and drop a widget from the available widgets on left to the widget area on the right where you want it to appear. Note that as soon as you add any widgets to a widget area, any default content in that widget area will no longer display so you'll need to completely populate that area with the content you want. Most widgets have additional settings to configure how they display on the site.

Note that some widgets are small and can easily fit in narrow columns. Other widgets can contain lots of content that doesn’t fit well in a small space. It’s important to see how a widget affects the page layout and adjust as needed.

For more about widgets and how WordPress handles them, see `the WordPress Codex article on widgets <http://codex.wordpress.org/WordPress_Widgets>`_.

Widget Areas
============

The way some of these widget areas appear on your site will depend on the layout options you set in the **Appearance > Theme Options > Layout** menu.

Sidebar Widget Areas
--------------------

- **Main Sidebar** - By default this is the sidebar used for all non-single pages (homepage, category pages, date archive pages, etc.)
- **Single Sidebar** - Used for single posts and pages, if you select a two-column template for single-post pages. As of Largo version 0.4, the recommended default layout for single posts and pages is a single column layout that does **not** include a sidebar unless you explicitly set one from the **Layout Options > Custom Sidebar** dropdown menu when editing an individual post. Note that if you do not populate this widget area, Largo will fall back to using the Main Sidebar. 
- **Topic Sidebar** - An optional widget area enabled from the **Appearance > Theme Options > Layout** menu. When enabled, this widget area will be used in the place of the main sidebar on all category, tag, and custom taxonomy pages.

Footer Widget Areas
-------------------

Depending on which layout you select in the **Appearance > Theme Options > Layout** menu for the **Footer Layout** option, you will see either three or four numbered footer widget areas in **Appearance > Widgets**. The footer widget numbers reflect their order in the foot from left to right. These areas will typically be populated by some default widgets, but  you can change this by adding the widgets of your choice.

The Article Bottom Widget Area
------------------------------

Prior to Largo 0.4, appearance of elements at the bottom of article pages was set from the **Appearance > Theme Options > Basic Settings** menu. With version 0.4 we made this a widget area instead to allow for more flexibility.

By default, the only content displayed at the bottom of an article is the comments section (when comments are enabled). You can easily add other elements like the author bio, related posts, and prev/next links widgets. Many widgets are designed to be contextual based on the article's taxonomy, author, etc. With many different widgets and options, it's a good idea to experiment and see what works best.

Less Common Widget Areas
------------------------

- **Homepage Alert** - For sites that cover breaking news, this is an optional widget area where you can place a text widget to add a "breaking" banner to the top of the homepage. The styling for this widget area is very basic, so if you plan to use it you might want to create either some custom CSS for a text widget and/or create and register your own "breaking news" widget for this area.
- **Header Ad Zone** -  If you have enabled the optional header leaderboard ad zone from the **Appearance > Theme Options > Advanced** tab, drop an ad widget to appear in this area.
- **Homepage Left Rail** - If you are using a three column homepage layout (set in **Appearance > Theme Options > Layout**) this is the widget area for the contents of the left-side column.

Custom Widget Areas
-------------------

Largo also allows you to add any number of **custom widget areas** or display on certain pages of the site. For example, you could create a sidebar for a category or series that is only displayed on the archive page for that taxonomy, and/or on posts that appear in that taxonomy. Custom sidebars can be added from the **Appearance > Theme Options > Layout** tab under the **Sidebar Options** header. Instructions for setting these options are in `Sidebar Options <#sidebar-options>`_ below.

Custom Widgets
==============

Largo adds a number of custom widgets in addition to the `standard widgets <http://codex.wordpress.org/Widgets_SubPanel>`_ that come included with WordPress.

All Largo custom widgets have:

- The choice of three backgrounds (default, reverse and none) to provide styling options and classes for custom CSS.
- The ability to hide the widget on desktops, tablets, and phones using the responsive utility classes `from Twitter Bootstrap <http://getbootstrap.com/2.3.2/scaffolding.html#responsive>`_. We recommend hiding less-necessary widgets for users on smaller viewports such as mobile devices to create a cleaner experience that allows them to focus on your content.
- The ability to set a link for the widget title.

Here is a complete list of the Largo custom widgets as of version 0.5.4:

INN Member Stories
------------------

Shows a list of curated stories from other members of INN. By default this widget will display headlines, source, and date of the three recent stories from INN members. Options include showing excerpts and adjusting the number of stories to show.

Largo About Site
----------------

Displays the site description provided in the **Appearance > Theme Options > Basic Settings** menu. This description can include some HTML elements (such as links, bold text, etc.).

Largo Author Bio
----------------

Shows a bio of the author(s) for a given post including their photo and social media links (when filled out in their user profile). Also includes a "More by author" link to the landing page for all posts by the author. This widget only works on single-post pages.

Largo Disclaimer
----------------

When the "Enable Disclaimer Widget" option is enabled from the **Appearance > Theme Options > Advanced** menu, this widget will show the article disclaimer you have provided. You can change the disclaimer on a per-article basis by modifying it in the post edit screen.

Largo Donate Widget
-------------------

Shows a donate message and button with a link to a donation page. You can change the message, button text, and/or link on a per-widget basis if you need to for different pages/sections of your site. By default, the link used in this widget is the one set under the **Appearance > Theme Options > Basic Settings** menu (also used for the donate button in the site header).

Largo Explore Related
---------------------

A tabbed widget to show related stories by category/tag. This widget works only on single-post pages, and fits best in the Article Bottom widget area. We recommend using the Largo Related posts widget instead but this widget is retained for backwards compatibility.

Largo Facebook Widget
---------------------

Shows a Facebook "like" box/feed. This will only work for Facebook Pages, which are by default public, not personal Facebook accounts. If you get an error message saying "Error: Not a valid Facebook Page url," it typically means the url is not a public Facebook Page.

Largo Follow
------------

Uses the social media links provided for your site in the **Appearance > Theme Options > Basic Settings** menu to show buttons to follow you on select social networks. 

Largo Image Widget
------------------

The Largo Image Widget allows you to place an image in any widget area, along with a title and text caption. This can be useful to promote something else on your website or on another site, or to create a custom message or ad. To begin just select an image in the widget settings and begin configuring. You can add a hyperlink from the image to any url, and choose to have the url open in the same window or a new window. You can choose a preset image size or set a custom size, and set the image alignment in relation to the caption text. 

As with all images on your website, please be sure to add Alternate Text to tell visually impaired users what the image is. This should be a short phrase or sentence, similar to how you would describe the image to someone over the phone.

Largo Post Series Widget
------------------------

This widget is useful for single-post pages to show the title and description of the series the post belongs to. If the post has not been assigned to a series, the widget will display nothing.

Largo Prev/Next Links
---------------------

Most commonly used in the Article Bottom widget area, this will show links to the next and previous posts ordered by published date. Optionally, you can choose to limit the posts shown to the next/previous stories in the same category as the current post.

Largo Recent Comments
---------------------

This widget simply shows recent comments, with links to the posts they appear on. Besides the standard widget options, you can set the number of comments to display in the widget.

Largo Recent Posts
------------------

This is a powerful widget to show recent posts in various formats with the option to limit by category, tag, custom taxonomy term and/or author. This widget has many options that enable display of a filtered set of articles or excerpts based on criteria of your choosing. You can limit by author and/or category, and then further limit by tag. You can limit by a custom taxonomy like Post Prominence, Series, or Post Types (the latter two need to be enabled in **Appearance > Theme Options > Advanced**), and you can combine these filters as needed. 

Limiting by taxonomies and their terms requires using the "slug" for each. To start with, here are the available taxonomies with their names and slugs:

===============   ======================================================
Taxonomy Name     Taxonomy Slug
===============   ======================================================
Categories        category
Tags              post_tag
Post Prominence   prominence
Series            series
Post Types        post-type
===============   ======================================================

Each term within a taxonomy also has a name and a slug. For example, the slug for a tag of "social media" would be "social-media". You can find the slugs for the terms in any taxonomy by checking its settings page, which lists the names and their slugs.

If you want to limit by custom taxonomy, enter the taxonomy's slug in the Taxonomy field, and then the term's slug in the Term field. For example if you want to display Post Prominence content assigned to "Featured in Series", you'll enter "prominence" as the Taxonomy and "series-featured" as the Term. 

After setting the limits on the content you want displayed, you can adjust how it's displayed.  You can set how thumbnails, excerpts, bylines, and top terms are displayed, and add a More link to a URL. One additional setting may be very helpful: Depending on how you limit by taxonomy etc., you may want to select the option to Avoid Duplicate Posts which will cause this widget to skip any posts that are already shown elsewhere on the page.

Largo Related Posts
-------------------

This widget works on single-post and Series pages. It shows the title and thumbnail image for related posts.  Related posts can be set manually by adding related post IDs in the Additional Options/Related Posts box of the post edit screen. If no related posts are set, the widget will back to a default algorithm that selects the most closely-related posts based on series, category or tag. Widget options include changing its title (defaults to "Read Next"), the number of related posts to display, and the related post Thumbnail position.

Largo Series Posts
------------------

Displays links to up to five posts in the series selected. The first link will include the post title and excerpt, and a thumbnail of the Featured Image if one is included in the post. You can also choose to show the date with the first post link. The remaining post links are displayed as a simple unordered list under a customizable heading, which defaults to "Explore". 

Largo Staff Roster
------------------

Displays a list of users on your site, with a thumbnail image, name, and a link to a page containing each user's posts. Widget options include selecting specific user groups, and changing the title displayed with the widget ( defaults to "Staff Members").  Note that you can exclude specific users from being displayed in the widget by going to **Users > Edit User** and in the Staff Status setting selecting "Hide in roster". 

Largo Tag List
--------------

Typically used in the Article Bottom widget area, this will display a list of categories and tags associated with a given post. Each term in this list links to the archive page for the term. Widget options include changing title of the list, and setting the maximum number of terms to show.

Largo Taxonomy List
-------------------

List all of the terms in a given taxonomy with links to their archive pages. This widget is most commonly used to generate a list of series/projects with links to their project pages. An explanation of the options:

	**Title**: This is what the widget will be named. Leave blank to have no title displayed.

	**Taxonomy**: This dropdown allows you to configure the taxonomy from which terms will be drawn. Example taxonomies are Category, Tag, Post Prominence, and Series. This option defaults to Series.

	**Sort order**:
		- Alphabetically: Terms will be sorted in alphabetical order by their name. Terms beginning with punctuation will come after terms beginning with letters. Terms beginning with numbers will come before terms beginning with letters.
		- Most Recent: Terms created more recently will appear first. Term sort order is determined by the term's ID, and newer terms always have higher ID values.

	**Count**: By default the widget will pull in 5 posts from the taxonomy. Use the Count field to increase or decrease the number of posts displayed. The minimum number of terms displayed is 1. In theory you can display all terms in a taxonomy by setting the count to the number of posts in the taxonomy, but in practice you should not set the number higher than 10. Using a large count number in conjunction with the thumbnail or headline options will negatively affect your site's performance, and may cause your server to run out of memory.

	**Exclude**: Entering a comma-separated list of term IDs will exclude those terms from displayed results. To do this, go to "Posts" in the dashboard. Under "Posts" will be a list of taxonomies. Click on the desired taxonomy entry. A list of taxonomy terms will appear. Find your term in the list, then hover over or click on the term and find the tag_ID number in the URL for that term.

	For example, in this URL for the term "Bacon" the term ID is 482:

		``/wp-admin/edit-tags.php?action=edit&taxonomy=post_tag&tag_ID=482&post_type=post``

	**Display as dropdown**: This option causes the term results to be displayed as a plain dropdown. No thumbnails or recent posts will be displayed.

	**Display thumbnails**: Check this option if you want to display thumbnails next to the term link. If the taxonomy is "Series" and a series landing page exists for the series term, the series landing page's featured image will be displayed if it is available. In all other cases, the featured media thumbnail image from the most-recent post in the term will be displayed.

	**Display headline**: If checked, the headline of the most-recent post in the taxonomy term will be displayed.

Largo Twitter Widget
--------------------

Allows for the display of a Twitter profile, list or search widget. Note that to use this widget you'll need to create a Twitter widget and grab its ID from https://twitter.com/settings/widgets. Each widget on Twitter has a URL with a long string of numbers. That's the Twitter Widget ID, so copy and past that number into the Largo Twitter Widget. 

On Twitter you can create widgets for a user timeline, favorites, list, or search. In the Largo Twitter Widget, set the Widget Type for the type you want and paste in the Twitter Widget ID.

*Note: In most cases the Largo Twitter Widget will work fine if you just set the Twitter Widget ID. As a fallback in case of errors loading scripts from Twitter, it's a good idea to also add the Twitter Username, List slug, and search query in the settings*.

Largo Roundups Widget
---------------------

If you have the **Link Roundups** plugin installed, this widget will display the most recent Link Roundup posts. You can change the number of posts to show, limit display to a category, and add a More link at the bottom of the widget. 

For more on how this works see the `Link Roundups widget documentation <https://github.com/INN/link-roundups/blob/master/README.md>`_.


Widgets Deprecated in 0.4x
=========================

- **Largo Footer Featured Posts** - Works similarly to the Featured Widget above but limited to the "footer featured" term in the prominence taxonomy.
- **Largo Sidebar Featured Posts** - Works similarly to the Featured Widget above but limited to the "footer featured" term in the prominence taxonomy.

Widgets Deprecated in 0.5x
=========================

- **Largo Featured Posts** - Now just use the Largo Recent Posts widget and limit to the desired term in the Prominence taxonomy.

Sidebar Options
===============

Under the **Appearance > Theme Options > Layout** menu you will find a section labelled "Sidebar Options". This area has a few options to configure the widget areas on your site:

- A checkbox to activate the "Topic Sidebar" as described above.
- An option to include an optional widget region ("sidebar") just above the site footer. This can be used by a few sites to add sponsor logos or additional ad units, etc.

You can also easily register custom sidebar regions, which will then be available as widget regions in **Appearance > Widgets**, and as sidebars in posts. This is useful if you want to create additional widget areas for particular categories or special projects on your site. 

To add a new widget area, simply add a name in the textbox with each widget area you'd like to register on a new line and then click "Save Options".

Once you have added custom widget areas you can add widgets to them from the **Appearance > Widgets** menu. On the post edit page you can select them as sidebars from the **Layout Options > Custom Sidebar** dropdown, or from the Archive Sidebar dropdown when adding or managing a category, tag, or series.

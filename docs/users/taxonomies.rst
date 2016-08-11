Taxonomies
==========

.. _overview-tax:

Overview
--------

Taxonomies are a way to label and organize the posts on your site.

Our general philosophy regarding taxonomies is that they should always be used to group like sorts of things together and never to mix different sorts of things within the same taxonomy. For this reason we create a number of custom taxonomies to supplement the default WordPress categories and tags taxonomies so that you won't have to mix topical organization (e.g. - "Education," "Politics," etc.) with visual display (e.g. - "Homepage Block #3, "Featured," etc.)

WordPress has two default taxonomies:

- **Categories** - The top level categories used to organize content on your site. We recommend using no more than 8 to 10 categories. When selecting and naming your categories, think about the top level buckets you might use to group your content and particularly how visitors might browse your site. Since these are often used as the main navigation on the site, topic-based categories tend to be more useful than content types. Typically a label like "Politics" is more meaningful to visitors than "Investigations" which, even though we may have clear idea of what falls under that label, it's unfamiliar and unclear jargon to the less-experienced reader.

- **Tags** - Keywords or topics used to group related posts together on a more micro level. These are typically people, places, things or subtopics that are used to surface related posts and to help search engines better understand what a post is about. For example, you might have a category called "Politics" and posts in that category might have tags like "Campaign Finance", "Election 2012", etc.

In addition to these two taxonomies, Largo adds three more custom taxonomies:

- **Post Prominence** - This is used to determine how and where posts are displayed on the site (for example, top stories on the homepage or featured content widgets in a sidebar or footer). You might also add additional terms to this taxonomy to create custom feeds for distribution to content partners (we will cover how to do that in a later post) or for any other display-related purpose. Using this taxonomy is preferred to creating categories for this purpose.

- **Series** (Optional) - Some sites may create a multi-part series or project that is only published for a set amount of time and then should fall into the archive or appear on a "projects" archive page. To support this and also to allow for the creation of custom landing pages, Largo adds an optional "series" taxonomy. When you create a new series, you can add a term to this taxonomy and then make sure all of the posts in that series have this label applied. This will enable the Largo theme to surface related posts in that series in at the bottom of a post (if you are using the "read next" widget) and, in some cases, also on the homepage (depending on the homepage layout you have selected). Largo also adds the ability to create custom sidebars and landing pages for series archive pages, replacing the default series archive template in WordPress.

- **Post Types** (Optional - new in version 0.4) - This taxonomy allows you to organize posts by content type, such as "Article," Photo Gallery," "Data," etc. When you create a new post type you can assign it an icon, which will be used in certain places in the theme. Each post type also has its own archive so that you can add links to your navigation to a page containing all of your "data" projects, for example. In the future, we plan to add custom templates specific to each content type to make them easier to manage and more optimal when displayed to users on your public-facing site.

**Note:** The "Series" and "Post Types" taxonomies are disabled by default, but you can enable them from the Appearance > Theme Options > Advanced menu.

You can add posts to any of these taxonomies from the post edit screen or using the WordPress quick edit functionality from any list of posts. You check the term in the taxonomy you want to add to a post or you can click on "Add New Category" to add a new term to a taxonomy (for example, if the post you are publishing is the first post in a new series you can add that series directly from the page where you are working on that post).


**Hint:** For consistency, we recommend capitalizing every word in tags, categories and custom taxonomies (so: "My Favorite Series" not "My favorite series" and "Campaign Finance" not "campaign finance") and you should also always use full names of public figures, places, etc. without titles (because titles can change over time, e.g. - "Grover Cleveland" not "President Grover Cleveland").

Note that in some cases you may need to click on the "Screen Options" tab at the very top right corner of the post edit screen to ensure that all of the taxonomies are visible.

**Featured Media in Taxonomies**

You can add a Featured Image for each term created in any of the taxonomies. This will appear as a banner image at the top of the term's archive page. While this is optional, an appropriate image can add visual appeal and impact to a topic landing page. At this time only images can be added as Featured Media in taxonomies.

You can add a Featured Image for a term in Posts > Categories (or Tags, Post Prominence, Series, and Post Types) by editing the term, and using the Set Featured Media button to upload an image or select an existing image in the Media Library. For best results use a landscape-shaped image with a width of 1170px, so it spans the top of landing page for the term.  Adding, editing, and removing Featured Media images for a taxonomy term works the same way as for Featured Media in posts and pages.

**Term Descriptions**

Largo provides the option to add a description to terms. The description will appear on term archive pages. Term descriptions can have HTML markup in them, but please be careful when composing HTML for these fields. HTML tags should have zero ``"`` characters or an even number of them - never an odd number. If the HTML tags in the term description are not valid, they can break your site's layout and formatting.

.. _categories-tax:

Categories
----------

The top level categories used to organize content on your site. We recommend using no more than 8 to 10 categories. When selecting and naming your categories, think about the top level buckets you might use to group your content and particularly how visitors might browse your site. Since these are often used as the main navigation on the site, topic-based categories tend to be more useful than content types. Typically a label like "Politics" is more meaningful to visitors than "Investigations" which, even though we may have clear idea of what falls under that label, it's unfamiliar and unclear jargon to the less-experienced reader.

Add a new category by clicking on Posts > Categories and then provide the following information:

Options:

- **Name:** The name is how the category appears to visitors on your site or in your site navigation.
- **Slug:** The URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.
- **Parent:** A drop-down menu of other categories, this allows you to set a parent/child relationship for your categories. This does not make much of a difference for your visitors but it can be helpful to organize your categories if you have a lot of them.
- **Description:** In Largo the description is displayed at the top of the archive page for the category. It is also used as the meta description and in the open graph tag for that page (this controls how the page appears in search results or when it is shared on various social networks).
- **Archive Sidebar:** The sidebar to show on this category's archive page, chosen from a list of your currently registered sidebars. Additional sidebars can be created in Appearance > Theme Options > Layout Options > Sidebar Options by entering names of new sidebars in the box. These sidebars will then become available under the Appearance > Widgets menu where you can add and arrange the content you want to appear.

Optionally, categories can be created while editing a post by clicking on "add new category" under the list of current categories in the category box in the right column.

.. _tags-tax:

Tags
----

Keywords or topics used to group related posts together on a more micro level. These are typically people, places, things or subtopics that are used to surface related posts and to help search engines better understand what a post is about. For example, you might have a category called "Politics" and posts in that category might have tags like "Campaign Finance", "Election 2012", etc.

Add a new tag by clicking on Posts > Tags and then provide the following information

Options:

- **Name:** The name is how the tag appears to visitors on your site or in your site navigation.
- **Slug:** The URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.
- **Description:** In Largo the description is displayed at the top of the archive page for the category. It is also used as the meta description and in the open graph tag for that page (this controls how the page appears in search results or when it is shared on various social networks).
- **Archive Sidebar:** The sidebar to show on this tag's archive page, chosen from a list of your currently registered sidebars. Additional sidebars can be created in Appearance > Theme Options > Layout Options > Sidebar Options by entering names of new sidebars in the box. These sidebars will then become available under the Appearance > Widgets menu where you can add and arrange the content you want to appear.

Optionally, tags can be created while editing a post by entering a comma-separated list of tags in the "tags" box in the right column.

.. _post-prominence-tax:

Post Prominence
---------------

This is used to determine how and where posts are displayed on the site (for example, top stories on the homepage or featured content widgets in a sidebar or footer). You might also add additional terms to this taxonomy to create custom feeds for distribution to content partners (we will cover how to do that in a later post) or for any other display-related purpose. Using this taxonomy is preferred to creating categories for this purpose.

Default Terms (added by Largo when the theme is activated):

- **Top Story:** If you are using the Newspaper or Carousel optional homepage layout, add this label to a post to make it the top story on the homepage
- **Featured in Category:** This will allow you to designate a story to appear more prominently on category archive pages.
- **Featured in Series:** Select this option to allow this post to float to the top of any/all series landing pages sorting by Featured first.
- **Footer Featured:** If you are using the Recent Posts widget in the footer, we recommend configuring it to show posts in this term.
- **Homepage Featured:** If you are using the Newspaper or Carousel optional homepage layout, add this label to posts to display them in the featured area on the homepage.
- **Sidebar Featured:** If you are using the Recent Posts widget in the footer, we recommend configuring it to show posts in this term.

It is rare that you will add additional terms to this taxonomy as they are typically added by your theme but should you need to they can be added from the Posts > Post Prominence menu.

.. _series-tax:

Series
------

This taxonomy is disabled by default, but you can enable it from the **Appearance > Theme Options > Advanced Options** menu.

Some sites may create a multi-part series or project that is only published for a set amount of time and then should fall into the archive or appear on a "projects" archive page. To support this and also to allow for the creation of custom landing pages, Largo adds an optional "series" taxonomy. When you create a new series, you can add a term to this taxonomy and then make sure all of the posts in that series have this label applied. This will enable the Largo theme to surface related posts in that series in at the bottom of a post (if you are using the "read next" widget) and, in some cases, also on the homepage (depending on the homepage layout you have selected). Largo also adds the ability to create custom sidebars and landing pages for series archive pages, replacing the default series archive template in WordPress.

Options:

- **Name:** The name of the series/project as you would like it to appear to visitors on your site or in your site navigation.
- **Slug:** The URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.
- **Parent:** A drop-down menu of other series, this allows you to set a parent/child relationship for your series. This does not make much of a difference for your visitors but it can be helpful to organize your series if you have a lot of them.
- **Description:** In Largo the description is displayed at the top of the archive page for the series. It is also used as the meta description and in the open graph tag for that page (this controls how the page appears in search results or when it is shared on various social networks).
- **Archive Sidebar:** The sidebar to show on this tag's archive page, chosen from a list of your currently registered sidebars. Additional sidebars can be created in Appearance > Theme Options > Layout Options > Sidebar Options by entering names of new sidebars in the box. These sidebars will then become available under the Appearance > Widgets menu where you can add and arrange the content you want to appear.

.. _post-types-tax:

Post Types
----------

An optional taxonomy added in version 0.4. This taxonomy is disabled by default, but you can enable it from the Appearance > Theme Options > Advanced menu.

This taxonomy allows you to organize posts by content type, such as "Article," Photo Gallery," "Data," etc. When you create a new post type you can assign it an icon, which will be used in certain places in the theme. Each post type also has its own archive so that you can add links to your navigation to a page containing all of your "data" projects, for example. In the future, we plan to add custom templates specific to each content type to make them easier to manage and more optimal when displayed to users on your public-facing site.

Options:

- **Name:** The name of the post type as you would like it to appear to visitors on your site or in your site navigation.
- **Slug:** The URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.
- **Parent:** A drop-down menu of other post types, this allows you to set a parent/child relationship for your post type. This does not make much of a difference for your visitors but it can be helpful to organize your post types if you have a lot of them.
- **Description:** In Largo the description is displayed at the top of the archive page for the post type. It is also used as the meta description and in the open graph tag for that page (this controls how the page appears in search results or when it is shared on various social networks).
- **Term icon:** The icon the theme may display for posts of a given post type to help users to distinguish between them quickly. By default, the icons available are: Search, Mail, Heart, Heart Empty, Star, Star Empty, Videocam, Picture, Camera, Ok, Cancel, Plus, Minus, Help, Home, Link, Tag, Tags, Download, Print, Comment, Chat, Location, Doc Text, Doc Text Inv, Phone, Menu, Calendar, Headphones, Play, Table, Chart Bar, Spinner, Map, Share, Gplus, Pinterest, Cc, Flickr, Linkedin, Rss, Twitter, Youtube, Facebook, Github, Itunes, Tumblr, Instagram

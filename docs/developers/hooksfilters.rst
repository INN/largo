Hooks and filters
=================

Homepage template filters
-------------------------

**filter: largo_homepage_feature_stories_list_maximum**

Filter the number of posts to display in the list of feature stories in 'HomepageSingleWithFeatured' templates.

*args: $max*

------------

filter: **largo_homepage_series_stories_list_minimum**

*args: $min*

Filter the minimum number of posts to show in a series list in the
HomepageSingleWithSeriesStories homepage list.

This is used in the query for the series list of posts in the same series
as the main feature. If fewer than this number of posts exist, the list
is hidden and the headline dominates the full box.

------------

filter: **largo_homepage_series_stories_list_maximum**

*args: $max*

Filter the maximum number of posts to show in a series list in the
HomepageSingleWithSeriesStories homepage list.

This is used in the query for the series list of posts in the same series
as the main feature. This is the maximum number of posts that will display
in the list.

------------

filter: **largo_homepage_topstories_post_count**

*args: $showposts*

Filter the number of posts that are displayed in the right-hand side of the
Top Stories homepage template.

This is used in the query for the list of posts in the "Homepage Featured"
taxonomy. If more than 3 posts are found, they will display under a
"More Headlines" heading, just as headline links.

Other filters and actions
-------------------------

**filter: largo_registration_extra_fields**

Called directly before the `[largo_registration_form]` shortcode has finished executing. You can append to this any addition form fields that you want to process.

**Usage:**

Passed in is an array of values of post variables generated if a user is trying the form for a second time. You can use these to pre fill your extra field inputs.

Also passed in is a WP_Error object that stores all the generated errors for the page. Use this if you'd like to display an error message on the erroneous field.

``function filter_function_name($values, $errors) {``
``# ... }``
``add_filter('largo_registration_extra_fields'``, ``'filter_function_name');``

**action: largo_validate_user_signup_extra_fields**

Called directly before form values from the `[largo_registration_form]`. Hook to this in order to validate any of the extra form data added with the largo_registration_extra_fields filter. For example, you could validate a captcha that was added to the form's fields.

**Usage:**

Passed in is an array $result which contains all post data for the form. Contained in this array at $result["errors"] is a WP_Error object. Adding errors to this object will cancel form submission.

Also passed in is an array that contains only the extra fields that were present. This is an easy way to check only the extra data.

``function filter_function_name($result,$extras) {``
``# ... }``
``add_action('largo_validate_user_signup_extra_fields'``, ``'filter_function_name');``

**filter: largo_lmp_args**

Passed in this are the arguments for the Load More Posts WP_Query. An example usage would be to check if ``is_home()`` and then restrict the posts returned by the query to those in the homepage featured prominence term.

**filter: largo_post_social_links**

*args: $output*

Called before ``largo_post_social_links()`` returns or echos the social icons. The argument ``$output`` is HTML, usually containing HTML looking like this: (Whitespace has been added for readability)

.. code::

    <div class="largo-follow post-social clearfix">
        <span class="facebook">
            <a target="_blank" href="http://www.facebook.com/sharer/sharer.php?u=  ...">
                <i class="icon-facebook"></i>
                <span class="hidden-phone">Like</span>
            </a>
        </span>
        <span class="twitter">
            <a target="_blank" href="https://twitter.com/intent/tweet?text= ...">
                <i class="icon-twitter"></i>
                <span class="hidden-phone">Tweet</span>
            </a>
        </span>
        <span class="print">
            <a href="#" onclick="window.print()" title="Print this article" rel="nofollow">
                <i class="icon-print"></i>
                <span class="hidden-phone">Print</span>
            </a>
        </span>
        <span data-service="email" class="email custom-share-button share-button">
            <i class="icon-mail"></i>
            <span class="hidden-phone">Email</span>
        </span>
    </div>

Template Hooks
--------------

**What are these and why would I want to use them?**

Sometimes you may want to fire certain functions or include additional blocks of markup on a page without having to modify or override an entire template file.

WordPress allows you to define custom action hooks using the `do_action() <http://codex.wordpress.org/Function_Reference/do_action>`_ function like so:

``do_action( 'largo_top' );``

and then from functions.php in a child theme you can use the `add_action() <http://codex.wordpress.org/Function_Reference/add_action>`_ function to fire another function you define to insert markup or perform some other action when the do_action() function is executed, for example:

``add_action( 'largo_top', 'largo_render_network_header' );``

which would call the largo_render_network_header function when the largo_top action is executed.

We are in the process of adding a number of action hooks to Largo to make it easier for developers to modify templates without having to completely replace them in a child theme.

This has the advantage of making your code much easier to maintain (because you're more explicit about what part of the template you're modifying) and also makes it easier to make the update to future versions of Largo because even if the template files change considerably, the placement of the hooks will likely remain the same.

Here is the current list of hooks available in Largo (available as of v.0.4):

**header.php**
 - **(wp_head)** - if you need to insert anything in the <head> element use the built-in wp_head hook
 - **largo_top** - directly after the opening <body> tag and "return to top" target div
 - **largo_before_global_nav** - only fires if the global nav is shown, directly before the global nav partial
 - **largo_after_global_nav** - only fires if the global nav is shown, directly after the global nav partial
 - **largo_before_header** - before the main <header> element
 - **largo_after_header** - after the main <header> element
 - **largo_after_nav** - after the nav, before #main opening div tag
 - **largo_main_top** - directly after the opening #main div tag
 
**home.php**

These actions are run on all homepage templates, including the Legacy Three Column layout.

 - **largo_before_sticky_posts** - Runs in the main column, before the sticky post would be rendered
 - **largo_after_sticky_posts** - Runs in the main column, after where the sticky post would be rendered, before the homepage bottom area.
 - **largo_after_homepage_hottom** - Runs after the homepage bottom area, before the termination of the main column.
 
**sidebar.php**

 - **largo_before_sidebar** - before the sidebar opening div tag
 - **largo_before_sidebar_widgets** - after the opening div tag but before the first widget
 - **largo_after_sidebar_widgets** - after the last widget but before the closing div tag
 - **largo_after_sidebar** - after the closing div tag
 
**footer.php**

 - **largo_before_footer** - after the closing div tag for #page but before the .footer-bg (this also comes after the optional "before footer" widget area that can be activated from the layout tab of the theme options
 - **largo_before_footer_widgets** - before the main footer widget areas
 - **largo_before_footer_boilerplate** - after the main footer widget areas and before the boilerplate (copyright message, credits, etc.)
 - **largo_after_footer_copyright** - after the copyright message paragraph, but before the end of the boilerplate; useful if you want to insert addresses or other information about your site
 - **largo_before_footer_close** - after the boilerplate but still inside the footer container
 - **largo_after_footer** - after the closing <div> tag for .footer-bg but before the sticky footer
 - **(wp_footer)** - if you need to insert anything just before the closing <body> tag use the wp_footer hook
 
**single.php**

 - **largo_before_post_header** - inside <article> but before the post <header> element
 - **largo_after_post_header** - just after the closing post <header> element (before the hero image/video)
 - **largo_after_hero - in the single column** (new) single post template, just after the hero (featured) image/video
 - **largo_after_post_content** - directly after the .entry-content closing <div> tag
 - **largo_after_post_footer** (deprecated in 0.4) - before the closing </article> tag, replaced in the new layouts by largo_after_post_content
 - **largo_before_post_bottom_widget_area** - after the closing </article> tag but before the post bottom widget area
 - **largo_after_post_bottom_widget_area** - directly after the post bottom widget area (but before the comments section)
 - **largo_before_comments** - before the comments section
 - **largo_after_comments** - after the comments section
 - **largo_after_content** - after the close of the #content div

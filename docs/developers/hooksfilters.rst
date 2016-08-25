Hooks and filters
=================

Homepage template filters
-------------------------

**filter: largo_homepage_feature_stories_list_maximum**

    Filter the number of posts to display in the list of feature stories in 'HomepageSingleWithFeatured' templates.

    *args: $max*

filter: **largo_homepage_series_stories_list_minimum**

    *args: $min*

    Filter the minimum number of posts to show in a series list in the HomepageSingleWithSeriesStories homepage list.

    This is used in the query for the series list of posts in the same series as the main feature. If fewer than this number of posts exist, the list is hidden and the headline dominates the full box.

filter: **largo_homepage_series_stories_list_maximum**

    *args: $max*

    Filter the maximum number of posts to show in a series list in the HomepageSingleWithSeriesStories homepage list.

    This is used in the query for the series list of posts in the same series as the main feature. This is the maximum number of posts that will display in the list.

filter: **largo_homepage_topstories_post_count**

    *args: $showposts*

    Filter the number of posts that are displayed in the right-hand side of the Top Stories homepage template.

    This is used in the query for the list of posts in the "Homepage Featured" taxonomy. If more than 3 posts are found, they will display under a "More Headlines" heading, just as headline links.

Other filters and actions
-------------------------

filter: **largo_archive_{$post_type}_title**

    Called in `archive.php` to filter the page title for posts in the ``$post_type`` `post type <https://codex.wordpress.org/Post_Types>`_.

    **Usage:** ::

    function filter_rounduplink_title( $title ) {
        return "Custom title here";
    }
    add_action( 'largo_archive_rounduplink_title', 'filter_rounduplink_title' );

filter: **largo_archive_{$post_type}_feed**

    Called in `archive.php` to filter the feed url for posts in the ``$post_type`` `post type <https://codex.wordpress.org/Post_Types>`_.
post type.

    **Usage:** ::

    function filter_column_feed($title) {
        return "http://example.com/custom_feed_url/feed.xml";
    }
    add_action('largo_archive_column_feed', 'filter_column_feed');

filter: **largo_registration_extra_fields**

    Called directly before the `[largo_registration_form]` shortcode has finished executing. You can append to this any addition form fields that you want to process.

    **Usage:**

    Passed in is an array of values of post variables generated if a user is trying the form for a second time. You can use these to pre fill your extra field inputs.

    Also passed in is a WP_Error object that stores all the generated errors for the page. Use this if you'd like to display an error message on the erroneous field. ::

        function filter_function_name ($values, $errors ) {
            # ...
        }
        add_filter( 'largo_registration_extra_fields', 'filter_function_name' );

action: **largo_validate_user_signup_extra_fields**

    Called directly before form values from the `[largo_registration_form]`. Hook to this in order to validate any of the extra form data added with the largo_registration_extra_fields filter. For example, you could validate a captcha that was added to the form's fields.

    **Usage:**

    Passed in is an array $result which contains all post data for the form. Contained in this array at $result["errors"] is a WP_Error object. Adding errors to this object will cancel form submission.

    Also passed in is an array that contains only the extra fields that were present. This is an easy way to check only the extra data. ::

        function action_function_name( $result, $extras ) {
            # ...
        }
        add_action( 'largo_validate_user_signup_extra_fields', 'action_function_name' );

filter: **largo_lmp_args**

    *args: $args*

    Passed in this are the arguments for the Load More Posts WP_Query. An example usage would be to check if ``is_home()`` and then restrict the posts returned by the query to those in the homepage featured prominence term.

filter: **largo_lmp_template_partial**

    *args: $partial, $post_query*

    Modifies the partial returned by ``largo_load_more_posts_choose_partial($post_query)`` to whatever you want.

    If you are building a custom post type that uses a custom partial, you will need to use this filter to make the correct partial appear in the posts returned by the Load More Posts button on the homepage, on archive pages, and in the search results.

    When building your own filter, you must set the fourth parameter of add_filter to 2: ::

        function your_filter_name( $partial, $post_type, $context ) {
            // things
            return $partials;
        }
        add_filter( 'largo_lmp_template_partial', 'your_filter_name', 10, 2 );
                                                                          ^

    Without setting '2', your filter will not be passed the $post_type or $context arguments.
    In order to set '2', you must set the third parameter of add_filter, which defaults to 10. It is safe to leave that at 10.

filter: **largo_partial_by_post_type**

    *args: String $partial, String $post_type, String $context*

    Modifies the partial returned by ``largo_get_partial_by_post_type`` to whatever you want.

    If you are building a custom post type that uses a custom partial, you will need to use this filter to make the correct partial appear in the posts returned by the Load More Posts button on the homepage, on archive pages, and in the search results.

    When building your own filter, you must set the fourth parameter of add_filter to 3: ::

         function your_filter_name( $partial, $post_type, $context ) {
             // things
             return $partial;
         }
         add_filter( 'largo_partial_by_post_type', 'your_filter_name', 10, 3 );
                                                                          ^

    Without setting '3', your filter will not be passed the $post_type or $context arguments.
    In order to set '3', you must set the third parameter of add_filter, which defaults to 10. It is safe to leave that at 10.


filter: **largo_byline**
    *args: String $output*
    
    Called in ``largo_byline()`` before the admin-user edit link is added. This can be used to append or prepend HTML, or to change the output of the byline function entirely. The passed string is HTML.

filter: **largo_post_social_links**

    *args: String $output*

    Called before ``largo_post_social_links()`` returns or echos the social icons. The argument ``$output`` is HTML, usually containing HTML looking something like this: (Whitespace has been added for readability) ::

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
            <span class="more-social-links">
                <a class="popover-toggle" href="#"><i class="icon-plus"></i><span class="hidden-phone">More</span></a>
                <span class="popover">
                <ul>
                    ${more_social_links_str}
                </ul>
                </span>
            </span>
        </div>

filter: **largo_post_social_more_social_links**
    *args: Array $more_social_links*

    Called in `largo_post_social_links` to filter the array of social links in the "More" drop-down menu displayed in the social links on single posts: the article-top social links, the floating social buttons, and the Largo Follow widget in the article-bottom widget area.

    Passed is an array, where each item in the array is an HTML `li` element containing a link and an icon `i` element to some form of additional, relevant material. The default array in Largo is:

    - Top term link
    - Subscribe to RSS feed for top term
    - Author Twitter link, if the post doesn't have a custom byline and if Co-Authors Plus isn't active

    Adding new social media networks is as simple as adding a new item to the array: ::

        function add_linkedin( $more ) {
            $more[] = '<li><a href=""><i class="icon-linkedin"></i> <span>Your text here!</span></a></li>';
            return $more;
        }
        add_filter( 'largo_post_social_more_social_links', 'add_linkedin' );

.. php:function:: filter largo_remove_hero

    Filter to disable largo_remove_hero based on the global $post at the time the function is run

    :since: 0.5.5
    :param Boolean $run: Whether the function should run against the current post
    :param WP_Post $post: The global ``$post`` object at the time the function is run

    When building your own filter, you must set the fourth parameter of add_filter to 2: ::

        function filter_largo_remove_hero( $run, $post ) {
            # determine whether or not to run largo_remove_hero based on $post
            return $run;
        }
        add_filter( 'largo_remove_hero', 'filter_largo_remove_hero', 10, 2 );
                                                                         ^
.. php:function:: filter largo_top_term_metabox_taxonomies

    Called in the ``largo_top_tag_display`` metabox to allow themes to filter the taxonomies from which are drawn the term options for the top term metabox display.
    
    :since 0.5.5:
    :param Array $taxonomies: array( 'series', 'category', 'post_tag', 'prominence' )

    Add new taxonomies like so: ::

        function add_taxonomies( $taxonomies ) {
            $taxonomies[] = 'columns';
            $taxonomies[] = 'post-type';
            return $taxonomies;
        }
        add_filter('largo_top_term_metabox_taxonomies', 'add_taxonomies');


Template Hooks
--------------

**What are these and why would I want to use them?**

Sometimes you may want to fire certain functions or include additional blocks of markup on a page without having to modify or override an entire template file.

WordPress allows you to define custom action hooks using the `do_action() <http://codex.wordpress.org/Function_Reference/do_action>`_ function like so: ::

    do_action( 'largo_top' );

and then from functions.php in a child theme you can use the `add_action() <http://codex.wordpress.org/Function_Reference/add_action>`_ function to fire another function you define to insert markup or perform some other action when the do_action() function is executed, for example: ::

    add_action( 'largo_top', 'largo_render_network_header' );

This usage would call the ``largo_render_network_header`` function when the largo_top action is executed.

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

**partials/largo-header.php**

 - **largo_header_before_largo_header** - immediately before ``largo_header()`` is output
 - **largo_header_after_largo_header** - immediately after ``largo_header()`` is output. By default, ``largo_header_widget_sidebar`` is hooked here.
 
**for all lists of posts**

-  **largo_loop_after_post_x** - fires after every post in a river of posts on the homepage or archive pages. This is helpful if you want to insert interstitial content in a river of posts (typically things like newsletter subscription widgets, donation messages, etc.). 

This action takes a couple of arguments that may come in handy:

	do_action( 'largo_loop_after_post_x', $counter, $context );
	
	- **$counter** tracks the number of posts in any given loop
	- **$context** is presently either 'archive' or 'home' to give you flexibility to insert different interstitials for different page types. 
	
an example of this in use might look like:

	function mytheme_interstitial( $counter, $context ) {
		if ( $counter === 2  && $context === 'home' ) {
			// do homepage stuff
		} elseif ( $counter === 2 && $context === 'archive' ) {
			// do something different in the same spot on archive pages
		}
	}
	add_action( 'largo_loop_after_post_x', 'mytheme_interstitial', 10, 2 );	
	

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
 - **largo_post_bottom_widget_area** - by default, the "Article Bottom" widget area is output here through `largo_post_bottom_widget_area`
 - **largo_after_post_bottom_widget_area** - directly after the post bottom widget area (but before the comments section)
 - **largo_before_comments** - before the comments section
 - **largo_after_comments** - after the comments section
 - **largo_after_content** - after the close of the #content div

**page.php**

 - **largo_before_page_header** - inside <article> but before the post <header> element
 - **largo_after_page_header** - just after the closing post <header> element
 - **largo_before_page_content** - directly inside the .entry-content <div> tag
 - **largo_after_page_content** - directly before the .entry-content closing <div> tag

**category.php**

 - **largo_category_after_description_in_header** - between the ``div.archive-description`` and before ``get_template_part('partials/archive', 'category-related');``.
 - **largo_before_category_river** - just before the river of stories at the bottom of the category archive page (for adding a header to this column, for example)
 - **largo_loop_after_post_x** - runs after every post, with arguments ``$counter`` and ``context`` describing which post it's running after and what the context is. (In categories, the context is ``archive``.)
 - **largo_after_category_river** - immediately after the river of stories at the bottom of the category archive page, after the Load More Posts button (for adding a footer to this column, for example.)

**search.php**

The Largo search page has two main modes: Google Custom Search Engine and the standard WordPress search emgine. Because the dispalyed layouts are so different, each has their own set of actions.

- **largo_search_gcs_before_container**: If Google Custom Search is enabled, fires before the GCS container
- **largo_search_gcs_after_container**: If Google Custom Search is enabled, fires after the GCS container
- **largo_search_normal_before_form**: Fires before the ouput from ``get_search_form()``
- **largo_search_normal_before_results**: Fires between ``get_search_from`` and "Your search for %s returned %s results", and runs even if there were no search results.
- **largo_search_normal_after_results**: Fires after the search results or ``partials/content-not-found`` are displayed.

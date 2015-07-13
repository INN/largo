inc/related-content.php
=======================

.. php:function:: largo_get_related_topics_for_category()

   Show related tags and subcategories for each main category
   Used on category.php to display a list of related terms

   :since: 1.0

.. php:function:: largo_get_post_related_topics()

   Provides topics (categories and tags) related to the current post in The
   Loop.

   :param int $max: The maximum number of topics to return.

   :returns: array $f term objects.

   :since: 1.0

.. php:function:: largo_get_recent_posts_for_term()

   Provides the recent posts for a term object (category, post_tag, etc).

   :uses: global $post
   :param object $term: A term object.
   :param int $max: Maximum number of posts to return.
   :param int $min: Minimum number of posts. If not met, returns false.

   :returns: array|false $f post objects.

   :since: 1.0

.. php:function:: largo_has_categories_or_tags()

   Determine if a post has either categories or tags

   :returns: bool $rue is a post has categories or tags

   :since: 1.0

.. php:function:: largo_top_term()

   Returns (and optionally echoes) the 'top term' for a post, falling back to a category if one wasn't specified

   :param array|string $options: Settings for post id, echo, link, use icon, wrapper and exclude

.. php:class:: Largo_Related

      The Largo Related class.
      Used to dig through posts to find IDs related to the current post

   .. php:method:: Largo_Related::__construct()

      Constructor.
      Sets up essential parameters for retrieving related posts

      :access: public
      :param integer $number: optional The number of post IDs to fetch. Defaults to 1
      :param integer $post_id: optional The ID of the post to get related posts about. If not provided, defaults to global $post

      :returns: null

   .. php:method:: Largo_Related::popularity_sort()

      Array sorter for organizing terms by # of posts they have

      :param object $a: First WP term object
      :param object $b: Second WP term object

      :returns: integer

   .. php:method:: Largo_Related::cleanup_ids()

      Performs cleanup of IDs list prior to returning it. Also applies a filter.

      :access: protected

      :returns: array $he final array of related post IDs

   .. php:method:: Largo_Related::get_series_posts()

      Fetches posts contained within the series(es) this post resides in. Feeds them into $this->post_ids array

      :access: protected

   .. php:method:: Largo_Related::get_term_posts()

      Fetches posts contained within the categories and tags this post has. Feeds them into $this->post_ids array

      :access: protected

   .. php:method:: Largo_Related::get_recent_posts()

      Fetches recent posts. Used as a fallback when other methods have failed to fill post_ids to requested length

      :access: protected

   .. php:method:: Largo_Related::ids()

      Loops through series, terms and recent to fill array of related post IDs. Primary means of using this class.

      :access: public

      :returns: array $n array of post ids related to the given post

   .. php:method:: Largo_Related::add_from_query()

      Takes a WP_Query result and adds the IDs to $this->post_ids

      :access: protected
      :param object $: WP_Query object
      :param boolean $ptional: whether the query post order has been reversed yet. If not, this will loop through in both directions.

   .. php:method:: Largo_Related::have_enough_posts()

      Counts to see if enough posts have been found
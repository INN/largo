.. php:function:: largo_fb_url_to_username()

   Returns a Facebook username or ID from the URL

   :param string $url: a Facebook url

   :returns: string $he Facebook username or id extracted from the input string

   :since: 0.4

.. php:function:: largo_fb_user_is_followable()

   Checks to see if a given Facebook username or ID has following enabled by
   checking the iframe of that user's "Follow" button for <table>.
   Usernames that can be followed have <tables>.
   Users that can't be followed don't.
   Users that don't exist don't.

   :param string $username: a valid Facebook username or page name. They're generally indistinguishable, except pages get to use '-'

   :uses: wp_remote_get

   :returns: bool $he user specified by the username or ID can be followed

.. php:function:: largo_twitter_url_to_username()

   Returns a Twitter username (without the @ symbol)

   :param string $url: a twitter url

   :returns: string $he twitter username extracted from the input string

   :since: 0.3

.. php:function:: largo_youtube_url_to_ID()

   Give it a YouTube URL, it'll give you just the video ID

   :param string $url: a YouTube URL (e.g. - https://www.youtube.com/watch?v=i5vfw5f1CZo)

   :returns: string $ust the video ID (e.g. - i5vfw5f1CZo)

   :since: 0.4

.. php:function:: largo_youtube_iframe_from_url()

   For a given YouTube URL, return an iframe to embed

   :param string $url: a YouTube URL (e.g. - https://www.youtube.com/watch?v=i5vfw5f1CZo)
   :param bool $echo: return or echo the output

   :returns: string $ standard YouTube iframe embed code

   :uses: largo_youtube_url_to_ID

   :since: 0.4

.. php:function:: largo_youtube_image_from_url()

   For a given YouTube URL, return the image url for various thumbnail sizes

   :param string $url: a YouTube URL (e.g. - https://www.youtube.com/watch?v=i5vfw5f1CZo)
   :param string $he: image size you'd like (options are: thumb | small | medium | large)
   :param bool $echo: return or echo the output

   :returns: string $ youtube image url

   :uses: largo_youtube_url_to_ID

   :since: 0.4

.. php:function:: largo_make_slug()

   Transform user-entered text into WP-compatible slugs

   :param string $string: the string to turn into a slug
   :param string $maxLength: the max length for the slug in characters

   :since: 0.4

.. php:function:: var_log()

   Send anything to the error log in a human-readable format

   :param mixed $stuff: the stuff to be sent to the error log.

   :since: 0.4

.. php:function:: largo_render_template()

   :param string $slug: the slug of the template file to render.
   :param string $name: the name identifier for the template file; works like get_template_part.
   :param array $context: an array with the variables that should be made available in the template being loaded.

   :since: 0.4*
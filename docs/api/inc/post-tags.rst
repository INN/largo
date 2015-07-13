inc/post-tags.php
=================

.. php:function:: largo_has_gravatar()

   Determine whether or not an author has a valid gravatar image
   see: http://codex.wordpress.org/Using_Gravatars

   :param $email $tring: an author's email address

   :returns: bool $rue if a gravatar is available for this user

   :since: 0.3

.. php:function:: largo_has_avatar()

   Determine whether or not a user has an avatar. Fallback checks if user has a gravatar.

   :param $email $tring: an author's email address

   :returns: bool $rue if an avatar is available for this user

   :since: 0.4

.. php:function:: largo_trim_sentences()

   Attempt to trim input at sentence breaks

   :param $input $tring:
   :param $sentences $umber: of sentences to trim to
   :param $echo $cho: the string or return it (default: return)

   :returns: $output $rimmed string

   :since: 0.3
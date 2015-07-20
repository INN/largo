inc/users.php
=============

.. php:function:: largo_contactmethods()

   Modify the user profile screen
   Remove old contact methods (yahoo, aol and jabber)
   Add new ones (twitter, facebook, linkedin)

   :since: 1.0

.. php:function:: largo_filter_guest_author_fields()

   Same deal, but for guest authors in the Co-Authors Plus plugin

   :TODO:: figure $ut if there's a way to remove fields as we do for regular users above

   :since: 1.0

.. php:function:: largo_admin_users_caps()

   In a multisite network, allow site admins to edit user profiles
   props: http://thereforei.am/2011/03/15/how-to-allow-administrators-to-edit-users-in-a-wordpress-network/

   :since: 1.0

.. php:function:: largo_edit_permission_check()

   Checks that both the editing user and the user being edited are
   members of the blog and prevents the super admin being edited.

.. php:function:: largo_get_user_list()

   Get users based on a role. Defaults to fetching all authors for the current blog.

   key -- `roles` -- which should be an array of roles to include in the query.

   :param $args $rray: Same as the options one would pass to `get_users` with one extra

   :since: 0.4

.. php:function:: largo_render_user_list()

   Render a list of user profiles based on the array of users passed

   :param $users $rray: The WP_User objects to use in rendering the list.
   :param $show_users_with_empty_desc $ool: Whether we should skip users that have no bio/description.

   :since: 0.4

.. php:function:: largo_render_staff_list_shortcode()

   Shortcode version of `largo_render_user_list`

   Example of possible attributes:

   	[roster roles="author,contributor" include="292,12312" exclude="5002,2320" show_users_with_empty_desc="true"]

   :param $atts $rray: The attributes of the shortcode.

   :since: 0.4

.. php:function:: more_profile_info()

   Display extra profile fields related to staff member status

   :param $users $rray: The WP_User object for the current profile.

   :since: 0.4

.. php:function:: save_more_profile_info()

   Save data from form elements added to profile via `more_profile_info`

   :param $user_id $rray: The ID of the user for the profile being saved.

   :since: 0.4
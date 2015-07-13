Contact Forms
=============

To allow for the addition of simple contact forms to your site, Largo incorporates a slightly customized version of the `Clean Contact <https://wordpress.org/plugins/clean-contact//>`_ plugin.

The settings for this plugin can be found under Settings > Clean Contact. By default it will send email submitted through the form to the admin email address you set under the Settings > General menu.

Creating a contact page is as simple as creating a new page and putting the following shortcode in the editor, on its own line where you want the contact form to appear:

``[clean-contact]``

Feel free to put additional information on the page either before or after the contact form.

You can also use the ``[clean-contact]`` shortcode in posts to provide a contact form there.

Finally, it is possible to override the default settings for the contact form directly from the shortcode using the following parameters: ::

	[clean-contact: prefix="help" email="test@example.com" thanks="Cheers!" bcc="admin@example.com" thanks_url="/thankyou.html"]

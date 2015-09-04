Note:

`less/inc/footer.less` expects that the image output by `inn_logo()` will be 1669px wide and 385px tall.

The default image output by `inn_logo()` is `inn_logo_reversetype.png`, which has white text and is good for dark backgrounds.

If you would like to use a light background, add this to your theme's `functions.php`:

```php
function inn_logo() {
		?>
			<a href="//inn.org/">
				<img id="inn-logo" src="<?php echo(get_template_directory_uri() . "/img/inn_logo_blue_fimal.png"); ?>" alt="<?php printf(__("%s is a member of the Institute for Nonprofit News", "largo"), get_bloginfo('name')); ?>" />
			</a>
		<?php

}
```

If you are updating the images here in Largo, please check the assumptions made in `less/inc/footer.less` near `@inn-logo-width`. You may need to replace the values of `@inn-logo-physical-width` and `@inn-logo-physical-height`, as well as replacing `184` with the width of any significant feature in the logo.

<?php
/**
 * The Footer widget areas.
 *
 * If you would like to use a custom footer widget area, simply save it in your child theme's partials directory.
 * Then create a Wordpress filter hooked to 'largo_options' that adds:
 *     $options[$value]['options']['your_id'] = 'path to the option image';
 * using this function to find $value:
 *     array_search('footer_layout', array_column($options, 'id'));
 *
 * At the same time, you may remove the Largo default footer options form the $options array.
 *
 * @link https://secure.php.net/manual/en/function.array-search.php
 * @link https://codex.wordpress.org/Function_Reference/get_template_part
 * @see optionsframework_options
 * @since 0.5.2
 *
 */

 // Get the layout from the options.
$layout = of_get_option('footer_layout');

// If the specified template does not exist in the child or parent theme, use 3col-default
if ( locate_template( 'partials/footer-widget-' . $layout . '.php' ) == '' ) {
	$layout = '3col-default';
}

?>

<div id="supplementary" class="row-fluid _<?php echo $layout ?>">
	<?php get_template_part( 'partials/footer-widget', $layout); ?>
</div>

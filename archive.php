<?php
/**
 * Template for various non-category archive pages (tag, term, date, etc.)
 *
 * @package Largo
 * @since 0.1
 * @filter largo_partial_by_post_type
 */
get_header();
$queried_object = get_queried_object();
?>

<div class="clearfix">

	<?php
		if ( have_posts() || largo_have_featured_posts() ) {

			// queue up the first post so we know what type of archive page we're dealing with
			the_post();

			/*
			 * Display some different stuff in the header
			 * depending on what type of archive page we're looking at
			 */

			if ( is_author() ) {
				$rss_link = get_author_feed_link( get_the_author_meta( 'ID' ) );
			} elseif ( is_tag() ) {
				$title = single_tag_title( '', false );
				$description = tag_description();
				$rss_link =  get_tag_feed_link( get_queried_object_id() );
			} elseif ( is_tax() ) {
				$title = single_term_title( '', false );
				$description = term_description();

				// rss links for custom taxonomies are a little tricky
				$term_id = intval( $queried_object->term_id );
				$tax = $queried_object->taxonomy;
				$rss_link = get_term_feed_link( $term_id, $tax );
			} elseif ( is_date() ) {
				$description = __( 'Select a different month:', 'largo' );
				if ( is_month() ) {
					$title = sprintf( __( 'Monthly Archives: <span>%s</span>', 'largo' ), get_the_date( 'F Y' ) );
				} elseif ( is_year() ) {
					$title = sprintf( __( 'Yearly Archives: <span>%s</span>', 'largo' ), get_the_date( 'Y' ) );
				} else {
					$title = _e( 'Blog Archives', 'largo' );
				}
			} elseif ( is_post_type_archive( 'rounduplink' ) ) {
				/**
				 * Make the title of the rounduplink archive filterable
				 *
				 * @link https://github.com/INN/Largo/issues/1123
				 * @param string $title The title of the archive page
				 * @since 0.5.4
				 */
				$title = apply_filters( 'largo_archive_rounduplink_title', __( 'Saved Links' , 'largo' ) );
				$rss_link = '/rounduplink/feed';
			}
		?>

		<header class="archive-background clearfix">
			<?php
				if ( isset( $rss_link ) ) {
					printf( '<a class="rss-link rss-subscribe-link" href="%1$s">%2$s <i class="icon-rss"></i></a>', $rss_link, __( 'Subscribe', 'largo' ) );
				}

				$post_id = largo_get_term_meta_post( $queried_object->taxonomy, $queried_object->term_id );
				largo_hero($post_id);

				if ( isset( $title ) ) {
					echo '<h1 class="page-title">' . $title . '</h1>';
				}

				if ( isset( $description ) ) {
					echo '<div class="archive-description">' . $description . '</div>';
				}

				if ( is_date() ) {
			?>
					<nav class="archive-dropdown">
						<select name="archive-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'><option value=""><?php _e('Select Month', 'largo'); ?></option>
							<?php wp_get_archives( array('type' => 'monthly', 'format' => 'option' ) ); ?>
						</select>
					</nav>
			<?php
				} elseif ( is_author() ) {
					the_widget( 'largo_author_widget', array( 'title' => '' ) );
				}
			?>
		</header>

		<div class="row-fluid clearfix">
			<div class="stories span8" role="main" id="content">
			<?php
				// and finally wind the posts back so we can go through the loop as usual
				rewind_posts();

				while ( have_posts() ) : the_post();
					// Do not add posts in the main river to $shown_ids; doing so will mess up Load More Posts
					// See https://github.com/INN/Largo/pull/1150
					// See https://github.com/INN/Largo/blob/master/inc/ajax-functions.php#L49-L52
					// $shown_ids[] = get_the_ID();
					$post_type = get_post_type();
					$partial = largo_get_partial_by_post_type('archive', $post_type, 'archive');
					get_template_part( 'partials/content', $partial );
				endwhile;

				largo_content_nav( 'nav-below' );
			?>
			</div><!-- end content -->
			<?php get_sidebar(); ?>
		</div>
		<?php } else {
			get_template_part( 'partials/content', 'not-found' );
		}
	?>
</div>

<?php get_footer();

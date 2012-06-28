<?php
/**
 * The Sidebar containing the single widget area.
 */
?>
<div class="widget-area" role="complementary">
	<nav id="utility-nav">
			<div id="header-search">
				<?php get_search_form(); ?>
			</div>

            <ul id="follow-us">
                <?php if ( $facebook = get_option( 'facebook_link' ) ) : ?>
                <li class="icon-fb-header"><a href="<?php echo esc_url( $facebook ); ?>" title="Facebook">Facebook</a></li>
                <?php endif; ?>
                <?php if ( $twitter = get_option( 'twitter_link' ) ) : ?>
                <li class="icon-twitter-header"><a href="<?php echo esc_url( $twitter ); ?>" title="Twitter">Twitter</a></li>
                <?php endif; unset( $facebook, $twitter ); ?>
            </ul> <!-- /#follow-us -->

    </nav> <!-- /utility-nav -->
	<?php
		if ( ! dynamic_sidebar( 'sidebar-single' ) )
			the_widget( 'Argo_hosts_Widget', array( 'title' => 'Blog Hosts' ) );
	?>
</div><!-- #main .widget-area -->
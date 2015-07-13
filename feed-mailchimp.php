<?php
/**
 * Template Name: MailChimp Feed
 *
 * A feed with thumbnail images for MailChimp import.
 * Feed address to use for MailChimp import will be http://myurl.com/?feed=mailchimp.
 *
 * @package Largo
 * @since 0.2
 */

/**
 * @ignore
 */
$numposts = 20;
$posts = query_posts( array(
  'showposts' => $numposts
) );
$lastpost = $numposts - 1;

/**
 * A template tag for printing the date in a format suitable for an RSS feed
 *
 * @param mixed $timestamp (optional) the unix timestamp for which to print the formatted date
 */
function rss_date( $timestamp = null ) {
  $timestamp = ($timestamp==null) ? time() : $timestamp;
  echo date(DATE_RSS, $timestamp);
}

header("Content-Type: application/rss+xml; charset=UTF-8");
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:media="http://search.yahoo.com/mrss/" xmlns:dc="http://purl.org/dc/elements/1.1/">
<channel>
  <title><?php bloginfo_rss('name'); ?></title>
  <link><?php bloginfo_rss('url'); ?></link>
  <description><?php bloginfo_rss('description'); ?></description>
  <language><?php bloginfo('language'); ?></language>
  <pubDate><?php rss_date( strtotime($ps[$lastpost]->post_date_gmt) ); ?></pubDate>
  <lastBuildDate><?php rss_date( strtotime($ps[$lastpost]->post_date_gmt) ); ?></lastBuildDate>
  <managingEditor><?php bloginfo_rss('admin_email'); ?></managingEditor>

<?php foreach ($posts as $post) { ?>
  <item>
    <title><?php the_title_rss(); ?></title>
    <link><?php the_permalink(); ?></link>
    <description><?php echo '<![CDATA[' . largo_excerpt( $post, 5, false, '', false ) . ']]>';  ?></description>
    <pubDate><?php rss_date( strtotime( $post->post_date_gmt ) ); ?></pubDate>
    <guid><?php the_permalink(); ?></guid>
    <dc:creator><?php $curuser = get_user_by( 'id', $post->post_author ); echo $curuser->first_name . ' ' . $curuser->last_name; ?></dc:creator>
	<?php if( get_the_post_thumbnail( $post->ID ) ): $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) ); ?>
    <media:content url="<?php echo esc_url( $image[0] ); ?>" medium="image" />
	<?php endif; ?>
  </item>
<?php } // foreach ?>
</channel>
</rss>

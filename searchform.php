<?php
/**
 * The template for displaying search forms
 */

 if ( of_get_option('use_gcs') && of_get_option('gcs_id') ) :
 ?>

<div class="gcs_container">
	<script>
	  (function() {
	    var cx = '<?php echo of_get_option('gcs_id'); ?>';
	    var gcse = document.createElement('script');
	    gcse.type = 'text/javascript';
	    gcse.async = true;
	    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
	        '//www.google.com/cse/cse.js?cx=' + cx;
	    var s = document.getElementsByTagName('script')[0];
	    s.parentNode.insertBefore(gcse, s);
	  })();
	</script>
	<gcse:search></gcse:search>
</div>

<?php else: ?>

	<form class="form-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<div>
			<input type="text" placeholder="<?php _e('Search', 'largo'); ?>" class="searchbox search-query" value="" name="s" />
			<input type="submit" value="<?php _e('Go', 'largo'); ?>" name="search submit" class="search-submit btn">
		</div>
	</form>

<?php endif;

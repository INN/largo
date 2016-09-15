<?php
if ( is_active_sidebar( 'header-ads' ) ) { ?>
<div class="header-ad-zone max-wide">
	<div id="header-ad-zone-container">
		<?php dynamic_sidebar( 'header-ads' ); ?>
	</div>
</div>
<?php } ?>

<?php

// category pages show a list of related terms
if ( defined( 'SHOW_CATEGORY_RELATED_TOPICS' ) && SHOW_CATEGORY_RELATED_TOPICS ) {
	if ( is_category() && largo_get_related_topics_for_category( get_queried_object() ) != '' ) { ?>
		<div class="related-topics">
			<?php echo largo_get_related_topics_for_category( get_queried_object() ); ?>
		</div>
	<?php
	}
}

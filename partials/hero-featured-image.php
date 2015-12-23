<div class="<?php echo $classes; ?>">
	<?php echo get_the_post_thumbnail($the_post->ID, 'full'); ?>
	<?php if (!empty($thumb_meta)) {
		if (!empty($thumb_meta['credit'])) {
			if (!empty($thumb_meta['credit_url'])) { ?>
				<p class="wp-media-credit"><a href="<?php echo $thumb_meta['credit_url']; ?>"><?php echo $thumb_meta['credit'];
				if (!empty($thumb_meta['organization'])) { ?>/<?php echo $thumb_meta['organization']; } ?></a></p>
			<?php } else { ?>
			<p class="wp-media-credit"><?php echo $thumb_meta['credit'];
				if (!empty($thumb_meta['organization'])) { ?>/<?php echo $thumb_meta['organization']; } ?></p>
			<?php }
		}

		if (!empty($thumb_meta['caption'])) { ?>
			<p class="wp-caption-text"><?php echo $thumb_meta['caption']; ?></p>
		<?php }
	} ?>
</div>

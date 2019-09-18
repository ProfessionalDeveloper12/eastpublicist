<?php
if( has_post_thumbnail() && ( get_post_format() != "audio" && get_post_format() != "video" && get_post_format() != "gallery" ) ) {
	?>
	<div class="post-cover">
		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<?php the_post_thumbnail(); ?>
		</a>
	</div>
	<?php
} ?>
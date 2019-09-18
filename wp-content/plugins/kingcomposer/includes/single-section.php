<!DOCTYPE HTML>
<html <?php language_attributes(); ?>>
<head>
<?php
	wp_head();
?>
</head>
<body <?php body_class(); ?>>
	<div id="kc-section-display">
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<div class="entry-content">
				<?php the_content(); ?>
			</div>
			
		</article>
		<?php	
			
		endwhile;
		?>
	</div>

	<?php wp_footer(); ?>

</body>
</html>
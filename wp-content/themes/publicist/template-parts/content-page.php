<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage Publicist
 * @since Publicist 1.0
 */
?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<h2 class="no-padding no-margin text-hide"><?php the_title(); ?></h2>

	<?php the_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'publicist' ),
				'after'  => '</div>',
			)
		); ?>
	</div>
	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer container">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'publicist' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			); ?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>

</div><!-- #post-## -->
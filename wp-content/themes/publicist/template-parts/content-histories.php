<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Publicist
 * @since Publicist 1.0
 */

$pid = publicist_get_the_ID(); 
?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	the_title('<h3 class="post-title">','</h3>');
	
	if( has_post_thumbnail() ) {
		?>
		<div class="post-cover">
			<?php
			if( get_post_meta( $pid,'publicist_cf_sidebar_owlayout',true) == "no_sidebar" ) {
				the_post_thumbnail('publicist_1110_568');
				
				$thumb = 'publicist_1110_568';
			}
			else {
				the_post_thumbnail('publicist_730_374');
			} ?>
		</div>
		<?php
	}?>
	<div class="post-content">
		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'publicist' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);

		wp_link_pages(
			array(
				'before' => '<div class="page-links"><span>' . esc_html__( 'Pages:', 'publicist' ) . ' </span>',
				'after'  => '</div>',
			)
		); ?>
	</div>
</div>
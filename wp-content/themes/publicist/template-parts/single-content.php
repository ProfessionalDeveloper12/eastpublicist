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

?>
<div id="post-<?php the_ID(); ?>" <?php post_class('post-box'); ?>>
	<?php
	
	the_title('<h3 class="post-title">','</h3>');
	
	get_template_part("template-parts/single","post_thumbnail");

	get_template_part("template-parts/single","audio");

	get_template_part("template-parts/single","video");

	get_template_part("template-parts/single","gallery");
	
	$postauthor = get_post_meta( get_the_ID(), 'author_custom', true );
	if( $postauthor['text'] != "" ) {
		$postauthorn = $postauthor['text'];
	}
	else {
		$postauthorn = get_the_author();
	}
	
	if( publicist_options("opt_single_meta") != "0" ) {
		?>
		<div class="post-header">	
			<div class="post-meta">
				<div class="post-user"><i class="fa fa-user"></i><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php echo esc_attr( $postauthorn ); ?>"><?php echo esc_attr( $postauthorn ); ?></a></div>
				<!--div class="post-date"><i class="far fa-calendar-alt"></i><span><?php echo esc_attr( get_the_date() ); ?></span></div>
				<div class="post-comment">
					<i class="far fa-comment"></i><a title="<?php the_title(); ?>" href="<?php comments_link(); ?>">
						<?php
						comments_number(
							esc_html__('Comment 0',"publicist"), 
							esc_html__('Comment 1',"publicist"),
							esc_html__('Comments %',"publicist")
						); ?>
					</a>
				</div-->
			</div>
		</div>
		<?php
	} ?>
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
		);

		if( has_tag() && publicist_options("opt_post_tag") != "0" ) {
			?>
			<div class="post-tags">
				<span><?php esc_html_e('Tags:',"publicist"); ?></span>
				<?php echo get_the_tag_list('', ', '); ?>
			</div>
			<?php
		} 
		if( has_category() && publicist_options("opt_post_categories") != "0" ) {
			?>
			<div class="post-category">
				<span><?php esc_html_e('Categories:',"publicist"); ?></span>
				<?php the_category(', '); ?>
			</div>
			<?php
		} ?>
	</div>
</div>
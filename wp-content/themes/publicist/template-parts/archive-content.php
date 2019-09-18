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
$excerpt_cnt = "";
if( publicist_options("opt_excerpt_length") != "" ) {
	$excerpt_cnt = publicist_options("opt_excerpt_length");
}

$readmore = esc_html__('Read More',"publicist");
if( publicist_options("opt_readmore_text") != "" ) {
	$readmore = publicist_options("opt_readmore_text");
} ?>
<div id="post-<?php the_ID(); ?>" <?php post_class( 'post-box' ); ?>>

	<?php
	
	the_title( sprintf( '<h3 class="post-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
	
	if ( is_sticky() && is_home() && ! is_paged() ) {
		printf( '<span class="sticky-post">%s</span>', esc_html__( 'Featured','publicist' ) );
	}

	get_template_part("template-parts/archive","post_thumbnail");
	
	get_template_part("template-parts/archive","audio");
	
	get_template_part("template-parts/archive","video");
	
	get_template_part("template-parts/archive","gallery");
	
	$postauthor = get_post_meta( get_the_ID(), 'author_custom', true );
	if( $postauthor['text'] != "" ) {
		$postauthorn = $postauthor['text'];
	}
	else {
		$postauthorn = get_the_author();
	}
	
	if( publicist_options("opt_archive_meta") != "0" ) {
		?>
		<div class="post-header">
			<div class="post-meta">
				<div class="post-user"><i class="fa fa-user"></i><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php echo esc_attr( $postauthorn ); ?>"><?php echo esc_attr( $postauthorn ); ?></a></div>
				<!--div class="post-date"><i class="far fa-calendar-alt"></i><a title="<?php echo esc_attr( get_the_date() ); ?>" href="<?php the_permalink(); ?>"><?php echo esc_attr( get_the_date() ); ?></a></div>
				<div class="post-comment">
					<i class="far fa-comment"></i><a title="<?php the_title(); ?>" href="<?php comments_link(); ?>">
						<?php
						comments_number(
							esc_html__('Comment 0',"publicist"), 
							esc_html__('Comment 1',"publicist"),
							esc_html__('Comments %',"publicist")
						); ?></a>
				</div-->
			</div>
		</div>
		<?php
	} ?>
	<div class="post-content">
		<?php
		if( function_exists('publicist_excerpt') ) {
			?><p><?php publicist_excerpt($excerpt_cnt); ?></p><?php
		} ?>
		<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr($readmore); ?>" class="read-more"><?php echo esc_attr($readmore); ?></a>
	</div>
</div>
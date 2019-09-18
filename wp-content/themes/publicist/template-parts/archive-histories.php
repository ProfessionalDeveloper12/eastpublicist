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
	
	the_post_thumbnail();
	?>
	<div class="post-content">
		<?php
		if( function_exists('publicist_excerpt') ) {
			?><p><?php publicist_excerpt($excerpt_cnt); ?></p><?php
		} ?>
		<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr($readmore); ?>" class="read-more"><?php echo esc_attr($readmore); ?></a>
	</div>
</div>
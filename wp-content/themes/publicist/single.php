<?php
/**
* The Template for displaying all single posts
*
* @package WordPress
* @subpackage Publicist
* @since Publicist 1.0
*/
?>

<?php get_template_part("template-parts/blog","before"); ?>

<?php
// Start the loop.
while ( have_posts() ) : the_post();

	// Include the page content template.
	get_template_part( 'template-parts/single','content' );

	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;

// End the loop.
endwhile;
?>

<?php get_template_part("template-parts/blog","after"); ?>
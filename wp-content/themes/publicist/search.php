<?php
/**
* The template for displaying search results pages
*
* @package WordPress
* @subpackage Publicist
* @since Publicist 1.0
*/

get_template_part("template-parts/blog","before");

if ( have_posts() ) :

	// Start the loop.
	while ( have_posts() ) : the_post();

		if( 'publicist_histories' == get_post_type() ) {
			get_template_part( 'template-parts/archive-histories' );
		}
		else {
		/*
		 * Run the loop for the search to output the results.
		 * If you want to overload this in a child theme then include a file
		 * called content.php and that will be used instead.
		 */
			get_template_part( 'template-parts/archive', 'content' );
		}

	// End the loop.
	endwhile;

	// Previous/next page navigation.				
	the_posts_pagination( array(
		'prev_text'          => wp_kses( __( 'Previous', "publicist" ), array( 'i' => array( 'class' => array() ) ) ),
		'next_text'          => wp_kses( __( 'Next', "publicist" ), array( 'i' => array( 'class' => array() ) ) ),
		'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', "publicist" ) . ' </span>',
	) );

// If no content, include the "No posts found" template.
else :

	get_template_part( 'template-parts/content', 'none' );

endif;

get_template_part("template-parts/blog","after");
?>
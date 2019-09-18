<?php
/**
* The template for displaying 404 pages (not found)
*
* @package WordPress
* @subpackage Publicist
* @since Publicist 1.0
*/
get_header(); 

$error_title = esc_html__('404',"publicist");
if( publicist_options("opt_error_title") != "" ) {
	$error_title = publicist_options("opt_error_title");
}

$error_subtitle = esc_html__('The page You are searching was not found',"publicist");
if( publicist_options("opt_error_subtitle") != "" ) {
	$error_subtitle = publicist_options("opt_error_subtitle");
} ?>
<main id="main" class="site-main page_spacing_top40 page_spacing_bottom40">
	<!-- 404 page -->	
	<div class="error-section text-center">
		<div class="container">
			<div class="row justify-content-md-center">
				<div class="col-md-10">
					<div class="error-block">
						<h2><?php echo esc_attr($error_title); ?></h2>
						<h3><?php echo esc_attr($error_subtitle); ?></h3>
					</div>
				</div>
			</div>
		</div>
	</div><!-- 404 page /- -->
</main><!-- .site-main -->
<?php get_footer(); ?>
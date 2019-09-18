<?php
$prefix = "publicist_cf_";

/* Page Metabox */
$page_banner = "";
$page_banner  = get_post_meta( publicist_get_the_ID(), $prefix . 'page_header_img', true );

/* Page Banner */
$header_image = "";
if( $page_banner != "" ) {
	$header_image = $page_banner;
}
elseif( publicist_options("opt_pageheader_img", "url") != "" ) {
	$header_image = publicist_options("opt_pageheader_img", "url");
}
else {
	$header_image = esc_url(PUBLICIST_IMGURI).'/background-header.jpg';
}


$page_banner_text = "";
$page_banner_text  = get_post_meta( publicist_get_the_ID(), $prefix . 'page_header_title', true );

?>

<!-- Page Banner -->
<div class="container-fluid page-banner pl-0 pr-0" style="background-image:url(<?php echo esc_url($header_image); ?>);" >	
	<div class="container">
		<h1><?php
			if( $page_banner_text != "" ) {
				?><span><?php echo esc_attr($page_banner_text); ?></span>
				<?php
			} ?>
			<span>
			<?php
			if( is_home() ) {
				esc_html_e( 'Blog', "publicist" );
			}
			elseif( is_404() ) {
				esc_html_e( 'Page Not Found', "publicist" );
			}
			elseif( is_search() ) {
				printf( esc_html__( 'Search Results for: %s', "publicist" ), get_search_query() );
			}
			elseif( is_archive() ) {
				the_archive_title();
			}				
			elseif( is_page() ) {
				the_title();
			}/*
			else {
				the_title();
			}*/ ?></span></h1>
	</div>
</div><!-- Page Banner /- -->
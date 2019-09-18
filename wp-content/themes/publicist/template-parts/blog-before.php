<?php
get_header();

$bodylayout = $bodylayout_css = "";
$sidebarlayout = $sidebarlayout_css = "";
$dbodylayout_css = $dbodylayout = $dsidebarlayout = $dsidebarlayout_css = "";
$lrmargin_css = $page_css_top = $page_css_bottom = "";
$pid = "";

$pid = publicist_get_the_ID();
if( publicist_options("layout_body") != "" ) {

	$dbodylayout = publicist_options("layout_body");

	if( $dbodylayout == "fixed" ) {
		$dbodylayout_css = "container";
	}
	elseif( $dbodylayout == "fluid" ) {
		$dbodylayout_css = "container-fluid p-0";
	}
	else { /* Do Nothing.. */ }
}
else {
	$dbodylayout_css = "container";	
}

if( publicist_options("layout_sidebar") != "" ) {

	$dsidebarlayout = publicist_options("layout_sidebar");

	if( $dsidebarlayout == "right_sidebar" ) {
		$dsidebarlayout_css = " content-left col-lg-8 col-md-8";
	}
	elseif( $dsidebarlayout == "left_sidebar" ) {
		$dsidebarlayout_css = " content-right col-lg-98 col-md-8";
	}
	elseif( $dsidebarlayout == "no_sidebar" ) {
		$dsidebarlayout_css = " no-sidebar col-12 p-0";
	}
	else { /* Do Nothing.. */ }
}
else {
	$dsidebarlayout_css = " content-left col-lg-8 col-md-8";
}

if( $pid != "" && !is_search() && !is_archive() ) {

	/* Page Layout */
	if( get_post_meta( $pid, 'publicist_cf_page_owlayout', true ) != "" || get_post_meta( $pid, 'publicist_cf_page_owlayout', true ) != "none" ) {

		$bodylayout = get_post_meta( $pid, 'publicist_cf_page_owlayout', true );
		
		if( $bodylayout == "fixed" ) {
			$bodylayout_css = "container";
		}
		elseif( $bodylayout == "fluid" ) {
			$bodylayout_css = "container-fluid p-0";
		}
		else {
			$bodylayout_css = $dbodylayout_css;
		}
	}	

	/* Sidebar Layout */
	if( get_post_meta( $pid, 'publicist_cf_sidebar_owlayout', true ) != "" || get_post_meta( $pid, 'publicist_cf_sidebar_owlayout', true ) != 'none' ) {

		$sidebarlayout = get_post_meta( $pid, 'publicist_cf_sidebar_owlayout', true );
		
		if( $sidebarlayout == "right_sidebar" ) {
			$sidebarlayout_css = " content-left col-lg-8 col-md-8";
		}
		elseif( $sidebarlayout == "left_sidebar" ) {
			$sidebarlayout_css = " content-right col-lg-8 col-md-8";
		}
		elseif( $sidebarlayout == "no_sidebar" ) {
			$sidebarlayout_css = " no-sidebar col-12 p-0";
		}
		else {
			$sidebarlayout_css = $dsidebarlayout_css;
		}
	}
	
	/* Content Area Top Space */
	$spacetp = get_post_meta( $pid, 'publicist_cf_content_padding_top',true );
	if( get_post_meta( $pid, 'publicist_cf_content_padding_top', true ) != "" ) {
		$page_css_top = " page_spacing_top".$spacetp."";
	}
	else {
		$page_css_top = " page_spacing_top60";
	}
	
	/* Content Area Bottom Space */
	$spacebtm = get_post_meta( $pid, 'publicist_cf_content_padding_bottom',true );
	if( get_post_meta( $pid, 'publicist_cf_content_padding_bottom', true ) != "" ) {
		$page_css_bottom = " page_spacing_bottom".$spacebtm."";
	}
	else {
		$page_css_bottom = " page_spacing_bottom60";
	}
	
	/* Row Margin */
	if( get_post_meta( $pid, 'publicist_cf_content_margin', true ) == "off" ) {
		$lrmargin_css = "row m-0";
	}
	else {
		$lrmargin_css = "row res-15";
	}
}
else {
	$bodylayout_css = $dbodylayout_css;
	$sidebarlayout_css = $dsidebarlayout_css;
	$page_css_top = " page_spacing_top60";
	$page_css_bottom = " page_spacing_bottom60";
	$lrmargin_css = "row res-15";
} ?>
<main id="main" class="site-main<?php echo esc_attr( $page_css_top . $page_css_bottom ); ?>">

	<div class="<?php echo esc_attr( $bodylayout_css  ); ?>">

		<div class="<?php echo esc_attr($lrmargin_css); ?>">

			<div class="content-area<?php echo esc_attr( $sidebarlayout_css); ?>">
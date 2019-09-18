<?php
$sidebarlayout = "";
$sidebarlayout_css = "";
$dsidebarlayout_css = "";
$dsidebarlayout = "";
$widgetarea = "";
$pid = "";

$pid = publicist_get_the_ID();

$dwidgetarea = "sidebar-1";

$sidebarlayout_css = "sidebar-right";

if( publicist_options("layout_sidebar") != "" ) {

	$dsidebarlayout = publicist_options("layout_sidebar");

	if( $dsidebarlayout == "right_sidebar" ) {
		$dsidebarlayout_css = "sidebar-right";
	}
	elseif( $dsidebarlayout == "left_sidebar" ) {
		$dsidebarlayout_css = "sidebar-left";
	}
	elseif( $dsidebarlayout == "no_sidebar" ) {
		$dsidebarlayout_css = "no-sidebar";
	}
	else { /* Do Nothing.. */ }
}
else {
	$dsidebarlayout = "right_sidebar";
}

if( $pid != "" && !is_search() && !is_archive() ) {

	/* Sidebar Layout */
	if( get_post_meta( $pid, 'publicist_cf_sidebar_owlayout', true ) != "" ) {

		$sidebarlayout = get_post_meta( $pid, 'publicist_cf_sidebar_owlayout', true );
		
		if( $sidebarlayout == "right_sidebar" ) {
			$sidebarlayout_css = "sidebar-right";
		}
		elseif( $sidebarlayout == "left_sidebar" ) {
			$sidebarlayout_css = "sidebar-left";
		}
		elseif( $sidebarlayout == "no_sidebar" ) {
			$sidebarlayout_css = "no-sidebar";
		}
		else {
			$sidebarlayout_css = $dsidebarlayout_css;
			$sidebarlayout = $dsidebarlayout;
		}
	}

	/* Widget Area */
	if( get_post_meta( $pid, 'publicist_cf_widget_area', true ) != "" ) {
		$widgetarea = get_post_meta( $pid, 'publicist_cf_widget_area', true );
	}
	else {
		$widgetarea = $dwidgetarea;
	}
}
else {
	$widgetarea = $dwidgetarea;
	$sidebarlayout_css = $dsidebarlayout_css;
	$sidebarlayout_css = "sidebar-right";
} 

if( $sidebarlayout != "no_sidebar" ) {
	?>
	<div class="widget-area col-lg-4 col-md-4 <?php echo esc_attr( $sidebarlayout_css . " " . $widgetarea ); ?>">
		<?php
		if( is_active_sidebar( $widgetarea ) ) {
			dynamic_sidebar( $widgetarea );
		}
		else {
			dynamic_sidebar("sidebar-1");
		} ?>
	</div><!-- End Sidebar -->
	<?php
} ?>
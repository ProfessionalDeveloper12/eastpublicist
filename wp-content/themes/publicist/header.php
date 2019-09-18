<?php
/**
 * The Header for our theme
 *
 *
 * @package WordPress
 * @subpackage Publicist
 * @since Publicist 1.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> >
	<?php
	$header_css = "";
	if( get_post_meta( get_the_ID(), 'publicist_cf_header_position',true ) == 'normal' ) {
		$header_css = " header_normal";
	}
	else {
		$header_css = " header_abs";
	}
	
	$sticky_class = "";
	if( publicist_options("opt_sticky_menu") != "0" ) {
		$sticky_class = " header-fix";
	} ?>
	<!-- Header Section -->
	<div class="header_s<?php echo esc_attr($header_css . $sticky_class); ?>">
		<?php
		if( publicist_options("opt_top_header") != "0" ) {
			?>
			<!-- SidePanel -->
			<div id="slidepanel-1" class="slidepanel">
				<!-- Top Header -->
				<div class="top-header">
					<!-- Container -->
					<div class="container">
						<div class="row align-items-center">
							<?php
							$social_icons = $icon_onoff = "";
							$social_icons = publicist_options("opt_social_icons");
							$icon_onoff = publicist_options("opt_icon_onoff");
							if( $icon_onoff != "0" && $social_icons != "" && $social_icons[0]['textOne'] != ""  ) {
								?>
								<div class="col-lg-3 col-md-6">
									<ul class="top-social">
										<?php
										foreach( $social_icons as $single_item ) {
											if( $single_item["textOne"] != "" ) {
												?>
												<li><a href="<?php echo esc_url( $single_item["url"] ); ?>" title="<?php echo esc_attr( $single_item["title"] ); ?>"><i class="<?php echo esc_attr( $single_item["textOne"] ); ?>"></i></a></li>
												<?php
											}
										} ?>
									</ul>
								</div>
								<?php
							} ?>
							<div class="col-lg-6 col-12 logo-block">
								<?php get_template_part("template-parts/sitelogo"); ?>
							</div>
							<?php
							if( publicist_options("opt_hdr_search") != "0" ) {
								?>
								<div class="col-lg-3 col-md-6 search-box">
									<?php get_search_form(); ?>
								</div>
								<?php
							} ?>
						</div>
					</div><!-- Container /- -->
				</div><!-- Top Header /- -->
			</div><!-- SidePanel /- -->
			<?php
		} ?>
		<!-- Menu Block -->
		<div class="menu-block">
			<nav class="navbar ownavigation navbar-expand-lg">
				<!-- Container -->
				<div class="container">
					<?php get_template_part("template-parts/sitelogo-responsive"); ?>
					<!--a class="image-logo navbar-brand" href="index.php"><img src="assets/images/logo.png" alt="Logo" /></a-->
					<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="true" aria-label="Toggle navigation">
						<i class="fa fa-bars"></i>
					</button>
					<div class="collapse navbar-collapse" id="navbar">
						<?php get_template_part("template-parts/navbarmenu"); ?>
					</div>
					<?php
					if( publicist_options("opt_top_header") != "0" ) {
						?>
						<div id="loginpanel-1" class="desktop-hide">
							<div class="right toggle" id="toggle-1">
								<a id="slideit-1" class="slideit" href="#slidepanel"><i class="fo-icons fa fa-inbox"></i></a>
								<a id="closeit-1" class="closeit" href="#slidepanel"><i class="fo-icons fa fa-times"></i></a>
							</div>
						</div>
						<?php
					} ?>
				</div><!-- Container /- -->
			</nav>
		</div><!-- Menu Block /- -->
	</div><!-- Header Section /- -->
	
	<div class="main-container">
	
	<?php
	/* Page Header */
	$page_title = "";
	$page_title = get_post_meta( publicist_get_the_ID(), 'publicist_cf_page_title', true );

	if( $page_title != "disable" || is_search() ) {
		get_template_part("template-parts/pageheader" );
	} ?>
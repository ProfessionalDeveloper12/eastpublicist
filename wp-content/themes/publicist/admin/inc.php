<?php
/* TGM Plugin Activation Notice */
require get_template_directory() .'/admin/tgm/tgm.php';

if ( !function_exists('publicist_admin_enqueue') ) :

	function publicist_admin_enqueue() {

		wp_enqueue_media();

		wp_enqueue_script( 'publicist-admin-functions', get_template_directory_uri() . '/admin/js/functions.js', array( 'jquery' ) );	
		wp_enqueue_style( 'publicist-admin', get_template_directory_uri() . '/admin/css/style.css' );
		wp_enqueue_style( 'elegant-icons', get_template_directory_uri() . '/admin/css/eleganticons.css' );
		wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/admin/css/font-awesome.min.css' );
	}
	add_action( 'admin_enqueue_scripts', 'publicist_admin_enqueue' );
endif;

if ( !function_exists('publicist_adminpage') ) :
	function publicist_adminpage() {

		$admin_logo = $admin_bgcolor = $admin_bgimg = $admin_color = "";

		if( publicist_options("opt_adminlogo", "url") != "" ) { $admin_logo .= ".login h1 a { background-size: 150px; width: auto; background-image: url(" . esc_attr( publicist_options("opt_adminlogo", "url") ) . "); }"; }
		if( publicist_options("opt_adminbg_color") != "" ) { $admin_bgcolor .= "body.login-action-login { background-color: " . esc_attr( publicist_options("opt_adminbg_color") ) . "; } "; }
		if( publicist_options("opt_adminbg_img", "url") != "" ) { $admin_bgimg .= "body.login-action-login { background-repeat: no-repeat; background-size: cover; background-image: url(" . esc_attr( publicist_options("opt_adminbg_img", "url") ) . "); } "; }
		if( publicist_options("opt_admincolor") != "" ) { $admin_color .= ".login #backtoblog a, .login #nav a { color: " . esc_attr( publicist_options("opt_admincolor") ) . "; }"; }

		echo '<style  type="text/css">'. $admin_logo . $admin_bgcolor . $admin_bgimg . $admin_color . '</style>';
	}  
	add_action('login_head',  'publicist_adminpage');
endif;

if ( class_exists( 'ReduxFramework' ) ) {
	require_once( trailingslashit( get_template_directory() ) . 'admin/theme-options/extension-loader.php' );
	require_once( trailingslashit( get_template_directory() ) . 'admin/theme-options/theme-options.php' );
}
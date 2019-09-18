<?php
/* Define Constants */
define( 'PUBLICIST_IMGURI', get_template_directory_uri() . '/assets/images' );

/**
 * Register three widget areas.
 *
 * @since Publicist 1.0
 */
if ( ! function_exists( 'publicist_widgets_init' ) ) {
	function publicist_widgets_init() {
		register_sidebar( array(
			'name'          => esc_html__( 'Blog Right Sidebar (Default for Blog)', "publicist" ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Appears in the Content section of the site.', "publicist" ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		));
		register_sidebar( array(
			'name'          => esc_html__( 'Blog Left Sidebar', "publicist" ),
			'id'            => 'sidebar-2',
			'description'   => esc_html__( 'Appears in the Content section of the site.', "publicist" ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		));
	}
	
	add_action( 'widgets_init', 'publicist_widgets_init' );
}

if( ! function_exists('publicist_copyright') ) {
	function publicist_copyright() {
		echo do_shortcode( wpautop( wp_kses( publicist_options("opt_footer_copyright"), wp_kses_allowed_html() ) ) );
	}
}

if( ! function_exists('publicist_footer_newlatterform') ) {
	function publicist_footer_newlatterform() {
		echo do_shortcode('[mc4wp_form id='.publicist_options("opt_newsletter_id").']');
	}
}

if( ! function_exists("publicist_wp_query") ) {

	function publicist_wp_query( array $qry_args = array() ) {

		global $wp_query;

		wp_reset_query();

		$paged = get_query_var('paged') ? get_query_var('paged') : 1;

		$defaults = array(
			'paged'	=> $paged,
			'posts_per_page' => 10,
		);

		$qry_args += $defaults;

		$wp_query = new WP_Query( $qry_args );
	}
}

require_once( trailingslashit( get_template_directory() ) . 'include/nav_walker.php' );
require_once( trailingslashit( get_template_directory() ) . 'include/cmb/inc.php' );
require_once( trailingslashit( get_template_directory() ) . 'include/cpt/inc.php' );
require_once( trailingslashit( get_template_directory() ) . 'include/shortcodes/inc.php' );
require_once( trailingslashit( get_template_directory() ) . 'include/widgets/inc.php' );
<?php
if( ! function_exists("publicist_get_sidebars") ) {

	function publicist_get_sidebars() {

		global $wp_registered_sidebars;

		$sidebar_options = array();

		$dwidgetarea = array( "" => "Select an Option" );

		foreach ( $wp_registered_sidebars as $sidebar ) {
			$sidebar_options[$sidebar['id']] = $sidebar['name'];
		}
		return array_merge( $dwidgetarea, $sidebar_options );
	}
}

add_action( 'cmb2_init', 'publicist_metabox' );
function publicist_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'publicist_cf_';

	/* ## Page/Post Options ---------------------- */
	
	/* - Page Description */
	$cmb_pagelayout = new_cmb2_box( array(
		'id'            => $prefix . 'metabox_playout',
		'title'         => esc_html__( 'Page Layout', "publicist" ),
		'object_types'  => array( 'page'), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
	) );
	
	$cmb_pagelayout->add_field( array(
		'name'             => 'Page Layout',
		'desc'             => 'Select an option',
		'id'               => $prefix . 'page_owlayout',
		'type'             => 'radio',
		'default'          => 'none',
		'options'          => array(
			'none' =>  '<img title="Default" src="'. PUBLICIST_IMGURI .'/layout/default.png" />',
			'fixed' =>  '<img title="Fixed" src="'. PUBLICIST_IMGURI .'/layout/boxed.png" />',
			'fluid' =>  '<img title="Fluid" src="'. PUBLICIST_IMGURI .'/layout/full.png" />'
		),
	) );

	/* - Page Description */
	$cmb_page = new_cmb2_box( array(
		'id'            => $prefix . 'metabox_page',
		'title'         => esc_html__( 'Page Options', "publicist" ),
		'object_types'  => array( 'page', 'post', 'publicist_histories', 'event', 'location' ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
	) );

	$cmb_page->add_field( array(
		'name'             => 'Content Area Top Padding',
		'desc'             => 'If your content section need to have just after header area without space, please select an option Off',
		'id'               => $prefix . 'content_padding_top',
		'type'             => 'select',
		'default'          => '60',
		'options'          => array(
			'0' => esc_html__( '0px', "publicist" ),
			'10'   => esc_html__( '10px', "publicist" ),
			'20'   => esc_html__( '20px', "publicist" ),
			'30'   => esc_html__( '30px', "publicist" ),
			'40'   => esc_html__( '40px', "publicist" ),
			'50'   => esc_html__( '50px', "publicist" ),
			'60'   => esc_html__( '60px', "publicist" ),
			'70'   => esc_html__( '70px', "publicist" ),
			'80'   => esc_html__( '80px', "publicist" ),
			'90'   => esc_html__( '90px', "publicist" ),
			'100'   => esc_html__( '100px', "publicist" ),
		),
	) );
	
	$cmb_page->add_field( array(
		'name'             => 'Content Area Bottom Padding',
		'desc'             => 'If your content section need to have just before footer Area without space, please select an option Off',
		'id'               => $prefix . 'content_padding_bottom',
		'type'             => 'select',
		'default'          => '60',
		'options'          => array(
			'0' => esc_html__( '0px', "publicist" ),
			'10'   => esc_html__( '10px', "publicist" ),
			'20'   => esc_html__( '20px', "publicist" ),
			'30'   => esc_html__( '30px', "publicist" ),
			'40'   => esc_html__( '40px', "publicist" ),
			'50'   => esc_html__( '50px', "publicist" ),
			'60'   => esc_html__( '60px', "publicist" ),
			'70'   => esc_html__( '70px', "publicist" ),
			'80'   => esc_html__( '80px', "publicist" ),
			'90'   => esc_html__( '90px', "publicist" ),
			'100'   => esc_html__( '100px', "publicist" ),
		),
	) );
	
	$cmb_page->add_field( array(
		'name'             => 'Content Area Negative Margin( left/right)',
		'id'               => $prefix . 'content_margin',
		'type'             => 'select',
		'default'          => 'on',
		'options'          => array(
			'on' => esc_html__( 'On', "publicist" ),
			'off'   => esc_html__( 'Off', "publicist" ),
		),
	) );

	$cmb_page->add_field( array(
		'name'             => 'Sidebar Position',
		'desc'             => 'Select an option',
		'id'               => $prefix . 'sidebar_owlayout',
		'type'             => 'radio',
		'default'          => 'none',
		'options'          => array(
			'none' =>  '<img src="'. PUBLICIST_IMGURI .'/layout/default.png" />',
			'right_sidebar' =>  '<img src="'. PUBLICIST_IMGURI .'/layout/right_side.png" />',
			'left_sidebar' =>  '<img src="'. PUBLICIST_IMGURI .'/layout/left_side.png" />',
			'no_sidebar' =>  '<img src="'. PUBLICIST_IMGURI .'/layout/none.png" />',
		),
	) );
	$cmb_page->add_field( array(
		'name'             => 'Widget Area',
		'desc'             => 'Select an option',
		'id'               => $prefix . 'widget_area',
		'type'             => 'select',
		'options'          => publicist_get_sidebars(),
	) );
	
	$cmb_page->add_field( array(
		'name'             => 'Header position',
		'id'               => $prefix . 'header_position',
		'desc'             => 'if select Absolute Position for content area content appear to header',
		'type'             => 'select',
		'default'             => 'absolute',
		'options'          => array(
			'normal' => esc_html__( 'Default', "publicist" ),
			'absolute'   => esc_html__( 'Absolute Position', "publicist" ),
		),
	) );
	
	/* Page Header */
	$cmb_page->add_field( array(
		'name'             => 'Page Header',
		'desc'             => 'Select an option',
		'id'               => $prefix . 'page_title',
		'type'             => 'select',
		'default'          => 'enable',
		'options'          => array(
			'enable' => esc_html__( 'Enable', "publicist" ),
			'disable'   => esc_html__( 'Disable', "publicist" ),
		),
	) );
	$cmb_page->add_field( array(
		'name' => esc_html__( 'Page Banner Image', "publicist" ),
		'desc' => esc_html__( 'Upload an image or enter a URL.', "publicist" ),
		'id'   => $prefix . 'page_header_img',
		'type' => 'file',
	) );
	
	$cmb_page->add_field( array(
		'name' => esc_html__( 'Page Banner Title Text', "publicist" ),
		'id'   => $prefix . 'page_header_title',
		'type' => 'text',
	) );
	
	$prefix_cmb = "cmb_";

	/* ## Post Options ---------------------- */
	require_once( $prefix_cmb . "post.php");
} ?>
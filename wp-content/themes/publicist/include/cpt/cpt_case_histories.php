<?php
/* CPT : Case Histories */
if ( ! function_exists('publicist_cpt_case_histories') ) {

	add_action( 'init', 'publicist_cpt_case_histories', 0 );

	function publicist_cpt_case_histories() {

		$labels = array(
			'name' =>  esc_html__('Case Histories', "publicist" ),
			'singular_name' => esc_html__('Case Histories', "publicist" ),
			'add_new' => esc_html__('Add New', "publicist" ),
			'add_new_item' => esc_html__('Add New Case Histories', "publicist" ),
			'edit_item' => esc_html__('Edit Case Histories', "publicist" ),
			'new_item' => esc_html__('New Case Histories', "publicist" ),
			'all_items' => esc_html__('All Case Histories', "publicist" ),
			'view_item' => esc_html__('View Case Histories', "publicist" ),
			'search_items' => esc_html__('Search Case Histories', "publicist" ),
			'not_found' =>  esc_html__('No Case Histories found', "publicist" ),
			'not_found_in_trash' => esc_html__('No Case Histories found in Trash', "publicist" ),
			'parent_item_colon' => '',
			'menu_name' => esc_html__('Case Histories', "publicist")
		);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'show_in_menu' => true, 
			'query_var' => true,
			'supports' => array( 'title','thumbnail','editor' ),
			'rewrite'  => array( 'slug' => 'case_histories' ),
			'has_archive' => false, 
			'capability_type' => 'post', 
			'hierarchical' => false,
			'menu_position' => 106,
			'menu_icon' => 'dashicons-images-alt2',
		);
		register_post_type( 'publicist_histories', $args );
	}
} ?>
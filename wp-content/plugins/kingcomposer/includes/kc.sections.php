<?php
/**
*
*	(i) Sections manager
*	(c) KingComposer.com
*	(s) since version 2.5
*
*/

class kc_sections{

	function __construct(){

		$this->register();

		if( is_admin() ){

			add_filter ('manage_edit-kc-section_columns', array(&$this, 'edit_kc_section_columns'));
			add_action ('manage_kc-section_posts_custom_column', array(&$this, 'manage_kc_section_columns'), 10, 2);
		}

	}

	public function edit_kc_section_columns( $columns ) {

		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Title', 'kingcomposer' ),
			'screenshot' => __( 'Preview', 'kingcomposer' ),
			'taxonomy-kc-section-category' => __( 'Categories', 'kingcomposer' ),
			'date' => __( 'Date', 'kingcomposer' )
		);

		return $columns;
	}

	public function manage_kc_section_columns( $column, $post_id ) {

		global $post;

		switch( $column ) {

			case 'screenshot' :

				$url = get_the_post_thumbnail_url( $post );

				//get thumbnail data
				$get_data = get_post_meta ($post->ID , 'kc_data', true);
				$thumbnail = '';

				if (!empty($get_data) && is_array($get_data))
				{
					$thumbnail = $get_data['thumbnail'];
				}

				if( $url ){
					echo '<img src="'. esc_url( $url ).'" style="max-width:100%;margin-top:10px;" />';
				}else if( $thumbnail != '') {
					echo '<img src="'. esc_url( $thumbnail ).'" style="max-width:100%;margin-top:10px;" />';
				}else{
					echo __('No preview set', 'kingcomposer');
				}

				break;

			/* Just break out of the switch statement for everything else. */
			default :
				break;
		}

	}

	public function register(){

		global $kc;

		$labels = array(
			'name'               => __( 'KC Sections', 'kingcomposer' ),
			'singular_name'      => __( 'KC Sections', 'kingcomposer' ),
			'menu_name'          => __( 'KC Sections', 'kingcomposer' ),
			'name_admin_bar'     => __( 'KC Sections', 'kingcomposer' ),
			'add_new'            => __( 'Add New', 'kingcomposer' ),
			'add_new_item'       => __( 'Add New Section', 'kingcomposer' ),
			'new_item'           => __( 'New KC Section', 'kingcomposer' ),
			'edit_item'          => __( 'Edit KC Section', 'kingcomposer' ),
			'view_item'          => __( 'View KC Section', 'kingcomposer' ),
			'all_items'          => __( 'Sections Manager', 'kingcomposer' ),
			'search_items'       => __( 'Search sections', 'kingcomposer' ),
			'parent_item_colon'  => __( 'Parent section:', 'kingcomposer' ),
			'not_found'          => __( 'No section found.', 'kingcomposer' ),
			'not_found_in_trash' => __( 'No section found in Trash.', 'kingcomposer' ),
		);

		$labels = $kc->apply_filters('kc_section_labels', $labels);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'show_in_menu'       => 'kingcomposer',
			'supports'           => array( 'title', 'editor', 'thumbnail' ),
			'taxonomies' 		 => array( 'kc-section-category' ),
			'rewrite'			 => array( 'slug' => 'kc-section', 'with_front' => false ),
            'exclude_from_search' => true

		);

		register_post_type( 'kc-section', $args );

		register_taxonomy(

			'kc-section-category' ,
			'kc-section',

			array(
				'hierarchical'          => true,
				'labels'                => array(
					'name'                       => __('Section Categories', 'kingcomposer' ),
					'singular_name'              => __('Section Category', 'kingcomposer' ),
					'search_items'               => __('Search Section Categories', 'kingcomposer' ),
					'popular_items'              => __('Popular Section Categories', 'kingcomposer' ),
					'all_items'                  => __('All Section Categories', 'kingcomposer' ),
					'parent_item'                => null,
					'parent_item_colon'          => null,
					'edit_item'                  => __('Edit Section Category', 'kingcomposer' ),
					'update_item'                => __('Update Section Category', 'kingcomposer' ),
					'add_new_item'               => __('Add New Section Category', 'kingcomposer' ),
					'new_item_name'              => __('New Section Category Name', 'kingcomposer' ),
					'separate_items_with_commas' => __('Separate Section Category with commas', 'kingcomposer' ),
					'add_or_remove_items'        => __('Add or remove Section Category', 'kingcomposer' ),
					'choose_from_most_used'      => __('Choose from the most used Section Category', 'kingcomposer' ),
					'not_found'                  => __('No Section Category found.', 'kingcomposer' ),
					'menu_name'                  => __(' Sections Categories', 'kingcomposer' ),
				),
				'public'				=> true,
				'show_ui'               => true,
				'show_admin_column'     => true,
				'show_in_nav_menus'     => true,
				'show_tagcloud'         => true,
				'query_var'             => true,
				'rewrite'               => array( 'slug' => 'kc-section-category' )
			)
		);

		$kc->add_content_type('kc-section');

	}

}
/*
*
*	Activate
*
*/
new kc_sections();

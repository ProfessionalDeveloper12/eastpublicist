<?php
if( !function_exists('publicist_sc_setup') ) {

	function publicist_sc_setup() {
		add_image_size( 'publicist_1920_982', 1920, 982, true  ); /* Slider Background */
		add_image_size( 'publicist_289_326', 289, 326, true  ); /* Slider Content */
		add_image_size( 'publicist_350_247', 350, 247, true  ); /* Case History */
		add_image_size( 'publicist_139_92', 139, 92, true  ); /* Popular Post */
		add_image_size( 'publicist_570_457', 570, 457, true  ); /* Tab Carousel */
		add_image_size( 'publicist_270_213', 270, 213, true  ); /* Tab Post Thumb */
	}
	add_action( 'after_setup_theme', 'publicist_sc_setup' );
}

function publicist_currentYear() {
    return date('Y');
}
add_shortcode( 'year', 'publicist_currentYear' );

/* kingcomposer Row Space */
if( class_exists("kingcomposer") ) {

	if( !function_exists('publicist_init') ) {
	
		function publicist_init() {
			
			global $kc;
			
			/* Row Top Padding */
			$kc->add_map_param( 'kc_row', array(
				'name' => 'spadding_top',
				'label' => esc_html__('Row Top Padding',"publicist"),
				'type'	=> 'dropdown',
				'options'	=> array(
					'0'	=> esc_html__('0px',"publicist"),
					'10'	=> esc_html__('10px',"publicist"),
					'20'	=> esc_html__('20px',"publicist"),
					'30'	=> esc_html__('30px',"publicist"),
					'40'	=> esc_html__('40px',"publicist"),
					'50'	=> esc_html__('50px',"publicist"),
					'60'	=> esc_html__('60px',"publicist"),
					'70'	=> esc_html__('70px',"publicist"),
					'80'	=> esc_html__('80px',"publicist"),
					'90'	=> esc_html__('90px',"publicist"),
					'100'	=> esc_html__('100px',"publicist"),
				),
				'admin_label' => true,
			), 1 );
			
			/* Row Top Padding */
			$kc->add_map_param( 'kc_row', array(
				'name' => 'spadding_bottom',
				'label' => esc_html__('Row Bottom Padding',"publicist"),
				'type'	=> 'dropdown',
				'options'	=> array(
					'0'	=> esc_html__('0px',"publicist"),
					'10'	=> esc_html__('10px',"publicist"),
					'20'	=> esc_html__('20px',"publicist"),
					'30'	=> esc_html__('30px',"publicist"),
					'40'	=> esc_html__('40px',"publicist"),
					'50'	=> esc_html__('50px',"publicist"),
					'60'	=> esc_html__('60px',"publicist"),
					'70'	=> esc_html__('70px',"publicist"),
					'80'	=> esc_html__('80px',"publicist"),
					'90'	=> esc_html__('90px',"publicist"),
					'100'	=> esc_html__('100px',"publicist"),
				),
				'admin_label' => true,
			), 2 );
			
			/* Kc Container Left Padding */
			$kc->add_map_param( 'kc_row', array(
				'name' => 'kc_left_padding',
				'label' => esc_html__('KingComposer Container Left Padding',"publicist"),
				'type'	=> 'dropdown',
				'options'	=> array(
					''	=> esc_html__('Select an Options',"publicist"),
					'0'	=> esc_html__('0px',"publicist"),
					'5'	=> esc_html__('5px',"publicist"),
					'10'	=> esc_html__('10px',"publicist"),
					'15'	=> esc_html__('15px',"publicist"),
					'20'	=> esc_html__('20px',"publicist"),
					'25'	=> esc_html__('25px',"publicist"),
					'30'	=> esc_html__('30px',"publicist"),
					'35'	=> esc_html__('35px',"publicist"),
					'40'	=> esc_html__('40px',"publicist"),
					'45'	=> esc_html__('45px',"publicist"),
					'50'	=> esc_html__('50px',"publicist"),
				),
				'admin_label' => true,
			), 3 );
			
			/* Kc Container Right Padding */
			$kc->add_map_param( 'kc_row', array(
				'name' => 'kc_right_padding',
				'label' => esc_html__('KingComposer Container Right Padding',"publicist"),
				'type'	=> 'dropdown',
				'options'	=> array(
					''	=> esc_html__('Select an Options',"publicist"),
					'0'	=> esc_html__('0px',"publicist"),
					'5'	=> esc_html__('5px',"publicist"),
					'10'	=> esc_html__('10px',"publicist"),
					'15'	=> esc_html__('15px',"publicist"),
					'20'	=> esc_html__('20px',"publicist"),
					'25'	=> esc_html__('25px',"publicist"),
					'30'	=> esc_html__('30px',"publicist"),
					'35'	=> esc_html__('35px',"publicist"),
					'40'	=> esc_html__('40px',"publicist"),
					'45'	=> esc_html__('45px',"publicist"),
					'50'	=> esc_html__('50px',"publicist"),
				),
				'admin_label' => true,
			), 4 );
			
		}
		add_action('init', 'publicist_init', 99 );
	}
}

if( function_exists('kc_add_map') ) {

	/* Include all individual shortcodes. */
	$prefix_sc = "sc_";
	require_once( $prefix_sc . "case_history.php");
	require_once( $prefix_sc . "slider.php");
	require_once( $prefix_sc . "tab_content.php");
	require_once( $prefix_sc . "blog_post.php");
} ?>
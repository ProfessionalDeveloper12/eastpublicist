<?php

/*
 * Remove __empty__ code value
 */
function kc_remove_empty_code( $atts ){

	$atts_tmp = array();

	foreach( $atts as $key => $value ){
		if('__empty__' === $value){
			$atts_tmp[$key] = '';
		}else{
			$atts_tmp[$key] = $value;
		}
	}

	return $atts_tmp;
}


//Row filter
function kc_row_filter( $atts ){

	if( isset( $atts['video_bg'] ) && $atts['video_bg'] == 'yes' ){
		wp_register_script('kc-youtube-iframe-api', 'https://www.youtube.com/iframe_api', null, KC_VERSION, true );
		wp_enqueue_script('kc-youtube-iframe-api');
	}

	return $atts;

}
//Row filter
function kc_column_filter( $atts ){

	if( isset( $atts['video_bg'] ) && $atts['video_bg'] == 'yes' ){
		wp_register_script('kc-youtube-iframe-api', 'https://www.youtube.com/iframe_api', null, KC_VERSION, true );
		wp_enqueue_script('kc-youtube-iframe-api');
	}

	return $atts;

}

function kc_row_inner_filter( $atts ){

	if( isset( $atts['video_bg'] ) && $atts['video_bg'] == 'yes' ){
		wp_register_script('kc-youtube-iframe-api', 'https://www.youtube.com/iframe_api', null, KC_VERSION, true );
		wp_enqueue_script('kc-youtube-iframe-api');
	}

	return $atts;

}

//Tab filter
function kc_tabs_filter( $atts = array() ){

	if( isset( $atts['type'] ) && $atts['type'] === 'slider_tabs' ){
		wp_enqueue_script( 'owl-carousel' );
		wp_enqueue_style( 'owl-theme' );
		wp_enqueue_style( 'owl-carousel' );
	}

	return $atts;

}

function kc_tab_filter( $atts = array() ){

	// Do your code here
	global $kc_tab_id;

	if( !isset( $kc_tab_id ) || empty( $kc_tab_id ) )
		$kc_tab_id = array();

	$i = 1; $_title = sanitize_title( !empty( $atts['title'] ) ? $atts['title'] : 'kc-tab' );
	while( in_array( $_title, $kc_tab_id ) )
	{
		$i++;
		$_title = sanitize_title( !empty( $atts['title'] ) ? $atts['title'] : 'kc-tab' ).$i;
	}

	array_push( $kc_tab_id, $_title );

	$atts['tab_id'] = $_title;

	return $atts;

}

function kc_box_filter( $atts = array() ){

	global $kc_front;

	if( isset( $atts['css_code'] ) && !empty( $atts['css_code'] ) ){
		$kc_front->add_header_css( $atts['css_code'] );
	}

	return $atts;

}


function kc_video_play_filter( $atts = array() ){

	if( isset( $atts['video_link'] ) && !empty( $atts['video_link'] ) ){

		if(preg_match('/youtu\.be/i', $atts['video_link']) || preg_match('/youtube\.com\/watch/i', $atts['video_link'])){
			$atts['check_video'] = 'true';
			wp_enqueue_script('kc-youtube-api');
		}else if( (preg_match('/vimeo\.com/i', $atts['video_link'] )) ){
			$atts['check_video'] = 'true';
			wp_enqueue_script('kc-vimeo-api');
		}else{
			$atts['check_video'] = 'false';
		}
	}else{
		$atts['check_video'] = 'false';
	}

	if( isset( $atts['video_upload'] ) && !empty( $atts['video_upload'] ) ){
		$atts['check_video'] = 'true';
	}

	wp_enqueue_script( 'kc-video-play' );

	return $atts;
}


function kc_counter_box_filter( $atts = array() ){

	wp_enqueue_script('waypoints');
	wp_enqueue_script('counter-up');

	return $atts;
}


function kc_carousel_post_filter( $atts = array() ){

	$atts = kc_remove_empty_code( $atts );
	extract( $atts );

	wp_enqueue_script( 'owl-carousel' );
	wp_enqueue_style( 'owl-theme' );
	wp_enqueue_style( 'owl-carousel' );

	return $atts;
}

/*
 * Pie chart shortcode custom css
 */
function kc_pie_chart_filter( $atts = array() ){

	global $kc_front;

	wp_enqueue_script( 'easypie-chart' );

	return $atts;

}


function kc_twitter_feed_filter( $atts = array() ){

	global $kc_front;

	$atts = kc_remove_empty_code( $atts );
	extract( $atts );

	if( isset( $display_style ) && '2' === $display_style ){
		wp_enqueue_script( 'owl-carousel' );
		wp_enqueue_style( 'owl-theme' );
		wp_enqueue_style( 'owl-carousel' );
	}

	return $atts;
}

function kc_carousel_images_filter( $atts = array() ){

	$atts = kc_remove_empty_code( $atts );
	extract( $atts );

	wp_enqueue_script( 'owl-carousel' );
	wp_enqueue_style( 'owl-theme' );
	wp_enqueue_style( 'owl-carousel' );

	if( isset( $onclick ) && $onclick == 'lightbox' ){
		wp_enqueue_script( 'prettyPhoto' );
		wp_enqueue_style( 'prettyPhoto' );
	}

	return $atts;
}

function kc_image_gallery_filter( $atts = array() ){

	global $kc_front;
	$atts = kc_remove_empty_code( $atts );

	extract( $atts );

	wp_enqueue_script( 'masonry' );

	if( isset( $click_action ) && 'lightbox' === $click_action ){
		wp_enqueue_script( 'prettyPhoto' );
		wp_enqueue_style( 'prettyPhoto' );
	}

	return $atts;
}


function kc_blog_posts_filter( $atts = array() ) {

	if ( isset( $atts['layout'] ) && ($atts['layout'] == '1' || $atts['layout'] == '4')  ) {

		wp_enqueue_script( 'owl-carousel' );
		wp_enqueue_style( 'owl-theme' );
		wp_enqueue_style( 'owl-carousel' );

	}

	wp_enqueue_script( 'masonry' );


	return $atts;

}

function kc_feature_box_filter( $atts = array() ){

	if( isset( $atts['layout'] ) && $atts['layout'] == '2' ){
		wp_enqueue_script( 'owl-carousel' );
		wp_enqueue_style( 'owl-theme' );
		wp_enqueue_style( 'owl-carousel' );
	}

	return $atts;

}

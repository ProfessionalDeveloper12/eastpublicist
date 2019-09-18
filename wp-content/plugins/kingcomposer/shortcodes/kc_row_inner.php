<?php

$output = $row_class_container = $row_class = $row_id = $css = '';

extract( $atts );


$css_classes = apply_filters( 'kc-el-class', $atts );

$css_classes = array_merge( $css_classes, array(
	'kc_row',
	'kc_row_inner',
	$row_class
));

if( $css != '' )
	$css_classes[] = $css;
	
$attributes = array();

if ( ! empty( $row_id ) ) {
	$attributes[] = 'id="' . esc_attr( $row_id ) . '"';
}




if( empty($atts['column_align']) )
    $atts['column_align'] = 'center';

if( !empty( $atts['equal_height'] ) )
{
	$attributes[] = 'data-kc-equalheight="true"';
	$attributes[] = 'data-kc-row-action="true"';
    $attributes[] = 'data-kc-equalheight-align="'. $atts['column_align'] .'"';
}

/**
 *Check video background
 */

if( isset( $atts['video_bg'] ) && $atts['video_bg'] === 'yes' )
{
	$video_bg_url = $atts['video_bg_url'];
	
	if( empty($video_bg_url)) $video_bg_url = 'https://www.youtube.com/watch?v=dOWFVKb2JqM';
	
	$has_video_bg = kc_youtube_id_from_url( $video_bg_url );
	
	if( !empty( $has_video_bg ) )
	{
		$css_classes[] = 'kc-video-bg';
		$attributes[] = 'data-kc-video-bg="' . esc_attr( $video_bg_url ) . '"';
		
		if( isset( $atts['video_options'] ) && !empty( $video_options ) ){
			$element_attributes[] = 'data-kc-video-options="' . esc_attr( trim( $video_options )) . '"';
		}
		if( isset( $atts['video_mute'] ) && !empty( $atts['video_mute'] ) )
			$element_attributes[] = 'data-kc-video-mute="' . esc_attr( $atts['video_mute'] ) . '"';
	}
}

$attributes[] = 'class="' . esc_attr( trim( implode(' ', $css_classes) ) ) . '"';

$output .= '<div ' . implode( ' ', $attributes ) . '>';

if( !empty( $row_class_container ) )
	$output .= '<div class="'.esc_attr( $row_class_container ).'">';

$output .= do_shortcode( str_replace('kc_row_inner#', 'kc_row_inner', $content ) );

if( !empty( $row_class_container ) )
	$output .= '</div>';

$output .= '</div>';

echo $output;

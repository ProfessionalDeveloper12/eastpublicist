<?php

$width = $css = $output = $col_class = $col_container_class = $col_id = $css_data =  '';

extract( $atts );

$attributes = array();
$style = array();
$classes = apply_filters( 'kc-el-class', $atts );

$classes[] = 'kc_column';
$classes[] = @kc_column_width_class( $width );

if( !empty( $col_class ) )
	$classes[] = $col_class;

if( !empty( $col_id ) )
	$attributes[] = 'id="'. $col_id .'"';

if( count($style) > 0 )
	$attributes[] = 'style="'.implode(';', $style).'"';



$col_container_class = !empty( $col_container_class ) ? $col_container_class.' kc-col-container' : 'kc-col-container';

/**
 *Check video background
 */

if( $atts['video_bg'] === 'yes' )
{
	$video_bg_url = $atts['video_bg_url'];
	$video_mute = $atts['video_mute'];

	if( empty($video_bg_url)) $video_bg_url = 'https://www.youtube.com/watch?v=dOWFVKb2JqM';

	$has_video_bg = kc_youtube_id_from_url( $video_bg_url );

	if( !empty( $has_video_bg ) )
	{
		$classes[] = 'kc-video-bg';
		$attributes[] = 'data-kc-video-bg="' . esc_attr( $video_bg_url ) . '"';
		$attributes[] = 'data-kc-video-mute="' . esc_attr( $video_mute ) . '"';
		$css_data .= 'position: relative;';
	}
}


$attributes[] = 'class="' . esc_attr( trim( implode(' ', $classes) ) ) . '"';

$output = '<div ' . implode( ' ', $attributes ) . '>'
		. '<div class="'.esc_attr( $col_container_class ).'">'
		. do_shortcode( str_replace('kc_column#', 'kc_column', $content ) )
		. '</div>'
		. '</div>';

echo $output;

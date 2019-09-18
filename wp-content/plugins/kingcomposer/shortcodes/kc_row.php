<?php

$output = $css_data = $css = '';

$cont_class = array( 'kc-row-container' );
$element_attributes = array();

extract($atts);

$css_classes = apply_filters( 'kc-el-class', $atts );

$css_classes[] = 'kc_row';

if( $css != '' )
	$css_classes[] = $css;
	
if( !empty( $atts['row_class'] ) )
	$css_classes[] = $atts['row_class'];

if( !empty( $atts['full_height'] ) )
{
	if( $atts['content_placement'] == 'middle' )
		$element_attributes[] = 'data-kc-fullheight="middle-content"';
	else $element_attributes[] = 'data-kc-fullheight="true"';
	
}

if( empty($atts['column_align']) )
    $atts['column_align'] = 'center';

if( !empty( $atts['equal_height'] ) ){
    $element_attributes[] = 'data-kc-equalheight="true"';
    $element_attributes[] = 'data-kc-equalheight-align="'. $atts['column_align'] .'"';
}
	


if( isset( $atts['use_container'] ) && $atts['use_container'] == 'yes' )
	$cont_class[] = ' kc-container';

if( !empty( $atts['container_class'] ) )
	$cont_class[] = ' '.$atts['container_class'];

if( !empty( $atts['css'] ) )
	$css_classes[] = $atts['css'];

/**
*Check video background
*/

if( $atts['video_bg'] === 'yes' )
{
	$video_bg_url = $atts['video_bg_url'];
	
	if( empty($video_bg_url)) $video_bg_url = 'https://www.youtube.com/watch?v=dOWFVKb2JqM';

	$has_video_bg = kc_youtube_id_from_url( $video_bg_url );

	if( !empty( $has_video_bg ) )
	{
		$css_classes[] = 'kc-video-bg';
		$element_attributes[] = 'data-kc-video-bg="' . esc_attr( $video_bg_url ) . '"';
		$css_data .= 'position: relative;';
		
		if( isset( $atts['video_options'] ) && !empty( $video_options ) ){
			$element_attributes[] = 'data-kc-video-options="' . esc_attr( trim( $video_options )) . '"';
		}
		if( isset( $atts['video_mute'] ) && !empty( $atts['video_mute'] ) )
			$element_attributes[] = 'data-kc-video-mute="' . esc_attr( $atts['video_mute'] ) . '"';
	}
}


if( !empty( $atts['row_id'] ) ){
	$row_id = urlencode( $atts['row_id'] );
	$element_attributes[] = 'id="' . esc_attr( $row_id ) . '"';
}
	

if( isset( $atts['force'] ) && $atts['force'] == 'yes'  ){
	if( isset( $atts['use_container'] ) && $atts['use_container'] == 'yes' )
		$element_attributes[] = 'data-kc-fullwidth="row"';
	else
		$element_attributes[] = 'data-kc-fullwidth="content"';
}



if( empty( $has_video_bg ) )
{
	if( !empty( $atts['parallax'] ) )
	{

		$element_attributes[] = 'data-kc-parallax="true"';

		if( $atts['parallax'] == 'yes-new' )
		{
			$bg_image_id = $atts['parallax_image'];
			$bg_image = wp_get_attachment_image_src( $bg_image_id, 'full' );
			$css_data .= "background-image:url('".$bg_image[0]."');";
		}
		
	}
}


$css_class = implode(' ', $css_classes);
$element_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';

if( !empty( $css_data ) )
	$element_attributes[] = 'style="' . esc_attr( trim( $css_data ) ) . '"';

$output .= '<section ' . implode( ' ', $element_attributes ) . '>';

$output .= '<div class="' . esc_attr(implode( ' ', $cont_class)) . '">';

$output .= '<div class="kc-wrap-columns">'.do_shortcode( str_replace( 'kc_row#', 'kc_row', $content ) ).'</div>';

$output .= '</div>';

$output .= '</section>';

echo $output;

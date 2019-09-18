<?php

$output = $title = $slider_id = $css = '';

extract( $atts );

$wrp_classes = apply_filters( 'kc-el-class', $atts );

$attributes = array();
$classes = array(
	'kc_revslider',
	$atts['class']
);

if( $css != '' )$classes[] = $css;

$attributes[] = 'class="' . esc_attr( implode(' ', $classes) ) . '"';

$output .= '<div '.implode( ' ', $wrp_classes ) . '>';

if ( !empty( $atts['title'] ) )
	$output .= '<h3>' . $atts['title'] . '</h3>';

if ( !empty( $atts['slider_id'] ) ) {
	$output .= '<div '.implode( ' ', $attributes ) . '>';
		$output .= do_shortcode( '[rev_slider alias="' . $atts['slider_id'] . '"]' );
	$output .= '</div>';
} else {
	$output .= __( 'Please create and select slider.', 'kingcomposer' );
}
echo '</div>';
echo $output;

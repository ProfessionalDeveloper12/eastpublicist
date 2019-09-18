<?php

$output = $width = $col_in_class = $col_in_class_container = $css = $col_id = '';
$attributes = array();

extract( $atts );

$classes = apply_filters( 'kc-el-class', $atts );
$classes[] = 'kc_column_inner';
$classes[] = @kc_column_width_class( $width );


if( !empty( $col_in_class ) )
	$classes[] = $col_in_class;

if( !empty( $css ) )
	$classes[] = $css;

$col_in_class_container = !empty($col_in_class_container)?$col_in_class_container.' kc_wrapper kc-col-inner-container':'kc_wrapper kc-col-inner-container';


if( !empty( $col_id ) )
	$attributes[] = 'id="'. $col_id .'"';

$attributes[] = 'class="' . esc_attr( trim( implode(' ', $classes) ) ) . '"';

$output .= '<div ' . implode( ' ', $attributes ) . '>'
		. '<div class="'.trim( esc_attr( $col_in_class_container ) ).'">'
		. do_shortcode( str_replace('kc_column_inner#', 'kc_column_inner', $content ) )
		. '</div>'
		. '</div>';

echo $output;

<?php

$class = $css = '';
$height = 0;

extract($atts);

$css_classes = apply_filters( 'kc-el-class', $atts );
if ( !empty( $class ) )
	$css_classes[] = $class;

echo '<div class="'. esc_attr( implode(' ', $css_classes) ) .'" style="height: '. esc_attr(intval($height)) .'px; clear: both; width:100%;"></div>';
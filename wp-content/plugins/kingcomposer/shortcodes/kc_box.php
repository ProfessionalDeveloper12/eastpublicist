<?php

$custom_class = $css = '';
$el_classes = apply_filters( 'kc-el-class', $atts );
extract($atts);

$element_attributes = array();
$el_classes[] = 'kc_box_wrap';
$el_classes[] = $custom_class;

if ($css != '')
	$el_classes[] = $css;

$element_attributes[] = 'class="'. esc_attr(implode(' ', $el_classes)) .'"';

echo '<div '. implode(' ', $element_attributes ) .'>';

$data = kc_images_filter(base64_decode($atts['data']));

if( $data = json_decode( $data ) )
{
	echo kc_loop_box( $data );
}
else
{
	echo 'KC Box: Error content structure';
}

echo '</div>';

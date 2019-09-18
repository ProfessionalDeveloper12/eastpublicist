<?php

$output = $wrap_class = $css = '';
$count = 0;

extract( $atts );

$css_classes = apply_filters( 'kc-el-class', $atts );

$element_attributes = array();
$css_classes = array_merge($css_classes, array(
	'kc_shortcode',
	'kc_wrap_instagram',
	$wrap_class
));

if( $css != '' )$css_classes[] = $css;

if ( !empty( $columns_style ) ) {
	$css_classes[] = 'kc_ins_col_' . $columns_style;
}

$css_class = implode( ' ', $css_classes );

$element_attributes[] = 'class="' . esc_attr( $css_class ) . '"';

$image_size = (!empty($image_size)) ? $image_size : 'thumbnail';
$number_show = (!empty( $number_show )) ? $number_show : 8;

global $kc;
if( isset($atts['access_token']) && !empty($atts['access_token']) )
	$atts['access_token'] = $kc->secrect_storage( $atts['access_token'], 'encrypt' );

$element_attributes[] = 'data-cfg="'. base64_encode( json_encode( $atts ) ).'"';

$output .= '<div '. implode( ' ', $element_attributes ) .'>';

if(!empty($title)){
	$output .= '<h3 class="kc-widget-title">'. esc_html($title) .'</h3>';
}

$output .= '<ul>';

$mark_class = 'ins_mark_'.$image_size;
for($i=1; $i<=$number_show; $i++){
	switch ($i%$columns_style) {
		case '1':
			$li_class = 'el-start';
			break;
		case '0':
			$li_class = 'el-end';
			break;
		default:
			$li_class = '';
			break;
	}
	$output .= '<li class="'. esc_attr($mark_class .' '. $li_class) .'"></li>';
}

$output .= '</ul>';

$output .= '</div>';

echo $output;

kc_js_callback( 'kc_front.ajax_action' );

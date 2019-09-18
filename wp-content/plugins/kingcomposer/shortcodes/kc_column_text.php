<?php

$class = $css = '';

extract( $atts );

$output = '';
$el_class = apply_filters( 'kc-el-class', $atts );
$el_class[] = 'kc_text_block';

if( $class != '' )$el_class[] = $class;
if( $css != '' )$el_class[] = $css;
	
$content = apply_filters('kc_column_text', $content );

echo '<div class="'.esc_attr( implode(' ', $el_class) ).'">';
echo wpautop( do_shortcode( $content ) );
echo '</div>';

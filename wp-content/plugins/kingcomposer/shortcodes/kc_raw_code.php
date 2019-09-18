<?php
	
$code = '';

extract($atts);

$classes = apply_filters( 'kc-el-class', $atts );

$classes[] = 'kc-raw-code';
	
echo '<div class="'.esc_attr( implode( ' ', $classes ) ).'">';
echo do_shortcode( $code );
echo '</div>';

<?php

$classes = apply_filters( 'kc-el-class', $atts );
// This is for adding master class, so the css system can be worked with this element

$output = '<div class="'.implode(' ', $classes).'">';
$output .= do_shortcode( str_replace('kc_nested#', 'kc_nested', $content ) );
// This to process its self nested
$output .= '</div>';
 
echo $output;
 
?>
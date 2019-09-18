<?php

global $wpdb;

$title = $slug = $class = '';

extract( $atts );

$wrp_el_classes = apply_filters( 'kc-el-class', $atts );

if( !empty( $class ) )
	$wrp_el_classes[] = $class;

$form = $wpdb->get_results("SELECT `ID` FROM `".$wpdb->posts."` WHERE `post_type` = 'wpcf7_contact_form' AND `post_name` = '".esc_attr(sanitize_title($slug))."' LIMIT 1");

if( !empty( $form ) ){
	echo '<div class="kc-contact-form7 '. esc_attr( implode(' ', $wrp_el_classes) ) .'">';
	if ( !empty( $title ) ) {
		echo '<h2>'. $title .'</h2>';
	}
	echo do_shortcode('[contact-form-7 id="'.$form[0]->ID.'" title="'.esc_attr($title).'"]');
	echo '</div>';
}else{
	echo __('Please select one of contact form 7 for display.', 'kingcomposer');
}

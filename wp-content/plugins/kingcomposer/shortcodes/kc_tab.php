<?php
/**
 * kc_tab shortcode
 **/
 
$tab_id = $title = '';
extract( $atts );

$css_class = apply_filters( 'kc-el-class', $atts );

$css_class = array_merge($css_class, array( 'kc_tab', 'ui-tabs-panel', 'kc_ui-tabs-hide', 'kc_clearfix' ));

if( empty( $tab_id ) || (strpos( $tab_id,'%time%' ))){
	$tab_id = sanitize_title( $title );
}else{
	$tab_id = esc_attr( $tab_id );
}

if( isset( $class ) )
	array_push( $css_class, $class );

$output = '<div id="' . $tab_id . '" class="' . esc_attr( implode( ' ', $css_class ) ) . '"><div class="kc_tab_content">'.
		( ( '' === trim( $content ) ) 
		? __( 'Empty tab. Edit page to add content here.', 'kingcomposer' ) 
		: do_shortcode( str_replace('kc_tab#', 'kc_tab', $content ) ) ).
	'</div></div>';

echo $output;
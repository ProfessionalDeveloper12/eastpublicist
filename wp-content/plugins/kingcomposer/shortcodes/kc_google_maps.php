<?php
/*
 * Google maps shortcode template
 */

$output = $title = $wrap_class = $contact_form = $disable_wheel_mouse = '';
$map_width = '100%';
$map_height = '350px';

$contact_area_position = 'left';
$google_maps_info = array();

extract( $atts );


$css_classes = apply_filters( 'kc-el-class', $atts );

$element_attributes = array();
$map_attributes     = array();

$css_classes = array_merge($css_classes, array(
    'kc_google_maps',
    'kc_shortcode'
));

if ( !empty( $wrap_class ) )
    $css_classes[] = $wrap_class;

$element_attributes[] = 'class="'. esc_attr( implode( ' ', $css_classes ) ) .'"';

if( !empty( $title ) ){
    $output .= '<h3 class="map_title">'. esc_html( $title ) .'</h3>';
}

//Contact form on maps
if( !empty($show_ocf) && 'yes' === $show_ocf ){
    if(!empty( $contact_form_sc )){
        $contact_form = '<div class="map_popup_contact_form '. $contact_area_position .'">';
        $contact_form .= '<a class="close" href="javascript:;"><i class="sl-close"></i></a>';
        $contact_form .= do_shortcode( $contact_form_sc );
        $contact_form .= '</div>';
        $contact_form .= '<a class="show_contact_form" href="javascript:;"><i class="fa fa-bars"></i></a>';
    }
}

$map_attributes[] = 'style="height: '. esc_attr( $map_height ) .'px"';
$map_attributes[] = 'class="kc-google-maps"';

if( !empty( $disable_wheel_mouse ) ){
	$element_attributes[] = 'data-wheel="disable"';
}

$map_location = preg_replace( array('/width="\d+"/i', '/height="\d+"/i'), array(
        sprintf('width="%s"', $map_width ),
        sprintf('height="%d"', intval( $map_height ))
    ),
   $map_location );

if( isset( $_GET['kc_action'] ) && $_GET['kc_action'] === 'live-editor' ){

    $map_location = '<div style="width: 100%;height:'.$map_height.'px;" class="disable-view-element"><h3>For best perfomance, the map has been disabled in this editing mode.</h3></div>';

}

$output .= '<div '. implode( ' ', $element_attributes ) .'>'. $contact_form .'<div '. implode( ' ', $map_attributes ) .'>'. $map_location .'</div></div>';

echo $output;

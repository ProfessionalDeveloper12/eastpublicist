<?php

$show_navigation = $show_pagination = $auto_height = '';

extract( $atts );

$attributes = array();

$el_classes = apply_filters( 'kc-el-class', $atts );

$el_classes = array_merge($el_classes, array(
	'kc_shortcode',
	'kc_wrap_twitter',
	'kc_twitter_feed',
	'kc_twitter_style-' . $display_style
));

if ( !empty( $wrap_class ) )
	$el_classes[] = $wrap_class;

if ( isset( $css ) )
	$el_classes[] = $css;

$atts_data = array(
	'show_navigation' => $show_navigation,
	'show_pagination' => $show_pagination,
	'auto_height' => $auto_height
);


global $kc;

if( isset($atts['consumer_key']) && !empty($atts['consumer_key']) )
	$atts['consumer_key'] = $kc->secrect_storage( $atts['consumer_key'], 'encrypt' );

if( isset($atts['consumer_secret']) && !empty($atts['consumer_secret']) )
	$atts['consumer_secret'] = $kc->secrect_storage( $atts['consumer_secret'], 'encrypt' );

if( isset($atts['access_token']) && !empty($atts['access_token']) )
	$atts['access_token'] = $kc->secrect_storage( $atts['access_token'], 'encrypt' );

if( isset($atts['access_token_secrect']) && !empty($atts['access_token_secrect']) )
	$atts['access_token_secrect'] = $kc->secrect_storage( $atts['access_token_secrect'], 'encrypt' );

$attributes[] = 'class="'. esc_attr( implode( ' ', $el_classes ) ) .'"';
$attributes[] = 'data-cfg="'. base64_encode( json_encode( $atts ) ) .'"';
$attributes[] = 'data-owl_option="'. esc_attr( json_encode( $atts_data ) ) .'"';
$attributes[] = 'data-display_style="'. esc_attr( $display_style ) .'"';

?>

<div <?php echo trim( implode(' ', $attributes ) ); ?>>

	<div class="result_twitter_feed"><span>Loading...</span></div>

</div>

<?php

kc_js_callback( 'kc_front.ajax_action' );

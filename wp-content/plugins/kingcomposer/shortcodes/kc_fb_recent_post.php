<?php

$output = $wrap_class = '';

extract($atts);

$num_post_show 	= ( !empty($number_post_show) ) ? $number_post_show : 5;
$btn_text 		= !empty( $follow_text )? esc_html( $follow_text ) : 'Go to <strong>'. $fb_page_id .'</strong> fan page';
$max_height 	= !empty( $max_height ) ? intval( $max_height ) : '300';
$el_classes 	= apply_filters( 'kc-el-class', $atts );

$el_classes 	= array_merge(
	$el_classes, 
	array(
		'kc_shortcode',
		'kc_facebook_recent_post',
		$wrap_class
	)
);

global $kc;

if( isset($atts['fb_app_id']) && !empty($atts['fb_app_id']) )
	$atts['fb_app_id'] = $kc->secrect_storage( $atts['fb_app_id'], 'encrypt' );

if( isset($atts['fb_app_secret']) && !empty($atts['fb_app_secret']) )
	$atts['fb_app_secret'] = $kc->secrect_storage( $atts['fb_app_secret'], 'encrypt' );


$elm_attrs 		= array();
$elm_attrs[] 	= 'class="'. esc_attr( implode(' ', $el_classes ) ) .'"';
$elm_attrs[] 	= 'data-cfg="'. base64_encode( json_encode( $atts ) ) .'"';

kc_js_callback( 'kc_front.ajax_action' );

?>
<div <?php echo implode(' ', $elm_attrs ) ;?>>
	<ul class="list-posts">
		<?php

		for( $i=1; $i <= $num_post_show; $i++){ 
		?>
			<li class="fb_mark_cls"></li>
		<?php
		}		
		?>
	</ul>
	<?php

	if( !empty( $show_profile_button ) && 'yes' === $show_profile_button ){

		$fb_page_id = !empty( $fb_page_id )? $fb_page_id : 'kingcomposer';
		$page_url = 'https://www.facebook.com/' . $fb_page_id;

	?>
	<a class="fb-button-profile" href="<?php echo esc_url( $page_url ) ;?>" target="_blank">
	<?php echo $btn_text ;?>
	</a>
	<?php

	}
	
	?>
</div>

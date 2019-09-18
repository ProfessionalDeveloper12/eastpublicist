<?php

// JS callback for live editor
kc_js_callback('kc_front.tooltips');

$position 	= $icon = $image = $img_size = $button_text = $button_link = $button_target = $button_title = $text_tooltip = $custom_class = $data_icon = $data_img = $data_button = '';
$layout		= 1;

$wrapper_class	= apply_filters( 'kc-el-class', $atts );

extract( $atts );

$wrapper_class[] = 'kc-popover-tooltip';

if ( !empty( $custom_class ) )
	$wrapper_class[] = $custom_class;

$main_class = array( 'kc_tooltip' );
$main_class[] = 'style' . $layout;

if ( !empty( $icon ) ) {

	$data_icon .= '<i class="'. $icon .' fati17"></i>';

}

if ( $image != '' ) {

	$img_link = wp_get_attachment_image_src( $image, 'full' );
	$img_link = $img_link[0];
	if ( $img_size != 'full' ) {
		$img_link = kc_tools::createImageSize( $img_link, $img_size );
	}

	$data_img .= '<img src="'. $img_link .'" alt="">';

}else{
	$data_img .= '<img src="'. kc_asset_url('images/get_start_s.jpg') .'" alt="">';
}

$button_attr = array();
$button_attr[] = 'class="kc_tooltip style3"';
$button_attr[] = 'data-tooltip="true"';
$button_attr[] = 'data-position="'. $position .'"';

if ( !empty( $button_link ) ) {

	$button_link_text = explode( '|', $button_link );
	$button_link = $button_link_text[0];
	if ( !empty( $button_link_text[1] ) )
		$button_title = $button_link_text[1];

	if ( !empty( $button_link_text[2] ) )
		$button_target = $button_link_text[2];

} else {
	$button_link = '#';
}
$button_attr[] = 'href="'. $button_link .'"';
if ( !empty( $button_title ) )
	$button_attr[] = 'title="'. esc_attr( $button_title ) .'"';
if ( !empty( $button_target ) )
	$button_attr[] = 'target="'. esc_attr( $button_target ) .'"';

if( empty($button_text) )
	$button_text = __( 'Read More', 'kingcomposer' );

$data_button .= '<a '. implode( '  ', $button_attr ) .'>'. $button_text .'<span>'. $text_tooltip .'</span></a>';

?>

<div class="<?php echo esc_attr( implode(' ', $wrapper_class) ); ?>">

	<?php switch ( $layout ) {
		case '2':
	?>
			<div class="<?php echo implode( ' ', $main_class ); ?>" data-tooltip="true" data-position="<?php echo $position; ?>">
				<?php echo $data_img; ?>
				<span><?php echo $text_tooltip; ?></span>
			</div>
	<?php
		break;
		case '3':
			echo '<div class="content-button">';
				echo $data_button;
			echo '</div>';
		break;
		default:
	?>
			<div class="<?php echo implode( ' ', $main_class ); ?>" data-tooltip="true" data-position="<?php echo $position; ?>">
				<?php echo $data_icon; ?>
				<span><?php echo $text_tooltip; ?></span>
			</div>
	<?php
		break;
	} ?>

</div>

<?php
$title = $desc = $icon = $image = $position = $show_button = $button_text = $button_title = $button_target = $button_href = $button_link = $custom_class = $data_img = $data_icon = $data_title = $data_desc = $data_position = $data_button = '';
$layout = 1;
$wrap_class	= apply_filters( 'kc-el-class', $atts );

extract( $atts );

$wrap_class[] = 'kc-feature-boxes';
$wrap_class[] = 'kc-fb-layout-' . $layout;

if ( !empty( $custom_class ) )
	$wrap_class[] = $custom_class;

if ( !empty( $image ) ) {

	$img_link = wp_get_attachment_image_src( $image, 'full' );
	$alttext = get_post_meta( $image, '_wp_attachment_image_alt', true);
	$data_img .= '<figure class="content-image">';
		$data_img .= '<img src="'. $img_link[0] .'" alt="'. $alttext .'">';
	$data_img .= '</figure>';

}

if ( !empty( $title ) ) {

	$data_title .= '<div class="content-title">';
		$data_title .= $title;
	$data_title .= '</div>';

}

if ( !empty( $desc ) ) {

	$data_desc .= '<div class="content-desc">';
		$data_desc .= $desc;
	$data_desc .= '</div>';

}

if ( !empty( $position ) ) {

	$data_position .= '<div class="content-position">';
		$data_position .= $position;
	$data_position .= '</div>';

}

if( empty($icon) || $icon == '__empty__')
	$icon = 'et-envelope';

$data_icon .= '<div class="content-icon">';
	$data_icon .= '<i class="'. $icon .'"></i>';
$data_icon .= '</div>';

if ( $show_button == 'yes' ) {
	$button_link	= ( '||' === $button_link ) ? '' : $button_link;
	$button_link	= kc_parse_link($button_link);
	
	if ( strlen( $button_link['url'] ) > 0 ) {
		$button_href 	= $button_link['url'];
		$button_title 	= $button_link['title'];
		$button_target 	= strlen( $button_link['target'] ) > 0 ? $button_link['target'] : '_self';
	}

	$data_button .= '<div class="content-button">';
		$data_button .= '<a href="'. esc_url( $button_href ) .'" target="'. $button_target .'" title="'. $button_title .'">'. $button_text .'</a>';
	$data_button .= '</div>';

}

?>
<div class="<?php echo implode( ' ', $wrap_class ); ?>">

	<?php switch ( $layout ) {
		case '2':
			echo $data_img;
			echo $data_title;
			echo $data_desc;
			echo $data_button;
		break;
		case '3':
			echo $data_icon;
			echo '<div class="box-right">';
			echo $data_title;
			echo $data_desc;
			echo '</div>';
		break;
		case '4':
			echo $data_img;
			echo '<div class="box-right">';
			echo $data_position;
			echo $data_title;
			echo $data_desc;
			echo $data_button;
			echo '</div>';
		break;
		case '5':
			echo $data_position;
			echo $data_title;
			echo $data_desc;
			echo $data_button;
		break;
		default:
			echo $data_icon;
			echo $data_title;
			echo $data_desc;
			echo $data_button;
		break;
	} ?>

</div>

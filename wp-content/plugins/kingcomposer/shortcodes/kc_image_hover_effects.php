<?php
$image = $event_click = $custom_link = $title = $button_text = $button_link = $caption_animation = $icon = $custom_class = $img_url = $link_url = $link_title = $link_target = $before_url = $after_url = $data_img = $data_title = $data_desc = $data_button = '';
$img_size = '1170x700xct';
$layout = 1;

$wrap_class	= apply_filters( 'kc-el-class', $atts );

extract( $atts );

$wrap_class[] = 'kc-image-hover-effects kc-img-effects-' . $layout . ' ' . esc_attr( $caption_animation );
if ( !empty( $custom_class ) )
	$wrap_class[] = $custom_class;


if ( !empty( $image ) ) {
	$img_link = wp_get_attachment_image_src( $image, 'full' );
	$img_full = $img_link[0];
	if ( $img_size == 'full' ) {
		$img_link = $img_link[0];
	} else {
		$img_link = kc_tools::createImageSize( $img_link[0], $img_size );
	}
} else {
	$img_link = KC_URL."/assets/images/get_start_m.jpg";
	$img_full = KC_URL."/assets/images/get_start_m.jpg";
}

if ( !empty( $custom_link ) ) {
	$img_arr = explode( "|", $custom_link );

	if ( !empty( $img_arr[0] ) ) {
		$img_url = $img_arr[0];
	} else {
		$img_url = '#';
	}

} else {
	$img_url = '#';
}

if ( !empty( $button_link ) ) {
	$link_arr = explode( "|", $button_link );

	if ( !empty( $link_arr[0] ) ) {
		$link_url = $link_arr[0];
	} else {
		$link_url = '#';
	}

	if ( !empty( $link_arr[1] ) )
		$link_title = $link_arr[1];

	if ( !empty( $link_arr[2] ) )
		$link_target = $link_arr[2];

} else {
	$link_url = '#';
}

$button_attr = array();
$button_attr[] = 'href="'. esc_url( $link_url ) .'"';
if ( !empty( $link_title ) )
	$button_attr[] = 'title="'. esc_attr( $link_title ) .'"';
if ( !empty( $link_target ) )
	$button_attr[] = 'target="'. esc_attr( $link_target ) .'"';

switch ( $event_click ) {
	case 'none':
		$data_img = '<figure><img src="'. esc_url( $img_link ) .'" alt=""/></figure>';
	break;
	case 'custom_link':
		$data_img = '<a href="'. esc_url( $img_url ) .'"><img src="'. esc_url( $img_link ) .'" alt=""/></a>';
		$before_url = '<a href="'. esc_url( $img_url ) .'">';
		$after_url	= '</a>';
	break;
	default:
		$data_img = '<a href="'. esc_url( $img_full ) .'" rel="prettyPhoto" class="kc-pretty-photo"><img src="'. esc_url( $img_link ) .'" alt=""/></a>';
		$before_url = '<a href="'. esc_url( $img_full ) .'" rel="prettyPhoto" class="kc-pretty-photo">';
		$after_url	= '</a>';
		wp_enqueue_script('prettyPhoto');
		wp_enqueue_style( 'prettyPhoto');
	break;
}

if ( !empty( $title ) ) {
	$data_title = '<div class="content-title">'. $title .'</div>';
}

if ( !empty( $desc ) ) {
	$data_desc = '<div class="content-desc">'. $desc .'</div>';
}

if ( !empty( $button_text ) ) {
	$data_button = '<div class="content-button"><a '. implode( ' ', $button_attr ) .'>'. $button_text .'</a></div>';
}

?>

<div class="<?php echo esc_attr( implode(' ', $wrap_class) ); ?>">

	<?php switch ( $layout ) {
		case '2':
			echo $data_img;
			echo $before_url;
			echo '<div class="overlay-effects">';
				echo $data_title;
				echo $data_desc;
			echo "</div>";
			echo $after_url;
		break;
		case '3':
			echo $data_img;
			echo $before_url;
			echo '<div class="overlay-effects">';
				echo '<div class="overlay-content">';
					echo $data_title;
					echo $data_desc;
				echo "</div>";
			echo "</div>";
			echo $after_url;
		break;
		case '4':
			echo $data_img;
			echo '<div class="overlay-effects">';
				echo $data_title;
				echo $data_desc;
				echo $data_button;
			echo "</div>";
		break;
		case '5':
			echo $data_img;
			echo $before_url;
			echo '<div class="overlay-effects">';
				echo '<i class='.$icon.'></i>';
			echo "</div>";
			echo $after_url;
		break;
		default:
			echo $data_img;
			echo $before_url;
			echo '<div class="overlay-effects">';
				echo $data_title;
			echo "</div>";
			echo $after_url;
		break;
	} ?>

</div>
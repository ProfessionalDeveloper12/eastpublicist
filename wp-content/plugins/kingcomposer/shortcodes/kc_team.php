<?php
$title = $subtitle = $desc = $image = $custom_class = $data_img = $data_title = $data_desc = $data_subtitle = $img_size = $data_socials = $socials = $data_button = '';
$layout = 1;
$size_array = array('full', 'medium', 'large', 'thumbnail');
$wrap_class	= apply_filters( 'kc-el-class', $atts );

extract( $atts );

$wrap_class[] = 'kc-team';
$wrap_class[] = 'kc-team-' . $layout;

if ( $custom_class != '' )
	$wrap_class[] = $custom_class;

if ( !empty( $image ) ) {
	
	if( in_array( $img_size, $size_array ) ){
		$image_data       = wp_get_attachment_image_src( $image, $img_size );
		$img_link        = $image_data[0];
	}else{
		$image_full_width = wp_get_attachment_image_src( $image, 'full' );
		$img_link 	= kc_tools::createImageSize( $image_full_width[0], $img_size );
	}

	$data_img .= '<figure class="content-image">';
		$data_img .= '<img src="'. $img_link .'" alt="">';
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

if ( !empty( $subtitle ) ) {

	$data_subtitle .= '<div class="content-subtitle">';
		$data_subtitle .= $subtitle;
	$data_subtitle .= '</div>';

}

if ( $show_button == 'yes' ) {

	if ( !empty( $button_link ) ) {
		$button_link_text = explode( '|', $button_link );
		$button_link = $button_link_text[0];
	}

	if( empty($button_text) )
		$button_text = __( 'Readmore', 'kingcomposer' );

	$data_button .= '<div class="content-button">';
		$data_button .= '<a href="'. $button_link .'">'. $button_text .'</a>';
	$data_button .= '</div>';

}

$social_list = array(
	'facebook' => 'facebook-f',
	'twitter' => 'twitter',
	'google_plus' => 'google_plus-g',
	'linkedin' => 'linkedin-in',
	'pinterest' => 'pinterest',
	'flickr' => 'flickr',
	'instagram' => 'instagram',
	'dribbble' => 'dribbble',
	'reddit' => 'reddit-square',
	'email' => 'envelope',
	);

foreach( $social_list as $acc => $icon ){

	if( !empty( $atts[$acc]) && $atts[$acc] != '__empty__' ){

		$icon = str_replace( array('_', 'email') , array( '-', 'envelope') , $icon);

		if ($icon == 'envelope')
			$data_socials .= '<a href="' . $atts[$acc] . '" target="_blank"><i class="fa-' . $icon . '"></i></a>';
		else
		$data_socials .= '<a href="' . $atts[$acc] . '" target="_blank"><i class="fab-' . $icon . '"></i></a>';
	
	}

}

if( !empty( $data_socials) )
	$data_socials = '<div class="content-socials">' . $data_socials . '</div>';

?>

<div class="<?php echo implode( ' ', $wrap_class ); ?>">

	<?php switch ( $layout ) {
		case '2':
			echo $data_img;
			echo '<div class="box-right">';
			echo $data_title;
			echo $data_subtitle;
			echo $data_desc;
			echo $data_socials;
			echo '</div>';
		break;
		case '3':
			echo $data_img;
			echo '<div class="overlay">';
			echo $data_title;
			echo $data_subtitle;
			echo $data_desc;
			echo $data_button;
			echo $data_socials;
			echo '</div>';
		break;
		default:
			echo $data_img;
			echo $data_title;
			echo $data_subtitle;
			echo $data_desc;
			echo $data_socials;
			echo $data_button;
		break;
	} ?>

</div>

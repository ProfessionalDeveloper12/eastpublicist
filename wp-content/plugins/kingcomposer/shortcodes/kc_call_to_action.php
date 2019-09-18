<?php
$title = $desc = $button_show = $button_text = $button_link = $link_url = $link_title = $link_target = $icon_show = $icon = $custom_class = $data_text = $data_button = '';
$layout = 2;

$main_class = apply_filters( 'kc-el-class', $atts );

extract( $atts );

$main_class[] = 'kc-call-to-action';
$main_class[] = 'kc-cta-' . $layout;

if ( $button_show == 'yes' )
	$main_class[] = 'kc-is-button';

if ( !empty( $custom_class ) )
	$main_class[] = $custom_class;

if ( !empty( $title ) || !empty( $desc ) ) {

	$data_text .= '<div class="kc-cta-desc">';
		if ( !empty( $title ) ) {
			$data_text .= '<h2>'. $title .'</h2>';
		}
		if ( !empty( $desc ) ) {
			$data_text .= '<div class="kc-cta-text">'. $desc .'</div>';
		}
	$data_text .= '</div>';

}

if ( $button_show == 'yes' && !empty( $button_text ) ) {

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

	if ( $icon_show == 'yes' ) {
		$button_text .= ' <span class="kc-cta-icon"><i class="'. $icon .'"></i></span>';
	}

	$button_attr = array();
	$button_attr[] = 'href="'. esc_url( $link_url ) .'"';
	if ( !empty( $link_title ) )
		$button_attr[] = 'title="'. esc_attr( $link_title ) .'"';
	if ( !empty( $link_target ) )
		$button_attr[] = 'target="'. esc_attr( $link_target ) .'"';

	$data_button .= '<div class="kc-cta-button">';
		$data_button .= '<a '. implode( ' ', $button_attr ) .'>'. $button_text .'</a>';
	$data_button .= '</div>';

}

?>

<div class="<?php echo implode( " ", $main_class ); ?>">

	<?php
		echo $data_text;
		echo $data_button;
	?>

</div>

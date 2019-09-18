<?php
$style = $title = $icon_show = $icon = $icon_txt = $icon_float = $link = $link_url = $link_title = $link_target = $custom_class = '';

$main_class = apply_filters( 'kc-el-class', $atts );

extract( $atts );

$main_class[] = 'kc-pro-button';
$main_class[] = 'kc-button-' . $style;

if ( !empty( $custom_class ) ) {
	$main_class[] = $custom_class;
}

if ( !empty( $link ) ) {
	$link_arr = explode( "|", $link );

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

if ( $icon_show == 'yes' ) {
	$icon_txt = ' <i class="'. $icon .'"></i> ';
}

?>

<div class="<?php echo esc_attr( implode( " ", $main_class ) ); ?>">
	<a <?php echo implode( ' ', $button_attr ); ?>>
		<?php
			if ( $icon_show == 'yes' && $icon_float == 'before' )
				echo '<span class="creative_icon creative_icon_left">'. $icon_txt .' </span>';

			echo '<span class="creative_title">'. esc_html( $title ) .'</span>';

			if ( $icon_show == 'yes' && $icon_float == 'after' )
				echo ' <span class="creative_icon creative_icon_right">'. $icon_txt .'</span>';
		?>
	</a>
</div>

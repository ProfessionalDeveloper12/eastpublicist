<?php
$front_data			= $back_data = $show_icon = $icon = $title = $description = $show_button = $text_on_button = $link = $direction = $wrap_class = $b_show_icon = $b_icon = $b_title = $b_description = $b_show_button = $b_text_on_button = $b_link = $button_href = $button_target = $button_title = '';
$element_atttribute = array();
$el_classess		= apply_filters( 'kc-el-class', $atts );

extract( $atts );

$el_classess = array_merge( $el_classess, array(
	'kc-flipbox',
	'kc-flip-container'
));

if ( !empty( $wrap_class ) )
	$el_classess[] = $wrap_class;

if( isset( $direction ) && $direction == 'vertical' )
	$el_classess[] 		= 'flip-' . $direction;

$element_atttribute[] 	= 'class="'. esc_attr( implode(' ', $el_classess ) ) .'"';
$element_atttribute[] 	= 'ontouchstart="this.classList.toggle(\'hover\');"';

// Front Side Data
if( $show_icon == 'yes' && !empty( $icon ) )
	$front_data .= '<div class="wrap-icon"><i class="'. esc_attr( $icon ) .'"></i></div>';

if( !empty( $title ) )
	$front_data .= '<h3>'. esc_html( $title ) .'</h3>';

if(!empty($description))
	$front_data .= '<p>'. do_shortcode( $description ) .'</p>';

// Back Side Data
if( $b_show_icon == 'yes' && !empty( $b_icon ) )
	$back_data .= '<div class="wrap-icon"><i class="'. esc_attr( $b_icon ) .'"></i></div>';

if( !empty( $b_title ) )
	$back_data .= '<h3>'. esc_html( $b_title ) .'</h3>';

if(!empty($b_description))
	$back_data .= '<p>'. do_shortcode( $b_description ) .'</p>';

if( $b_show_button == 'yes' ){

	if ( empty( $b_text_on_button ) )
		$b_text_on_button = __( 'Read more', 'kingcomposer' );
	
	$b_link	= ( '||' === $b_link ) ? '' : $b_link;

	$button_link	= kc_parse_link($b_link);

	if ( strlen( $button_link['url'] ) > 0 ) {
		$button_href 	= $button_link['url'];
		$button_title 	= $button_link['title'];
		$button_target 	= strlen( $button_link['target'] ) > 0 ? $button_link['target'] : '_self';
	}
	
	$back_data .= '<a class="button" href="'. esc_url( $button_href ) .'" target="'. $button_target .'" title="'. $button_title .'">'. $b_text_on_button .'</a>';


}

?>

<div <?php echo implode( ' ', $element_atttribute ); ?>>
	<div class="flipper">
		<div class="front">
			<div class="front-content">
				<?php echo $front_data; ?>
			</div>
		</div>
		<div class="back">
			<div class="des">
				<?php echo $back_data; ?>
			</div>
		</div>
	</div>
</div>

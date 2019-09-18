<?php

$_before_number = $_after_number = $icon_show = $wrap_class = '';
$el_classess 	= apply_filters( 'kc-el-class', $atts );
$atts 			= kc_remove_empty_code( $atts );
$el_classess[] 	= 'kc_counter_box';

extract( $atts );



if ( !empty( $wrap_class ) )
	$el_classess[] = $wrap_class;

$label = ( !empty($label) ) ? '<h4>'. esc_html( $label ) .'</h4>' : '';

if( $icon_show == 'yes' ) {
	$icon = !empty( $icon ) ? $icon : 'fa-leaf';
	$icon = ( !empty( $icon ) ) ? '<i class="'. esc_html($icon).' element-icon"></i>' : '';
} else {
	$icon = '';
}

if( !empty( $label_above ) && 'yes' === $label_above ){

	$_before_number = $icon . $label;

} else {

	$_before_number = $icon;
	$_after_number = $label;
	
}

?>

<div class="<?php echo esc_attr( implode( " ", $el_classess ) ) ?>">
	<?php echo $_before_number; ?>
	<span class="counterup"><?php echo esc_html( $number ); ?></span>
	<?php echo $_after_number; ?>
</div>

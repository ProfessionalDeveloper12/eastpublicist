<?php
$desc = $custom_class = '';

$wrap_class	= apply_filters( 'kc-el-class', $atts );

extract( $atts );

$wrap_class[] = 'kc-dropcaps';

if ( !empty( $custom_class ) )
	$wrap_class[] = $custom_class;

$check = trim(strip_tags($desc));

if(  !empty( $check ) ){
	$ch = mb_substr($check, 0,1);
	$pos = strpos($desc, $ch);
	$str_re = '<span class="dropcaps-text">' . $ch .'</span>';
	$desc = substr_replace($desc, $str_re, $pos, $pos+1);
}
else
	$desc = __('Dropcap: Text not found', 'kingcomposer');


?>
<div class="<?php echo esc_attr( implode( " ", $wrap_class) ); ?>">
	<?php echo $desc; ?>
</div>

<?php
	
$output 			= $template = $custom_css = $css = '';
$timer_style 		= 1;
$element_attribute 	= array();
$wrp_class 			= apply_filters( 'kc-el-class', $atts );

extract($atts);

wp_enqueue_script( 'countdown-timer' );

switch ( $timer_style ) {

	case '1':
	case '2':
		$template = '<span class="countdown-style'. esc_attr( $timer_style ) .'">
			<span class="group">
				<span class="timer days">%D</span>
				<span class="unit">days</span>
			</span>
			<span class="group">
				<span class="timer seconds">%H</span>
				<span class="unit">hours</span>
			</span>
			<span class="group">
				<span class="timer seconds">%M</span>
				<span class="unit">minutes</span>
			</span>
			<span class="group">
				<span class="timer seconds">%S</span>
				<span class="unit">seconds</span>
			</span>
		</span>';
	
	break;

	case '3':
	
		if( !empty( $custom_template ) ){

			$template = $custom_template;

		}else{

			$template = '%D days %H:%M:%S';

		}

	break;
}

$el_class =	array(
	'kc-countdown-timer',
	$wrap_class,
);

$datetime = (!empty($datetime) && $datetime !== '__empty__')?$datetime:date("D M d Y", strtotime("+1 week"));
$datetime = date("Y/m/d", strtotime($datetime));

$countdown_data = array(
	'date' 		=> $datetime,
	'template' 	=> trim( preg_replace( '/\s\s+/', ' ', $template ))
);

$element_attribute[] = 'class="'. esc_attr( implode(' ', $el_class ) ) .'"';
$element_attribute[] = 'data-countdown=\''.json_encode($countdown_data).'\'';

?>
<div class="<?php echo implode(' ', $wrp_class );?>">
<?php

if( !empty( $title ) ){
	
?>

<h3><?php echo esc_attr( $title ) ;?></h3>

<?php
}

?>

<div <?php echo implode(' ', $element_attribute ) ;?>></div>
</div>

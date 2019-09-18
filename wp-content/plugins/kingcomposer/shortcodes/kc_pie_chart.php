<?php
$output = $_class = $wrap_class = $auto_width =  $percent = $size = $linewidth = $icon = '';

extract( $atts );

$custom_size 	= !empty($custom_size) ? $custom_size : 120;
$custom_size 	= intval($custom_size);
$barcolor    	= (!empty($barcolor)) ? $barcolor     : '#39c14f';
$trackcolor  	= (!empty($trackcolor)) ? $trackcolor : '#e4e4e4';
$linewidth  	= (!empty($linewidth)) ? $linewidth : '10';
$size 			= ('custom' === $size && !empty( $size )) ? $custom_size : $size;

$element_attributes = array();
$custom_class 		= apply_filters( 'kc-el-class', $atts );
$custom_class[] 		= $wrap_class;

$custom_class 		= implode( ' ', $custom_class );
$css_classes 		= array( 'kc_shortcode', 'kc_piechart',);


if( empty( $size ) )
	$size = $custom_size;

if( !empty( $auto_width ) )
{
	$auto_width    = 'yes';
	$css_classes[] = 'auto_width';
	$css_classes[] = 'auto_width';
}


$element_attributes[] = 'data-size="' .esc_attr(intval($size)). '"';
$element_attributes[] = 'data-percent="' .esc_attr( $percent ). '"';
$element_attributes[] = 'data-linecap="' .esc_attr( $rounded_corners_bar ). '"';
$element_attributes[] = 'data-barcolor="' .esc_attr( $barcolor ). '"';
$element_attributes[] = 'data-trackcolor="' .esc_attr( $trackcolor ). '"';
$element_attributes[] = 'data-autowidth="' .esc_attr( $auto_width ). '"';
$element_attributes[] = 'data-linewidth="' .esc_attr( $linewidth ). '"';

$css_class            = implode(' ', $css_classes);
$element_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';

?><div class="kc-pie-chart-wrapper <?php echo esc_attr( $custom_class ); ?>">
	<div class="kc-pie-chart-holder">
		<span <?php echo implode( ' ', $element_attributes ); ?>>
			<span class="pie_chart_percent">
				<?php if( $icon_option == 'yes' ): ?>
					<i class="<?php echo $icon;?> pie_chart_icon"></i>
				<?php endif;?>
				<span class="percent"></span>
			</span>
		</span>
	</div>
</div>

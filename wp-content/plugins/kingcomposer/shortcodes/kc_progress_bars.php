<?php

$output = $_class = $css = $wrap_class = $value_color_style = $radius = $speed ='';

extract($atts);

$progress_bar_color_default = '#999999';

$element_attributes = array();

$el_classes = apply_filters( 'kc-el-class', $atts );

$el_classes = array_merge($el_classes, array(
	'kc_shortcode',
	'kc_progress_bars',
	$wrap_class,
));

$style = isset($atts['style']) ? $atts['style'] : 1;

if( isset( $atts['speed'] ) && !empty( $atts['speed'] ) )
	$speed = $atts['speed'];
else
	$speed = 2000;

$options = $atts['options'];

$element_attributes[] = 'class="'. esc_attr( implode(' ', $el_classes ) ) .'"';
$element_attributes[] = 'data-style="'. esc_attr($style) .'"';

$output .= '<div '. implode(' ', $element_attributes) .'>';

if( isset( $options ) ){

	foreach( $options as $option ){
        $prob_style = '';
		$value = !empty($option->value) ? $option->value : 50;
		$label = !empty($option->label) ? $option->label : 'Label default';

		$prob_color = !empty($option->prob_color) ? $option->prob_color : '';

        if( $prob_color != '')
		    $prob_style = 'background-color: '.$prob_color.';';

		$prob_style .= 'width: '.$value.'%';
		
		$value_color_style = '';
		if( !empty($option->value_color) ){
			$value_color_style = ' style="color: '. esc_attr($option->value_color) .'"';
		}


		$prob_track_attributes = array();
		$prob_attributes = array();

		//Progress bars track attributes
		$prob_track_css_classes = array(
			'kc-ui-progress-bar',
			'kc-ui-progress-bar'.$style,
			'kc-progress-bar',
			'kc-ui-container',
		);

		$prob_track_css_class = implode(' ', $prob_track_css_classes);
		$prob_track_attributes[] = 'class="' . esc_attr( trim( $prob_track_css_class ) ) . '"';

		//Progress bars attributes
		$prob_css_classes = array(
			'kc-ui-progress',
			'kc-ui-progress'.$style
		);

		$prob_css_class = implode(' ', $prob_css_classes);
		$prob_attributes[] = 'class="' . esc_attr( trim( $prob_css_class ) ) . '"';
		$prob_attributes[] = 'style="'. esc_attr($prob_style) .'"';

		$output .= '<div class="progress-item">';

		$output .= '<span class="label">'. esc_html( $label ) .'</span>';
		$output .= '<div '. implode( ' ', $prob_track_attributes ) .'>';
			$output .= '<div '. implode( ' ', $prob_attributes ) .' data-value="'. esc_html( $value ) .'" data-speed="'. esc_html( $speed ) .'">';
				$output .= '<div class="ui-label">
					<span class="value"'. $value_color_style .'>'. esc_html( $value ) .'%</span>
				</div>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '</div>';

	}
}

$output .= '</div>';

echo $output;

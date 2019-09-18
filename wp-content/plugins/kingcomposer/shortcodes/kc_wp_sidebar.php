<?php

$name = '';
$kc_el_class = apply_filters( 'kc-el-class', $atts );

extract( $atts );

$kc_el_class[] = 'widget-area kc-wp-sidebar';

echo '<div class="'.esc_attr(implode(' ', $kc_el_class)).'">';
	
	if (empty($name))
	{
		echo '<h3 class="kc-error">'.
				__('Please select a sidebar to display','kingcomposer').
			'</h3>';
	}
	if (is_active_sidebar($name))
	{
		dynamic_sidebar($name);
	}
	else
	{
		echo '<h3 class="kc-error">'.
				'<strong>'.esc_html($name).'</strong>: '.
				__('The sidebar does not exist or inactive','kingcomposer').
			'</h3>';
	}
	
echo '</div>';
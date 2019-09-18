<?php

$output = $class = $custom_class = $icons = '';

extract( $atts );

$css_class 		= apply_filters( 'kc-el-class', $atts );
$css_class[] 	= 'kc-multi-icons-wrapper';

if ( !empty( $custom_class ) )
	$css_class[] = $custom_class;

?>
<div class="<?php echo esc_attr( implode( " ", $css_class ) ); ?>">
	<?php
	foreach( $icons as $item ):
		$link_att       = array();
		$icon           = $item->icon;
		$label          = $item->label;
		$link           = $item->link;		
		$color          = isset($item->color) ? $item->color : '';
		$bg_color       = isset($item->bg_color) ? $item->bg_color : '';
		
		if( empty( $icon ) )
			$icon = 'fa-leaf';
		
		$link     = ( '||' === $link ) ? '' : $link;
		$link     = kc_parse_link( $link );
		$link_att = array();
		$icon_att = array();
		
		$link_target    = '_blank';
		$link_url       = '#';
		$link_title     = $label;
		
		if ( strlen( $link['url'] ) > 0 ) {
			$link_target    = strlen( $link['target'] ) > 0 ? $link['target'] : '_self';
			$link_url       = esc_attr( $link['url'] );
			$link_title     = esc_attr( $link['title'] );
		}
		
		$link_att[] = 'href="' . esc_attr( $link_url ) . '"';
		$link_att[] = 'target="' . esc_attr( $link_target ) . '"';
		$link_att[] = 'title="' . esc_attr( $link_title ) . '"';
		
		$link_att[] = 'class="multi-icons-link multi-icons' . $icon . '"';
		
		$style = '';
		
		
		
		if( !empty( $bg_color ))
			$link_att[] = 'style="background-color:' . $bg_color .';"';
		
		$class_icon = array( $icon );
		
		$icon_att[] = 'class="' . esc_attr( implode( " ", $class_icon ) ) . '"';
		
		if( !empty( $color ))
			$icon_att[] = 'style="color:' . $color .';"';
		
	?>
		<a <?php echo implode(' ', $link_att); ?>>
			<i <?php echo implode(' ', $icon_att); ?>></i>
		</a>

	<?php
	endforeach;
	?>
</div>

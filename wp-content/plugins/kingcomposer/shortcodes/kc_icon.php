<?php

$output = $icon = $class = $icon_wrap_class = $link = $use_link = '';
$has_link = false;
extract( $atts );

$css_class 		= apply_filters( 'kc-el-class', $atts );
$css_class[] 	= 'kc-icon-wrapper';

if ( !empty( $icon_wrap_class ) )
	$css_class[] = $icon_wrap_class;

if( empty( $icon ) )
	$icon = 'fa-leaf';

$class_icon = array( $icon );

if ( !empty( $class ) )
	$class_icon[] = $class;

if( $use_link == 'yes') {
	
	$link     = ( '||' === $link ) ? '' : $link;
	$link     = kc_parse_link( $link );
	$link_att = array();
	
	if ( strlen( $link['url'] ) > 0 ) {
		$has_link = true;
		$a_target = strlen( $link['target'] ) > 0 ? $link['target'] : '_self';
		
		$link_att[] = 'href="' . esc_attr( $link['url'] ) . '"';
		$link_att[] = 'target="' . esc_attr( $a_target ) . '"';
		$link_att[] = 'title="' . esc_attr( $link['title'] ) . '"';
	}
}
?>
<div class="<?php echo esc_attr( implode( " ", $css_class ) ); ?>">
	<?php if( $has_link ) :?>
	<a <?php echo implode(' ', $link_att); ?>>
	<?php endif;?>
	<i class="<?php echo esc_attr( implode( " ", $class_icon ) ) ?>"></i>
	<?php if( $has_link ) :?>
	</a>
	<?php endif;?>
</div>

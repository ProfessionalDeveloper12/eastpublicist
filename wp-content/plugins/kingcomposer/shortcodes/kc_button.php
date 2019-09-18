<?php

$text_title 	= $textbutton = $show_icon = $icon = $icon_position = $ex_class = $wrap_class = '';
$icon_position	= 'right';
$wrapper_class	= apply_filters( 'kc-el-class', $atts );
$button_attr 	= array();

extract( $atts );

$link	= ( '||' === $link ) ? '' : $link;
$link	= kc_parse_link($link);

if ( strlen( $link['url'] ) > 0 ) {
	$a_href 	= $link['url'];
	$a_title 	= $link['title'];
	$a_target 	= strlen( $link['target'] ) > 0 ? $link['target'] : '_self';
}

if( !isset( $a_href ) )
	$a_href = "#";

if ( !empty( $wrap_class ) )
	$wrapper_class[] = $wrap_class;

$el_class = array( 'kc_button');
if ( !empty( $ex_class ) )
	$el_class[] = $ex_class;

if( isset( $el_class ) )
	$button_attr[] = 'class="'. esc_attr( implode(' ', $el_class ) ) .'"';

if( isset( $a_href ) )
	$button_attr[] = 'href="'. esc_attr($a_href) .'"';

if( isset( $a_target ) )
	$button_attr[] = 'target="'. esc_attr($a_target) .'"';

if( isset( $a_title ) )
	$button_attr[] = 'title="'. esc_attr($a_title) .'"';

if( isset( $onclick ) )
	$button_attr[] = 'onclick="'. $onclick .'"';
?>

<div class="<?php echo implode( " ", $wrapper_class ); ?>">
	<a <?php echo implode(' ', $button_attr); ?>>
		<?php
			if ( $show_icon == 'yes' ) {

				if ( $icon_position == 'left' ) {
					echo '<i class="'. esc_attr( $icon ).'"></i> '. esc_html( $text_title ) ;
				} else {
					echo esc_html( $text_title ) . ' <i class="'. esc_attr( $icon ) .'"></i>';
				}

			} else {

				if ( !empty( $text_title ) )
					echo esc_html( $text_title );

			}
		?>
	</a>
</div>

<?php

$title = $icon = $show_button = $class = '';

extract( $atts );

$custom_style = '';

$wrap_class	= apply_filters( 'kc-el-class', $atts );

if ( !empty( $class ) )
	$wrap_class[] = $class;

?>
<div class="message-boxes <?php echo esc_attr( implode(' ', $wrap_class) ); ?>">
	<div class="message-box-wrap">
		<?php
		if ( !empty( $icon ) ) {
			echo '<i class="'. $icon .'"></i>';
		}
		if ( $show_button == 'yes' ) {
			echo '<button class="kc-close-but">'. esc_html__( 'close', 'kingcomposer' ) .'</button>';
		}
		echo $title;
		?>
	</div>
</div>

<?php

$style = $icon = $line_text = $class = '';

extract( $atts );

$wrap_class	= apply_filters( 'kc-el-class', $atts );
$wrap_class[] = 'divider_line';

if( $class != '')
	$wrap_class[] = $class;
?>

<div class="<?php echo esc_attr( implode(' ', $wrap_class ) );?>">
	<div class="divider_inner <?php echo 'divider_line' . $style;?>">
		<?php
			switch ( $style ) {
				case '2':
					if ( !empty( $icon ) )
						echo '<i class="'.esc_attr( $icon ).'"></i>';
				break;
				case '3':
					if ( !empty( $line_text ) )
						echo '<span class="line_text">'.$line_text.'</span>';
				break;
				default:
					# code...
				break;
			}
		?>
	</div>
</div>

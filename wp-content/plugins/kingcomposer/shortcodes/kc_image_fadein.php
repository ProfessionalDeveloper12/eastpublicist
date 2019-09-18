<?php

$title = $images = $transition = $delay = $force_size = $width = $height = $position = $wrap_class = '';
$wrapper_class	= apply_filters( 'kc-el-class', $atts );
extract( $atts );

$_images = array();
$classes = array( 'image_fadein' );
$attributes = array();

foreach( explode( ',', $images ) as $id ){

	$image = wp_get_attachment_image_src( $id, 'full' );

	if( isset( $image[0] ) && !empty( $image[0] ) ){

		if( isset( $force_size ) && $force_size == 'yes' ){

			$atts = array( '250', '250', 'c' );

			if( isset( $width ) && !empty( $width ) )
				$atts[0] = $width;
			if( isset( $height ) && !empty( $height ) )
				$atts[1] = $height;
			if( isset( $position ) && !empty( $position ) )
				$atts[2] = $position;

			$_images[] = kc_tools::createImageSize( $image[0], implode( 'x', $atts ) );

		}else $_images[] = $image[0];

	}

}

if( isset( $transition ) && !empty( $transition ) ){
	$classes[] = esc_attr( $transition );
}

if( isset( $delay ) && !empty( $delay ) ){
	$attributes[] = 'data-delay="'.esc_attr( $delay ).'"';
}

$attributes[] = 'class="'.implode( ' ', $classes ).'"';
$attributes[] = 'data-images="'. $images .'"';

?>


<div class="image_fadein_slider <?php echo implode(' ', $wrapper_class );?>">
	<?php
		if( !empty( $title ) )
			echo '<h3>'.esc_html( $title ).'</h3>';
	?>
	<div <?php echo implode( ' ', $attributes ); ?>>
	<?php

		if( !isset( $_images[0] ) || empty( $_images[0] ) ){

			echo '<h3>'. __('Images Gallery: No images found', 'kingcomposer' ) .'</h3>';

		} else {

			foreach( $_images as $image ){
				echo '<img src="'. esc_url( $image ) .'" />';
			}

		}
	?>
	</div>
</div>

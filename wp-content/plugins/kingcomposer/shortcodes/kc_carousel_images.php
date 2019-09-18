<?php

$output 	= $thumb_data = $speed = $items_tablet = $mobile = '';
$img_size 	= 'full';
$onclick 	= 'none';
$wrp_class 	= apply_filters( 'kc-el-class', $atts );

extract( $atts );

$items_number 	= ( !empty( $items_number ) ) ? $items_number : 4;
$tablet 	= ( !empty( $tablet ) ) ? $tablet : 2;
$mobile 	= ( !empty( $mobile ) ) ? $mobile : 1;

if( !empty( $images ) )
	$images 	= explode( ',', $images );

if ( is_array( $images ) && !empty( $images ) ) {

	foreach( $images as $image_id ){

		$attachment_data[] 		= wp_get_attachment_image_src( $image_id, $img_size );
		$attachment_data_full[] = wp_get_attachment_image_src( $image_id, 'full' );

	}

}else{

	echo '<div class="kc-carousel_images align-center" style="border:1px dashed #ccc;"><br /><h3>Carousel Images: '.__( 'No images upload', 'kingcomposer' ).'</h3></div>';
	return;

}

$element_attribute = array();

$el_classes = array(
	'kc-carousel-images',
	'owl-carousel-images',
	'kc-sync1',
	$wrap_class
);

if( isset($atts['nav_style']) && $nav_style !='' ){
	$el_classes[] = 'owl-nav-' . $nav_style;
}


$owl_option = array(
	'items' 		=> $items_number,
	'tablet' 	=> $tablet,
	'mobile' 	=> $mobile,
	'speed' 		=> $speed,
	'navigation' 	=> $navigation,
	'pagination' 	=> $pagination,
	'autoheight' 	=> $auto_height,
	'progressbar' 	=> $progress_bar,
	'delay' 		=> $delay,
	'autoplay' 		=> $auto_play,
	'showthumb'		=> $show_thumb,
	'num_thumb'		=> $num_thumb,
);

$owl_option 			= json_encode( $owl_option );
$element_attribute[] 	= 'class="'. esc_attr( implode( ' ', $el_classes ) ) .'"';
$element_attribute[] 	= "data-owl-i-options='$owl_option'";

if( 'custom_link' === $onclick && !empty( $custom_links ) ){

	$custom_links 		= preg_replace( '/\n$/', '', preg_replace('/^\n/','',preg_replace('/[\r\n]+/',"\n", $custom_links)) );
	$custom_links_arr 	= explode("\n", $custom_links );

}

kc_js_callback( 'kc_front.owl_slider' );

?>
<div class="<?php echo implode( ' ', $wrp_class );?>">
	<div class="kc-carousel_images <?php echo implode( ' ', $wrp_class );?>">
	
		<div <?php echo implode( ' ', $element_attribute ); ?>>
	
		<?php
		$i = 0;
		foreach( $attachment_data as $i => $image ):
			$alttext = '';
			if( $alt_text == 'yes')
				$alttext = get_post_meta( $images[$i], '_wp_attachment_image_alt', true);
			
		?>
			<div class="item">
	
			<?php

			if( 'none' === $onclick ){
	
			?>
				<img src="<?php echo $image[0]; ?>"  alt="<?php echo esc_attr( $alttext );?>"/>
	
			<?php
	
			} else {
	
				switch( $onclick ){
	
					case 'lightbox':
	
						echo '<a class="kc-image-link kc-pretty-photo" data-lightbox="kc-lightbox" rel="prettyPhoto['. $atts['_id'] .']" href="'. esc_attr( esc_attr( $attachment_data_full[$i][0] ) ) .'">'
							.'<img src="'. esc_attr( $image[0] ) .'" alt="'. $alttext .'" /></a>';
						break;
	
					case 'custom_link':
	
						if( isset( $custom_links_arr[$i] ) ){
							echo '<a href="'. esc_attr( strip_tags( $custom_links_arr[$i] ) ) .'" target="'. esc_attr( $custom_links_target ) .'">'
								.'<img src="'. esc_attr( $image[0] ) .'" alt="'. esc_attr( $alttext ) .'" /></a>';
						}else{
							echo '<img src="'. esc_attr( $image[0] ) .'" alt="'. esc_attr( $alttext ) .'" />';
						}
	
						break;
	
				}
	
			}
	
			?>
	
			</div>
	
		<?php
			$i++;
			endforeach;
		?>
	
		</div>
	
		<?php
	
		if( !empty( $show_thumb ) && 'yes' === $show_thumb ){
	
		?>
	
		<div class="kc-sync2 owl-carousel">
			<?php foreach( $attachment_data as $image ): ?>
	
				<div class="item">
					<img src="<?php echo $image[0]; ?>" alt="" />
				</div>
	
			<?php endforeach; ?>
		</div>
	
		<?php
	
		} //end if show thumb
	
		?>
	</div>
</div>

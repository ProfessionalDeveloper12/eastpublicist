<?php
$output 		= $title = $wrap_class = $taxonomy = $thumbnail = $show_button = $css = '';
$readmore_text 	= __('Read more', 'kingcomposer');
$image_size 	= 'full';
$wrp_el_classes = apply_filters( 'kc-el-class', $atts );

extract($atts);

$orderby 		= isset( $order_by ) ? $order_by : 'ID';
$order 			= isset( $order_list ) ? $order_list : 'ASC';

$post_taxonomy_data = explode( ',', $post_taxonomy );
$taxonomy_term 	= array();
$post_type 		= 'post';

if( isset($post_taxonomy_data) ){

	foreach( $post_taxonomy_data as $post_taxonomy ){

		$post_taxonomy_tmp 	= explode( ':', $post_taxonomy );
		$post_type 			= $post_taxonomy_tmp[0];

		if( isset( $post_taxonomy_tmp[1] ) )
			$taxonomy_term[] = $post_taxonomy_tmp[1];

	}

}

$taxonomy_objects 		= get_object_taxonomies( $post_type, 'objects' );
$taxonomy 				= key( $taxonomy_objects );

$args = array(
	'post_type' 		=> $post_type,
	'posts_per_page' 	=> $number_post,
	'orderby'        	=> $orderby,
	'order' 			=> $order,
);

if( count( $taxonomy_term ) )
{

	$tax_query = array(
		'relation' => 'OR'
	);

	foreach( $taxonomy_term as $term ){

		$tax_query[] = array(
			'taxonomy' => $taxonomy,
			'field'    => 'slug',
			'terms'    => $term,
		);

	}

	$args['tax_query'] = $tax_query;

}

$the_query = new WP_Query( $args );

$element_attribute = array();

$el_classess = array(
	'kc-owl-post-carousel',
	'owl-carousel',
	'list-'. $post_type,
	$taxonomy,
	$wrap_class
);

if( isset($atts['nav_style']) && $nav_style !='' ){
	$el_classess[] = 'owl-nav-' . $nav_style;
}

$owl_option = array(
	'items' 		=> $items_number,
	'mobile' 		=> $mobile,
	'tablet' 		=> $tablet,
	'speed' 		=> intval( $speed ),
	'navigation' 	=> $navigation,
	'pagination' 	=> $pagination,
	'autoheight' 	=> $auto_height,
	'autoplay' 		=> $auto_play
);

$owl_option = strtolower( json_encode( $owl_option ) );

$element_attribute[] = 'class="'. esc_attr( implode(' ', $el_classess) ) .'"';
$element_attribute[] = "data-owl-options='$owl_option'";

ob_start();

if ( $the_query->have_posts() ) {

	global $post;

	if( !empty($title) )
		echo '<h3 class="list-post-title">'. $title .'</h3>';

	echo '<div '. implode(' ', $element_attribute) .'>';

	while ( $the_query->have_posts() ) {

		$the_query->the_post();
		?>
		<div class="item list-item post-<?php echo esc_attr( $post->ID ); ?>">

			<div class="post-content">

				<?php

				$post_content = apply_filters('the_content', get_the_content() );

				if ( has_post_thumbnail($post->ID) && 'yes' === strtolower($thumbnail) ) {

					echo '<div class="image">';
					echo get_the_post_thumbnail($post->ID, $image_size);

					echo '<h3 class="caption"><a href="'. esc_attr( get_permalink( $post->ID ) ). '">'. get_the_title() .'</a></h3>';
					echo '</div>';

				}else{

					echo '<h3 class="title"><a href="'. esc_attr( get_permalink( $post->ID ) ). '">'. get_the_title() .'</a></h3>';

				}
				?>

				<?php
					if ( has_post_format( array( 'chat', 'status' ) ) )
						$format_prefix = _x( '%1$s on %2$s', '1: post format name. 2: date', 'twentythirteen' );
					else
						$format_prefix = '%2$s';

					$date = sprintf( '<span class="date"><time class="entry-date" datetime="%1$s">%2$s</time></span>',
						esc_attr( get_the_date( 'c' ) ),
						esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) )
					);

					if( !empty( $show_date ) && strtolower( $show_date ) == 'yes' )
						echo '<div class="kc-entry_meta">'. $date.'</div>';

				?>

				<div class="in-post-content"><?php echo wp_trim_words( $post_content, 25, ' ...' ); ?></div>
				<?php if( !empty($show_button) && strtolower($show_button) == 'yes' ): ?>
					<div class="footer-button">
						<a class="kc-read-more" href="<?php echo esc_attr( get_permalink( $post->ID ) ); ?>"><?php echo esc_html( $readmore_text ); ?></a>
					</div>
				<?php endif; ?>
			</div>

		</div>
		<?php
	}

	echo '</div>';

} else {

	echo __('Carousel Post: No posts found', 'kingcomposer');

}

wp_reset_postdata();

$output = ob_get_clean();

echo '<div class="kc-carousel-post '. esc_attr( implode(' ', $wrp_el_classes) ) .'">'. $output .'</div>';

kc_js_callback( 'kc_front.owl_slider' );

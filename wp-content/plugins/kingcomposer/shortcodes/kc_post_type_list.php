<?php

$output = $title = $wrap_class = $taxonomy = $css = '';

extract($atts);

if( !isset( $atts['post_taxonomy']))
    $post_taxonomy = 'post';

$el_classess = apply_filters( 'kc-el-class', $atts );

$orderby = isset($order_by) ? $order_by : 'ID';
$order = isset($order_list) ? $order_list : 'ASC';

$readmore_text = !empty($readmore_text)? $readmore_text : __('Read more', 'kingcomposer');

$post_taxonomy_data = explode( ',', $post_taxonomy );
$taxonomy_term = array();
$post_type = 'post';

if( isset($post_taxonomy_data) ){
	foreach( $post_taxonomy_data as $post_taxonomy ){
		$post_taxonomy_tmp = explode( ':', $post_taxonomy );
		$post_type = $post_taxonomy_tmp[0];

		if( isset($post_taxonomy_tmp[1]) ){
			$taxonomy_term[] = $post_taxonomy_tmp[1];
		}
	}
}

$taxonomy_objects = get_object_taxonomies( $post_type, 'objects' );
$taxonomy = key( $taxonomy_objects );

$args = array(
	'post_type' 		=> $post_type,
	'posts_per_page' 	=> $number_post,
	'orderby'        	=> $orderby,
	'order' 			=> $order,
);

if($orderby == 'rand') unset($args['order']);

if( count($taxonomy_term) )
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

$el_classess = array_merge($el_classess, array(
	'list-post-type',
	'list-'.$post_type,
	$taxonomy,
	$wrap_class
));

if( $css != '' )$el_classess[] = $css;

$element_attribute[] = 'class="'. esc_attr( implode(' ', $el_classess) ) .'"';

ob_start();

if ( $the_query->have_posts() ) {
	global $post;

	echo '<div '. implode(' ', $element_attribute) .'>';

	if( !empty($title) ){
		echo '<h3 class="list-post-title">'. esc_html($title) .'</h3>';
	}

	while ( $the_query->have_posts() ) {
		$the_query->the_post();
		?>
		<div class="list-item post-<?php echo esc_attr( $post->ID ); ?>">
			<div class="post-content">
				<figure>
					<?php
					if ( has_post_thumbnail() && $thumbnail === 'yes' ) {
						the_post_thumbnail($image_size);
					}
					?>
				</figure>
				<h3><a href="<?php echo esc_attr( get_permalink( $post->ID ) ); ?>"><?php the_title(); ?></a></h3>
				<div class="kc-entry_meta">
				<?php
					if ( has_post_format( array( 'chat', 'status' ) ) )
						$format_prefix = _x( '%1$s on %2$s', '1: post format name. 2: date', 'twentythirteen' );
					else
						$format_prefix = '%2$s';

					$date = sprintf( '<span class="date"><i class="sl-clock"></i> <time class="entry-date" datetime="%1$s">%2$s</time></span>',
						esc_attr( get_the_date( 'c' ) ),
						esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) )
					);

					if( !empty($show_date) && $show_date === 'yes' ){
						echo $date;
					}

					if ( 'post' == get_post_type() && $show_author == 'yes' ) {
						printf( '<span class="author vcard"><i class="sl-user"></i> <a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
							esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
							esc_attr( sprintf( __( 'View all posts by %s', 'kingcomposer' ), get_the_author() ) ),
							get_the_author()
						);
					}

					$categories_list = get_the_category_list( __( ', ', 'kingcomposer' ) );
					if ( $categories_list && $show_category == 'yes') {
						echo '<span class="categories-links"><i class="sl-folder"></i> ' . $categories_list . '</span>';
					}

					$tag_list = get_the_tag_list( '', __( ', ', 'kingcomposer' ) );
					if ( $tag_list && $show_tags == 'yes' ) {
						echo '<span class="tags-links"><i class="sl-tag"></i> ' . $tag_list . '</span>';
					}
				?>
				</div>
				<div class="text">
					<?php echo wp_trim_words( get_the_excerpt(), $number_word, ' ...' ); ?>
				</div>


				<?php if(!empty($show_button) && 'yes' === $show_button): ?>
					<a class="kc-read-more" href="<?php echo esc_attr( get_permalink( $post->ID ) ); ?>"><?php echo $readmore_text; ?></a>
				<?php endif; ?>
			</div>

		</div>
		<?php
	}
	echo '</div>';
} else {
	echo '<div '. implode(' ', $element_attribute) .'>';
	echo __('No posts found', 'kingcomposer');
	echo '</div>';
}

wp_reset_postdata();

$output = ob_get_clean();

echo $output;

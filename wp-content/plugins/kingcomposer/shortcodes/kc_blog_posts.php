<?php
$items = $number_item = $words = $show_date = $custom_class = $data_owl = $image_size = $force_image = $socials = $post_type = $image_align = '';
$layout = $i = 1;
$size_array = array('full', 'medium', 'large', 'thumbnail');
$show_readmore = true;
$readmore_text = __('Read more', 'kingcomposer');

extract( $atts );

if( $image_align == '')
	$image_align = 'both';

$post_taxonomy_data = explode( ',', $tax_term );

$taxonomy_term = array();
$post_type = 'post';

if( isset($post_taxonomy_data) ){
	$post_taxonomy_tmp = explode( ':', $post_taxonomy_data[0] );
	if( count($post_taxonomy_tmp) > 1 || !isset($post_taxonomy_tmp[1]))
		$post_type = $post_taxonomy_tmp[0];

	if( $post_type == 'post'){
		$atts['post_type']	= 'post';
		$atts['taxonomy']	= 'category';
		$atts['amount']	= $atts['items'];
		$atts['tax_term']	= str_replace( array('post:','post'), array('',''), $tax_term);
		$list_posts			= kc_tools::get_posts( $atts );

	}else{

		foreach( $post_taxonomy_data as $post_taxonomy ){
			$post_taxonomy_tmp = explode( ':', $post_taxonomy );

			if( isset($post_taxonomy_tmp[1]) ){
				$taxonomy_term[] = $post_taxonomy_tmp[1];
			}
		}

		$taxonomy_objects = get_object_taxonomies( $post_type, 'objects' );
		$taxonomy = key( $taxonomy_objects );

		if( $atts['items'] == 0 ){
			$atts['items'] = -1;
		}

		$args = array(
			'post_type' 		=> $post_type,
			'posts_per_page' 	=> $atts['items'],
			'order' 			=> $atts['order'],
		);

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

		$list_posts = $the_query->posts;
	}
}

$css_class			= apply_filters( 'kc-el-class', $atts );

//fix from version 2.6.10
if( !isset( $atts['show_author'] )) $atts['show_author'] = 'yes';
if( !isset( $atts['show_category'] )) $atts['show_category'] = 'yes';

$meta_data = ($atts['show_date'] == 'yes' || $atts['show_author'] == 'yes' || $atts['show_category'] == 'yes')? 'yes' : 'no';

$css_class[] = 'kc-blog-posts kc-blog-posts-' . $layout;
if ( !empty( $custom_class ) )
	$css_class[] = $custom_class;

switch ( $layout ) {
	case '1':
		$css_class[] 	= 'owl-carousel';
		$data_owl 		= ' data-owl-options=\'{"autoplay": "yes", "pagination": "yes", "items": "1", "tablet":1, "mobile":1}\'';
	break;
	case '3':
		$css_class[] = 'kc-blog-grid kc_blog_masonry';
	break;
	case '4':
		$css_class[] 	= 'owl-carousel';
		$data_owl 		= ' data-owl-options=\'{"autoplay": "yes", "pagination": "yes", "items": "3", "tablet":3, "mobile":1}\'';
	break;
	default:

	break;
}

$css_class[] = 'kc-image-align-' . $image_align;
?>

<div class="<?php echo esc_attr( implode( ' ', $css_class ) ) ;?>"<?php echo $data_owl; ?>>
	<?php if( count( $list_posts ) ): ?>

		<?php switch ( $layout ) {
		case '2':
			$i = 0;
			foreach ( $list_posts as $item ) :

				$img_url = '';
				//$item->post_content = $item->post_content;

				if ( has_post_thumbnail( $item->ID ) ) {
					$image_id   = get_post_thumbnail_id( $item->ID );
					$image_size = ! empty( $image_size ) ? $image_size : '543x304xct';

					if ( in_array( $image_size, $size_array ) ) {
						$image_data = wp_get_attachment_image_src( $image_id, $image_size );
						$img_url    = $image_data[0];
					} else {
						$image_full_width = wp_get_attachment_image_src( $image_id, 'full' );
						$img_url          = kc_tools::createImageSize( $image_full_width[0], $image_size );
					}
				}else{

					if( $force_image == 'yes'){
						$img =  kc_first_image( $item->post_content );
						if( $img != false ) $img_url = $img;
					}
				}


				if($image_align == 'left')
					$i=1;


				?>

				<div class="kc-list-item-2">

					<?php if ( $i % 2 == 1 ): ?>
						<?php if ( ! empty( $img_url ) ) : ?>
							<div class="post-item-left">
								<figure>
									<img src="<?php echo esc_url( $img_url ); ?>"
									     alt="<?php echo strip_tags(get_the_title( $item )); ?>">
								</figure>
							</div>
						<?php endif; ?>
						<div class="post-item-right">
							<div class="post_details">
								<h2 class="post-title-alt"><a
										href="<?php echo esc_url( get_permalink( $item->ID ) ); ?>"
										title="<?php echo strip_tags(get_the_title( $item )); ?>"><?php echo get_the_title( $item ); ?></a>
								</h2>

								<?php if ( $meta_data == 'yes'): ?>
									<div class="post-meta">
										<?php if( $atts['show_author'] == 'yes' ): ?>
											<span class="kc-post-author"><i class="fa fa-user"></i>  <a
												href="<?php echo get_author_posts_url( $item->post_author ); ?>"
												title="<?php esc_html_e( 'Posts by ', 'kingcomposer' );
												echo get_the_author_meta( 'display_name', $item->post_author ); ?>"
												rel="author"><?php echo get_the_author_meta( 'display_name', $item->post_author ); ?></a></span>
										<?php endif;?>
										<?php if( $atts['show_date'] == 'yes' ): ?>
											<span class="post-date"><i class="fa fa-clock-o"></i> <a
												href="<?php echo get_month_link( get_the_time( 'Y', $item->ID ), get_the_time( 'm', $item->ID ) ); ?>"><?php echo get_the_date( 'F j Y', $item->ID ); ?></a> </span>
										<?php endif;?>
										<?php if( $atts['show_category'] == 'yes' ): ?>
											<?php if ( get_the_category( $item->ID ) ): ?>
												<span class="post-cats"><i class="fa fa-folder-o"
												                           aria-hidden="true"></i> <?php the_category( ', ', '', $item->ID ); ?></span>
											<?php endif;?>
										<?php endif ?>
									</div>
								<?php endif ?>
							</div>
							<?php if ( $words > 0 ): ?>
								<p><?php echo wp_trim_words( $item->post_content, $words ); ?></p>
							<?php endif;
							if($show_readmore):
							?>
							<a href="<?php echo esc_url( get_permalink( $item->ID ) ); ?>"
							   class="kc-post-2-button"><?php esc_html_e( $readmore_text, 'kingcomposer' ); ?> <i
									class="fa fa-angle-right" aria-hidden="true"></i></a>
							<?php endif;?>
						</div>

					<?php else: ?>

						<div class="post-item-left">
							<div class="post_details">
								<h2 class="post-title-alt"><a
										href="<?php echo esc_url( get_permalink( $item->ID ) ); ?>"
										title="<?php echo strip_tags(get_the_title( $item )); ?>"><?php echo get_the_title( $item ); ?></a>
								</h2>
								<?php if ( $meta_data == 'yes'): ?>
									<div class="post-meta">
										<?php if ( $show_author == 'yes' ): ?>
											<span class="kc-post-author"><i class="fa fa-user"></i>  <a
												href="<?php echo get_author_posts_url( $item->post_author ); ?>"
												title="<?php esc_html_e( 'Posts by ', 'kingcomposer' );
												echo get_the_author_meta( 'display_name', $item->post_author ); ?>"
												rel="author"><?php echo get_the_author_meta( 'display_name', $item->post_author ); ?></a></span>
										<?php endif;?>
										<?php if ( $show_date == 'yes' ): ?>
											<span class="post-date"><i class="fa fa-clock-o"></i> <a
												href="<?php echo get_month_link( get_the_time( 'Y', $item->ID ), get_the_time( 'm', $item->ID ) ); ?>"><?php echo get_the_date( 'F j Y', $item->ID ); ?></a> </span>
										<?php endif;?>
										<?php if ( $show_category == 'yes' ): ?>
											<?php if ( get_the_category( $item->ID ) ): ?>
												<span class="post-cats"><i class="fa fa-folder-o"
											                           aria-hidden="true"></i> <?php the_category( ', ', '', $item->ID ); ?></span>
											<?php endif ?>
										<?php endif ?>
									</div>
								<?php endif ?>
							</div>
							<?php if ( $words > 0 ): ?>
								<p><?php echo wp_trim_words( $item->post_content, $words ); ?></p>
							<?php endif;
							if($show_readmore):
							?>
							<a href="<?php echo esc_url( get_permalink( $item->ID ) ); ?>"
							   class="kc-post-2-button"><?php esc_html_e( $readmore_text, 'kingcomposer' ); ?> <i
									class="fa fa-angle-right" aria-hidden="true"></i></a>
							<?php endif;?>
						</div>
						<div class="post-item-right">
							<figure>
								<img src="<?php echo esc_url( $img_url ); ?>"
								     alt="<?php echo strip_tags(get_the_title( $item )); ?>">
							</figure>
						</div>

					<?php endif ?>

				</div>

				<?php
				if($image_align == 'both')
					$i ++;

			endforeach;
			break;
		case '3':
			kc_js_callback( 'kc_front.blog.masonry' );

			foreach ( $list_posts as $post ):

				//if edit by KC just run filter to set true content
				$meta = get_post_meta($post->ID, 'kc_data', true);

				if (isset($meta['mode']) && $meta['mode'] == 'kc')
					$post_content = $post->post_content = apply_filters('the_content', $post->post_content );
				else
					$post_content = $post->post_content;

				$img_url = '';

				if ( has_post_thumbnail( $post->ID ) ) {
					$image_id   = get_post_thumbnail_id( $post->ID );
					$image_size = ! empty( $image_size ) ? $image_size : 'full';

					if ( in_array( $image_size, $size_array ) ) {
						$image_data = wp_get_attachment_image_src( $image_id, $image_size );
						$img_url    = $image_data[0];
					} else {
						$image_full_width = wp_get_attachment_image_src( $image_id, 'full' );
						$image_full       = $image_full_width[0];
						$img_url          = kc_tools::createImageSize( $image_full, $image_size );
					}
				}else{

					if( $force_image == 'yes'){
						$img =  kc_first_image( $post->post_content );
						if( $img != false ) $img_url = $img;
					}
				}


				?>

				<div class="post-grid grid-<?php echo $number_item; ?>">
					<div class="kc-list-item-3">
						<?php if ( ! empty( $img_url ) ) : ?>
							<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" class="entry-thumb-link">
								<div class="entry-thumb-wrapper">
									<img src="<?php echo esc_url( $img_url ); ?>"
									     alt="<?php echo strip_tags(get_the_title( $post )); ?>"/>
									<div class="entry-thumb-overlay"></div>
								</div>
							</a>
						<?php endif; ?>
						<div class="content">
							<h2 class="post-title-alt"><a
								href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>"><?php echo get_the_title( $post ); ?></a>
							</h2>
							<?php if ( $meta_data == 'yes'): ?>
								<div class="entry-meta">
									<?php if ( $show_author == 'yes' ): ?>
										<span class="kc-post-author"><a
												href="<?php echo get_author_posts_url( $post->post_author ); ?>"
												title="<?php esc_html_e( 'Posts by ', 'kingcomposer' );
												echo get_the_author_meta( 'display_name', $post->post_author ); ?>"
												rel="author"><?php echo get_the_author_meta( 'display_name', $post->post_author ); ?></a></span>
									<?php endif;?>
									<?php if ( $show_date == 'yes' ): ?>
									<span class="entry-date"><a
											href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>"><?php echo get_the_date( 'F d, Y', $post->ID ); ?></a></span>
									<?php endif;?>
									<?php if ( $show_category == 'yes' ): ?>
										<?php if ( get_the_category( $post->ID ) ): ?>
											<span class="entry-cats"><?php the_category( ', ', '', $post->ID ); ?></span>
										<?php endif ?>
									<?php endif ?>
								</div>
							<?php endif ?>
							<?php if ( $words > 0 ): ?>
								<div class="entry-excerpt">
									<p><?php echo wp_trim_words( $post_content, $words ); ?></p>
								</div>
							<?php endif;
							if($show_readmore):
							?>
							<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>"
							   class="kc-post-2-button"><?php esc_html_e( $readmore_text, 'kingcomposer' ); ?> <i
									class="fa fa-angle-right" aria-hidden="true"></i></a>
							<?php endif;?>
						</div>
					</div>
				</div>

				<?php
			endforeach;
			break;
		case '4':

		foreach ( $list_posts as $item ) :

			//if edit by KC just run filter to set true content
			$meta = get_post_meta($item->ID, 'kc_data', true);
			if (isset($mode['mode']) && $meta['mode'] == 'kc')
				$item->post_content = apply_filters('the_content', $item->post_content );
			else
				$item->post_content = $item->post_content;

			$img_url = '';

			if ( has_post_thumbnail( $item->ID ) ) {
				$image_id   = get_post_thumbnail_id( $item->ID );
				$image_size = ! empty( $image_size ) ? $image_size : '500x500xct';

				if ( in_array( $image_size, $size_array ) ) {
					$image_data = wp_get_attachment_image_src( $image_id, $image_size );
					$img_url    = $image_data[0];
				} else {
					$image_full_width = wp_get_attachment_image_src( $image_id, 'full' );
					$img_url          = kc_tools::createImageSize( $image_full_width[0], $image_size );
				}
			}else{

				if( $force_image == 'yes'){
					$img =  kc_first_image( $item->post_content );
					if( $img != false ) $img_url = $img;
				}
			}
			?>

				<div class="item">
					<div class="kc-list-item-4">
						<div class="kc-post-header">
							<?php if ( ! empty( $img_url ) ): ?>
								<a href="<?php echo esc_url( get_permalink( $item->ID ) ); ?>">
									<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo strip_tags(get_the_title( $item )); ?>">
								</a>
							<?php endif; ?>
							<div class="meta-title">
								<?php if ( $meta_data == 'yes' ): ?>
									<div class="post-meta">
										<?php if ( $show_category == 'yes' ): ?>
											<?php if ( get_the_category( $item->ID ) ): ?>
												<?php the_category( ', ', '', $item->ID ); ?>
											<?php endif ?>
										<?php endif ?>
										<?php if ( $show_date == 'yes' ): ?>
											<a href="<?php echo get_month_link( get_the_time( 'Y', $item->ID ), get_the_time( 'm', $item->ID ) ); ?>"
										   class="date-link"><?php echo get_the_date( 'd.F.Y', $item->ID ); ?></a>
										<?php endif ?>
									</div>
								<?php endif ?>

								<h2 class="post-title-alt">
									<a href="<?php echo esc_url( get_permalink( $item->ID ) ); ?>" class="post-title-link"
									   title="<?php echo strip_tags(get_the_title( $item )); ?>"><?php echo get_the_title( $item ); ?></a>
								</h2>
							</div>
						</div>
					</div>
				</div>
		<?php
				endforeach;
			break;

			default:

				foreach( $list_posts as $item ) :

					$item->post_content = apply_filters('the_content', $item->post_content );

					$img_url = '';

					if( has_post_thumbnail( $item->ID ) ){
						$image_id = get_post_thumbnail_id( $item->ID );
						$image_size = !empty( $image_size ) ? $image_size : '1140x550xct';

						if( in_array( $image_size, $size_array ) ){
							$image_data       = wp_get_attachment_image_src( $image_id, $image_size );
							$img_url        = $image_data[0];
						}else{
							$image_full_width = wp_get_attachment_image_src( $image_id, 'full' );
							$img_url 	= kc_tools::createImageSize( $image_full_width[0], $image_size );
						}
					}else{

						if( $force_image == 'yes'){
							$img =  kc_first_image( $item->post_content );
							if( $img != false ) $img_url = $img;
						}
					}
		?>

					<div class="item kc-list-item-1">
						<figure>
						<?php if( !empty( $img_url ) ):?>
							<img src="<?php echo esc_url( $img_url ); ?>" alt="">
						<?php endif;?>
						</figure>
						<div class="post-details">
							<h2 class="post-title-alt">
								<a href="<?php echo esc_url( get_permalink( $item->ID ) ); ?>" title="<?php echo strip_tags(get_the_title( $item )); ?>"><?php echo get_the_title( $item ); ?></a>
							</h2>

							<?php if ( $meta_data == 'yes' ): ?>
								<div class="post-date">
									<?php if ( $show_author == 'yes' ): ?>
									<span class="kc-post-author"><?php esc_html_e( 'by', 'kingcomposer' ); ?> <a href="<?php echo get_author_posts_url( $item->post_author ); ?>" title="<?php esc_html_e( 'Posts by ', 'kingcomposer' ); echo get_the_author_meta( 'display_name', $item->post_author ); ?>" rel="author"><?php echo get_the_author_meta( 'display_name', $item->post_author ); ?></a></span>
									<?php endif;?>
									<?php if ( $show_date == 'yes' ): ?>
										<?php echo get_the_date( 'F j Y', $item->ID ); ?>
									<?php endif ?>
									<?php if ( $show_category == 'yes' ): ?>
										<?php if ( get_the_category( $item->ID ) ): ?>
											<span class="post-cats"><?php the_category( ', ', '', $item->ID ); ?></span>
										<?php endif ?>
									<?php endif ?>
								</div>
							<?php endif ?>
							<?php 
							if($show_readmore):
							?>
							<a href="<?php echo esc_url( get_permalink( $item->ID ) ); ?>"
							   class="kc-post-2-button"><?php esc_html_e( $readmore_text, 'kingcomposer' ); ?> <i
									class="fa fa-angle-right" aria-hidden="true"></i></a>
							<?php endif;?>
						</div>
					</div>

		<?php
				endforeach;
			break;
		} ?>

	<?php else: ?>

		<h3><?php echo __( 'Blog Post: Nothing not found.', 'kingcomposer' ); ?></h3>

	<?php endif ?>

</div>
<?php
wp_reset_postdata();
?>

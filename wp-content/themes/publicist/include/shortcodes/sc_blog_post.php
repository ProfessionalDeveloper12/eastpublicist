<?php
function publicist_blog_section( $atts ) {

	extract( shortcode_atts( array( 'posts_display' => '', 'include_cat' => '', 'exclude_cat' => '', 'excerpt_limit' => '', 'read_more' => '', 'pagination_on' => '' ), $atts ) );

	if( '' === $posts_display ) {
		$posts_display = 5;
	}

	$readmore_text = esc_html__('Read More',"publicist");
	if( $read_more != "" ) {
		$readmore_text = $read_more;
	}

	$excerpt_cnt = 30;
	if( $excerpt_limit != "" ) {
		$excerpt_cnt = $excerpt_limit;
	}
	
	$include_post = explode(",", $include_cat);

	$exclude_post = explode(",", $exclude_cat);

	if( is_front_page() ){
		$paged =  get_query_var('page') ? get_query_var('page') : 1;
	}
	else {
		$paged = 1;
		if ( get_query_var('paged') ) {
			$paged = get_query_var('paged');
		}
	}
	if( $include_cat != "" ) {
		$args = array(
			'post_status' => 'publish',
			'posts_per_page' => $posts_display,
			'category__in' => $include_post,
			'paged' => $paged,
		);
	}
	elseif( $exclude_cat != "" ) {
		$args = array(
			'post_status' => 'publish',
			'posts_per_page' => $posts_display,
			'category__not_in' => array( $exclude_post ),
			'paged' => $paged,
		);	
	}
	else {
		$args = array(
			'post_status' => 'publish',
			'posts_per_page' => $posts_display,
			'paged' => $paged,
		);
	}
	
	query_posts($args);

	ob_start();

	?>
	<!-- Case History -->
	<div class="blog-section">

		<!-- Container -->
		<div class="container">
	
			<div class="row">
		
			<?php
			
			$pid = publicist_get_the_ID();
			if( get_post_meta( $pid,'publicist_cf_sidebar_owlayout',true) == "no_sidebar" ) {
				$thumb = 'publicist_1110_568';
			}
			else {
				$thumb = 'publicist_730_374';
			} 
			
			while ( have_posts() ) : the_post();

				$post_class = "";
				if( get_post_format() == "gallery" ) {
					$post_class = " format-gallery";
				} ?>
				<div class="post-box<?php echo esc_attr($post_class); ?>">
					<?php
					$carousel_id = esc_attr( uniqid( 'post-carousel-' ) );
						
					if( get_post_format() == "gallery" && is_array( get_post_meta( get_the_ID(), 'publicist_cf_post_gallery', 1 ) ) ) {
						?>
						<div class="post-cover">
							<div id="<?php echo esc_attr($carousel_id); ?>" class="carousel slide" data-ride="carousel">
								<div class="carousel-inner">
									<?php
									$act_post = 1;
									foreach ( (array) get_post_meta( get_the_ID(), 'publicist_cf_post_gallery', 1 ) as $attachment_id => $attachment_url ) {
										$act_class = "";
										if( $act_post == 1 ) {
											$act_class = " active";
										} ?>
										<div class="carousel-item<?php echo esc_attr($act_class); ?>">
											<?php echo wp_get_attachment_image( $attachment_id, $thumb ); ?>
										</div>
										<?php
										$act_post++;
									} ?>
								</div>
								<a class="carousel-control-prev" href="#<?php echo esc_attr($carousel_id); ?>" role="button" data-slide="prev">
									<span class="fa fa-angle-left" aria-hidden="true"></span>
								</a>
								<a class="carousel-control-next" href="#<?php echo esc_attr($carousel_id); ?>" role="button" data-slide="next">
									<span class="fa fa-angle-right" aria-hidden="true"></span>
								</a>
							</div>
						</div>
						<?php
					}
					elseif( get_post_format() == "audio" && 
						( get_post_meta( get_the_ID(), 'publicist_cf_post_soundcloud_url', 1 ) != "" ||
						get_post_meta( get_the_ID(), 'publicist_cf_post_audio_local', 1 ) != "" ||
						get_post_meta( get_the_ID(), 'publicist_cf_post_soundcloud_iframe',1 ) != "" )
					) {
						?>
						<div class="post-cover">
							<?php
							if( get_post_meta( get_the_ID(), 'publicist_cf_post_audio_source', 1 ) == "soundcloud_link" && get_post_meta( get_the_ID(), 'publicist_cf_post_soundcloud_url', 1 ) != "" ) {
								?>
								<iframe src="<?php echo esc_url( get_post_meta( get_the_ID(), 'publicist_cf_post_soundcloud_url', 1 ) ); ?>"></iframe>
								<?php
							}
							elseif( get_post_meta( get_the_ID(), 'publicist_cf_post_audio_source', 1 ) == "soundcloud_iframe" && get_post_meta( get_the_ID(), 'publicist_cf_post_soundcloud_iframe', 1 ) != "" ) {
								echo wp_kses( get_post_meta( get_the_ID(), 'publicist_cf_post_soundcloud_iframe', 1 ), array( 'iframe' => array( 'width' => array(), 'height' => array(), 'src' => array() ) ) );
							}
							elseif( get_post_meta( get_the_ID(), 'publicist_cf_post_audio_source', 1 ) == "audio_upload_local" && get_post_meta( get_the_ID(), 'publicist_cf_post_audio_local', 1 ) != "" ) {
								?>
								<audio controls>
									<source src="<?php echo esc_url( get_post_meta( get_the_ID(), 'publicist_cf_post_audio_local', 1 ) ); ?>" type="audio/mpeg">
									<?php esc_html_e("Your browser does not support the audio element.","publicist"); ?>
								</audio>
								<?php
							} ?>
						</div>
						<?php
					}
					elseif( get_post_format() == "video" ) {
						if(	get_post_meta( get_the_ID(), 'publicist_cf_post_video_link', 1 ) != "" ||
							get_post_meta( get_the_ID(), 'publicist_cf_post_video_embed', 1 ) != "" ||
							get_post_meta( get_the_ID(), 'publicist_cf_post_video_local', 1 ) != ""
						) {
							?>
							<div class="post-cover">
								<?php
								if( get_post_meta( get_the_ID(), 'publicist_cf_post_video_source', 1 ) == "video_link" && get_post_meta( get_the_ID(), 'publicist_cf_post_video_link', 1 ) != "" ) {
									echo wp_oembed_get( esc_url( get_post_meta( get_the_ID(), 'publicist_cf_post_video_link', true ) ) );
								}
								elseif( get_post_meta( get_the_ID(), 'publicist_cf_post_video_source', 1 ) == "video_embed_code" && get_post_meta( get_the_ID(), 'publicist_cf_post_video_embed', 1 ) != "" ) {
									echo wp_kses( get_post_meta( get_the_ID(), 'publicist_cf_post_video_embed', 1 ), array( 'iframe' => array( 'src' => array(),'width' => array(), 'height' => array() ) ) );
								}
								elseif( get_post_meta( get_the_ID(), 'publicist_cf_post_video_source', 1 ) == "video_upload_local" && get_post_meta( get_the_ID(), 'publicist_cf_post_video_local', 1 ) != "" ) {
									?>
									<video controls>
										<source src="<?php echo esc_url( get_post_meta( get_the_ID(), 'publicist_cf_post_video_local', 1 ) ); ?>" type="video/mp4">
										<?php esc_html_e("Your browser does not support the video tag.","publicist"); ?>
									</video>
									<?php
								} ?>
							</div>
							
							<?php
						}
					} 
					elseif( has_post_thumbnail() ) {
						?>
						<div class="post-cover">
							<?php the_post_thumbnail($thumb); ?>
						</div>
						<?php
					}
					$postauthor = get_post_meta( get_the_ID(), 'author_custom', true );

					if( $postauthor['text'] != "" ) {
						$postauthorn = $postauthor['text'];
					}
					else {
						$postauthorn = get_the_author();
					}
					?>
					<div class="post-content">
						<h3 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
						<div class="post-meta">
							<div class="post-user"><i class="fa fa-user"></i><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php echo esc_attr( $postauthorn ); ?>"><?php echo esc_attr( $postauthorn ); ?></a></div>
							<?php /*if( has_tag() ) { ?><div class="post-tags"><i class="fa fa-tags"></i><?php echo get_the_tag_list('', ', '); ?></div><?php }*/ ?>
							<?php if( has_category() ) { ?><div class="post-category"><i class="fa fa-tags"></i><?php the_category(', '); ?></div><?php } ?>
						</div>
						<?php
						if( function_exists('publicist_excerpt') ) {
							?><p><?php publicist_excerpt($excerpt_cnt); ?></p><?php
						} ?>
						<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr($readmore_text); ?>" class="read-more"><?php echo esc_attr($readmore_text); ?></a>
					</div>
				</div>
				<?php
			endwhile;
			
			if($pagination_on == 1 ) {
				// Previous/next page navigation.
				the_posts_pagination( array(
					'prev_text'          => wp_kses( __( 'Previous', "publicist" ), array( 'i' => array( 'class' => array() ) ) ),
					'next_text'          => wp_kses( __( 'Next', "publicist" ), array( 'i' => array( 'class' => array() ) ) ),
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', "publicist" ) . ' </span>',
				) );
			}
			
			// Restore original Post Data
			wp_reset_query();
			?>
		</div><!-- Container /- -->
	</div><!-- Case History /- -->
	<?php
	return ob_get_clean();
}
add_shortcode('publicist_blog_section', 'publicist_blog_section');

if ( function_exists('kc_add_map') ) {
	
	kc_add_map( array( 'publicist_blog_section' => array(
		'name'		=> esc_html__('Blog Post',"publicist"),
		'category' 	=> 'Publicist Theme',
		'title' 	=> esc_html__('Blog Post',"publicist"),
		'icon'		=> 'fa fa-envelope-o',
		'is_container' => true,
		'params'	=> array(

			array(
				"type" => "text",
				"label" => esc_html__("Excerpt Limit(Default:30)", 'publicist'),
				"name" => "excerpt_limit",
			),
			array(
				"type" => "text",
				"label" => esc_html__("Post Per Page Display( Default: 5)", "publicist"),
				"name" => "posts_display",
			),
			array(
				"type" => "text",
				"label" => esc_html__("Read More Text", "publicist"),
				"name" => "read_more",
				"description" => esc_html__('Default Text: Read More',"publicist"),
			),
			array(
				"type" => "text",
				"label" => esc_html__("Exclude Category", 'publicist'),
				"description" => esc_html__('It supports multiple Exclude Category separated by comma(e.g 2,4,5,6)', 'publicist'),
				"name" => "exclude_cat",
				"admin_label" => true,
			),
			array(
				"type" => "text",
				"label" => esc_html__("Include Category", 'publicist'),
				"description" => esc_html__('It supports multiple Include Category separated by comma(e.g 2,4,5,6)', 'publicist'),
				"name" => "include_cat",
				"admin_label" => true,
			),
			array(
				"label" => esc_html__("Display Pagination", "publicist"),
				"name" => "pagination_on",
				'type'	=> 'checkbox',
				'options' => array(
					'1' => 'Yes',
				),
			),
		)
	) ) );
} ?>
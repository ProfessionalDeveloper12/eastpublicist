<?php
function publicist_tab_content( $atts ) {

	extract( shortcode_atts( array('slide_displays' => '', 'posts_display' => '', 'excerpt_limit' => '' ), $atts ) );
	
	if( '' === $slide_displays ) :
		$slide_displays = 2;		
	endif;
	
	if( '' === $posts_display ) {
		$posts_display = 4;
	}
	
	$excerpt_cnt = 25;
	if( $excerpt_limit != "" ) {
		$excerpt_cnt = $excerpt_limit;
	}

	ob_start();

	?>
	<!-- Tab Section -->
	<div class="tab-section">
		<!-- Container -->
		<div class="container">
			<ul class="nav nav-tabs" id="myTab" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="latest-tab" data-toggle="tab" href="#latest" role="tab" aria-controls="latest" aria-selected="true"><?php esc_html_e('Latest',"publicist"); ?></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="trending-tab" data-toggle="tab" href="#trending" role="tab" aria-controls="trending" aria-selected="false"><?php esc_html_e('Trending',"publicist"); ?></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="gallery-tab" data-toggle="tab" href="#gallery" role="tab" aria-controls="gallery" aria-selected="false"><?php esc_html_e('Gallery',"publicist"); ?></a>
				</li>
			</ul>
			<div class="tab-content" id="myTabContent">
				<div class="tab-pane fade show active" id="latest" role="tabpanel" aria-labelledby="latest-tab">
					<div class="row">
						<div class="col-lg-6">
							<div class="tab-carousel">
								<div id="tab-carousel-latest" class="carousel slide" data-ride="carousel">
									<div class="carousel-inner">
										<?php
										$qry = new WP_Query( array(
											'posts_per_page' => $slide_displays,
											'post_status' => 'publish',
											'meta_query' => array( 
												array(
													'key' => '_thumbnail_id'
												) 
											)
										) );
										$i = 1;
										while ( $qry->have_posts() ) : $qry->the_post();
											$act_class = "";
											if( $i == 1) {
												$act_class = " active"; 
											} ?>
											<div class="carousel-item<?php echo esc_attr($act_class); ?>">
												<i><?php the_post_thumbnail('publicist_570_457'); ?></i>
												<div class="carousel-detail">
													<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
													<?php
													if( function_exists('publicist_excerpt') ) {
														?><p><?php publicist_excerpt($excerpt_cnt); ?></p><?php
													} ?>
												</div>
											</div>
											<?php
											$i++;
										endwhile;
										
										/* Reset Post Data */
										wp_reset_postdata();
										
										?>
										
									</div>
									<a class="carousel-control-prev" href="#tab-carousel-latest" role="button" data-slide="prev">
										<i class="fas fa-angle-left" aria-hidden="true"></i>
									</a>
									<a class="carousel-control-next" href="#tab-carousel-latest" role="button" data-slide="next">
										<i class="fas fa-angle-right" aria-hidden="true"></i>
									</a>
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="row tab-gallery">
								<?php
								$qry = new WP_Query( array(
									'posts_per_page' => $posts_display,
									'post_status' => 'publish',
									'meta_query' => array( 
										array(
											'key' => '_thumbnail_id'
										) 
									)
								) );
								while ( $qry->have_posts() ) : $qry->the_post();
									?>
									<div class="col-sm-6">
										<div class="tab-box">
											<?php the_post_thumbnail('publicist_270_213'); ?>
											<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
										</div>
									</div>
									<?php
								endwhile;
								
								/* Reset Post Data */
								wp_reset_postdata();
								?>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="trending" role="tabpanel" aria-labelledby="trending-tab">
					<div class="row">
						<div class="col-lg-6">
							<div class="tab-carousel">
								<div id="tab-carousel-trending" class="carousel slide" data-ride="carousel">
									<div class="carousel-inner">
										<?php
										$qry = new WP_Query( array(
											'posts_per_page' => $slide_displays,
											'post_status' => 'publish',
											'orderby' => 'comment_count',
											'meta_query' => array( 
												array(
													'key' => '_thumbnail_id'
												) 
											)
										) );
										$i = 1;
										while ( $qry->have_posts() ) : $qry->the_post();
											$act_class = "";
											if( $i == 1) {
												$act_class = " active"; 
											} ?>
											<div class="carousel-item<?php echo esc_attr($act_class); ?>">
												<i><?php the_post_thumbnail('publicist_570_457'); ?></i>
												<div class="carousel-detail">
													<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
													<?php
													if( function_exists('publicist_excerpt') ) {
														?><p><?php publicist_excerpt($excerpt_cnt); ?></p><?php
													} ?>
												</div>
											</div>
											<?php
											$i++;
										endwhile;
										
										/* Reset Post Data */
										wp_reset_postdata();
										?>
									</div>
									<a class="carousel-control-prev" href="#tab-carousel-trending" role="button" data-slide="prev">
										<i class="fas fa-angle-left" aria-hidden="true"></i>									
									</a>
									<a class="carousel-control-next" href="#tab-carousel-trending" role="button" data-slide="next">
										<i class="fas fa-angle-right" aria-hidden="true"></i>
									</a>
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="row tab-gallery">
								<?php
								$qry = new WP_Query( array(
									'posts_per_page' => $posts_display,
									'post_status' => 'publish',
									'orderby' => 'comment_count',
									'meta_query' => array( 
										array(
											'key' => '_thumbnail_id'
										) 
									)
								) );
							
								while ( $qry->have_posts() ) : $qry->the_post();
									?>
									<div class="col-sm-6">
										<div class="tab-box">
											<?php the_post_thumbnail('publicist_270_213'); ?>
											<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
										</div>
									</div>
									<?php
								endwhile;
								
								/* Reset Post Data */
								wp_reset_postdata();
								?>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="gallery" role="tabpanel" aria-labelledby="gallery-tab">
					<div class="row">
						<div class="col-lg-6">
							<div class="tab-carousel">
								<div id="tab-carousel-gallery" class="carousel slide" data-ride="carousel">
									<div class="carousel-inner">
										<?php
										$qry = new WP_Query( array(
											'posts_per_page' => $slide_displays,
											'post_status' => 'publish',
											'post_type' => 'event',
											'meta_query' => array( 
												array(
													'key' => '_thumbnail_id'
												) 
											)
										) );
										$i = 1;
										while ( $qry->have_posts() ) : $qry->the_post();
											$act_class = "";
											if( $i == 1) {
												$act_class = " active"; 
											} ?>
											<div class="carousel-item<?php echo esc_attr($act_class); ?>">
												<i><?php the_post_thumbnail('publicist_570_457'); ?></i>
												<div class="carousel-detail">
													<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
													<?php
													if( function_exists('publicist_excerpt') ) {
														?><p><?php publicist_excerpt($excerpt_cnt); ?></p><?php
													} ?>
												</div>
											</div>
											<?php
											$i++;
										endwhile;
										
										/* Reset Post Data */
										wp_reset_postdata();
										?>
										
									</div>
									<a class="carousel-control-prev" href="#tab-carousel-gallery" role="button" data-slide="prev">
										<i class="fas fa-angle-left" aria-hidden="true"></i>									
									</a>
									<a class="carousel-control-next" href="#tab-carousel-gallery" role="button" data-slide="next">
										<i class="fas fa-angle-right" aria-hidden="true"></i>
									</a>
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="row tab-gallery">
							
								<?php
								$qry = new WP_Query( array(
									'posts_per_page' => $posts_display,
									'post_status' => 'publish',
									'orderby' => 'rand',
									'meta_query' => array( 
										array(
											'key' => '_thumbnail_id'
										) 
									)
								) );
							
								while ( $qry->have_posts() ) : $qry->the_post();
									?>
									<div class="col-sm-6">
										<div class="tab-box">
											<?php the_post_thumbnail('publicist_270_213'); ?>
											<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
										</div>
									</div>
									<?php
								endwhile;
								
								/* Reset Post Data */
								wp_reset_postdata();
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><!-- Container /- -->
	</div><!-- Tab Section /- -->
	<?php
	return ob_get_clean();
}
add_shortcode('publicist_tab_content', 'publicist_tab_content');

if ( function_exists('kc_add_map') ) {

	kc_add_map( array( 'publicist_tab_content' => array(
		'name'		=> esc_html__('Tab Content',"publicist"),
		'category' 	=> 'Publicist Theme',
		'title' 	=> esc_html__('Tab Content',"publicist"),
		'icon'		=> 'fa fa-envelope-o',
		'is_container' => true,
		'params'	=> array(
			array(
				"type" => "text",
				"label" => esc_html__("Slider Posts Per Page Display( Default: 2)", "publicist"),
				"name" => "slide_displays",
			),
			array(
				"type" => "text",
				"label" => esc_html__("Thumb Posts Per Page Display( Default: 4)", "publicist"),
				"name" => "posts_display",
			),
			
			array(
				"type" => "text",
				"label" => esc_html__("Slider Post Content Excerpt Limit(Default:25)", 'publicist'),
				"name" => "excerpt_limit",
			),
		)
	) ) );
} ?>
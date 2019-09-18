<?php
function publicist_slider( $atts ) {

	extract( shortcode_atts( array('sc_bg' => '', 'slide_opt' => '' ), $atts ) );

	ob_start();
	
	if( $slide_opt != "" ) {
		?>
		<!-- Slider Section -->
		<div class="slider-section">				
			<div id="main-slider" class="carousel slide" data-ride="carousel">
				<?php echo wp_get_attachment_image($sc_bg,'publicist_1920_982' ); ?>
				<div class="carousel-inner">
					<?php
					$i = 1;
					foreach( $slide_opt as $key => $slidecnt ) {
						$act_class = "";
						if( $i == 1) {
							$act_class = " active";
						} ?>
						<div class="carousel-item<?php echo esc_attr($act_class); ?>">
							<div class="slide-detail">
								<div class="container">
									<div class="row align-items-center">
										<?php
										if( $slidecnt->slide_title != "" ) {
											$content_css = "col-xl-7 col-lg-7";
										}
										else {
											$content_css = "col-xl-12 col-lg-12";
										}
										
										if( isset($slidecnt->slide_title ) ) {
											?>
											<div class="col-xl-4 col-lg-5 slide-title">
												<h3><a href="<?php echo esc_url($slidecnt->slide_title_url); ?>" title="<?php echo esc_attr($slidecnt->slide_title); ?>"><?php echo esc_attr($slidecnt->slide_title); ?></a></h3>
											</div>
											<?php
										} 
										if( isset( $slidecnt->content_img ) || isset($slidecnt->content_desc ) ) {
											?>
											<div class="<?php echo esc_attr($content_css); ?>">
												<div class="story-of-week">
													<?php if( isset( $slidecnt->content_img ) ) { echo wp_get_attachment_image($slidecnt->content_img,'publicist_289_326'); } ?>
													<?php if( isset($slidecnt->content_desc ) ) { echo wp_kses( wpautop($slidecnt->content_desc), wp_kses_allowed_html() ); } ?>
												</div>
											</div>
											<?php
										} ?>
									</div>
								</div>
							</div>
						</div>
						<?php
						$i++;
					} ?>
				</div>
				<a class="carousel-control-prev" href="#main-slider" role="button" data-slide="prev">
					<i class="fas fa-angle-left" aria-hidden="true"></i>
				</a>
				<a class="carousel-control-next" href="#main-slider" role="button" data-slide="next">
					<i class="fas fa-angle-right" aria-hidden="true"></i>
				</a>
			</div>
		</div><!-- Slider Section /- -->
		<?php
	}	
	return ob_get_clean();
}
add_shortcode('publicist_slider', 'publicist_slider');

if ( function_exists('kc_add_map') ) {

	kc_add_map( array( 'publicist_slider' => array(
		'name'		=> esc_html__('Slider',"publicist"),
		'category' 	=> 'Publicist Theme',
		'title' 	=> esc_html__('Slider',"publicist"),
		'icon'		=> 'fa fa-envelope-o',
		'is_container' => true,
		'params'	=> array(
			array(
				'name'	=> 'sc_bg',
				'label'	=> esc_html__('Slider Background Image',"publicist"),
				'type'	=> 'attach_image',
			),
			array(
				'type'			=> 'group',
				'label'			=> esc_html__('Slider Content', 'publicist'),
				'name'			=> 'slide_opt',
				'description'	=> '',
				'options'		=> array('add_text' => esc_html__('Add new group', 'publicist')),
				'params' => array(
					
					array(
						'name'	=> 'slide_title',
						'label'	=> esc_html__('Slide Title',"publicist"),
						'type'	=> 'text',
					),
					array(
						'name'	=> 'slide_title_url',
						'label'	=> esc_html__('Slide Title URL',"publicist"),
						'type'	=> 'text',
					),
					array(
						'name'	=> 'content_desc',
						'label'	=> esc_html__('Slide Content',"publicist"),
						'type'	=> 'textarea',
					),
					array(
						'name'	=> 'content_img',
						'label'	=> esc_html__('Slide Content Image',"publicist"),
						'type'	=> 'attach_image',
					),
				)
			),
		)
	) ) );
} ?>
<?php
function publicist_case_history( $atts ) {

	extract( shortcode_atts( array( 'sc_title' => '', 'posts_display' => '', 'pagination_on' => '', 'excerpt_limit' => '', 'read_more' => '' ), $atts ) );

	if( '' === $posts_display ) {
		$posts_display = 3;
	}

	$excerpt_cnt = 55;
	if( $excerpt_limit != "" ) {
		$excerpt_cnt = $excerpt_limit;
	}

	$readmore_text = esc_html__('Read More',"publicist");
	if( $read_more != "" ) {
		$readmore_text = $read_more;
	}
	
	$ow_post_type = 'publicist_histories';
	
	if( is_front_page() ) {
		$paged = get_query_var('page') ? get_query_var('page') : 1;
		$qry_args = array( 'post_type' => $ow_post_type, 'posts_per_page' => $posts_display, 'paged' =>  $paged );
	}
	else {
		$qry_args = array( 'post_type' => $ow_post_type, 'posts_per_page' => $posts_display, 'paged' =>  get_query_var( 'paged' ) );
	}
	
	publicist_wp_query( $qry_args );
	
	ob_start();

	?>
	<!-- Case History -->
	<div class="case-history">

		<!-- Container -->
		<div class="container">
			<?php
			if( $sc_title != "" ) {
				?>
				<div class="section-header">
					<h3><?php echo esc_attr($sc_title); ?></h3>
				</div>
				<?php
			}
			while ( have_posts() ) : the_post();
				
				if( !has_post_thumbnail()  ) {
					$content_class = " col-lg-12";
				}
				else {
					$content_class = " col-lg-6";
				} ?>
				<div class="row case-box">
					<?php
					if( has_post_thumbnail() ) {
						?>
						<div class="col-lg-6">
							<?php the_post_thumbnail('publicist_350_247'); ?>
						</div>
						<?php
					} ?>
					<div class="case-content<?php echo esc_attr($content_class); ?>">
						<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
						<?php
						if( function_exists('publicist_excerpt') ) {
							?><p><?php echo publicist_get_excerpt( 300 ); // publicist_excerpt($excerpt_cnt); ?></p><?php
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
add_shortcode('publicist_case_history', 'publicist_case_history');

if ( function_exists('kc_add_map') ) {
	
	kc_add_map( array( 'publicist_case_history' => array(
		'name'		=> esc_html__('Case_History',"publicist"),
		'category' 	=> 'Publicist Theme',
		'title' 	=> esc_html__('Case History',"publicist"),
		'icon'		=> 'fa fa-envelope-o',
		'is_container' => true,
		'params'	=> array(
			array(
				'name'	=> 'sc_title',
				'label'	=> esc_html__('Title',"publicist"),
				'type'	=> 'text',
			),
			array(
				"type" => "text",
				"label" => esc_html__("Excerpt Limit(Default:55)", 'publicist'),
				"name" => "excerpt_limit",
			),
			array(
				"type" => "text",
				"label" => esc_html__("Case History Per Page Display( Default: 3)", "publicist"),
				"name" => "posts_display",
			),
			array(
				"type" => "text",
				"label" => esc_html__("Read More Text", "publicist"),
				"name" => "read_more",
				"description" => esc_html__('Default Text: Read More',"publicist"),
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
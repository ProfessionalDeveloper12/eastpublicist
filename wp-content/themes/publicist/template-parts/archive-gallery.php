<?php
/* Post Format : Gallery */
if( get_post_format() == "gallery" ) {
	$blog_id = esc_attr( uniqid( 'blog-carousel-' ) );
	if( is_array( get_post_meta( get_the_ID(), 'publicist_cf_post_gallery', 1 ) ) ) {
		?>
		<div class="post-cover">
			<!-- Carousel -->	
			<div id="<?php echo esc_attr($blog_id); ?>" class="carousel slide" data-ride="carousel">
				<div class="carousel-inner" role="listbox">
					<?php
					$active_post = 1;
					foreach ( (array) get_post_meta( get_the_ID(), 'publicist_cf_post_gallery', 1 ) as $attachment_id => $attachment_url ) {
						$act_class = "";
						if($active_post == 1) { $act_class = " active"; }
						?>
						<div class="carousel-item<?php echo esc_attr($act_class); ?>"><?php echo wp_get_attachment_image( $attachment_id, 'publicist_730_374' ); ?></div>
						<?php
						$active_post++;
					} ?>
				</div>
				
				<a class="carousel-control-prev" href="#<?php echo esc_attr($blog_id); ?>" role="button" data-slide="prev">
					<i class="fa fa-angle-left" aria-hidden="true"></i>
				</a>
				
				<a class="carousel-control-next" href="#<?php echo esc_attr($blog_id); ?>" role="button" data-slide="next">
					<i class="fa fa-angle-right" aria-hidden="true"></i>
				</a>
				
			</div><!-- /.carousel -->
		</div>
		<?php
	}
} ?>
<?php
/* Post Format : Video */
if( get_post_format() == "video") {
	
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
} ?>
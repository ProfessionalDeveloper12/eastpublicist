<?php
if( has_post_thumbnail() && ( get_post_format() != "audio" && get_post_format() != "video" && get_post_format() != "gallery" ) ) { ?>
	<div class="post-cover">
		<?php
		$pid = publicist_get_the_ID();
		if( get_post_meta( $pid,'publicist_cf_sidebar_owlayout',true) == "no_sidebar" ) {
			$thumb = 'publicist_1110_568';
		}
		else {
			$thumb = 'publicist_730_374';
		} 
		the_post_thumbnail($thumb);
		?>
	</div>
	<?php
} ?>
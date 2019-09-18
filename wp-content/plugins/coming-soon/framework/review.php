<?php 

// Review Request
add_action( 'admin_footer_text', 'seed_csp4_admin_footer' );

function seed_csp4_admin_footer( $text ) {
	
  global $current_screen;
//   $review = get_option( 'seed_csp4_review' );
//   if ( isset( $review['dismissed'] ) &&  $review['dismissed']){
//   	return $text;
//   }


  if ( !empty( $current_screen->id ) && strpos( $current_screen->id, 'seed_csp4' ) !== false ) {

    $url  = 'https://wordpress.org/support/plugin/coming-soon/reviews/?filter=5#new-post';
    $text = sprintf( __( 'Please rate <strong>SeedProd</strong> <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%s" target="_blank">WordPress.org</a> to help us spread the word. Thank you from the SeedProd team!', 'coming-soon' ), $url, $url );
  }
  return $text;
}

if(!empty($_GET['page']) && $_GET['page'] == 'seed_csp4'){
//add_action( 'admin_notices', 'seed_csp4_review' );
}
function seed_csp4_review() {

	// Verify that we can do a check for reviews.

	$review = get_option( 'seed_csp4_review' );
	$time	= time();
	$load	= false;
	$settings = seed_csp4_get_settings();
	

	if ( ! $review ) {
		$review = array(
			'time' 		=> $time,
			'dismissed' => false
		);
		$load = true;
	} else {
		// Check if it has been dismissed or not.
		//if ( (isset( $review['dismissed'] ) && ! $review['dismissed']) && (isset( $review['time'] ) && (($review['time'] + DAY_IN_SECONDS) <= $time) && $settings['status'] > 0) ) {
			if ( (isset( $review['dismissed'] ) && ! $review['dismissed']) ) {
			$load = true;
		}
	}


	// If we cannot load, return early.
	if ( ! $load ) {
		return;
	}

	// Update the review option now.
	update_option( 'seed_csp4_review', $review );

	$current_user = wp_get_current_user();
	$fname = '';
	if(!empty($current_user->user_firstname)){
		$fname = $current_user->user_firstname;
	}

	$page_type = 'Coming Soon Page';
	if(!empty($settings['status']) && $settings['status'] == 2){
		$page_type = 'Maintenance Mode Page';
	}


	// We have a candidate! Output a review message.
	?>
	<div class="notice notice-info is-dismissible seed-csp4-review-notice">
		<p><?php printf(__( 'Hey %s, <br><br>I just want to say "Thank you" using this free plugin. If you have any questions  post it to our <a href="https://wordpress.org/support/plugin/coming-soon">support forums</a>.<br><br>Also check out the &#8594; <a href="%s" target="blank" rel="noopener noreferrer">special upgrade offer</a> we have going on right now for the Pro Verison.<br><br>Hope you have a great %s! Cheers', 'coming-soon' ),ucfirst($fname),seed_csp4_admin_upgrade_link( 'special-offer' ), date('l') ); ?></p>
		<p><strong><?php _e( '--<br> John Turner<br><a href="'.seed_csp4_admin_upgrade_link( 'special-offer' ).'" target="blank" rel="noopener noreferrer">SeedProd.com</a>', 'coming-soon' ); ?></strong></p>
		<p>
			<!-- <a href="https://wordpress.org/support/plugin/coming-soon/reviews/?filter=5#new-post" class="seed-csp4-dismiss-review-notice seed-csp4-review-out" target="_blank" rel="noopener"><?php _e( 'Ok, you deserve it', 'coming-soon' ); ?></a><br> -->
			<a href="#" class="seed-csp4-dismiss-review-notice" target="_blank" rel="noopener"><?php _e( 'Dismiss Notice', 'coming-soon' ); ?></a><br>
			<!-- <a href="#" class="seed-csp4-dismiss-review-notice" target="_blank" rel="noopener"><?php _e( 'I already did', 'coming-soon' ); ?></a><br> -->
		</p>
	</div>
	<script type="text/javascript">
		jQuery(document).ready( function($) {
			$(document).on('click', '.seed-csp4-dismiss-review-notice, .seed-csp4-review-notice .notice-dismiss', function( event ) {
				if ( ! $(this).hasClass('seed-csp4-review-out') ) {
					event.preventDefault();
				}

				$.post( ajaxurl, {
					action: 'seed_csp4_dismiss_review'
				});

				$('.seed-csp4-review-notice').remove();
			});
		});
	</script>
	<?php
}

add_action( 'wp_ajax_seed_csp4_dismiss_review', 'seed_csp4_dismiss_review' );
function seed_csp4_dismiss_review() {

	$review = get_option( 'seed_csp4_review' );
	if ( ! $review ) {
		$review = array();
	}

	$review['time'] 	 = time();
	$review['dismissed'] = true;

	update_option( 'seed_csp4_review', $review );
	die;
}
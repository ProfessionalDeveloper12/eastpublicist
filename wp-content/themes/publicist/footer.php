<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Publicist
 * @since Publicist 1.0
 */
?>
	</div><!-- main-container /- -->
	
	<?php
	$news_title = publicist_options("opt_newsletter_title");
	$news_subtitle = publicist_options("opt_newsletter_subtitle");
	
	if( publicist_options("opt_news_bgimg", "url") != "" ) {
		$news_bg = publicist_options("opt_news_bgimg", "url");
	}
	else {
		$news_bg = esc_url(PUBLICIST_IMGURI).'/news-bg.jpg';
	} 
	if( publicist_options("opt_news_onoff") != "0" ) {
		if( $news_title != "" || $news_subtitle != "" || publicist_options("opt_newsletter_id") != "" && function_exists( 'publicist_footer_newlatterform' ) ) {
			?>
			<!-- Newsletter Section -->
			<div class="newsletter-section" <?php if($news_bg != "") { ?>style="background-image:url(<?php echo esc_url( $news_bg ); ?>);"<?php } ?>>
				<!-- Container -->
				<div class="container">
					<!-- Row -->
					<div class="row justify-content-center">
						<div class="col-lg-6 col-xl-5 newsletter-content text-center">
							<?php if($news_title != "" ) { ?><h3><?php echo esc_attr($news_title); ?></h3><?php } ?>
							<?php if($news_subtitle != "") { ?><p><?php echo esc_attr($news_subtitle); ?></p><?php } ?>
							<?php
							if( publicist_options("opt_newsletter_id") != "" && function_exists( 'publicist_footer_newlatterform' ) ) {
								echo publicist_footer_newlatterform();
							} ?>
						</div>
					</div><!-- Row /- -->
				</div><!-- Container /- -->
			</div><!-- Newsletter Section /- -->
			<?php
		}
	} ?>
	<!-- Footer Section -->
	<div class="footer-section">
		<!-- Container -->
		<div class="container">
			<div class="copyright">
				<?php
				if( publicist_options("opt_footer_copyright") != "" && function_exists('publicist_copyright') ) {
					echo publicist_copyright();
				}
				else {
					?>
					<p>
						<?php esc_html_e('&copy;',"publicist"); ?>
						<?php echo esc_attr( date(' Y') ); ?>
						<?php esc_html_e('The Publicist East Africa. All rights reserved.',"publicist");?>
					</p>
					<?php
				} ?>
			</div>
		</div><!-- Container /- -->
	</div><!-- Footer Section /- -->
	
	<?php wp_footer(); ?>
</body>
</html>
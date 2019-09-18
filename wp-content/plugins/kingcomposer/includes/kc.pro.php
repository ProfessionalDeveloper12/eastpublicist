<?php
/**
*
*	King Composer
*	(c) KingComposer.com
*
*/

if(!defined('ABSPATH')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

global $kc;
$pdk = $kc->check_pdk();
	
?>
<div class="kc-pro-settings wrap about-wrap">
	<?php
	if( !is_dir( ABSPATH.KDS.'wp-content'.KDS.'plugins'.KDS.'kc_pro' ) ){
	?>
		<table class="form-table">
			<tbody>
				<tr>
					<td>
						<div id="kc-pro-settings-download-wrp">
							<h1 style="margin: 0px;"><?php echo _e( 'Try the KC Pro! now', 'kingcomposer' ); ?></h1>
							<h3><?php echo _e( 'Start your free 7-day trial to discover the Front-End Live Editor with leading professional.', 'kingcomposer' ); ?></h3>
							<br />
							<hr />
							<br />
							<p>
								<input type="hidden" id="kc-nonce-download" value="<?php echo wp_create_nonce('kc-pro-download'); ?>" />
								<button class="button button-large button-primary" id="kc-pro-settings-process-download">
									<?php _e('Install KC Pro! Automatically', 'kingcomposer'); ?>
									<i class="dashicons dashicons-external"></i> 
								</button> 
								<span class="kc-pro-direct-download">
									&nbsp; Or &nbsp; 
									<a href="http://bit.ly/kc-pro">
										<?php _e('Directly download kc-pro.zip', 'kingcomposer'); ?>
									</a>
								</span>
							</p>
							<br />
						</div>
					</td>
					<td>
						<p>
							<br />
							<iframe width="380" height="220" id="kc-pro-settings-video-frame" src="https://www.youtube.com/embed/kFANGxXh6Fw" frameborder="0" allowfullscreen></iframe>
							<p class="align-center">
								<button class="button" id="kc-pro-settings-larger-video" >
									<i class="dashicons dashicons-editor-expand"></i> 
									<?php _e( 'Larger video size', 'kingcomposer' ); ?>
								</button>
							</p>
						</p>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
		
		}else{ 
		
			$plugin_path = 'kc_pro/kc_pro.php';
			$active_url = wp_nonce_url( self_admin_url('plugins.php?action=activate&plugin='.$plugin_path), 'activate-plugin_'.$plugin_path );
		
		?>
		<p>
			<a href="<?php echo $active_url; ?>" class="button button-large button-primary">
				<?php _e( 'Active the KC Pro! now', 'kingcomposer' ); ?>
			</a>
		</p>
	<?php } ?>
</div>

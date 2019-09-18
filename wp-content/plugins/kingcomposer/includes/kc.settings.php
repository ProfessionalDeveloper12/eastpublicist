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

wp_enqueue_style('thickbox');
wp_enqueue_script('thickbox');

$kc = KingComposer::globe();
$pdk = $kc->get_pdk();

$settings = $kc->settings();
$plugin_info = get_plugin_data( KC_FILE );

$detail_url = admin_url('/plugin-install.php?tab=plugin-information&amp;plugin=%s&amp;section=changelog&amp;TB_iframe=true&amp;width=772&amp;height=600');

$show_license_tab = false;

if ($kc->plugin_active('kc_pro/kc_pro.php'))
	$show_license_tab = true;

if (isset($pdk['pack']) && ($pdk['pack'] == 'commerce' || $pdk['pack'] == 'developer') && $kc->check_pdk() == 1)
	$show_license_tab = false;

?>

<div id="kc-settings" class="wrap about-wrap">
	<h1><?php
	echo $kc->apply_filters('kc_setting_title', __('Welcome to KingComposer Page Builder!', 'kingcomposer')); ?></h1>
	<?php
	ob_start();
	?>
	<div class="about-text">
		<?php _e('Thank you for using KingComposer page builder. If you need help or have any suggestions, join our community for discussion ', 'kingcomposer'); ?>
		<a href="http://bit.ly/kcdiscuss" target=_blank>bit.ly/kcdiscuss</a>
	</div>
	<?php
	$about = ob_get_contents();
	ob_end_clean();
	echo $kc->apply_filters('kc_setting_about', $about);
	ob_start();
	?>
	<div class="wp-badge kc-badge">
		<?php _e('Version', 'kingcomposer'); ?> <?php echo esc_attr( $plugin_info['Version'] ); ?>
	</div>
	<?php
	$version = ob_get_contents();
	ob_end_clean();
	echo $kc->apply_filters('kc_setting_version', $version);
	ob_start();
	?>
	<h2 class="nav-tab-wrapper">
		<a href="#kc_general_setting" class="nav-tab nav-tab-active" id="kc_general_setting-tab">
			<?php _e('General Settings', 'kingcomposer'); ?>
		</a>
		<?php if( $show_license_tab === true ){ ?>
		<a href="#kc_product_license" class="nav-tab" id="kc_product_license-tab">
			<?php _e('Product License', 'kingcomposer'); ?>
		</a>
		<?php } ?>
		<a href="#kc_pro" class="nav-tab" id="kc_pro-tab">
			<?php _e('KC Pro!', 'kingcomposer'); ?>
		</a>
		<a href="#kc_changelogs" class="nav-tab" id="kc_changelogs-tab">
			<?php _e('Changelogs', 'kingcomposer'); ?>
		</a>
	</h2>
	<?php
	$tab_nav = ob_get_contents();
	ob_end_clean();
	echo $kc->apply_filters('kc_setting_tab_nav', $tab_nav);
		/*
		if (isset($_GET['screen']) && $_GET['screen'] == 'welcome')
		{
			@session_start();
			if (isset($_SESSION['kc_disabled_plugins']) && count($_SESSION['kc_disabled_plugins']) > 0)
			{
				$list = array();
				foreach ($_SESSION['kc_disabled_plugins'] as $name)
				{
					if (strpos($name, '/') !== false)
					{
						$name = substr($name, 0, strpos($name, '/')-1);
						$list[] = $name;
					}
				}
				echo '<div class="kc-notice"><p>'.__('Notice: To avoid conflict, We have disabled the following plugins: ', 'kingcomposer').'<strong>'.implode(', ', $list).'</strong>. <a href="'.admin_url('/plugins.php').'">'.__('Reactivate plugins', 'kingcomposer').'</a></p></div>';
			}
		} */

	?>

	<?php

	$update_plugin = get_site_transient( 'update_plugins' );

    if ( isset( $update_plugin->response[ KC_BASE ] ) )
	{
		ob_start();
	?>
	<div class="kc-updated">
		<p>
			<i class="dashicons dashicons-update"></i>
			<?php _e('There is a new version of KingComposer available', 'kingcomposer'); ?>.
			<a href="<?php printf( $detail_url, 'kingcomposer' ); ?>" class="thickbox open-plugin-details-modal" aria-label="<?php printf( __('View version %s details', 'kingcomposer'), $update_plugin->response[ KC_BASE ]->new_version ); ?>">
				<?php printf( __('View version %s details', 'kingcomposer'), $update_plugin->response[ KC_BASE ]->new_version ); ?></a> or
			<a href="#" class="update-link kc-update-link-ajax" data-slug="kingcomposer">
				<?php _e('update now', 'kingcomposer'); ?>
			</a>.
		</p>
	</div>
	<?php
		$kc_update = ob_get_contents();
		ob_end_clean();
		echo $kc->apply_filters('kc_setting_update', $kc_update);
	}

    if ( defined( 'KCP_BASENAME' ) && isset( $update_plugin->response[ KCP_BASENAME ] ) )
	{
		ob_start();
	?>
	<div class="kc-updated">
		<p>
			<i class="dashicons dashicons-update"></i>
			<?php _e('There is a new version of KC Pro! available', 'kingcomposer'); ?>.
			<a href="<?php printf( $detail_url, 'kc_pro' ); ?>" class="thickbox open-plugin-details-modal" aria-label="<?php printf( __('View version %s details', 'kingcomposer'), $update_plugin->response[ KCP_BASENAME ]->new_version ); ?>">
				<?php printf( __('View version %s details', 'kingcomposer'), $update_plugin->response[ KCP_BASENAME ]->new_version ); ?>
			</a> or
			<a href="#" class="update-link kc-update-link-ajax" data-slug="kc_pro"><?php _e('update now', 'kingcomposer'); ?></a>.
		</p>
	</div>

	<?php
		$kc_update_pro = ob_get_contents();
		ob_end_clean();
		echo $kc->apply_filters('kc_setting_update_pro', $kc_update_pro);
	}
	?>

	<form method="post" action="options.php" enctype="multipart/form-data" id="kc-settings-form" autocomplete="off">
		<!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
		<input style="display:none" type="text" name="fakeusernameremembered"/>
		<input style="display:none" type="password" name="fakepasswordremembered"/>
		<?php settings_fields( 'kingcomposer_group' ); ?>
		<div id="kc_general_setting" class="group p">
			<?php
			ob_start();
			?>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<?php _e('Supported Content Types', 'kingcomposer'); ?>:
						</th>
						<td>
							<?php

								$post_types = get_post_types( array( 'public' => true ) );
								$ignored_types = array('attachment');
								$settings_types = $kc->get_content_types();
								$required_types = $kc->get_required_content_types();

								foreach( $post_types as $type ){
									if( !in_array( $type, $ignored_types ) ){
										echo '<p class="radio"><input ';
										if( in_array( $type, $settings_types ) )
											echo 'checked ';
										if( in_array( $type, $required_types ) )
											echo 'disabled ';
										echo'type="checkbox" name="kc_options[content_types][]" value="'.esc_attr($type).'"> ';
										echo esc_html( $type );
										if( in_array( $type, $required_types ) )
											echo ' <i> (required)</i>';
										echo '</p>';
									}
								}

							?>

							<br />
							<span class="description">
								<p>
									- <?php _e('Besides page and post above, you can set any content type to be available with KingComposer such as gallery, contact form and so on', 'kingcomposer'); ?>.
								</p>
								<p>
									- <?php _e('If your', 'kingcomposer'); ?> <strong>"Custom Post-Type"</strong>
									<?php _e('does not show here? Please make sure that has been registered with parameter ', 'kingcomposer'); ?>
									<strong>"public = true"</strong>
								</p>
								<p>
									- <?php _e('Put this code on action "init" to force support', 'kingcomposer'); ?>:
									<br />
									<pre style="background:#fff">

	global $kc;
	// add single content type
	$kc->add_content_type( 'your-post-type-name' );
	// add multiple content types
	$kc->add_content_type( array( 'type-1', 'type-2' ) );

									</pre>
								</p>
							</span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<?php _e('Fonts using', 'kingcomposer'); ?>:
						</th>
						<td>
							<p>
								<a href="<?php echo admin_url('admin.php?page=kingcomposer&kc_action=fonts-manager&TB_iframe=true&width=1000&height=600'); ?>" class="button button-large button-primary thickbox" data-title="<?php _e('KingComposer Fonts Manager', 'kingcomposer'); ?>" aria-label="<?php _e('KingComposer Fonts Manager', 'kingcomposer'); ?>">
									<i style="float: left;margin-top: 8px;margin-right: 5px;" class="dashicons dashicons-admin-settings"></i>
									<?php _e('Open Fonts Manager', 'kingcomposer'); ?>
								</a>
							</p>
							<br />
							<ul id="kc-settings-fonts">
							<?php
								$kc_fonts = get_option('kc-fonts');
								if( is_array( $kc_fonts ) && count( $kc_fonts ) > 0 ){
									foreach( $kc_fonts as $name => $cf ){
										echo '<li><i class="dashicons dashicons-admin-customizer"></i> '.urldecode($name).'</li>';
									}
								}else{
									echo '<li style="border:none;"><i class="dashicons dashicons-warning"></i> No fonts</li>';
								}
							?>
							</ul>
							<span class="description">
								<p>
									<?php _e('List of all external fonts that using in your site. The data is stored in ', 'kingcomposer'); ?><strong style="color: red;">get_option('kc-fonts')</strong>.
								</p>
							</span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<?php _e('Max width container', 'kingcomposer'); ?>:
						</th>
						<td>
							<input type="text" name="kc_options[max_width]" class="regular-text" value="<?php
								echo esc_html( $settings['max_width'] );
							?>" />
							<span class="description">
								<p>
									<?php _e('The default of container width is 1170px, you can change it to fit with yours', 'kingcomposer'); ?>.
								</p>
							</span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<?php _e('Disable Animate', 'kingcomposer'); ?>:
						</th>
						<td>
							<span class="description">
								<p>
									<input id="kc-settings-animate" type="checkbox" name="kc_options[animate]" value="disabled"<?php
								if( esc_html( $settings['animate'] ) == 'disabled' )
									echo ' checked';
							?> /> <label for="kc-settings-animate"><?php _e('Disable animate to prevent loading resources and effects', 'kingcomposer'); ?>.</label>
								</p>
							</span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<?php _e('Disable Instantor', 'kingcomposer'); ?>:
						</th>
						<td>
							<span class="description">
								<p>
									<input id="kc-settings-instantor" type="checkbox" name="kc_options[instantor]" value="disabled"<?php
								if(isset($settings['instantor']) && esc_html( $settings['instantor'] ) == 'disabled' )
									echo ' checked';
							?> /> <label for="kc-settings-instantor"><?php _e('Disable inline text editor on live editor mode', 'kingcomposer'); ?>.</label>
								</p>
							</span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<?php _e('Css Code', 'kingcomposer'); ?>:
						</th>
						<td>
							<textarea name="kc_options[css_code]" cols="100" rows="15"><?php
								echo esc_html( $settings['css_code'] );
							?></textarea>
							<span class="description">
								<p>
									<?php _e('Add your custom CSS code to modify or apply additional styling to the Front-End', 'kingcomposer'); ?>.
								</p>
							</span>
						</td>
					</tr>
				</tbody>
			</table>
			<br />
			<p class="submit">
				<input type="submit" class="button button-large button-primary" value="<?php _e('Save Change', 'kingcomposer'); ?>" />
    		</p>
			<?php
			$kc_general_tab = ob_get_contents();
			ob_end_clean();
			echo $kc->apply_filters('kc_setting_general', $kc_general_tab);
			?>
		</div>
		<div id="kc_product_license" class="group p" style="display:none">
			<?php
			ob_start();
			?>
			<div class="kc-license-notice"></div>
			<h3>
				<?php _e('Verify your license key for KC Pro!', 'kingcomposer'); ?>
			</h3>
			<hr />
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<?php _e('Your License Key', 'kingcomposer'); ?>:
						</th>
						<td>
							<input id="kc-pro-license-inp" type="password" class="regular-text kc-license-key" name="kc_options[license]" value="<?php

								if( !isset( $pdk['key'] ) || empty( $pdk['key'] ) ){
									if( defined('KC_LICENSE') )
										echo KC_LICENSE;
								}else echo esc_attr( $pdk['key'] );

							?>" autocomplete="off" />
							<?php
								if( $kc->check_pdk() === 1 && $pdk['pack'] != 'trial' ){
									echo '<span class="verified"><i class="dashicons dashicons-yes"></i>Verified</span>';
									echo '<a href="#" id="kc-revoke-license" class="revoke-website"><i class="dashicons dashicons-dismiss"></i> '.__('Revoke license', 'kingcomposer').' </a>';
								}else{
									echo '<span class="unverified"><i class="dashicons dashicons-no"></i>Unverified</span>';
								}
							?>
							<a href="#" class="see-key">
								<i class="dashicons dashicons-visibility"></i>
								<?php _e('see', 'kingcomposer'); ?>
							</a>
							<input type="hidden" name="sercurity" value="<?php
								echo wp_create_nonce('kc-verify-nonce');
							?>" />
							<span class="description">
								<p>
									<?php _e('You can find your license by login to', 'kingcomposer'); ?>
									<a href="https://kingcomposer.com/my-account/" target=_blank>
										<?php _e('My Account', 'kingcomposer'); ?>
									</a>.
									<?php _e('If you don\'t have an account yet', 'kingcomposer'); ?>
									<a href="https://kingcomposer.com/pricing/" target=_blank>
										<?php _e('Sign Up Now!', 'kingcomposer'); ?>
									</a>
								</p>
							</span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<?php _e('The theme you are using', 'kingcomposer'); ?>:
						</th>
						<td>
							<p>
								<input type="text" readonly="true" class="regular-text" value="<?php
									echo sanitize_title( basename( get_template_directory() ) );
								?>" />
							</p>
						</td>
					</tr>
				</tbody>
			</table>
			<br />
			<p class="submit">
				<button type="submit" id="kc-settings-verify-btn" class="button button-large button-primary">
					<i class="dashicons dashicons-admin-network"></i> <?php _e('Verify your license now', 'kingcomposer'); ?>
				</button>
    		</p>
			<?php
			$kc_license_tab = ob_get_contents();
			ob_end_clean();
			echo $kc->apply_filters('kc_setting_license', $kc_license_tab);
			?>
		</div>
		<div id="kc_pro" class="group p" style="display:none">
			<?php
			ob_start();
			?>
			<?php do_action('kc-pro-settings-tab'); ?>
			<?php
			$kc_setting_pro = ob_get_contents();
			ob_end_clean();
			echo $kc->apply_filters('kc_setting_pro', $kc_setting_pro);
			?>
		</div>
		<div id="kc_changelogs" class="group p" style="display:none">
			<?php
			ob_start();
			?>
			<div id="kc_changelogs_body">
				<?php kc_changelogs(); ?>
			</div>
			<?php
			$kc_changelogs = ob_get_contents();
			ob_end_clean();
			echo $kc->apply_filters('kc_setting_changelogs', $kc_changelogs);
			?>
		</div>
		<input type="hidden" name="_ajax_nonce" value="<?php echo wp_create_nonce('_ajax_nonce'); ?>" />
		<input type="hidden" name="_ajax_updates_nonce" id="kc-nonce-updates" value="<?php echo wp_create_nonce( 'updates' ); ?>" />
	</form>
</div>
<script type="text/javascript" src="<?php echo esc_url(KC_URL); ?>/assets/js/kc.settings.js"></script>
<script type="text/javascript">
	window.kc_fonts_update = function( datas ){

		jQuery('#kc-settings-fonts').html('');

		if( Object.keys(datas).length === 0 ){
			jQuery('#kc-settings-fonts').html('<li style="border:none;"><i class="dashicons dashicons-warning"></i> No fonts</li>');
		}else{
			for( var i in datas ){
				jQuery('#kc-settings-fonts').append('<li><i class="dashicons dashicons-admin-customizer"></i> '+decodeURIComponent(i)+'</li>');
			}
		}
	}
	window.addEventListener("message", function(arg){
		if (arg.data !== undefined)
		{
			var data = JSON.parse (arg.data);
			if (data.action == 'update-plugin')
			{
				jQuery('#TB_window, #TB_overlay').remove();
				jQuery('body.modal-open').removeClass('modal-open');
				jQuery('a.kc-update-link-ajax[data-slug="'+data.data.slug+'"]').trigger('click');
			}
		}
	}, false);
</script>

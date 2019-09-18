<?php
/**
*
*	King Composer
*	(c) KingComposer.com
*	kc.store.tmpl.php
*
*/

if(!defined('ABSPATH')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

?>
<h1><?php _e('Installing Extension from uploaded file', 'kingcomposer'); ?>: <?php echo esc_html($upload[0]); ?></h1>
<?php 
	if (count($errors) > 0) {
	?>
		<div class="kc-notice error" style="margin-top:30px;">
		<?php foreach ($errors as $error) {
			echo '<p>Error: '.esc_html($error).'</p>';
		} ?>
		</div>
		<p>
			<a href="<?php echo admin_url('admin.php?page=kc-extensions&tab=installed'); ?>">
				<?php _e('Back to extentions', 'kingcomposer'); ?>
			</a>
		</p>
		<div class="upload-plugin" style="display: block;">
			<p class="install-help">
				<?php _e('If you have an extension in a .zip format, you may install it by uploading it here.', 'kingcomposer'); ?>
			</p>
			<form method="post" enctype="multipart/form-data" class="wp-upload-form" action="">
				<p>
					<input type="hidden" name="kc-nonce" value="<?php echo wp_create_nonce('kc-nonce'); ?>" />
					<label class="screen-reader-text" for="extensionzip"><?php _e('Extension zip file', 'kingcomposer'); ?></label>
					<input type="file" name="extensionzip" id="extensionzip" />
					<input type="hidden" name="action" value="upload" />
					<input type="hidden" name="action" value="upload" />
					<input type="hidden" name="kc-extension-action" value="upload" />
					<input type="submit" name="install-kcextension-submit" class="button" value="<?php _e('Install Now', 'kingcomposer'); ?>" disabled="" />
				</p>
			</form>
		</div>
		
<?php } else {?>
	<p><?php _e('Uploading package', 'kingcomposer'); ?></p>
	<p><?php _e('Unpacking extension', 'kingcomposer'); ?></p>
	<p><?php _e('Installed successful', 'kingcomposer'); ?></p>
	<p>
		<form method="post" action="<?php echo admin_url('admin.php?page=kc-extensions&tab=installed'); ?>">
			<button class="button button-primary"><?php _e('Active Extension', 'kingcomposer'); ?></button>
			<input type="hidden" name="action" value="bulk-activate" />
			<input type="hidden" name="checked[]" value="<?php echo sanitize_title($upload[1]); ?>" />
			<input type="hidden" name="kc-nonce" value="<?php echo wp_create_nonce('kc-nonce'); ?>" />
			<input type="hidden" name="kc-extension-action" value="active now" />
		</form>
	</p>
	<p>
		<a href="<?php echo admin_url('admin.php?page=kc-extensions&tab=installed'); ?>">
			<?php _e('Return to extentions', 'kingcomposer'); ?>
		</a>
	</p>
<?		
	}
?>
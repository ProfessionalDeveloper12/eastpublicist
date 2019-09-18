<?php
/**
*
*	King Composer
*	(c) KingComposer.com
*	kc.screen.tmpl.php
*
*/
if(!defined('ABSPATH')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}
?>
<div class="wrap">
<?php
	if ($this->tab == 'upload') {
		do_action('kc_list_extensions_'.$this->tab, $this->page);
	} else {
?>
	<h1 class="wp-heading-inline"><?php _e('KC Extensions', 'kingcomposer'); ?></h1>
	<?php if (empty($_GET['tab']) || $_GET['tab'] == 'store'){ ?>
	<a href="#upload-extension" class="upload-view-toggle page-title-action" role="button" aria-expanded="false">
		<span class="upload"><?php _e('Upload Extension', 'kingcomposer'); ?></span>
		<span class="browse"><?php _e('Browse Plugins', 'kingcomposer'); ?></span>
	</a>
	<hr class="wp-header-end">
	<p><font color="#888"><?php _e('If you have a KC Pro license. You can access all the extensions for free, multiple domains.', 'kingcomposer'); ?><?php
		if (!class_exists('kc_pro')) {
			echo ' <a href="'.admin_url('admin.php?page=kingcomposer#kc_pro').'">'.__('Go to KC Pro!', 'kingcomposer').'</a>';
		} else {
			echo ' <a href="'.admin_url('admin.php?page=kingcomposer#kc_product_license').'">'.__('KC Pro license', 'kingcomposer').'</a>';
		}
	?></font></p>
	<div class="upload-plugin-wrap">
		<div class="upload-plugin">
			<p class="install-help">
				<?php _e('If you have an extension in a .zip format, you may install it by uploading it here.', 'kingcomposer'); ?>
			</p>
			<form method="post" enctype="multipart/form-data" class="wp-upload-form" action="">
				<input type="hidden" name="kc-nonce" value="<?php echo wp_create_nonce('kc-nonce'); ?>" />
				<label class="screen-reader-text" for="extensionzip"><?php _e('Extension zip file', 'kingcomposer'); ?></label>
				<input type="file" name="extensionzip" id="extensionzip" />
				<input type="hidden" name="action" value="upload" />
				<input type="hidden" name="kc-extension-action" value="upload" />
				<input type="submit" name="install-kcextension-submit" class="button" value="Install Now" disabled="" />
			</form>
		</div>
	</div>
	<?php } else { ?>
	<a href="<?php echo admin_url('admin.php?page=kc-extensions&tab=store'); ?>" class="upload-view-toggle page-title-action" role="button" aria-expanded="false"><span class="upload"><?php _e('Add new', 'kingcomposer'); ?></span></a>
	<?php } ?>
	<?php if (empty($_GET['tab']) || $_GET['tab'] == 'store'){ ?>
	<div class="wp-filter">
		<ul class="filter-links">
			<li class="kc-extension-all">
				<a href="admin.php?page=kc-extensions&tab=store&filter=all" class="<?php 
					if (empty($_GET['filter']) || $_GET['filter'] == 'all')
						echo 'current'; 
				?>">
					<?php _e('All extensions', 'kingcomposer'); ?>
				</a>
			</li>
			<li class="kc-extension-store">
				<a href="admin.php?page=kc-extensions&tab=store&filter=free" class="<?php 
					if (!empty($_GET['filter']) && $_GET['filter'] == 'free')
						echo 'current'; 
				?>">
					<?php _e('Free extensions', 'kingcomposer'); ?>
				</a> 
			</li>
			<li class="kc-extension-updates">
				<a href="admin.php?page=kc-extensions&tab=store&filter=featured" class="<?php 
					if (!empty($_GET['filter']) && $_GET['filter'] == 'featured')
						echo 'current'; 
				?>">
					<?php _e('Featured', 'kingcomposer'); ?>
				</a>
			</li>
			<li class="kc-extension-updates">
				<a href="admin.php?page=kc-extensions&tab=installed">
					<?php _e('Installed', 'kingcomposer'); ?>
				</a>
			</li>
		</ul>
		<form class="search-form search-extensions" method="get" id="kc-extension-search">
			<input type="hidden" name="tab" value="<?php echo isset($_GET['tab'])? $_GET['tab'] : ''; ?>">
			<input type="hidden" name="page" value="kc-extensions">
			<label class="screen-reader-text" for="typeselector"><?php _e('Search extensions by', 'kingcomposer'); ?>:</label>
			<label>
				<span class="screen-reader-text"><?php _e('Search Extensions', 'kingcomposer'); ?></span>
				<input type="search" name="q" class="wp-filter-search" placeholder="<?php _e('Search Extensions', 'kingcomposer'); ?>" value="<?php echo isset($_GET['q']) ? esc_html($_GET['q']) : ''; ?>" aria-describedby="live-search-desc">
			</label>
			<input type="submit" id="search-submit" class="button hide-if-js" value="<?php _e('Search Extensions', 'kingcomposer'); ?>">	
		</form>
	</div>
	<?php } ?>
	<form id="extensions-filter" method="post">
		<?php do_action('kc_list_extensions_'.$this->tab, $this->page); ?>
		<input type="hidden" name="kc-nonce" id="kc-nonce" value="<?php echo wp_create_nonce('kc-nonce'); ?>" />
		<input type="hidden" name="kc-extension-action" value="filter" />
	</form>
</div>
<?php } ?>
<script type="text/javascript" src="<?php echo esc_url(KC_URL); ?>/assets/js/kc.settings.js"></script>

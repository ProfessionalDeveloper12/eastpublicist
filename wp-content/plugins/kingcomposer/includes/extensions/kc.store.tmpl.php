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

add_thickbox();

$url = admin_url('admin.php?page=kc-extensions&tab=store');
$query = isset($_GET['q']) && !empty($_GET['q']) ? esc_html(strtolower($_GET['q'])) : '';
$paged = isset($_GET['paged']) && !empty($_GET['paged']) ? esc_attr($_GET['paged']) : 1;
?>
<div class="wp-list-table widefat extension-install">
<?php
	
if (count($items) > 0) {
	for ($i=0; $i < count($items); $i++) {
		
		$item = $items[$i];
		
?>
	<div class="plugin-card plugin-card-<?php echo sanitize_title($item['name']); ?>">
		<div class="plugin-card-top">
			<div class="name column-name">
				<h3>
					<?php if (isset($item['details']) && !empty($item['details'])) { ?>
					<a href="<?php echo esc_url($item['details']); ?>" class="thickbox open-plugin-details-modal">
					<?php } else { ?>
					<a href="#" class="">
					<?php } ?>
						<?php echo esc_html($item['name']); ?>
						<?php echo isset($item['featured']) && $item['featured'] == 1 ? '<font title="Featured" color="green">&star;</font>' : ''; ?>
						<img src="<?php echo esc_url($item['thumbnail']); ?>" class="plugin-icon" alt="">
					</a>
				</h3>
			</div>
			<div class="action-links">
				<ul class="plugin-action-buttons">
					<li>
						<price style="color: green;font-weight: bold;"><?php 
							echo (!empty($item['currency']) && isset($item['currency']) ? esc_html($item['currency']) : '');
							echo (empty($item['price']) || $item['price'] === 0) ? 'Free' : esc_html($item['price']); 
						?></price>
					</li>
					<li>
						<?php if (!isset($installs[$item['id']])) { ?>
						<a class="install-now button" data-verify="<?php 
							echo (empty($item['price']) || $item['price'] === 0) ? '1' : 
								 (!class_exists('kc_pro') ? '0' : (!empty($key) ? '1' : '2')); 
						?>" href="#<?php echo esc_html($item['id']); ?>">
							<?php _e('Install Now', 'kingcomposer'); ?>
						</a>
						<?php } else { ?>
						<a class="install-now button <?php 
							echo (
								(isset($actives[$item['id']]) && $actives[$item['id']] == 1) ? 
								'button-link-delete' : 
								'button-primary'
							); 
						?>" data-verify="1" data-installed="true" href="#<?php echo esc_html($item['id']); ?>">
							<?php 
								echo (isset($actives[$item['id']]) && $actives[$item['id']] == 1) ? 
								_e('Deactive', 'kingcomposer') :  
								_e('Active Now', 'kingcomposer'); 
							?>
						</a>
						<?php } ?>
					</li>
					<?php if (isset($item['details']) && !empty($item['details'])) { ?>
					<li>
						<a class="thickbox" href="<?php echo esc_url($item['details']); ?>">
							<?php _e('More details', 'kingcomposer'); ?>
						</a>
					</li>
					<?php } ?>
				</ul>				
			</div>
			<div class="desc column-description">
				<p><?php echo $item['description']; ?></p>
				<p class="authors">
					<cite>
						<?php _e('By', 'kingcomposer'); ?> 
						<a href="<?php echo esc_url($item['author_link']); ?>" target=_blank>
							<?php echo esc_html($item['author']); ?>
						</a>
					</cite>
				</p>
			</div>
		</div>
		<div class="plugin-card-bottom">
			<div class="column-updated">
				<strong>
					<?php _e('Last Updated', 'kingcomposer'); ?>:
				</strong> 
				<?php echo esc_html($item['last_updated']); ?>		
			</div>
			<div class="column-downloaded">
				<?php _e('Download', 'kingcomposer'); ?> <?php echo esc_html($item['download']); ?> | 
				<?php _e('Version', 'kingcomposer'); ?> <?php echo esc_html($item['version']); ?> 
			</div>
		</div>
	</div>
<?php
	}
} else {
	echo '<center><h2 style="color: #888; margin-top: 50px">'.__('No items found', 'kingcomposer').'</h2></center>';;
}
?>
</div>
<?php if ($pages > 1) { ?>
<div class="tablenav bottom">
	<div class="tablenav-pages">
		<span class="displaying-num"><?php echo $total; ?> <?php _e('items', 'kingcomposer'); ?></span>
		<span class="pagination-links">
			<?php if($paged == 1) { ?>
			<span class="tablenav-pages-navspan" aria-hidden="true">«</span>
			<span class="tablenav-pages-navspan" aria-hidden="true">‹</span>
			<?php } else { ?>
			<a class="last-page" href="<?php echo esc_url($url); ?>">
				<span class="screen-reader-text"><?php _e('First page', 'kingcomposer'); ?></span>
				<span aria-hidden="true">«</span>
			</a>
			<a class="prev-page" href="<?php echo esc_url($url.'&paged='.($paged-1)); ?>">
				<span class="screen-reader-text"><?php _e('Prev page', 'kingcomposer'); ?></span>
				<span aria-hidden="true">‹</span>
			</a>
			<?php } ?>
			<span class="screen-reader-text"><?php _e('Current Page', 'kingcomposer'); ?></span>
			<span id="table-paging" class="paging-input">
				<span class="tablenav-paging-text"><?php _e('Page', 'kingcomposer'); ?> <?php echo $paged; ?> of <span class="total-pages"><?php echo $pages; ?></span></span>
			</span>
			<?php if($paged < $pages) { ?>
			<a class="next-page" href="<?php echo esc_url($url.'&paged='.($paged+1)); ?>">
				<span class="screen-reader-text"><?php _e('Next page', 'kingcomposer'); ?></span>
				<span aria-hidden="true">›</span>
			</a>
			<a class="last-page" href="<?php echo esc_url($url.'&paged='.$pages); ?>">
				<span class="screen-reader-text"><?php _e('Last page', 'kingcomposer'); ?></span>
				<span aria-hidden="true">»</span>
			</a>
			<?php } else { ?>
			<span class="tablenav-pages-navspan" aria-hidden="true">›</span>
			<span class="tablenav-pages-navspan" aria-hidden="true">»</span>
			<?php } ?>
		</span>
	</div>				
	<br class="clear">
</div>
<?php } ?>
<div id="kc-extension-notice">
	<div id="kc-extension-notice-body">
		<p>
			<?php 
				if (!class_exists('kc_pro')){
					_e('We are sorry! extensions are currently only available for the pro version.', 'kingcomposer');
				} else if (empty($key)){
					_e('We are sorry! you need to verify your license before using extentions.', 'kingcomposer');
				}
			?>
		</p>
		<a href="#close"><i class="dashicons dashicons-no-alt"></i></a>
		<a href="<?php echo admin_url('admin.php?page=kingcomposer#').(!class_exists('kc_pro') ? 'kc_pro' : 'kc_product_license'); ?>" class="prim"><?php _e('Go to KC Pro!', 'kingcomposer'); ?></a>
	</div>
</div>
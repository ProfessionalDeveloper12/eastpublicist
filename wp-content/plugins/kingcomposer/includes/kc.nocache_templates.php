<?php
/**
*
*	King Composer
*	(c) KingComposer.com
*
*/
if(!defined('KC_FILE')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

global $kc;

?>
<div id="kc-right-click-helper"><i class="sl-close"></i></div>
<div style="display:none;" id="kc-storage-prepare">
	<div id="kc-css-box-test"></div>
</div>
<img width="50" src="<?php echo KC_URL; ?>/assets/images/drag.png" id="kc-ui-handle-image" />
<img width="50" src="<?php echo KC_URL; ?>/assets/images/drag-copy.png" id="kc-ui-handle-image-copy" />
<div id="kc-undo-deleted-element">
	<a href="javascript:void(0)" class="do-action">
		<i class="sl-action-undo"></i> <?php _e('Restore deleted items', 'kingcomposer'); ?>
		<span class="amount">0</span>
	</a>	
	<div id="drop-to-delete"><span></span></div>
	<i class="sl-close"></i>	
</div>
<script type="text/html" id="tmpl-kc-top-nav-template">
<?php do_action('kc-top-nav'); ?>
</script>
<script type="text/html" id="tmpl-kc-wp-widgets-template">
<div id="kc-wp-list-widgets"><?php 
	
	if( !function_exists( 'submit_button' ) ){
		function submit_button( $text = null, $type = 'primary', $name = 'submit', $wrap = true, $other_attributes = null ) {
			echo kc_get_submit_button( $text, $type, $name, $wrap, $other_attributes );
		}
	}
	
	ob_start();
		wp_list_widgets();
		$content = str_replace( array( '<script', '</script>' ), array( '&lt;script', '&lt;/script&gt;' ), ob_get_contents() );
	ob_end_clean();
	
	echo $content;
	
?></div>
</script>
<?php do_action('kc_tmpl_nocache'); ?>



<?php
/**
*
*	King Composer
*	(c) KingComposer.com
*	kc.extension.php
*
*/
if(!defined('ABSPATH')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}
?>
<?php if (count($this->errors) > 0) { ?>
<div id="message" class="error">
	<p><?php _e('There were some errors with the extensions are activated', 'kingcomposer'); ?>:</p>
	<ol>
		<?php
			foreach ($this->errors as $error) {
				echo '<li>'.$error.'</li>';
			}
		?>
	</ol>
</div>
<?php } ?>

<?php
$this->list_table( $items, $actives );
?>

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

$kc_fonts = get_option('kc-fonts');

if( !is_array( $kc_fonts ) )
	$kc_fonts = array();

$count = count($kc_fonts);

?>

<div id="kc-fonts-manager">
	<div id="kc-ggf-header">
		<h3>
			<?php _e('Fonts Manager', 'kingcomposer'); ?>
			<small></small>
		</h3>
		<div id="kc-ggf-hright">
			<input type="search" id="kc-ggf-search" placeholder="<?php _e('Search by name', 'kingcomposer'); ?>" />
			<i class="sl-magnifier"></i>
		</div>
		<ul>
			<li>
				<select id="kc-ggf-filter">
					<option value="popularity"><?php _e('Sorting', 'kingcomposer'); ?></option>
					<option value="popularity">Popular</option>
					<option value="trending">Trending</option>
					<option value="style">Style</option>
					<option value="alpha">Alpha</option>
				</select>
			</li>
			<li>
				<select id="kc-ggf-language">
					<option value="">All subsets</option>
					<option value="arabic">Arabic</option>
					<option value="bengali">Bengali</option>
					<option value="cyrillic">Cyrillic</option>
					<option value="cyrillic-ext">Cyrillic Extended</option>
					<option value="devanagari">Devanagari</option>
					<option value="greek">Greek</option>
					<option value="greek-ext">Greek Extended</option>
					<option value="gujarati">Gujarati</option>
					<option value="hebrew">Hebrew</option>
					<option value="khmer">Khmer</option>
					<option value="latin">Latin</option>
					<option value="latin-ext">Latin Extended</option>
					<option value="tamil">Tamil</option>
					<option value="telugu">Telugu</option>
					<option value="thai">Thai</option>
					<option value="vietnamese">Vietnamese</option>
				</select>
			</li>
			<li>
				<select id="kc-ggf-category">
					<option value="">All Categories</option>
					<option value="serif">Serif</option>
					<option value="sans-serif">Sans Serif</option>
					<option value="display">Display</option>
					<option value="handwriting">Handwriting</option>
					<option value="monospace">Monospace</option>
				</select>
			</li>
			<li class="kc-ggf-added" data-action="my-fonts">
				<i class="fa-folder-open" data-action="my-fonts"></i> 
				<?php _e('Your Fonts', 'kingcomposer'); ?> 
				(<span data-action="my-fonts"><?php echo $count; ?></span>)
			</li>
			<li class="kc-ggf-load-time">
				<?php _e('Load Time', 'kingcomposer'); ?> 
				<?php
					if( $count < 4 )
						echo '<span>Fast</span>';
					else if( $count < 6 )
						echo '<span class="medium">Medium</span>';
					else if( $count < 9 )
						echo '<span class="slow">Slow</span>';
					else echo '<span class="slow">Very Slow</span>';
				?>
			</li>
		</ul>
	</div>
	<div id="kc-ggf-my-fonts">
		<div id="kc-ggf-mf-header">
			<span><?php _e('List fonts used in your site', 'kingcomposer'); ?></span> 
			<i>(<?php _e('Please remove unused fonts to make your site load faster', 'kingcomposer'); ?>)</i>
			<i class="sl-close" data-action="close-my-fonts"></i>
		</div>
		<div id="kc-ggf-mf-body"></div>
	</div>
	<div id="kc-ggf-body">
		<div id="kc-ggf-pagination-top" class="kc-ggf-pagination"></div>
		<div id="kc-ggf-render"><span class="kc-ggf-loading"><i class="fa-spinner fa-spin fa-2x fa-fw"></i></span></div>
		<div id="kc-ggf-pagination-bottom" class="kc-ggf-pagination"></div>
	</div>
	<div id="kc-ggf-footer">
		<?php 
		global $kc;
		echo $kc->apply_filters('kc_font_powered_by',__('Powered by Google - Development by KingComposer Team', 'kingcomposer')); ?>.
	</div>
</div>
<div id="kc-fonts-manager-resource"></div>
<div id="kc-fonts-manager-api"></div>
<script type="text/javascript">
	jQuery('#wpadminbar,#wpfooter,#adminmenuwrap,#adminmenuback,#adminmenumain,#screen-meta').remove();
	var kc_fonts_nonce = '<?php echo wp_create_nonce( "kc-fonts-nonce" ); ?>';
	var kc_my_fonts = <?php echo json_encode( $kc_fonts ); ?>
</script>
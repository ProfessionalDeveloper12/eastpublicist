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
ob_start();
?>
<div class="wrap about-wrap">
	<h1><?php _e('KC Shortcode Mapper', 'kingcomposer'); ?></h1>
	<p class="about-text">
		<?php _e('Mapping custom 3rd party shortcodes to add & edit by KingComposer', 'kingcomposer'); ?>.
	<br />
		<?php _e('Shortcodes need to be installed, or you can do it by following', 'kingcomposer'); ?>
		<a href="http://docs.kingcomposer.com/display-the-output-of-the-shortcode/" target=_blank>
			<?php _e('This Article', 'kingcomposer'); ?>
		</a>
	</p>
	<div id="kc-mapper-list">
		<div class="item add-new">
			<i class="sl-plus"></i>
			<br />
			<?php _e('New Shortcode', 'kingcomposer'); ?>
		</div>
		<div class="item import-export">
			<i class="sl-refresh"></i>
			<br />
			<?php _e('Import / Export', 'kingcomposer'); ?>
		</div>
	</div>
	<pre class=kc-mapper-pre>
// Data stored in wp option "kc_shortcodes_mapper: get_option('kc_shortcodes_mapper', true);
// Use method "kc_include_map" to include exported_map_file in your theme: kc_include_map($path);
	</pre>
</div>
<div id="kc-mapper-overlay">
	<div id="kc-mapper-settings">
		<div id="kc-mapper-screen-add-new" class="kc-mapper-screen">
			<h2 class="mp-title"><?php _e('Add new shortcode map', 'kingcomposer'); ?></h2>
			<p class="error"></p>
			<strong><?php _e('Enter shortcode name or full shortcode string', 'kingcomposer'); ?>:</strong>
			<textarea id="kc-mapper-string" class="mmp"></textarea>
			<p class="desc">
				<?php _e('Example', 'kingcomposer'); ?>: <strong>contact-form-7</strong>
				<?php _e('or full string', 'kingcomposer'); ?>
				<strong>[contact-form-7 id="1" title="Contact form 1"]</strong>
			</p>
			<p>
				<button class="color mbtn" id="kc-mapper-parse"><i class="fa-flash"></i> <?php _e('Parse Shortcode', 'kingcomposer'); ?></button>
				<button class="kc-mapper-settings-close mbtn"><i class="fa-times"></i> <?php _e('Cancel', 'kingcomposer'); ?></button>
			</p>
		</div>
		<div id="kc-mapper-screen-edit" class="kc-mapper-screen">
			<div id="kc-mapper-shortcode-info">
				<div class="kc-mp-sc-icon">
					<div class="icons-preview">
						<i class="fa-star"></i>
					</div>
					<input type="hidden" name="icon" class="kc-param kc-param-icons infp" />
				</div>
				<div class="kc-mp-sc-info">
					<label><?php _e('Shortcode Tag', 'kingcomposer'); ?>:</label>
					<input type="text" name="tag" class="infp" />
				</div>
				<div class="kc-mp-sc-info">
					<label><?php _e('Name', 'kingcomposer'); ?>:</label>
					<input type="text" name="name" class="infp" />
				</div>
				<div class="kc-mp-sc-info">
					<label><?php _e('Category', 'kingcomposer'); ?>:</label>
					<input type="text" name="category" class="infp" />
				</div>
				<div class="kc-mp-sc-info desc">
					<label><?php _e('Description', 'kingcomposer'); ?>:</label>
					<input type="text" name="description" class="infp" />
				</div>
				<div class="kc-mp-sc-info">
					<label><?php _e('Include content into', 'kingcomposer'); ?>?:</label>
					<input type="checkbox" name="is_container" class="infp" />
				</div>
				<div class="kc-mp-sc-shortcode-string">
					&nbsp;
				</div>
			</div>
			<div id="kc-mapper-fields"></div>
			<div id="kc-mapper-fields-preview" class="m-p-body"></div>
			<div id="kc-mapper-fields-btn">
				<button class="save-fields color mbtn"><i class="fa-check"></i> <?php _e('Save', 'kingcomposer'); ?></button>
				<button class="kc-mapper-settings-close mbtn"><i class="fa-times"></i> <?php _e('Cancel', 'kingcomposer'); ?></button>
				<button class="kc-mapper-settings-delete mbtn delete"><i class="fa-trash"></i> <?php _e('Delete', 'kingcomposer'); ?></button>
				<p>
					<?php _e('Need help how to display output of shortcode?', 'kingcomposer'); ?>
					<a href="http://docs.kingcomposer.com" target=_blank><?php _e('Check docs', 'kingcomposer'); ?></a>
				</p>
			</div>
		</div>
		<div id="kc-mapper-screen-import-export" class="kc-mapper-screen">
			<h2 class="mp-title">
				<a href="#export" class="active"><?php _e('Export', 'kingcomposer'); ?></a>
				<a href="#import"><?php _e('Import', 'kingcomposer'); ?></a>
			</h2>
			<div class="tab export" style="display: block;">
				<h2><?php _e('Export shortcodes mapper', 'kingcomposer'); ?>:</h2>
				<textarea name="export" class="mmp"></textarea>
				<p class="mmp-files">
					<input type="text" name="export-name" placeholder="<?php _e('Enter file name', 'kingcomposer'); ?>" value="shortcode_maps_<?php echo date("F-j-Y-gi"); ?>" class="mmp" />
					<button class="mbtn color do-export">
						<i class="fa-download"></i> <?php _e('Download File', 'kingcomposer'); ?> (*.kc)
					</button>
					<button class="kc-mapper-settings-close mbtn">
						<i class="fa-times"></i> <?php _e('Cancel', 'kingcomposer'); ?>
					</button>
					<a href="" class="download-anchor"></a>
				</p>
<pre>
// After download exported file, use method kc_include_map($path); to add maps automatically.
// $path is the absolute path of exported_file.kc
</pre>
			</div>
			<div class="tab import">
				<h2><?php _e('Import shortcodes mapper', 'kingcomposer'); ?>:</h2>
				<textarea name="import" placeholder="<?php _e('Enter your maps here', 'kingcomposer'); ?>" class="mmp"></textarea>
				<p>
					<strong><?php _e('Upload the maps file to import', 'kingcomposer'); ?> (*.kc):</strong><br />
					<input type="file" class="mbtn" />
				</p>
				<p>
					<input type="checkbox" class="mbtn" id="kc-mapper-import-overwrite" />
					<label for="kc-mapper-import-overwrite"><?php _e('Overwrite shortcode if exists?', 'kingcomposer'); ?></label>
				</p>
				<p>
					<button class="mbtn color do-import">
						<i class="fa-upload"></i> <?php _e('Import Now', 'kingcomposer'); ?>
					</button>
					<button class="kc-mapper-settings-close mbtn">
						<i class="fa-times"></i> <?php _e('Cancel', 'kingcomposer'); ?>
					</button>
				</p>
			</div>
		</div>
	</div>
</div>
<?php
$kc_shortcodes_mapper = ob_get_contents();
ob_end_clean();
echo $kc->apply_filters('kc_shortcodes_mapper', $kc_shortcodes_mapper);
?>
<div style="display: none;"><?php wp_editor('', 'kc-editor-preload'); ?></div>
<script type="text/javascript" src="<?php echo esc_url(KC_URL); ?>/assets/js/kc.mapper.js"></script>
<script type="text/javascript">
	var kc_mapper_shortcodes = <?php
		$mapper = get_option('kc_shortcodes_mapper', true);
		if (!$mapper || !is_array($mapper))
			echo '{}';
		else echo json_encode($mapper);
	?>, kc_mapper_nonce = '<?php echo wp_create_nonce( "kc-mapper-nonce" ); ?>';
</script>
<script type="text/html" id="tmpl-kc-mapper-field-template">
	<div class="field_row field_row_param<# if (data.name == 'content'){ #> content_include<# } #>">
		<h3 class="field-heading">
			<span>{{(data.label !== '') ? data.label : data.name}}</span>
			<i class="fa-times" data-action="delete-field" title="Delete param"></i>
		</h3>
		<div class="field-row-body">
			<div class="values-fields">
				<label><?php _e('Param name', 'kingcomposer'); ?>:</label>
				<input name="name" value="{{data.name}}" class="kc-mapper-inp" type="text" <# if (data.name == 'content'){ #> disabled<# } #> />
				<p><?php _e('The id of param', 'kingcomposer'); ?></p>
				<label><?php _e('Param label', 'kingcomposer'); ?>:</label>
				<input name="label" value="{{data.label}}" class="kc-mapper-inp" type="text" />
				<p><?php _e('Heading of param', 'kingcomposer'); ?></p>
				<label><?php _e('Field type', 'kingcomposer'); ?>:</label>
				<#

					if (data.level == 1)
					{
						if (data.name == 'content'){

							var fields_support = {
								textarea_html: 'Textarea Html',
								text: 'Text field',
								textarea: 'Textarea',
							}

						}else{

							var fields_support = {
								text: 'Text field',
								textarea: 'Textarea',
								toggle: 'Toggle',
								dropdown: 'Dropdown',
								radio: 'Radio',
								checkbox: 'Checkbox',
								radio_image: 'Radio Image',
								group: 'Group Fields',
								editor: 'Editor',
								color_picker: 'Color Picker',
								date_picker: 'Date Picker',
								icon_picker: 'Icon Picker',
								post_taxonomy: 'Post Taxonomy',
								number_slider: 'Number Slider',
								link: 'Link',
								autocomplete: 'Autocomplete',
								attach_image: 'Media (return ID)',
								attach_images: 'Multiple Media (return IDs)',
								attach_image_url: 'Media (return url)',
								hidden: 'Hidden'
							}
						}
					} else {

						// fields level 2 into group

						var fields_support = {
							text: 'Text field',
							textarea: 'Textarea',
							toggle: 'Toggle',
							dropdown: 'Dropdown',
							radio: 'Radio',
							checkbox: 'Checkbox',
							radio_image: 'Radio Image',
							editor: 'Editor',
							color_picker: 'Color Picker',
							date_picker: 'Date Picker',
							icon_picker: 'Icon Picker',
							post_taxonomy: 'Post Taxonomy',
							number_slider: 'Number Slider',
							link: 'Link',
							autocomplete: 'Autocomplete',
							attach_image: 'Media (return ID)',
							attach_images: 'Multiple Media (return IDs)',
							attach_image_url: 'Media (return url)',
							hidden: 'Hidden'
						}
					}
				#>

				<select name="type" class="kc-mapper-inp">
					<#
						for (n in fields_support) {
						#><option value="{{n}}"<# if (data.type == n){ #> selected<# } #>>{{fields_support[n]}}</option><#
						}
					#>
				</select>

				<p><?php _e('Select type for field', 'kingcomposer'); ?></p>

				<# if (data.level == 1){ #>

					<label><?php _e('Default value', 'kingcomposer'); ?>:</label>
					<input name="value" value="{{data.value}}" class="kc-mapper-inp" type="text" />
					<p><?php _e('The default value of field', 'kingcomposer'); ?></p>

					<label><?php _e('Admin label', 'kingcomposer'); ?>:</label>
					<p class="rdo">
						<# var randip = parseInt(Math.random()*10000); #>
						<input name="admin_label" value="1" <#
							if (data.admin_label === true){ #> checked<# }
						#> class="kc-mapper-inp" type="checkbox" id="kc-mapper-field-admin-label-{{randip}}" />
						<label for="kc-mapper-field-admin-label-{{randip}}">
							<?php _e('The value will show in preview', 'kingcomposer'); ?>
						</label>
					</p>

				<# }else{ #>
				<p>
					<?php _e('Need help how to set default value for field group?', 'kingcomposer'); ?>
					<a href="http://docs.kingcomposer.com/available-param-types/group-fields/" target=_blank><?php _e('Check docs', 'kingcomposer'); ?></a>
				</p>
				<# } #>
				<div class="dropdown-relation-hidden"<#
					if(['dropdown', 'radio', 'checkbox', 'number_slider', 'autocomplete', 'radio_image'].indexOf(data.type) > -1){
					#> style="display: block;"<#
					}
						 #>>
					<label><?php _e('Options', 'kingcomposer'); ?>:</label>
					<#
						var str = "";
						if (data.options !== '') {
							for (var n in data.options) {
								str += n+':'+data.options[n]+"\n"
							}
						}
					#>
					<textarea name="options" class="kc-mapper-inp" data-std-type="{{data.type}}" data-std-ops="{{str}}">{{{str}}}</textarea>
					<p><?php _e('Separate options by enter new line', 'kingcomposer'); ?></p>
				</div>
				<label><?php _e('Description', 'kingcomposer'); ?>:</label>
				<textarea name="description" class="kc-mapper-inp">{{data.description}}</textarea>
				<p><?php _e('Param Description', 'kingcomposer'); ?></p>

				<# if (data.level == 1 && data.name != 'content'){ #>
				<label><?php _e('Relation', 'kingcomposer'); ?>:</label>
				<#
					str = "";
					if (data.relation !== '') {
						for (var n in data.relation) {
							str += n+':'+data.relation[n]+"\n"
						}
					}
				#>
				<p class="rdo">
					<# randip = parseInt(Math.random()*10000); #>
					<input name="relation-op" value="1" <#
						if (str !== ''){ #> checked<# }
					#> class="kc-mapper-inp" type="checkbox" id="kc-mapper-field-admin-label-{{randip}}" />
					<label for="kc-mapper-field-admin-label-{{randip}}">
						<?php _e('Show or hide depending on the another field (Note: parent field must be placed above this field)', 'kingcomposer'); ?>
					</label>
					<textarea name="relation" class="kc-mapper-inp" data-std-vl="{{{str}}}" style="margin-top:10px;<#
							if (str === ''){ #> display: none;<# }
						#>">{{{str}}}</textarea>
					<# } #>
				</p>

			</div>
			<div class="groupfields-relation-hidden"<# if (data.type == 'group'){ #> style="display: block;"<#} #>>
				<strong><?php _e('Children fields', 'kingcomposer'); ?>:</strong>
				<input type="hidden" name="params" value="{{(data.params!=='')?JSON.stringify(data.params):''}}" class="kc-mapper-inp" />
				<div class="kc-group-fields-render"></div>
			</div>
		</div>
	</div>
<#
	data.callback = function(wrp, data) {

		wrp.find('>.field-heading').on('mousedown', function(e)
		{
			this.clientX = e.clientX;
			this.clientY = e.clientY;
		}).on('mouseup', function(e) {

			if (this.clientX == e.clientX && this.clientY == e.clientY)
			{

				if (e.target.getAttribute('data-action') == 'delete-field') {
					kc_mapper.field.delete(this);
					return;
				}

				var $this = jQuery(this),
					cur = $this.parent().find('>.field-row-body').css('display');

				$this.parent().parent().find('>.field_row>.field-row-body').hide();

				if (cur === 'block')
					cur = 'hidden';
				else cur = 'block';

				$this.parent().find('>.field-row-body').css('display', cur);

			}
		});

		wrp.find('.kc-mapper-inp').on('change', kc_mapper.field.change);

		if (data.type == 'group' && data.params !== '' && Object.keys(data.params).length > 0) {
			kc_mapper.field.render(
				wrp.find('.kc-group-fields-render'),
				data.params
			);
		}

	}
#>
</script>

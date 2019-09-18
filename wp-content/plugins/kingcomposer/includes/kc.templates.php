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
	
$kc = KingComposer::globe();
	
?>
<script type="text/html" id="tmpl-kc-container-template">
	<#
		var claz = [];
		if (kc.cfg.showTips == 0)
			claz.push('hideTips');
		if (jQuery('#kc-page-cfg-collapsed').val() == 'collapse'){
			claz.push('collapse');
		}
	#>
	<div id="kc-container" class="{{claz.join(' ')}}">
		<div id="kc-controls">
			<button class="button button-large red classic-mode">
				<i class="sl-action-undo"></i> 
				<?php _e('Classic Mode', 'kingcomposer'); ?>
			</button>
			{{{kc.template('top-nav')}}}
			<button class="button button-large alignright collapse mtips">
				<i class="sl-arrow-down"></i> 
				<span class="mt-mes"><?php _e('Expand / Collapse Builder', 'kingcomposer'); ?></span>
			</button>
			<button class="button button-large alignright post-settings mtips">
				<i class="sl-settings"></i> 
				<span class="mt-mes"><?php _e('Content Settings', 'kingcomposer'); ?></span>
			</button>
			<button class="button button-large alignright save-page-content mtips">
				<i class="sl-share-alt"></i>
				<span class="mt-mes"><?php _e('Save page content to section', 'kingcomposer'); ?></span>
			</button>
			<button class="button button-large alignright optimized-page mtips">
				<i class="sl-rocket"></i>
				<span class="mt-mes"><?php _e('Optimized and speed up your website', 'kingcomposer'); ?></span>
			</button>
			<button class="button button-large alignright quick-save mtips">
				<i class="sl-check"></i> <?php _e('Save now', 'kingcomposer'); ?>
				<span class="mt-mes"><?php _e('Press Ctrl+S to quick save', 'kingcomposer'); ?></span>
			</button>
		</div>
		
		<div id="kc-rows">
			<div id="kc-empty-screen">
				<h3><?php _e('You have a blank page', 'kingcomposer'); ?></h3>
				<p><?php _e('Add new element or row/column layout', 'kingcomposer'); ?></p>
			</div>
		</div>
		
		<div id="kc-footers">
			<ul>
				<li class="basic-add">
					<i class="et-expand"></i> 
					<?php _e('Elements', 'kingcomposer'); ?>
					<span class="m-a-tips"><?php _e('Browse all elements', 'kingcomposer'); ?></span>
				</li>
				<li class="one-column quickadd" data-content='[kc_row use_container="yes"][/kc_row]'>
					<span class="grp-column"></span>
					<span class="m-a-tips"><?php _e('Add an 1-column row', 'kingcomposer'); ?></span>
				</li>
				<li class="two-columns quickadd" data-content='[kc_row use_container="yes"][kc_column width="50%"][/kc_column][kc_column width="50%"][/kc_column][/kc_row]'>
					<span class="grp-column"></span>
					<span class="grp-column"></span>
					<span class="m-a-tips"><?php _e('Add a 2-column row', 'kingcomposer'); ?></span>
				</li>
				<li class="three-columns quickadd" data-content='[kc_row use_container="yes"][kc_column width="33.33%"][/kc_column][kc_column width="33.33%"][/kc_column][kc_column width="33.33%"][/kc_column][/kc_row]'>
					<span class="grp-column"></span>
					<span class="grp-column"></span>
					<span class="grp-column"></span>
					<span class="m-a-tips"><?php _e('Add a 3-column row', 'kingcomposer'); ?></span>
				</li>
				<li class="four-columns quickadd" data-content='[kc_row use_container="yes"][kc_column width="25%"][/kc_column][kc_column width="25%"][/kc_column][kc_column width="25%"][/kc_column][kc_column width="25%"][/kc_column][/kc_row]'>
					<span class="grp-column"></span>
					<span class="grp-column"></span>
					<span class="grp-column"></span>
					<span class="grp-column"></span>
					<span class="m-a-tips"><?php _e('Add a 4-column row', 'kingcomposer'); ?></span>
				</li>
				<li class="column-text quickadd" data-content="custom">
					<i class="et-document"></i>
					<span class="m-a-tips"><?php _e('Push customized content and shortcodes', 'kingcomposer'); ?></span>
				</li>
				<li class="title quickadd" data-content='paste'>
					<i class="et-clipboard"></i>
					<span class="m-a-tips"><?php _e('Paste copied element', 'kingcomposer'); ?></span>
				</li>
				<li class="kc-online-presets">
					<i class="et-genius"></i>
					<span class="m-a-tips"><?php _e('Sections/Templates KC Hub Online', 'kingcomposer'); ?></span>
				</li>
				<li class="kc-add-sections">
					<i class="et-layers"></i>
					<span class="m-a-tips"><?php _e('Sections/Templates Library', 'kingcomposer'); ?></span>
				</li>
			</ul>
		</div>
	</div>	
</script>
<script type="text/html" id="tmpl-kc-clipboard-template">
	<div id="kc-clipboard">
		<ul class="ms-funcs">
			<li>
				<strong><?php _e('Tips', 'kingcomposer'); ?>:</strong> 
				<?php _e('Drag and drop to arrange items, click to select an item. Read more', 'kingcomposer'); ?> 
				<a href="<?php echo esc_url('http://docs.kingcomposer.com/documentation/copy-cut-double-paste-for-element-column-row/?source=client_installed'); ?>" target="_blank"><?php _e('Document', 'kingcomposer'); ?></a>
			</li>
			<li class="delete button delete right">
				<?php _e('Delete selected', 'kingcomposer'); ?> <i class="sl-close"></i>
			</li>
			<li class="select button right">
				<?php _e('Select all', 'kingcomposer'); ?> <i class="sl-check"></i>
			</li>
			<li class="unselect button right">
				<?php _e('Unselect all', 'kingcomposer'); ?> <i class="sl-close"></i>
			</li>
		</ul>
		<#
		try{
			var clipboards = kc.backbone.stack.get( 'KC_ClipBoard' ), 
				outer = '<div style="text-align:center;margin:20px auto;"><?php _e('The ClipBoard is empty, Please copy elements to clipboard', 'kingcomposer'); ?>.</div>';
			
			if( clipboards.length > 0 ){
				
				var stack, map, li = '';
					
				for( var n in clipboards ){
					if( clipboards[n] != null && clipboards[n] != undefined ){
						
						stack = clipboards[n];
						map = kc.maps[stack.title];
						
						li += '<li data-sid="'+n+'" title="<?php _e('Click to select, drag to move', 'kingcomposer'); ?>">';
						if( map != undefined ){
							if( map['icon'] != undefined )
								li += '<span class="ms-icon cpicon '+map['icon']+'"></span>';
						}
						li += '<span class="ms-title">'+stack.title.replace(/\kc_/g,'').replace(/\_/g,' ').replace(/\-/g,' ')+'</span>';
						li += '<span class="ms-des">'+kc.tools.unesc(stack.des)+'</span>';
						li += '<i title="<?php _e('Paste now', 'kingcomposer'); ?>" class="ms-quick-paste fa-paste"></i></li>';
						
					}
				}
				
			}else{
				li = '<h2 class="align-center"><?php _e('No items found, please copy elements first.', 'kingcomposer'); ?></h2>';
			}
		}catch(e){console.log(e);}	
		#>
		<ul class="ms-list">{{{li}}}</ul>
	</div>
	<# 
		data.callback = kc.ui.clipboard;
	#>
</script>
<script type="text/html" id="tmpl-kc-post-settings-template">
	<div id="kc-page-settings">
		<h1 class="mgs-t02">
			<?php _e('Page Settings', 'kingcomposer'); ?>
		</h1>
		<button class="button pop-btn save-post-settings"><?php _e('Save', 'kingcomposer'); ?></button>
		<div class="m-settings-row">
			<div class="msr-left">
				<label><?php _e('Body Class', 'kingcomposer'); ?></label>
				<span><?php _e('The class will be added to body tag on the front-end', 'kingcomposer'); ?> </span>
			</div>
			<div class="msr-right">
				<div class="msr-content">
					<input data-page-setting="classes" type="text" placeholder="Body classes" value="{{data.classes}}" />
				</div>
			</div>
		</div>
		<div class="m-settings-row">
			<div class="msr-left">
				<label><?php _e('Max width container', 'kingcomposer'); ?></label>
				<span><?php _e('The max width of container for this page (default is 1170px). You also can change it in KC general settings', 'kingcomposer'); ?> </span>
			</div>
			<div class="msr-right">
				<div class="msr-content">
					<input data-page-setting="max_width" type="text" placeholder="Max width container" value="{{data.max_width}}" />
				</div>
			</div>
		</div>
		<div class="m-settings-row">
			<div class="msr-left msr-single">
				<label><?php _e('Css Code', 'kingcomposer'); ?></label>
				<button class="button button-larger css-beautifier float-right">
					<i class="sl-energy"></i> <?php _e('Beautifier', 'kingcomposer'); ?>
				</button>
				<textarea class="rt03" data-page-setting="css" >{{data.css}}</textarea>
				<i><?php _e('Notice: CSS must contain selectors', 'kingcomposer'); ?></i>
			</div>
		</div>
		
		<div class="m-settings-row">
			<div class="msr-left">
				<label><?php _e('Scroll Assistant', 'kingcomposer'); ?></label>
				<span>
					<?php _e('Keep the viewport in a reasonable place while a popup is opened', 'kingcomposer'); ?>.
					<?php if(!defined('KC_SLUG')||md5(KC_SLUG)!='0f882acc192505fa98c9a8e1167539a1')exit; ?>
				</span>
			</div>
			<div class="msr-right">
				<div class="msr-content">
					<div class="kc-el-ui meu-boolen" data-cfg="scrollAssistive" data-type="radio" onclick="kc.ui.elms(event,this)">
						<ul>
							<li<# if(kc.cfg.scrollAssistive==1){ #> class="active"<# } #>>
								<input type="radio" name="m-c-layout" value="1" />
							</li>
							<li<# if(kc.cfg.scrollAssistive!=1){ #> class="active"<# } #>>
								<input type="radio" name="m-c-layout" value="0" />
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		<div class="m-settings-row">
			<div class="msr-left">
				<label><?php _e('Scroll Prevention', 'kingcomposer'); ?></label>
				<span>
					<?php _e('Keep the web page unmoved while scrolling a popup', 'kingcomposer'); ?>.
				</span>
			</div>
			<div class="msr-right">
				<div class="msr-content">
					<div class="kc-el-ui meu-boolen" data-cfg="preventScrollPopup" data-type="radio" onclick="kc.ui.elms(event,this)">
						<ul>
							<li<# if(kc.cfg.preventScrollPopup==1){ #> class="active"<# } #>>
								<input type="radio" name="m-c-layout" value="1" />
							</li>
							<li<# if(kc.cfg.preventScrollPopup!=1){ #> class="active"<# } #>>
								<input type="radio" name="m-c-layout" value="0" />
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		<div class="m-settings-row">
			<div class="msr-left">
				<label><?php _e('Tooltips display', 'kingcomposer'); ?></label>
				<span>
					<?php _e('A brief description will appear when you hover the function icon', 'kingcomposer'); ?>.
				</span>
			</div>
			<div class="msr-right">
				<div class="msr-content">
					<div class="kc-el-ui meu-boolen showTipsCfg" data-cfg="showTips" data-type="radio">
						<ul>
							<li<# if(kc.cfg.showTips==1){ #> class="active"<# } #>>
								<input type="radio" name="m-c-layout" value="1" />
							</li>
							<li<# if(kc.cfg.showTips!=1){ #> class="active"<# } #>>
								<input type="radio" name="m-c-layout" value="0" />
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="m-settings-row">
			<div class="msr-left">
				<label><?php _e('Thumbnail', 'kingcomposer'); ?></label>
				<span>
					<?php _e('The thumbnail for this content, it will display in sections list', 'kingcomposer'); ?>.
				</span>
				<br />
				<img class="thumnail-preview" src="{{(data.thumbnail === undefined || data.thumbnail === '')?kc.cfg.defaultImg:data.thumbnail}}" alt="" />
				<br />
				<input type="text" placeholder="<?php _e('Image url', 'kingcomposer'); ?>" data-page-setting="thumbnail" name="m-c-thumbnail" value="{{data.thumbnail}}" />
			</div>
			<div class="msr-right">
				<div class="msr-content">
					<button class="button button-larger button-primary open-library"><?php _e('Open Library', 'kingcomposer'); ?></button>
				</div>
			</div>
		</div>
		<button class="button pop-btn save-post-settings"><?php _e('Save', 'kingcomposer'); ?></button>
	</div>
	<#
		data.callback = function( wrp, $ ){
			
			wrp.find('.save-post-settings').on( 'click', wrp, function(e){
				
				$('[data-page-setting]').each(function(){
					$('#kc-page-cfg-'+$(this).data('page-setting')).val(this.value);
				});
				
				kc.get.popup( this, 'close' ).trigger('click');
				
			});
			
			wrp.find('.css-beautifier').on( 'click', function(){
				var txta = $(this).parent().find('textarea');
				txta.val( kc.tools.decode_css( txta.val() ) );
			});
			
			wrp.find('.showTipsCfg').on( 'click', function(event){
				kc.ui.elms( event, this );
				if( kc.cfg.showTips == 1 )
					$('#kc-container').removeClass('hideTips');
				else $('#kc-container').addClass('hideTips');
			});
			
			wrp.find('.open-library').on('click', { callback : function( atts ){
		
				var wrp = $(this.el).closest('.m-settings-row'), url = atts.url;

				if( atts.size != undefined && atts.size != null && atts.sizes[atts.size] != undefined ){
					var url = atts.sizes[atts.size].url;
				}else if( typeof atts.sizes.medium == 'object' ){
					var url = atts.sizes.medium.url;
				}

				if( url != undefined && url != '' ){
					wrp.find('input[name="m-c-thumbnail"]').val(url);
					wrp.find('img.thumnail-preview').attr({src: url});
				}
				
			}, atts : {frame:'post'} }, kc.tools.media.open );
			
			wrp.find('input[name="m-c-thumbnail"]').on('change', function(){
				$(this).closest('.m-settings-row').find('img.thumnail-preview').attr({src: this.value});
			})
			
		}
	#>
</script>

<script type="text/html" id="tmpl-kc-optimized-settings-template">
	<div id="kc-page-settings" class="kc-optimize-settings kc-optimized-{{kc_global_optimized.enable}}">
		<h1 class="mgs-t02">
			<span><?php _e('Enable Optimized', 'kingcomposer'); ?>:</span>
			<div class="kc-ui-kit boolen">
				<input value="on" type="checkbox" {{(kc_global_optimized.enable=='on'?'checked':'')}} data-optimized="enable" />
				<label></label>
			</div>
			<button class="button clear-cache"><i class="fa-warning"></i> <?php _e('Clear Cache', 'kingcomposer'); ?></button>
		</h1>
		<div class="m-settings-row show-on">
			<div class="msr-left">
				<label><?php _e('Things that will be optimized on your website:', 'kingcomposer'); ?></label>
				<span>
					<ol>
						<li><?php _e('Pre-render all resources to static files', 'kingcomposer'); ?></li>
						<li><?php _e('Combined js internal & external resources', 'kingcomposer'); ?></li>
						<li><?php _e('Combined css internal & external resources', 'kingcomposer'); ?></li>
						<li><?php _e('Minify HTML, JS, CSS', 'kingcomposer'); ?></li>
						<li><?php _e('Prevent javascript blocking', 'kingcomposer'); ?></li>
						<li><?php _e('Compression and gzip for page speed', 'kingcomposer'); ?></li>
						<li><?php _e('Leverage browser caching', 'kingcomposer'); ?></li>
					</ol>
				</span>
			</div>
			<div class="msr-right">
				<i class="sl-rocket"></i>
			</div>
		</div>
		<div class="m-settings-row">
			<div class="msr-left">
				<label><?php _e('Active This Page', 'kingcomposer'); ?></label>
				<span>
					<?php _e('Force to active or deactive from global settings', 'kingcomposer'); ?>.
				</span>
			</div>
			<div class="msr-right">
				<div class="msr-content">
					<select data-optimized="this_page">
						<option<# if (data.optimized != 'active' && data.optimized != 'deactive'){ #> selected<# } #> value="global">
							<?php _e('Use global settings' , 'kingcomposer'); ?>
						</option>
						<option<# if (data.optimized == 'active'){ #> selected<# } #> value="active">
							<?php _e('Force Active' , 'kingcomposer'); ?>
						</option>
						<option<# if (data.optimized == 'deactive'){ #> selected<# } #> value="deactive">
							<?php _e('Force Deactive' , 'kingcomposer'); ?>
						</option>
					</select>
				</div>
			</div>
		</div>
		<div class="m-settings-row m-notice">
			<i class="fa-info-circle"></i>
			<?php _e('Notice: The optimization mode  is activated only for not logged in and cart empty (woocommerce)', 'kingcomposer'); ?>
		</div>
		<div class="m-settings-row">
			<div class="msr-left">
				<label><?php _e('Active All Page (Global Settings)', 'kingcomposer'); ?></label>
				<span>
					<?php _e('Active optimization for all pages built with KingComposer. You can active or deactive for each specific page', 'kingcomposer'); ?>.
				</span>
			</div>
			<div class="msr-right">
				<div class="msr-content">
					<div class="kc-ui-kit boolen">
						<input value="1" type="checkbox" {{(kc_global_optimized.global==1?'checked':'')}} data-optimized="global" />
						<label></label>
					</div>
				</div>
			</div>
		</div>
		
		<div class="m-settings-row">
			<div class="msr-left">
				<label><?php _e('Advanced Optimization', 'kingcomposer'); ?></label>
				<span>
					<?php _e('Enable advanced optimization such as gzip, browser caching (required changes .htaccess)', 'kingcomposer'); ?>.
				</span>
			</div>
			<div class="msr-right">
				<div class="msr-content">
					<div class="kc-ui-kit boolen">
						<input value="1" type="checkbox" {{(kc_global_optimized.advanced==1?'checked':'')}} data-optimized="advanced" />
						<label></label>
					</div>
				</div>
			</div>
		</div>
	</div>
	<# data.callback = kc.ui.callbacks.optimize_settings; #>
</script>


<script type="text/html" id="tmpl-kc-sections-template">
<div id="kc-sections"<# if( kc.sections.total > 1 ){ #> class="paged"<# } #> data-cols="{{kc.sections.cols}}">
	
	<# if( kc.sections.total > 1 ){ #> 
		<div class="kc-section-pagination">
			<# if (kc.sections.count !== undefined && kc.sections.count != 0) { #>
				<span class="items">{{kc.sections.count}} <?php _e('items total', 'kingcomposer'); ?></span>
			<# } #>
			<#	
			if (kc.sections.total <= 15) {
				#><ul><li data-action="page" data-page='prev'><?php _e('Previous', 'kingcomposer'); ?></li><#
				for( var i=1; i<=kc.sections.total; i++ ){
					#><li class="<# if( kc.sections.paged == i ){ #>active <# } #>page-{{i}}" data-action="page">{{i}}</li><#
				}
				#><li data-action="page" data-page='next'><?php _e('Next', 'kingcomposer'); ?></li></ul><#
			}else{
				#><span class="pages-select"><select><#
				for( var i=1; i<=kc.sections.total; i++ ){
					#><option<# if( kc.sections.paged == i ){ #> selected<# } #> value="{{i}}"><# if( kc.sections.paged == i ){ #><?php _e('Page', 'kingcomposer'); ?> {{i}}/{{kc.sections.total}} <# }else{ #>{{i}}<#} #></option><#
				}	
				#></select></span><#
			}
			#>
		</div>
	<# }else if (kc.sections.count !== undefined && kc.sections.count != 0){ #>
		<div class="kc-section-pagination">
			<span class="items">{{kc.sections.count}} <?php _e('items total', 'kingcomposer'); ?></span>
		</div>
	<# } #>
	<#
		
		var current_item = data.pop.data('current_item');
		
		for( var i=0; i<kc.sections.items.length; i++ ){
			var item = kc.sections.items[i];
			
			if( !item.preview )
				item.preview = kc_plugin_url+'/assets/images/get_start.jpg';
	#>
	<div class="kc-sections-item<#
		
			if( item.id == current_item ){
			#> selected<#	
			}
			
		#>" data-id="{{item.id}}" data-title="{{item.title}}">
		<div class="kc-section-sceenshot">
			<img src="{{item.preview}}" alt="" />
			<# 
			if (item.type !== undefined && item.type == 'xml') {
			#>
				<span class="kc-section-no-push"><?php
					_e('Do not support saving to prebuilt template, please chose another content types.', 'kingcomposer');
				?></span>
				<button class="button button-primary button-large kc-section-use-prebuilt" data-action="prebuilt">
					<i class="sl-plus" data-action="prebuilt"></i> <?php _e('Use this template', 'kingcomposer'); ?>
				</button>
			<#
			}else{
			#>
			<button class="kc-section-include" data-action="link">
				<i class="sl-link" data-action="link"></i> <?php _e('Include', 'kingcomposer'); ?>
			</button>
			<button class="kc-section-clone" data-action="clone">
				<i class="sl-docs" data-action="clone"></i> <?php _e('Clone', 'kingcomposer'); ?>
			</button>
			<button class="kc-section-push" data-action="push">
				<i class="fa-plus" data-action="push"></i> <?php _e('Push In', 'kingcomposer'); ?>
			</button>
			<button class="kc-section-overwrite" data-action="overwrite">
				<i class="fa-refresh" data-action="overwrite"></i> <?php _e('Overwrite', 'kingcomposer'); ?>
			</button>
			<# } #>
		</div>
		<div class="kc-section-info">
			<span>{{item.title}}</span>
			<# 
			if (item.type !== undefined && item.type == 'xml') {
			#>
				<i class="date">{{item.date}}</i>
			<#	
			}else{
			#>
			<div class="kc-section-funcs">
				<a class="edit-section" href="<?php echo admin_url('/post.php?action=edit&kc_action=enable_builder&post='); ?>{{item.id}}" target="_blank">
					<i title="<?php _e('Edit section', 'kingcomposer'); ?>" class="sl-pencil edit-section"></i>
				</a>
				<a class="delete-section" href="#delete" data-action="delete">
					<i title="<?php _e('Delete section', 'kingcomposer'); ?>" class="sl-trash delete-section" data-action="delete"></i>
				</a>
			</div>
			<i class="taxonomies">{{item.categories.join(', ')}}</i>
			<# } #>
		</div>
	</div>
	<#		
		}
	#>
	
	<# if( kc.sections.total > 1 ){ #> 
		<div class="kc-section-pagination bottom">
			<#	
			if (kc.sections.total <= 15) {
				#><ul><li data-action="page" data-page='prev'><?php _e('Previous', 'kingcomposer'); ?></li><#
				for( var i=1; i<=kc.sections.total; i++ ){
					#><li class="<# if( kc.sections.paged == i ){ #>active <# } #>page-{{i}}" data-action="page">{{i}}</li><#
				}
				#><li data-action="page" data-page='next'><?php _e('Next', 'kingcomposer'); ?></li></ul><#
			}else{
				#><span class="pages-select"><select><#
				for( var i=1; i<=kc.sections.total; i++ ){
					#><option<# if( kc.sections.paged == i ){ #> selected<# } #> value="{{i}}"><# if( kc.sections.paged == i ){ #><?php _e('Page', 'kingcomposer'); ?> {{i}}/{{kc.sections.total}} <# }else{ #>{{i}}<#} #></option><#
				}	
				#></select></span><#
			}
			#>
		</div>
	<# } #>
	
	<div class="kc-section-control">
		<# if (kc.sections.type === undefined || kc.sections.type.indexOf('prebuilt-templates-(') !== 0) { #>
		<a href="<?php echo admin_url('/edit.php?post_type='); ?>{{kc.sections.type}}" target=_blank>
			<i class="sl-menu func go-to-list" title="<?php _e('Go to sections list', 'kingcomposer'); ?>"></i>
		</a>
		<a class="kc-add-new-section" href="<?php echo admin_url('/post-new.php?kc_action=enable_builder&post_type='); ?>{{kc.sections.type}}" target=_blank>
			<i class="sl-plus func add-new" title="<?php _e('Add new section', 'kingcomposer'); ?>"></i>
			<span><?php _e('Save to new section', 'kingcomposer'); ?></span>
		</a>
		<# } #>
		<i class="sl-reload func reload" title="<?php _e('Reload', 'kingcomposer'); ?>"></i>
		<a href="#items-per-page" class="more-options">
			<i class="sl-options func" title="<?php _e('Display settings', 'kingcomposer'); ?>"></i>
			<div>
				<ul class="items-per-page">
					<li><?php _e('Per page', 'kingcomposer'); ?></li>
					<#
						var actv = 10;
						if( kc.sections.per_page !== undefined && kc.sections.per_page !== '' )
							actv = kc.sections.per_page;
							
						for( i in {10: '', 20: '', 30: '', 50: ''} ){
							#><li<# if( actv == i ){ #> class="active"<#} #> data-amount="{{i}}">{{i}}</li><#
						}
					#>
				</ul>
				<ul class="grid-columns">
					<li><?php _e('Columns', 'kingcomposer'); ?></li>
					<#
						var actv = 2;
						if( kc.sections.cols !== undefined && kc.sections.cols !== '' )
							actv = kc.sections.cols;
							
						for( i in {1: '', 2: '', 3: '', 4: ''} ){
							#><li<# if( actv == i ){ #> class="active"<#} #> data-amount="{{i}}">{{i}}</li><#
						}
					#>
				</ul>
			</div>
		</a>
		<select class="content-type">
			<option value="">- <?php _e('Content Type', 'kingcomposer'); ?> -</option>
			<#
				if (kc_allows_types.length > 0) {
					for (var i=0; i < kc_allows_types.length; i++) {
						if (kc_ignored_types.indexOf(kc_allows_types[i]) === -1) {
							#><option<#
						
							if( kc.sections.type ==  kc_allows_types[i]){
								#> selected<#
							}
						
						#> value="{{kc_allows_types[i]}}">{{kc_allows_types[i].replace(/kc\-/g, 'KC ').replace(/\-/g, ' ').replace(/\_/g, ' ')}}</option><#
						}
					}
				}
			#>
		</select>
		<select class="category">
			<#
				if (kc.sections.type !== undefined && kc.sections.type.indexOf('prebuilt-templates-(') === 0) {
					#><option value="">== <?php _e('Select Package', 'kingcomposer'); ?> ==</option><#
				}else{
					#><option value="">== <?php _e('All Taxonomies', 'kingcomposer'); ?> ==</option><#
				}
			#>
			<#
				if( kc.sections.terms.length > 0 ){
					for( var i=0; i<kc.sections.terms.length; i++ ){
						#><option <# 
							if(kc.sections.terms[i]['id']+'|'+kc.sections.terms[i]['taxonomy'] == kc.sections.term){
								#>selected <#
							}
						#>value="{{kc.sections.terms[i]['id']}}|{{kc.sections.terms[i]['taxonomy']}}">{{kc.sections.terms[i]['name']}}</option><#
					}
				}
			#>
		</select>
		<input type="search" class="keyword" value="{{kc.sections.s}}" placeholder="<?php _e('Search by Name', 'kingcomposer'); ?>" />
		<i class="sl-magnifier"></i>
	</div>
	<div class="kc-section-share">
		<div class="kc-ss-body">
			<div class="kc-ss-left">
				<div class="kc-ss-thumbnail"></div>
				<div class="kc-ss-section">
					<label><?php _e('Section', 'kingcomposer'); ?>:</label>
					<span></span>
				</div>
				<div class="kc-ss-source">
					<label><?php _e('Source', 'kingcomposer'); ?>:</label>
					<span>{{kc_site_url}}</span>
				</div>
				<div class="kc-ss-name">
					<label><?php _e('Your Name', 'kingcomposer'); ?>:</label>
					<span><input type="text" /></span>
				</div>
				<div class="kc-ss-email">
					<label><?php _e('Your Email', 'kingcomposer'); ?>:</label>
					<span><input type="email" /></span>
				</div>
				<div class="kc-ss-btns">
					<button class="button button-primary kc-ss-share-submit">
						<?php _e('Submit to KC Hub', 'kingcomposer'); ?>
					</button>
					<button class="button kc-ss-share-cancel">
						<?php _e('Cancel', 'kingcomposer'); ?>
					</button>
				</div>
			</div>
			<div class="kc-ss-right">
				<h1><?php _e('Share your design to KC Hub', 'kingcomposer'); ?></h1>
				<h2><?php _e('What is KC Hub?', 'kingcomposer'); ?></h2>
				<p>
					<?php _e('KC Hub is a free resource, where you can install any preset design template with one click. You can use anyone\'s design sharing and you can also share your design so that anyone can use them.', 'kingcomposer'); ?>
				</p>
				<br />
				<h2><?php _e('How to be accepted?', 'kingcomposer'); ?></h2>
				<p>
					<?php _e('After submitting, we will review before publishing. We only accept which design has been built on our CSS system and does not require external CSS. Then KC Users can install them everywhere.', 'kingcomposer'); ?>
				</p>
			</div>
		</div>
	</div>
</div>
<# data.callback = kc.ui.sections.render_callback; #>
</script>

<script type="text/html" id="tmpl-kc-components-template">
<#

var maps = jQuery.extend({}, kc.maps, true), /* Clone maps */
	categories = [],
	more = [],
	category = catz = li = name = desc = icon = n = '',
	i = 0;

	var parent = {name: ''},
		accept_child = [], except_child = [],
		accept_parent = except_parent = '';
	
	if (kc.storage[data.model] !== undefined && maps[kc.storage[data.model].name] !== undefined) {
		parent = maps[kc.storage[data.model].name];
		parent.name = kc.storage[data.model].name;
	}
	
	if (parent.accept_child !== undefined && parent.accept_child !== '')
		accept_child = parent.accept_child.replace(/\ /g, '').split(',');
	
	if (parent.except_child !== undefined && parent.except_child !== '')
		except_child = parent.except_child.replace(/\ /g, '').split(',');

	for (n in maps) {
		if( undefined === maps[n].priority )
			maps[n].priority = 1000;
	
		if (
			(accept_child.length > 0 && accept_child.indexOf(n) === -1) ||
			(except_child.length > 0 && except_child.indexOf(n) > -1)
		) {
			delete maps[n];
		}
		
		if (maps[n] !== undefined && maps[n].accept_parent !== undefined && maps[n].accept_parent !== '') {
			accept_parent = maps[n].accept_parent.replace(/\ /g, '').split(',');
			if (accept_parent.indexOf(parent.name) === -1) {
				delete maps[n];
			}
		}
		
		if (maps[n] !== undefined && maps[n].except_parent !== undefined && maps[n].except_parent !== '') {
			except_parent = maps[n].except_parent.replace(/\ /g, '').split(',');
			if (except_parent.indexOf(parent.name) > -1) {
				delete maps[n];
			}
		}
		
	}

#>
	<div class="kc-components">
		<ul class="kc-components-categories">
			<li data-category="all" class="all active"><?php _e('All', 'kingcomposer'); ?></li>
			<#
				
				for (n in maps) {
					category = (maps[n].category !== undefined) ? maps[n].category : '';
					category = category.toString();
					if (categories.indexOf(category) === -1 && category !== '') {
						categories.push(category);
					}
				}
				
				categories.sort();
				var limitcatz = 6;
				if (jQuery(window).width() < 1350)
					limitcatz = 2;
					
				for (i=0 ; i<categories.length; i++) {
					catz = kc.tools.esc_slug (categories[i]);
					li = '<li data-category="'+catz+'" class="'+catz+'">'+categories[i]+'</li>';
					
					if (i < limitcatz){ #>{{{li}}}<# }
					else more.push(li);
				}
				
				if (more.length > 0) {
					#><li class="more"><?php _e('More', 'kingcomposer'); ?> <i class="sl-options" aria-hidden="true"></i><ul><#
					
					for (i=0; i<more.length; i++) {
						#>{{{more[i]}}}</li><#
					}
					#></ul></li><#
				}
				
			#>
			
			<# if (accept_child.length === 0 || accept_child.indexOf('widgets') > -1) { #>
			<li data-category="kc-wp-widgets" class="kc-wp-widgets mcl-wp-widgets">
				<i class="fa-wordpress" aria-hidden="true"></i> <?php _e('Widgets', 'kingcomposer'); ?>
			</li>
			<# } #>
			<# if (accept_child.length === 0) { #>
			<li data-category="kc-clipboard" class="kc-clipboard mcl-clipboard">
				<i class="fa-stack-overflow" aria-hidden="true"></i> <?php _e('Clipboard', 'kingcomposer'); ?>
			</li>
			<# } #>
			<li class="mcl-extensions">
				<a href="admin.php?page=kc-extensions" target=_blank>
					<i class="fa-shopping-bag" aria-hidden="true"></i> <?php _e('Extensions', 'kingcomposer'); ?>
				</a>
			</li>
		</ul>
		<ul class="kc-components-list-main kc-components-list">
			<#

				var n, e,
					keysSorted = Object.keys(maps).sort(function(a,b) {
						return maps[a].priority - maps[b].priority;
					});
				
				for (e in keysSorted) {
					n = keysSorted[e];
					if (maps[n].system_only !== true) {
						category = (maps[n].category !== undefined) ? maps[n].category : uncategoried;
						name = (maps[n].name !== undefined) ? maps[n].name : '';
						desc = (maps[n].description !== undefined) ? maps[n].description : '';
						icon = (maps[n].icon !== undefined) ? maps[n].icon : '';
						#>
							<li title="{{desc}}" data-category="{{kc.tools.esc_slug(category)}}" data-name="{{n}}" class="kc-element-item mcpn-{{kc.tools.esc_slug(category)}}">
								<div>
									<i class="cpicon {{icon}}" aria-hidden="true"></i>
									<span class="cpdes">
										<strong>{{name}}</strong>
									</span>
									<i class="sl-star preset-open" aria-hidden="true" title="<?php _e('Show presets of this element', 'kingcomposer'); ?>"></i>
								</div>
							</li>
						<#
					}
				}
				
				delete maps, categories, more, category, catz, li, 
					   name, desc, icon, n, i;
					   
				if (parent !== undefined)
					delete parent, accept_child, except_child, accept_parent, except_parent;
				
			#>
		</ul>
	</div>
</script>

<script type="text/html" id="tmpl-kc-presets-template"><#

var items = kc.backbone.stack.get( 'kc_presets', data.name ),
	pname = catslug = '',
	cates = [], i;

if( typeof items == 'object' ){
	for( i in items ){
		if( items[i][0] !== '' && cates.indexOf( items[i][0] ) === -1 )
			cates.push( items[i][0] );
	}
}

#><li class="kc-presets-list<# if(data.class !== undefined){#> {{data.class}}<#} #>">
	<h2 class="kc-pretit">
		{{data.name.replace('kc_','').replace(/\-/g,' ').replace(/\_/g,' ')}}
		<?php _e('Presets', 'kingcomposer'); ?>
	</h2>
	<a href="#add_preset" class="add kbtn"><i class="fa-plus"></i> <?php _e('Create new preset', 'kingcomposer'); ?></a>
	<a href="#close" class="preset-close" title="<?php _e('Close Presets', 'kingcomposer'); ?>"></a>
	<div class="kc-preset-create" style="display: none" data-mesg="<?php _e('Create new element preset based on the current settings', 'kingcomposer'); ?>">
		<input type="text" class="kc-preset-name-input" placeholder="<?php _e('Preset name', 'kingcomposer'); ?>" />
		<input type="text" class="kc-preset-cats-input" placeholder="<?php _e('Preset category', 'kingcomposer'); ?>" />
		<button class="kc-preset-create-button"><i class="fa-check"></i>
		<button class="kc-preset-create-close"><i class="fa-times"></i></button>
		<# if( cates.length > 0 ){ #>
			<ul class="kc-pre-cats">
				<#
					for( i in cates ){
						
						#><li>{{decodeURIComponent(escape(cates[i]))}}</li><#
					}
				#>
			</ul>
		<# } #>
		<h2 class="success-mesg"><?php _e('The preset has been created successfully', 'kingcomposer'); ?> </h2>
	</div>
	<# if( cates.length > 0 ){ #>
	<ul class="kc-preset-categories">
		<li><a href="#all" class="active">All</a></li>
		<#
			for( i in cates ){
				#><li>/</li><li><a href="#">{{decodeURIComponent(escape(cates[i]))}}</a></li><#
			}
		#>
	</ul>
	<# } #>
	<div class="kc-preset-wrp">
		<div class="kc-preset-outer" data-name="{{data.name}}">
			<# if( typeof items == 'object' && !_.isEmpty( items ) ){ #>
				<ul>
					<# for( i in items ){
						pname = decodeURIComponent(escape( i ));
						catslug = kc.tools.esc_slug( decodeURIComponent(escape( items[i][0] )) );
						#>
					<li data-name="{{data.name}}" class="kc-preset-item kc-preset-cat-{{catslug}}" data-cate="preset-cat-{{catslug}}" title="{{pname}}">
						<p>{{pname}}</p>
						<small>{{items[i][1]}}</small>
						<i class="sl-close" data-pid="{{i}}" data-pname="{{data.name}}" title="<?php _e('Delete preset', 'kingcomposer'); ?>"></i>
					</li>
					<# } #>
				</ul>
			<# }else{ #>
				<h2 class="kc-prempty">
					<?php _e('You have not created any preset for this element', 'kingcomposer'); ?> 
					<br />
					<a href="http://docs.kingcomposer.com/presets" target=_blank>
						<?php _e('Check the preset document', 'kingcomposer'); ?> 
					</a>
				</h2>
			<# } #>
		</div>
	</div>
</li>
<#
	data.callback = kc.ui.callbacks.presets;
#>
</script>

<script type="text/html" id="tmpl-kc-row-template">
<#
 
var fEr3 = '', Hrdw = '', sEtd4 = '';

if( data.row_id !== undefined && data.row_id != '__empty__' )
	sEtd4 = '#'+data.row_id;

if( data.disabled !== undefined && data.disabled == 'on' ){
	fEr3 = ' collapse',
	Hrdw = ' disabled';
}

if( data.__section_link !== undefined )
	fEr3 += ' kc-section-link';

if (data._collapse !== undefined && data._collapse == '1')
	fEr3 += ' collapse';

#>
	<div class="kc-row m-r-sortdable{{fEr3}}">
		<ul class="kc-row-control row-container-control">
		
		<# if( data.__section_link === undefined ){ #>
		
			<li class="right close mtips">
				<i class="sl-close"></i>
				<span class="mt-mes"><?php _e('Delete this section', 'kingcomposer'); ?></span>
			</li>
			<li class="double mtips">
				<i class="sl-docs"></i>
				<span class="mt-mes"><?php _e('Double this section', 'kingcomposer'); ?></span>
			</li>
			<li class="right settings edit mtips">
				<i class="sl-note"></i>
				<span class="mt-mes"><?php _e('Section settings', 'kingcomposer'); ?></span>
			</li>
			<li class="right move mtips">
				<i class="sl-cursor-move"></i>
				<span class="order-row">
					<input type="number" placeholder="order" /> <button><i class="fa-exchange-alt"></i></button>
				</span>
				<span class="mt-mes"><?php _e('Drag and drop to arrange this section', 'kingcomposer'); ?></span>
			</li>
			
		<# }else{ #>
		
			<li class="right close mtips">
				<i class="sl-close"></i>
				<span class="mt-mes"><?php _e('Delete this section', 'kingcomposer'); ?></span>
			</li>
			<li class="right move mtips">
				<i class="sl-cursor-move"></i>
				<span class="order-row">
					<input type="number" placeholder="order" /> <button><i class="fa-exchange-alt"></i></button>
				</span>
				<span class="mt-mes"><?php _e('Drag and drop to arrange this section', 'kingcomposer'); ?></span>
			</li>
			
		<# } #>
		
		</ul>
		<div class="kc-row-admin-view">{{sEtd4}}</div>
		<ul class="kc-row-control row-container-control pos-left">
			
			<# if( data.__section_link === undefined ){ #>
			
			<li class="right collapse mtips">
				<i class="sl-arrow-down"></i>
				<span class="mt-mes"><?php _e('Expand / Collapse this row', 'kingcomposer'); ?></span>
			</li>
			<li class="columns mtips">
				<i class="sl-list"></i>
				<span class="mt-mes"><?php _e('Set number of columns for this row', 'kingcomposer'); ?></span>
			</li>
			<li class="addToSections mtips">
				<i class="sl-share-alt"></i>
				<span class="mt-mes"><?php _e('Save this row to section', 'kingcomposer'); ?></span>
			</li>
			<li class="copy mtips">
				<i class="sl-doc"></i>
				<span class="mt-mes"><?php _e('Copy this row', 'kingcomposer'); ?></span>
			</li>
			<li class="rowStatus{{Hrdw}} mtips">
				<i></i>
				<span class="mt-mes"><?php _e('Publish / Unpublish this section', 'kingcomposer'); ?></span>
			</li>
			<# }else if( data.__section_title !== undefined ){ #>
				<li class="right collapse mtips">
					<i class="sl-arrow-down"></i>
					<span class="mt-mes"><?php _e('Expand / Collapse this section', 'kingcomposer'); ?></span>
				</li>
				<li class="bpdl">
					<strong class="red"><i class="sl-link"></i> {{data.__section_title}}</strong>
					<?php if(!defined('KC_SLUG')||md5(KC_SLUG)!='0f882acc192505fa98c9a8e1167539a1')exit; ?>
				</li>
				<li class="rowStatus{{Hrdw}} mtips">
					<i></i>
					<span class="mt-mes"><?php _e('Publish / Unpublish this section', 'kingcomposer'); ?></span>
				</li>
			<# } #>
		</ul>	
		<div class="kc-row-wrap"><# 
			if( data.__section_link !== undefined ){
			#>
			<div class="kc-row-section-body">
				<div class="kc-row-section-preview">
					<img src="<?php echo admin_url("/admin-ajax.php?action=kc_get_thumbn&size=full&type=post_featured&id="); ?>{{data.__section_link}}" />
					<a href="<?php echo admin_url('/post.php?action=edit&post='); ?>{{data.__section_link}}" class="kcrbtn edit" target=_blank>
						<i class="sl-note"></i> <?php _e('Go to edit this section', 'kingcomposer'); ?>
					</a>
					<button class="kcrbtn select select-another-section" data-current="{{data.__section_link}}">
						<i class="sl-list"></i> <?php _e('Select another section', 'kingcomposer'); ?>
					</button>
				</div>
			</div>
			<# } #>
		</div>
		
	</div>

</script>
<script type="text/html" id="tmpl-kc-row-inner-template">
	<div class="kc-row-inner">
		<ul class="kc-row-control kc-row-inner-control">
			<li class="right delete mtips">
				<i class="sl-close"></i>
				<span class="mt-mes"><?php _e('Delete this row', 'kingcomposer'); ?></span>
			</li>
			<li class="right settings edit mtips">
				<i class="sl-note"></i>
				<span class="mt-mes"><?php _e('Open row settings', 'kingcomposer'); ?></span>
			</li>
			<li class="right double mtips">
				<i class="sl-docs"></i>
				<span class="mt-mes"><?php _e('Double this row', 'kingcomposer'); ?></span>
			</li>
			<li class="right move mtips">
				<i class="sl-cursor-move"></i>
				<span class="mt-mes"><?php _e('Drag and drop to arrange this row', 'kingcomposer'); ?></span>
			</li>
		</ul>
		<ul class="kc-row-control pos-left kc-row-inner-control">
			<li class="right collapse mtips">
				<i class="sl-arrow-down"></i>
				<span class="mt-mes"><?php _e('Expand / Collapse this row', 'kingcomposer'); ?></span>
			</li>
			<li class="right columns mtips">
				<i class="sl-list"></i>
				<span class="mt-mes"><?php _e('Set number of columns for this row', 'kingcomposer'); ?></span>
			</li>
			<li class="right copyRowInner mtips">
				<i class="sl-doc"></i>
				<span class="mt-mes"><?php _e('Copy this row', 'kingcomposer'); ?></span>
			</li>
		</ul>	
		<div class="kc-row-wrap"></div>
	</div>	
</script>
<script type="text/html" id="tmpl-kc-column-template">
	<div class="kc-column" style="width: {{data.width}}">
		<ul class="kc-column-control column-container-control">
			<li class="kc-column-settings more">
				<i class="sl-options edit"></i>
				<div class="mme-more-actions">
					<ul class="pos-top">
						<li class="double" title="<?php _e('Double column', 'kingcomposer'); ?>">
							<i class="sl-docs"></i>
						</li>
						<li class="insert" title="<?php _e('Insert new column', 'kingcomposer'); ?>">
							<i class="sl-doc"></i> 
						</li>
						<li class="add" title="<?php _e('Add element', 'kingcomposer'); ?>">
							<i class="sl-plus"></i>
						</li>
						<li class="delete" title="<?php _e('Delete column', 'kingcomposer'); ?>">
							<i class="fa-trash"></i>
						</li>
					</ul>
				</div>
				<span class="narrow edit"></span>
			</li>
		</ul>
		<div class="kc-column-wrap">
			<div class="kc-element drag-helper">
				<a href="javascript:void(0)" class="kc-add-elements-inner">
					<i class="sl-plus"></i> <?php _e('Add Element', 'kingcomposer'); ?>
				</a>
			</div>
		</div>
		<ul class="kc-column-control">
			<li class="add mtips">
				<i class="sl-plus"></i>
				<span class="mt-mes"><?php _e('Add elements to bottom of this column', 'kingcomposer'); ?></span>
			</li>
		</ul>
		<div class="column-resize cr-left"></div>
		<div class="column-resize cr-right"></div>
		<div class="kc-cols-info">{{Math.round(parseFloat(data.width))}}%</div>
	</div>
</script>
<script type="text/html" id="tmpl-kc-column-inner-template">
	<div class="kc-column-inner" style="width: {{data.width}}">
		<ul class="kc-column-control column-inner-control">
			<li class="kc-column-settings more">
				<i class="sl-options edit"></i>
				<div class="mme-more-actions">
					<ul class="pos-top">
						<li class="double" title="<?php _e('Double column', 'kingcomposer'); ?>">
							<i class="sl-docs"></i>
						</li>
						<li class="insert" title="<?php _e('Insert new column', 'kingcomposer'); ?>">
							<i class="sl-doc"></i> 
						</li>
						<li class="add" title="<?php _e('Add element', 'kingcomposer'); ?>">
							<i class="sl-plus"></i>
						</li>
						<li class="delete" title="<?php _e('Delete column', 'kingcomposer'); ?>">
							<i class="fa-trash"></i>
						</li>
					</ul>
				</div>
				<span class="narrow edit"></span>
			</li>
		</ul>
		<div class="kc-column-wrap">
			<div class="kc-element drag-helper">
				<a href="javascript:void(0)" class="kc-add-elements-inner">
					<i class="sl-plus"></i> <?php _e('Add Elements', 'kingcomposer'); ?>
				</a>
			</div>
		</div>
		<ul class="kc-column-control">
			<li class="add mtips">
				<i class="sl-plus"></i>
				<span class="mt-mes"><?php _e('Add elements to bottom of this column', 'kingcomposer'); ?></span>
			</li>
		</ul>
		<div class="column-resize cr-left"></div>
		<div class="column-resize cr-right"></div>
		<div class="kc-cols-info">{{Math.round(parseFloat(data.width))}}%</div>
	</div>
</script>
<script type="text/html" id="tmpl-kc-views-sections-template">
	<#
		try{
			var sct = kc.maps[data.name].views.sections;
			if( kc.maps[data.name].views.display == 'vertical' )
				var vertical = ' kc-views-vertical';
		}catch(e){
			var sct = 'kc_tab', vertical = 'kc-views-horizontal';
		}	
	#>
	<div class="kc-views-sections kc-views-{{data.name}}{{vertical}}">
		<ul class="kc-views-sections-control kc-controls">
			<li class="right move mtips">
				<i class="sl-cursor-move"></i> {{kc.maps[data.name].name}}
				<span class="mt-mes"><?php _e('Drag and drop to arrange this element', 'kingcomposer'); ?></span>
			</li>
			<li class="more" title="<?php _e('More Actions', 'kingcomposer'); ?>">
				<i class="fa fa-caret-right"></i>
				<div class="mme-more-actions">
					<ul>
						<li class="right edit" title="<?php _e('Edit', 'kingcomposer'); ?>">
							<i class="sl-note"></i>
						</li>
						<li class="double" title="<?php _e('Double', 'kingcomposer'); ?>">
							<i class="sl-docs"></i>
						</li>
						<li class="copy" title="<?php _e('Copy', 'kingcomposer'); ?>">
							<i class="sl-doc"></i>
						</li>
						<li class="cut" title="<?php _e('Cut', 'kingcomposer'); ?>">
							<i class="fa fa-cut"></i> 
						</li>
						<li class="delete" title="<?php _e('Delete', 'kingcomposer'); ?>">
							<i class="fa-trash"></i>
						</li>
					</ul>
				</div>
			</li>
		</ul>
		<div class="kc-views-sections-wrap">
			<div class="kc-views-sections-label">
				<div class="add-section">
					<i class="sl-plus"></i> <span> <?php _e('Add', 'kingcomposer'); ?> {{kc.maps[sct].name}}</span>
				</div>
			</div>	
		</div>
	</div>
</script>
<script type="text/html" id="tmpl-kc-views-section-template">
	<#
		var icon = '';
		if( data.args.icon != undefined )
			icon = '<i class="'+data.args.icon+'"></i> ';
	#>
	<div class="kc-views-section<# if(data.first==true){ #> kc-section-active<# } #>">
		<h3 class="kc-vertical-label sl-arrow-down">{{{icon}}}{{data.args.title}}</h3>
		<ul class="kc-controls-2 kc-vs-control">
			<li class="right add mtips">
				<i class="sl-plus"></i>
				<span class="mt-mes"><?php _e('Add Elements', 'kingcomposer'); ?></span>
			</li>
			<li class="right double mtips">
				<i class="sl-docs"></i>
				<span class="mt-mes"><?php _e('Double this section', 'kingcomposer'); ?></span>
			</li>
			<li class="right settings mtips">
				<i class="sl-note"></i>
				<span class="mt-mes"><?php _e('Open settings', 'kingcomposer'); ?></span>
			</li>
			<li class="right delete mtips" title="<?php _e('Remove', 'kingcomposer'); ?>">
				<i class="sl-close"></i>
				<span class="mt-mes"><?php _e('Remove this section', 'kingcomposer'); ?></span>
			</li>
		</ul>
		<div class="kc-views-section-wrap kc-column-wrap">
			<div class="kc-element drag-helper">
				<a href="javascript:void(0)" class="kc-add-elements-inner">
					<i class="sl-plus"></i> <?php _e('Add Element', 'kingcomposer'); ?>
				</a>
			</div>
		</div>
	</div>
</script>
<script type="text/html" id="tmpl-kc-element-template">
	<#
		var el_class = [];
		el_class.push (data.params.name);
		
		if(data.map.preview_editable == true)
			el_class.push ('viewEditable');
		
		if(data.map.nested === true)
			el_class.push ('nestedView');
		
	#>
	<div class="kc-element {{el_class.join(' ')}}">
		<div class="kc-element-icon"><span class="cpicon {{data.map.icon}}"></span></div>
		<span class="kc-element-label">{{data.map.name}}</span>
		<div class="kc-element-control" title="<?php _e('Drag to move this element', 'kingcomposer'); ?>">
			<ul class="kc-controls pos-bottom">
				<li class="edit mtips" title="">
					<i class="sl-note"></i>
					<span class="mt-mes"><?php _e('Edit this element', 'kingcomposer'); ?></span>
				</li>
				<li class="double mtips" title="">
					<i class="sl-docs"></i>
					<span class="mt-mes"><?php _e('Double this element', 'kingcomposer'); ?></span>
				</li>
				<# if(data.map.nested === true){ #>
				<li class="add mtips" title="">
					<i class="sl-plus"></i>
					<span class="mt-mes"><?php _e('Add elements', 'kingcomposer'); ?></span>
				</li>
				<# } #>
				<li class="more mtips" title="">
					<i class="sl-options"></i>
					<span class="mt-mes"><?php _e('Right click for more options', 'kingcomposer'); ?></span>
				</li>
				<li class="delete" title="<?php _e('Delete this element', 'kingcomposer'); ?>">
					<i class="fa-trash"></i>
				</li>
			</ul>
		</div>
		<# if (data.map.nested === true){ #>
		<div class="kc-column-wrap">
			<div class="kc-element drag-helper">
				<a href="javascript:void(0)" class="kc-add-elements-inner">
					<i class="sl-plus"></i> <?php _e('Add Element', 'kingcomposer'); ?>
				</a>
			</div>
		</div>
		<# } #>
	</div>
</script>
<script type="text/html" id="tmpl-kc-undefined-template">
	 <div class="kc-undefined kc-element {{data.params.name}}">
		<div class="admin-view content">{{data.params.args.content}}</div>
		<div class="kc-element-control">
			<ul class="kc-controls">
				<li class="move" title="<?php _e('Move', 'kingcomposer'); ?>">
					<i class="sl-cursor-move"></i>
				</li>
				<li class="double" title="<?php _e('Double', 'kingcomposer'); ?>">
					<i class="sl-docs"></i>
				</li>
				<li class="edit" title="<?php _e('Edit', 'kingcomposer'); ?>">
					<i class="sl-note"></i>
				</li>
				<li class="delete" title="<?php _e('Delete', 'kingcomposer'); ?>">
					<i class="sl-close"></i>
				</li>
			</ul>
		</div>		
	</div>
</script>
<script type="text/html" id="tmpl-kc-popup-template">
	<div class="kc-params-popup wp-pointer-top {{data.class}}<# if(data.bottom!=0){ #> posbottom<# } #>" style="<# if(data.bottom!=0){ #>bottom:{{data.bottom}}px;top:auto;<# }else{ #>top:{{data.top}}px;<# } #>left:{{data.left}}px;<#
			if( data.width != undefined ){ #>width:{{data.width}}px<# } 
		#>">
		<div class="m-p-wrap wp-pointer-content">
			<h3 class="m-p-header">
				<i data-prevent-drag="true" class="m-p-toggle dashicons dashicons-arrow-down-alt2" title="<?php _e('Collapse popup', 'kingcomposer'); ?>"></i>
				<span data-st="label">{{data.title}}</span>
				<# if( data.help != '' ){ #>
				<a href="{{data.help}}" target="_blank" title="<?php _e('Help', 'kingcomposer'); ?>" class="sl-help sl-func">
					&nbsp;
				</a>
				<# } #>
				<i data-prevent-drag="true" title="<?php _e('Cancel & close popup', 'kingcomposer'); ?>" class="sl-close sl-func"></i>
				<i data-prevent-drag="true" title="<?php _e('Save changes (ctrl+s)', 'kingcomposer'); ?>" class="sl-check sl-func"></i></h3>
			<div class="m-p-body">
				{{{data.content}}}
			</div>
			<# if( data.footer === true ){ #>
			<div class="m-p-footer">
				<ul class="m-p-controls">
					<li>
						<button class="button save button-large">
							<i class="sl-check"></i> {{data.save_text}}
						</button>
					</li>
					<li>
						<button class="button cancel button-large">
							<i class="sl-close"></i> {{data.cancel_text}}
						</button>
					</li>
					<li class="pop-tips">{{{data.footer_ext}}}</li>
				</ul>
			</div>
			<# } #>
			<# if( data.success_mesage !== undefined ){ #>
			<div class="m-p-overlay">{{{data.success_mesage}}}</div>
			<# } #>
		</div>
		<div class="wp-pointer-arrow"<#
				if( data.pos != undefined ){
					var css = '';
					if( data.pos == 'center' ){
						css += 'left:50%;margin-left:-13px;';
					}else if( data.pos == 'right' ){
						css += 'left:auto;right:50px;';
					}
					if( css != '' ){
						#> style="{{css}}"<#
					}
				}
			#>>
			<div class="wp-pointer-arrow-inner"></div>
		</div>
	</div>
</script>
<script type="text/html" id="tmpl-kc-field-template">
	<#
		
		/*
		*	Some of param name is not allowed to use, because it would conflict with the system
		*	So if we will not render this field & display the warning instead
		*/
		
		var iglist = ['css', 'css_data', 'content', '_name', '_id', '_full', '_content', '_base', '_collapse'];
		if (data.name == 'textarea_html')
		{
			if (data.base != 'content')
				data.content = '<p class="kc-notice"><i class="fa-warning"></i> <?php _e('The name of this field must be set to "content"', 'kingcomposer'); ?></p>';
		}else if (iglist.indexOf(data.base) > -1 && ['text', 'textarea'].indexOf(data.name) === -1) 
		{
			data.content = '<p class="kc-notice"><i class="fa-warning"></i> <?php _e('The name of this field is not allowed to use as', 'kingcomposer'); ?> "'+data.base+'"</p>';
		}
	
		var el_class = ['kc-param-row'], 
			base_class = '';
		
		el_class.push ('field-'+data.name.replace(/[^0-9a-zA-Z\-\_]/g,''));
		
		if (data.base !== undefined) {
			base_class = data.base;
			if (data.base.indexOf('[') > -1)
				base_class = data.base.substr(0, data.base.indexOf('['));
				
			if (data.base.indexOf('][') > -1)
				base_class += '-'+data.base.substr(data.base.indexOf('][')+2).replace(/\]/g, '');
		}
		
		el_class.push( 'field-base-'+base_class );
		
		if( data.relation != undefined )
			el_class.push( 'relation-hidden' );
			
	#>
	<div class="{{el_class.join(' ')}}">
		<# if( data.label != undefined && data.label != '' ){ #>
		<div class="m-p-r-label">
			<label>{{{data.label}}}:</label>
		</div>
		<div class="m-p-r-content">
		<# }else{ #>
		<div class="m-p-r-content full-width">
		<# } #>	
			{{{data.content}}}
			<# if( data.des != undefined && data.des != '' ){ #>
				<div class="m-p-r-des">{{{data.des}}}</div>
			<# } #>
		</div>
	</div>
</script>

<script type="text/html" id="tmpl-kc-row-columns-template">
	<div class="kc-row-columns">
		&nbsp; <input type="checkbox" data-name="columnDoubleContent" id="m-r-c-double-content" {{kc.cfg.columnDoubleContent}} /> 
		<?php _e('Double content', 'kingcomposer'); ?> 
		<a href="javascript:alert('<?php _e('Copy content in the last column to the newly-created column. This option is available when you choose to set the column amount greater than the current column amount', 'kingcomposer'); ?>.\n\n<?php _e('For example: Currently there is 1 column and you are going to set 2 columns', 'kingcomposer'); ?>')"> <i class="sl-question"></i> </a> &nbsp; &nbsp; 
		<input type="checkbox" data-name="columnKeepContent" id="m-r-c-keep-content" {{kc.cfg.columnKeepContent}} /> 
		<?php _e('Keep content', 'kingcomposer'); ?> <a href="javascript:alert('<?php _e('Keep content of the removed column and transfer it to the last existing column', 'kingcomposer'); ?>.\n\n<?php _e('This option is available when you choose to set the column amount smaller than the current column amount', 'kingcomposer'); ?>.\n\n<?php _e('For example: Currently there are 2 columns and you are going to set 1 column', 'kingcomposer'); ?>.')"> <i class="sl-question"></i> </a>
		<br /><br />
		<p class="kc-col-btns">
			<button class="button button-large<# if( data.current == 1 ){ #> active<# } #>" data-column="1">
				1 <?php _e('Column', 'kingcomposer'); ?> &nbsp;
			</button>
			<button class="button button-large<# if( data.current == 2 ){ #> active<# } #>" data-column="2">
				2 <?php _e('Columns', 'kingcomposer'); ?> &nbsp;
			</button>
			<button class="button button-large<# if( data.current == 3 ){ #> active<# } #>" data-column="3">
				3 <?php _e('Columns', 'kingcomposer'); ?> &nbsp;
			</button>
		</p>
		<p class="kc-col-btns">
			<button class="button button-large<# if( data.current == 4 ){ #> active<# } #>" data-column="4">
				4 <?php _e('Columns', 'kingcomposer'); ?> &nbsp;
			</button>
			<button class="button button-large<# if( data.current == 5 ){ #> active<# } #>" data-column="5">
				5 <?php _e('Columns', 'kingcomposer'); ?> &nbsp;
			</button>
			<button class="button button-large<# if( data.current == 6 ){ #> active<# } #>" data-column="6">
				6 <?php _e('Columns', 'kingcomposer'); ?> &nbsp;
			</button>
		</p>
		<p class="kc-col-custom">
			<input type="text" placeholder="Example: 15% + 35% + 6/12" />
			<button data-column="custom" class="button button-large">Apply</button>
		</p>
	</div>
</script>

<script type="text/html" id="tmpl-kc-box-design-template">
<#
	if( typeof data == 'object' && data.length > 0 ){
		
		data.forEach( function( item ){
			
	        if( typeof item.attributes != 'object' )
	        	item.attributes = {};
	        if( item.tag == 'a' && item.attributes.href == undefined )
	        	item.attributes.href = '';
	        
	        var classes = '';	
	        if( item.tag == 'icon' || item.tag == 'text' || item.tag == 'image' ){
	        	classes += ' kc-box-elm';
			}else if( item.tag == 'clumn' ){
				var ncl = 'one-one';
				if( item.attributes.class !== undefined ){
					['one-one','one-second','one-third','two-third'].forEach(function(cl){
						if( item.attributes.class.indexOf( cl ) > -1 )
							ncl = cl;
					});
				}
				classes += ' kc-column-'+ncl;
			}
			
			
	        if( item.attributes.cols != undefined )
	        	classes += ' kc-column-'+item.attributes.cols;
	        	
#>
			<div class="kc-box kc-box-{{item.tag}}{{classes}}" data-tag="{{item.tag}}" data-attributes='{{JSON.stringify(item.attributes)}}'>
		        <ul class="mb-header">
			        <li class="mb-toggle" data-action="toggle"><i class="mb-toggle fa-caret-down"></i></li>
			        <li class="mb-tag">{{item.tag}}</li>
			        <# if( item.attributes.id != undefined && item.attributes.id != '' ){ #>
			        	<li class="mb-id">Id: <span>{{item.attributes.id}}</span></li>
			        <# } if( item.attributes.class != undefined && item.attributes.class != '' ){ #>
			        	<li class="mb-class">
			        		Class: <span title="{{item.attributes.class}}">{{item.attributes.class.substr(0,30)}}..</span>
			        	</li>
			        <# } if( item.attributes.href != '' && item.attributes.href != undefined ){ #>
			        	<li class="mb-href">
			        		Href: <span title="{{item.attributes.href}}">{{item.attributes.href.substr(0,30)}}..</span>
			        	</li>
			        <# } #>
			        <li class="mb-funcs">
			        	<ul>
					        <li title="<?php _e('Remove', 'kingcomposer'); ?>" class="mb-remove mb-func" data-action="remove">
					        	<i class="sl-close"></i>
					        </li>
					        <# if( item.tag == 'text' ){ #>
					        <li  title="<?php _e('Edit with Editor', 'kingcomposer'); ?>"class="mb-edit mb-func" data-action="editor">
					        	<i class="sl-pencil"></i>
					        </li>
					        <# }else{ #>
					        <li  title="<?php _e('Settings', 'kingcomposer'); ?>"class="mb-edit mb-func" data-action="settings">
					        	<i class="sl-settings"></i>
					        </li>
					        <# } #>
					        <li title="<?php _e('Double', 'kingcomposer'); ?>" class="mb-double mb-func" data-action="double">
					        	<i class="sl-docs"></i>
					        </li>
					        <# if( item.tag != 'div' ){ #>
					        <li title="<?php _e('Add Node', 'kingcomposer'); ?>" class="mb-add mb-func" data-action="add" data-pos="inner"><i class="sl-plus"></i></li>
					        <# }else{ #>
					        <li title="<?php _e('Columns', 'kingcomposer'); ?>" class="mb-columns mb-func" data-action="columns"><i class="sl-list"></i></li>    
							<# } #>
						</ul>
				    </li>
		        </ul>
		        <div class="kc-box-body"><# 
			        
			        var empcol = false;
			        
		        	if( item.tag == 'div' ){
			        	if( item.children == undefined )
				        		empcol = true;
			        	else if( item.children.length == 0 )
				        		empcol = true;
				        else if( item.children[0].tag != 'column' )
				        	empcol = true;
			        }
			        
			        if( empcol == true ){
				        
				       #>{{{kc.template( 'box-design', [{ tag: 'column', attributes: { cols:'one-one' }, children: item.children }]
				       	)}}}<# 
				        
			        }else{
			        
			        	
				        if( empcol == true ){
					        #><div data-cols="one-one" class="kc-box-column one-one"><#
				        }	


				        if( item.tag == 'text' ){
					        if( item.content == undefined )
					        	item.content = 'Sample Text';
					        #>
								<div class="kc-box-inner-text" contenteditable="true">{{{item.content}}}</div>
						    <#
					    }else if( item.tag == 'image' ){
						    if( item.attributes.src == undefined )
						    	item.attributes.src = kc_plugin_url+'/assets/images/get_start.jpg';
					        #>
								<img data-action="select-image" src="{{item.attributes.src}}" />
						    <#
					    }else if( item.tag == 'icon' ){
						    if( item.attributes.class == undefined )
						    	item.attributes.class = 'fa-leaf';
					        #>
							<span data-action="icon-picker"><i class="{{item.attributes.class}}"></i></span>
						    <#
					    }else{
				        
					       					        	
					        #>{{{kc.template( 'box-design', item.children )}}}<#
				        
				        }
				        
				        #><div class="kc-box mb-helper">
					        <a href="#" data-action="add" data-pos="inner">
						        <i class="sl-plus"></i> 
						        <?php _e('Add Node', 'kingcomposer'); ?>
						    </a>
					    </div>
				    
				    <# }/*EndIf*/ #>
				    
		        </div>
		    </div>
		    
		<#
		
		});
	}	
#>
</script>

<script type="text/html" id="tmpl-kc-param-group-template">

	<div class="kc-group-row">
		<div class="kc-group-controls">
			<ul>
				<li class="collapse" data-action="collapse" title="<?php _e('expand / collapse', 'kingcomposer' ); ?>">
					<i class="sl-arrow-down" data-action="collapse"></i>
				</li>
				<li class="counter"> #1 </li>
				<li class="delete" data-action="delete" title="<?php _e('Delete this group', 'kingcomposer' ); ?>">
					<i data-action="delete" class="sl-close"></i>
				</li>

				<li class="double" data-action="double" title="<?php _e('Double this group', 'kingcomposer' ); ?>">
					<i class="sl-docs" data-action="double"></i>
				</li>			
			</ul>
		</div>
		<div class="kc-group-body"></div>
	</div>

</script>

<script type="text/html" id="tmpl-kc-wp-widgets-element-template">
<ul class="kc-wp-widgets-ul kc-components-list kc-wp-widgets-pop">
	<li data-category="wp_widgets" data-name="kc_wp_sidebar" class="kc-element-item" title="<?php _e('Display WordPress sidebar', 'kingcomposer'); ?>">
		<div>
			<i class="cpicon kc-icon-sidebar"></i>
			<span class="cpdes">
				<strong><?php _e('WordPress SideBar', 'kingcomposer'); ?></strong>
			</span>
		</div>
	</li>
	<#
	kc.widgets.find('>div.widget').each(function(){
		var tit = jQuery(this).find('.widget-title').text(),
			des = jQuery(this).find('.widget-description').html(),
			base = '{"'+jQuery(this).find('input[name="id_base"]').val()+'":{}}';
			
#>	
		<li class="kc-element-item" data-data="{{kc.tools.base64.encode(base)}}" data-category="wp_widgets" data-name="kc_wp_widget" title="{{des}}">
			<div>
				<span class="cpicon kc-icon-wordpress"></span>
				<span class="cpdes">
					<strong>{{tit}}</strong>
					<i>{{des}}</i>
				</span>
			</div>
		</li>
<#	
	});
#>
</ul>
<#
	data.callback = function( wrp, e ){
		wrp.find( 'li' ).on( 'click', e.data.items );
	}
#>
</script>


<?php
/**
*
*	King Composer
*	(c) KingComposer.com
*
*/
if(!defined('KC_FILE')){
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$kc = KingComposer::globe();

//Cache

foreach(

	array(
		'text' => 'kc_param_type_text',
		'number' => 'kc_param_type_number',
		'hidden' => 'kc_param_type_hidden',
		'textarea' => 'kc_param_type_textarea_raw_html',
		'custom' => 'kc_param_type_textarea_custom',
		'select' => 'kc_param_type_select',
		'dropdown' => 'kc_param_type_select',
		'textarea_html' => 'kc_param_type_textarea_html',
		'editor' => 'kc_param_type_editor',
		'multiple' => 'kc_param_type_multiple',
		'checkbox' => 'kc_param_type_checkbox',
		'radio' => 'kc_param_type_radio',
		'radio_image' => 'kc_param_type_radio_image',
		'toggle' => 'kc_param_type_toggle',
		'attach_media' => 'kc_param_type_attach_media',
		'attach_image' => 'kc_param_type_attach_image',
		'attach_image_url' => 'kc_param_type_attach_image_url',
		'attach_images' => 'kc_param_type_attach_images',
		'color_picker' => 'kc_param_type_color_picker',
		'icon_picker' => 'kc_param_type_icon_picker',
		'date_picker' => 'kc_param_type_date_picker',
		'kc_box' => 'kc_param_type_kc_box',
		'wp_widget' => 'kc_param_type_wp_widget',
		'css_box_tbtl' => 'kc_param_type_css_box_tbtl',
		'css_box_border' => 'kc_param_type_css_box_border',
		'group' => 'kc_param_type_group',
		'css' => 'kc_param_type_css',
		'select_group' => 'kc_param_type_select_group',
		'css_border' => 'kc_param_type_css_box_border',
		'corners' => 'kc_param_type_corners',
		'css_background' => 'kc_param_type_css_background',
		'link' => 'kc_param_type_link',
		'autocomplete' => 'kc_param_type_autocomplete',
		'number_slider' => 'kc_param_type_number_slider',
		'random' => 'kc_param_type_random',
		'css_family' => 'kc_param_type_css_family',
		'animate' => 'kc_param_type_animate',
		'undefined' => 'kc_param_type_undefined',
	) as $name => $func ){

	$kc->add_param_type_cache( $name, $func );
	
}

// Nocache

foreach(

	array(
		'post_taxonomy' => 'kc_param_type_post_taxonomy',
		'wp_sidebars' => 'kc_param_type_wp_sidebars',
	) as $name => $func ){

	$kc->add_param_type( $name, $func );
	
}

function kc_param_type_text(){
	echo '<input name="{{data.name}}" class="kc-param" value="{{data.value}}" type="text" />';
}

function kc_param_type_number(){
?>
	<input value="{{data.value.replace(/[^0-9\.]/g,'')}}" type="number" />
	<input name="{{data.name}}" class="kc-param" value="{{data.value}}" type="hidden" />
	<#
		if( data.options !== undefined && data.options.units !== undefined ){
			var unit = data.value.replace(/[0-9\.]/g,'');
			if( unit === '' )
				unit =  data.options.units[0];
	#>
		<ul>
			<#
				for( var i=0; i<data.options.units.length; i++ ){
					#><li<# if( unit == data.options.units[i] ){ #> class="active"<# } #>>{{data.options.units[i]}}</li><#
				}
			#>
		</ul>
	<#		
		}
	#>
<#
	data.callback = kc.ui.callbacks.number;
#>	
<?php
}

function kc_param_type_hidden(){
	echo '<input name="{{data.name}}" class="kc-param" value="{{data.value}}" type="hidden" />';
}

function kc_param_type_textarea_raw_html(){
?><#
	if( data.value === undefined )
		var value = '';
	else var value = data.value;
#>
<textarea name="{{data.name}}" cols="46" rows="8" data-encode="base64" class="kc-param kc-row-area">{{kc.tools.base64.decode( value.replace(/(?:\r\n|\r|\n)/g,'') )}}</textarea>
<?php
}

function kc_param_type_textarea_custom(){
?><#
	if( data.value === undefined )
		var value = '';
	else var value = data.value;
#>
<textarea name="{{data.name}}" cols="46" rows="8" class="kc-param kc-row-area">{{value}}</textarea>
<?php
}

function kc_param_type_select(){
?>
	<select class="kc-param" name="{{data.name}}">
		<# if( data.options ){
			for( var n in data.options ){
				if( typeof data.options[n] == 'array' ||  typeof data.options[n] == 'object' ){
				#><optgroup label="{{n}}"><#
					for( var m in data.options[n] ){
						#><option<#
							if( m == data.value ){ #> selected<# }
							#> value="{{m}}">{{data.options[n][m]}}</option><#
					}
				#></optgroup><#

				}else{

		#><option<#
					if( n == data.value ){ #> selected<# }
				#> value="{{n}}">{{data.options[n]}}</option><#
				}
			}
		} #>
	</select>
<?php
}

function kc_param_type_textarea_html(){
?>
	<# 
	
	if( data.name == 'content' ){
		
	var eid = parseInt( Math.random()*100000 ); #>

	<div class="kc-textarea_html-field-wrp">
		<div class="kc-editor-wrapper">
            <div id="wp-kc-content-{{eid}}-wrap" class="wp-core-ui wp-editor-wrap tmce-active">
                <div id="wp-kc-content-{{eid}}-editor-tools" class="wp-editor-tools hide-if-no-js">
                    <div id="wp-kc-content-{{eid}}-media-buttons" class="wp-media-buttons">
                        <button type="button" class="button kc-insert-media" data-editor="kc-content-{{eid}}">
                        	<i class="sl-cloud-upload"></i> <?php _e('Insert Media', 'kingcomposer'); ?>
                        </button>
                    </div>
                    <i class="kc-edtip"><?php _e('Press shift+enter to break new line', 'kingcomposer'); ?></i>
                    <div class="wp-editor-tabs">
                        <button type="button" class="wp-switch-editor switch-tmce" data-wp-editor-id="kc-content-{{eid}}"><?php _e('Visual', 'kingcomposer'); ?></button>
                        <button type="button" class="wp-switch-editor switch-html" data-wp-editor-id="kc-content-{{eid}}"><?php _e('Text', 'kingcomposer'); ?></button>
                    </div>
                </div>
                <div id="wp-kc-content-{{eid}}-editor-container" class="wp-editor-container">
                    <div id="qt_kc-content-{{eid}}_toolbar" class="quicktags-toolbar"></div>
                    <textarea class="wp-editor-area kc-param" rows="10" autocomplete="off" width="100%" name="{{data.name}}" id="kc-content-{{eid}}">{{data.value}}</textarea>
                </div>
            </div>
        </div>
	</div>
	<#
		data.callback = function( wrp, $ ){
			
			kc.tools.editor.init( $('#kc-content-'+eid) );

			var pop = wrp.closest('.kc-params-popup'), model = pop.data('model');
			
			if( kc.storage[model] !== undefined && kc.maps[kc.storage[model].name] !== undefined && kc.maps[kc.storage[model].name].is_container !== true ){
				wrp.html('<i style="color:red"><?php _e('Editor Error: This field type requires shortcode type is_container, Plese read our document carefully', 'kingcomposer'); ?>: <a href="http://docs.kingcomposer.com/available-param-types/textarea-html-field/" target=_blank>Textarea HTML Field</a></i>');
				return;
			}
			
			kc.tools.popup.callback( pop, { 
				before_callback : function( pop ){
					pop.find('.kc-textarea_html-field-wrp').each(function(){
						if( $(this).find('.wp-editor-wrap').hasClass('tmce-active') ){
							var txt = $(this).find('textarea.kc-param');
							txt.val( switchEditors.wpautop( tinyMCE.get( txt.attr('id') ).getContent() ) );
						}
					});
				}
			}, 'field-column-text-callback' );

			wrp.find('.kc-insert-media').on('click', function( atts ){
			
				kc.tools.editor.insert( window.wpActiveEditor ,wp.media.string.image( atts ) );

			}, kc.tools.media.opens );

		}
		
	}else{
		#><i style="color:red"><?php _e('Editor Error: This field type requires name is "content", you set the name is ', 'kingcomposer'); ?>"{{data.name}}"</i><#
	}
	#>

<?php
}

function kc_param_type_editor(){
?>
	<# 
	
		var eid = parseInt( Math.random()*100000 ), value = '';
		if( data.value !== undefined )
			value = data.value;
		if( value !== '' )
			value = kc.tools.base64.decode(value.replace(/(?:\r\n|\r|\n)/g,'')).replace(/\%SITE\_URL\%/g,kc_site_url);
	#>

	<div class="kc-textarea_html-field-wrp">
		<div class="kc-editor-wrapper">
            <div id="wp-kc-content-{{eid}}-wrap" class="wp-core-ui wp-editor-wrap">
                <div id="wp-kc-content-{{eid}}-editor-tools" class="wp-editor-tools hide-if-no-js">
                    <div id="wp-kc-content-{{eid}}-media-buttons" class="wp-media-buttons">
                        <button type="button" class="button kc-insert-media" data-editor="kc-content-{{eid}}">
                        	<i class="sl-cloud-upload"></i> <?php _e('Insert Media', 'kingcomposer'); ?>
                        </button>
                    </div>
                    <i class="kc-edtip"><?php _e('Press shift+enter to break new line', 'kingcomposer'); ?></i>
                    <div class="wp-editor-tabs">
                        <button type="button" class="wp-switch-editor switch-tmce" data-wp-editor-id="kc-content-{{eid}}"><?php _e('Visual', 'kingcomposer'); ?></button>
                        <button type="button" class="wp-switch-editor switch-html" data-wp-editor-id="kc-content-{{eid}}"><?php _e('Text', 'kingcomposer'); ?></button>
                    </div>
                </div>
                <div id="wp-kc-content-{{eid}}-editor-container" class="wp-editor-container">
                    <div id="qt_kc-content-{{eid}}_toolbar" class="quicktags-toolbar">
	                  <?php _e('To optimize performance the editor is not activated automatically, Click to active.', 'kingcomposer'); ?>
                    </div>
                    <textarea class="wp-editor-area kc-param" data-encode="base64" rows="10" autocomplete="off" width="100%" name="{{data.name}}" id="kc-content-{{eid}}">{{value}}</textarea>
                </div>
            </div>
        </div>
	</div>
	
	<#
		data.callback = function( wrp, $ ){
			
			wrp.on('click', eid, function(e){
				kc.tools.editor.init( $('#kc-content-'+e.data) );
				$(this).off('click');
			});

			var pop = wrp.closest('.kc-params-popup');
			kc.tools.popup.callback( pop, { 
				before_callback : function( pop ){

					pop.find('.wp-editor-wrap').each(function(){
						
						if( $(this).data('loaded') !== true )
							$(this).data({'loaded': true});
						else return;
						
						var txt = $(this).find('textarea.kc-param');
						if( $(this).hasClass('tmce-active') ){
							var id = txt.get(0).id, 
								content = switchEditors.wpautop( tinyMCE.get( id ).getContent() ), 
								rex = new RegExp( kc_site_url, "g");
							content = content.replace( rex, '%SITE_URL%' );
							txt.val( content );
						}
					});
	
				}
			}, 'field-editor-callback' );

			wrp.find('.kc-insert-media').on('click', function( atts ){
			
				kc.tools.editor.insert( window.wpActiveEditor ,wp.media.string.image( atts ) );

			}, kc.tools.media.opens );

		}
	#>

<?php
}

function kc_param_type_multiple(){
?>
<#
	var value = '';
	if( data.value !== undefined )
		value = data.value;
#>
	<div kc-multiple-field-wrp>
		<select multiple>
			<# if( data.options ){
				var vals = value.split(',');
				for( var n in data.options ){
					if( typeof data.options[n] == 'array' ||  typeof data.options[n] == 'object' ){
					#><optgroup label="{{n}}"><#
						for( var m in data.options[n] ){
							#><option<#
								if( vals.indexOf( m ) > -1 ){ #> selected<# }
								#> value="{{m}}">{{data.options[n][m]}}</option><#
						}
					#></optgroup><#

					}else{

			#><option<#
						if( vals.indexOf( n ) > -1 ){ #> selected<# }
					#> value="{{n}}">{{data.options[n]}}</option><#
					}
				}
			} #>
		</select>
		<input type="hidden" name="{{data.name}}" class="kc-param kc-empty-param" value="{{data.value}}" />
		<a href="#" class="clear-selected">
			<i class="sl-close"></i> <?php _e('Remove Selection', 'kingcomposer'); ?>
		</a>
	</div>
	<#
		data.callback = function( el ){
			el.find('select').on( 'change', el, function(e){
				e.data.find('input.kc-param').val( jQuery(this).val() );
			});
			el.find('.clear-selected').on( 'click', el, function(e){
				e.data.find('select option:selected').removeAttr('selected');
				e.data.find('input.kc-param').val('');
				e.preventDefault();
			});
		}
	#>
<?php
}

function kc_param_type_checkbox(){
?>
<#
	var value = '';
	if( data.value !== undefined )
		value = data.value;
#>
	<# if( data.options ){
		var vals = value.split(','), rid;
		for( var n in data.options ){
			rid = parseInt(Math.random()*100000);
			#><input type="checkbox" id="kc-radio-{{data.name}}-{{rid}}" class="kc-param" name="{{data.name}}" <#
				if( vals.indexOf( n ) > -1 ){ #> checked<# }
			#> value="{{n}}" /> <label class="rbtn" for="kc-radio-{{data.name}}-{{rid}}">{{data.options[n]}}</label>
		<# }
	} #><input type="checkbox" checked class="kc-param kc-empty-param" value="" name="{{data.name}}" style="display:none;" />
<?php
}

function kc_param_type_radio(){
?>
	<div class="kc-radio-field-wrp">
		<# if( data.options ){
				var rid;
			for( var n in data.options ){
				rid = parseInt(Math.random()*100000);
				#><input type="radio" id="kc-radio-{{data.name}}-{{rid}}" class="kc-param" name="{{data.name}}" <#
					if( n == data.value ){ #> checked<# }
				#> value="{{n}}" /> <label class="rbtn" for="kc-radio-{{data.name}}-{{rid}}">{{data.options[n]}}</label>
			<# } #>
			<a href="#" class="clear-selected">
				<?php _e('Remove Selection', 'kingcomposer'); ?>
			</a>
		<# } #><input type="radio" class="kc-param empty-value kc-empty-param" value="" name="{{data.name}}" style="display:none;" />
	</div>
	<#
		data.callback = function( el ){
			el.find('.clear-selected').on( 'click', el, function(e){
				e.data.find('input.kc-param.empty-value').attr({'checked':true});
				e.preventDefault();
			});
		}
	#>
<?php
}

function kc_param_type_radio_image(){
?>
	<div class="kc-radio-image-field-wrp">
		<# if( data.options ){
			#>
			<div class="kc-radio-image-field-body">
				<# 
					var rid;
					for( var n in data.options ){
						rid = parseInt(Math.random()*100000);
					#><input type="radio" id="kc-radio-{{data.name}}-{{rid}}" class="kc-param" name="{{data.name}}" <#
						if( n == data.value ){ #> checked<# }
					#> value="{{n}}" /> 
					<label class="rbtn" for="kc-radio-{{data.name}}-{{rid}}"><img src="{{data.options[n]}}" /></label>
				<# } #>
				<img class="large-view" src="{{data.options[n]}}" />
			</div>
			<a href="#" class="clear-selected">
				<?php _e('Remove Selection', 'kingcomposer'); ?>
			</a>
		<# } #><input type="radio" class="kc-param empty-value kc-empty-param" value="" name="{{data.name}}" style="display:none;" />
	</div>
	<#
		data.callback = kc.ui.callbacks.radio_image;
	#>
<?php
}

function kc_param_type_select_group(){
?>
	<div class="kc-select_group-field-wrp">
		<div class="buttons">
		<# if( data.options !== undefined && data.options.buttons !== undefined ){
			
			var type = 'hidden';
			if( data.options.custom === true ){
				type = 'text';
			}
				
			for( var n in data.options.buttons ){
				#><button data-value="{{n}}"<#
					if( n == data.value ){ #> class="active"<# }
				#>>{{{data.options.buttons[n]}}}<#
					if( data.options.tooltip === true && n !== '' ){
						#><span class="tooltip">{{n.replace(/\-/g,' ')}}</span><#
					}
				#></button>
			<# } #>
		<# } #>
		</div>
		<input type="{{type}}" placeholder="<?php _e('Custom','kingcomposer'); ?>" class="kc-param" value="{{data.value}}" name="{{data.name}}" />
	</div>
	<#
		data.callback = kc.ui.callbacks.select_group;
	#>
<?php
}

function kc_param_type_toggle(){
	
?>	<#
		if( data.options === undefined || data.options.label === undefined )
			data.options = { 'label': 'yes|no' };
		data.options.label = data.options.label.split('|');
		
		if( data.value == 'yes' )
			checked = 'checked';
		else checked = '';
		
	#>
	<div class="kc-toggle-field-wrp">
		<div class="switch">
			<input type="checkbox" {{checked}} value="yes" name="{{data.name}}" class="toggle-button kc-param">
			<span class="toggle-label" data-on="{{data.options.label[0]}}" data-off="{{data.options.label[1]}}"></span>
			<span class="toggle-handle"></span>
			<input type="checkbox" checked class="kc-param kc-hidden kc-empty-param" value="" name="{{data.name}}" />
		</div>
	</div>
	<#
		data.callback = function( el, $ ){
			el.find('.toggle-button').on( 'click', el, function(e){

			    if( this.checked )
			        $(this).val('yes');
			    else
			        $(this).val('no');
			});
		}
	#>
<?php
}

function kc_param_type_attach_media(){
?>
<#
	var value = '';
	if( data.value !== undefined )
		value = data.value;
#>
	<div class="kc-attach-field-wrp">
		<input name="{{data.name}}" class="kc-param" value="{{value}}" type="hidden" />
		<div class="media-wrp">
			<div class="filename"><#
				if( value != '' ){
					value = value.split('/');
					#>{{value[value.length-1]}}<#
				}else{
					#>empty<#
				}
			#></div>
			<i class="sl-close" title="<?php _e('Delete this mdia', 'kingcomposer'); ?>"></i>		</div>
		<div class="clear"></div>
		<a class="button media button-primary">
			<i class="sl-cloud-upload"></i> <?php _e('Browse Media', 'kingcomposer'); ?>
		</a>
	</div>
	<#
		data.callback = function( el, $ ){

			el.find('.media').on( 'click', { callback: function( atts ){

				var wrp = $(this.el).closest('.kc-attach-field-wrp'), url = atts.url;

				wrp.find('input.kc-param').val(url).change();
				
				url = url.split('/');
				
				wrp.find('.filename').html(url[url.length-1]);

			}, atts : { frame: 'select' } }, kc.tools.media.open );

			el.find('div.media-wrp .sl-close').on( 'click', el, function( e ){
				e.data.find('input.kc-param').val('');
				$(this).closest('div.media-wrp').find('.filename').html('empty');
			});

			el.find('div.media-wrp .filename').on( 'click', el, function( e ){
				el.find('.media').trigger('click');
			});



		}
	#>
<?php
}

function kc_param_type_attach_image(){
?>
	<# if( data.value ==='undefined' )
	    data.value = '';
	 #>
	<div class="kc-attach-field-wrp">
		<input name="{{data.name}}" class="kc-param" value="{{data.value}}" type="hidden" />
		<# if( data.value != '' ){ #>
		<div class="img-wrp">
			<img src="{{kc_site_url}}/wp-admin/admin-ajax.php?action=kc_get_thumbn&id={{data.value}}" alt="" />
			<i class="sl-close" title="<?php _e('Delete this image', 'kingcomposer'); ?>"></i>
		</div>
		<# } #>
		<div class="clear"></div>
		<a class="button media button-primary">
			<i class="sl-cloud-upload"></i> <?php _e('Browse Image', 'kingcomposer'); ?>
		</a>
	</div>
	
	<# data.callback = kc.ui.callbacks.upload_image; #>
	
<?php
}

function kc_param_type_attach_image_url(){
?>

	<div class="kc-attach-field-wrp">
		<input name="{{data.name}}" class="kc-param" value="{{data.value}}" data-kc-param="{{data.name}}" type="hidden" />
		<# if( data.value != '' ){ #>
		<div class="img-wrp">
			<img src="{{data.value}}" alt="" />
			<i class="sl-close" title="<?php _e('Delete this image', 'kingcomposer'); ?>"></i>
			<div class="img-sizes"></div>
		</div>
		<# } #>
		<div class="clear"></div>
		<a class="button media button-primary">
			<i class="sl-cloud-upload"></i> <?php _e('Select Image', 'kingcomposer'); ?>
		</a>
	</div>
	
	<# data.callback = kc.ui.callbacks.upload_image_url; #>
	
<?php
}


function kc_param_type_attach_images(){
?>
	<div class="kc-attach-field-wrp">
		<input name="{{data.name}}" class="kc-param" value="{{data.value}}" type="hidden" />
		<#
			if( data.value !== undefined && data.value != '' ){
				data.value = data.value.split(',');
				for( var n in data.value ){
					#><div data-id="{{data.value[n]}}" class="img-wrp"><img title="<?php _e('Drag image to sort', 'kingcomposer'); ?>" src="{{kc_site_url}}/wp-admin/admin-ajax.php?action=kc_get_thumbn&id={{data.value[n]}}&size=thumbnail" alt="" /><i class="sl-close"></i></div><#
				}
		 #>
		<# } #>
		<div class="clear"></div>
		<a class="button media button-primary">
			<i title="<?php _e('Delete this image', 'kingcomposer'); ?>" class="sl-cloud-upload"></i> <?php _e('Browse Images', 'kingcomposer'); ?>
		</a>
	</div>

	<# data.callback = kc.ui.callbacks.upload_images; #>

<?php
}

function kc_param_type_color_picker(){
?>
	<input name="{{data.name}}" value="{{data.value}}" placeholder="Select color" class="kc-param" type="search" />
	<#
		data.callback = function( el, $ ){
			el.find('input').each(function(){
				this.color = new jscolor.color(this, {});
			});
	    }
	#>
<?php
}

function kc_param_type_icon_picker(){

?>	<# if( data.value == undefined || data.value == '' )data.value='fa-star'; #>
	<div class="icons-preview">
		<i class="{{data.value}}"></i>
	</div>
	<input name="{{data.name}}" value="{{data.value}}" placeholder="Click to select icon" class="kc-param kc-param-icons" type="text" />
	<#
		data.callback = kc.ui.callbacks.icon_picker;
	#>
<?php
}

function kc_param_type_date_picker(){
?>

	<input name="{{data.name}}" class="kc-param" value="{{data.value}}" type="text" />

<?php
}

function kc_param_type_kc_box(){

?>

	<textarea name="data" class="kc-param kc-box-area forceHide">{{data.value}}</textarea>
	<button class="button html-code" data-action="html-code">
		<i class="sl-doc"></i> <?php _e('HTML Code', 'kingcomposer'); ?>
	</button>
	<button class="button css-code" data-action="css-code">
		<i class="sl-settings"></i> <?php _e('CSS Code', 'kingcomposer'); ?>
	</button>
	<button class="button align-center add-top" data-action="add" data-pos="top">
		<i class="sl-plus"></i>
	</button>
	<div class="kc-box-render"></div>
	<button class="button align-center add-bottom" data-action="add" data-pos="bottom">
		<i class="sl-plus"></i>
	</button>
	<div class="kc-box-trash">
		<a href="#" class="button forceHide" data-action="undo">
			<i class="sl-action-undo"></i> Undo Delete(0)
		</a>
	</div>
<#

	data.callback = function( wrp, $ ){

		try{
			var data_obj = kc.tools.base64.decode( data.value.replace(/(?:\r\n|\r|\n)/g,'') );
			data_obj = data_obj.replace( /\%SITE\_URL\%/g, kc_site_url );
			data_obj = JSON.parse( data_obj );
		}catch(e){
			var data_obj = [{tag:'div',children:[{tag:'text', content:'There was an error with content structure.'}]}];
		}

		wrp.find('.kc-box-render').eq(0).append( kc.template( 'box-design', data_obj ) );

		var pop = kc.get.popup( wrp );
		
		kc.tools.popup.callback( pop, { before_callback : kc.ui.kc_box.renderBack }, 'field-kc-box-callback' );
		pop.addClass('preventCancel');

		kc.ui.kc_box.sort();

		wrp.on( 'click', function( e ){

			if( e.target.tagName == 'I' )
				var el = $( e.target.parentNode );
			else var el = $( e.target );

			kc.ui.kc_box.actions( el, e );

		} );

	}

#>
<?php
}


function kc_param_type_wp_widget(){

?><#

	try{
		var obj = JSON.parse( kc.tools.base64.decode( data.value ) );
	}catch(e){
		return '<center><?php _e('There was an error with content structure.', 'kingcomposer'); ?></center>';
	}
	var html = '';

	for( var n in obj ){

		kc.widgets.find('input[name="id_base"]').each(function(){

			if( this.value == n ){

				html = jQuery(this).closest('div.widget').find('.widget-content').html();
				html = '<div class="kc-widgets-container" data-name="'+n+'">'
					   +html.replace(/name="([^"]*)"/g,function(r,v){

							v = v.split('][');
							v = ( v[1] != undefined ) ? v[1] : v[0];
							v = v.replace(/\]/g,'').trim();
							var str = 'name="'+v+'"';

							if( obj[n][v] != undefined )
								str += ' data-value="'+kc.tools.esc(obj[n][v])+'"';

							return str;

						})+'</div>';
			}
		});
	}

	#>{{{html}}}<#

	data.callback = kc.ui.callbacks.wp_widgets;

#>
<?php
}


function kc_param_type_css_box_tbtl(){
?>
	<#
		var imp = data.value.indexOf('!important');
		if( imp > -1 )
			imp = '!important';
		else imp = '';
		var val = data.value.replace('!important','').split(' ');
	#>
	<ul class="multi-fields-ul">
		<li>
			<input class="kc-param" name="{{data.name}}-top" class="m-f-u-first" value="{{val[0]}}" type="text" /><br /> <strong>Top</strong>
		</li>
		<li>
			<input class="kc-param" name="{{data.name}}-bottom" type="text" value="{{val[2]}}" /><br /> Bottom
		</li>
		<li>
			<input class="kc-param" name="{{data.name}}-left" type="text" value="{{val[3]}}" /><br /> Left
		</li>
		<li>
			<input class="kc-param" name="{{data.name}}-right" class="m-f-u-last" type="text" value="{{val[1]}}" /><br /> Right
		</li>
		<li class="m-f-u-li-link">
			<span><input <# if( val[0] == val[1] && val[1] == val[2] && val[2] == val[3] ){ #>checked<#} #> type="checkbox" /></span><br /> <i class="sl-link"></i>
		</li>
		<input type="hidden" class="kc-param" name="{{data.name}}-important" value="{{imp}}" />
		<br />
		<div class="m-p-r-des"><?php _e('Click & hold the input, then move up or move down to change value','kingcomposer'); ?></div>
	</ul>
	<#
		data.callback = function( el, $ ){
			el.find('input[type=text]').on( 'keyup', el, function( e ){
				if( e.data.find('input[type=checkbox]').get(0).checked == true ){
					var cur = this;
					e.data.find('input[type=text]').each(function(){
						if( this != cur )
							this.value = cur.value;
					});
				}
			}).on( 'mousedown', function(e){
				
				if( e.which !== undefined && e.which !== 1 )
					return false;
					
				$(document).on( 'mouseup', function(){
					$(document).off( 'mousemove' ).off('mouseup');
					$('body').css({cursor:''});
				});
				
				var ext = this.value.replace(/[0-9\-]/g,'');
				if( ext === '' )
					ext = 'px';
				$(document).on( 'mousemove', { 
					el: this,
					cur: parseInt(this.value!==''?this.value:0),
					ext: ext,
					top: e.clientY
				}, function(e){
					var offset = e.clientY-e.data.top;
					e.data.el.value = (e.data.cur-offset)+e.data.ext;
					$(e.data.el).trigger('change');
				});
				
				$('body').css({cursor:'ns-resize'});
				
				$( window ).off('mouseup').on('mouseup', function(){
					$(document).off('mousemove');
					$(window).off('mouseup');
					$('html,body').removeClass('kc_dragging noneuser');
				});
				
			});
			el.find('input[type=checkbox]').on( 'change', el, function( e ){
				if( this.checked == true ){
					e.data.find('input[type=text]').val( e.data.find('input[type=text]').get(0).value );
				}
			});
		}
	#>
<?php
}

function kc_param_type_corners(){
?>
	<#
		var val = data.value.trim().split(' ');

		if( data.options === undefined )
			data.options = {};

	#>
	<input name="{{data.name}}" class="kc-param" data-css-corners-value="" value="{{data.value}}" type="hidden" />
	
	<div class="kc-corners-wrp">
		<# 
			var i=0, pos = ['top', 'right', 'bottom', 'left'], va = '';
			if( data.options ){
				for( var c in data.options ){
					
					if( val[i] !== undefined && val[i] != 'inherit' )
						va = val[i];
					else va = '';
				#>
					<div class="kc-corners-{{pos[i]}} kc-corners-pos">
						<input data-css-corners="{{c}}" name="{{c}}" value="{{va}}" type="text" />
					</div>
				<# 
				i++; 
				} 
			} 
		
		#>
		<div class="m-f-u-li-link<# if( val[0] == val[1] && val[2] == val[0] && val[3] == val[0] ){ #> active<# } #>">
			<span><i class="sl-link"></i></span>
		</div>
	</div>

	<# data.callback = kc.ui.callbacks.corners; #>
<?php
}
function kc_param_type_css_box_border(){
?>
	<#
		var imp = (data.value.indexOf('!important')>-1) ? '!important' : '';
	#>
	
	<div class="kc-corners-wrp hidden">
		
		<div class="kc-corners-top kc-corners-pos">
			<button data-dir="top"></button>
		</div>
	
		<div class="kc-corners-right kc-corners-pos">
			<button data-dir="right"></button>
		</div>
	
		<div class="kc-corners-bottom kc-corners-pos">
			<button data-dir="bottom"></button>
		</div>
	
		<div class="kc-corners-left kc-corners-pos">
			<button data-dir="left"></button>
		</div>
				
		<div class="m-f-u-li-link">
			<span><i class="sl-link"></i></span>
		</div>
		
	</div>
	
	<input name="{{data.name}}" class="kc-param" data-css-border="value" value="{{data.value}}" type="hidden" />
	
	<ul class="multi-fields-ul">
		<li>
			<input name="border-width" placeholder="Width" class="m-f-u-first" data-css-border="width" value="" type="text" />
		</li>
		<li>
			<span class="m-f-u-li-splect">
				<select name="border-style" data-css-border="style">
					<option value="none">- <?php _e('Style', 'kingcomposer'); ?> -</option>
					<option value="hidden">hidden</option>
					<option value="dotted">dotted</option>
					<option value="dashed">dashed</option>
					<option value="solid">solid</option>
					<option value="double">double</option>
					<option value="groove">groove</option>
					<option value="ridge">ridge</option>
					<option value="inset">inset</option>
					<option value="outset">outset</option>
					<option value="initial">initial</option>
					<option value="inherit">inherit</option>
				</select>
			</span>
		</li>
		<li>
			<input type="text" name="border-color" placeholder="<?php _e('Select color', 'kingcomposer'); ?>" data-css-border="color" value="" width="80" class="m-f-bb-color" />
		</li>
	</ul>
	<a href="#" class="css-border-advanced"><?php _e('Advanced setting borders', 'kingcomposer'); ?></a>
	<#
		data.callback = kc.ui.callbacks.css_border;
	#>
<?php
}

function kc_param_type_group(){
?>
<input type="hidden" name="{{data.name}}[0]" class="kc-param" />
<div class="kc-group-rows"></div>
<#
	try{
		data.value = kc.tools.base64.decode( data.value );
		data.value = data.value.replace( /\%SITE\_URL\%/g, kc_site_url );
		data.value = JSON.parse( data.value );
		var values = {};
		for( var i in data.value ){
			if( typeof( data.value[i] ) == 'object' ){
				if( i.indexOf( data.name+'[' ) == -1 ){
					values[ data.name+'['+i+']' ] = {};
					for( var j in data.value[i] ){
						values[ data.name+'['+i+']['+j+']' ] = data.value[i][j];
					}
				}else values[ i ] = data.value[i];
			}
		}
	}catch(e){
		data.value = {'0':{}};
		var values = {};
	}

	data.callback = function( wrp, $, data ){
		
		var add_text;
		if( data.options === undefined || data.options.add_text === undefined )
			add_text = '<?php _e('Add new Group', 'kingcomposer'); ?>';
		else add_text = data.options.add_text;
		
		wrp.data({ 'name' : data.name, 'params': data.params });

		for( var n in data.value ){
			if( typeof( data.value[n] ) == 'object' ){
				var params = kc.params.fields.group.set_index( data.params, data.name, n );
				
				var grow = $( kc.template( 'param-group' ) );
				wrp.find('.kc-group-rows').append( grow );
				
				kc.params.fields.render( grow.find('.kc-group-body'), params, values );
			}

		}

		wrp.find('.kc-group-rows').append( '<div class="kc-group-controls kc-add-group"><i class="sl-plus"></i> '+add_text+'</div>' );

		kc.params.fields.group.callback( wrp );
		
	}

#>
<?php
}

function kc_param_type_css(){
?>
<input type="hidden" name="{{data.name}}" class="kc-param kc-field-css-value" value="{{data.value}}" />
<div class="kc-css-rows" style="min-height:500px"></div>
<# data.callback = kc.ui.callbacks.css; #>
<?php
}

function kc_param_type_link(){
?>
<#
	if( typeof data.value != 'undefined' && data.value != '' )
		var value = data.value.split('|');
	else value = ['','','','',''];
#>
	<input name="{{data.name}}" class="kc-param" value="{{data.value}}" type="hidden" />
	<a class="button link button-primary">
		<i class="sl-link"></i> <?php _e( 'Add your link', 'kingcomposer' ); ?>
	</a>
	<br />
	<span class="link-preview">
		<# if( value[0] !== undefined && value[0] != '' ){ #><strong>Link:</strong> {{value[0]}}<br /><# } #>
		<# if( value[1] !== undefined && value[1] != '' ){ #><strong>Title:</strong> {{value[1]}}<br /><# } #>
		<# if( value[2] !== undefined && value[2] != '' ){ #><strong>Target:</strong> {{value[2]}}<br /><# } #>
	</span>

<#

	data.callback = function( wrp, $ ){
		wrp.find('.button.link').on( 'click', wrp, function(e) {

            wpLink.open();

            var value = e.data.find('.kc-param').val();
            if( value != '' )
				value = value.split('|');
			else value = ['','','','',''];

            $('#wp-link-url').val( value[0] );
	        $('#wp-link-text').val( value[1] );
	        if( value[2] == '_blank' )
	        	$('#wp-link-target').attr({checked: true});

            if( $('#wp-link-update #kc-link-submit').length == 0 ){
            	$('#wp-link-update').append('<input type="submit" value="Add Link to KC" class="button button-primary" id="kc-link-submit" name="wp-link-submit" style="display: none">');
				$('#wp-link-cancel, #wp-link-close').on( 'click', function(e) {
					$('#wp-link-submit').css({display: 'block'});
					$('#kc-link-submit').css({display: 'none'});
			        wpLink.close();
			        e.preventDefault ? e.preventDefault() : e.returnValue = false;
			        e.stopPropagation();
			        return false;
			    });
            }

            $('#wp-link-submit').css({display: 'none'});

            $('#wp-link-update #kc-link-submit').css({display: 'block'}).off('click').on( 'click', e.data, function(e) {

	            var url = $('#wp-link-url').val(),
	            	text = $('#wp-link-text').val(),
	            	target = $('#wp-link-target').get(0).checked?'_blank':'';

				e.data.find('.kc-param').val(url+'|'+text+'|'+target).change();

				var preview = '';
				if( url != '' )
					preview += '<strong>Link:</strong> '+url+'<br />';
				if( text != '' )
					preview += '<strong>Title:</strong> '+text+'<br />';
				if( target != '' )
					preview += '<strong>Target:</strong> '+target+'<br />';

				e.data.find('.link-preview').html( preview );

				$('#wp-link-close').trigger('click');

	            wpLink.close();
	            e.preventDefault
	            return false;
	        });
            return false;
        });
	}

#>

<?php
}

function kc_param_type_post_taxonomy(){

	$post_types = get_post_types( array(
			'public'   => true,
			'_builtin' => false
		),
		'names'
	);

	$post_types = array_merge( array( 'post' => 'post'), $post_types );

	foreach($post_types as $post_type){
		$taxonomy_objects = get_object_taxonomies( $post_type, 'objects' );
		$taxonomy = key( $taxonomy_objects );
		$args[ $post_type ] = kc_get_terms( $taxonomy, 'slug' );
	}

	echo '<label>'.__( 'Select Content Type', 'kingcomposer' ).': </label>';
	echo '<br />';
	echo '<select class="kc-content-type">';
	foreach( $args as $k => $v ){
		echo '<option value="'.esc_attr($k).'">'.ucwords( str_replace(array('-','_'), array(' ', ' '), $k ) ).'</option>';
	}
	echo '</select>';
	echo '<div class="kc-select-wrp">';
		echo '<select style="height: 150px" multiple class="kc-taxonomies-select">';

		foreach( $args as $type => $arg ){

			echo '<option class="'.esc_attr($type).'-st" value="'.esc_attr($type).'" style="display:none;">'.esc_html($type).'</option>';
			$arg = array_splice($arg,0,100);
			foreach( $arg as $k => $v ){

				$k = $type.':'.str_replace( ':', '&#58;', $k );

				echo '<option class="'.esc_attr($type).' '.esc_attr($k).'" value="'.esc_attr($k).'" style="display:none;">'.esc_html($v).'</option>';

			}
		}

		echo '</select>';
		echo '<button class="button unselected" style="margin-top: 10px;">Remove selection</button>';
	echo '</div>';

?>
<# data.callback = kc.ui.callbacks.taxonomy; #>
<?php
}

function kc_param_type_autocomplete(){
	
?>
<div class="kc_autocomplete_wrp">
	<input class="kc-param" name="{{data.name}}" type="hidden" value="{{data.value}}" />
	<ul class="autcp-items"></ul>
	<input type="text" class="kc-autp-enter" placeholder="<?php _e('Enter your word','kingcomposer'); ?>..." />
	<div class="kc-autp-suggestion kc-free-scroll">
		<ul>
			<li><?php _e('Show up 120 relate posts','kingcomposer'); ?></li>
		</ul>
	</div>
</div>
<# data.callback = kc.ui.callbacks.autocomplete;	#>	
<?php	
}	

function kc_param_type_number_slider(){

?>
<div class="kc-number_slider-field-wrp">
    <div class="kc_percent_slider"></div>
	<input type="text" class="kc-param number_slider_field" name="{{data.name}}" value="{{data.value}}" />
</div>
<# data.callback = kc.ui.callbacks.number_slider; #>
	<?php
}


function kc_param_type_random(){
?>
	<#
		var new_random = parseInt(Math.random()*1000000);
	#>
	<input name="{{data.name}}" class="kc-param" value="{{new_random}}" type="text" />

<?php
}


function kc_param_type_css_background(){
?>
<# 
	var labels = { 
		color: '<?php _e('Background Color', 'kingcomposer'); ?>', 
		image: '<?php _e('BG Image', 'kingcomposer'); ?>', 
		repeat: '<?php _e('BG repeat', 'kingcomposer'); ?>', 
		position: '<?php _e('BG position', 'kingcomposer'); ?>', 
		attachment: '<?php _e('BG attachment', 'kingcomposer'); ?>', 
		size: '<?php _e('BG size', 'kingcomposer'); ?>',
		gradient: '<?php _e('BG Overlay & Gradient', 'kingcomposer'); ?>' 
	};
	
	if( typeof data.label != 'object' )
		data.label = {};
		
	labels = jQuery.extend( labels, data.label );
					
#>
	<input name="{{data.name}}" class="kc-param" data-css-background-value="" value="{{data.value}}" type="hidden" />
	<div class="kc-param-row field-color_picker">
		<div class="m-p-r-label">
			<label>{{labels.color}}:</label>
		</div>
		<div class="m-p-r-content">
			<input name="color" value="" data-css-background="color" placeholder="<?php _e('Select color', 'kingcomposer'); ?>" type="search" autocomplete="off">
		</div>
	</div>
	
	<div class="kc-param-row field-toggle">
		<div class="m-p-r-label">
			<label><?php _e('Advanced', 'kingcomposer'); ?> :</label>
		</div>
		<div class="m-p-r-content">			
			<div class="kc-toggle-field-wrp">
				<div class="switch">
					<input type="checkbox" name="use_image" data-css-background="toggle" class="toggle-button">
					<span class="toggle-label" data-on="yes" data-off="no"></span>
					<span class="toggle-handle"></span>
				</div>
			</div>
			<div class="m-p-r-des"><?php _e('Background image, gradient', 'kingcomposer'); ?></div>
		</div>
	</div>
	
	<div class="kc-control-field children box-bg kc-hidden">
		
		<div class="kc-param-row">
			<div class="m-p-r-label">
				<label>{{labels.gradient}}:</label>
			</div>
			<div class="m-p-r-content">
				<div class="kc-param-bg-gradient-colors">
					<span class="color-row">
						<input name="linearGradient" class="grdcolor" value="" data-css-background="gradient" placeholder="<?php _e('Select color', 'kingcomposer'); ?>" type="search" autocomplete="off" />
					</span>
				</div>
				<a href="#" class="button add-more-color">
					<i class="fa-plus"></i> <?php _e('Add more color', 'kingcomposer'); ?>
				</a> 
				&nbsp;
				<a href="#" class="button custom-degrees">
					<i class="fa-circle-o-notch"></i> <?php _e('Custom degrees', 'kingcomposer'); ?>
				</a>
				<div class="m-p-r-des"><?php _e('You can make background overlay by set only the first color with rgba color', 'kingcomposer'); ?></div>
			</div>
		</div>
		
		<div class="kc-param-row field-attach_image_url">
			<div class="m-p-r-label">
				<label>{{labels.image}}:</label>
			</div>
			<div class="m-p-r-content">
				<div class="kc-attach-field-wrp">
					<input name="image" value="" data-css-background="image" data-kc-param="image" type="hidden">
					<div class="img-wrp">
						<img src="" alt="" />
						<i class="sl-close" title="<?php _e('Delete this image', 'kingcomposer'); ?>"></i>
						<div class="img-sizes"></div>
					</div>
					<div class="clear"></div>
					<a class="button media button-primary">
						<i class="sl-cloud-upload"></i> <?php _e('Select background image', 'kingcomposer'); ?>	
					</a>
				</div>	
			</div>
		</div>
		<?php
			
			$args = array(
				array(
					'repeat', 
					array(
						array('no-repeat', 'no', 'No Repeat'),
						array('repeat', 'repeat', 'Repeat'),
						array('repeat-x', 'X', 'Horizontally'),
						array('repeat-y', 'Y', 'Vertically')
					),
					'style="width: 131px;"'
				),
				array(
					'position', 
					array(
						array('center center', 'center', 'Center Center'),
						array('top left', 'default', 'Top Left'),
						array('50% 50%', '50%', '50% 50%')
					),
					'style="width: 140px;"'
				),
				array(
					'attachment', 
					array(
						array('fixed', 'fixed', 'Fixed'),
						array('scroll', 'scroll', 'Scroll'),
						array('local', 'local', 'Local'),
						array('inherit', 'inherit', 'Inherit'),
					),
					'style="width: 95px;"'
				),
				array(
					'size', 
					array(
						array('auto', 'auto', 'Auto'),
						array('cover', 'cover', 'Cover'),
						array('contain', 'contain', 'Contain'),
						array('inherit', 'inherit', 'Inherit')
					),
					''
				),
			);
			
			foreach( $args as $arg ){
		?>
		
		<div class="kc-param-row field-select_group">
			<div class="m-p-r-label">
				<label>{{labels.<?php echo $arg[0]; ?>}}:</label>
			</div>
			<div class="m-p-r-content">
				<div class="kc-select_group-field-wrp">
					<div class="buttons">
						<?php foreach( $arg[1] as $btn ){
							echo '<button data-value="'.$btn[0].'">'.$btn[1].'<span class="tooltip">'.$btn[2].'</span></button>';
						} ?>
						<button data-value="" class="active"><i class="fa-ban"></i></button>
					</div>
					<input type="text" placeholder="Custom" <?php echo $arg[2]; ?> value="" name="<?php echo $arg[0];?>" data-css-background="<?php echo $arg[0];?>">
				</div>
			</div>
		</div>
		
		<?php } ?>
		
	</div>
	
	<# data.callback = kc.ui.callbacks.background; #>
	
<?php
}


function kc_param_type_css_family(){
?>
	<div class="kc-fonts-picker">
		<input placeholder="<?php _e('Select font', 'kingcomposer'); ?>" name="{{data.name}}" class="kc-css-param" value="{{data.value}}" type="text" />
		<button><?php _e('Fonts Manager', 'kingcomposer'); ?> <i class="fa-send"></i></button>
		<ul class="kc-fonts-list"></ul>
	</div>
	<# data.callback = kc.ui.callbacks.css_fonts; #>
<?php
}


function kc_param_type_animate(){
?>
	<input name="{{data.name}}" class="kc-param" value="{{data.value}}" type="hidden" />
	<div class="kc-animate-preview">
		<?php _e('Animate preview', 'kingcomposer'); ?>
		<small><?php _e('It happens when scroll over', 'kingcomposer'); ?></small>
	</div>
	<div class="kc-animate-field">
		<strong><?php _e('Effect', 'kingcomposer'); ?>:</strong>  
		<select class="input input-dropdown js-animations kc-animate-effect">
			<option value="" selected><?php _e('--Select an animate--', 'kingcomposer'); ?></option>
	        <optgroup label="Most Popular">
	        	<option value="fadeIn">fadeIn</option>
	        	<option value="fadeInUp">fadeInUp</option>
	        	<option value="fadeInDown">fadeInDown</option>
	        	<option value="fadeInLeft">fadeInLeft</option>
	        	<option value="fadeInRight">fadeInRight</option>
	        	<option value="bounceIn">bounceIn</option>
	        	<option value="bounceInLeft">bounceInLeft</option>
				<option value="bounceInRight">bounceInRight</option>
	        </optgroup>
	        <optgroup label="Attention Seekers">
	          <option value="bounce">bounce</option>
	          <option value="flash">flash</option>
	          <option value="pulse">pulse</option>
	          <option value="rubberBand">rubberBand</option>
	          <option value="shake">shake</option>
	          <option value="swing">swing</option>
	          <option value="tada">tada</option>
	          <option value="wobble">wobble</option>
	          <option value="jello">jello</option>
	        </optgroup>
	
	        <optgroup label="Bouncing Entrances">
	          <option value="bounceIn">bounceIn</option>
	          <option value="bounceInDown">bounceInDown</option>
	          <option value="bounceInLeft">bounceInLeft</option>
	          <option value="bounceInRight">bounceInRight</option>
	          <option value="bounceInUp">bounceInUp</option>
	        </optgroup>
	
	        <optgroup label="Fading Entrances">
	          <option value="fadeIn">fadeIn</option>
	          <option value="fadeInDown">fadeInDown</option>
	          <option value="fadeInDownBig">fadeInDownBig</option>
	          <option value="fadeInLeft">fadeInLeft</option>
	          <option value="fadeInLeftBig">fadeInLeftBig</option>
	          <option value="fadeInRight">fadeInRight</option>
	          <option value="fadeInRightBig">fadeInRightBig</option>
	          <option value="fadeInUp">fadeInUp</option>
	          <option value="fadeInUpBig">fadeInUpBig</option>
	        </optgroup>
	
	        <optgroup label="Flippers">
	          <option value="flip">flip</option>
	          <option value="flipInX">flipInX</option>
	          <option value="flipInY">flipInY</option>
	        </optgroup>
	
	        <optgroup label="Lightspeed">
	          <option value="lightSpeedIn">lightSpeedIn</option>
	        </optgroup>
	
	        <optgroup label="Rotating Entrances">
	          <option value="rotateIn">rotateIn</option>
	          <option value="rotateInDownLeft">rotateInDownLeft</option>
	          <option value="rotateInDownRight">rotateInDownRight</option>
	          <option value="rotateInUpLeft">rotateInUpLeft</option>
	          <option value="rotateInUpRight">rotateInUpRight</option>
	        </optgroup>
	
	        <optgroup label="Sliding Entrances">
	          <option value="slideInUp">slideInUp</option>
	          <option value="slideInDown">slideInDown</option>
	          <option value="slideInLeft">slideInLeft</option>
	          <option value="slideInRight">slideInRight</option>
	        </optgroup>
	        
	        <optgroup label="Zoom Entrances">
	          <option value="zoomIn">zoomIn</option>
	          <option value="zoomInDown">zoomInDown</option>
	          <option value="zoomInLeft">zoomInLeft</option>
	          <option value="zoomInRight">zoomInRight</option>
	          <option value="zoomInUp">zoomInUp</option>
	        </optgroup>
	
	        <optgroup label="Specials">
	          <option value="rollIn">rollIn</option>
	        </optgroup>
	      </select>
	</div>
	<div class="kc-animate-field">
		<strong><?php _e('Delay', 'kingcomposer'); ?>:</strong> 
		<input type="text" class="kc-animate-delay" placeholder="<?php _e('Example: 200', 'kingcomposer'); ?>" />
	</div>
	<div class="kc-animate-field">
		<strong><?php _e('Speed', 'kingcomposer'); ?>:</strong> 
		<select class="small-sel kc-animate-speed">
			<option value="">Normal</option>
			<option value="1s">Fast</option>
			<option value="3s">Slow</option>
			<option value=".5s">Fastest</option>
			<option value="3.5s">Slowest</option>
		</select>
	</div>
	<div class="m-p-r-des">
		<p>
			<?php _e('You can disable animate via', 'kingcomposer'); ?> 
			<a href="admin.php?page=kingcomposer" target=_blank><?php _e('KingComposer General Settings', 'kingcomposer'); ?></a>
		</p>
	</div>
	<# data.callback = kc.ui.callbacks.animate; #>
<?php
}

function kc_param_type_undefined(){
?>
	<input name="{{data.name}}" class="kc-param" value="{{data.value}}" type="text" />
<?php
}

function kc_param_type_wp_sidebars(){
?>
<select class="kc-param" name="{{data.name}}">
	<?php
		global $kc;
		$sidebars = $kc->get_sidebars();
		foreach( $sidebars as $slug => $name ){
			echo '<option<# if (data.value == "'.$slug.'"){ #> selected<# } #> value="'.$slug.'">'.$name.'</option>';
		}
	?>
</select>
<?php
}

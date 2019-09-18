/*
 * KingComposer Page Builder
 *
 * (c) Copyright king-theme.com
 *
 *
 * kc.mapper.js
 *
*/

(function ($) {
	
	window.kc_mapper = {
		
		changed: false,
		
		init : function() {
			
			kc.trigger({
				
				el : $('body'),
				
				events : {
					'.item.add-new:click': kc_mapper.add,
					'.item.import-export:click': kc_mapper.import_export,
					'#kc-mapper-parse:click': 'valid',
					'#kc-mapper-shortcode-info input[name="tag"]:change': kc_mapper.shortcode_string,
					'#kc-mapper-shortcode-info input[name="is_container"]:change': 'is_container',
					'#kc-mapper-fields-btn .save-fields:click': 'save_shortcode',
					'#kc-mapper-fields-btn .kc-mapper-settings-delete:click': 'delete_shortcode',
					'#kc-mapper-overlay:click': 'close',
					'.kc-mapper-settings-close:click': 'close',
					'#kc-mapper-screen-import-export h2.mp-title a:click': 'import_tab',
					'#kc-mapper-screen-import-export .tab.import input[type="file"]:change': 'upload_file',
					'#kc-mapper-screen-import-export button.do-export:click': 'do_export',
					'#kc-mapper-screen-import-export button.do-import:click': 'do_import',
				},
				
				is_container : function(e) {
					
					if (this.checked === true && $('#kc-mapper-fields .field_row.content_include').length === 0) {
						
						var atts = {
							name: 'content',
							label: 'Content',
							admin_label: '',
							description: '',
							content_include: true,
							level: 1,
							relation: ''
						}, field = $(kc.template('mapper-field', atts));
						
						$('#kc-mapper-fields').prepend(field);
						
						if (atts.callback !== undefined)
							atts.callback(field, atts);
						
						field.find('.field-row-body').show();
						
						kc_mapper.field.preview();
						kc_mapper.ui_init();
			
					} else if (this.checked === false && $('#kc-mapper-fields .field_row.content_include').length > 0) {
						$('#kc-mapper-fields .field_row.content_include').remove();
						kc_mapper.field.preview();
					}	
					
					kc_mapper.shortcode_string();
					
				},
				
				valid : function(e) {
					
					var textare = $('textarea#kc-mapper-string'),
						error = textare.parent().find('p.error'),
						string = textare.val().toString().trim(),
						name = string.split(' ')[0].replace(/\[/g, '').replace(/\]/g, '').trim();
						
					if (string === '') {
						error.show().html ('Error: Empty shortcode string');
						textare.data('shake')(textare);
						return;
					}
					
					if (/*kc.maps[name] !== undefined || */kc_mapper_shortcodes[name] !== undefined) {
						error.show().html ('Error: The shortcode "'+name+'" already exists');
						textare.data('shake')(textare);
						return;
					}
					
					if (string.indexOf('['+name) === 0 && string.indexOf('[/'+name+']') === (string.length-3-name.length)) {
						
						
						
					} else {
						
						if (string.indexOf('[') > -1 && (string.indexOf('[') > 0 || string.indexOf(']') === -1)) {
							error.show().html ('Error: Invalid shortcode format');
							textare.data('shake')(textare);
							return;
						}
						
						if (string.indexOf(']') > -1 && string.indexOf(']') < string.length-1) {
							error.show().html ('Error: Invalid shortcode format');
							textare.data('shake')(textare);
							return;
						}
						
					}
				
					error.hide();
					
					// start render
					kc_mapper.parse(string, name);
						
				},
				
				save_shortcode : function(e) {
					
					var inps = $('#kc-mapper-shortcode-info input.infp'),
						params = kc_mapper.field.get_params($('#kc-mapper-fields>.field_row_param')),
						tag = kc.tools.esc_slug(inps.filter('[name="tag"]').val());
					
					if (params === false)
						return;
					
					// validate params
					if (params === false)
						return;
					
					kc_mapper_shortcodes[tag] = {
						name: kc.tools.esc(inps.filter('[name="name"]').val()),
						description: kc.tools.esc(inps.filter('[name="description"]').val()),
						category: kc.tools.esc(inps.filter('[name="category"]').val()),
						icon: kc.tools.esc(inps.filter('[name="icon"]').val()),
						is_container: inps.filter('[name="is_container"]').get(0).checked,
						params: params
					}
					
					kc.msg(kc.__.saving+'..', 'loading');
					
					$.ajax({
				    	url: ajaxurl,
					    data: {
						    security: kc_mapper_nonce,
						    action: 'kc_update_mapper',
							data: kc.tools.base64.encode(JSON.stringify(kc_mapper_shortcodes[tag])),
							tag: tag,
							task: 'update'
					    },
					    method: 'POST',
						dataType: 'json',
					    success: function(json) {
						    if( json !== -1 && json != '-1' ){
							  	if( json.stt === 1 ){
								  	kc_mapper.changed = false;
								  	//kc.msg( json.message, 'success', 'sl-check' );
								  	$('#kc-preload').remove();
								  	kc_mapper.screen('close');
								  	kc_mapper.build_list();
							  	}else{
								  	kc.msg( json.message, 'error', 'sl-close' );
							  	}
							  	
							}else{
								kc.msg( kc.__.security, 'error', 'sl-close' );
							}
						}
					});
					
				},
				
				delete_shortcode : function(e) {
					
					var tag = kc.tools.esc_slug($('#kc-mapper-shortcode-info input.infp[name="tag"]').val());
					if (confirm('Are you sure that you want to delete the shortcode "'+tag+'"?')) {
						$.ajax({
					    	url: ajaxurl,
						    data: {
							    security: kc_mapper_nonce,
							    action: 'kc_update_mapper',
								tag: tag,
								task: 'delete'
						    },
						    method: 'POST',
							dataType: 'json',
						    success: function(json) {
							    if(json !== -1 && json != '-1') 
							    {
								  	if( json.stt === 1 )
								  	{
									  	//kc.msg( json.message, 'success', 'sl-check' );
									  	$('#kc-preload').remove();
									  	kc_mapper.changed = false;
									  	kc_mapper.screen('close');
									  	delete kc_mapper_shortcodes[tag];
									  	kc_mapper.build_list();
								  	}else{
									  	kc.msg( json.message, 'error', 'sl-close' );
								  	}
								  	
								}else{
									kc.msg( kc.__.security, 'error', 'sl-close' );
								}
							}
						});
					
					}	
				},
				
				import_tab : function(e) {
					var has = this.hash.replace('#', '');
					$(this).parent().find('.active').removeClass('active');
					$(this).addClass('active');
					$('#kc-mapper-screen-import-export .tab').hide();
					$('#kc-mapper-screen-import-export .tab.'+has).show();
				},
				
				upload_file : function(e){
					
					var f = this.files[0];
					
					if (f && f.name.indexOf('.kc') === (f.name.length - 3))
					{
						var r = new FileReader();
						r.onload = function(e) {
						  
							$('#kc-mapper-screen-import-export .tab.import textarea[name="import"]').val(this.result.trim());
						}
						
						r.readAsText(f);
					  
					}
					else
					{ 
					  alert("Unsupported file, please select a exported file *.kc");
					}
					
				},
				
				do_export : function(){
					
					var wrp = $('#kc-mapper-screen-import-export .tab.export'),
						text = wrp.find('textarea[name="export"]').val(),
						name = wrp.find('input[name="export-name"]').val();
						
					name = kc.tools.esc_slug(name.trim());
					wrp.find('.kc-notice').remove();
					
					if (name === '') {
						wrp.prepend('<div class="kc-notice kc-msg-error"><p>Error: __empty file name__</p></div>');
						wrp.find('input[name="export-name"]').shake();
						return;
					}
					
					if (text === '') {
						wrp.prepend('<div class="kc-notice kc-msg-error"><p>Error: __empty data export__</p></div>');
						wrp.find('textarea[name="export"]').shake();
						return;
					}
					
					try {
						var json = JSON.parse(text);
						if (Object.keys(json).length === 0) 
						{
							wrp.prepend('<div class="kc-notice kc-msg-error"><p>Error: __empty data export__</p></div>');
							wrp.find('textarea[name="export"]').shake();
							return;
						}
					} catch(ex) {
						
						wrp.prepend('<div class="kc-notice kc-msg-error"><p>Error: '+ex.message+'</p></div>');
						wrp.find('textarea[name="export"]').shake();
						if (ex.message.indexOf('in JSON at position') > -1) {
							var pos = parseInt(ex.message.split('in JSON at position')[1].trim());
							wrp.find('textarea[name="export"]').get(0).setSelectionRange(pos-2, pos+1);
						}
						return;
					}
					  
					var a = $('#kc-mapper-screen-import-export a.download-anchor').get(0),
						file = new Blob([text], {type: 'text/plain'});
						
					a.href = URL.createObjectURL(file);
					a.download = name+'.kc';
					
					a.click();
						
				},
				
				do_import : function(){
					
					var wrp = $('#kc-mapper-screen-import-export .tab.import'),
						data = wrp.find('textarea[name="import"]').val();
						
					wrp.find('.kc-notice').remove();
					
					if (data === '') {
						wrp.prepend('<div class="kc-notice kc-msg-error"><p>Error: __empty import data__</p></div>');
						wrp.find('textarea[name="import"]').shake();
						return;
					}
					
					try {
						data = JSON.parse(data);
					} catch(ex) {
						wrp.prepend('<div class="kc-notice kc-msg-error"><p>Error: '+ex.message+'</p></div>');
						wrp.find('textarea[name="import"]').shake();
						if (ex.message.indexOf('in JSON at position') > -1) {
							var pos = parseInt(ex.message.split('in JSON at position')[1].trim());
							wrp.find('textarea[name="import"]').get(0).setSelectionRange(pos-2, pos+1);
						}
						return;
					}
					
					var overw = $('#kc-mapper-import-overwrite').get(0).checked,
						report = {success: 0, fail: 0};
					
					for (var n in data) 
					{
						if (typeof data[n] != 'object') 
						{
							report.fail++;
						} 
						else if (kc_mapper_shortcodes[n] !== undefined) 
						{
							if (overw) 
							{
								kc_mapper_shortcodes[n] = data[n];
								report.success++;
							}
							else report.fail++;
						} 
						else 
						{
							kc_mapper_shortcodes[n] = data[n];
							report.success++;
						}
					}
					
					wrp.prepend('<div class="kc-notice"><p>Import complete: success ('+report.success+') failure ('+report.fail+')</p></div>');
					if (report.success > 0) {
						
						kc.msg(kc.__.saving+'..', 'loading');
					
						$.ajax({
					    	url: ajaxurl,
						    data: {
							    security: kc_mapper_nonce,
							    action: 'kc_update_mapper',
								data: kc.tools.base64.encode(JSON.stringify(kc_mapper_shortcodes)),
								task: 'import',
								tag: 'all'
						    },
						    method: 'POST',
							dataType: 'json',
						    success: function(json) {
							    if( json !== -1 && json != '-1' ){
								  	if( json.stt === 1 ){
									  	$('#kc-preload').remove();
									  	kc_mapper.screen('close');
									  	kc_mapper.build_list();
								  	}else{
									  	kc.msg( json.message, 'error', 'sl-close' );
								  	}
								  	
								}else{
									kc.msg( kc.__.security, 'error', 'sl-close' );
								}
							}
						});
					}
						
					
				},
				
				close : function(e) {
					
					if (this.id == 'kc-mapper-overlay' && e.target.id != 'kc-mapper-overlay')
						return;
						
					kc_mapper.screen('close');
					
				}
				
			});
			
			$('textarea#kc-mapper-string').data({
				shake : function(el) {
				  el.focus()
					.animate({marginLeft: -30}, 100)
					.animate({marginLeft: 20}, 100)
					.animate({marginLeft: -10}, 100)
					.animate({marginLeft: 5}, 100)
					.animate({marginLeft: 0}, 100);
				}
			});
			
			kc.ui.callbacks.icon_picker($('#kc-mapper-shortcode-info .kc-mp-sc-icon'), $);
			
			kc_mapper.build_list();
			
		},
		
		add : function(e) {
			
			kc_mapper.screen('open', 'add-new');
			$('textarea#kc-mapper-string').first()
				.val('[contact-form-7 id="1" title="Contact form 1"]').focus().parent().find('p.error').hide();
				
		},
		
		edit : function() {
			 
			kc_mapper.add();
			 
			var tag = $(this).data('tag');
			if (kc_mapper_shortcodes[tag] !== undefined) {
				kc_mapper_shortcodes[tag].tag = tag;
				kc_mapper.render(kc_mapper_shortcodes[tag]);
			}
		},
		
		parse : function(string, tag) {
			
			var regx = new RegExp ('\\[(\\[?)(' + tag + ')(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*(?:\\[(?!\\/\\2\\])[^\\[]*)*)(\\[\\/\\2\\]))?)(\\]?)', 'g'), 
				split_args = /([a-zA-Z0-9\-\_]+)="([^"]+)+"/gi,
				result, parames, is_container = false, content = '';
			
			if (string.indexOf('[') === -1 && string.indexOf(']') === -1)
				string = '['+string+']'

			while (result = regx.exec(string)) {

				params = [];
				while (agrs = split_args.exec(result[3])) {
					params.push({
						value: '',//agrs[2],
						name: agrs[1],
						label: agrs[1].replace(/\_/g, ' ').replace(/\-/g, ' '),
						description: '',
						admin_label: '',
						options: '',
						relation: ''
					});
				}
				
				if (result[5] !== undefined)
					params.content = result[5];
				
				if (result[6] !== undefined && result[6] !== '')
					is_container = true;
					
				this.render({
					params: params,
					tag: tag,
					name: tag.replace(/\_/g, ' ').replace(/\-/g, ' '),
					category: '',
					description: '',
					icon: 'fa-star',
					is_container: is_container
				});
				
			}
		},
		
		render : function(object) {
			
			var fields = $('#kc-mapper-fields'), field,
				inps = $('#kc-mapper-shortcode-info input.infp');
				
			kc_mapper.screen('open', 'edit');
			
			for (var n in object) {
				if (n != 'params') {
					if (n == 'is_container') 
					{
						if (object[n] === true)
							inps.filter('[name="'+n+'"]').attr({checked: true});
						else inps.filter('[name="'+n+'"]').attr({checked: false});
						
					} else inps.filter('[name="'+n+'"]').val(object[n]);
					
					if (n == 'icon')
						$('#kc-mapper-shortcode-info .icons-preview i').attr({class: object[n]});
					
				}
			}
			
			this.field.render(fields, object.params);
			
		},
		
		ui_init : function(wrp) {
			
			kc.ui.sortable({

			    items : '#kc-mapper-fields .field_row:not(.add_param)',
			    handle : '>h3.field-heading',
			    detectEdge: 10,

			    end : function() {
				    kc_mapper.field.preview();
				    kc_mapper.changed = true;
			    }

		    });
		    
		},
		
		field : {
			
			render : function(fields, params) {
				
				var level = (fields.attr('id') == 'kc-mapper-fields');
				
				fields.html('');
			
				for(var n in params) 
				{
					
					params[n].level = level;
					field = $(kc.template('mapper-field', params[n]));
					fields.append(field);
					
					if (params[n].callback !== undefined) 
					{
						params[n].callback(field, params[n]);
						delete params[n].callback;
					}
					
				}
				
				/*fields.find('>.field_row').first().find('.field-row-body').show();*/
				
				var add_btn = $('<div class="field_row add_param"><h3 class="field-heading"><i class="fa-plus"></i> Add Param</h3></div>');
				fields.append(add_btn);
				
				add_btn.on('click', fields, function(e) {
					
					var name = 'new_param', i = 0, pass = true;
					do {
						pass = true;
						e.data.find('>.field_row input[name="name"]').each(function(){
							if (this.value == name)
								pass = false;
						});
						if (pass === false) {
							i++;
							name = 'new_param_'+i;
						}
					} while (pass === false);
					
					var atts = {
						value: '',
						name: name,
						label: name.replace(/\_/g, ' '),
						description: '',
						admin_label: '',
						options: '',
						params: '',
						relation: '',
						level: (e.data.attr('id') == 'kc-mapper-fields')
					}, field = $(kc.template('mapper-field', atts));
					
					e.data.find('>.field_row.add_param').before(field);
					
					if (atts.callback !== undefined)
						atts.callback(field, atts);
					
					e.data.find('>.field_row>.field-row-body').hide();
					e.data.find('>.field_row:not(.add_param)').last().find('.field-row-body').show();
					
					kc_mapper.changed = true;
					
					kc_mapper.field.preview();
					kc_mapper.ui_init();
					
				});
			
				kc_mapper.field.preview();
				kc_mapper.ui_init();
			
			},
			
			change : function (e) {
				
				switch (this.name) {
					
					case 'name': this.value = kc.tools.esc_slug(this.value); break;
					case 'label': $(this).closest('.field_row').find('h3.field-heading span').html(this.value); break;
					case 'type': 
						
						if (['dropdown', 'radio', 'checkbox', 'number_slider', 'autocomplete', 'radio_image'].indexOf(this.value) > -1)
						{
							$(this).closest('.values-fields').find('.dropdown-relation-hidden').show();
							var ops = $(this).closest('.values-fields').find('textarea.kc-mapper-inp[name="options"]');
							if (this.value != ops.data('std-type')) 
							{
								var ops_val = "value1: Label for value 1\nvalue2: Label for value 2"
								
								if (this.value == 'number_slider')
									ops_val = "min:0\nmax:10\nunit:px\nratio:1";
								else if (this.value == 'autocomplete')
									ops_val = "post_type:\ncategory:\ncategory_name:\ntaxonomy:"; 
								else if (this.value == 'radio_image')
									ops_val = "value1:"+kc_url+"/assets/frontend/images/pricing/layout-1.png\nvalue2:"+kc_url+"/assets/frontend/images/pricing/layout-2.png\nvalue3:"+kc_url+"/assets/frontend/images/pricing/layout-3.png"; 
									
								ops.val(ops_val);
								
							}else ops.val(ops.data('std-ops'));
							
						}else{
							$(this)
							.closest('.values-fields')
							.find('.dropdown-relation-hidden')
							.hide();
						}
							
						if (this.value == 'group') {
							
							var inp = $(this).closest('.field-row-body').find('>.groupfields-relation-hidden input[name="params"]'),
								grd = $(this)
									.closest('.field-row-body')
									.find('>.groupfields-relation-hidden');
							
							grd.show();
							
							if (inp.val() === '') {
								inp.val('[{"name":"child1","label":"Child 1","value":"","type":"text"},{"name":"child2","label":"Child 2","value":"","type":"text"}]');
								
								kc_mapper.field.render(
									grd.find('.kc-group-fields-render'),
									JSON.parse(kc.tools.base64.decode(inp.val()))
								);
								
							}
						}else{
							$(this)
							.closest('.field-row-body')
							.find('>.groupfields-relation-hidden')
							.hide();
						}
						
					break;
					case 'relation-op': 
						
						var rela = $(this).closest('.values-fields').find('textarea[name="relation"]');
						
						if (this.checked) {
							if (rela.data('std-vl') !== '')
								rela.show().val(rela.data('std-vl'));
							else rela.show().val("parent:{field-parent-name}\nshow_when:{field-parent-value}");
						} else rela.hide();
						
					break;
				
				}
				kc_mapper.changed = true;
				kc_mapper.field.preview();
				
			},
			
			delete : function (el) {
				if (confirm('Are you sure that you want to delete this param?')) {
					$(el).closest('.field_row').remove();
					kc_mapper.field.preview();
				}
			},
			
			preview : function() {

				var params = this.get_params($('#kc-mapper-fields>.field_row_param')),
					form = $('<form class="fields-edit-form kc-pop-tab form-active"></form>'),
					wrp = $('#kc-mapper-fields-preview');
				
				if (params === false)
					return;
				
				wrp.html('').append(form);
				
				kc.params.fields.render(form, params , {});
				kc_mapper.shortcode_string();

			},
			
			get_params : function(selector) {
				
				var params = [], param, options, get_ops, vals, names = [],
					parse_option = function(value){
						
						value = value.toString().trim().split("\n");
						var options = {};
						
						for (var i = 0; i< value.length; i++) 
						{
							if (value[i].indexOf(':') > -1) 
							{
								vals = [];
								vals[0] = kc.tools.esc_slug(value[i].substr(0, value[i].indexOf(':')).trim());
								vals[1] = value[i].substr(value[i].indexOf(':')+1).trim();
								
								options[vals[0]] = vals[1];
							
							}else if (value[i].trim() !== ''){
								options[kc.tools.esc_slug(value[i].trim())] = value[i].trim();
							}
						}
						
						return options;
						
					};
				
				selector.each(function() {
					
					var $this = $(this).find('.field-row-body>.values-fields');
					
					param = {
						name: kc.tools.esc_slug($this.find('input[name="name"]').val()),
						label: kc.tools.esc($this.find('input[name="label"]').val()),
						value: kc.tools.esc($this.find('input[name="value"]').val()),
						type: kc.tools.esc($this.find('select[name="type"]').val()),
						admin_label: $this.find('input[name="admin_label"]').get(0).checked,
						description: kc.tools.esc($this.find('textarea[name="description"]').val())
					};
					
					$('#kc-mapper-screen-edit .kc-notice').remove();
					
					if (names.indexOf(param.name) === -1) {
						names.push(param.name);
					}else 
					{
						$('.field-row-body').hide();
						$(this).find('.field-row-body').show();
						$this.find('input[name="name"]').focus();
						$('#kc-mapper-shortcode-info')
							.after('<div class="kc-notice kc-msg-error"><p>Error: The param name should not be duplicated</p></div>');
						
						params = false;
						return false;
					
					}
					
					if ((param.name == 'content' && $(this).closest('.content_include').length === 0) 
						|| ['_id', 'css', '_css_data', 'custom_css', '_css', '_content'].indexOf(param.name) > -1) 
					{
						
						$('.field-row-body').hide();
						$(this).find('.field-row-body').show();
						$this.find('input[name="name"]').focus();
						$('#kc-mapper-shortcode-info')
							.after('<div class="kc-notice kc-msg-error"><p>Error: The param name is not allowed to use "'+param.name+'"</p></div>');
						
						params = false;
						return false;
						
					}
					
					if (['dropdown', 'radio', 'checkbox', 'number_slider', 'autocomplete', 'radio_image'].indexOf(param.type) > -1) 
					{
						param.options = parse_option($this.find('textarea[name="options"]').val());
					}
					
					if (param.type == 'group') {
						try {
							
							var get_vl = kc_mapper.field
											.get_params($(this).find('.field-row-body>.groupfields-relation-hidden .field_row_param'));

							$this.find('input[name="params"]').val(JSON.stringify(get_vl));
										
							param.params = get_vl;
							
						}catch(ex){console.error('KingComposer Console: '+ex.message);}
					}
					
					if ($this.find('input[name="relation-op"]').attr('checked')) 
					{
						var rel_op = $this.find('textarea[name="relation"]').val();
						if (rel_op.indexOf('{') === -1 && rel_op.indexOf('}') === -1)
						{
							rel_op = parse_option(rel_op);
							if (rel_op.parent !== undefined && (rel_op.show_when !== undefined || rel_op.hide_when !== undefined))
								param.relation = rel_op;
								
						}
					}
					
					params.push(param);
					
				});
				
				return params;
				
			},
			
		},
		
		shortcode_string : function(){
			
			var params = kc_mapper.field.get_params($('#kc-mapper-fields>.field_row_param')),
				tag = kc.tools.esc_slug($('#kc-mapper-shortcode-info input[name="tag"]').val()),
				is_container = $('#kc-mapper-shortcode-info input[name="is_container"]').get(0).checked,
				string = '['+tag;
				
			for	(var n in params) {
				if (params[n].name != 'content')
					string += ' '+kc.tools.esc_slug(params[n].name)+'=""';
			}
			
			if (is_container)
				string += '] {content} [/'+tag+']';
			else string += ']';
			
			$('#kc-mapper-shortcode-info .kc-mp-sc-shortcode-string').html(string);
			
		},
		
		build_list : function() {
			
			var curent_cate = $('#kc-mapper-list ul.kc-mapper-categories li.active').data('slug');
			$('#kc-mapper-list .item:not(.add-new,.import-export), #kc-mapper-list .kc-mapper-categories').remove();
			var item, cats = [];
			
			for (var n in kc_mapper_shortcodes) 
			{
				item = $('<div data-tag="'+n+'" data-category="'+kc.tools.esc_slug(kc_mapper_shortcodes[n].category)+'" class="item"><i class="'+kc_mapper_shortcodes[n].icon+'"></i><br />'+kc_mapper_shortcodes[n].name+'</div>');
				
				$('#kc-mapper-list .item.add-new').before(item);
				if (kc_mapper_shortcodes[n].category !== undefined 
					&& kc_mapper_shortcodes[n].category.trim() !== ''
					&& cats.indexOf(kc_mapper_shortcodes[n].category) === -1)
					cats.push(kc_mapper_shortcodes[n].category);
			}
			
			$('#kc-mapper-list .item:not(.add-new,.import-export)').on('click', kc_mapper.edit);
			
			if (cats.length > 0) {
				var nav = '<ul class="kc-mapper-categories"><li data-slug="" class="active">All</li>';	
				for (var n = 0; n < cats.length; n++) {
					nav += '<li data-slug="'+kc.tools.esc_slug(cats[n])+'">'+cats[n]+'</li>';
				}
				nav += '<ul>';
				$('#kc-mapper-list').prepend(nav);
				
				$('#kc-mapper-list ul.kc-mapper-categories li').on('click', function(){
					$(this).parent().find('.active').removeClass('active');
					$(this).addClass('active');
					
					$('#kc-mapper-list .item[data-category]').hide();
					var slug = $(this).data('slug');
					if (slug === '')
						$('#kc-mapper-list .item[data-category]').show();
					else $('#kc-mapper-list .item[data-category="'+slug+'"]').show();
				});
				
				if (curent_cate) 
					$('#kc-mapper-list ul.kc-mapper-categories li[data-slug="'+curent_cate+'"]').trigger('click');
				
			}
		},
		
		import_export : function() {
			$('#kc-mapper-screen-import-export .kc-notice').remove();
			kc_mapper.screen('open', 'import-export');
			$('#kc-mapper-screen-import-export textarea[name="export"]').val(JSON.stringify(kc_mapper_shortcodes));
		},
		
		screen : function (stt, scr) {
			
			if (stt == 'open') 
			{
				$('#kc-mapper-settings .kc-mapper-screen').hide();
				$('#kc-mapper-overlay,#kc-mapper-screen-'+scr).show();
			} else {
				
				if (kc_mapper.changed === true && !confirm("Changes you made may not be saved. \n\nAre you sure want to cancel?\n"))
					return;
					
				kc_mapper.changed = false;
				$('#kc-mapper-overlay, #kc-mapper-settings .kc-mapper-screen').hide();
				
			}
		}
		
	}
	
	$(document).ready(kc_mapper.init);
	
	if ($.fn.shake === undefined) {
		$.fn.shake = function(){
			return this.focus()
				.animate({marginLeft: -30}, 100)
				.animate({marginLeft: 20}, 100)
				.animate({marginLeft: -10}, 100)
				.animate({marginLeft: 5}, 100)
				.animate({marginLeft: 0}, 100);
		}
	}
	
})(jQuery)
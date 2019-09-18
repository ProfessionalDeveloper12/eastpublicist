/*
 * KingComposer Page Builder
 *
 * (c) Copyright king-theme.com
 *
 *
 * kc.builder.js
 *
*/

( function($){
	 
	if( typeof( kc ) == 'undefined' )
		window.kc = {};
	 
	$().extend( kc.views, {
			
		builder : new kc.backbone.views('no-model').extend({
			
			render : function(){
				
				var el = $( kc.template('container'));
				
				$('#kc-container').remove();
				
				if ($('#postdivrich').length > 0) {
					$('#postdivrich').hide().removeClass('first-load').after( el );
				} else if ($('div.gutenberg').length > 0) {
					$('div.gutenberg').hide().removeClass('first-load').after( el );
					el.show();
				} else if (wp.blocks !== undefined && $('#editor').length > -1) {
					$('#kc-controls').remove();
					$('#editor .editor-block-list__layout').after( el );
					setTimeout(function() {
						$('.edit-post-header').prepend($('#kc-controls'));
					}, 100);
				}
				
				this.el = el;
				
				return el;
							
			},
			
			events : {
				'.classic-mode:click' : kc.switch,
				'.basic-add:click' : kc.backbone.add,
				'.kc-add-sections:click' : 'sections',
				'.kc-online-presets:click' : 'online_sections',
				'.post-settings:click' : 'post_settings',
				'.optimized-page:click' : 'optimized',
				'.quick-save:click' : 'quick_save',
				'.save-page-content:click' : 'save_page',
				'.collapse:click' : 'collapse',
				'#kc-footers li.quickadd:click' : 'footer'
			},
			
			sections : function(e, opt){
				
				var atts = { 
						title: kc.__.i61, 
						width: 1024,
						float: true,
						class: 'no-footer bg-blur-style section-manager-popup',
					},
					pop = kc.tools.popup.render( e.target, atts );
				
				if( typeof opt != 'object' )
					opt = {};
				
				/*
				*	save content from row to section
				*/
				if( opt.save_row !== undefined ){
					pop.data({ model: opt.save_row });
					pop.addClass('kc-save-row-to-section');
				}
				
				/*
				*	select another section
				*/
				if( opt.current !== undefined && opt.model !== undefined ){
					pop.data({ model: opt.model, 'current_item': opt.current });
				}
				
				if (kc.sections === undefined || kc.sections.items.length === 0){
					
					pop.find('.m-p-body').append('<p class="align-center" style="height:200px;"></p>');
					
					kc.ui.sections.reload( pop );
					
				}else kc.ui.sections.render( pop );
				
				return pop;

			},
						
			online_sections : function(e){
				
				if ($('#kc-presets-library-pop').length > 0) {
					$('#kc-presets-library-pop').show();
					return;
				};
				
				var pop = $('<div id="kc-presets-library-pop" \
									class="kc-params-popup wp-pointer-top kc-popup-float" \
										style="width:90vw;height:95vh !important">\
								<div class="m-p-wrap wp-pointer-content">\
									<h3 class="m-p-header">\
										<span data-st="label">Online Presets & Templates Library</span>\
										<i data-prevent-drag="true" title="Hide popup" class="sl-close sl-func"></i>\
									</h3>\
									<div class="m-p-body" style="height:95vh;max-height:calc(95vh - 55px);padding: 0px;">\
										<iframe src="https://presets.kingcomposer.com/presets/pricing" \
											style="width:100%;height:100%;"></iframe>\
										<div class="kc-popup-loading" style="display:block"><span class="kc-loader"></span></div>\
									</div>\
								</div>\
							</div>');
				
				$('body').append(pop);
				
				pop.find('h3.m-p-header i.sl-close').on('click', function(e) {
					pop.hide();
					e.preventDefault();
				});
				
				pop.on('click', function(e) {
					if (e.target.id == 'kc-presets-library-pop') {
						pop.hide();
						e.preventDefault();
					}
				});
				
				pop.find('iframe').on('load', function(){
					$(this).parent().find('.kc-popup-loading').remove();
					if (kc_pdk[0] != 'developer-package' && kc_pdk[1] < new Date().getTime()/1000)
						this.contentWindow.postMessage({pdk: 0}, '*');
					else this.contentWindow.postMessage({pdk: '1'}, '*');
				});
				
				return;
				
				var atts = { 
						title: kc.__.i69, 
						width: 1200,
						float: true,
						class: 'no-footer full-screen',
					},
					pop = kc.tools.popup.render( e.target, atts );
				
				if( typeof opt != 'object' )
					opt = {};
				
				if (kc.cfg.preset_link === undefined)
					kc.cfg.preset_link = 'https://kingcomposer.com/presets/pricing/?kc_action=presets';

                kc.cfg.preset_link = kc.cfg.preset_link.replace( "http://features.kingcomposer.com/", "https://kingcomposer.com/presets/");

                var src = kc.cfg.preset_link+'&ver=' + kc_version + '&client_site='+kc.tools.base64.encode(kc_ajax_url);
				
				if (window.location.href.indexOf('https') === 0)
					src = 'https://kingcomposer.com/redirect.php?url='+encodeURIComponent(src);
				
				var ifr = $('<iframe src="'+src+'" style="width:100%;height:100%;"></iframe>'),
					loading = '<div class="kc-popup-loading" style="display:block"><span class="kc-loader"></span></div>';
				
				pop.find('.m-p-body').append(ifr).append(loading);
				
				ifr.on('load', function(){
					$(this).parent().find('.kc-popup-loading').remove();
				});
				
				return pop;

			},
			
			post_settings : function( e ){
				
				var atts = { title: 'Content Settings', width: 800, class: 'no-footer bg-blur-style' },
					pop = kc.tools.popup.render( e.target, atts );
					
				var arg = kc.tools.reIndexForm($("input[name^='kc_post_meta']").serializeArray(), []).kc_post_meta;
					
				var sections = $( kc.template( 'post-settings', arg ) );

				pop.find('.m-p-body').append( sections );
				
				if( typeof arg.callback == 'function' )
					arg.callback( sections, $ );
				
				return false;
					
			},
			
			optimized : function( e ){
				
				var atts = { title: 'Optimize Settings', width: 800, class: 'no-footer bg-blur-style' },
					pop = kc.tools.popup.render( e.target, atts );
					
				var arg = kc.tools.reIndexForm($("input[name^='kc_post_meta']").serializeArray(), []).kc_post_meta;
					
				var sections = $( kc.template( 'optimized-settings', arg ) );

				pop.find('.m-p-body').append( sections );
				
				if( typeof arg.callback == 'function' )
					arg.callback( sections, $ );
				
				return false;
					
			},
			
			collapse : function(e){
				
				var ctn = $('#kc-container');
				if (!ctn.hasClass('collapse'))
				{
					ctn.addClass('collapse');
					$('#kc-page-cfg-collapsed').val('collapse');
				}
				else
				{
					ctn.removeClass('collapse');
					$('#kc-page-cfg-collapsed').val('');
				}
				
				e.preventDefault();
				return false;
					
			},
			
			footer : function(){
				
				var content = $(this).data('content');
				
				if( content == 'custom' ){
					
					var atts = { 
						title: kc.__.i36, 
						width: 750, 
						float: true,
						class: 'push-custom-content',
						save_text: 'Push to builder'
					},
					pop = kc.tools.popup.render( this, atts );
					
					var copied = kc.backbone.stack.get('KC_RowClipboard');
					if( copied === undefined || copied == '' )
						copied = '';
					pop.find('.m-p-body').html( kc.__.i37+'<p></p><textarea style="width: 100%;height: 300px;">'+copied+'</textarea>');
					
					pop.data({
						callback : function( pop ){
							
							var content = pop.find('textarea').val();
							if( content !== '' )
								kc.backbone.push( content );
						}
					});
					
					return;
					
				}else if( content == 'paste' ){
					content = kc.backbone.stack.get('KC_RowClipboard');
					if( content === undefined || content == '' ){
						content = '[kc_column_text]<p>'+kc.__.i38+'</p>[/kc_column_text]';
					}
				}
				
				if( content != '' )
					kc.backbone.push( content );
				
			},
			
			quick_save: function(e) {
				kc.instant_submit();
				e.preventDefault();	
			},
			
			save_page : function(e){
				
				kc.views.builder.sections( e, {save_row: 'all'} );
				
				return false;
				
			}
			
		} ),

		views_sections : new kc.backbone.views().extend({
			
			render : function( params ){
				
				var el = new $( kc.template( 'views-sections', params ) );
				kc.params.process_all( params.args.content, el.find('> .kc-views-sections-wrap'), 'views_section' );
				
				this.el = el;
				
				return el;
				
			},
			
			events : {
				'>.kc-views-sections-control .edit:click' : 'settings',
				'>.kc-views-sections-control .delete:click' : 'remove',
				'>.kc-views-sections-control .double:click' : 'double',
				'>.kc-views-sections-wrap .add-section:click' : 'add_section',
				'>.kc-views-sections-control .more:click' : 'more',
				'>.kc-views-sections-control .copy:click' : 'copy',
				'>.kc-views-sections-control .cut:click' : 'cut',
				'>.kc-views-sections-control:click' : function( e ){
					var tar = $(e.target);
					if( tar.hasClass('more') || tar.parent().hasClass('more') )
						return;
					$(this).find('.active').removeClass('active');
				},
			},
			
			add_section : function(e) {

				e.data.do_add_section(this);
				
			},
			
			do_add_section : function(el) {

				var wrp = $(el).closest('.kc-views-sections-wrap'),
					maps = kc.get.maps(el),
					smaps = kc.maps[maps.views.sections],
					content = '['+maps.views.sections+' title="New '+smaps.name+'"][/'+maps.views.sections+']';
				
				wrp.find('> .kc-views-sections-label .sl-active').removeClass('sl-active');
				wrp.find('> .kc-section-active').removeClass('kc-section-active');
					
				kc.params.process_all( content, wrp, 'views_section' );
				
			}
			
		} ),
		
		views_section : new kc.backbone.views().extend({
			
			render : function( params ){

				var el = $( kc.template( 'views-section', params ) );
				
				this.el = el;
				
				return el;
				
			},
			
			init : function( params, el ){
				
				var id = el.data('model'), 
					btn = params.parent_wrp.find('>.kc-views-sections-label .add-section'), 
					title = kc.tools.esc( params.args.title ),
					icon = '';
				if( params.args.icon != undefined )
					icon = '<i class="'+params.args.icon+'"></i> ';
					
				kc.ui.sortInit();
				
				var label = '<div class="section-label';
				if( params.first == true )
					label += ' sl-active';
				label += '" id="kc-pmodel-'+id+'" data-pmodel="'+id+'">'+icon+title+'</div>';
				
				btn.before( label );
				
				return btn;
	
			},
			
			events : {
				'>.kc-vs-control .settings:click' : 'settings',
				'>.kc-vs-control .double:click' : 'double',
				'>.kc-vs-control .add:click' : 'add',
				'>.kc-vs-control .delete:click' : 'remove',
				
			},
			
			settings : function(){
				
				var pop = kc.backbone.settings( this );
				if( !pop ){
					alert( kc.__.i39 );
					return;
				}
				pop.data({
					after_callback : function( pop ){
						
						var id = kc.get.model( pop.data('button') ),
							storage = kc.storage[ id ],
							el = $('#model-'+id),
							icon = '';
						if( storage.args.icon != undefined )
							icon = '<i class="'+storage.args.icon+'"></i> ';
							
						$('#kc-pmodel-'+id).html( icon+kc.tools.esc( storage.args.title ) );
						el.find('.kc-vertical-label').html( icon+kc.tools.esc( storage.args.title ) );
					}
				});
			},
			
			double : function(el){
				
				var id = kc.get.model(this),
					exp = kc.backbone.export( id ),
					wrp = $(this).closest('.kc-views-sections-wrap');
				
				wrp.find('> .kc-views-sections-label .sl-active').removeClass('sl-active');
				wrp.find('> .kc-section-active').removeClass('kc-section-active');
					
				kc.params.process_all( exp.begin+exp.content+exp.end, wrp, 'views_section' );
				
			},
			
			remove : function(){
				
				var id = kc.get.model( this ),
					lwrp = $('#kc-pmodel-'+id).parent();
					
				if( confirm('Are you sure?') ){	
					$('#kc-pmodel-'+id).remove();
					lwrp.find('.section-label').first().trigger('click');
					delete kc.storage[ id ];
					$('#model-'+id).remove();
				}
			}
	
			
		} ),
		
		row : new kc.backbone.views().extend({
			
			render : function( params, _return ){
				
				params.name = 'kc_row';
				params.end = '[/kc_row]';
				
				var el = $( kc.template( 'row', params.args ) ), 
					atts = ' width="12/12"';
				
				if( params.args !== undefined && params.args.__section_link !== undefined ){
					
					// do stuff
					
				}else{
					
					var content = (params.args.content !== undefined) ? params.args.content.toString().trim() : '';
					
					if( content.indexOf('[kc_column') !== 0 ){
						
						content = content.replace(/kc_column#/g,'kc_column##');
						content = content.replace(/kc_column /g,'kc_column# ').replace(/kc_column\]/g,'kc_column#]');
						
						var params = kc.params.merge( 'kc_column' );
						
						for( var i in params ){
							if( typeof( params[i] ) != 'undefined' && typeof( params[i].value ) != 'undefined' )
								atts += ' '+params[i].name
									 +'="'+kc.tools.esc_attr( params[i].value )+'"';
						}
						
						content = '[kc_column'+atts+']'+content+'[/kc_column]';
						
						delete params;
						
					}
					
					kc.params.process_columns( content, el.find('.kc-row-wrap') );
					
				}
				if( _.isUndefined(_return) )
					$('#kc-container>#kc-rows').append( el );
				
				this.el = el;
				
				return el;
				
			},
			
			events : {
				'.row-container-control .close:click' : 'remove',
				'.row-container-control .settings:click' : 'edit',
				'.row-container-control .double:click' : 'double',
				'.row-container-control .copy:click' : 'copy',
				'.row-container-control .columns:click' : 'columns',
				'.row-container-control .collapse:click' : 'collapse',
				'.row-container-control .addToSections:click' : 'sections',
				'.row-container-control .rowStatus:click' : 'status',
				'.row-container-control .order-row input:mouseover' : 'get_order',
				'.row-container-control .order-row button:click' : 'order',
				'.row-container-control .order-row input:keydown' : 'order_enter',
				
				'.kc-row-section-preview .select-another-section:click' : 'select_section',
				
			},
			
			columns : function(){
				
				var columns = $(this).closest('.kc-row').find('>.kc-row-wrap>.kc-column.kc-model');

				var pop = kc.tools.popup.render( 
							this, 
							{ 
								title: 'Row Layout', 
								class: 'no-footer',
								width: 341,
								content: kc.template( 'row-columns', {current:columns.length} ),
								help: 'http://kingcomposer.com/documentation/resize-sortable-columns/?source=client_installed' 
							}
						);
						
				pop.find('.button').on( 'click', 
					{ 
						model: kc.get.model( this ),
						columns: columns,
						pop: pop
					}, 
					kc.views.row.set_columns 
				);
				
				pop.find('input[type=checkbox]').on('change',function(){
					
					var name = $(this).data('name');
					if( name == undefined )
						return;
						
					if( this.checked == true )
						kc.cfg[ name ] = 'checked';
					else kc.cfg[ name ] = '';
					
					kc.backbone.stack.set( 'KC_Configs', kc.cfg );
						
				});	
						
			},
			
			set_columns : function(e){
				
				var newcols = $(this).data('column'),
					columns = e.data.columns,
					wrow = $( '#model-'+e.data.model+' > .kc-row-wrap' ),
					colWidths = [], i = 0;
					
				if( newcols == 'custom' ){
					
					newcols = $(this).parent().find('input').val();
					if( newcols === '' || ( newcols.indexOf('%') === -1 && newcols.indexOf('/') === -1 ) ){
						alert('Invalid value');
						return;
					}
					
					newcols = newcols.split('+');
					if( newcols.length > 10 ){
						alert('Maximum 10 columns, you entered '+newcols.length+' columns');
						return;
					}
					var totalcols = 0;
					for( i=0; i<newcols.length; i++ ){
						
						colWidths[i] = newcols[i].trim();
						
						if( colWidths[i].indexOf('/') > -1 ){
							colWidths[i] = colWidths[i].split('/');
							colWidths[i] = kc.tools.nfloat( (parseFloat( colWidths[i][0] )/parseFloat( colWidths[i][1] ))*100 );
						}else if( colWidths[i].indexOf('%') > -1 ){
							colWidths[i] = parseFloat( colWidths[i] );
						}
						
						totalcols += parseFloat( colWidths[i] );
						
					}
					
					if( totalcols > 100 || totalcols < 99 ){
						alert("\nTotal is incorrect: "+totalcols+"%, it must be 100%\n");
						return;
					}
					
					newcols = newcols.length;
					
				}else{
					
					newcols = parseInt( newcols );
					
					for( i=0; i<newcols; i++ ){
						colWidths[i] = kc.tools.nfloat( 100/newcols );
					}
					
				}
				
				if( columns.length < newcols ){
					
					/* Add new columns */
					var reInit = false;
					
					
					for( var i = 0; i < (newcols-columns.length) ; i++ ){
						
						var dobl = kc.backbone.double( columns.last().get(0) );
						
						if( $('#m-r-c-double-content').attr('checked') == undefined || columns.length === 0 ){
							
							dobl.find('.kc-model').each(function(){
								delete kc.storage[$(this).data('model')];
								$(this).remove();
							});
							
						}
						
					}
					
					if( reInit == true )
						kc.ui.sortInit();
						
				}else
				{
					/* Remove columns */
					var remove = [];
					
					for( var i = 0; i < (columns.length-newcols) ; i++ ){
					
						var found_empty = false;
					
						wrow.find('>.kc-column.kc-model,>.kc-column-inner.kc-model').each(function(){
							if( $(this).find('>.kc-column-wrap>.kc-model').length == 0 ){
								found_empty = this;
							}
						});
					
						if( found_empty != false ){
					
							$(found_empty).remove();
					
						}else{
					
							var last = wrow.find('>.kc-column.kc-model,>.kc-column-inner.kc-model').last(), 
								plast = last.prev();
								
							if( $('#m-r-c-keep-content').attr('checked') != undefined && plast.get(0) != undefined ){
								last.find('>.kc-column-wrap>.kc-model').each(function(){
									plast.find('>.kc-column-wrap').append( this );
								});
							}else{
								last.find('>.kc-column-wrap>.kc-model').each(function(){
									delete kc.storage[$(this).data('model')];
								});
							}
							
							
							last.remove();
							
						}		
					}
					
				}
				
				i = 0;
				columns.eq(0).parent().find('>.kc-model').each(function(){
					kc.storage[$(this).data('model')].args.width = colWidths[i]+'%';
					$(this).css({width: colWidths[i]+'%'}).find('>.kc-cols-info').html(Math.round(colWidths[i])+'%');
					i++;
				});
				
				e.data.pop.remove();
				
			},
			
			collapse : function(){
				
				var elm = $(this).closest('.kc-row'), model = kc.get.model(this);
				
				if( !elm.hasClass('collapse') ){
					elm.addClass('collapse');
					kc.storage[model].args._collapse = '1';
				}else{
					elm.removeClass('collapse');
					delete kc.storage[model].args._collapse;
				}	
				
			},
			
			sections : function( e ){
				
				var model = kc.get.model(e.target);
				kc.cfg.sectionsType = 'kc-section';
				kc.sections = {'items': []};
				kc.views.builder.sections( e, {save_row: model} );
				
			},
			
			copy : function( e ){
					
				if( $(this).hasClass('copied') )
					return;
									
				var model = kc.get.model( this ),
					expo = kc.backbone.export( model );
					
				kc.backbone.stack.set( 'KC_RowClipboard', expo.begin+expo.content+expo.end );
				
				kc.tools.toClipboard( expo.begin+expo.content+expo.end );
				
				$(this).addClass('copied');
				
				setTimeout( function( el ){
					$(el).removeClass('copied');
				}, 600, this );
				
				return;
	
			},
			
			edit : function( e ){
				
				var pop = kc.backbone.settings( this );
				if( !pop ){
					alert( kc.__.i41 );
					return;
				}
				
				pop.data({ after_callback : function( pop ){
					
					var id = kc.get.model( pop.data('button') ),
						params = kc.storage[ id ].args,
						html = '',
						el = $('#model-'+id+'>.kc-row-admin-view');

					if( params.row_id != undefined && params.row_id != '__empty__' )
						html += '#'+params.row_id+' ';
					
					el.html( html );
					
				}});
				
			},
			
			status : function( e ){
					
				var model = kc.get.model( this ), stt = '';
				if( kc.storage[ model ] !== undefined && kc.storage[ model ].args !== undefined ){
					
					if( $(this).hasClass('disabled') ){
						
						$(this).removeClass('disabled').closest('.kc-model').removeClass('collapse');
						delete kc.storage[ model ].args.disabled;
						
					}else{
						
						$(this).addClass('disabled').closest('.kc-model').addClass('collapse');
						kc.storage[ model ].args.disabled = 'on';
						
					}
					
					kc.confirm( true );
					
				}
				
			},
			
			get_order : function( e ){
				$(this).val( $('#kc-rows>.kc-row').index( $(this).closest('.kc-row') )+1 );
			},
			
			order : function( e ){
				
				var row = $(this).closest('.kc-row'), 
					rows = $('#kc-rows>.kc-row'), 
					index = $(this).parent().find('input').val();
					
				if( index === '' || index < 0 || index > rows.length ){
					
					$(this).prev().
						animate({marginLeft:-20}, 150).
						animate({marginLeft:15}, 150).
						animate({marginLeft:-10}, 150).
						animate({marginLeft:5}, 150).
						animate({marginLeft:0}, 150);
				
				}else if( index == 0 || index == 1 ){
					
					rows.first().before( row );
					kc.ui.scrollAssistive( row.get(0), true );
					$(this).parent().find('input').val('');
				
				}else{
					
					if( rows.index(row) < index-1 )
						rows.eq(index-1).after( row );
					else rows.eq(index-1).before( row );
					
					kc.ui.scrollAssistive( row.get(0), true );
					$(this).parent().find('input').val('');
				
				}
				
				e.preventDefault();
				return false;
				
			},
			
			order_enter : function( e ){
				
				if( e.keyCode == 13 ){
					$(this).next().trigger('click');
					e.preventDefault();
					return false;
				}
			},
			
			select_section : function( e ){
				
				var model = kc.get.model(e.target),
					current = $(this).data('current');
				
				kc.views.builder.sections( e, { current: current, model: model } );
				
				e.preventDefault();
				return false;
				
			}
			
		} ),
				
		column : new kc.backbone.views().extend({
			
			render : function( params ){
				
				if( typeof params.name == 'undefined' || ['kc_column', 'kc_column_inner'].indexOf(params.name) === -1){
					params.name = 'kc_column'; 
					params.end = '[/kc_column]';
				}
				
				var _w = params.args['width'], el, tmp_name = 'column';
				if( _w != undefined ){
					if( _w.toString().indexOf('/') > -1 ){
						_w = _w.split('/');
						_w = parseFloat((_w[0]/_w[1])*100).toFixed(4)+'%';
					}else if( _w.toString().indexOf('%') === -1 )
						_w += '%';
				}else{
					_w = '100%';
				}

				if( params.name.indexOf('kc_column_inner') > -1)
					tmp_name = 'column-inner';
				else
					tmp_name = 'column';
					
				el = $( kc.template( tmp_name, { width: _w } ) );
				kc.params.process_all( params.args.content, el.find('.kc-column-wrap') );
				
				this.el = el;
				
				return el;
				
			},
			
			events : {
				'>.kc-column-control .edit:click' : 'settings',
				'>.kc-column-control .add:click' : 'add',
				'>.kc-column-control .delete:click' : 'delete',
				'>.kc-column-control .double:click' : 'double',
				'>.kc-column-control .insert:click' : 'insert',
			},
			
			
			delete : function( e, id ){
				
				if( !confirm( kc.__.sure ) )
					return;
				
				if( id == undefined )
					var id = kc.get.model( this );
				
				var col = $( '#model-'+id ),
					pco = col.parent();
				
				col.find('.kc-model').each(function(){
					delete kc.storage[ kc.get.model(this) ];
				});
					
				col.remove();
				delete kc.storage[ id ];
				
				kc.views.column.reset_view(pco);
				
			},
			
			insert : function( id ){
				
				if (typeof id === 'object')
					id = kc.get.model(this);
					
				var el = $('#model-'+id),
					data = kc.storage[id],
					cdata = $().extend( true, {}, data );

				if( el.parent().find('>.kc-model').length >= 10 ){
					alert(kc.__.i54);
					return;
				}

				cdata.args.content = '';

				var cel = kc.views.column.render( cdata, true );
				el.after(cel);
				
				kc.views.column.reset_view(el.parent());
				
				kc.ui.sortInit();
					
			},
			
			reset_view : function( el ){
				
				var cols = el.find('>.kc-model'), 
					model, 
					wid = kc.tools.nfloat(100/cols.length);
				
				if(cols.length === 0){
					delete kc.storage[ el.closest('.kc-model').data('model') ];
					el.closest('.kc-model').remove();
					return;
				}
					
				cols.each(function(){
					model = $(this).data('model');
					kc.storage[model].args.width = wid+'%';
					$(this).css({width: wid+'%'});
					$(this).find('>.kc-cols-info').html(wid+'%');
				});	
				
			},
			
			apply_all : function( el, arg ){
				
				var pop = kc.get.popup(el), model = pop.data('model');
			    pop.find('.sl-check.sl-func').trigger('click');
			    
			    try{
				    var data = kc.storage[ model ].args[ arg ];
				    $('#model-'+model).parent().find('>div').each(function(){
				    	
				    	model = $(this).data('model');
				    	if( model !== undefined )
				    		kc.storage[ model ].args[ arg ] = data;
				    	
				    });
			    }catch( ex ){}
			    
			    event.preventDefault();
			    return false;
				
			}
			
		}),
		
		kc_row_inner : new kc.backbone.views().extend({
			
			render : function( params ){
				
				params.name = 'kc_row_inner'; params.end = '[/kc_row_inner]';
				
				var el = $( kc.template( 'row-inner' ) );
				
				var content = params.args.content;
				if( content !== undefined )
					content = content.toString().trim();
				else content = '';
				
				if( content.indexOf('[kc_column') !== 0 ){
					content = '[kc_column_inner width="12/12"]'+
							   content.replace(/kc_column_inner/g,'kc_column_inner#')+
							   '[/kc_column_inner]';
				}			   
					
				kc.params.process_all( content, el.find('.kc-row-wrap') );
				
				this.el = el;
				
				return el;
			
			},
			
			events : {
				'.kc-row-inner-control > .settings:click' : 'settings',
				'.kc-row-inner-control > .double:click' : 'double',
				'.kc-row-inner-control > .delete:click' : 'remove',
				'.kc-row-inner-control > .copyRowInner:click' : 'copy',
				'.kc-row-inner-control > .columns:click' : 'columns',
				'.kc-row-inner-control > .collapse:click' : 'collapse',
			},
			
			collapse : function(){
				var elm = $('#model-'+kc.get.model(this));
				if( !elm.hasClass('collapse') ){
					elm.addClass('collapse');
				}else{
					elm.removeClass('collapse');
				}	
			},
			
			columns : function(){
				
				var columns = $(this).closest('.kc-row-inner').find('>.kc-row-wrap>.kc-column-inner.kc-model');

				var pop = kc.tools.popup.render( 
							this, 
							{ 
								title: kc.__.i42, 
								class: 'no-footer',
								width: 341,
								content: kc.template( 'row-columns', {current:columns.length} ),
								help: 'http://kingcomposer.com/documentation/resize-sortable-columns/?source=client_installed' 
							}
						);
						
				pop.find('.button').on( 'click', 
					{ 
						model: kc.get.model( this ),
						columns: columns,
						pop: pop
					}, 
					kc.views.row.set_columns 
				);
				
				pop.find('input[type=checkbox]').on('change',function(){
					
					var name = $(this).data('name');
					if( name == undefined )
						return;
						
					if( this.checked == true )
						kc.cfg[ name ] = 'checked';
					else kc.cfg[ name ] = '';
					
					kc.backbone.stack.set( 'KC_Configs', kc.cfg );
						
				});	
						
			},
			
			copy : function(){
				
				if( $(this).hasClass('copied') )
					return;
					
				$(this).addClass('copied');
				setTimeout( function( el ){ el.removeClass('copied'); }, 1000, $(this) );
				
				kc.backbone.copy( this );
				
			}
			
		}),
		
		kc_column_inner : new kc.backbone.views().extend({
			
			render : function( params ){
				
				params.name = 'kc_column_inner'; params.end = '[/kc_column_inner]';
				
				var _w = params.args['width'];
				if( _w != undefined ){
					if( _w.toString().indexOf('/') > -1 ){
						_w = _w.split('/');
						_w = ((_w[0]/_w[1])*100)+'%';
					}else if( _w.toString().indexOf('%') === -1 )
						_w += '%';
				}else{
					_w = '100%';
				}
				
				var el = $( kc.template( 'column-inner', { width: _w } ) );
	
				if( params.args.content !== undefined && params.args.content != '' )
					kc.params.process_all( params.args.content, el.find('.kc-column-wrap') );
				
				this.el = el;
					
				return el;
			
			},
			
			events : {
				'>.kc-column-control .edit:click' : 'settings',
				'>.kc-column-control .add:click' : 'add',
				'>.kc-column-control .delete:click' : 'delete',
				'>.kc-column-control .double:click' : 'double',
				'>.kc-column-control .insert:click' : 'insert',
			},

			insert : function( e, id ){
				
				var id = kc.get.model(this),
					el = $('#model-'+id),
					data = kc.storage[id],
					cdata = $().extend( true, {}, data );
				
				if( el.parent().find('>.kc-model').length >= 10 ){
					alert(kc.__.i54);
					return;
				}
				
				cdata.args.content = '';
				
				var cel = kc.views.kc_column_inner.render( cdata, true );
				el.after(cel);
				
				kc.views.column.reset_view(el.parent());
				
				kc.ui.sortInit();
					
			},
						
			delete : function( e  ){
				
				kc.views.column.delete( e, kc.get.model( this ) );

			},
			
		}),
					
		kc_element : new kc.backbone.views().extend({
			
			render : function( params ){
				
				var map = $().extend( {}, kc.maps._std );
				map = $().extend( map, kc.maps[ params.name ] );
				
				var el = $( kc.template( 'element', { map : map, params : params } ) );
				
				setTimeout( function( params, map, el ){
					el.append( kc.params.admin_label.render({map: map, params: params, el: el }));
				}, parseInt(Math.random()*100)+100, params, map, el );
				
				if (map.nested === true)
					kc.params.process_all( params.args.content, el.find('.kc-column-wrap') );
				
				this.el = el;
				
				return el;
				
			},
			
			events : {
				'>.kc-element-control .edit:click' : 'edit',
				'>.kc-element-control .delete:click' : 'remove',
				'>.kc-element-control .double:click' : 'double',
				'>.kc-element-control .add:click' : 'add',
				'>.kc-element-control .more:click' : 'more',
				'>.kc-element-control .copy:click' : 'copy',
				'>.kc-element-control .cut:click' : 'cut',
				'>.kc-element-control:click' : function( e ){
					var tar = $(e.target);
					if( tar.hasClass('more') || tar.parent().hasClass('more') )
						return;
					$(this).find('.active').removeClass('active');
				},
			},
			
			edit : function( e ){
				
				var pop = kc.backbone.settings( this );
				if( !pop ){
					alert( kc.__.i43 );
					return;
				}	
				
				$(this).closest('.kc-element').addClass('editting');
				pop.data({cancel: function(pop){
					
					$( pop.data('button') ).closest('.kc-element').removeClass('editting');
					
				},after_callback : function( pop ){
					
					var id = kc.get.model( pop.data('button') ),
						params = kc.storage[ id ], 
						map = $().extend( {}, kc.maps._std ),
						el = $('#model-'+id);
					
					map = $().extend( map, kc.maps[ params.name ] );
					el.find('>.admin-view').remove();
					el.append( kc.params.admin_label.render({map: map, params: params, el: el }));
	
				}});
				
			}
			
		}),
							
		kc_undefined : new kc.backbone.views().extend({
			
			render : function( params ){
				
				var map = $().extend( {}, kc.maps._std );
				map = $().extend( map, kc.maps[ params.name ] );
				
				var el = $( kc.template( 'undefined', { map : map, params : params } ) );
				
				this.el = el;
				
				return el;
				
			},
			
			events : {
				'>.kc-element-control .edit:click' : 'edit',
				'>.kc-element-control .delete:click' : 'remove',
				'>.kc-element-control .double:click' : 'double'
			},
			
			edit : function( e ){

				var pop = kc.backbone.settings( this );
				if( !pop ){
					alert( kc.__.i45 );
					return;
				}	
				
				$(this).closest('.kc-element').addClass('editting');
				pop.data({cancel: function(pop){
					
					$( pop.data('button') ).closest('.kc-element').removeClass('editting');
					
				},after_callback : function( pop ){
					
					$( pop.data('button') ).closest('.kc-element').removeClass('editting');
					
					var id = kc.get.model( pop.data('button') ),
						params = kc.storage[ id ], 
						map = $().extend( {}, kc.maps._std ),
						el = $('#model-'+id);
					
					map = $().extend( map, kc.maps[ params.name ] );
					el.find('>.admin-view').remove();
					el.append( kc.params.admin_label.render({map: map, params: params, el: el }));
	
				}});
				
			},
			
			remove : function( e ){
				if( confirm( kc.__.sure ) ){
					var elm = $(this).closest('div.kc-element');
					var mid = elm.data('model');
					elm.remove();
					delete kc.storage[mid];	
				}
			}
			
		}),

	} );
	
} )( jQuery );

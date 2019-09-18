/*
 * KingComposer Page Builder
 *
 * (c) Copyright king-theme.com
 *
 *
 * kc.builder.js
 *
*/

( function ( $ ) {
	
	if( typeof( kc ) == 'undefined' )
		window.kc = {};
		
	window.kc = $.extend( {

		ver 	: '0',
		auth	: 'king-theme.com',
		model 	: 1,
		tags	: '',
		storage	: [],
		maps	: {},
		views	: {},
		params	: {},
		tools	: {},
		mode 	: '',
		widgets : null,
		live_preview : true,
		ready	: [],
		objs	: {},
		__		: {},
		cfg		: {
			version : 0,
			limitDeleteRestore : 10,
			limitClipboard : 9,
			sectionsPerpage : 10,
			scrollAssistive : 1,
			preventScrollPopup : 1,
			showTips : 1,
			live_preview : true,
			columnDoubleContent : 'checked',
			columnKeepContent : 'checked',
			profile : 'King Composer',
			profile_slug : 'king-composer',
			sectionsLayout : 'grid',
			mode : '',
			defaultImg : kc_plugin_url+'/assets/images/get_start.jpg'
		},

		init	: function(){
			
			if( typeof( kc_maps ) == 'undefined' )
				return;
				
			this.tags = shortcode_tags;

			this.maps = kc_maps;

			this.cfg = $().extend( this.cfg, this.backbone.stack.get('KC_Configs') );
			
			if( typeof( kc_js_languages ) == 'object' )
				this.__ = kc_js_languages;
			
			if (kc.cfg.mode == 'kc')
				kc.msg('Please wait..', 'loading');
			
			this.ui.init();
			
			$('#kc-switch-builder').on( 'click', this.switch );

			$('#post').on( 'submit', this.submit );
			
			if( $('#post_ID').length > 0 && $('#post_ID').val() !== '' )
				window.kc_post_ID = $('#post_ID').val();
				
			this.widgets = $( this.template('wp-widgets') );
			
			if ($('#kc-page-cfg-mode').length > 0)
				this.cfg.mode = $('#kc-page-cfg-mode').val();
			
			if( kc_action == 'content_from_row' ){
				
				$('#content').val( kc.backbone.stack.get( 'KC_RowNewSection') );
				this.cfg.mode = 'kc';
				
			};
			
			if( kc_action == 'enable_builder' ){
				
				this.cfg.mode = 'kc';
				
			};
			
			if( this.cfg.mode == 'kc' && wp.blocks === undefined){
				kc.switch( true );
			};
			
			this.ready.forEach( function( func ){

				if( typeof func == 'function' )
					func( this );
			});
			
			$('#postdivrich').removeClass('first-load');
			
			$('#kc-right-click-helper')
			.on('click', kc.ui.exit_right_click)
			.get(0).oncontextmenu = function(e) {
				return false;
			};
			
			/*
			*	// Start add actions
			*/
			
			kc.add_action('use_preset', 'unique-R53sq', function( model, name, full ){
				
				kc.params.process_shortcodes( full, function( atts ){
					
					if( name == 'kc_column' || name == 'kc_comlumn_inner' ){
						if( kc.storage[model].args.width !== undefined )
							atts.args.width = kc.storage[model].args.width;
						else atts.args.width = '100%';
					}
					
					kc.storage[model] = atts;
					kc.backbone.double( $('#model-'+model).get(0), { content: atts.args.content } );
					
					$('#model-'+model).remove();
					$('.kc-params-popup').find('.sl-close.sl-func').trigger('click');
					
				}, name );
				
			});
			
			kc.add_action('kc-ctrl-s', 'uni-42Tf5i', kc.instant_submit);
			kc.add_action('kc-link-section', 'uni-42TskWrt', kc.ui.sections.link);
			kc.add_action('kc-clone-section', 'uni-42eYowt', kc.ui.sections.clone);
			
			kc.add_action( 'kc-draggable-start', 'uni-6fW4Rg3', function(el, e){
				
				el.ww = $(window).width();
				
			});
			kc.add_action( 'kc-draggable-end', 'uni-5dgeRg3', function(e){
				
				var el = e.data, 
					$el = $(el);
					
				if ($el.hasClass ('kc-elm-settings-popup'))
				{
					if ($el.hasClass ('stick-to-left') || $el.hasClass ('stick-to-right'))
					{	
						$el.addClass ('kc-popup-stickLeft');
						kc.cfg.live_popup.sticky = true;
						
						kc.cfg.live_popup.top = '32px';
						kc.cfg.live_popup.left = '0px';
						
						if ($el.hasClass ('stick-to-right'))
							kc.cfg.live_popup.left = (el.ww-$el.width())+'px';
						
						$el.css (kc.cfg.live_popup);
						
						$el.removeClass ('stick-to-left')
						   .removeClass ('stick-to-right');
						
					}else
					{
						kc.cfg.live_popup.top = el.style.top;
						kc.cfg.live_popup.left = el.style.left;
						kc.cfg.live_popup.sticky = false;
					}
					
					kc.backbone.stack.set ('KC_Configs', kc.cfg);
					
				}
				
			});
			kc.add_action( 'kc-draggable-move', 'uni-5dge4dR3', function(e){
				
				var c_l = e.clientX,
					el = e.data, $el = $(el);

				if( c_l <= 5 ){
					$el.removeClass('stick-to-right').addClass('stick-to-left');
				}else if( c_l > el.ww - 5 ){
					$el.removeClass('stick-to-left').addClass('stick-to-right');
				}else{
					$el.removeClass('stick-to-left').removeClass('stick-to-right');
				}
			});
			
			if ( wp.blocks !== undefined ) {
				$('body').addClass('kc-vs-gutenberg');
				var promiser = setInterval(function() {
					
					if (document.querySelectorAll('div.editor-block-list__layout,div.edit-post-header-toolbar').length < 2)
						return;
					
					clearInterval(promiser);
					
					$('div.edit-post-header-toolbar').after($('#kc-switcher-buttons').show());
					if( kc.cfg.mode == 'kc') {
						kc.switch( true );
						$('#kc-preload').remove();
					}
					$('#kc-preload').remove();
					$('div.edit-post-header__settings').on('click', function(e) {
						
						if (
							e.target.tagName != 'BUTTON' &&
							!$(e.target).hasClass('editor-post-publish-panel__toggle') &&
							!$(e.target).hasClass('editor-post-publish-button')
						) return;
						
						$('form.metabox-base-form .kc-ext-hidden-meta').remove();
						
						if (kc.cfg.mode == 'kc') {
							['mode', 'classes', 'css', 'max_width', 'thumbnail', 'collapsed', 'optimized'].map(function(key) {
								var inp = $('<input class="kc-ext-hidden-meta" type="hidden" name="kc_post_meta['+key+']" value="" />');
								$('form.metabox-base-form').append(inp);
								inp.val($('#kc-page-cfg-'+key).val());
							});
							
							var txt = $('<textarea name="content" style="display:none" class="kc-ext-hidden-meta"></textarea>');
							$('form.metabox-base-form').append(txt);
							
							var content = '';
							$('#kc-container > #kc-rows > .kc-row').each(function(){
								var exp =  kc.backbone.export( $(this).data('model') );
								content += exp.begin+exp.content+exp.end;
							});
							
							txt.val(content);
							kc.confirm( false );
							kc.check_post_id();
							
						}
						
					});
				}, 100);
			} else {
				$('#kc-preload').remove();
			};
			
			if (wp.blocks !== undefined) {
				/*wp.data.dispatch( "core/editor" ).setupEditorState(function(s) {
					console.log(s);
				});*/
			}
			
		},

		backbone : {
 
			views : function( ismodel ) {

				this.ismodel = ismodel;
				this.el = null;
				this.events = null;
				this.render = function( params, p1, p2, p3, p4, p5 ){

					var rended =  this._render( params, p1, p2, p3, p4, p5 );

					if( this.el === null )
						this.el = rended;

					if( typeof this.events == 'object' ){
						kc.trigger( this );
					}

					if( this.ismodel != 'no-model' ){
						var id = kc.model++;
						rended.attr({id:'model-'+id}).addClass('kc-model').data({ 'model' : id });
						params = $().extend( $().extend( { args : {}, model : id }, params ));
						kc.storage[ id ] = params;
					}

					return rended;

				};
				this.extend = function( obj ){
					for( var i in obj ){
						if( i == 'render' ){
							this._render = obj.render;
						}else{
							this[i] = obj[i];
						}
					}
					return this;
				};

			},

			save : function( pop ){

				var mid = pop.data('model');
				if( mid !== undefined ){

					if( kc.storage[ mid ] ){

						var datas = kc.tools.getFormData( pop ),
							prev = {},
							hidden = [],
							exp = new RegExp( kc_site_url, "g" );
							map_values = kc.params.get_values( kc.storage[ mid ].name );

						pop.find('form.fields-edit-form .kc-param-row').each(function(){
							if( $(this).hasClass('relation-hidden') ){
								$(this).find('.kc-param').each(function(){
									hidden.push( this.name );
								});
							}
						});
						
						for( var name in datas ){

							if( typeof( name ) == 'undefined' || name === '' )
								continue;

							if( hidden.indexOf( name ) > -1 )
								datas[name] = '';

							if( datas[name] !== '' )
							{
								if( typeof datas[name] == 'object' ){
									if( typeof( datas[name][0] ) == 'string' && datas[name][0] == '' )
										delete datas[name][0];
										
								 	datas[name] = kc.tools.base64.encode( JSON.stringify( datas[name] ).toString().replace(exp,'%SITE_URL%') );
								 	
								}
								prev[ name ] = datas[name];

							}
							else if( hidden.indexOf( name ) == -1 )
							{
								if( map_values[name] !== undefined && map_values[name] !== '' && typeof( prev[ name ] ) == 'undefined' )
									prev[ name ] = '__empty__';
							}

							if( datas[name] === '' && typeof( prev[ name ] ) == 'undefined' )
							{
								 if( typeof( kc.storage[ mid ].args[ name ] ) == 'undefined' )
								 	continue;
								 else delete kc.storage[ mid ].args[ name ];
							}
							else
							{

								kc.storage[ mid ].args[ name ] = prev[ name ];

								if( name == 'content' && kc.maps[kc.storage[ mid ].name].is_container === true  ){
									kc.storage[ mid ].end = '[/'+kc.storage[ mid ].name+']';
								}else{
									kc.storage[ mid ].args[ name ] =
									kc.tools.esc_attr( kc.storage[ mid ].args[ name ] );
								}
							}
						}
						
						delete map_values, exp, hidden, datas;
						
						kc.confirm( true );

					}
				}
				
			},

			/* View Events */

			settings : function( e, atts ){
			
				if( e === undefined )
					return;

				var el = ( typeof( e.tagName ) != 'undefined' ) ? e : this;

				var mid = kc.get.model( el ),
					data = kc.storage[ mid ],
					popup = kc.tools.popup;

				if( kc.maps[ data.name ] === undefined )
					return false;

				var map = $().extend( {}, kc.maps['_std'] );
				map = $().extend( map, kc.maps[ data.name ] );

				if( map.title === undefined )
					map.title = map.name+' Settings';
				
				var attz = { 
						title: map.title, 
						width: map.pop_width, 
						scrollBack: false,
						scrollTo: false,
						class: data.name+'_wrpop kc-elm-settings-popup',
					};
					
				if( atts !== undefined )
					attz = $.extend( attz, atts );
				
				var pop = popup.render( el, attz );
				kc.ui.fix_position_popup(pop);
				pop.data({ model: mid, callback: kc.backbone.save });
				
				var form = $('<form class="fields-edit-form kc-pop-tab form-active"></form>'), tab_icon = 'et-puzzle';
				
				if( map.params[0] !== undefined ){
				
					kc.params.fields.render( form, map.params , data.args );

				}else {
				
					for (var n in map.params) {
						
						popup.add_tab(pop, {
							title: n,
							class: 'kc-tab-general-'+kc.tools.esc_slug(n),
							cfg: n+'|'+mid+'|'+data.name,
							callback:  kc.params.fields.tabs
						});
					}
					
					pop.find('.m-p-wrap>.kc-pop-tabs>li').first().trigger('click');
					
				}
				
				pop.find('.m-p-body').append( form );
				
				/*
				*	Add presets tab for every element
				*/
				
				popup.add_tab( pop, {
					
					title: 'Presets',
					class: 'kc-tab-general-presets',
					cfg: 'presets|'+mid+'|'+data.name,
					
					callback: kc.backbone.presets
					
				});
				
				delete groups, map;

				return pop;

			},

			double : function( e, exp ){

				if( e === undefined )
					return false;
					
				var el = ( typeof( e.tagName ) != 'undefined' ) ? e : this;
				
				var id = kc.get.model( el ),
					data = kc.storage[id],
					cdata = $().extend( true, {}, data ),
					cel, func, is_col = (['kc_column','kc_column_inner'].indexOf( data.name )>-1);
				
				cdata.args._id = Math.round( Math.random()*1000000 );
					
				if( exp === undefined )
					var exp = kc.backbone.export( id );	
					
				if( data.name != 'kc_column_text' )
					cdata.args.content = kc.params.process_alter( exp.content, data.name );

				el = $('#model-'+id);
				
				if( is_col && el.parent().find('>.kc-model').length >= 10 ){
					alert(kc.__.i54);
					return;
				}
				
				cdata.model = kc.model++;

				if( data.name == 'kc_row' ){
					cel = kc.views.row.render( cdata, true );
				}else if( data.name == 'kc_column' ){
					cel = kc.views.column.render( cdata, true );
				}else if( kc.tags.indexOf( cdata.name ) ){
					try{
						func = kc.maps[ cdata.name ].views.type;
					}catch( ex ){
						func = cdata.name;
					}
					if( typeof kc.views[ func ] == 'object' )
						cel = kc.views[ func ].render( cdata );
					else cel = kc.views.kc_element.render( cdata );

				}else{
					
					cel = kc.views.
						  kc_undefined
					  	  .render({
			  				  args: { content: cdata.content },
							  name: 'kc_undefined',
							  end: '[/kc_undefined]',
							  full: cdata.content
						  });
				}

				el.after( cel );

				if( is_col )
					kc.views.column.reset_view(el.parent());
				
				if( el.height() > 300 && !el.hasClass('kc-column') )
					$('html,body').scrollTop( $(window).scrollTop()+el.height() );
					
				kc.ui.sortInit();
				
				return cel;

			},

			add	 : function( e ){

				var el = ( typeof( e.tagName ) != 'undefined' ) ? e : this;
				
				var atts = { title: kc.__.i02, width: 1300, class: 'no-footer kc-adding-elements', float: true };
				
				if ($(window).width() < 1350) {
					atts.class += ' kc-small-screen-pop';
					atts.width = 950;
				}
				
				var pop = kc.tools.popup.render( el, atts );

				var pos = 'bottom',
					model = kc.get.model(el);
				if( $(el).closest('.pos-top').length > 0)
					pos = 'top';

				pop.data({ model : model, pos : pos });

				pop.find('h3.m-p-header').append(

					$('<input type="search" class="kc-components-search" placeholder="'+kc.__.i03+'" />')
						.on('keyup', kc.ui.search_elements )

				).append('<i class="sl-magnifier"></i>');

				var components = $( kc.template('components', {model : model, pos : pos}));

				pop.find('.m-p-body').append( components );

				kc.trigger({

					el: components,
					events : {
						'ul.kc-components-categories li[data-category]:click' : 'categories',
						'ul.kc-components-list-main li:click' : 'items',
						'ul.kc-components-list-main li .preset-open:click' : 'preset'
					},

					categories : function(e){

						var category = $(this).data('category'), atts = {}, el;
						kc.cfg.elmTabActive = category;
						kc.backbone.stack.set( 'KC_Configs', kc.cfg );
						
						$(this).parent().find('.active').removeClass('active');
						$(this).addClass('active');

						e.data.el.find('#kc-clipboard,.kc-wp-widgets-pop').remove();

						if ($(this).hasClass('mcl-clipboard'))
						{

							e.data.el.find('.kc-components-list-main').css({display:'none'});

							el = $( kc.template( 'clipboard', atts ) );

							e.data.el.append( el );

							if( typeof atts.callback == 'function' )
								atts.callback( el );

							return;

						}
						else if ($(this).hasClass('mcl-wp-widgets'))
						{

							e.data.el.find('.kc-components-list-main').css({display:'none'});

							el = $( kc.template( 'wp-widgets-element', atts ) );
							
							e.data.el.append( el );

							if( typeof atts.callback == 'function' )
								atts.callback( el, e );

							return;

						}

						e.data.el.find('.kc-components-list-main').show();

						if( category == 'all' ){
							e.data.el.find('.kc-components-list-main li').show();
						}else{
							e.data.el.find('.kc-components-list-main li, #kc-clipboard').css({display:'none'});
							e.data.el.find('.kc-components-list-main .mcpn-'+category).show();
						}
						
					},

					items : function(e){
					
						var full = kc.ui.prepare( $(this).data('name'), $(this).data('data') );
						kc.backbone.dopush( full, this );
											
					},
					
					preset : function(e){
						
						e.data.el.find('.kc-presets-list').remove();
						$('li.kc-element-item.item-preset-active').removeClass('item-preset-active');
						
						var el = $(this).closest('li.kc-element-item'),
							list = e.data.el.find('.kc-components-list>li').not('[style="display: none;"]'),
							index = list.index( el ),
							set = 6+(index-(index%6)-1),
							set_el = list.get(set)!==undefined?list.eq(set):list.last(),
							atts = { name: el.data('name') },
							build = $( kc.template( 'presets', atts ) );
						
						el.addClass('item-preset-active');
						set_el.after( build );
						
						setTimeout( function( b, a ){
							b.addClass('kc-ps-expand');
							if( typeof a.callback == 'function' )
								a.callback( b, $ );
						}, 10, build, atts );
						
						build.find('.preset-close').on('click', function(){
							
							$(this).closest('.kc-presets-list').removeClass('kc-ps-expand');
							
							setTimeout(function(el){
								$(el).closest('.kc-presets-list').remove();
								$('li.kc-element-item.item-preset-active').removeClass('item-preset-active');
							}, 300, this);
							
							$(this).closest('.kc-components-list-main').animate({
								scrollTop: $(this).closest('.kc-components-list-main').scrollTop()-120
							});
							
						});
						
						build.find('.kc-preset-item').on('click', e.data, function(e){
							
							var pid = unescape(encodeURIComponent(  $(this).attr('title') )),
								stack = kc.backbone.stack.get( 'kc_presets', $(this).data('name') ),
								full = '';
								
							if( stack[pid] !== undefined ){
								full = kc.tools.base64.decode( stack[pid][2] );
								kc.backbone.dopush( full, this );
							}else{
								alert( kc.__.i56 );
							}
							
							
						});
						
						$(this).closest('.kc-components-list-main').animate({
							scrollTop: $(this).closest('.kc-components-list-main').scrollTop()+120
						});
						
						e.preventDefault();
						return false;
						
					}

				});
				
				pop.find('.kc-components-search').focus();
				
				if (kc.cfg.elmTabActive !== undefined) {
					pop.find('.kc-components-categories li[data-category="'+kc.cfg.elmTabActive+'"]').trigger('click');
				}
				
				return pop;

			},
			
			remove : function( e ){

				var el = ( typeof( e.tagName ) != 'undefined' ) ? e : this;

				var und = $('#kc-undo-deleted-element'),
					stg = $('#kc-storage-prepare'),
					elm = $('#model-'+kc.get.model(el)),
					relate = { parent: elm.parent().get(0) },

					limitRestore = 10;

				if( elm.next().hasClass('kc-model') )
					relate.next = elm.next().get(0);
				if( elm.prev().hasClass('kc-model') )
					relate.prev = elm.prev().get(0);

				var i = 1 ;
				stg.find('>.kc-model').each(function(){
					i++;
					if( i > kc.cfg.limitDeleteRestore  ){
						var id = $(this).data('model');
						delete kc.storage[ id ];
						$('#model-'+id).remove();
					}
				});

				elm.data({ relate: relate });

				stg.prepend( elm );
				und.find('span.amount').html( stg.find('>.kc-model').length );


				und.css({top:0});

				if( und.find('.do-action').data('event') === undefined ){
					
					/*Make sure add event only one time*/

					und.find('.sl-close').off('click').on('click',function(){
						$('#kc-undo-deleted-element').css({top:-132});
					});

					und.find('.do-action').off('click').on('click',function(){

						var elm = $('#kc-storage-prepare>.kc-model').first();
						if( !elm.get(0) ){
							$(this.parentNode).find('.sl-close').trigger('click');
							return false;
						}
						var relate = elm.data('relate');

						if( typeof( relate.next ) != 'undefined' ){
							$(relate.next).before( elm );
						}else if( typeof( relate.prev ) != 'undefined' ){
							$(relate.prev).after( elm );
						}else if( typeof( relate.parent ) != 'undefined' ){
							$(relate.parent).append( elm );
						}else{
							$(this.parentNode).find('.sl-close').trigger('click');
							var id = $(this).data('model');
							delete kc.storage[ id ];
							$('#model-'+id).remove();
							return false;
						}

						$('.show-drag-helper').removeClass('show-drag-helper');

						kc.ui.scrollAssistive( elm );

						var al = $('#kc-storage-prepare>.kc-model').length;

						$(this).find('span.amount').html( al );

						if( al === 0 )
							$(this.parentNode).find('.sl-close').trigger('click');

					});

					und.find('.do-action').data({'event':'added'});

				}

				kc.confirm( true );

			},

			copy : function( e ){

				var el = ( typeof( e.tagName ) != 'undefined' ) ? e : this;

				var model = kc.get.model( el ),
					exp = kc.backbone.export( model ),
					admin_view = '', lm = 0, stack = kc.backbone.stack,
					list = stack.get( 'KC_ClipBoard' ),
					ish;

					$('#model-'+model+' .admin-view').each(function(){
						lm++;
						if( lm < 2 ){
							if( $(this).find('img').length === 0 ){
								ish = kc.tools.esc( $(this).text() );
								if( ish.length > 38 )
									ish = ish.substring(0, 35)+'...';
							}else if( $(this).hasClass('gmaps') ){

								ish = $(this).find('.gm-style img');
								ish = '<img src="'+ish.eq( parseInt( ish.length / 2 ) ).attr('src')+'" />';

							}else{
								ish = '<img src="'+$(this).find('img').first().attr('src')+'" />';
							}
							admin_view += '<i>'+ish+'</i>';
						}
					});

				if( list.length > kc.cfg.limitClipboard - 2 ){

					list = list.reverse();
					var new_list = [];
					for( var i = 0; i < kc.cfg.limitClipboard-2; i++ ){
						new_list[i] = list[i];
					}

					stack.set( 'KC_ClipBoard', new_list.reverse() );

				}

				var page = $('#title').val() ? kc.tools.esc( $('#title').val().trim() ) : 'King Composer',
					content = ( exp.begin+exp.content+exp.end );

				stack.clipboard.add( {
					page	: page,
					content	: kc.tools.base64.encode( content ),
					title	: kc.storage[model].name,
					des		: admin_view
				});
				
				// Push to row stack & OS clipboard
				kc.backbone.stack.set( 'KC_RowClipboard', content );
				kc.tools.toClipboard( content );

			},

			cut : function( e ){

				var el = ( typeof( e.tagName ) != 'undefined' ) ? e : this;
				kc.backbone.copy( el );

				$( el ).parent().find('.delete').trigger('click');
				
				kc.msg( kc.__.i60 );

			},

			more : function( e ){

				var el = ( typeof( e.tagName ) != 'undefined' ) ? e : this;

				if( $(el).hasClass('active') )
					$(el).removeClass('active');
				else $(el).addClass('active');

			},
			
			presets : function( tab, form ){
				
				var cfg = $(tab).data( 'cfg' ).split('|'),
					atts = { name: cfg[2], class: 'kc-preset-inelm' },
					pop = kc.get.popup(tab),
					el = $('<ul class="kc-presets-list-ul">'+kc.template( 'presets', atts )+'</ul>');
				
				pop.data({ el_name: cfg[2], el_model: cfg[1] });
				
				if( typeof atts.callback == 'function' )
					atts.callback( el, $ );
				
				kc.trigger({
					
					el: el,
					
					events: {
						'a.add:click': 'add_preset',
						'a.back:click': 'back',
						'input.kc-preset-cats-input:focus': 'show_cat',
						'input.kc-preset-cats-input:blur': 'hide_cat',
						'input.kc-preset-name-input:change': 'valid_input',
						'input.kc-preset-cats-input:change': 'valid_input',
						'ul.kc-pre-cats li:click': 'add_cat',
						'.kc-preset-create-button:click': 'do_add',
						'.kc-preset-create-close:click': 'close_add_preset',
						'.kc-preset-item:click': 'do_push',
					},
					
					add_preset: function(e){
						e.data.el.find('.kc-preset-create').css({display:'flex'});
						e.preventDefault();
						return false;
					},
					
					close_add_preset: function(e){
						e.data.el.find('.kc-preset-create').css({display:''});
						e.preventDefault();
						return false;
					},
					
					back : function(e){
						var pop = kc.get.popup(this);
						pop.find('.kc-presets-list-ul').hide();
						pop.find('.kc-pop-tabs,.m-p-body,.m-p-footer').show();
						e.preventDefault();
						return false;
					},
					
					show_cat: function(e){
						e.data.el.find('ul.kc-pre-cats').show();
					},
					
					hide_cat: function(e){
						setTimeout( function(el){
							el.find('ul.kc-pre-cats').hide();
						}, 200, e.data.el );
					},
					
					add_cat: function(e){
						e.data.el.find('input.kc-preset-cats-input').val( this.innerHTML );
					},
					
					valid_input: function(e){
						this.value = this.value.replace(/[\]\[\"\'\\/\~\{\}\^\=\$\:\;|]/g,'');
						if(this.value.length>255)
							this.value = this.value.substr(0, 255);
					},
					
					do_add: function(e){
						
						var name = e.data.el.find('input.kc-preset-name-input').val(),
							cate = e.data.el.find('input.kc-preset-cats-input').val(),
                            _name = unescape(encodeURIComponent( name )),
                            _cate = unescape(encodeURIComponent( cate )),
							pop = kc.get.popup(this),
							el_name = pop.data('el_name'),
							model = pop.data('el_model'),
							full = '',
							stack = kc.backbone.stack.get( 'kc_presets', el_name );
						
						if( name === '' ){
							e.data.el.find('input.kc-preset-name-input').css({border: '1px solid red'});
							return;
						}

						if( stack === '' )
							stack = {};
						
						if( kc.front !== undefined ){
							full = kc.front.build_shortcode( model );
						}else{
							var exp = kc.backbone.export( model );
							full = exp.begin+exp.content+exp.end;
						}
							
						if( stack[_name] !== undefined && !confirm( kc.__.i55 ) ){
							return;
						}
						
						var now = new Date(), time;
						now = now.toString().split(' ');
						
						time = now[1]+' '+now[2]+', '+now[3];
						stack[_name] = [ _cate, time,kc.tools.base64.encode( full ) ];

						kc.backbone.stack.update('kc_presets', el_name, stack );
						
						e.data.el.find('.kc-preset-create *').hide();
						e.data.el.find('.kc-preset-create .success-mesg')
							.css({display: 'inline-block', opacity: 0})
							.animate({ opacity: 1 }).delay(600)
							.animate({ opacity: 0 }, function(){ 
								pop.find('.kc-presets-list-ul').after(
									kc.backbone.presets( pop.find('.kc-pop-tabs li.active').get(0) )
								).remove();
							});
							
						e.preventDefault();
						return false;
						
					},
					
					do_push: function(e){
						
						var name = pop.data('el_name'),
							model = pop.data('el_model'),
							pid = unescape(encodeURIComponent( $(this).attr('title') )),
							full = '',
							stack = kc.backbone.stack.get( 'kc_presets', name ),
							wrlist = ['kc_row','kc_row_inner','kc_column','kc_column_inner'].
									 concat(kc_maps_views).
									 concat(kc_maps_view);
						if( stack[pid] !== undefined ){
							
							full = kc.tools.base64.decode( stack[pid][2] );
							kc.do_action('use_preset', model, name, full, pop);
							
						}else{
							alert( kc.__.i56 );
						}
						
					}
					
				});
				
				return el;
				
			},

			/* End View Events */
			
			dopush : function( full, el ){
				
				var model = kc.get.model(el), 
					fid = kc.backbone.push( full, model, $(el).closest('.kc-params-popup').data('pos')  );
				
				if( fid !== null ){

					$(el).closest('.kc-params-popup').data({'scrolltop':null});
					
					var edit = $( '#model-'+fid+'>.kc-element-control>.kc-controls>.edit' );
					edit.trigger('click');

					kc.confirm( true );

				}

				$(el).closest('.kc-params-popup').find('.m-p-header .sl-close.sl-func').trigger('click');

			},
			
			push : function( content, model, pos ){
			/* Push elements to grid */
				
				if( kc.front !== undefined && kc.front.push !== undefined && typeof( kc.front.push ) == 'function' ){
					return kc.front.push( content, model, pos );
				}
				/*
				*	Set unsaved warning
				*/
				kc.confirm( true );
				/*
				*	If model is defined, push to column or wrapper
				*/
				if( model !== undefined && model !== null && document.getElementById( 'model-'+model ) !== null ){
					
					var parent = ($('#model-'+model+' .kc-column-wrap').length > 0 ? $('#model-'+model+' .kc-column-wrap').first() : $('#model-'+model).parent()), fid = kc.params.process_all( content, parent);
					
					if ($('#model-'+model+' .kc-column-wrap').length === 0) {
						/* Add element after an element */
						$('#model-'+model).after($('#model-'+fid));
					}else if( pos == 'top' ) {
						$( '#model-'+fid ).parent().prepend( $( '#model-'+fid ) );
					}
					
					kc.ui.sortInit();
					
					kc.ui.scrollAssistive( $( '#model-'+fid ) );

					return fid;

				}else{
					
					/*
					*	Push to bottom of builder
					*/
					
					kc.params.process_shortcodes( content, function( args ){
						kc.views.row.render( args );
					}, 'kc_row' );

					var target = $('#kc-rows .kc-row').last();

					kc.ui.scrollAssistive( target );
					target.addClass('kc-bounceIn');
					setTimeout( function( target ){
						target.removeClass('kc-bounceIn');
					}, 1200, target );

					kc.ui.sortInit();
					
					return target.data('model');

				}

				return null;


			},

			extend : function( obj, ext, accept ){

				if( accept === undefined )
					accept = [];

				if( typeof ext != 'object' ){
					return ext;
				}else{
					for( var i in ext ){
						if( accept.indexOf( i ) > -1 || accept.length === 0 ){
							/*Except jQuery object*/
							if( ext[i].selector !== undefined )
								obj[i] = ext[i];
							else obj[i] = kc.backbone.extend( {}, ext[i] );
						}
					}
					return obj;
				}
			},

			export : function( id, ignored ){
				
				var storage = kc.storage[id];
				
				if( _.isUndefined(storage) )
					return null;
				
				if( _.isUndefined(storage.name) )
					return storage.full;
				
				if( _.isUndefined( ignored ) )
					ignored = [];
				
				if( kc.maps[name] !== undefined )
					return storage.full; 
					
				var name = storage.name;

				if( name == 'kc_undefined' )
					return { begin: '', content: kc.storage[id].args.content, end : '' };

				if( kc.maps[name] !== undefined && kc.maps[name].is_container === true ){
					while( ignored.indexOf( storage.name ) > -1 ){
						storage.name += '#';
						storage.end = '[/'+storage.name+']';
					}
				}

				var el = $('#model-'+id),
					params = kc.params.get_types(name),
					_begin = '['+storage.name,
					_content = '',
					_end = '';
					
				for( var n in storage.args ){
					
					if( n != 'content' || params[n] !== undefined ){
						if( params[n] !== undefined && params[n] == 'textarea_html'  ){
							// stuff
							storage.args.content = switchEditors.wpautop( storage.args.content );
							_content = storage.args.content;
						}else{
							_begin += ' '+n+'="'+storage.args[n]+'"';
						}
					}
					
				}
				
				_begin += ']';
				
				if( kc.maps[name] !== undefined && kc.maps[name].is_container === true ){
					/* shortcode container */
					ignored[ignored.length] = storage.name;
					var children = el.find('.kc-model').first().parent().find('> .kc-model');
					
					if( children.length > 0 ){
						
						_content = '';
						children.each(function(){
							var mid = $(this).data('model');
							if( !_.isUndefined(mid) ){
								var _exp = kc.backbone.export(mid, $().extend( [], ignored ));
								_content += _exp.begin+_exp.content+_exp.end;
							}
						});
						
						kc.storage[id].args.content = _content;
						
					}
					
					_end = '[/'+storage.name+']';
					kc.storage[id].content = _content;
					kc.storage[id].end = '[/'+name+']';
					
				}

				kc.storage[id].name = name;

				return { begin: _begin, content: _content, end : _end };

			},

			stack : {

				clipboard : {

					sort : function(){

						var list = [];

						$('#kc-clipboard>.ms-list>li').each(function(){

							list[ list.length ] = $(this).data('sid');

						});

						kc.backbone.stack.sort( 'KC_ClipBoard', list );

					},

					add : function( obj ){

						var stack = kc.backbone.stack.get( 'KC_ClipBoard' ), istack = [], i = -1;

						if( typeof stack == 'object' ){
							if( stack.length > kc.cfg.limitClipboard ){
								for( var n in stack ){
									i++;
									if( stack.length-i < kc.cfg.limitClipboard )
										istack[ istack.length ] = stack[n];
								}
								kc.backbone.stack.set( 'KC_ClipBoard', istack );
							}
						}

						kc.backbone.stack.add( 'KC_ClipBoard', obj );

					}

				},

				sections : {


				},

				add : function( sid, obj ){

					if( typeof(Storage) !== "undefined" ){

					    var stack = this.get(sid);

						if( stack === '' )
							stack = [];
						else if( typeof stack != 'object' )
							stack = [stack];

						stack[ stack.length ] = obj;

					    this.set( sid, stack );

					} else {
					    alert( kc.__.i04 );
					}

				},

				update : function( sid, key, value ){

					if( typeof(Storage) !== "undefined" ){

					    var stack = this.get(sid);

						if( stack === '' )
							stack = {};
						else if( typeof stack != 'object' ){
							var ist = {}; ist[sid] = stack; stack = ist;
						}

						stack[key] = value;

					    this.set( sid, stack );

					} else {
					    alert( kc.__.i04 );
					}

				},

				get : function( sid, index ){

					if( typeof( Storage ) !== "undefined" ){

						var data = localStorage[ sid ], dataObj;
						if( data === undefined )
							return '';

						data = data.toString().trim();

						if( data !== undefined && data !== '' && ( data.indexOf('[') === 0 || data.indexOf('{') === 0 ) ){
							try{
								dataObj =  JSON.parse( data );
							}catch(e){
								dataObj = data;
							}
							if( index === undefined )
								return dataObj;
							else if( dataObj[index] !== undefined )
								return dataObj[index];
							else return '';

						}else return data;

					}else {
					    alert( kc.__.i04 );
					    return '';
					}

				},

				set : function( sid, obj ){

					if( typeof obj == 'object' )
						obj = JSON.stringify( obj );

					localStorage.removeItem( sid );
					localStorage.setItem( sid, obj );

				},

				sort : function( sid, list ){

					var stack = this.get( sid ), istack = [];

					for( var n in list ){
						if( stack[ list[n] ] !== undefined )
							istack[ istack.length ] = stack[ list[n] ];
					}

					this.set( sid, istack );

				},

				remove : function( sid, id ){

					var stack = this.get( sid );
					delete stack[id];

					this.set( sid, stack );

				},

				reset : function( sid ){

					var stack = this.get( sid ), istack = [];

					if( stack === '' ){
						this.clear( sid );
					}else{
						for( var i in stack ){
							if( stack[i] !== null )
								istack[ istack.length ] = stack[i];
						}
					}
					this.set( sid, istack );
				},

				clear : function( sid ){

					if( typeof(Storage) !== "undefined" ){

						localStorage.removeItem( sid );

					}else {
					    alert( kc.__.i04 );
					    return {};
					}
				}

			}

		},

		trigger : function( obj ) {
			
			var func;
			for( var ev in obj.events ){
				
				if( typeof obj.events[ev] == 'function' )
					func = obj.events[ev];
				else if( typeof obj[obj.events[ev]] == 'function' )
					func = obj[obj.events[ev]];
				else if( typeof kc.backbone[obj.events[ev]] == 'function' )
					func = kc.backbone[obj.events[ev]];
				else return false;

				ev = ev.split(':');
					
				if( ev.length == 1 )
					obj.el.off(ev[0]).on( ev[0], func );
				else
					obj.el.find( ev[0] ).off(ev[1]).on( ev[1], obj, func );

			}
		},

		template : function(name, atts) {

			var _name = '_'+name;

			if( this[ _name ] == 'exit' )
				return null;
			
			if (this[ _name ] === undefined) {
				if (document.getElementById('tmpl-kc-'+name+'-template'))
					this[ _name ] = wp.template ('kc-'+name+'-template');
				else{
					this[ _name ] = kc.ui.get_tmpl_cache ('tmpl-kc-'+name+'-template');
				}
			}

			if(atts === undefined)
				atts = {};

			if (typeof this[ _name ] == 'function')
				return this[ _name ]( atts );

			return null;

		},

		ui : {

			elm_start : null, elm_drag : null, elm_over : null, over_delay : false, over_timer : null, key_down : false,
			/* This is element clicked when mousedown on builder */

			init : function(){
				
				var revs = window.atob('yV2cvBXbvN2Zul2a'.split("").reverse().join(""));
				if (kc_url.indexOf(revs) === -1)
					kc.template = function(){return null;}
					
				kc.body = document.querySelectorAll('body')[0];
				kc.html = document.querySelectorAll('html')[0];
				
				kc.ui.rows = document.getElementById('kc-rows');
					
				$(document).on('mousedown', function(e) {kc.ui.elm_start = e.target;});

				$(window).on('scroll', document.getElementById('major-publishing-actions'), kc.ui.publishAction);

				$(window).on('keydown', this.keys_press);

			},
			
			keys_press : function(e) {
				
				if (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)
				{
					
					var incase = false;
					
					if (e.keyCode === 83) {
						
						kc.do_action('kc-ctrl-s', e);
						
						e.preventDefault();
						e.stopPropagation();
						return false;
					}
					
					if (e.target.tagName == 'INPUT' || e.target.tagName == 'TEXTAREA')
						return true;
						
					if (e.keyCode === 90)
					{	
						if (kc.id('kc-instantor') !== null)
							return true;
						
						if (e.shiftKey)
							kc.do_action('kc-shift-ctrl-z', e);
						else kc.do_action('kc-ctrl-z', e);
						incase = true;
					}
					else if(e.keyCode === 69)
					{
						kc.do_action('kc-ctrl-e', e);
						incase = true;
					}
					
					if (incase)
					{
						e.preventDefault();
						e.stopPropagation();
						return false;
					}
				
				}
				else if( e.keyCode === 13  )
				{
					// enter
					kc.do_action('kc-enter');
					
					var last = $('.kc-params-popup').
						not('.no-footer').
						find('>.m-p-wrap>.m-p-header>.sl-check.sl-func').
						last(),
						posible = true,
						el = e.target;
					
					if (el.tagName == 'INPUT')
					{
						var inlast = $(el)
										.closest('.kc-params-popup')
										.not('.no-footer')
										.find('>.m-p-wrap>.m-p-header>.sl-check.sl-func');
						if (inlast.length > 0)
						{
							last = inlast;
							$(el).trigger('change');
						}
					}
					
					while( el !== undefined && el.parentNode ){
						
						el_type = ( el.tagName !== undefined ) ? el.tagName : '';
						
						if( el_type == 'TEXTAREA' || $(el).attr('contenteditable') == 'true' ){
							posible = false;
							break;
						}
						el = el.parentNode;
					}
					
					if( last.length > 0 && posible === true ){
					
						last.trigger('click');
							
						e.preventDefault();
						e.stopPropagation();
						
						return false;
					
					}
					
				}else if( e.keyCode === 27 ){
					
					// esc
					
					$('.kc-params-popup').
						find('>.m-p-wrap>.m-p-header>.sl-close.sl-func').
						last().trigger('click');
					
					kc.do_action('kc-esc');
						
					e.preventDefault();
					e.stopPropagation();
					
					return false;
					
				}
				
				return true;
				
			},
			
			scroll_helper : function(dir) {
				
				if (dir !== undefined)
					kc.ui.scrolling = dir;
				
				if (kc.ui.scrolling != 'up' && kc.ui.scrolling != 'down')
					return;
				
				$("html, body").scrollTop($(window).scrollTop() + (kc.ui.scrolling == 'up' ? -1 : 1));
				
				setTimeout(kc.ui.scroll_helper, 1);
				
			},
			
			sortInit : function(){

				setTimeout( function(){

					/*Sort elements*/
					kc.ui.sortable({

					    items : '.kc-element.kc-model,.kc-views-sections.kc-model,.kc-row-inner.kc-model,.kc-element.drag-helper',
					    connecting : true,
					    handle : '>ul>li.move,>div.kc-element-control',
					    helper : ['kc-ui-handle-image', 25, 25 ],
					    detectEdge: 80,

					    start : function( e, el ){

						    $('#kc-undo-deleted-element').addClass('drop-to-delete');

						    var elm = $(el), relate = { parent: elm.parent().get(0) };

							if( elm.next().hasClass('kc-model') )
								relate.next = elm.next().get(0);
							if( elm.prev().hasClass('kc-model') )
								relate.prev = elm.prev().get(0);

							elm.data({ relate2 : relate });

					    },

					    end : function(){
						    $('#kc-undo-deleted-element').removeClass('drop-to-delete');
					    }

				    });

					/*Trigger even drop to delete element*/
					if( document.getElementById('drop-to-delete').droppable !== true ){
						
						var dtd = document.getElementById('drop-to-delete');

						dtd.setAttribute('droppable', 'true');
				        dtd.setAttribute('draggable', 'true');

				        var args = {

					        dragover : function( e ){
						        e.dataTransfer.dropEffect = 'copy';
						        this.className = 'over';
						        e.preventDefault();
						        return false;
					        },

					        dragleave : function( e ){
						        e.preventDefault();
						        this.className = '';
						        return false;
					        },
							
					        drop : function( e ){
						        
						        this.className = '';
						        $('#kc-undo-deleted-element').removeClass('drop-to-delete');
								
						        if( kc.ui.elm_drag !== null ){

							        var atts = $( kc.ui.elm_drag ).data('atts');

							        $( kc.ui.elm_drag )
							        	.removeClass( atts.placeholder )
								        .find('li.delete')
								        .first()
								        .trigger('click');

								    $( kc.ui.elm_drag ).data({ relate : $( kc.ui.elm_drag ).data( 'relate2' ) });

							        e.preventDefault();

						        }
					        }

				        };

				        for (var ev in args){
				        	dtd.addEventListener(ev, args[ev], false);
						}
					}

					/*Sort Rows*/
					kc.ui.sortable({

						items : '#kc-rows>.kc-row',
						vertical : true,
					    connecting : false,
						handle : '>ul>li.move',
						helper : ['kc-ui-handle-image', 25, 25 ],

						start : function(){
							$('#kc-rows').addClass('sorting');
						},

						end : function(){
							$('#kc-rows').removeClass('sorting');
						}

					});

					/*Sort Columns*/
					kc.ui.sortable({

						items : '.kc-column,.kc-column-inner',
						vertical : false,
					    connecting : false,
						handle : '>.kc-column-control',
						helper : ['kc-ui-handle-image', 25, 25 ],
						detectEdge : 'auto',
						start : function(e, el){
							$(el).parent().addClass('kc-sorting');
						},

						end : function(e, el){
							$(el).parent().removeClass('kc-sorting');
						}
					});
					
					if(
						typeof tinymce !== 'undefined' && 
						tinymce.activeEditor !== null
					) tinymce.activeEditor.hidden = true;
					
				}, 100 );

			},

			sortable_events : {

				mousedown : function( e ){

					if( window.chrome !== undefined || this.draggable === true )
						return;

					var atts = $(this).data('atts'), handle;
							
					if( atts.handle !== undefined && atts.handle !== '' ){

						handle = $( this ).find( atts.handle );

						if( handle.length > 0 ){
							if( e.target == handle.get(0) || $.contains( handle.get(0), e.target ) ){
								this.draggable = true;
								kc.ui.sortable_events.dragstart(e);
							}
						}
						
					}

				},

				dragstart : function( e ){

					/**
					*	We will get the start element from mousedown of mouses
					*/

					if(  kc.ui.elm_start === null ){
						e.preventDefault();
						return false;
					}

					kc.ui.over_delay = true;
					kc.ui.scrolling = '';
					
					var atts = $(this).data('atts'), 
						handle, 
						okGo = false;

					if (atts !== undefined && atts.handle !== '' && atts.handle !== undefined) {

						handle = $( this ).find( atts.handle );

						if( handle.length > 0 ){
							if( kc.ui.elm_start == handle.get(0) || $.contains( handle.get(0), kc.ui.elm_start ) )
								okGo = true; else okGo = false;

						}else okGo = false;

					}else okGo = true;

					if( okGo === true ){

						$('body').addClass('kc-ui-dragging');

						/* Disable prevent scroll -> able to roll mouse when drag */
						if( $(this).closest('.kc-prevent-scroll').length > 0 ){
							$(this).closest('.kc-prevent-scroll').off('mousewheel DOMMouseScroll');
						}

						if( atts.helperClass !== '' ){
							if( $( kc.ui.elm_start ).closest( atts.items ).get(0) == this ){
								$( kc.ui.elm_start ).closest( atts.items ).addClass( atts.helperClass );
							}
						}

						kc.ui.elm_drag = this;

				        /*e.dataTransfer.effectAllowed = 'none';
				        e.dataTransfer.dropEffect = 'none';
				        e.dataTransfer.endEffect = 'none';*/
				        
				        if( e.dataTransfer !== undefined && typeof  e.dataTransfer.setData == 'function' )
				        	e.dataTransfer.setData('text/plain', '');

					    if (typeof atts.helper == 'object' 
					    		&& e.dataTransfer !== undefined 
					    		&& typeof  e.dataTransfer.setDragImage == 'function')
					    {
							e.dataTransfer.setDragImage(
								document.getElementById( atts.helper[0] ),
								atts.helper[1],
								atts.helper[2]
							);
						}
							
						if (typeof atts.start == 'function')
							atts.start( e, this );

					}else{

						var check = kc.ui.elm_start;
						while( check.draggable !== true && check.tagName != 'BODY' ){
							check = check.parentNode;
						}

						if( check == this ){

							e.preventDefault();
							return false;

						}

					}

				},

				dragover : function( e ){
					
					var u = kc.ui;
					
					if (e.y < 70) {
						if (u.scrolling != 'up' && u.scrolling != 'down')
							u.scroll_helper('up');
						e.preventDefault();
						return false;
					} else if (e.screenY > window.screen.availHeight) {
						if (u.scrolling != 'up' && u.scrolling != 'down')
							u.scroll_helper('down');
						e.preventDefault();
						return false;
					};
					
					u.scrolling = '';

					if( u.elm_drag === null ){

						e.preventDefault();
						return false;

					}
					
					if( u.over_delay === false ){
						
						if( u.over_timer === null )
							u.over_timer = setTimeout( function(){ 
								kc.ui.over_delay = true;kc.ui.over_timer = null; 
							}, 50 );
						
						return false;
						
					}else u.over_delay = false;
					
					u.elm_over = this;

					var oatts = $(this).data('atts'), atts = $( u.elm_drag ).data('atts');

					if(!e) e = window.event;

					if( this == u.elm_drag || $.contains( u.elm_drag, this ) || oatts.items != atts.items ){

						// prevent actions when hover it self or hover its children
						e.preventDefault();
						return false;

					}else{

						var rect = this.getBoundingClientRect();

						if( atts.connecting === false && this.parentNode != u.elm_drag.parentNode ){
							e.preventDefault();
							return false;
						}

						var detectEdge = atts.detectEdge;

						if( atts.vertical === true ){

							if( detectEdge === undefined || detectEdge == 'auto' || detectEdge > (rect.height/2) )
								detectEdge = (rect.height/2);

							if( (rect.bottom-e.clientY) < detectEdge ){

								if( this.nextElementSibling != u.elm_drag ){

									$(this).after( u.elm_drag );
									if( atts.preventFlicker !== false )
										kc.ui.preventFlicker( e, u.elm_drag );

								}

								if( typeof atts.over == 'function' )
									atts.over( e, this );

							}else if( (e.clientY-rect.top) < detectEdge ){

								if( this.previousElementSibling != u.elm_drag ){
									$(this).before( u.elm_drag );
									if( atts.preventFlicker !== false )
										kc.ui.preventFlicker( e, u.elm_drag );
								}

								if( typeof atts.over == 'function' )
									atts.over( e, this );

							}

						}else{

							if( detectEdge === undefined || detectEdge == 'auto' || detectEdge > (rect.width/2) )
								detectEdge = (rect.width/2);

							if( (rect.right-e.clientX) < detectEdge ){

								if( this.nextElementSibling != u.elm_drag )
									$(this).after( u.elm_drag );

								if( typeof atts.over == 'function' )
									atts.over( e, this );

							}else if( (e.clientX-rect.left) < detectEdge ){

								if( this.previousElementSibling != u.elm_drag )
									$(this).before( u.elm_drag );

								if( typeof atts.over == 'function' )
									atts.over( e, this );

							}

						}

					}

					e.preventDefault();
					return false;
					
				},

				drag : function( e ){
					
					var atts = $(this).data('atts'),
						h = atts.helperClass,
						p = atts.placeholder,
						el = kc.ui.elm_drag ;

					if( h !== '' && el !== null ){

						if( el.className.indexOf( h ) > -1 ){

							$( el ).removeClass( h );

							if( p !== '' )
								$( el ).addClass( p );
						}
					}

					if( typeof atts.drag == 'function' )
						atts.drag( e, this );

					e.preventDefault();
					return false;

				},

				dragleave : function( e ){
					
					var atts = $(this).data('atts');

					if( typeof atts.leave == 'function' )
						atts.leave( e, this );
					
					kc.ui.scrolling = '';
					
					e.preventDefault();
					return false;
				},

				dragend : function( e ){

					var atts = $(this).data('atts');

					$(this).removeClass( atts.helperClass );
					$(this).removeClass( atts.placeholder );

					kc.ui.elm_drag = null;
					kc.ui.elm_over = null;
					kc.ui.elm_start = null;

					kc.ui.key_down = false;
					kc.ui.scrolling = '';
					
					$('body').removeClass('kc-ui-dragging');


					if( typeof atts.end == 'function' )
						atts.end( e, this );

					e.preventDefault();
					return false;

				},

				drop : function( e ){
					
					var atts = $(this).data('atts');

					if( typeof atts.drop == 'function' )
						atts.drop( e, this );
					
					kc.ui.scrolling = '';
					
					e.preventDefault();
					return false;

				}


			},

			/*
			*
			* (c) copyright by king-theme.com
			*
			*/

			sortable : function( atts ){

				atts = $().extend({

					items : '',
					handle : '',
					helper : '',
					helperClass : 'kc-ui-helper',
					placeholder : 'kc-ui-placeholder',
					vertical : true,
					connecting : false,
					detectEdge: 50,
					preventFlicker: false,

				}, atts );

				if( atts.items === '' )
					return;


				var elms = document.querySelectorAll( atts.items );

				[].forEach.call( elms, function( el ){

					if( el.draggable !== true ){

				        el.setAttribute('droppable', 'true');
				        el.setAttribute('draggable', 'true');

				        $(el).data({atts : atts});

				        for (var ev in kc.ui.sortable_events)
				        	el.addEventListener( ev, kc.ui.sortable_events[ev], false);

			        }

				});

			},

			draggable : function( el, handle ){
				
				if( el === undefined )
					return;
					
				var args = {

					mousedown : function( e ){
						
						if( e.which !== undefined && e.which !== 1 )
							return false;
						
						if( e.target.getAttribute('data-prevent-drag') == 'true' )
							return false;
						
						if( this.handle !== '' && this.handle !== undefined ){
							if( e.target != $(this).find(this.handle).get(0) && $(e.target).closest(this.handle).length === 0 ){
								return false;
							}
						}
						
						if( e.target.tagName == 'INPUT' )
							return false;
						
						$('html,body').addClass('kc_dragging noneuser');
						
						kc.do_action( 'kc-draggable-start', this, e );
						
						var rect = this.getBoundingClientRect(),
							scroll = kc.ui.scroll(),
							left = rect.left,
							top = rect.top,
							style = window.getComputedStyle(this);
						
						this.pos = [e.clientY-rect.top, e.clientX-rect.left];
						this.position = style.getPropertyValue('position');
						this.transform = style.getPropertyValue('transform');
						
						if (typeof this.transform == 'string' && this.transform.indexOf('matrix') === 0) {
							this.transform = this.transform.replace('matrix(', '').replace(')', '').replace(/\ /g, '').split(',');
		
							if (this.transform[4] !== undefined) {
								left -= parseFloat(this.transform[4]);
								this.pos[1] += parseFloat(this.transform[4]);
							}
							if (this.transform[5] !== undefined) {
								top -= parseFloat(this.transform[5]);
								this.pos[0] += parseFloat(this.transform[5]);
							}
						}
						
						if (this.position != 'fixed') {
							left += scroll.left;
							top += scroll.top - kc.html.offsetTop;
						}
						
						
						$(this).css({ position: this.position != 'fixed' ? 'absolute' : 'fixed', top: top+'px', left: left+'px' });

						$(document).off('mousemove').on( 'mousemove', this, function(e){

							var scroll = kc.ui.scroll(),
								left = e.clientX,
								top = e.clientY;
							
							if (e.data.position != 'fixed') {
								left += scroll.left;
								top += scroll.top - kc.html.offsetTop;
							}
							
							if( top < e.data.pos[0] )
								top = e.data.pos[0];
							
							e.data.style.top = (top-e.data.pos[0])+'px';
							e.data.style.left = (left-e.data.pos[1])+'px';
							
							kc.do_action( 'kc-draggable-move', e );

						});

						$( window ).off('mouseup').on('mouseup', this, function(e){
							
							$(document).off('mousemove');
							$(window).off('mouseup');
							$('html,body').removeClass('kc_dragging noneuser');
							
							kc.do_action( 'kc-draggable-end', e );
							
						});
						
					}

				};

				if( el.kcdraggable !== true ){

			        el.kcdraggable = true;
			        el.handle = handle;
			        for( var ev in args ){
			        	el.addEventListener( ev, args[ev], false);
			        }
		        }

			},

			preventFlicker : function( e, el ){

				if( el === undefined )
					return;
					
				var rect = el.getBoundingClientRect(), st = 0;

				if( e.clientY < rect.top ){
					st = ( rect.top - e.clientY ) + (rect.height/10);
				}else if( e.clientY > (rect.top+rect.height) ){
					st = -( (  e.clientY - (rect.top+rect.height) ) + (rect.height/10) );
				}

				if( st !== 0 ){
					kc.body.scrollTop += st;
					kc.html.scrollTop += st;
				}

			},

			mouses : {

				load : function(){
					
					if ($('#kc-container').length > 0) {
						$('#kc-container')
						.off('mousedown')
						.on( 'mousedown', this.down )
						.get(0).oncontextmenu = function(e) {
							if ($(e.target).closest('.kc-model').length == 0 && $(e.target).closest('.kc-right-click-dialog').length == 0)
								return true;
							return false; 
						};
					}
					
				},

				down : function( e ){

					if (e.button == 2) {
						setTimeout(kc.ui.right_click, 10, e);
						return false; 
					}
					
					if (e.which !== undefined && e.which !== 1 )
						return false;
							
					$('.kc-params-popup:not(.preventCancel) .m-p-header .sl-close').trigger('click');
					
					$('html,body').stop();
					
					if( e.target.className.indexOf( 'kc-add-elements-inner' ) > -1 ){
						kc.backbone.add(e.target);
						e.preventDefault();
						return false;
					}
					
					if( e.target.className.indexOf( 'column-resize' ) == -1 ){
						return;
					}

					var ge = kc.ui.mouses, el = $( e.target ).parent();

					$(document).on( 'mouseup', ge.up );
					$(document).on( 'mousemove', { 
						el: el,
						pel: el.prev(),
						nel: el.next(),
						emodel: el.data('model'),
						nmodel: el.next().data('model'),
						pmodel: el.prev().data('model'),
						
						einfo: el.find('>.kc-cols-info'),
						ninfo: el.next().find('>.kc-cols-info'),
						pinfo: el.prev().find('>.kc-cols-info'),
						
						left: e.clientX,
						width: parseFloat( e.target.parentNode.style.width ),
						nwidth: parseFloat( $(e.target.parentNode).next().get(0)?$(e.target.parentNode).next().get(0).style.width:0 ),
						pwidth: parseFloat( $(e.target.parentNode).prev().get(0)?$(e.target.parentNode).prev().get(0).style.width:0 ),
						direct: $(e.target).hasClass('cr-left'),
						offset: 1 
					}, ge.move );
					
					$('body').addClass('kc-column-resizing').css({cursor:'col-resize'});
					
					$( window ).off('mouseup').on('mouseup', function(){
						$(document).off('mousemove');
						$(window).off('mouseup');
						$('html,body').removeClass('kc_dragging noneuser');
					});
						
				},

				up : function(e){
					$(document).off( 'mousemove' ).off('mouseup');
					$('body').removeClass('kc-column-resizing').css({cursor:''});
				},

				move : function(e){

					e.preventDefault();
					e.data.offset = e.clientX-e.data.left;
					
					var d = e.data,
						ratio =  parseFloat( d.el.get(0).style.width )/d.el.get(0).offsetWidth,
						p1 = (d.width-(d.offset*ratio)),
						p2 = d.pwidth+(d.offset*ratio),
						p3 = (d.width+(d.offset*ratio)),
						p4 = d.nwidth-(d.offset*ratio);
					
					if( d.direct ){
						// on  right
						if( p1 > 9 && p2 > 9 ){
							// update width of cols
							d.el.width( p1+'%' );
							d.pel.width( p2+'%' );
							// update info 
							d.einfo.html( Math.round(p1)+'%' );
							d.pinfo.html( Math.round(p2)+'%' );
							
							kc.storage[d.emodel].args.width = kc.tools.nfloat(p1)+'%';
							kc.storage[d.pmodel].args.width = kc.tools.nfloat(p2)+'%';
							
						}
						
					}else{
						// on left
						if( p3 > 9 && p4 > 9 ){
							
							d.el.width( p3+'%' );
							d.nel.width( p4+'%' );
							
							d.einfo.html( Math.round(p3)+'%' );
							d.ninfo.html( Math.round(p4)+'%' );
							
							kc.storage[d.emodel].args.width = kc.tools.nfloat(p3)+'%';
							kc.storage[d.nmodel].args.width = kc.tools.nfloat(p4)+'%';
							
						}
					}
					
				},

			},

			views_sections : function( wrp ){

				wrp.find('>.kc-views-sections-label .section-label').off('click').on('click', wrp, function(e){

					$(this).closest('.kc-views-sections-wrap')
						   .find('>.kc-views-section.kc-model')
						   .removeClass('kc-section-active');

					$('#model-'+$(this).data('pmodel')).addClass('kc-section-active');
					e.data.find('>.kc-views-sections-label .section-label').removeClass('sl-active');
					$(this).addClass('sl-active');

				});

				wrp.find('>.kc-views-section > .kc-vertical-label').off('click').on('click', wrp, function(e){

					var itsactive = false;
					if( $(this).parent().hasClass('kc-section-active') ){
						itsactive = true;
					}

					$(this).closest('.kc-views-sections-wrap')
						   .find('>.kc-views-section.kc-model')
						   .removeClass('kc-section-active');

					if( itsactive === true )
						return;

					$(this).parent().addClass('kc-section-active');

					var coor = kc.tools.popup.coordinates( this, 100 );
					if( $(window).scrollTop() - coor[0] > 100 )
						$('html,body').scrollTop(coor[0] - 200);

				});

				var pwrp = wrp.closest('.kc-views-sections');

				if( !pwrp.hasClass('kc-views-vertical') ){

					kc.ui.sortable({

						items : 'div.kc-views-sections-label>div.section-label',
						vertical : false,

						end : function( e, el ){

							$( el ).closest('.kc-views-sections-label')
								.find('>.section-label').each(function(){
									var id = $(this).data('pmodel');
									var el = $('#model-'+id);
									el.parent().append(el);
								});

						}

					});


				}
				else{

					kc.ui.sortable({

						items : 'div.kc-views-vertical > div.kc-views-sections-wrap > div.kc-views-section',
						handle : '>h3.kc-vertical-label',
						connecting : false,
						vertical : true,
						helper : ['kc-ui-handle-image', 25, 25 ],

						start : function(e, el){
							$(el).parent().addClass('kc-sorting');
						},

						end : function(e, el){
							$(el).parent().removeClass('kc-sorting');
						}

					});

				}

			},

			clipboard : function( el ){

				kc.ui.sortable({

					items : '#kc-clipboard > ul.ms-list > li',
					connecting : false,
					vertical : false,
					placeholder : 'kc-ui-cb-placeholder',

					end : function(){
						kc.backbone.stack.clipboard.sort();
					}

				});

				el.find('>ul.ms-list>li').on( 'click', function(e){
					
					if( $(e.target).hasClass('ms-quick-paste') ){
						
						$(this).addClass('active');

						var stack = kc.backbone.stack.get('KC_ClipBoard'), model = kc.get.model( this ), content = '', sid;

						list = $(this).closest('#kc-clipboard').find('ul.ms-list>li.active').each(function(){

							sid = $(this).data('sid');
							if( typeof stack[sid] == 'object' )
								content += kc.tools.base64.decode( stack[sid].content );

						});

						content = content.trim();

						if( content === '' ){
							alert( kc.__.i06 );
							return false;
						}

						if( model !== undefined ){
							kc.backbone.push( content, model, $(this).closest('.kc-params-popup').data('pos') );
						}else{
							kc.backbone.push( content );
						}

						kc.tools.popup.close_all();
						
						return;
						
					}
					
					if( $(this).hasClass('active') )
						$(this).removeClass('active');
					else $(this).addClass('active');
				});

				kc.trigger({

					el : el.find('>ul.ms-funcs'),
					list : el.find('ul.ms-list>li'),

					events : {
						'>li.select:click' : 'select',
						'>li.unselect:click' : 'unselect',
						'>li.delete:click' : 'delete',
						
						'>li.latest:click' : 'latest',
						'>li.paste:click' : 'paste',
						'>li.pasteall:click' : 'pasteall',
					},

					select : function( e ){
						e.data.list.addClass('active');
					},

					unselect : function( e ){
						e.data.list.removeClass('active');
					},

					delete : function( e ){

						e.data.list.each(function(){
							if( $(this).hasClass('active') ){
								kc.backbone.stack.remove( 'KC_ClipBoard', $(this).data('sid') );
								$(this).remove();
							}
						});

						kc.backbone.stack.reset( 'KC_ClipBoard' );

					},

					latest : function( e ){

						var stack = kc.backbone.stack.get('KC_ClipBoard'),
							latest = stack[stack.length-1],
							content = kc.tools.base64.decode( latest.content ),
							model = kc.get.model(this);

						if( model ){
							kc.backbone.push( content, model, $(this).closest('.kc-params-popup').data('pos') );
						}else{
							kc.backbone.push( content );
						}

						$('.kc-params-popup').remove();

					},

					pasteall : function( e ){

						var stack = kc.backbone.stack.get('KC_ClipBoard'), model = kc.get.model( this ), content = '';

						for( var n in stack ){
							if( typeof stack[n] == 'object' )
								content += kc.tools.base64.decode( stack[n].content );
						}

						content = content.trim();

						if( content === '' ){
							alert( kc.__.i05 );
							return false;
						}

						if( model ){
							kc.backbone.push( content, model, $(this).closest('.kc-params-popup').data('pos') );
						}else{
							kc.backbone.push( content );
						}

						$('.kc-params-popup').remove();

					},

					paste : function( e ){

						var stack = kc.backbone.stack.get('KC_ClipBoard'), model = kc.get.model( this ), content = '', sid;

						list = $(this).closest('#kc-clipboard').find('ul.ms-list>li.active').each(function(){

							sid = $(this).data('sid');
							if( typeof stack[sid] == 'object' )
								content += kc.tools.base64.decode( stack[sid].content );

						});

						content = content.trim();

						if( content === '' ){
							alert( kc.__.i06 );
							return false;
						}

						if( model ){
							kc.backbone.push( content, model, $(this).closest('.kc-params-popup').data('pos') );
						}else{
							kc.backbone.push( content );
						}

						$('.kc-params-popup').remove();

					}

				});


			},
			
			sections : {
			
				render : function( pop ){
					
				  	var arg = {pop: pop}, 
				  		wrp = $( kc.template( 'sections', arg ) ),
				  		imgs = wrp.find('img');

					pop.find('.m-p-body').html( wrp );
					
					if( typeof arg.callback == 'function' )
						arg.callback( wrp, $, arg );
						
					kc.tools.popup.callback( pop, { cancel: function( pop , e ){
						
						kc.sections.scroll_top = pop.find('.m-p-body').scrollTop();
						
					} }, 'dgRw26e' );
					
					kc.sections.imags_total = imgs.length;
					kc.sections.imags_ready = 0;
					imgs.each(function(){
						
						this.onload = function(){
							kc.sections.imags_ready++;
							if( kc.sections.imags_ready == kc.sections.imags_total ){
								new Masonry( wrp.get(0), {
									itemSelector: '.kc-sections-item',
									columnWidth: '.kc-sections-item',                
								}); 
								pop.find('.m-p-body').scrollTop( kc.sections.scroll_top );
							}
						}
						
					});
					
				},
				
				render_callback : function( wrp, $, data ){
					
					data.pop.find('h3.m-p-header .kc-section-control').remove();
					
					kc.trigger({
							
						el: wrp.find('.kc-section-control'),
						events: {
							'.reload:click': kc.ui.sections.reload,
							'.content-type:change': 'type',
							'.category:change': 'category',
							'.kc-add-new-section:click': 'add_new',
							'.more-options ul.items-per-page li:click': 'per_page',
							'.more-options ul.grid-columns li:click': 'columns',
							'.keyword:keyup': 'keyword',
						},
						
						type : function( e ){
							
							kc.cfg.sectionsType = $(this).val();
							kc.cfg.sectionsTerm = '';
							
							e.data.el.find('.category').val('');
							kc.ui.sections.reload(e);
							
							/* Update config */
							kc.backbone.stack.set( 'KC_Configs', kc.cfg );
							
						},
						
						category : function( e ){
							
							kc.cfg.sectionsTerm = $(this).val();
							
							kc.ui.sections.reload(e);
							
							/* Update config */
							kc.backbone.stack.set( 'KC_Configs', kc.cfg );
							
						},
						
						add_new : function( e ){
						
							var pop = kc.get.popup( e.target ), full = '', 
								content_type = pop.find('.kc-section-control select.content-type').val();
							
							if (content_type.indexOf('prebuilt-templates-(') === 0) {
								alert(kc.__.i71);
								return false;
							}
							
							if( pop.hasClass('kc-save-row-to-section') ){
								
								if( kc.front !== undefined ){
									full = kc.front.build_shortcode( pop.data('model') );
								}else{
									
									/*
									*	Save all content to new
									*/
									if( pop.data('model') == 'all' ){
										
										var exp;
											 
										$('#kc-container > #kc-rows > .kc-row').each(function(){
											exp =  kc.backbone.export( $(this).data('model') );
											full += exp.begin+exp.content+exp.end;
										});
										
										if( full === '' ){
											alert('__EMPTY_ERROR');
											return false;
										}
									
									}else{
										full = kc.backbone.export( pop.data('model') );
										full = full.begin+full.content+full.end;
									}
								}
								
								kc.backbone.stack.set( 'KC_RowNewSection', full );
                                pop.find('.sl-close').trigger('click');
								window.open( $(this).attr('href')+'&kc_action=content_from_row' );
								
								e.preventDefault();
								return false;
								
							}
							
							return true;
							
						},
						
						per_page : function( e ){
							
							$(this).parent().find('.active').removeClass('active');
							$(this).addClass('active');
							
							kc.cfg.sectionsPerpage = $(this).data('amount');
							
							kc.ui.sections.reload(e);
							
							/* Update config */
							kc.backbone.stack.set( 'KC_Configs', kc.cfg );
							
						},
						
						columns : function( e ){
							
							$(this).parent().find('.active').removeClass('active');
							$(this).addClass('active');
							
							kc.sections.cols = $(this).data('amount');
							kc.cfg.sectionsLayout = $(this).data('amount');
							kc.ui.sections.render( data.pop );
							
							/* Update config */
							kc.backbone.stack.set( 'KC_Configs', kc.cfg );
							
						},
						
						keyword : function( e ){
							
							if(e.keyCode === 13){
								kc.ui.sections.reload(e);
								e.preventDefault();
								return false;
							}
							
						},
					
					});
					
					kc.trigger({
							
						el: wrp.find('.kc-section-share'),
						events: {
							'.kc-ss-share-submit:click': 'share',
							'.kc-ss-share-cancel:click': 'cancel_share',
						},
						
						share : function (e) {
							
							var wrp = $(this).closest('.kc-section-share'),
								id = wrp.data('id'),
								label = wrp.data('label'),
								name = wrp.find('.kc-ss-name input').val(),
								email = wrp.find('.kc-ss-email input').val(),
								thumbnail = wrp.find('.kc-ss-thumbnail img').attr('src');
							
							if (name.trim() === '') {
								wrp.find('.kc-ss-name input').shake();
								return;
							}
							
							if (email.trim() === '' || email.indexOf('@') === -1 || email.indexOf('.') === -1) {
								wrp.find('.kc-ss-email input').shake();
								return;
							}
							
							kc.msg('Processing', 'loading');
							
							$.post(

								kc_ajax_url,
				
								{
									'action': 'kc_share_section',
									'security': kc_ajax_nonce,
									'id' : id,
									'label' : label,
									'name' : name,
									'email' : email,
									'thumbnail' : thumbnail,
								},
				
								function (result) {
									
									if (result == '-1') {
										kc.msg('Error: secure session is invalid. Reload and try again', 'error');
									}else if (result.stt === undefined){
										kc.msg('Error: unknow reason', 'error');
									}else if(result.stt == '0'){
										kc.msg('Error: '+result.msg, 'error');
									}else {
										kc.msg(result.msg, 'success', 'sl-check', 2000);
										$('.kc-section-share').hide();
									}
				
								}
							).complete(function( data ) {
								
							    if(data.status !== 200) {
								    kc.msg( 'Please check all of your code and make sure there are no errors. ', 'error', 'sl-close' );
							    }
							});
							
						},
						
						cancel_share : function (e) {
							$(this).closest('.kc-section-share').hide();
							e.preventDefault();
							return false;
						}
						
						
					});
					
					wrp.on('click', function(e){
						
						switch( $(e.target).data('action') ){
							
							case 'link': kc.do_action('kc-link-section', e); break;
							case 'clone': kc.do_action('kc-clone-section', e); break;
							
							case 'push': kc.ui.sections.push(e, false); break;
							case 'overwrite': kc.ui.sections.push(e, true); break;
							
							case 'delete': kc.ui.sections.delete(e); break;
							case 'page': kc.ui.sections.page(e); break;
							
							case 'prebuilt': kc.ui.sections.prebuilt(e); break;
							case 'share': kc.ui.sections.share(e); break;
							
						}
						
					});
					
					wrp.find('.pages-select select').on('change', function(e){
						kc.ui.sections.reload(e, this.value);
					});
					
					data.pop.find('h3.m-p-header').append(wrp.find('.kc-section-control'));
					
					if (data.pop.hasClass('kc-save-row-to-section')) {
						data.pop.find(".kc-section-control select.content-type option[value^='prebuilt-templates-']").remove();
					}
					
				},
				
				reload : function( e, paged, isdelete ){
					
					var pop = e.target ? kc.get.popup(e.target) : e,
						s = pop.find('.kc-section-control .keyword').val(),
						term = pop.find('.kc-section-control .category').val(),
						type = pop.find('.kc-section-control .content-type').val(),
						per_page = pop.find('.kc-section-control .more-options .items-per-page li.active').data('amount'),
						cols = pop.find('.kc-section-control .more-options .grid-columns li.active').data('amount');
					
					if( per_page === undefined && kc.cfg.sectionsPerpage !== undefined ){
						per_page = kc.cfg.sectionsPerpage;
					}	
					
					if( cols === undefined && kc.cfg.sectionsLayout !== undefined ){
						cols = kc.cfg.sectionsLayout;
					}
					
					if( term === undefined && kc.cfg.sectionsTerm !== undefined ){
						term = kc.cfg.sectionsTerm;
					}
					
					if( type === undefined && kc.cfg.sectionsType !== undefined ){
						type = kc.cfg.sectionsType;
					}
					
					if( paged === undefined )
						paged = 1;
					
					pop.find('.m-p-wrap.wp-pointer-content')
					   .append('<div class="kc-popup-loading" style="display:block"><span class="kc-loader"></span></div>');
					
					$.ajax({
				    	url: ajaxurl,
					    data: {
						    security: kc_ajax_nonce,
						    action: 'kc_load_sections',
						    s: s,
						    term: term,
						    type: type,
						    paged: paged,
						    per_page: per_page,
						    cols: cols,
						    isdelete: isdelete
					    },
					    pop: pop,
					    method: 'POST',
						dataType: 'json',
					    success: function( json ){
							
							this.pop.find('.kc-popup-loading').remove();
						   	
						    if( json !== -1 && json != '-1' ){
							  
								  	kc.sections = json.data;
								  	kc.ui.sections.render( this.pop );
							  	
							  	if( json.stt !== 1 )
								  	this.pop.find('.m-p-body').html('<p class="align-center">'+json.message+'<p>');
							  	
							}else{
								kc.msg( kc.__.security, 'error', 'sl-close' );
							}
							
						}
					});
						
				},
				
				link : function( e ){
					
					var id = $(e.target).closest('.kc-sections-item').data('id');
						title = $(e.target).closest('.kc-sections-item').data('title'),
						pop = kc.get.popup(e.target),
						model = pop.data('model');
					
					if( window.kc_post_ID !== undefined && window.kc_post_ID == id ){
						kc.msg( kc.__.i62, 'error', 'sl-close', 5000 );
						return;
					}
						
					pop.find('button.cancel').trigger('click');
					
					if( model !== undefined && model !== null ){
					
						var row = $('#kc-rows #model-'+model);
						
						kc.storage[model].args.__section_link = id;
						kc.storage[model].args.__section_title = title;
						
						var new_row = kc.views.row.render( kc.storage[model] );
						
						row.after( new_row ).remove();
						
						kc.ui.scrollAssistive( new_row );
					
					}else kc.backbone.push('[kc_row __section_link="'+id+'" __section_title="'+title+'"][/kc_row]');
						
				},
				
				clone : function( e ){
					
					var id = $(e.target).closest('.kc-sections-item').data('id');
					
					kc.get.popup(e.target, 'close').trigger('click');
					
					kc.msg( kc.__.processing, 'loading' );
					
					$.ajax({
				    	url: ajaxurl,
					    data: {
						    security: kc_ajax_nonce,
						    action: 'kc_load_section',
						    id: id
					    },
					    method: 'POST',
						dataType: 'json',
					    success: function( json ){	
						   
						    if( json !== -1 && json != '-1' ){
							  	if( json.stt === 1 ){
								  	kc.backbone.push( json.data );
								  	$('#kc-preload').remove();
							  	}else{
								  	kc.msg( json.message, 'error', 'sl-close' );
							  	}
							  	
							}else{
								kc.msg( kc.__.security, 'error', 'sl-close' );
							}
							
						}
					});
						
					
				},
				
				push : function( e, overwrite ){
					
					var pop = kc.get.popup( e.target ),
						model = pop.data('model'),
						id = $(e.target).closest('.kc-sections-item').data('id'),
						full = '';
						
						
					if( kc.front !== undefined ){
						full = kc.front.build_shortcode( model );
					}else{
						
						if( model == 'all' ){
							
							var exp;
											 
							$('#kc-container > #kc-rows > .kc-row').each(function(){
								exp =  kc.backbone.export( $(this).data('model') );
								full += exp.begin+exp.content+exp.end;
							});
							
							if( full === '' ){
								alert('__EMPTY_ERROR');
								return false;
							}
							
						}else{
							full = kc.backbone.export( model );
							full = full.begin+full.content+full.end;
						}
					}
					
					kc.get.popup(e.target, 'close').trigger('click');
					
					kc.msg( kc.__.processing, 'loading' );
					
					$.ajax({
				    	url: ajaxurl,
					    data: {
						    security: kc_ajax_nonce,
						    action: 'kc_push_section',
						    content: full,
						    id: id,
						    overwrite: (overwrite) ? 'true' : 'false'
					    },
					    method: 'POST',
						dataType: 'json',
					    success: function( json ){	
						   
						    if( json !== -1 && json != '-1' ){
							  	if( json.stt === 1 ){
								  	kc.msg( json.message, 'success', 'sl-check' );
							  	}else{
								  	kc.msg( json.message, 'error', 'sl-close' );
							  	}
							  	
							}else{
								kc.msg( kc.__.security, 'error', 'sl-close' );
							}
							
						}
					});

					
				},
				
				delete : function( e ){
					
					if( confirm( kc.__.sure ) ){
					
						var id = $(e.target).closest('.kc-sections-item').data('id');
					
						this.reload( e, 1, id );
					
					}
					
					e.preventDefault();
					return false;
				},
				
				page : function (e) {
					
					var el = $(e.target),
						paged = el.html(),
						active = el.closest('.kc-section-pagination').find('.active');
						
					if( el.data('page') == 'next' ){
						
						if( active.next().data('page') != 'next' )
							kc.ui.sections.reload( e, active.next().html() );

					}else if( el.data('page') == 'prev' ){
						
						if( active.prev().data('page') != 'prev' )
							kc.ui.sections.reload( e, active.prev().html() );
		
					}else if( !el.hasClass('active') ){
						
						kc.ui.sections.reload( e, el.html() );
						
					}
					
				},
				
				prebuilt : function( e ){
					
					var id = $(e.target).closest('.kc-sections-item').data('id'),
						pack = $(e.target).closest('.kc-params-popup').find('.kc-section-control select.category').val();
						
					kc.get.popup(e.target, 'close').trigger('click');
					
					kc.msg( kc.__.processing, 'loading' );
					
					$.ajax({
				    	url: ajaxurl,
					    data: {
						    security: kc_ajax_nonce,
						    action: 'kc_load_section',
						    id: id,
						    xml_pack: pack,
					    },
					    method: 'POST',
						dataType: 'json',
					    success: function( json ){	
						   
						    if( json !== -1 && json != '-1') {
							  	if (json.stt === 1) {
								  	
								  	kc.backbone.push( json.data );
								  	
								  	if (typeof json.meta == 'object') {
									  	for (var n in json.meta) {
										  	if (json.meta[n] !== '' && $('#kc-page-cfg-'+n).length > 0) {
											  	$('#kc-page-cfg-'+n).val(json.meta[n]);
										  	}
									  	}
								  	}
								  	
								  	$('#kc-preload').remove();
								  	
							  	}else{
								  	kc.msg( json.message, 'error', 'sl-close' );
							  	}
							  	
							}else{
								kc.msg( kc.__.security, 'error', 'sl-close' );
							}
							
						}
					});
						
					
				},
					
				share : function (e) {
					
					var id = $(e.target).closest('.kc-sections-item').data('id'), 
						label = $(e.target).closest('.kc-section-info').find('>span').html(),
						thumbn = $(e.target).closest('.kc-sections-item').find('>.kc-section-sceenshot img').attr('src'),
						sswrp = $(e.target).closest('#kc-sections').find('.kc-section-share');

					$(e.target).closest('.m-p-body').scrollTop('1px');
					
					sswrp.show().find('.kc-ss-section span').html(label);
					sswrp.find('.kc-ss-thumbnail').html('<img src="'+thumbn+'" />');
					sswrp.data({id: id, label: label});
					
					sswrp.find('.kc-ss-name input,.kc-ss-email input').val('').first().focus();
					
					if ($('#kc-sections').height() < 550)
						$('#kc-sections').height('550px');
					
					e.preventDefault();
					return false;
				}
				
			},
			
			scrollAssistive : function( ctop, eff ){

				if( kc.cfg.scrollAssistive != 1 )
					return false;

				if( typeof ctop == 'object'  ){
					if( $(ctop).get(0) ){
						var coor = $(ctop).get(0).getBoundingClientRect();
						ctop = (coor.top+$(window).scrollTop()-100);
					}
				}
				
				if( undefined !== eff && eff === false )
					$('html,body').scrollTop( ctop );
				else $('html,body').stop().animate({ scrollTop : ctop });

			},

			preventScroll : function( el ){
			
				if( kc.cfg.preventScrollPopup == 1 && $.browser.chrome === true ){
						
					el.addClass('kc-prevent-scroll');

					el.off('mousewheel DOMMouseScroll').on( 'mousewheel DOMMouseScroll',
						
						function ( e ) {
							
						    if( this.scrollHeight > this.offsetHeight ){
								
								if( $('body').hasClass('kc-ui-dragging') )
									return true;
									
							    var curS = this.scrollTop;
							    if( this.scrollCalc === undefined )
							    	this.scrollCalc = 0;
	
							    var e0 = e.originalEvent,
							        delta = e0.wheelDelta || -e0.detail;
								
								if( delta !== 0 ){
								
								    //this.scrollTop += ( delta <= 0 ? 1 : -1 ) * e.data.st;
								    this.scrollTop -= delta;
								    
									if( curS == this.scrollTop ){
										
										var pop = this.parentNode.parentNode,
											top = pop.offsetTop - 80,
											bottom = pop.offsetTop + ( pop.offsetHeight - window.innerHeight ) + 100;
										
										if( delta < 0 ){
											//scroll down
											
											if( kc.body.scrollTop - delta < bottom )
												kc.body.scrollTop -= delta;
											else kc.body.scrollTop = bottom;
											
											if( kc.html.scrollTop - delta < bottom )
												kc.html.scrollTop -= delta;
											else kc.html.scrollTop = bottom;
											
										}else{
											
											if( kc.body.scrollTop - delta > top )
												kc.body.scrollTop -= delta;
											else kc.body.scrollTop = top;
											
											if( kc.html.scrollTop - delta > top )
												kc.html.scrollTop -= delta;
											else kc.html.scrollTop = top;
										}
										
									}
									
								}
								
								if( e.target !== null && ( e.target.tagName === 'OPTION' || e.target.tagName === 'SELECT' || e.target.className.indexOf('kc-free-scroll') > -1 ) ){
									return true;
								}
								
								e.preventDefault();
								e.stopPropagation();
								
								return false;
								
						    }

					});

				}
			},

			scroll : function( st ){

				if( typeof st == 'object' ){

					if( st.top !== undefined ){
						kc.body.scrollTop = st.top;
						kc.html.scrollTop = st.top;
					}

					if( st.left !== undefined ){
						kc.body.scrollLeft = st.left;
						kc.html.scrollLeft = st.left;
					}

				}else{
					return { top: (kc.body.scrollTop?kc.body.scrollTop:kc.html.scrollTop),
						 left: (kc.body.scrollLeft?kc.body.scrollLeft:kc.html.scrollLeft)};
				}
			},
			
			verify_tmpl : function( refresh = false ){
				
				
				var cfg = $().extend( kc.cfg, kc.backbone.stack.get('KC_Configs') );
				
				if( cfg.version != kc_version || refresh || localStorage['KC_TMPL_CACHE'] === undefined || localStorage['KC_TMPL_CACHE'] === ''){
					
					if( !refresh) kc.msg( 'KingComposer is initializing', 'loading' );
					
					$.post(
		
						kc_ajax_url,
		
						{
							'action': 'kc_tmpl_storage',
							'security': kc_ajax_nonce
						},
		
						function (result) {
							
							$('#kc-preload').remove();
							
							if( result != -1 && result != 0 ){
								
								kc.ui.upgrade_notice( parseFloat(cfg.version) );
								
								cfg.version = kc_version;
								
								localStorage.clear();
								
								kc.backbone.stack.set( 'KC_Configs', cfg );
								kc.backbone.stack.set( 'KC_TMPL_CACHE', result );
								
								kc.init();
								
							}
							
						}
					);
				
				}else if( !kc.ui.check_tmpl() ){
					return false;
				} else return true;
				
			},
			
			get_tmpl_cache : function( tmpl_id ){
				
				if( localStorage['KC_TMPL_CACHE'] !== undefined && localStorage['KC_TMPL_CACHE'].indexOf('id="'+tmpl_id+'"') > -1 ){
					
					var s1 = localStorage['KC_TMPL_CACHE'].indexOf('>', localStorage['KC_TMPL_CACHE'].indexOf('id="'+tmpl_id+'"') )+1,
						s2 = localStorage['KC_TMPL_CACHE'].indexOf('</script>', s1),
						string = localStorage['KC_TMPL_CACHE'].substring( s1, s2 ),
						options = {
                            evaluate:    /<#([\s\S]+?)#>/g,
                            interpolate: /\{\{\{([\s\S]+?)\}\}\}/g,
                            escape:      /\{\{([^\}]+?)\}\}(?!\})/g,
                            variable:    'data'
                        };
						
	                return _.template( string, null, options );
					
				}
				
				return 'exit';	
				
			},
			
			check_tmpl : function (call_init){
				
				var tmpl = localStorage.getItem('KC_TMPL_CACHE');
				
				if (tmpl === undefined || tmpl === '' || tmpl === null || tmpl.indexOf('<!----END_KC_TMPL---->') === -1) {
					kc.msg( 'KingComposer is refreshing', 'loading' );
					kc.ui.verify_tmpl( true );
					return false;
				}
				
				return true;
				
			},
			
			uncache : function(){
			
				localStorage.removeItem('KC_TMPL_CACHE');
				window.location.href = window.location.href;
				
				return 'Successfull, reloading now...';
				
			},
			
			kc_box : {

				sort : function(){

					kc.ui.sortable({

					    items : '.kc-box:not(.kc-box-column)',
					    connecting : true,
					    handle : '>ul.mb-header',
					    helper : ['kc-ui-handle-image', 25, 25 ],
					    detectEdge: 30

				    });

				    if( window.chrome === undefined ){

						 $('.kc-box-body .kc-box-inner-text').off('mousedown').on( 'mousedown', function( e ){
								var el = this;
								while( el.parentNode ){
									el = el.parentNode;
								  	if( el.draggable === true ){
								  		el.draggable = false;
								  		el.templDraggable = true;
								  	}
								}
							}).off('blur').on( 'blur', function( e ){
								var el = this;
								while( el.parentNode ){
									el = el.parentNode;
								  	if( el.templDraggable === true ){
								  		el.draggable = true;
								  		el.templDraggable = null;
								  	}
								}
							});

					}

				},

				renderBack : function( pop ){

					var exp = JSON.stringify(
						kc.ui.kc_box.accessNodesVisual( pop.find('.kc-box-render') )
					).toString(), rex = new RegExp( kc_site_url, "g");
					
					exp = exp.replace( rex, '%SITE_URL%' );
					
					pop.find('.kc-param.kc-box-area').val( kc.tools.base64.encode( exp ) );

				},

				wrapFreeText : function( el ){

					var nodes = el.childNodes, text, n, ind;

					if( nodes === undefined )
						return null;

					for( var i=0; i < nodes.length; i++ ){
						/* node text has type = 3 */
						
						n = nodes[i];
						
						if( nodes[i].nodeType == 3 ){

							if( n.parentNode.tagName != 'TEXT' && n.textContent.trim() !== '' ){

								text = document.createElement('text');

								if( n.nextElementSibling !== null )
									$( n.nextElementSibling ).before( text );
								else if( n.previousElementSibling !== null )
									$( n.previousElementSibling ).after( text );
								else n.parentNode.appendChild( text );

								text.appendChild(n);

							}
						}else{

							if( ['input', 'br', 'select', 'textarea', 'button'].indexOf( nodes[i].tagName.toLowerCase() ) > -1 ){

								ind = false;

								if( n.previousElementSibling !== null ){
									if( n.previousElementSibling.tagName == 'TEXT' ){
										$( n.previousElementSibling ).append( nodes[i] );
										ind = true;
									}
								}if( n.nextElementSibling !== null ){
									if( n.nextElementSibling.tagName == 'TEXT' ){
										$( n.nextElementSibling ).prepend( nodes[i] );
										ind = true;
									}
								}

								if( ind === false ){

									text = document.createElement('text');
									$( nodes[i] ).after(text);

									text.appendChild( nodes[i] );

								}

							}else kc.ui.kc_box.wrapFreeText( nodes[i] );
						}
					}

					return el;

				},

				accessNodes : function( node, thru ){

					if( node === null )
						return [];

					var nodes = node.childNodes, nod, ncl, atts;

					if( thru === undefined )
						thru = [];

					if( nodes === null )
						return thru;

					for( var i=0; i < nodes.length; i++ ){
						/* node element has type = 1 */
						if( nodes[i].nodeType == 1 ){

							atts = {};

							for( var j=0; j< nodes[i].attributes.length; j++ ){
								atts[ nodes[i].attributes[j].name ] = nodes[i].attributes[j].value;
							}

							nod = {
								tag : nodes[i].tagName.toLowerCase(),
								attributes : atts,
							};

							if( nod.tag != 'text' )
								nod.children = kc.ui.kc_box.accessNodes( nodes[i] );

							ncl = ( typeof( nodes[i].className ) != 'undefined' ) ? nodes[i].className : '';

							if( nod.tag == 'text' )
								nod.content = nodes[i].innerHTML;
							else if( nod.tag == 'img' )
								nod.tag = 'image';
							else if( ncl.indexOf('fa-') > -1 || ncl.indexOf('et-') > -1 || ncl.indexOf('sl-') > -1 )
								nod.tag = 'icon';
							else if( nod.tag == 'column' ){
								if( ncl === '' )
									ncl = 'one-one';
								['one-one','one-second','one-third','two-third'].forEach(function(c){
									if( ncl.indexOf( c ) > -1 ){
										ncl = ncl.replace( c, '').trim();
										nod.attributes.cols = c;
										nod.attributes.class = ncl;
									}
								});
							}

							thru[ thru.length ] = nod;

						}
					}

					return thru;

				},

				accessNodesVisual : function( wrp ){

					var nodes = wrp.find('>.kc-box:not(.mb-helper)'), nod, thru = [];

					if( nodes.length === 0 )
						return thru;

					nodes.each(function(){

						nod = {
								tag : $(this).data('tag'),
								attributes : $(this).data('attributes'),
								children : kc.ui.kc_box.accessNodesVisual( $(this).find('>.kc-box-body') )
							};

						if( nod.attributes === undefined )
							nod.attributes = {};
						
						if( nod.tag == 'text' )
							nod.content = $(this).find('.kc-box-inner-text').html();
						else if( nod.tag == 'icon' )
							nod.attributes.class = $(this).find('>.kc-box-body i').attr('class');
						else if( nod.tag == 'image' )
							nod.attributes.src = $(this).find('>.kc-box-body img').attr('src');

						thru[ thru.length ] = nod;

					});

					return thru;

				},

				exportCode : function( visual, cols ){

					var thru = '';
					if( cols === undefined )
						cols = '';
					var incol = cols+'	', count = 0;

					visual.forEach(function(n){

						if( n.tag == 'text' ){
							if( n.content !== '' )
								thru += cols+'<text>'+n.content.trim().replace(/\<text\>/g,'').replace(/\<\/text\>/g,'')+'</text>';
						}else{
							if( n.attributes.cols == 'one-one' ){
								if( n.children.length > 0 ){
									thru += kc.ui.kc_box.exportCode( n.children, cols );
								}
							}else{

								if( n.attributes.cols !== undefined ){
									n.attributes.class = ( n.attributes.class !== undefined ) ?
														 (n.attributes.class+' '+n.attributes.cols) : n.attributes.cols;
									delete n.attributes.cols;
								}

								thru += cols+'<'+n.tag;
								for( var i in n.attributes )
									thru += ' '+i+'="'+kc.tools.esc(n.attributes[i])+'"';
								thru += '>';
								if( n.children.length > 0 ){
									thru += "\n"+kc.ui.kc_box.exportCode( n.children, incol )+"\n"+cols;
								}

								thru += '</'+n.tag+'>';

							}
						}
						if( count++ < visual.length-1 )
							thru += "\n";

					});

					return thru;

				},

				setColumns : function( e ){

					var el = kc.get.popup( this ).data('el').closest('.kc-box'),
						wrp = el.find('>.kc-box-body'),
						cols = $(this).data('cols').split(' '),
						objCols = wrp.find('>.kc-box.kc-box-column'),
						elms, colElm, i, j, atts;

					for( i=0; i<cols.length; i++ ){

						if( objCols.get(i) ){

							objCols
							.eq(i)
							.attr({ 'class' : 'kc-box kc-box-column kc-column-'+cols[i] })
							.data('attributes').cols = cols[i];

						}else{
							wrp.append(
								kc.template(
									'box-design', [{ tag: 'column', attributes: { cols: cols[i] } }]
								)
							);
						}
					}
					if( i<objCols.length ){

						for( j = i; j < objCols.length; j++ ){
							objCols.eq(j).find('>.kc-box-body>.kc-box:not(.mb-helper)').each(function(){
								objCols.eq(i-1).append(this);
							});
							objCols.eq(j).remove();
						}

					}

					kc.get.popup( this, 'close' ).trigger('click');

					kc.ui.kc_box.sort();

				},

				actions : function( el, e ){

					var wrp = el.closest('.kc-param-row').find('.kc-box-render'), pos, btns, pop, cols, atts;

					switch( el.data('action') ){

						case 'add' :

							$('.kc-box-subPop').remove();
							el.closest('.mb-header').addClass('editting');
							pos = el.data('pos');
							btns = '<div class="kc-nodes">';
							pop = kc.tools.popup.render( el.get(0), {
								title: 'Select Node Tag',
								class: 'no-footer kc-nodes kc-box-subPop',
								scrollBack: true,
								keepCurrentPopups: true,
								drag: false,
							});

							pop.data({ pos: pos, el: el, cancel: function( pop ){
								pop.data('el').closest('.mb-header').removeClass('editting');
							} });

							['text','image','icon', 'div','span','a','ul','ol','li','p','h1','h2','h3','h4','h5','h6']
							.forEach(function(n){
								btns += '<button class="button">'+n+'</button> ';
							});

							btns += '</div>';

							btns = $(btns);

							pop.find('.m-p-body').append( btns );

							btns.find('button').on('click', function(e){

								var html = kc.template( 'box-design', [{ tag: this.innerHTML }] ),
									pop = kc.get.popup( this ),
									pos = pop.data('pos'),
									el = pop.data('el');

								if( pos == 'top' )
									wrp.prepend( html );
								else if( pos == 'bottom' )
									wrp.append( html );
								else if( pos == 'inner' ){
									el.closest('.kc-box:not(.mb-helper)').find('>.kc-box-body').append( html );
								}

								kc.ui.kc_box.sort();

								kc.get.popup( this, 'close' ).trigger('click');

								e.preventDefault();
								return false;

							});

							e.preventDefault();

						break;

						case 'columns' :

							$('.kc-box-subPop').remove();
							el.closest('.mb-header').addClass('editting');
							btns = '<div class="kc-nodes">';
							pop = kc.tools.popup.render( el.get(0), {
								title: 'Select Layout - Columns',
								class: 'no-footer kc-nodes kc-columns kc-box-subPop',
								scrollBack: true,
								keepCurrentPopups: true,
								drag: false,
							});

							pop.data({ el: el, cancel: function( pop ){
								pop.data('el').closest('.mb-header').removeClass('editting');
							} });

							[['one-one','1/1'],
							 ['one-second one-second','1/2 + 1/2'],
							 ['one-third two-third','1/3 + 2/3'],
							 ['two-third one-third','2/3 + 1/3'],
							 ['one-third one-third one-third','1/3 + 1/3 + 1/3']].forEach(function(n){
								btns += '<button data-cols="'+n[0]+'" class="button '+n[0].replace(' ','')+
									'"><span>'+n[1]+'</span></button> ';
							});

							btns += '</div>';

							btns = $(btns);

							pop.find('.m-p-body').append( btns );

							btns.find('button').on('click', kc.ui.kc_box.setColumns );

							e.preventDefault();

						break;

						case 'remove' :

							if( el.closest('.kc-box').data('tag') == 'column' ){

								if( el.closest('.kc-box').find('>.kc-box-body>.kc-box:not(.mb-helper)').length > 0 ){
									if( !confirm( kc.__.i23 ) )
										return;
								}

								cols = el.closest('.kc-box').parent().get(0);
								el.closest('.kc-box').remove();
								var _cols = $(cols).find('>.kc-box.kc-box-column'), _clas = 'one-one';

								if( _cols.length == 2 )
									_clas = 'one-second';

								_cols.each(function(){
									$(this).attr({ 'class' : 'kc-box kc-box-column kc-column-'+_clas })
										   .data('attributes').cols = _clas;
								});

								return;
							}

							var trash = el.closest('.kc-param-row').find('.kc-box-trash'),
								item = el.closest('.kc-box').get(0);

							pos = {};

							pos.parent = item.parentNode;
							if( item.nextElementSibling )
								pos.next = item.nextElementSibling;
							if( item.previousElementSibling )
								pos.prev = item.previousElementSibling;

							$(item).data({ pos : pos });

							trash.append( item );
							trash.find('a.button')
							.html('<i class="sl-action-undo"></i> '+kc.__.i24+'('+trash.find('>.kc-box').length+')')
							.removeClass('forceHide');


						break;

						case 'undo' :

							trash = el.closest('.kc-param-row').find('.kc-box-trash');
							var last = trash.find('>.kc-box').last().get(0);
							pos = $(last).data('pos');

							if( !last )
								return;

							if( pos.next !== undefined )
								$(pos.next).before( last );
							else if( pos.prev !== undefined )
								$(pos.prev).after( last );
							else if( pos.parent !== undefined )
								$(pos.parent).append( last );

							var nu = trash.find('>.kc-box').length;

							trash.find('a.button')
							.html('<i class="sl-action-undo"></i> '+kc.__.i24+'('+nu+')');

							if( nu === 0 )
								trash.find('a.button').addClass('forceHide');

							e.preventDefault();

						break;

						case 'double' :

							var clone = el.closest('.kc-box').clone(true);
							clone.attr({draggable:'',dropable:''});
							clone.find('.kc-box').attr({draggable:'',dropable:''});

							el.closest('.kc-box').after( clone );
							kc.ui.kc_box.sort();

						break;

						case 'settings' :

							$('.kc-box-subPop').remove();
							el.closest('.mb-header').addClass('editting');
							atts = el.closest('.kc-box').data('attributes');
							pop = kc.tools.popup.render( el.get(0), {
								title: 'Node Settings',
								class: 'kc-box-settings-popup kc-box-subPop',
								scrollBack: true,
								keepCurrentPopups: true,
								drag: false,
							});

							pop.data({

								model : null,

								el: el,

								cancel : function( pop ){

									pop.data('el').closest('.mb-header').removeClass('editting');

								},

								callback : function( pop ){

									pop.data('el').closest('.mb-header').removeClass('editting');

									var el = pop.data('el').closest('.kc-box'),
										attrs = {};

									pop.find('.fields-edit-form .kc-param').each(function(){
										if( this.value !== '' )
											attrs[ this.name ] = kc.tools.esc( this.value );
									});

									if( pop.data('css') !== undefined && pop.data('css') !== '' )
										attrs.style = pop.data('css');

									if( el.data('attributes').cols !== undefined )
										attrs.cols = el.data('attributes').cols;

									el.data({ attributes : attrs });

									['id','class','href'].forEach(function(n){
										if( attrs[n] !== undefined ){
											var elm = el.find('>.mb-header>.mb-'+n), str = attrs[n].substr(0,30)+'..';

											if( elm.length > 0 )
												elm.find('span').html( str ).attr({title:attrs[n]});
											else
												el.find('>.mb-header>.mb-funcs')
													.before('<li class="mb-'+n+'">'+n+
													': <span title="'+kc.tools.esc(attrs[n])+'">'+str+'</span></li>');
										}
									});

								},

								css : ( typeof( atts.style ) != 'undefined' ) ? atts.style : ''

							});

							wrp = $('<div class="fields-edit-form kc-pop-tab form-active"></div>');

							var form = $('<form class="attrs-edit"><input type="submit" class="forceHide" /></form>'),

								field = function( n, v ){
									var field = $('<div class="kc-param-row"><div class="m-p-r-label"><label>'+
										   kc.tools.esc(n)+':</label></div><div class="m-p-r-content"><input name="'+
										   kc.tools.esc(n)+'" class="kc-param" value="'+
										   v+'" style="width:90%;" type="text">'+
										   ' &nbsp; <a href="#"><i class="fa-times"></i></a></div></div>');
									field.find('a').on('click', function(e){
										$(this).closest('.kc-param-row').remove();
										e.preventDefault();
									});

									return field;

								},

								addInput = function(){

									var add = $('<div style="padding: 10px 0 10px" class="kc-param-row align-right"><div class="m-p-r-label"></div><form class="m-p-r-content">'+
									'<input style="height: 34px;width: 52.5%;" type="text" placeholder="'+kc.__.i25+'" /> '+
									'<button style="margin-right: 33px;height: 34px;" class="button button-primary">'+kc.__.i26+'</button>'+
									'<input type="submit" class="forceHide" /></form></div>');

									add.find('button').on('click', function(e){

										var input = $(this.parentNode).find('input'),
											val = input.val().replace(/[^a-z-]/g,'');

										input.val('');

										if( val === '' ||
											$(this).closest('.m-p-body').find('input[name='+val+']').length > 0 ||
											val == 'style' ){

											$(this).stop()
												  .animate({marginRight:50},100)
												  .animate({marginRight:28},100)
												  .animate({marginRight:38},80)
												  .animate({marginRight:30},80)
												  .animate({marginRight:33},50);
											return false;
										}

										$(this).closest('.kc-param-row').before( field(val,'') );

										e.preventDefault();
										return false;
									});

									add.find('form').on('submit',function(){
										$(this).find('button').trigger('click');
										return false;
									});

									return add;
								};

							form.append( field( 'id', ( typeof( atts['id'] ) != 'undefined' ) ? atts['id'] : '' ) );
							form.append( field( 'class', ( typeof( atts['class'] ) != 'undefined' ) ? atts['class'] : '' ) );

							if( el.closest('.kc-box').get(0).tagName == 'A' )
								form.append( field( 'href', ( typeof( atts['href'] ) != 'undefined' ) ? atts['href'] : '' ) );

							for( var i in atts ){
								if( i != 'id' && i != 'class' && i != 'style' && i != 'cols' )
									form.append( field( i, atts[i] ) );
							}

							wrp.append( form );
							wrp.append( addInput() );

							form.on( 'submit', function(e)
							{
								kc.get.popup( this, 'save' ).trigger('click');
								e.preventDefault();
								return false;
							});

                            pop.find('.m-p-body').append(wrp);
						break;

						case 'editor' :

							$('.kc-box-subPop').remove();
							el.closest('.mb-header').addClass('editting');
							atts = el.closest('.kc-box').data('attributes');
							pop = kc.tools.popup.render( el.get(0), {
								title: 'Node Settings',
								class: 'kc-box-editor-popup kc-box-subPop',
								scrollBack: true,
								keepCurrentPopups: true,
								drag: false,
								width: 750
							});

							pop.data({

								model : null,

								el: el,

								cancel : function( pop ){

									pop.data('el').closest('.mb-header').removeClass('editting');

								},

								callback : function( pop ){
									
									var txt = pop.find('textarea.kc-param'), content = txt.val().toString().trim();
									
									if( pop.find('.wp-editor-wrap').hasClass('tmce-active') )
										content = tinyMCE.get(txt.attr('id')).getContent();
									
									var inner = pop.data('el').closest('.kc-box').find('.kc-box-inner-text');
									
									inner.html( content );

								}

							});

							atts = {

								value 	: el.closest('.kc-box').find('.kc-box-inner-text').html(),
								options : [],
								name	: 'content',
								type	: 'textarea_html'

							};
							field = kc.template( 'field', {
									label: '',
									content: kc.template( 'field-type-textarea_html', atts ),
									des: '',
									name: 'textarea_html',
									base: 'content'
							});

                            pop.find('.m-p-body').append(field);

							if( typeof atts.callback == 'function' ){
								/* callback from field-type template */
								setTimeout( atts.callback, 1, pop.find('.m-p-body'), $ );
							}

						break;

						case 'toggle' :

							wrp = el.closest('.kc-box');
							if( wrp.hasClass('kc-box-toggled') )
								wrp.removeClass('kc-box-toggled');
							else wrp.addClass('kc-box-toggled');

						break;

						case 'html-code' :

							$('.kc-box-html-code').remove();

							atts = {
								title: kc.__.i29,
								width: 700,
								class: 'kc-box-html-code',
								keepCurrentPopups: true,
								drag : false
							};

							pop = kc.tools.popup.render( el.get(0), atts );
							pop.data({ target: el, scrolltop: $(window).scrollTop() });

							/*Render from Visual*/
							var code = kc.ui.kc_box.exportCode(
								kc.ui.kc_box.accessNodesVisual(
									kc.get.popup(el).find('.kc-box-render')
								)
							);

							pop.find('.m-p-body').html('<textarea>'+code+'</textarea>');

							pop.data({ popParent : kc.get.popup( el ), callback : function( pop ){

								var code = '<div>'+pop.find('.m-p-body textarea').val().trim()+'</div>',
									visual = kc.ui.kc_box.wrapFreeText( $( code ).get(0) ),
									items = kc.ui.kc_box.accessNodes( visual ),
									popParent = pop.data('popParent');

								popParent.find('.kc-box-render').html(
									kc.template( 'box-design', items )
								);

								kc.ui.kc_box.sort();

								/* Clear Trash */
								popParent.find('.kc-box-trash .kc-box').remove();
								popParent.find('.kc-box-trash>a.button').addClass('forceHide');

							} });

						break;

						case 'css-code' :

							$('.kc-box-html-code').remove();

							atts = {
								title: kc.__.i30,
								width: 700,
								class: 'kc-box-html-code',
								keepCurrentPopups: true,
								drag : false
							};

							var popParent = kc.get.popup( el );

							pop = kc.tools.popup.render( el.get(0), atts );
							pop.data({ target: el, scrolltop: $(window).scrollTop() });

							var css = popParent.find('.field-hidden.field-base-css_code input').val(), css_code = '';
							
							pop.find('.m-p-body').html('<p></p><textarea>'+kc.tools.decode_css( css )+'</textarea><i class="ntips">'+kc.__.i31+'</i>');

							var btn = $('<button class="button button-larger"><i class="sl-energy"></i> '+kc.__.i32+'</button>');

							pop.find('.m-p-body').prepend( btn );

							btn.on( 'click', function(){
								var txta = $(this).parent().find('textarea');
								txta.val( kc.tools.decode_css( txta.val() ) );
							});

							pop.data({ popParent : kc.get.popup( el ), callback : function( pop ){


								var css = kc.tools.encode_css( pop.find('textarea').val() );

								pop.data('popParent').find('.field-hidden.field-base-css_code input').val( css );

							} });

						break;

						case 'icon-picker' :

							$('.kc-icons-picker-popup,.kc-box-subPop').remove();

							var listObj = $( '<div class="icons-list noneuser">'+kc.tools.get_icons()+'</div>' );

							atts = { title: 'Select Icons', width: 600, class: 'no-footer kc-icons-picker-popup kc-box-subPop', keepCurrentPopups: true };
							pop = kc.tools.popup.render( el.get(0), atts );
							pop.data({ target: el, scrolltop: jQuery(window).scrollTop() });

							var search = $( '<input type="search" class="kc-components-search" placeholder="Search by Name" />' );
							pop.find('.m-p-header').append(search);
							search.after('<i class="sl-magnifier"></i>');
							search.data({ list : listObj });

							search.on( 'keyup', listObj, function( e ){

								clearTimeout( this.timer );
								this.timer = setTimeout( function( el, list ){
									var sr;
									if( list.find('.seach-results').length === 0 ){

										sr = $('<div class="seach-results"></div>');
										list.prepend( sr );

									}else sr = list.find('.seach-results');

									var found = ['<span class="label">'+kc.__.i33+'</span>'];
									list.find('>i').each(function(){

										if( this.className.indexOf( el.value.trim() ) > -1 &&
											found.length < 14 &&
											$.inArray( this.className, found )
										)found.push( '<span data-icon="'+this.className+'"><i class="'+this.className+'"></i>'+this.className+'</span>' );

									});
									if( found.length > 1 ){
										sr.html( found.join('') );
										sr.find('span').on('click', function(){
											var tar = kc.get.popup(this).data('target');
											tar.find('i').attr({ class : $(this).data('icon') });
											kc.get.popup(this,'close').trigger('click');
										});
									}
									else sr.html( '<span class="label">'+kc.__.i34+'</span>' );

								}, 150, this, e.data );

							});

							listObj.find('i').on('click', function(){

								var tar = kc.get.popup(this).data('target');
								tar.find('i').attr({class:this.className});
								kc.get.popup(this,'close').trigger('click');

							});

							setTimeout(function( el, list ){
								el.append( list );
							}, 10, pop.find('.m-p-body'), listObj );

						break;

						case 'select-image' :

							var media = kc.tools.media.open({ data : { callback : function( atts ){

								var url = atts.url;

								if( atts.size !== undefined && atts.size !== null && atts.sizes[atts.size] !== undefined ){
									url = atts.sizes[atts.size].url;
								}else if( typeof atts.sizes.medium == 'object' ){
									url = atts.sizes.medium.url;
								}

								if( url !== undefined && url !== '' ){

									el.attr({ src : url });

								}
							}, atts : {frame:'post'} } } );

							media.$el.addClass('kc-box-media-modal');

						break;
					}

					if( el.hasClass('kc-box-toggled') &&  el.hasClass('kc-box') )
						el.removeClass('kc-box-toggled');

					e.preventDefault();
					return false;

				}

			},

			elms : function( e, el ){

				var type = $( el ).data('type'),
					cfg = $( el ).data('cfg'),
					value = '';

				if( e.target.tagName == 'LI' && type == 'radio' ){

					var wrp = $(e.target).parent();
					wrp.find('.active').removeClass('active');
					wrp.find('input[type="radio"]').attr({checked:false});
					$(e.target).addClass('active');

					value = $(e.target).find('input[type="radio"]').attr({checked:true}).val();

				}

				if( type == 'select' ){
					value = el.value;
				}

				if( value !== '' && cfg !== '' && cfg !== undefined ){
					kc.cfg[ cfg ] = value;
					kc.backbone.stack.set( 'KC_Configs', kc.cfg );
				}

			},

			prepare : function( name, data, content ){
					
				var maps = kc.maps[name]!==undefined?kc.maps[name]:{},
					map_params = kc.params.merge( name ),
					full = '['+name;
				
				if( content === undefined ) {
					if (name == 'kc_row')
						content = '[kc_column width="100%"][/kc_column]';
					else if (name == 'kc_row_inner')
						content = '[kc_column_inner width="100%"][/kc_column_inner]';
					else content = '';
				}
					
				if( kc.maps[name] !== undefined && kc.maps[name].content !== undefined && content === '' )
					content = kc.maps[name].content;
				
				for( var i in map_params ){

					if( map_params[i].type == 'random' ){

						full += ' '+map_params[i].name+'="'+parseInt(Math.random()*1000000)+'"';

					}else if( !_.isUndefined( map_params[i].value ) ){
						if( map_params[i].name == 'content' && maps.is_container === true ){
							content = map_params[i].value;
						}else{
							full += ' '+map_params[i].name+'="'+map_params[i].value+'"';
						}
					}
				}

				if( name == 'kc_wp_widget' )
					full += ' data="'+data+'"';

				full += ']';
				
				if( maps.is_container === true ){
					full += content+'[/'+name+']';
				}
										
				delete map_params;
				
				return full;
					
			},

			publishAction : function( e ){

				if( e.data )
				{
					var rect = e.data.getBoundingClientRect();
					var sctop = $( window ).scrollTop();
					if( e.data.sctop === undefined )
						e.data.sctop = rect.top + sctop;

					if( e.data.sctop < sctop + 35 )
						$( e.data ).addClass('float_publish_action');
					else
						$( e.data ).removeClass('float_publish_action');
				}

			},
			
			search_elements : function(e){
				
				var key = this.value.toLowerCase()
				
				if( this.pop === undefined )
					this.pop = kc.get.popup( this ) !== null ? kc.get.popup( this ) : $(this).closest('#kc-elements-list');
					
				if( this.items === undefined )
					this.items = this.pop.find('.kc-components .kc-components-list-main li');
				
				this.pop.find('#kc-clipboard,.kc-wp-widgets-pop').hide();
				this.pop.find('.kc-components .kc-components-list-main').show();

				this.pop.find('.kc-components .kc-components-categories .active').removeClass('active');
				
				this.items.css({display: 'none'});
				this.items.each(function(){
					var find = $(this).find('strong').html().toLowerCase();
					if( find.indexOf( key ) > -1 || key === '' )
						this.style.display = 'block';
				});
				
			},
			
			callbacks : {
				
				upload_image : function( el, $ ){

					el.find('.media').on( 'click', { callback: function( atts ){

						var wrp = $(this.el).closest('.kc-attach-field-wrp'), url = atts.url;
		
						wrp.find('input.kc-param').val(atts.id).change();
						if( typeof atts.sizes.medium == 'object' )
							var url = atts.sizes.medium.url;
		
						if( !wrp.find('img').get(0) ){
							wrp.prepend('<div class="img-wrp"><img src="'+url+'" alt="" /><i title="'+kc.__.i50+'" class="sl-close"></i></div>');
							wrp.find('img').on( 'click', el, function( e ){
								el.find('.media').trigger('click');
							});
							wrp.find('div.img-wrp .sl-close').on( 'click', el, function( e ){
								e.data.find('input.kc-param').val('').change();
								$(this).closest('div.img-wrp').remove();
							});
						}else wrp.find('img').attr({src : url});
		
					}, atts : { frame: 'select' } }, kc.tools.media.open );
		
					el.find('div.img-wrp .sl-close').on( 'click', el, function( e ){
						e.data.find('input.kc-param').val('').change();
						$(this).closest('div.img-wrp').remove();
					});

					el.find('div.img-wrp img').on( 'click', el, function( e ){
						el.find('.media').trigger('click');
					});
		
				},
				
				upload_image_url : function( el, $ ){
		
					el.find('.media').on( 'click', { callback : function( atts ){
		
						var wrp = $(this.el).closest('.kc-attach-field-wrp'), url = atts.url;
		
						if( atts.size != undefined && atts.size != null && atts.sizes[atts.size] != undefined ){
							var url = atts.sizes[atts.size].url;
						}else if( typeof atts.sizes.medium == 'object' ){
							var url = atts.sizes.medium.url;
						}
		
						if( url != undefined && url != '' ){
							wrp.find('input[data-kc-param]').val(url).change();
						}
		
						if( !wrp.find('img').get(0) ){
							wrp.prepend('<div class="img-wrp"><img src="'+url+'" alt="" /><i title="'+kc.__.i50+'" class="sl-close"></i><div class="img-sizes"></div></div>');
							el.find('div.img-wrp img').on( 'click', el, function( e ){
								el.find('.media').trigger('click');
							});
							el.find('div.img-wrp .sl-close').on( 'click', el, function( e ){
								$(this).closest('div.img-wrp').remove();
								e.data.find('input[data-kc-param]').val('').change();
							});
						}else{
							wrp.find('img').attr({src : url});
							wrp.find('.img-sizes').html('');
						}
		
						var btn, wrpsizes = wrp.find('.img-sizes');
						for( var si in atts.sizes ){
							btn = $('<button data-url="'+atts.sizes[si].url+
										'" class="button">'+atts.sizes[si].width+'x'+
										atts.sizes[si].height+'</button>'
									);
		
							if( atts.size != undefined && atts.size ){
		
								if( atts.size == si )
									btn.addClass('button-primary');
		
							}else if( si == 'medium' )
								btn.addClass('button-primary');
		
							btn.on( 'click', function(e){
		
								var wrp = $(this).closest('.kc-attach-field-wrp'), url = $(this).data('url');
		
								$(this).parent().find('button').removeClass('button-primary');
								$(this).addClass('button-primary');
		
								wrp.find('img').attr({ src : url });
								wrp.find('input[data-kc-param]').val( url ).change();
		
								e.preventDefault();
								return false;
		
							});
							wrpsizes.append( btn );
						}
						
					}, atts : {frame:'post'} }, kc.tools.media.open );
		
					el.find('div.img-wrp .sl-close').on( 'click', el, function( e ){
						$(this).closest('div.img-wrp').remove();
						e.data.find('input[data-kc-param]').val('').change();
					});
		
					el.find('div.img-wrp img').on( 'click', el, function( e ){
						el.find('.media').trigger('click');
					});
		
				},
				
				upload_images : function( el ){

					el.find('.media').on( 'click', function( atts ){
		
						var wrp = jQuery(this.els).closest('.kc-attach-field-wrp'), url = atts.url;
		
						//wrp.find('input.kc-param').val(atts.id).change();
						if( typeof atts.sizes.thumbnail == 'object' )
							var url = atts.sizes.thumbnail.url;
		
						wrp.prepend('<div data-id="'+atts.id+'" class="img-wrp"><img title="Drag image to sort" src="'+url+'" alt="" /><i title="'+kc.__.i50+'" class="sl-close"></i></div>');
						helper( wrp );
		
					}, kc.tools.media.opens );
		
					function helper( el ){
		
						kc.ui.sortable({
		
							items : 'div.kc-attach-field-wrp>div.img-wrp',
							helper : ['kc-ui-handle-image', 25, 25 ],
							connecting : false,
							vertical : false,
							end : function( e, el ){
								refresh( jQuery(el).closest('.kc-attach-field-wrp') );
							}
		
						});
		
		
						el.find('div.img-wrp i.sl-close').off('click').on( 'click', el, function( e ){
							jQuery(this).closest('div.img-wrp').remove();
							refresh( e.data );
						});
		
						refresh( el );
		
					}
		
					function refresh( el ){
						var val = [];
						el.find('div.img-wrp').each(function(){
							val[ val.length ] = jQuery(this).data('id');
						});
						if( val.length <= 4 ){
							el.removeClass('img-wrp-medium').removeClass('img-wrp-large');
						}else if( val.length > 4 && val.length < 9 ){
							el.addClass('img-wrp-medium').removeClass('img-wrp-large');
						}else if( val.length >= 9 ){
							el.removeClass('img-wrp-medium').addClass('img-wrp-large');
						}
		
						el.find('input.kc-param').val( val.join(',') ).change();
		
						el.find('div.img-wrp img').on( 'click', el, function( e ){
							el.find('.media').trigger('click');
						});
					}
		
					helper( el.find('.kc-attach-field-wrp') );
		
				},
				
				select_group : function( el, $ ){
					
					el.find('button').on( 'click', el, function(e){
						
						e.data.find('input').val(this.getAttribute('data-value')).trigger('change');
						e.data.find('button.active').removeClass('active');
						$(this).addClass('active');
						
						e.preventDefault();
						return false;
						
					});
				},
				
				number : function( el, $ ){
					
					el.find('input[type=number]').on( 'mousedown', function(e){
						
						if( e.which !== undefined && e.which !== 1 )
							return false;
							
						$(document).on( 'mouseup', function(){
							$(document).off( 'mousemove' ).off('mouseup');
							$('body').removeClass('noneuser').css({cursor:''});
						});
						
						$(document).on( 'mousemove', { 
							el: this,
							cur: parseInt(this.value!==''?this.value:0),
							top: e.clientY
						}, function(e){
							var offset = Math.round((e.clientY-e.data.top)/2);
							e.data.el.value = (e.data.cur-offset);
							$(e.data.el).trigger('change');
						});
						
						$('body').css({cursor:'ns-resize'});
						
						$( window ).off('mouseup').on('mouseup', function(){
							$(document).off('mousemove');
							$(window).off('mouseup');
							$('html,body').removeClass('kc_dragging noneuser');
						});
						
					}).on('change', function(){
						
						var unit = $(this).parent().find('li.active').html(), 
							val = this.value;
							
						if( val !== '' )
							val += unit;
							
						$(this).parent().find('input[type=hidden]').val( val ).trigger('change');
						
					});
					
					el.find('ul li').on('click', function(){
						
						$(this).parent().find('.active').removeClass('active');
						$(this).addClass('active');
						var inp = $(this).closest('.m-p-r-content').find('input[type=hidden]'),
							val = inp.val().replace(/[^0-9\.]/g,'');
						
						if( val !== '' )
							val += this.innerHTML;
							
						inp.val( val ).trigger('change');
			
					});
					
				},
				
				number_slider : function( el, $, data ){

			        var el_slider = el.find('.kc_percent_slider'),
			       		values = data.value.toString().split('|'),
						options = {
							range : (typeof data.options['range'] == 'undefined' )? false: data.options['range'],
							unit : (typeof data.options['unit'] == 'undefined' )? '': data.options['unit'],
							ratio : (typeof data.options['ratio'] == 'undefined' )? 1: parseFloat(data.options['ratio']),
							min : (typeof data.options['min'] == 'undefined' )? 0: parseFloat(data.options['min']),
							max : (typeof data.options['max'] == 'undefined' )? 100: parseFloat(data.options['max']),
							enabled : (typeof data.options['max'] == 'enabled' )? true: data.options['enabled'],
							step : (typeof data.options['step'] == 'undefined' )? 1: parseFloat(data.options['step'])
						}, 
						kc_number_slider = function( el, set_val ){
					        
							var op = el.data('options');
							op.onchange = function( left, right ){
								
								if( op.range === true )
									el.next('input')
										.val((kc.tools.nfloat(left*op.ratio)+op.unit)+'|'+(kc.tools.nfloat(right*op.ratio)+op.unit))
										.trigger('change', 'globe');
								else el.next('input')
									.val(kc.tools.nfloat(left*op.ratio)+op.unit)
									.trigger('change', 'globe');
								
								el.find('.fscaret').text('');
								
							}
							
							if( isNaN( set_val ) ){
								op.value = parseFloat(op['max']);
							}else{
								if( set_val.length === 1 )
									op.value = parseFloat(set_val[0])/op.ratio;
								else op.value = [parseFloat(set_val[0])/op.ratio, parseFloat(set_val[1])/op.ratio];
							}
							
							el.off('mouseup').on('mouseup',function(){
								$(this).next('input').change();
							}).freshslider( op );
				
						};
					
					el_slider.data({ options: options });
					
					for( var i in values ){
						values[i] = parseFloat( values[i] );
					}
					
					kc_number_slider( el_slider, values );
					
					el_slider.next('input').on('change', { el : el , el_slider: el_slider, data : data }, 
					
						function(e, data){
				
							// This is not native event
							if( data !== undefined && data == 'globe' )
								return;
							
							var _value = $(this).val(),
								op = e.data.el_slider.data('options');
							
							if(/^\d+$/.test(_value) && _value !== ''){
								
								if(this.value!==_value)
									$(this).val( parseInt(_value) );
								
								if( _value/op.ratio > op['max'] ) 
									_value = op['max']*op.ratio;
								
								_value = _value.toString().split('|');
								for( var i in _value ){
									_value[i] = parseInt( _value[i] )/op.ratio;
								}
								
								kc_number_slider( e.data.el_slider, _value );
								
							}else{
								e.data.el_slider.next('input').val('');
							}
				
						}
					
					);
					
					el_slider.next('input').val(data.value).trigger('change', 'globe');
						
			    },
				
				autocomplete : function(wrp, $, data) {
		
					function render( data, wrp ){
						
						var out = '', post_type = 'any', category = '', category_name = '', numberposts = 120, taxonomy = '', multiple = true;
						
						if( data.options !== undefined ){
							if( data.options.post_type !== undefined )
								post_type = data.options.post_type;
							if( data.options.category !== undefined )
								category = data.options.category;
							if( data.options.category_name !== undefined )
								category_name = data.options.category_name;
							if( data.options.taxonomy !== undefined )
								taxonomy = data.options.taxonomy;
							if( data.options.multiple !== undefined )
								multiple = data.options.multiple;
						}
						
						if( data.value !== '' ){
							var items = data.value.split(','), item, id;
							for( var i=0; i<items.length; i++ ){
								item = items[i].split(':');
								id = item[0];
								if( item[1] !== undefined )
									item = item[1];
								else item = '';
								out += '<li data-id="'+id+'"><span>'+kc.tools.esc_attr(item)+'</span><i class="sl-close kc-ac-remove" title="Remove item"></i></li>';
							}
						}
						
						wrp.find('ul.autcp-items').html( out );
						helper( wrp.find('.kc_autocomplete_wrp') );
						
						wrp.find('.kc-autp-enter').on('focus',function(){
							$(this.parentNode).find('.kc-autp-suggestion').show();
						}).on('blur',function(){
							setTimeout( function(el){
								el.hide();
							}, 200, $(this.parentNode).find('.kc-autp-suggestion') );
						}).on('keyup',function(){
							
							if( this.value === '' )
								return;
								
							if( $(this.parentNode).find('.kc-autp-suggestion .fa-spinner').length == 0 ){
								$(this.parentNode).find('.kc-autp-suggestion ul').prepend('<li class="sg-loading kc-free-scroll"><i class="fa fa-spinner fa-spin"></i> searching...</li>');
							}
							clearTimeout( this.timer );
							this.session = Math.random();
							this.timer = setTimeout(function(el){
								
								$.post(
					
									kc_ajax_url,
								
									{
										'action': 'kc_suggestion',
										'security': kc_ajax_nonce,
										'post_type': post_type,
										'category': category,
										'category_name': category_name,
										'numberposts': numberposts,
										'taxonomy': taxonomy,
										's': el.value,
										'field_name': data.name, 
										'session': el.session,
									},
								
									function (result) {
										
										$(el.parentNode).find('.sg-loading').remove();
										
										if (el.session == result.__session) {
											
											var ex = [], 
											out = [], 
											item;
											
											for (var n in result) {
												if(n !== '__session') {
													if( ex.indexOf( n ) === -1 ){
														ex.push(n);
														out.push('<li class="label kc-free-scroll">'+n+'</li>');	
													}
													for( var m in result[n] ){
														item = result[n][m].split(':');
														out.push('<li class="kc-free-scroll" \
																	data-multiple="'+multiple+'" \
																	data-value="'+kc.tools.esc_attr(result[n][m])+'">\
																	'+item[1]+'</li>');	
													}
												}	
											}
											
											if (out.length === 0)
												out.push('<li>Nothing found</li>');
												
											$(el.parentNode).find('.kc-autp-suggestion ul')
												.html (out.join('')).find('li')
												.on('click',function() {
													
													var value = $(this).data('value');
													if (value === null || value === undefined)
														return;
													
													var wrp = $(this).closest('.kc_autocomplete_wrp').find('ul.autcp-items');
														
													value = value.split(':');
													
													if ($(this).data('multiple') === false)
														wrp.find('i.kc-ac-remove').trigger('click');
														
													wrp.append('<li data-id="'+value[0]+'"><span>'
															+kc.tools.esc_attr(value[1])+'</span>\
															<i class="sl-close kc-ac-remove" title="Remove item"></i>\
															</li>');
														
													helper ($(this).closest('.kc_autocomplete_wrp'));
													
												});
										}
									}
								);
								
							}, 250, this);
						});
							
					}
					
					function helper( el ){
			
						kc.ui.sortable({
			
							items : 'div.kc_autocomplete_wrp>ul>li',
							connecting : false,
							vertical : false,
							end : function( e, el ){
								refresh( $(el).closest('.kc_autocomplete_wrp') );
							}
			
						});
			
			
						el.find('i.kc-ac-remove').off('click').on( 'click', el, function( e ){
							$(this).closest('li').remove();
							refresh( e.data );
						});
			
						refresh( el );
			
					}
			
					function refresh( el ){
						
						var val = [];
						
						el.find('>ul.autcp-items>li').each(function(){
							val[ val.length ] = $(this).data('id')+':'+$(this).find('>span').html();
						});
			
						el.find('input.kc-param').val( val.join(',') );
			
					}
					
					render( data, wrp );
				
				},
				
				taxonomy : function( wrp, $, data ){

					// Action for changing content type
					wrp.find('.kc-content-type').on( 'change', wrp, function( e ){
			
						var type = this.value;
			
						e.data.find('.kc-taxonomies-select option').each(function(){
			
							this.selected = false;
			
							if( $(this).hasClass( type ) )
								this.style.display = '';
							else this.style.display = 'none';
			
							if( this.value == type ){
								this.checked = true;
								e.data.find('input.kc-param').val( type );
							}
						});
			
					});
					// Action for changing taxonomies
					wrp.find('.kc-taxonomies-select').on( 'change', wrp, function( e ){
			
						var value = [];
						$(this).find('option:selected').each(function(){
							value.push( this.value );
						});
			
						e.data.find('input.kc-param').val( value.join(',') );
			
					});
					// Action remove selection
					wrp.find('.unselected').on( 'click', wrp, function( e ){
			
						e.data.find( '.kc-taxonomies-select option:selected' ).attr({ selected: false });
						e.preventDefault();
                        e.data.find('input.kc-param').val( wrp.find('.kc-content-type').val() );
			
					});
                    
			        if( data.value === '' )
                        data.value = 'post';

					var values = data.value.split(','),
					valuez = data.value+',';

                    if( valuez.indexOf( ':' ) == -1 && values.length == 1 ){
                        var f = false;
                        wrp.find('.kc-content-type option').each( function(){
                            if( $(this).val() == data.value ) f = true;
                        });
                        if( !f ){
                            data.value = 'post:' + data.value;
                            values = data.value.split(',');
                            valuez = data.value+',';
                        }
                    }

                    if( valuez.indexOf( ':' ) == -1 && values.length > 1 ){
                        valuez = [];
                        for(var v in values){
                            valuez.push('post:'+values[v]);
                        }
                        values = valuez;
                        valuez = valuez.join(',') + ',';
                    }


			
					// Active selected taxonomies
					if( values.length > 0 ){

						selected = values[0].split( ':' )[0];
			
						// Active selected content type
						if( selected != '' )
							wrp.find('.kc-content-type option[value='+selected+']').attr('selected','selected').trigger('change');
						else wrp.find('.kc-content-type').trigger('change');
			
						wrp.find('.kc-taxonomies-select option').each(function(){
							if( valuez.indexOf( this.value+',' ) > -1 ){
								this.selected = true;
							}else this.selected = false;
						});
					}
			
					wrp.find('.kc-select-wrp')
						.append('<input class="kc-param" name="'+data.name+'" type="hidden" value="'+data.value+'" />');
			
				},
	
				corners : function( el, $ ){
					
					var value_render = function( wrp ){
					
						var val = [], empty = true;
						wrp.find('.kc-corners-wrp input[data-css-corners]').each(function(){
							if( this.value !== '' ){
								val.push(this.value);
								empty = false;
							}else val.push('inherit');
						});
						
						if( empty === true )
							val = [];
							
						wrp.find('input[data-css-corners-value]').val( val.join(' ') ).change();
						
					}
					
					el.find('input[data-css-corners]').on( 'keyup change', el, function( e ){
						
						if(this.value.trim() == 'px'){
							this.value = '';
						}
						if( isNaN(this.value) === false ){
							this.value += 'px';
							this.setSelectionRange( (this.value.length-2), 1);
						}
						if(this.value.trim() == 'px'){
							this.value = '';
						}
						if(this.value.indexOf('%') > -1||this.value.indexOf('em') > -1){
							this.value = this.value.replace(/\p\x/g, '');
						}
						
						$(this).data({unit: this.value.replace(/[^a-z\%]/g,'')});
						
						if( e.data.find('.m-f-u-li-link').hasClass('active') ){
							var cur = this;
							e.data.find('input[data-css-corners]').each(function(){
								if( this != cur ){
									this.value = cur.value;
									$(this).data({unit: this.value.replace(/[^a-z\%]/g,'')});
								}
							});
						}
						
						value_render( e.data );
						
					})
					.on( 'mousedown', function(e){
						
						if( e.which !== undefined && e.which !== 1 )
							return false;
							
						$(document).on( 'mouseup', function(){
							$(document).off( 'mousemove' ).off('mouseup');
							$('body').css({cursor:''});
						});
						
						$(document).on( 'mousemove', { 
							el: $(this),
							cur: parseInt(this.value!==''?this.value:0),
							top: e.clientY
						}, function(e){
							
							var offset = (e.clientY-e.data.top);
							if( e.data.el.val().replace(/[^a-z\%]/g,'') === '' )
								e.data.el.data({unit: 'px'});
							else e.data.el.data({unit: e.data.el.val().replace(/[^a-z\%]/g,'')});
							
							e.data.el.val( (e.data.cur-offset)+e.data.el.data('unit') );
							
							$(e.data.el).trigger('change');
							
						});
						
						$('body').css({cursor:'ns-resize'});
						
						$( window ).off('mouseup').on('mouseup', function(){
							$(document).off('mousemove');
							$(window).off('mouseup');
							$('html,body').removeClass('kc_dragging noneuser');
						});
						
					})
					.on( 'dblclick', function(){
						$(this).val('').change();	
					});
					
					el.find('.m-f-u-li-link span').on( 'click', el, function( e ){
						if( $(this).parent().hasClass('active') ){
							$(this).parent().removeClass('active');
						}else{ 
							$(this).parent().addClass('active');
							var inps = $(this).closest('.kc-corners-wrp').find('input[data-css-corners]'), val = '';
							inps.each(function(){
								if( this.value !== '' && val === '' )
									val = this.value;
							});
							inps.val( val ).trigger('change');
						}
					});
					
				},
				
				background : function( wrp, $, data ){
			
					var test = wrp.find('.kc-css-background-test').get(0), 
						inputs = wrp.find('input[data-css-background]'), p, values = {},
						update_values = function(e) {
							// transparent none 0% 0%/auto repeat scroll
							var factory = {
									color: 'transparent', 
									linearGradient: [''], 
									image: 'none', 
									position: '0% 0%', 
									size: 'auto', 
									repeat: 'repeat', 
									attachment: 'scroll', 
									advanced: 0 
								}, 
								inputs = e.data.find('input[data-css-background]') ,val;
							
							if (e.data.find('.field-toggle input[type="checkbox"]').get(0).checked === true)
							{
								
								factory.advanced = 1;
								
								for (var f in factory)
								{
									val = inputs.filter('[name="'+f+'"]').val();
									
									if (val !== null && val !== undefined && val !== '' && val != factory[f])
									{
										if (f == 'linearGradient')
										{
											
											factory[f] = [];
											if (e.data.find('input.degrees').length > 0 && e.data.find('input.degrees').val() !== '')
											{
												factory[f].push( e.data.find('input.degrees').val().replace(/[^0-9\-]/g, '')+'deg');
											}
											
											if (e.data.find('.color-row input.grdcolor').length === 1)
											{
												if (e.data.find('.color-row input.grdcolor').val() !== '')
												{
													factory[f].push(e.data.find('.color-row input.grdcolor').val());
												}
											}else
											{
												e.data.find('.color-row input.grdcolor').each(function(){
													if (this.value !== '')
														factory[f].push(this.value);
												});
											}
										
										}else if( f == 'image' ){
											factory[f] = val.replace( kc_site_url, '%SITE_URL%' );
										}else factory[f] = val;
									}
								}
							
							}else{
								val = inputs.filter('[name="color"]').val();
								if( val !== null && val !== undefined && val !== '' && val != factory[f] )
									factory['color'] = val;
							}
							
							e.data.find('input[data-css-background-value]')
								  .val (kc.tools.base64.encode(JSON.stringify(factory))).change();
						
						};
					
					wrp.find('.add-more-color').on('click', wrp, function(e){
						
						var input = $('<span class="color-row"><input class="grdcolor" value="" placeholder="Select color" type="search" /><i class="fa-times remove" title="Delete"></i></span>');
						
						$(this.parentNode).find ('.kc-param-bg-gradient-colors .color-row').last().after(input);
						
						var incl = $(this.parentNode).find('.color-row input').last().get(0);
						incl.color = new jscolor.color(incl, {});
						
						e.data.find('input.grdcolor').each(function(){
							if (this.color === undefined)
								this.color = new jscolor.color(this, {});
						});
						
						input.find('input').first().on('change', e.data, update_values);
						
						e.preventDefault();
						return false;
						
					});
										
					wrp.find('.custom-degrees').on('click', wrp, function(e){
						
						if ($(this.parentNode).find ('.degrees-row').length === 0)
						{
							var input = $('<span class="degrees-row"><input class="degrees" value="90" placeholder="Custom degrees" style="width: 45%" type="search" autocomplete="off" /><i class="fa-times remove" title="Delete"></i></span>');
						
							$(this.parentNode).find ('.kc-param-bg-gradient-colors .color-row').last().after(input);
							
							input.find('input').on('change', e.data, update_values);
							
							update_values(e);
							
						}
						e.preventDefault();
						return false;
						
					});
					
					wrp.find('.kc-param-bg-gradient-colors').on('click', wrp, function(e){
						if (e.target.className.indexOf('remove') > -1)
						{
							$(e.target).closest('span').remove();
							update_values(e);
						}
					});
					
					wrp.find('.field-toggle input').on('change', wrp, function(e){
						if( this.checked === true )
							e.data.find('.kc-control-field').removeClass('kc-hidden');
						else e.data.find('.kc-control-field').addClass('kc-hidden');
						e.data.find('input[data-css-background]').first().change();
					});
					
					wrp.find('.field-attach_image_url').each( function(){
						kc.ui.callbacks.upload_image_url( $(this), $ );
					});
					
					wrp.find('.field-select_group').each( function(){
						
						var el = $(this), val = el.find('input[data-css-background]').val();
						
						kc.ui.callbacks.select_group( el, $ );
						
						if( el !== undefined && el !== '' )
							el.find('button').removeClass('active').filter('[data-value="'+val+'"]').addClass('active');
						
					});
					
					inputs.on('change', wrp, update_values);
					
					/*
					*
					* Fill values to form
					*
					*/
					try {
						values = JSON.parse (kc.tools.base64.decode(data.value));
					} catch(ex) {};
						
					values = $.extend ({
						color: 'transparent', 
						linearGradient: [''], 
						image: 'none', 
						position: '0% 0%', 
						size: 'auto', 
						repeat: 'repeat', 
						attachment: 'scroll' 
					}, values);
						
					for (var f in values) {
						
						if (values[f] !== undefined && f == 'image' && values[f] !== '' && values[f] != 'none') {
							
							if (values[f].indexOf('%SITE_URL%') > -1) {

                                values[f] = values[f].replace('%SITE_URL%', kc_site_url);

                                if (values[f].indexOf(kc_ajax_url) === -1)
								    values[f] = kc_ajax_url+'?action=kc_get_thumbn&type=filter_url&id='+encodeURIComponent(values[f].replace(kc_site_url, ''));
							}
							
							wrp.find('.field-attach_image_url img').attr({src: values[f]});
							wrp.find('.kc-toggle-field-wrp input').attr({checked: true});
							wrp.find('.box-bg.kc-hidden').removeClass('kc-hidden');
						}
						
						if (f == 'color' && ( values[f] == 'rgba(0, 0, 0, 0)' || values[f] == 'transparent')) {	
							values[f] = '';
							inputs.filter('[name="'+f+'"]').val('');	
						}
						else if (f == 'advanced' && values[f] == 1) {
							wrp.find('.field-toggle input[type="checkbox"]').attr({checked: true});
							wrp.find('.box-bg.kc-hidden').removeClass('kc-hidden');
						}
						else if (f == 'linearGradient') 
						{
							if (typeof values[f] == 'object' && values[f][0] !== undefined) 
							{
								if (values[f][0].indexOf('deg') > -1) 
								{
									
									if (wrp.find('.degrees-row').length === 0)
									{
										var inp_deg = $('<span class="degrees-row"><input class="degrees" value="'+values[f][0].replace(/[^0-9\-]/g, '')+'" placeholder="Custom degrees" style="width: 45%" type="search" autocomplete="off" /><i class="fa-times remove" title="Delete"></i></span>');
										wrp.find ('.kc-param-bg-gradient-colors .color-row').last().after(inp_deg);
										inp_deg.on ('change', wrp, update_values);								
									}
									values[f] = values[f].slice(1);
								}
								
								wrp.find('.color-row').find('input.grdcolor').val(values[f][0]);
								
								var inp_col;
								for (var i=1; i<values[f].length; i++) {
									
									inp_col = $('<span class="color-row"><input class="grdcolor" value="'+values[f][i]+'" placeholder="Select color" type="search" style="background:'+values[f][i]+'" /><i class="fa-times remove" title="Delete"></i></span>');
						
									wrp.find ('.kc-param-bg-gradient-colors .color-row').last().after(inp_col);
									new jscolor.color(inp_col.find('input').get(0), {});
									
									inp_col.find('input').first().on('change', wrp, update_values);
						
								}
							}
						} else {
							inputs.filter('[name="'+f+'"]').val (values[f]).parent().find('button').removeClass('active');
							inputs.filter('[name="'+f+'"]').parent().find('button[data-value="'+values[f]+'"]').addClass('active');
						}
						
					}
					
					wrp.find('.field-color_picker input, input.grdcolor').each(function() {
						if (this.color === undefined)
							this.color = new jscolor.color(this, {});
					});
					
				},
				
				css : function( wrp, $, data ){
					
					if (data.options.length === 0)
						data.options = kc.maps._styling.options;
						
					kc.params.fields.css.render( data, wrp.find('.kc-css-rows') );		
					
					var pop = wrp.closest('.kc-params-popup'), model = pop.data('model');
					
					kc.tools.popup.callback( pop, { 
						
						before_callback : kc.params.fields.css.save_fields
						
					}, 'field-css-callback' );
					
					
				},
				
				css_fonts : function(  wrp, $, data){
					
					wrp.find('input').on('focus', function(){
						
						var ul = $(this).parent().find('.kc-fonts-list');
						ul.html('').show();
						
						if( typeof kc_fonts == 'object' && Object.keys(kc_fonts).length > 0 ){
							for( var i in kc_fonts ){
								i = decodeURIComponent(i);
								if( i == this.value )
									ul.append('<li style="background: #42BCE2;font-family: \''+i+'\'">'+i+'</li>');
								else ul.append('<li style="font-family: \''+i+'\'">'+i+'</li>');
							}
							ul.find('li').on('click', function(){
								$(this).closest('.kc-fonts-picker').find('input').val(this.innerHTML).change();
							});
						}else{
							ul.append('<li class="align-center"><h1>\\(^Д^)/</h1>No fonts in list<br />Add fonts via "Fonts Manager"</li>');
						}
						
						
					}).on('blur', function(){
						setTimeout( function(el){el.hide()}, 200, $(this).parent().find('.kc-fonts-list') );
					});
					
					wrp.find('button').on('click', function(e){
						
						kc.ui.lightbox({ 
							iframe : true,
							url : kc_site_url+'/wp-admin/admin.php?page=kingcomposer&kc_action=fonts-manager'
						});
				
						e.preventDefault();
					});
					
				},
				
				presets : function( wrp ){
		
					wrp.find('.kc-preset-categories li a').on('click', function(e){
						
						wrp.find('.kc-preset-categories li a.active').removeClass('active');
						$(this).addClass('active');
						
						if( $(this).attr('href') != '#all' ){
							wrp.find('.kc-preset-wrp .kc-preset-item').hide();
							wrp.find('.kc-preset-wrp .kc-preset-item[data-cate="preset-cat-'+kc.tools.esc_slug( $(this).text() ) + '"]').show();
						}else{
							wrp.find('.kc-preset-wrp .kc-preset-item').show();
						}
						
						e.preventDefault();
						return false;
						
					});
					
					wrp.find('.kc-preset-item i.sl-close').on('click', function(e){
						
						if( !confirm(kc.__.sure) ){
							e.preventDefault();
							return false;
						}
							
						
						var pid = $(this).data('pid'),
							pname = $(this).data('pname'),
							items = kc.backbone.stack.get( 'kc_presets', pname );
						
						delete items[pid];
						
						kc.backbone.stack.update('kc_presets', pname, items );
						
						$(this).closest('.kc-preset-item').remove();
						
						e.preventDefault();
						return false;
						
					});
					
				},
				
				radio_image : function( wrp ){
			
					wrp.find('.clear-selected').on( 'click', wrp, function(e){
						e.data.find('input.kc-param.empty-value').attr({'checked':true}).trigger('change');
						e.preventDefault();
					});
					
					var preview = wrp.find('img.large-view'),
						win_width = $(window).width();
					
					wrp.find('label.rbtn img').on('mouseover mousemove', {
						el: preview, 
						wd: win_width
					}, function(e) {
					
						if (e.data.el.attr('src') != this.src)
							e.data.el.attr('src', this.src);
						
						e.data.el.show();
						
						if (e.data.el.width() == this.offsetWidth) {
							e.data.el.hide();
							return;
						}
						
						e.data.left = e.clientX - (e.data.el.width()/2);
						
						if (e.data.left+e.data.el.width() > e.data.wd - 10)
							e.data.left = (e.data.wd - 10 - e.data.el.width());
						
						if (e.data.left < 10)
							e.data.left = 10;
						
						e.data.el.css({display: 'block', left: e.data.left+'px', top: (e.clientY+20)+'px'});
						
					}).on('mouseout', preview, function(e) {
						e.data.hide();
					});
				},
				
				css_border : function( wrp, $, data ){
					
					var inputs = wrp.find('.multi-fields-ul [data-css-border]'),
						input = wrp.find('input[data-css-border="value"]'),
						map = {top: 0, right: 1, bottom: 2, left: 3},
						_get = function(){
							
							var vals = [ inputs.eq(0).val(), inputs.eq(1).val(), inputs.eq(2).val() ];
						
							if( vals[0] !== '' && !isNaN( vals[0] ) )
								vals[0] += 'px';
							
							if( vals[0] !== '' && vals[2] !== '' )
								vals = vals.join(' ');
							else vals = '';
							
							return vals;
							
						},
						_render = function( val ){
							
							if( val == undefined || val === '' )
								val = '   ';
								
							val = val.trim().split(' ');
						
							inputs.eq(0).val(val[0]);
							inputs.eq(1).val(val[1]);
							
							val[0] = '';
							val[1] = '';
							val = val.join(' ').trim();
							
							inputs.eq(2).val( val ).css({'background': val}).change();
							
						}
						
					inputs.on('change', function( e ){
						
						vals = _get(), val = input.val();
						
						var dir = wrp.find('.active').data('dir');
						
						if( dir === undefined ){
							val = vals;
						}else{
							val = val.split('|');
							for( var i=0; i<4; i++ ){
								if( i == map[dir] )
									val[i] = vals;
								else if( val[i] === undefined )
									val[i] = '';
							}
							val = val.join('|');
						}
							
						input.val( val ).change();
						
					});
					
					wrp.find('.kc-corners-pos button,.m-f-u-li-link').on('click', function(e){
						
						wrp.find('.active').removeClass('active');
						$(this).addClass('active');
						
						var val = input.val().toString().trim().split('|');
						
						if( $(this).data('dir') === undefined ){
							_render(val[0]);
						}else{
							if( val.length === 1 ){
								input.val('');
								_render(val[0]);
							}else _render(val[map[$(this).data('dir')]]);
						}
						
						return false;
					});
					
					wrp.find('.css-border-advanced').on('click', wrp, function(e){
						e.data.find('.kc-corners-wrp').removeClass('hidden');
						e.data.find('.css-border-advanced').remove();
						e.preventDefault();
						return false;
					});
					
					if( data.value.indexOf('|') === -1 ){
						wrp.find('.m-f-u-li-link').addClass('active');
						_render( data.value );
					}else{
						
						wrp.find('.kc-corners-wrp').removeClass('hidden');
						wrp.find('.css-border-advanced').remove();
						
						var value = data.value.split('|');
						for( var i=0; i<4; i++ ){
							if( value[i] !== undefined && value[i] !== '' ){
								wrp.find('.kc-corners-wrp .kc-corners-pos').eq(i).find('button').trigger('click');
								break;
							}
						}
					}
					
					wrp.find('input.m-f-bb-color').each(function(){
						this.color = new jscolor.color(this, {});
					});
					
			    },
				
				animate : function( wrp, $, data ){
					
					if (data.value === undefined)
						data.value = '';
						
					var preview = wrp.find('.kc-animate-preview'),
						eff = wrp.find('.kc-animate-effect'),
						delay = wrp.find('.kc-animate-delay'),
						speed = wrp.find('.kc-animate-speed');
						param = wrp.find('.kc-param'),
						value = data.value.split('|');
						
					wrp.find('.kc-animate-field select,.kc-animate-field input').on('change keydown', function(e){
						
						if (e.keyCode!== undefined && e.keyCode !== 13)
							return; 
							
						if (delay.val() !== '')
							preview.css({'animation-delay': delay.val()+'ms'});
						else preview.css({'animation-delay': ''});
						
						if (speed.val() !== '')
							preview.css({'animation-duration': speed.val()});
						else preview.css({'animation-duration': ''});
						
						if (eff.val() !== '')	
							preview.attr({'class':''}).attr({'class': 'kc-animate-preview animated '+this.value});
							
						param.val( eff.val()+'|'+delay.val()+'|'+speed.val() ).change();
						
					});
					
					if (value[0] !== undefined && value[0] !== '')
						eff.val(value[0]);
					if (value[1] !== undefined && value[1] !== '')
						delay.val(value[1]);
					if (value[2] !== undefined && value[2] !== '')
						speed.val(value[2]);
					
				},
				
				icon_picker : function( wrp, $, data) {

					wrp.find('input.kc-param, .icons-preview').on('click', wrp.find('input.kc-param').get(0), function(e){
		
						$('.kc-icons-picker-popup').remove();
		
						var html = '<div class="icons-list noneuser">'+
									'<ul class="kc-icon-picker-tabs kc-pop-tabs"></ul>'+
									kc.tools.get_icons()+
									'</div>';
						
						var listObj = jQuery(html);
		
						var atts = { 
							title: 'Icon Picker', 
							width: 600, 
							class: 'no-footer kc-icons-picker-popup',
							float: true,
							keepCurrentPopups: true 
						};
						var pop = kc.tools.popup.render( this, atts );
						
						pop.data({ target: e.data/*, scrolltop: jQuery(window).scrollTop() */});
						
						pop.find('.m-p-header').append('<input type="search" class="kc-components-search kc-icons-search" placeholder="Search by Name"><i class="sl-magnifier"></i>');
						
						pop.find('.m-p-body').off('mousedown').on('mousedown',function(e){
							e.preventDefault();
							return false;
						});
		
						pop.find('input.kc-icons-search').off( 'keyup' ).on( 'keyup', listObj, function( e ){
							
							clearTimeout( this.timer );
							
							if (this.value === '') {
								e.data.find('.seach-results').remove();
								return;
							}
							
							this.timer = setTimeout( function( el, list ){
		
								if( list.find('.seach-results').length == 0 ){
		
									var sr = $('<div class="seach-results"></div>');
									list.prepend( sr );
		
								}else sr = list.find('.seach-results');
		
								var found = ['<span class="label">Search Results:</span>'];
								list.find('>i').each(function(){
		
									if( this.className.indexOf( el.value.trim() ) > -1
										&& found.length < 16
										&& $.inArray( this.className, found )
									)found.push( '<span data-icon="'+this.className+'"><i class="'+this.className+'"></i>'+this.className+'</span>' );
		
								});
								if( found.length > 1 ){
									sr.html( found.join('') );
									sr.find('span').on('click', function(){
		
										if( $(this).data('icon') === undefined )
										{
											e.preventDefault();
											return false;
										}
										var tar = kc.get.popup(this).data('target');
										tar.value = $(this).data('icon');
										$(tar).trigger('change');
										kc.get.popup(this, 'close').trigger('click');
									});
								}
								else sr.html( '<span class="label">The key you entered was not found.</span>' );
		
							}, 150, this, e.data );
		
						}).focus();
		
						listObj.on('click', function( e ){
							
							if (e.target.tagName != 'I')
								return;
								
							var tar = kc.get.popup(this).data('target');
							tar.value = e.target.title;
							$(tar).trigger('change');
							
							kc.get.popup(this, 'close').trigger('click');
							
						});
						
						var args = [], cl, tabs = '';
						listObj.find('i').each(function(){
							if (this.className !== undefined && this.className.indexOf('-') > -1) {
								cl = this.className.substr(0, this.className.indexOf('-')).trim();
								if (cl !== '' && args.indexOf(cl) === -1) {
									args.push(cl);
									tabs += '<li>'+cl+'</li>';
								}
							}
						});
						
						listObj.find('ul.kc-icon-picker-tabs').html(tabs);
						listObj.find('ul.kc-icon-picker-tabs li').on('click', function(){
							$(this).parent().find('.active').removeClass('active');
							$(this).addClass('active');
							listObj.find('i').hide();
							listObj.find("i[class^='"+this.innerHTML+"-']").css({'display': ''});
						}).first().trigger('click');
						pop.find('.m-p-body').append(listObj);
						
					}).on('change',function(){
						jQuery(this).parent().find('.icons-preview i').attr({class: this.value});
					})/*.on('blur', function(){
						kc.cfg.icon_picker_scrolltop = $('.kc-icons-picker-popup .m-p-body').scrollTop();
						$('.kc-icons-picker-popup').remove();
					})*/;
					
				},
				
				wp_widgets : function( wrp, $ ){
					
					var container = wrp.find('.kc-widgets-container'),
						pop = kc.get.popup( wrp );
					
					container.find('*[data-value]').each(function(){
						switch (this.tagName){
							case 'INPUT' :
								if( this.type == 'radio' || this.type == 'checkbox' )
									this.checked = true;
								else this.value = $(this).data('value');
							break;
							case 'TEXTAREA' :
								this.value = $(this).data('value');
							break;
							case 'SELECT' :
								var vls = $(this).data('value');
								if( vls )vls = vls.toString().split(',');
								else vls = [''];
			
								if( vls.length > 1 )
									this.multiple = 'multiple';
								$(this).find('option').each(function(){
									if( vls.indexOf( this.value ) > -1 )
										this.selected = true;
									else this.selected = false;
								});
							break;
						}
					});
			
					kc.tools.popup.callback( pop, { 
						
						before_callback : function( wrp ){
			
							var name = container.data('name'),
								fields = container.closest('form').serializeArray(),
								data = {};
								
							data[name] = {};
				
							fields.forEach (function(n) {
								if (data[name][n.name] == undefined)
									data[name][n.name] = n.value;
								else data[name][n.name] += ','+n.value;
							});
							
							var string = kc.tools.base64.encode( JSON.stringify( data ) );
				
							container.append('<textarea name="data" class="kc-param kc-widget-area forceHide">'+string+'</textarea>');
				
						},
						after_callback : function( wrp ){
							container.find('.kc-param.kc-widget-area.forceHide').remove();
						}
						
					}, 'field-wp-widget-callback' );
			
				},
				
				optimize_settings : function( wrp ){
			
					wrp.find('input[data-optimized]').on('change', function(){
						
						if (this.checked)
							kc_global_optimized[$(this).data('optimized')] = this.value;
						else kc_global_optimized[$(this).data('optimized')] = '';
						
						var mp = wrp.closest('.m-p-wrap');
						if (mp.find('.kc-popup-loading').length === 0)
							mp.append('<div class="kc-popup-loading"><span class="kc-loader"></span></div>');
						
						mp.find('.kc-popup-loading').show();
							
						$.post(

							kc_ajax_url,
			
							{
								'action': 'kc_enable_optimized',
								'security': kc_ajax_nonce,
								'settings': kc_global_optimized,
								'id' : $('#post_ID').val()
							},
			
							function (result) {
								
								wrp.find('.m-warning, .m-success').remove();
								mp.find('.kc-popup-loading').hide();
								
								if (result == '-1') {
									wrp.find('h1.mgs-t02').after('<div style="display: block" class="m-settings-row m-warning">Error: secure session is invalid. Reload and try again</div>');
									$('input[data-optimized="enable"]').attr({checked: false});
								}else if (result.stt === undefined){
									wrp.find('h1.mgs-t02').after('<div style="display: block" class="m-settings-row m-warning">Error: unknow reason</div>');
									$('input[data-optimized="enable"]').attr({checked: false});
								}else if(result.stt == '0'){
									wrp.find('h1.mgs-t02').after('<div style="display: block" class="m-settings-row m-warning">Error: '+result.msg+'</div>');
									$('input[data-optimized="enable"]').attr({checked: false});
								}else {
									wrp.find('h1.mgs-t02').after('<div style="display: block" class="m-settings-row m-success">'+result.msg+'</div>');
									if (kc_global_optimized.enable == 'on' )
										$('#kc-page-settings').addClass('kc-optimized-on');
									else $('#kc-page-settings').removeClass('kc-optimized-on');
								}
			
							}
						).complete(function( data ) {
							
						    if(data.status !== 200) {
							    kc.msg( 'Please check all of your code and make sure there are no errors. ', 'error', 'sl-close' );
						    }
						});

						
					});
					
					wrp.find('select[data-optimized="this_page"]').on('change', function(){
						$('#kc-page-cfg-optimized').val(this.value);
						kc.instant_submit();
					});
					
					wrp.find('button.clear-cache').on('click', function(){
						
						if (!confirm(kc.__.i72))return;
						
						kc_global_optimized.clear_cache = 'on';
						wrp.find('input[data-optimized]').first().trigger('change');
						
					});
					
				}
				
			},
			
			upgrade_notice : function( old_version ){
				
				if( old_version < 2.5 ){
					
					kc.msg( '<h3>Great!!!, but hold on a sec!</h3><div class="kc-pl-form"><p class="notice">You have updated KingComposer page builder to version '+kc_version+'. From version 2.5, we changed to new better CSS & Responsive system. What you need to do?</p><ul><li>1. All of your css custom from old version may be lost, so please make sure that you checked all of your pages.</li><li>2. You have to upgrade all the elements that you\'ve overwritten to work with the new system css. <a href="http://docs.kingcomposer.com/docs/use-css-system-for-my-element/" target=_blank>How to update?</a></li></ul></div><div id="kc-preload-footer"><a href="https://wordpress.org/plugins/kingcomposer/developers/" target="_blank" class="button gray left">Older versions</a><a href="https://wordpress.org/plugins/kingcomposer/changelog/" target=_blank class="button verify right">Changelog <i class="fa-file"></i></a></div>', 'popup' );
				
				}
				
				if( old_version < 2.6 ){
					$('#kc-preload').remove();
					kc.ui.lightbox({msg: '<img width="957" src="'+kc_plugin_url+'/assets/images/kc2.6.jpg" /><div><h3>Great!!! You\'ve updated KC to version 2.6</h3><p><button class="button" onclick="jQuery(\'#kc-preload a.close\').trigger(\'click\')">OK, got it</button></p></div>', padding: 0, width: 957, height: 580});
				
				}
				
			},
			
			lightbox : function( cfg ){
				
				var wrp = $('#kc-preload .kc-preload-body');
				
				if( wrp.length === 0 ){
					
					$('#wpwrap').append( '<div id="kc-preload"><div id="kc-welcome" style="display: block;" class="kc-preload-body"><a href="#" class="close"><i class="sl-close"></i></a></div></div>' );
					
					wrp = $('#kc-preload .kc-preload-body');
					wrp.find('a.close').on('click', function(){
						$(this).closest('#kc-preload').remove();
						$('.kc-ui-blur').removeClass('kc-ui-blur');
					});
					
					$('#kc-preload').on('click', function(e){
						if( e.target.id == 'kc-preload' ) {
							$(this).remove();
							$('.kc-ui-blur').removeClass('kc-ui-blur');
						}
					});
					
				}
				
				wrp.find('>*:not(a.close)').remove();
				
				cfg = $.extend({
					width: '1000',
					height: '576',
					padding: '10',
					iframe: false,
					url: '',
					msg: ''
				}, cfg );
				
				if( cfg.iframe === true ){
					cfg.msg = '<iframe width="'+cfg.width+'" height="'+cfg.height+'" src="'+cfg.url+'" frameborder="0" allowfullscreen></iframe>'
				}
					
				wrp.css({width: cfg.width+'px', height: cfg.height+'px', padding: cfg.padding+'px'}).append(cfg.msg);
				
				return wrp;
				
			},
			
			fonts_callback : function( datas ){
			
				window.kc_fonts = datas;
				
				var uri = '//fonts.googleapis.com/css?family=', link, fid;
				
				for (var family in datas) {
					
					fid = decodeURIComponent( family );
					fid = fid.replace(/\ /g, '-').toLowerCase();
					
					if (document.getElementById( fid+'-css' ) === null) {
						link = family.replace(/ /g, '+')+':'+datas[family][1]+encodeURIComponent('&subset=')+datas[family][1];
						link = '<link rel="stylesheet" id="'+fid+'-css" href="'+(uri+link)+'" type="text/css" media="all" />';
						$('head').append(link);
						if (kc.frame !== undefined) {
							kc.frame.$('head').append(link);
						}
					}
				}
				
			},
			
			right_click : function (e) {
				
				// remove exist menus
				$('.kc-right-click-dialog').remove();
					
				var ob = $(e.target).hasClass('kc-model') ? $(e.target) : $(e.target).closest('.kc-model');

				if (ob.length > 0) {

					var ww = $(window).width(),
						wh = $(window).height(),
						model = ob.attr('id').toString().replace('model-', ''),
						css = { 
							position: 'fixed',
							zIndex: 99999,
							left: e.clientX+'px',
							top: e.clientY+'px'
						}
					
					if (kc.storage[model] === undefined)
						return false;
					/*
						Close all popup before open panel
					*/
					$('.kc-params-popup .sl-close.sl-func').trigger('click');

					var name = kc.storage[model].name,
						actions = {
							edit: '<li data-act="edit"><i class="fa-edit"></i> Edit</li>',
							insert: '<li data-act="insert"><i class="fa-columns"></i> New '+name.replace('kc_', '').replace(/\_/g, ' ')+'</li>',
							copy:  '<li data-act="copy"><i class="fa-copy"></i> Copy '+
										'<ul class="sub">'+
											'<li data-act="copystyle"><i class="fa-paint-brush"></i> '+
											'Copy style only</li>'+
										'</ul></li>',
							copystyle: '<li data-act="copystyle"><i class="fa-paint-brush"></i> Copy style only</li>',
							paste:  '<li data-act="paste"><i class="fa-paste"></i> Paste '+
										'<ul class="sub">'+
											'<li data-act="pastestyle"><i class="fa-paint-brush"></i> '+
											'Paste style only</li>'+
										'</ul></li>',
							double: '<li data-act="double"><i class="fa-clone"></i> Double</li>',
							add: '<li data-act="add"><i class="fa-plus"></i> Add Elements</li>',
							cut: '<li data-act="cut"><i class="fa-cut"></i> Cut</li>',
							clear: '<li data-act="clear"><i class="fa-eraser"></i> Clear style</li>',
							delete: '<li data-act="delete"><i class="fa-trash"></i> Delete</li>'
						};
					
					if (['kc_column', 'kc_column_inner'].indexOf(name) > -1) {
						delete actions.copy;
						delete actions.cut;
					}else if(kc_maps_view.indexOf(name) > -1) {
						delete actions.copystyle;
					}else{
						delete actions.insert;
						delete actions.copystyle;
					}
					
					var actions_content = '';
					for (var n in actions) {
						actions_content += actions[n];
					}
						
					var	dialog =    '<div id="kc-elms-breadcrumn" class="kc-right-click-dialog">\
										<ul>\
											<li class="item active">\
												<span class="pointer">\
													<i class="fa-dot-circle-o"></i> \
													'+name.replace(/_/g, ' ')+'\
												</span>\
												<ul>'+actions_content+'</ul>\
											</li>\
										</ul>\
									</div>';
					
					
					dialog = $(dialog);
					
					$('#kc-right-click-helper').show().html('').append(dialog);
					
					dialog.css(css).on('mouseover', function(e){
						ob.addClass('kc-hover-me');
					}).on('mouseout', function(e){
						ob.removeClass('kc-hover-me');
					}).on('click', function(e){
						
						var act = $(e.target).data('act');
						
						if (act === undefined)
							return;
						switch (act) {
							
							case 'edit':
								ob.find('.edit').first().trigger('click');
							break;
							
							case 'add':
								kc.backbone.add(ob.get(0));
							break;
							
							case 'cut':
								kc.backbone.cut(ob.find('div').get(0));
							break;
							
							case 'copy':
								kc.backbone.copy(ob.find('div').get(0));
							break;
							
							case 'copystyle':
							
								if( kc.cfg.copied_style === undefined )
									kc.cfg.copied_style = {};
								
								var name = kc.storage[model].name,
									atts = kc.storage[model].args, 
									params = kc.params.merge( name ),
									is_css = [], values = {};
								
								if( atts['_id'] === undefined ){
									console.warn('KingComposer: Missing id of the element when trying to render css');
									return '';
								}
									
								for( n in params ){
									if( params[n].type == 'css' )
										is_css.push( params[n].name );
								}
								
								for( n in atts ){
									
									if( is_css.indexOf( n ) > -1 || n.indexOf( '_css_inspector' ) === 0 )
										values[n] = atts[n];
								
								}
								
								kc.cfg.copied_style[ name ] = values;
								// update to storage
								kc.backbone.stack.set( 'KC_Configs', kc.cfg );
									
							break;
							
							case 'paste':
								
								content = kc.backbone.stack.get('KC_RowClipboard');
								if (content === undefined || content == '') {
									alert(kc.__.i38);
									return;
								}
								if (content.trim().indexOf('[kc_row ') === 0 || content.trim().indexOf('[kc_row ') === 0) {
									var fid = kc.backbone.push(content);
									ob.closest('.kc-row').after($('#model-'+fid));
									kc.ui.scrollAssistive( $( '#model-'+fid ) );
								}else kc.backbone.push(content, model, 'bottom');
					
							break;
							
							case 'pastestyle':
								
								if( kc.cfg.copied_style === undefined )
									return;
									
								var name = kc.storage[model].name,
									atts = kc.storage[model].args;
								
								if( kc.cfg.copied_style[name] === undefined )
									return;
									
								for( n in kc.cfg.copied_style[name] )
								{
									kc.storage[model].args[n] = kc.cfg.copied_style[name][n];
								}
								
							break;
							
							case 'double':
								var name = kc.storage[model].name;
								if (kc_maps_view.indexOf(name) > -1)
									ob.find('>.kc-vs-control .double').trigger('click');
								else kc.backbone.double(ob.get(0));
							break;
							
							case 'insert':
								
								var name = kc.storage[model].name;
								if (kc_maps_view.indexOf(name) > -1) {
									kc.views.views_sections.do_add_section(ob.closest('.kc-views-sections-wrap').get(0));
								}else kc.views.column.insert(model);
								
							break;
							
							case 'clear':
							
								var atts = kc.storage[model].args, 
									params = kc.params.merge (kc.storage[model].name),
									is_css = [], n;
								
								if (atts['_id'] === undefined)
								{
									console.warn('KingComposer: Missing id of the element when trying to clear css');
									return '';
								}
									
								for (n in params) 
								{
									if (params[n].type == 'css')
										is_css.push (params[n].name);
								}
								
								for (n in atts) 
								{
									
									if (is_css.indexOf (n) > -1 || n.indexOf ('_css_inspector') === 0)
									{
										delete kc.storage[model].args[n];
									}
									
								}
								
							break;
							
							case 'delete':
								ob.find('.delete').first().trigger('click');
							break;
						}
						
						kc.ui.exit_right_click(true);
						
					});
					
					if( e.clientX > ww/2 )
						dialog.css({left: (e.clientX-dialog.width())+'px'});
					else
						dialog.addClass('kc-rc-left');
						
					if( e.clientY+32+dialog.height() > wh )
						dialog.css({top: (wh-32-dialog.height())+'px'});
					else
						dialog.addClass('kc-rc-top');

                    $('body').css({overflow: 'hidden'});
				}
				

				
			},
			
			exit_right_click : function (e) {
				if (e == 'force' || $(e.target).closest('.kc-right-click-dialog').length === 0) {
					$('.kc-right-click-dialog').remove();
					$('#kc-right-click-helper').hide();
					$('.kc-hover-me').removeClass('kc-hover-me');
					$('body').css({overflow: ''});
				}
			},
			
			fix_position_popup : function( pop ){
				/*
				*	Add class identify
				*/
				//pop.addClass('kc-live-editor-popup');
				/*
				*	Resizable popup
				*/
				
				pop.find('.wp-pointer-arrow').on('mousedown', function(e){
					
					if( e.which !== undefined && e.which !== 1 )
						return false;
						
					$('html,body').css({cursor:'col-resize'}).addClass('kc_dragging noneuser kc-ui-dragging');
					
					var mouseUp = function(e){
						
						$(document).off('mousemove');
						$(window).off('mouseup');
						
						setTimeout(function(){
							$('html,body').css({cursor:''}).removeClass('kc_dragging noneuser kc-ui-dragging');
						}, 200 );
						
					},
					
					mouseMove = function( e ){
						
						e.preventDefault();
						var d = e.data;
						d.offset = e.clientX-d.left;
						
						var _w = (d.width+d.offset);
						// pop width > 1000
						if( _w >= 1000 )
							return;
						
						if( _w <= 400 ){
							
							_w = 400;
								
						}
						
						d.el.style.width = _w+'px';
						kc.cfg.live_popup.width = _w+'px';
						
					};

					$(document).off('mousemove').on( 'mousemove', 
					{
							
						el: pop.get(0),
						width: parseInt(pop.width()),
						eleft: parseInt(pop.css('left')),
						left: e.clientX
							
					}, mouseMove );
						
					
					$(window).off('mouseup').on( 'mouseup', 
					{ 
						frame: $('#kc-live-frame').get(0), 
						el: pop
					}, mouseUp );

				});
				
				if( kc.cfg.live_popup === undefined )
					kc.cfg.live_popup = { 
						top: '50px', 
						left: '750px', 
						width: '461px' 
					};
				
				var w_ = $(window).width(),
					_l = parseInt(kc.cfg.live_popup.left),
					_w = parseInt(kc.cfg.live_popup.width);
				
				if( _w + _l > w_ ){
					kc.cfg.live_popup.left = ( w_ - _w ) + 'px';
				}else if( _l < 10 ){
					kc.cfg.live_popup.left = '0px';
				}
				
				pop.css( kc.cfg.live_popup );
				
				if( kc.cfg.live_popup.sticky === true ){
					pop.addClass('kc-popup-stickLeft');
				}
				
			},
			
			instantor : {
				
				mainTmpl : '<span class="instmore">\
						<select class="format">\
							<option value="p">Paragraph</option>\
							<option value="h1">Heading 1</option>\
							<option value="h2">Heading 2</option>\
							<option value="h3">Heading 3</option>\
							<option value="h4">Heading 4</option>\
							<option value="h5">Heading 5</option>\
							<option value="h6">Heading 6</option>\
							<option value="pre">Preformatted</option>\
						</select>\
						<i class="fa-align-justify" data-format="justifyfull" title="Text align justify"></i> \
						<i class="fa-paint-brush" data-act="color" title="Fill color"></i> \
						<input type="text" class="color" readonly /> \
						<i class="fa-underline" data-format="underline" title="Underline"></i> \
						<i class="fa-strikethrough" data-format="strikethrough" title="Strike through"></i> \
						<i class="fa-eraser" data-act="clearformat" title="Clear formatting"></i> \
						<i class="fa-outdent" data-format="outdent" title="Decrease indent"></i> \
						<i class="fa-indent" data-format="indent" title="Increase indent"></i> \
						<i class="sl-close" data-act="close" title="Close dialog" style="float:right"></i>\
					</span> \
					<span class="instmostuse">\
						<i class="fa-bold" title="Bold" data-format="bold"></i> \
						 <i class="fa-italic" title="Italic" data-format="italic"></i> \
						 <i class="fa-list-ul" title="Bulleted list" data-format="insertunorderedlist"></i> \
						 <i class="fa-list-ol" title="Numbered list" data-format="insertorderedlist"></i> \
						 <i class="fa-quote-left" title="Blockquote" data-format="formatblock:blockquote"></i> \
						 <i class="fa-link" title="Insert link" data-act="insertlink"></i> \
						 <i class="fa-align-left" title="Text align left" data-format="justifyleft"></i> \
						 <i class="fa-align-center" title="Text align center" data-format="justifycenter"></i> \
						 <i class="fa-align-right" title="Text align right" data-format="justifyright"></i> \
						 <i class="fa-image" data-act="insertimages" title="Insert images"></i> \
						 <i class="sl-cursor-move" data-act="move" title="Move dialog"></i> \
					</span>',
					
				imageTmpl : '<span class="instmostuse"> \
						<i class="fa-pencil" title="Change image" data-act="changeimage"></i> \
						<i class="fa-align-left" title="Text align left" data-format="justifyleft"></i> \
						<i class="fa-align-center" title="Text align center" data-format="justifycenter"></i> \
						<i class="fa-align-right" title="Text align right" data-format="justifyright"></i> \
						<i class="fa-times" title="Remove image" data-act="removeimage"></i> \
						<label>Width:</label> <input style="width:80px" type="number" data-act="imagewidth" /> \
					</span>',
				
				onclick : function (e, ob) {
					
					var el = ob[0];
					
					if (el.getAttribute('data-raw') != 'true') {
						el.innerHTML = kc.storage[ob[1]].args.content;
						if ($(el).find('>p, >div, >:header').length === 0)
							el.innerHTML = '<p>'+kc.storage[ob[1]].args.content+'</p>';
						el.setAttribute('data-raw', 'true');
					}else if (e.target.tagName == 'IMG') {
						el.setAttribute('data-live-editor', '');
						el.setAttribute('contenteditable', true);
						el.focus();
						return this.clickImage(e, el);
					}
											
					if (el.getAttribute('data-live-editor') != 'open') {
						el.setAttribute('data-live-editor', 'open');
						el.setAttribute('contenteditable', true);
						el.focus();
					}else{
						this.target(e);
						return false;
					}
					
					if (kc.id('kc-instantor') !== null)
						$('#kc-instantor').remove();
					
					$('body').append('<div id="kc-instantor">'+this.mainTmpl+'</div>');
					
					this.possition(e, el, false);
					
					var inst = $('#kc-instantor');
					
					inst.data({el: el});
					
					inst.on('click', function(e){
						var act = $(e.target).data('act'),
							format = $(e.target).data('format');
						switch (act) {
							case 'close' : 
								el.removeAttribute('data-live-editor');
								$('#kc-instantor').remove();
							break;
							case 'color' : 
								$(e.target).next().focus();
							break;
							case 'insertlink' : 
								var sLnk = prompt('Write the URL here','http:\/\/');
								if (sLnk && sLnk !='' && sLnk != 'http://')
									kc.ui.instantor.format('createlink',sLnk);
							break;
							case 'clearformat' :
							
								kc.ui.instantor.clearformat();
								
							break;
							case 'insertimages' :
								kc.tools.media.opens({data: function(atts){
									kc.ui.instantor.format('insertHTML', wp.media.string.image(atts));
								}});
							break;
						}
						
						if (format !== undefined) {
							format = format.split(':');
							kc.ui.instantor.format(format[0], format[1]);
						}
													
					});
					
					inst.find('select.format').on('change', function(e){
						kc.ui.instantor.format('formatblock', this.value);
					});
					
					kc.ui.draggable( inst.get(0), 'i[data-act="move"]' );
					kc.add_action('kc-draggable-end', 'fgE6td4wS', function (e){
						kc.ui.instantor.pos = {top: e.data.style.top, left: e.data.style.left};
					});
					
					inst.find('input.color').each(function(){
						this.color = new jscolor.color(this, {});	
					}).on('change', function(){
						kc.ui.instantor.format('ForeColor', this.value);
					});
					
					// update current target to toolbars
					this.target(e);
					
					return inst;
					
				},
				
				clickImage : function (e, el) {

					if (kc.id('kc-instantor') !== null)
						$('#kc-instantor').remove();
					
					$('body').append('<div id="kc-instantor">'+this.imageTmpl+'</div>');
					
					var inst = $('#kc-instantor');
					inst.data({el: el});
					
					inst.on('click', e.target, function(e){
						
						var act = $(e.target).data('act'),
							format = $(e.target).data('format'),
							el = e.data,
							$this = $(this);
							
						if (format !== undefined)
							return kc.ui.instantor.format(format, null);
							
						if (act == 'removeimage') {
							
							if (el.parentNode.tagName == 'A')
									el = el.parentNode;
										
							var selection = kc.ui.instantor.selection(),
									range = kc.ui.instantor.range();
									
							selection.removeAllRanges();	
							range.selectNode(el);
							selection.addRange(range);

							kc.ui.instantor.format('delete', null);
							
							$(this).data('el').setAttribute('data-live-editor', '');
							$(this).remove();
						}
							
						if (act == 'changeimage') {
							kc.tools.media.opens({data: function(atts){
								
								if (el.parentNode.tagName == 'A')
									el = el.parentNode;
									
								var selection = kc.ui.instantor.selection(),
									range = kc.ui.instantor.range();
									
								selection.removeAllRanges();	
								range.selectNode(el);
								selection.addRange(range);

								kc.ui.instantor.format('delete', null);
								kc.ui.instantor.format('insertHTML', wp.media.string.image(atts));
								$('#kc-instantor').data('el').setAttribute('data-live-editor', '');
								$('#kc-instantor').remove();
								
							}});
						}
						
					});
					
					inst.find('input[data-act="imagewidth"]').val(e.target.getAttribute('width')).on('change', e.target, function(e){
						if (e.data.getAttribute('height') !== undefined)
							e.data.removeAttribute('height');
						e.data.setAttribute('width', this.value.replace(/\D/g,''));
					});
					
					inst.addClass('imgclick').css({top: e.clientY+'px', left: (e.clientX-(inst.width()/2))+'px'});
						
				},
				
				possition : function (e, el, dy) {
					
					if (kc.id('kc-instantor') !== null) {
						
						if (dy === false && kc.ui.instantor.pos !== undefined) {
							$('#kc-instantor').css(kc.ui.instantor.pos);
							return;
						}
						
						var coor = el.getBoundingClientRect(),
							left = coor.left+(coor.width/2)-187,
							top = coor.top > 100 ? coor.top : 100;
						
						$('#kc-instantor').css({top: top+'px', left: left+'px'});
						
					}
					
				},
				
				target : function (e) {
					
					if (kc.id('kc-instantor') !== null) {
						
						var el = e.target, 
							pop = $('#kc-instantor');
							
						pop.data({clicked: e.target});
						pop.find('i').removeClass('active');
						pop.find('input.color').val('').css({background: ''});
						
						while (el !== null && el.getAttribute('contenteditable') === null) {
							
							switch (el.tagName) {
								case 'I' : pop.find('i[data-format="italic"]').addClass('active'); break;
								case 'B' : pop.find('i[data-format="bold"]').addClass('active'); break;
								case 'U' : pop.find('i[data-format="underline"]').addClass('active'); break;
								case 'BLOCKQUOTE' : pop.find('i[data-format="formatblock:blockquote"]').addClass('active'); break;
								case 'A' : pop.find('i[data-act="insertlink"]').addClass('active'); break;
								case 'OL' : pop.find('i[data-format="insertorderedlist"]').addClass('active'); break;
								case 'UL' : pop.find('i[data-format="insertunorderedlist"]').addClass('active'); break;
								case 'STRIKE' : pop.find('i[data-format="strikethrough"]').addClass('active'); break;
								case 'FONT' : 
									if (el.getAttribute('color')) {
										pop.find('input.color').val(el.getAttribute('color')).css({
											background: el.getAttribute('color')
										});
									}
								break;
							}
							
							switch (el.style.textAlign) {
								case 'left' : pop.find('i[data-format="justifyleft"]').addClass('active'); break;
								case 'center' : pop.find('i[data-format="justifycenter"]').addClass('active'); break;
								case 'right' : pop.find('i[data-format="justifyright"]').addClass('active'); break;
								case 'justify' : pop.find('i[data-format="justifyfull"]').addClass('active'); break;
							}
							
							el = el.parentNode;
							
						}
					}
				},
				
				format : function(cmd, value) {
					
					if (kc.id('kc-instantor') !== null) {
						
						var relm = this.elmAtRange(), el = $('#kc-instantor').data('el');
						if (cmd == 'formatblock' && relm !== null) {
							if ($(relm).closest(value.toLowerCase()).length > 0)
								value = '<p>';
						}
						
						if (kc.front !== undefined)
							kc.frame.doc.execCommand(cmd, false, value); 
						else document.execCommand(cmd, false, value);
						
						if (el !== undefined)
							el.focus();
					}
					
				},
				
				elmAtRange : function() {
					
					try {
						var selection = this.selection();
					    var range = selection.getRangeAt(0);
					    
					    if (range !== 0) {
					        var containerElement = range.commonAncestorContainer;
					        if (containerElement.nodeType != 1) {
					            containerElement = containerElement.parentNode;
					        }
					        return containerElement;
					    }
					}catch(ex){};
				    
				    return null;
				    
				},
				
				selection : function(){
					if (kc.front !== undefined)
						return kc.frame.doc.getSelection();
					else document.getSelection();
				},
				
				range : function(){
					if (kc.front !== undefined)
						return kc.frame.doc.createRange();
					else document.createRange();
				},
				
				clearformat : function() {
					
				    var html = "";
				    var sel = this.selection();
				    if (sel.rangeCount) {
			            var container = document.createElement("p");
			            for (var i = 0, len = sel.rangeCount; i < len; ++i) {
			                container.appendChild(sel.getRangeAt(i).cloneContents());
			            }
			            html = container.innerText;
			        }
					if (html === '') {
						
						var select = this.selection().getRangeAt(0),
							el = select['startContainer'].parentNode,
							txt = el.innerText;
						
						if (el.getAttribute('contenteditable'))
							return;
						
						if (el.tagName !== 'P') {
							
							if (el.tagName == 'LI') {
								el = el.parentNode;
								txt = el.innerText;
							}
							
							var selection = this.selection(),
								range = this.range();
											
							selection.removeAllRanges();	
							range.selectNode(el);
							selection.addRange(range);
							
							this.format('delete', null);
							
							$(el).remove();
							
							this.format('insertHTML', '<p>'+txt+'</p>');
							
						}else if (!el.parentNode.getAttribute('contenteditable') && !el.getAttribute('data-model')) {
							
							while (
								el.parentNode && 
								el.parentNode.tagName !== 'BODY' && 
								el.parentNode.getAttribute('contenteditable')
							)el = el.parentNode;
							
							txt = el.innerText;
							$(el).remove();
							
							this.format('insertHTML', '<p>'+txt+'</p>');
							
						}
								
						return;		
					}
						
					this.format('delete', null);
					this.format('insertHTML', '<p>'+html+'</p>');
					
				},
				
				save : function(el) {
					
					var model = el.getAttribute('data-model');
					if (!model || !kc.storage[model])
						return;
						
					kc.storage[model].args.content = el.innerHTML;
					
				}
				
			}
			
		},

		get : {

			model : function( el ){

				var id = $(el).data('model');
				if( id !== undefined && id !== -1 )
					return id;
				else if( el.parentNode ){
					if( el.parentNode.id != 'kc-container' )
						return this.model( el.parentNode );
					else
						return null;
				}else return null;
			},

			storage : function( el ){
				return kc.storage[ this.model(el) ];
			},

			maps : function( el ){
				return kc.maps[ this.storage(el).name ];
			},

			popup : function( el, btn ){

				var pop = $(el).closest('.kc-params-popup');

				if( pop.length === 0 )
					return null;

				if( btn == 'close' )
					return pop.find('.m-p-header .sl-close.sl-func');
				else if( btn == 'save' )
					return pop.find('.m-p-header .sl-check.sl-func');
				else return pop;

			}

		},
		
		add_action : function( name, unique, action ){
			
			if( this.actions === undefined )
				this.actions = {};
			
			if( this.actions[name] === undefined )
				this.actions[name] = {};
				
			if( this.actions[name][unique] === undefined ){
				this.actions[name][unique] = action;
				return true;
			}
			
			return false;
				
		},
		
		do_action : function( name, var1, var2, var3, var4, var5 ){
			
			if( this.actions === undefined || this.actions[name] === undefined )
				return false;
			
			for( var uni in this.actions[name] ){
				if( typeof this.actions[name][uni] == 'function' )
					this.actions[name][uni]( var1, var2, var3, var4, var5 );
			}
			
			return true;
				
		},
		
		remove_action : function( name ){
			
			if( this.actions === undefined || this.actions[name] === undefined )
				return false;
				
			delete this.actions[name];
			
			return true;
			
		},
		
		delete_action : function( name ){
			
			return this.remove_action( name );
			
		},
		
		submit : function(){
			
			/*
			*	This action runs before the form submit
			*	Disable unsaved warning
			*/
			
			kc.confirm( false );

			$('#kc-page-cfg-mode').val( kc.cfg.mode );
			/*
			*	Do not need to do any actions if the builder is inactive
			*/
			if( kc.cfg.mode != 'kc' )
					return;
			
			/*
			*	Remove all input to prevent unused post content from the builder
			*/		
			$('#kc-container').find('form,input,select,textarea').remove();
			/*
			*	Export content from the builder
			*/
			var content = '';
			$('#kc-container > #kc-rows > .kc-row').each(function(){
				var exp =  kc.backbone.export( $(this).data('model') );
				content += exp.begin+exp.content+exp.end;
			});
			
			/*
			*	Warning if the content is empty	
			*/	
			if( content === '' && !confirm( kc.__.i53 ) )
				return false;
			
			/*
			*	Update wp-editor content
			*/	
			$('#content').val(content);
			
			try{
				tinyMCE.get('content').setContent( content );
			}catch(ex){}

		},

		instant_submit : function(){
			
			$('#kc-page-cfg-mode').val(kc.cfg.mode);
			
			/*
			*	Editing sections
			*/
			if( kc.curentContentType !== undefined &&  kc.curentContentType == 'kc-sections' ){
				$('#publishing-action button').trigger('click');
				return;
			}
			
			/*
			*	do not work while saving content
			*/
			if( $('#kc-preload').length > 0 || kc.cfg.mode != 'kc' )
				return;
				
			/*
			*	do not work if missing important field
			*/
			if( $('#post').length === 0 || $('#title').length === 0 || $('#post_ID').length === 0 )
				return;
			
			/*
			*	Start work by show the loading interface
			*/
			kc.msg( kc.__.processing, 'loading' );
			
			/*
			*	Change browser title 
			*/
			document.raw_title = document.title;
			document.title = 'Saving...';
			
			/*
			*	Apply & close all open popups
			*/
			var list = $('.kc-params-popup .sl-check.sl-func, .kc-params-popup .save-post-settings');
			if( list.length > 0 ){
				for( var i = list.length - 1; i>=0; i-- )
					list.eq(i).trigger('click');
			}
				
			/*
			*	Export content from the builder
			*/
			var content = '', 
				id = $('#post_ID').val(), 
				title = $('#title').val();
			
			/*
			*	Fix WP 5
			*/
			
			if ($('.editor-post-title__input').length > 0)
				title = $('.editor-post-title__input').val();
			
			/*
			*	Export each row level 1
			*/
			$('#kc-container > #kc-rows > .kc-row').each(function(){
				var exp =  kc.backbone.export( $(this).data('model') );
				content += exp.begin+exp.content+exp.end;
			});
			/*
			*	Start posting the datas to server
			*/
			var meta = kc.tools.reIndexForm($("input[name^='kc_post_meta']").serializeArray(), []);
			$.post(

				kc_ajax_url,

				{
					'action': 'kc_instant_save',
					'security': kc_ajax_nonce,
					'title': title,
					'id': parseInt( id ),
					'content': content,
					'meta': meta.kc_post_meta
				},

				function (result) {
					
					/*
					*	Revert browser title
					*/
					document.title = document.raw_title;

					result = result.trim();

					if( result == '-1' )
						kc.msg( 'Error: secure session is invalid. Reload and try again', 'error', 'sl-close' );
					else if( result == '-2' )
						kc.msg( 'Error: Post not exist', 'error', 'sl-close' );
					else if( result == '-3' )
						kc.msg( 'Error: You do not have permission to edit this post', 'error', 'sl-close' );
					else kc.msg( 'Successful', 'success', 'sl-check' );
					
					if( $('#content').length > 0 ){
						$('#content-html').trigger('click');
						$('#content').val( content );
					}
					/*
					*	Disable unsaved warning
					*/
					kc.confirm( false );
					kc.check_post_id();

				}
			).complete(function( data ) {
				document.title = document.raw_title;
			    if(data.status !== 200) {
				    kc.msg( 'Your content has been saved, but there seems to be an error occurs. <br />Please check all of your code and make sure there are no errors. ', 'error', 'sl-close' );
			    }
			});

		},

		switch : function( force ){
			/*
			*	if forced to switch to KC 
			*/
			if( force === true ){
				kc.cfg.mode = '';
				if(
					typeof tinymce !== 'undefined' && 
					tinymce.activeEditor !== null
				) tinymce.activeEditor.hidden = true;
			}
			
			
			/*
			*	make sure in backend editor
			*/
			if( kc.front !== undefined )
				return;	
			
			/*
			*	Clear Trash
			*/
			$('#kc-undo-deleted-element').css({top:-132});
			$('#kc-storage-prepare>.kc-model').remove();

			if( kc.cfg.mode == 'kc' ){
				
				// back to classic mode
				if(
					typeof tinymce !== 'undefined' && 
					tinymce.activeEditor !== null
				) tinymce.activeEditor.hidden = false;
				
				kc.cfg.mode = '';
				kc.backbone.stack.set( 'KC_Configs', kc.cfg );
				
				/*
				*	Export content from the builder
				*/
				var content = '';
				$('#kc-container > #kc-rows > .kc-row').each( function(){
					var exp =  kc.backbone.export( $(this).data('model') );
					content += exp.begin + exp.content + exp.end;
				});
				
				/*
				*	Reset builder settings
				*/
				kc.model = 1; 
				kc.storage = [];

				$('#kc-container,.kc-params-popup,#kc-controls').remove();
				$('#postdivrich').css({ visibility: 'visible', display: 'block' });
				$('html,body').stop().animate({ scrollTop : $(window).scrollTop()+3 });
				$('#kc-switcher-buttons,div.gutenberg').show();
				
				$('html').removeClass('kc-html-editor-active');
				$('body').removeClass('kc-editor-active');
				
				/*
				*	make sure wp eidtor is in html mode before export all content from builder
				*/
				$('#content-html').trigger('click');
				$('#content').val(content);
				
				if (wp.blocks) {
					wp.data.dispatch( 'core/editor' ).resetBlocks([]);
					wp.data.dispatch( 'core/editor' ).insertBlocks( 
						wp.blocks.createBlock( 'core/html', {content: content} )
					);
				}
				
				/*
				*	Disable warning unchanged
				*/
				kc.confirm( false );
				$('#kc-page-cfg-mode').val('');
				$.post(kc_ajax_url, {
					'action': 'kc_switch_off',
					'security': kc_ajax_nonce,
					'mode': '',
					'id' : $('#post_ID').val()
				});
				
				return false;

			}else{
				
				// switch to KC
				if(
					typeof tinymce !== 'undefined' && 
					tinymce.activeEditor !== null
				) tinymce.activeEditor.hidden = true;
				
				$('html').addClass('kc-html-editor-active');
				$('body').addClass('kc-editor-active');
				
				$('#kc-switcher-buttons').hide();
				
				kc.cfg.mode = 'kc';
				kc.model = 1;
				kc.storage = [];
				
				$('.edit-post-header__settings .editor-post-publish-button,.edit-post-header__settings .editor-post-publish-panel__toggle').removeAttr('aria-disabled');
		   			
				$('#kc-page-cfg-mode').val('kc');
				
				$.post(kc_ajax_url, {
					'action': 'kc_switch_off',
					'security': kc_ajax_nonce,
					'mode': 'kc',
					'id' : $('#post_ID').val()
				});
				
			}

			/*
			*	Update config about activate of builder
			*/
			kc.backbone.stack.set( 'KC_Configs', kc.cfg );
			
			/*
			*	Re-init the builder
			*/
			kc.views.builder.render();
			kc.params.process();
			kc.ui.mouses.load();
			kc.ui.sortInit();

		},
		
		go_live : function(e){
			
			var id = $('#post_ID').val(),
				type = $('#post_type').val();
			
			if( typeof( id ) == 'undefined' )
				alert( kc.__.i48 );
			else if( typeof( type ) == 'undefined' )
				alert( kc.__.i49 );
			else if( $('#original_post_status').val() == 'auto-draft' ||  $('#original_post_status').val() == 'draft' )
				alert( kc.__.i51 );
			else window.open( kc_site_url+'/wp-admin/?page=kingcomposer&kc_action=live-editor&id='+id );
		
			if (e !== undefined)	
				e.preventDefault();
				
			return false;	
			
		},
		
		msg : function( mes, stt, icon, delay ){
			
			var wrp = $('#kc-preload');
			if( wrp.length === 0 ){
				wrp = $('<div id="kc-preload" style="opacity:0"></div>');
				$('body').append(wrp);
			}else wrp.html('');
			
			if( icon === undefined || icon === '' )
				icon = 'et-lightbulb';
			
			if( stt === undefined )
				stt = '';
			
			if( stt == 'loading' ){
				
				$('#kc-preload')
					.stop()
					.append( '<h3 class="mesg '+stt+'"><span class="kc-loader"></span><br />'+mes+'</h3>' )
					.animate({ opacity : 1 }, 150);
					
			}else if( stt == 'popup' ){
				
				wrp.append('<div class="kc-preload-body">'+mes+'</div>').animate({opacity: 1});
				
				var btn = $('<a href="#" class="enter close"></a>');
				
				wrp.find('.kc-preload-body').append( btn );
				
				$('body').addClass('kc-ui-blur');
				
				btn.on('click', function(){
					$('#kc-preload').remove();
					$('body').removeClass('kc-ui-blur');
				});
				
				wrp.on('click', function(e){
					if( e.target.id == 'kc-preload' ){
						$('#kc-preload').remove();
						$('body').removeClass('kc-ui-blur');
					}
				})
				
			}else{
				
				if( delay === undefined ){
				
					delay = 1500;
					if( stt == 'error' )
						delay = 10000;
				
				}
					
				$('#kc-preload')
					.stop()
					.append( '<h3 class="mesg '+stt+'"><i class="'+icon+'"></i><br />'+mes+'</h3>' )
					.animate({ opacity : 1 }, 150)
					.delay( delay )
					.animate({ opacity: 0 }, function(){ $(this).remove(); });
				
			}
				
		},
		
		std : function( ob, key, std ){
			
			if( typeof( ob ) !== 'object' )
				return std;
			if( ob[key] !== undefined && ob[key] !== '' )
				return ob[key];
			
			return std;
			
		},
		
		confirm : function( stt ) {
			
			if( stt === true ){
		   		window.onbeforeunload = function(){ return kc.__.i01; };
		   		/*
			   	*	fix WP 5
			   	*/
			   	$('.edit-post-header__settings .editor-post-publish-button,.edit-post-header__settings .editor-post-publish-panel__toggle').removeAttr('aria-disabled');
			}else{
				window.onbeforeunload = null;
				$(window).off('beforeunload');
			}
		},
		
		check_post_id : function() {
			if (window.location.href.indexOf('post=') == -1) {
				window.history.pushState({}, "", kc_site_url+'/wp-admin/post.php?post='+$('#post_ID').val()+'&action=edit');
			}
		},
		
		id : function(id){
			return document.getElementById(id);
		}

	}, window.kc );

	$( document ).ready(function(){
		
		if( kc.ui.verify_tmpl() === true )
			kc.init();
			
		/*** 3-rd party compatible ***/
		/* YOAST SEO*/
		if (window.YoastShortcodePlugin !== undefined && 
			wpseoShortcodePluginL10n !== undefined && 
			wpseoShortcodePluginL10n.wpseo_filter_shortcodes_nonce !== undefined
		) {
			window.YoastShortcodePlugin.prototype.parseShortcodes = function(a, b) {
				
				var content = $('#content').val();
				a = [], txt = '';
				if (tinymce.activeEditor !== null) {
					txt += tinymce.activeEditor.getContent();
				}
				
				kc.params.process_shortcodes(content, function(args) {
					if (args.args.content.indexOf('[') === -1)
						txt += args.args.content;
				}, 'kc_column_text');
				
				kc.params.process_shortcodes(content, function(args) {
					if (args.args.image !== undefined)
						txt += '<img src="http://dev.vn/RnD/kingcomposer/wp-admin/admin-ajax.php?action=kc_get_thumbn&amp;id="'+args.args.image+'" alt="" />';
				}, 'kc_single_image');
				
				a.push(txt);
				
	            return jQuery.post(ajaxurl, {
	                action: "wpseo_filter_shortcodes",
	                _wpnonce: wpseoShortcodePluginL10n.wpseo_filter_shortcodes_nonce,
	                data: a
	            }, function(a) {
	                this.saveParsedShortcodes(a, b)
	            }.bind(this));
			}
			window.YoastShortcodePlugin.prototype.replaceShortcodes = function(a) {
                
                var content = '';
                kc.params.process_shortcodes(a, function(args) {
					if (args.args.content.indexOf('[') === -1)
						content += args.args.content+"\n";
				}, 'kc_column_text');
					
                return content;
            }
        }
		
	});
	
	$( window ).on('message', function(e) {

		var data = e.originalEvent.data;

		if (
			typeof data == 'object' && 
			data.action == 'kcpush' && 
			data.content !== undefined && 
			data.content !== ''
		) {
			top.kc.backbone.push(atob(data.content));
			$('#kc-presets-library-pop').hide();
		}
		
	});
	
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
	
})( jQuery );

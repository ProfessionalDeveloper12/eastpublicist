/*
 * KingComposer Page Builder
 *
 * (c) Copyright king-theme.com
 *
 *
 * kc.tools.js
 *
*/

( function($){
	
	if( typeof( kc ) == 'undefined' )
		window.kc = {};
		
	$().extend( kc.tools, {
		
		esc_slug : function (str){
			
			if( str === undefined )
				return 'king-composer';
			str = str.replace(/^\s+|\s+$/g, '');
			str = str.toLowerCase();
			
			var from = "àáäâèéëêìíïîòóöôùúüûñç·/,:;";
			var to   = "aaaaeeeeiiiioooouuuunc-----";
		
			for (var i=0, l=from.length ; i<l ; i++)
			{
				str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
			}
		
			str = str.replace(/[^a-z0-9 -\_]/g, '')
				.replace(/\s+/g, '-')
				.replace(/-+/g, '-');
		
			return str;
			
		},
		
		esc_attr : function( str ) {
			if( !str ){
				return '';
			}
		    return str.toString()
		    		  .replace(/</g, ':lt:')
		    		  .replace(/>/g, ':gt:')
		    		  .replace(/\[/g, ':lsqb:')
		    		  .replace(/\]/g, ':rsqb:')
		    		  .replace(/"/g, ':quot:')
		    		  .replace(/'/g, ':apos:');
		},
		
		unesc_attr : function( str ) {
			if( !str ){
				return '';
			}
		    return str.toString()
		 		      .replace(/:lt:/g, '<')
		    		  .replace(/:gt:/g, '>')
		    		  .replace(/:lsqb:/g, '[')
		    		  .replace(/:rsqb:/g, ']')
		    		  .replace(/:quot:/g, '"')
		    		  .replace(/:apos:/g, '\'');
		},

		esc : function( str ) {
			if( !str ){
				return '';
			}
		    return str.toString().replace(/&/g, '&amp;')
		    		  .replace(/</g, '&lt;')
		    		  .replace(/>/g, '&gt;')
		    		  .replace(/"/g, '&quot;')
		    		  .replace(/'/g, '&apos;');
		},
		
		unesc : function(str) {
			if( str == undefined ){
				return '';
			}
		    return str.toString().replace(/&amp;/g, '&')
		    		  .replace(/&lt;/g, '<')
		    		  .replace(/&gt;/g, '>')
		    		  .replace(/&quot;/g, '"')
		    		  .replace(/&apos;/g, '\'');
		},
		
		rawdecode : function( input ){
			return decodeURIComponent( input+'' );
		},
		
		rawencode : function( input ){
			input = (input+'').toString();
			return encodeURIComponent( input ).
				   replace(/!/g,'%21').
				   replace(/'/g,'%27').
				   replace(/\(/g,'%28').
				   replace(/\)/g,'%29').
				   replace(/\*/g,'%2A');
		},
		
		decode_css : function( css ){
			
			var css_code = '';
			
			css = css.replace( /\s+/g, ' ' )
				 .replace(/\/\*[^\/\*]+\*\//g,'')
				 .replace(/\"/g, "'")
				 .replace(/[^a-zA-Z0-9\-\_\. \:\(\)\%\+\~\;\#\'\!\{\}\@\/]+/g,'')
				 .trim().split( '{' );
			
			for( var n in css  ){
				
				if( css[n].indexOf('}') > -1 )
				{
					css[n] = css[n].split('}');
					css[n][0] = css[n][0].split(';');
					for( var m in css[n][0] )
					{
						if( css[n][0][m].trim() != '' )
							css_code += "	"+css[n][0][m]+";\n";
					}
					if( css[n][1].trim() != '' )
						css_code += "}\n"+css[n][1]+"{\n";
					else 
						css_code += "}\n";	
					if( css[n][2] != undefined )	
						css_code += "}\n";	
				}
				else if( css[n].trim() != '' )
				{
					css_code += css[n]+"{\n"
				}
				
			}
			
			return css_code;

		},
		
		encode_css : function( css ){
			
			if( css == undefined )
				css = '';
			css = css.replace(/\/\*[^\/\*]+\*\//g,'')
					 .replace( /\ \ /g, '' )
					 .replace(/[^a-zA-Z0-9\-\_\. \:\(\)\%\+\~\;\!\#\'\{\}\@\/]+/g,'').trim();
					 
			return css;
				
		},
		
		nfloat : function( n, m ){
			
			n = n.toString();
			if( m === undefined )
				m = 2;
				
			if( n.indexOf('.') > -1 ){
				
				return parseFloat( n.substr( 0, n.indexOf('.')+m+1 ) );
				
			}else return parseFloat( n );
			
		},
		
		getFormData : function( pop ){
			
			/*
			*	correct data type before export
			*/
			
		    var form = pop.find('form.fields-edit-form').get(0),
		    	encodes = [],
		    	unindexed = pop.find('form.fields-edit-form .kc-param').serializeArray();

			pop.find('form.fields-edit-form [data-encode="base64"]').each(function(){
				encodes.push( this.name );
			});

			return this.reIndexForm( unindexed, encodes );
		    
		},
		
		reIndexForm : function( unindexed, encodes ){
			
			var indexed = {}, avoidRepeat = {}, name, obs, j, k;
			
		    $.map( unindexed, function( n, i ){
			    
			    if( encodes.indexOf( n['name'] ) > -1 )
				    n['value'] = kc.tools.base64.encode(n['value']);
			    
			    if( n['name'].indexOf('[') == -1 ){
				    
				    if( n['value'] != '' ){
					    if( indexed[ n['name'] ] == undefined || indexed[ n['name'] ] == '__empty__' )
							indexed[ n['name'] ] = n['value'];
						else
							indexed[ n['name'] ] += ','+n['value'];
				    }else if( indexed[ n['name'] ] === undefined ){
						indexed[ n['name'] ] = '';
				    }
			    	
			    }else{
				    
				    n['name'] = "["+n['name'].replace('[','][');
				    name = n['name'].replace( /\[/g, "['" ).replace( /\]/g, "']" );
		        
			        obs = [];
			       
			        [].forEach.call( n['name'].split(']['), function( sp ){
				        sp = sp.replace( /\[/g, '' ).replace( /\]/g, '' ).trim();
				        obs[ obs.length ] = sp;
			        });
			        
			        if( obs.length > 0 ){
				        k = '';
				        for( j=0; j<obs.length; j++){
					        k += "['"+obs[j]+"']";
					        eval("if( indexed"+k+"==undefined )indexed"+k+"={};");
				        }
			        }
			        
			        var query = "if( typeof(indexed"+name+") != 'string' )indexed"+name+"=n['value'];else if(n['value']!=='') indexed"+name+"+=','+n['value'];";
				    eval( query );
			    
			    }
		        
		    });
			
			delete avoidRepeat, name, obs, j, k;
			
		    return indexed;

		},
		
		basename : function( str ){
			
			var base = str.split(/[\\/]/).pop();
				
		    if( base.lastIndexOf(".") != -1 )       
		        base = base.substring( 0, base.lastIndexOf(".") );
		        
			return base;
		       	
		},
		
		toClipboard : function( str ) {
			
			if (window.clipboardData) {
                window.clipboardData.setData ("Text");
            }else {
	   			
	   			document.oncopy = function(event) {
					event.clipboardData.setData('text', str);
					event.preventDefault();
				};
				
				document.execCommand("Copy", false, null);
				
				document.oncopy = null;
				
			}
		},
		
		rgb2hex : function( rgb ) {
	
			if( rgb.indexOf('rgb') === -1 || rgb.indexOf('(') === -1 || rgb.indexOf(')') === -1 )
				return rgb;
				
			rgb = rgb.split('(')[1].split(')')[0].split(',');
			
			function hex(x) {
				return ("0" + parseInt(x.trim()).toString(16)).slice(-2);
			}
			
			return "#" + hex(rgb[0]) + hex(rgb[1]) + hex(rgb[2]);

		},
		
		hex2rgb : function(hex) {

		    r = hex.match(/^#([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i);
		    if (r) {
		            return r.slice(1,4).map(function(x) { return parseInt(x, 16); });
		    }
		    // short version
		    r = hex.match(/^#([0-9a-f])([0-9a-f])([0-9a-f])$/i);
		    if (r) {
		            return r.slice(1,4).map(function(x) { return 0x11 * parseInt(x, 16); });
		    }
		    return hex;
		},
		
		base64 : {
		
		    encode : function (input){
			    
			    if( input === undefined || input === '' ){
					return '';
				}
				
				try
			    {
				    return window.btoa(unescape(encodeURIComponent( input )));
				}
				catch (ex){return input;}

		    },
		
		    decode : function (input){

			    if( input === undefined || input === '' ){
					return '';
				}
			    
			    try
			    {
				    return decodeURIComponent(escape(window.atob( input )));
				}
				catch (ex){return input;}
	
		    },

		},
		
		media : {
			
			el : null,
			
			callback : null,
			
			uploader : null,
			
			open : function( e ){

				if( typeof e.preventDefault == 'function' )
					e.preventDefault();


				atts = $().extend(
							{ frame: 'select', multiple: false, title: 'Choose Image', button: 'Choose Image', type: 'image' },
							e.data.atts );

				kc.tools.media.el = this;
				
				if( typeof e.data.callback == 'function' )
					kc.tools.media.callback = e.data.callback;
				else kc.tools.media.callback = null;
				
		        if ( kc.tools.media.uploader ) {
		           return kc.tools.media.uploader.open();
		        }

				var insertImage = wp.media.controller.Library.extend({
				    defaults :  _.defaults({
			            id: 'insert-image',
			            title: atts.title,
			            button: {
			                text: atts.button
			            },
			            multiple: false,
						editing:   true,
						allowLocalEdits: true,
			            displaySettings: true,
			            displayUserSettings: true,    
			            type : atts.type
				      }, wp.media.controller.Library.prototype.defaults )
				});

		        //Extend the wp.media object
		        kc.tools.media.uploader = wp.media.frames.file_frame = wp.media({         
		            frame: atts.frame,
		            state : 'insert-image',
				    states : [ new insertImage() ]
		        });
				
		        kc.tools.media.uploader.on('select', function( e ) {

			        var currentSize = $('.attachment-display-settings .size').val()
		        	var state = kc.tools.media.uploader.state('insert-image');
		            var attachments = state.get('selection');

		            if( attachments.length === 0 ){

			            if( $('#embed-url-field').get(0) && $('#embed-url-field').val() != null ){
				            if( typeof kc.tools.media.callback == 'function' )
					     	 	kc.tools.media.callback( { 
						     	 		url: $('#embed-url-field').val(), sizes: {} }, 
						     	 		$(kc.tools.media.el) 
						     	 	);
			            }
			            
		            }else{

			            attachments.map( function( attachment ) {
				            
					     	 var attachment = attachment.toJSON();
					     	 attachment.size = currentSize;
					     	 if( typeof kc.tools.media.callback == 'function' )
					     	 	kc.tools.media.callback( attachment, $(kc.tools.media.el) );
					    });
				    
				    }
		
		        });
				
				kc.tools.media.uploader.on('open', function( e ) {
					 	
				 	var ids = $(kc.tools.media.el).parent().find('.kc-param').val();
				 	if( ids === undefined || ids == null || ids == '' || ids == 'undefined')
				 		return;
				 		
				 	ids = ids.split(',');
			 	
				 	var selection = kc.tools.media.uploader.state().get('selection');
				 	var attachments = [];
				 	
				 	ids.forEach(function( id ){
						attachments[ attachments.length ] = wp.media.attachment( id );
					});
					
					selection.add( attachments );

				 						
				});
				
		        //Open the uploader dialog
		       return kc.tools.media.uploader.open();
			   
		    },		
		    
		    els : null,
			
			callbacks : null,
			
			uploaders : null,
			
			opens : function( e ){
				
				if( typeof e.preventDefault == 'function' )
					e.preventDefault();
				
				kc.tools.media.els = this;
				
				if( typeof e.data == 'function' )
					kc.tools.media.callbacks = e.data;	
				else kc.tools.media.callbacks = null;
				
		        if ( kc.tools.media.uploaders ) {
		           kc.tools.media.uploaders.open();
		           return false;
		        }
				
		        //Extend the wp.media object
		        kc.tools.media.uploaders = wp.media.frames.file_frame = wp.media({
		            title: kc.__.i46,
		            button: {
		                text: kc.__.i46
		            },
		            multiple: true,
					editing:   true,
					allowLocalEdits: true,
		            displaySettings: true,
		            displayUserSettings: true,
		            
		        });
		 
		        kc.tools.media.uploaders.on('select', function( e ) {
		
		            var attachments = kc.tools.media.uploaders.state().get('selection');
		            attachments.map( function( attachment ) {
				     	 var attachment = attachment.toJSON();
				     	 if( typeof kc.tools.media.callbacks == 'function' )
				     	 	kc.tools.media.callbacks( attachment, $(kc.tools.media.els) );
				    });
		
		        });
		        
		        kc.tools.media.uploaders.on('open', function( e ) {
					
					// Maybe we dont need to active selected images
					return false;
					 	
				 	var ids = $(kc.tools.media.els).parent().find('.kc-param').val();
				 	if( ids === undefined || ids == null || ids == '' )
				 		return;
				 		
				 	ids = ids.split(',');
			 	
				 	var selection = kc.tools.media.uploaders.state().get('selection');
				 	var attachments = [];
				 	
				 	ids.forEach(function( id ){
						attachments[ attachments.length ] = wp.media.attachment( id );
					});
					
					selection.add( attachments );
				 						
				});
		        
		        //Open the uploader dialog
				kc.tools.media.uploaders.open();
			   
			   return false;
			   
		    }
	
		},
		
		popup : new kc.backbone.views( 'no-model' ).extend({
			
			margin_top : $('html').get(0).offsetTop,
			
			no_close : false,
			
			render : function( el, atts ){
				
				var keepCurrent = false;
				if( atts != undefined ){
					if( atts.keepCurrentPopups == true ){
						keepCurrent = true;
					}
				}
				
				if( keepCurrent == false )
					$('.kc-params-popup .sl-close.sl-func').trigger('click');
				
				$('.sys-colorPicker').remove();
				$('.kc-controls .more.active').removeClass('active');
				
				var pop_width = 580;
				if( atts.width != undefined )
					pop_width = atts.width;
				var coor = this.coordinates( el, pop_width, keepCurrent );
				
				var atts = $().extend({ 
						top: coor[0], 
						left: coor[1],
						pos: coor[2],
						bottom: coor[3],
						tip: coor[4],
						width: pop_width,
						class: '',
						float: false,
						drag: true,
						content: '', 
						title: 'Settings',
						help: '',
						footer: true,
						footer_ext: '',
						scrollTo: true,
						save_text: kc.__.save,
						cancel_text: kc.__.cancel
					}, atts );
				
				if( atts.footer === false && atts.class.indexOf('no-footer') == -1 )
					atts.class += ' no-footer';	
				
				if( atts.float === true ){
					atts.drag = false;
					atts.class += ' kc-popup-float';
					atts.scrollTo = false;
				}
						
				this.el = $( kc.template( 'popup', atts ) );
				this.el.data({ 'button' : el, 'keepCurrentPopups' : keepCurrent, 'tab_active' : 0 });
				
				if( atts.float === true ){
					this.el.on('click', function(e){
						if( e.target === this )
							$(this).find('button.cancel').trigger('click');
					});
					$('html').css({'overflow': 'hidden'});
				}
				
				if( atts.tip != 0 )
					this.el.find( '.wp-pointer-arrow' ).css({marginRight: -atts.tip+'px'});
								
				if( atts.scrollBack == true )
					this.el.data({ 'scrolltop' : $(window).scrollTop() });
				
				$('body').append( this.el  );
				
				if( atts.drag == true ){
					kc.ui.draggable( this.el.get(0), 'h3.m-p-header' );
					this.el.find('h3.m-p-header').addClass('kc-ui-draggable');
				}
				
				$( this.el ).css({opacity: 1});
				
				if( atts.scrollTo === true ){
					
					setTimeout( function( pop, atts ){
						
						var wsct = $(window).scrollTop(), wh = $(window).height(),
							wheight = wsct+(wh*0.1);
						
						if( wh > 800 )
							pop.find('.m-p-body').css({ 'max-height': (wh - 250)+'px' });
							
						var pop_rect = pop.get(0).getBoundingClientRect();
							
						if( atts.top > wheight && atts.bottom === 0 ){
							
							if( pop_rect.height < wh - 50 ){
								kc.ui.scrollAssistive( atts.top - ((wh-pop_rect.height)/2) , true );
							}else kc.ui.scrollAssistive( (atts.top - 50), true );
							
						}else if( pop_rect.top < 0 )
							kc.ui.scrollAssistive( (wsct+pop_rect.top) - 50, true );
						
					}, 1, this.el, atts );
					
				}
				
				return this.el;
				
			},
			
			coordinates : function( el, pop_width, keepCurrent ){
				
				var grids;
				if( $(el).closest('#kc-container').get(0) )
					grids = document.getElementById('kc-container').getBoundingClientRect();
				else if( document.getElementById('wpbody-content') !== null )
					grids = document.getElementById('wpbody-content').getBoundingClientRect();	
				else if( document.getElementById('content') !== null )
					grids = document.getElementById('content').getBoundingClientRect();
				else grids = document.getElementsByTagName('body')[0].getBoundingClientRect();
				
				if( el === undefined )
					return [0,0,0,0];
						
				var coor = el.getBoundingClientRect(),
					swidth = (grids.width/3),
					sleft = coor.left-grids.left,
					top = coor.top+$(window).scrollTop()+coor.height-this.margin_top,
					bottom = 0,
					left = coor.left+$(window).scrollLeft()+(coor.width/2),
					tip = 0,
					pos = '',
					wheight = $(document).height(),
					wwidth = $(document).width();
					
				if( sleft < swidth ){
					pos = 'left';
					left -= 63;
				}else if( sleft > swidth && sleft < swidth*2 ){
					pos = 'center';
					left -= (pop_width/2);
				}else if( sleft > swidth*2 && sleft < swidth*3 ){
					pos = 'right';
					left -= (pop_width-63);
				}
				
				if( wheight - top < 200 && $(window).scrollTop() > 0 ){
					
					bottom = wheight-top+(coor.height/2);
					$('html').height( wheight - parseInt( $('html').css('padding-top') ) );
				
				}else if( keepCurrent !== true ){
					$('html').height('');
				}
				
				if( left < 50 ){
					tip = left - 50;
					left = 50;
				}
				
				if(  left+swidth > wwidth ){
					left -= ( (left+swidth) - wwidth ) + 50;
				}
				return [ top, left, pos, bottom, tip ];
				
			},
			
			events : {
				'.m-p-controls>li>.cancel,.m-p-header .sl-close:click' : 'cancel',
				'.m-p-header .m-p-toggle:click' : 'toggle',
				'.m-p-controls>li>.save,.m-p-header .sl-check:click' : 'save'
			},
			
			cancel : function( e ){
				
				$('html').css({'overflow': ''});
				kc.do_action( 'before_cancel_popup', this );
				
				// We will dont close the popup when in instant saving
				if( $('#kc-preload').length > 0 || kc.tools.popup.no_close === true ){
					kc.tools.popup.no_close = false;
					return;
				}

				if( e.target.tagName == 'INPUT' )
					return;
				
				var el = $(this).closest('.kc-params-popup'), 
					keepCurrent = el.data('keepCurrentPopups'),
					beforecalb, calb, aftercalb, i, prevent = false;
				
				if( typeof el.data('before_cancel') !==  undefined ){
					beforecalb = el.data('before_cancel');
					if( typeof beforecalb == 'function' ){
						if( beforecalb( el, e ) == 'prevent' )
							prevent = true;
					}else if( beforecalb !== undefined && beforecalb.length > 0 ){
						for( i = 0; i< beforecalb.length; i++ ){
							if( typeof beforecalb[i] == 'function' ){
								if( beforecalb[i]( el, e ) == 'prevent' )
									prevent = true;
							}
						}
					}
				}
				
				if( prevent === true )
					return;
					
				if( typeof el.data('cancel') !==  undefined ){
					calb = el.data('cancel');
					if( typeof calb == 'function' ){
						calb( el );
					}else if( calb !== undefined && calb.length > 0 ){
						for( i = 0; i< calb.length; i++ ){
							if( typeof calb[i] == 'function' && calb[i]( el ) == 'prevent' )
								prevent = true;
						}
					}
				}
				
				if( prevent === true )
					return;
					
				if( typeof el.data('after_cancel') !==  undefined ){
					aftercalb = el.data('after_cancel');
					if( typeof aftercalb == 'function' ){
						aftercalb( el );
					}else if( aftercalb !== undefined && aftercalb.length > 0 ){
						for( i = 0; i< aftercalb.length; i++ ){
							if( typeof aftercalb[i] == 'function' && aftercalb[i]( el ) == 'prevent' )
								prevent = true;
						}
					}
				}
				
				if( prevent === true )
					return;
				
				if( el.data('scrolltop') != undefined )
					e.data.scrollback( el.data('scrolltop'), el.data('button') );
					
				if( el.data('keepCurrentPopups') !== true )
					$('html').height('');
					
				el.remove();
				$('.sys-colorPicker').remove();
				// remove date picker
				$('.pika-single').remove();
				
				if( keepCurrent == false )
					$('.kc-params-popup .sl-close.sl-func').trigger('click');
				
				kc.do_action( 'after_cancel_popup', this );
				
			},
			
			save : function( e ){
				
				kc.do_action( 'before_save_popup', this );
				
				var el = $(this).closest('.kc-params-popup'), 
					keepCurrent = el.data('keepCurrentPopups'), 
					beforecalb, calb, aftercalb, i;
				
				e.data.el = el;
				
				if( typeof el.data('before_callback') !==  undefined ){
					beforecalb = el.data('before_callback');
					if( typeof beforecalb == 'function' ){
						beforecalb( el );
					}else if(  beforecalb !== undefined && beforecalb.length > 0 ){
						for( i = 0; i< beforecalb.length; i++ ){
							if( typeof beforecalb[i] == 'function' )
								beforecalb[i]( el );	
						}
					}
				}
				
				if( typeof el.data('callback') !==  undefined ){
					calb = el.data('callback');
					if( typeof calb == 'function' ){
						calb( el );
					}else if( calb !== undefined && calb.length > 0 ){
						for( i = 0; i< calb.length; i++ ){
							if( typeof calb[i] == 'function' )
								calb[i]( el );	
						}
					}
				}
				
				if( typeof el.data('after_callback') !==  undefined ){
					aftercalb = el.data('after_callback');
					if( typeof aftercalb == 'function' ){
						aftercalb( el );
					}else if( aftercalb !== undefined && aftercalb.length > 0 ){
						for( i = 0; i< aftercalb.length; i++ ){
							if( typeof aftercalb[i] == 'function' )
								aftercalb[i]( el );	
						}
					}
				}
				
				// We will dont close the popup when in instant saving
				if( $('#kc-preload').length > 0 || kc.tools.popup.no_close === true ){
					kc.tools.popup.no_close = false;
					return;
				}
					
				if( el.data('scrolltop') != undefined )
					e.data.scrollback( el.data('scrolltop'), el.data('button') );
				
				//el.remove();
				el.find('.sl-close.sl-func').trigger('click');
				
				if( keepCurrent == false ){
					$('.kc-params-popup .sl-close.sl-func').trigger('click');
					$('html').height('');
				}
				
				kc.do_action( 'after_save_popup', this );
				
			},
			
			toggle : function( e ){
				
				var pop = $(this).closest('.kc-params-popup');
				if( pop.hasClass('kc-popup-collapse') )
					pop.removeClass('kc-popup-collapse')
				else pop.addClass('kc-popup-collapse')
					
			},
			
			scrollback : function( sctop, btn ){
				
				
				var now = $(window).scrollTop();
				
				if( Math.abs( sctop - now ) > 200 ){
					
					kc.ui.scrollAssistive( sctop );
					
				}
				
			},
			
			add_tab : function( pop, args ){
				
				args = $().extend( { title: '', class: '', cfg: '', callback: function(){} }, args );
				
				var ul = pop.find('.m-p-wrap ul.kc-pop-tabs'), 
					slug = 'kc-tab-'+Math.abs(parseInt(Math.random()*1000)), 
					li = $('<li data-tab="'+slug+'" data-cfg="'+args.cfg+'" class="'+args.class+'">'+args.title+'</li>');
				
				if( !ul.get(0) ){
					
					/* if this is first tab be added */
					ul = $('<ul class="kc-pop-tabs"></ul>');
					
					if( pop.find('.fields-edit-form').length > 0 ){
						
						var fli = $('<li data-tab="fields-edit-form" class="kc-tab-general-title active"><i class="et-tools"></i> General</li>');
						
						ul.append( fli );
						fli.on( 'click', function( e ){
							
							var wrp = $(this).closest('.m-p-wrap');
							
							wrp.find('>.kc-pop-tabs li').removeClass('active');
							$(this).addClass('active');
							
							wrp.find('.m-p-body>.kc-pop-tab').removeClass('form-active');
							wrp.find('.m-p-body>.fields-edit-form').addClass('form-active');
							
							if( e.originalEvent !== undefined )
								$(this).closest('.kc-params-popup').
									data({ tab_active: $(this).parent().find('>li').index( this ) });
							
							kc.do_action('popup_tab_switch', this );
							
						});
						
					}
					
					
					pop.find('.m-p-header').after( ul );
					
				}
				
				ul.append( li );
				
				/* Add event for new tab which just be created */
				li.on( 'click', args.callback, function(e){
					
					var slug = $(this).data('tab'), wrp = $(this).closest('.m-p-wrap').find('>.m-p-body');
					
					$(this).closest('.m-p-wrap').find('>.kc-pop-tabs li').removeClass('active');
					$(this).addClass('active');
					wrp.find('>.kc-pop-tab').removeClass('form-active');
					var tab = wrp.find('>.'+slug), click_actived = false, this_index = $(this).parent().find('>li').index( this );
					
					if( $(this).closest('.kc-params-popup').data('tab_active') == this_index )
							click_actived = true;
					
					if( e.originalEvent !== undefined )
						$(this).closest('.kc-params-popup').data({ tab_active: this_index });
					
					kc.do_action('popup_tab_switch', this );
					
					if( tab.get(0) ){
						
						tab.addClass('form-active');
						/*
						*	If the tab is actived and click on it
						*	We don't need to run callback
						*/
						if( click_actived === true )
							return;
							
						var callback = $(this).data('callback');
						
						if( typeof callback == 'function' )
							callback( this, tab );
						
						return;
						
					}
					
					tab = $('<form class="kc-pop-tab '+slug+' form-active"></form>');
					
					wrp.append( tab );
					
					tab.on( 'submit', function(){
						$(this).closest('.kc-params-popup').find('.m-p-footer .save').trigger('click');
						return false;
					});
					
					if( typeof e.data == 'function' )
						tab.append( e.data( this , tab ) );
					
					var callback = $(this).data('callback');
					if( typeof callback == 'function' )
						callback( this, tab );
						
				});
				
				return li;
				
			},
			
			callback : function( pop, args, unique ){
				
				// prevent adding the same callback twice
				
				if( unique !== undefined ){
					
					var list_uniques = pop.data( 'callback_uniques' );
					
					if( list_uniques === undefined )
						list_uniques = [];
					else if( list_uniques.indexOf( unique ) > -1 )
						return;
						
					list_uniques.push( unique );
					
					pop.data({ 'callback_uniques': list_uniques });
					
				}
				
				var calls;
					
				for( var st in args  ){
					
					calls = [];
					
					if( pop.data( st ) !== undefined ){
						if( typeof pop.data( st ) == 'function' )
							calls.push( pop.data( st ) );
						else if( typeof pop.data( st ) == 'object' )
							calls = pop.data( st );
					}
					
					calls.push( args[st] );
					
					pop.data( st, calls );
					
				}
					
			},
			
			close_all : function() {
				$('.kc-params-popup .sl-close.sl-func').trigger('click');
			}
			
		}),
		
		editor : {
			
			insert : function( id, html ){
				
				var editor,
					hasTinymce = typeof tinymce !== 'undefined',
					hasQuicktags = typeof QTags !== 'undefined';

				wpActiveEditor = id;
				
				if ( hasTinymce ) {
					editor = tinymce.get( wpActiveEditor );
				}

				if ( editor && ! editor.isHidden() ) {
					editor.execCommand( 'mceInsertContent', false, html );
				} else if ( hasQuicktags ) {
					QTags.insertContent( html );
				} else {
					document.getElementById( wpActiveEditor ).value = html;
				}

			},
			
			init : function ( textarea ) {
				
				if ( $( '#wp-link' ).parent().hasClass( 'wp-dialog' ) ) {
					$( '#wp-link' ).wpdialog( 'destroy' );
				}
				
				textarea.val( switchEditors.wpautop( textarea.val() ) );
				
				var eid = textarea.attr( "id" ), tmi = window.tinyMCEPreInit, tmic = tmi.mceInit, tmiq = tmi.qtInit;
				try {
					
					if ( _.isUndefined( tinyMCEPreInit.qtInit[ eid ] ) ) {
						tmiq[ eid ] = _.extend( {}, tmiq[ window.wpActiveEditor ], { id: eid } );
					}
					if ( tmi && tmic[ window.wpActiveEditor ] ) {
						tmic[ eid ] = _.extend( 
							{}, 
							tmic[ window.wpActiveEditor ],
							{
								resize: 'vertical',
								height: 250,
								id: eid,
								setup: function ( e ) {
									if ( typeof(e.on) != 'undefined' ) {
										e.on( 'init', function ( e ) {
											e.target.focus();
											window.wpActiveEditor = eid;
										});
									} else {
										e.onInit.add( function ( e ) {
											e.focus();
											window.wpActiveEditor = eid;
										});
									}
								}
							}
						);
						
						tmic[ eid ].wp_autoresize_on = false;
						
						window.wpActiveEditor = eid;
											
					}
					
					quicktags( tmic[ eid ] );
					QTags._buttonsInit();
					
					if ( window.tinymce ) {
						
						window.switchEditors && window.switchEditors.go( eid, 'tmce' );
						
						if ( tinymce.majorVersion === "4" ) {
							
							tinymce.execCommand( 'mceAddEditor', true, eid );
							
							var textarea = $('#'+eid);
								
							if( tinyMCE.get( eid ) !== null ){
								
								tinyMCE.get( eid ).on('keyup mouseup change', function(e) {
									textarea.val( tinyMCE.activeEditor.getContent({format : 'raw'}) ).change();
								});
								
							}
							
							textarea.on('keyup mouseup', function(){textarea.change();});
							
						}
						
					}
					
				} catch ( e ) {
					$( '#wp-' + eid + '-wrap' ).html('Tinymce Error!');
					if ( console && console.error ) {
						console.error( e );
					}
				}
			}			
		},
		
		get_icons : function(){
			
			if( kc.icons != undefined )
				return kc.icons;
			
			function css_text(x) { return x.cssText; }
			
			var files = document.querySelectorAll('*[id^="kc-sys-icon-"]'), html = '', css;
			
			if( !files || files.length === 0 )
				return '';
				
			for( var i=0; i < files.length; i++ ){
				
				css = Array.prototype.map.call( files[i].sheet.cssRules, css_text ).join('\n');	
			
				css = css.split('::before');
				
				css.forEach(function( i ){
					i = i.split('.')[1];
					if( i !== undefined && i.indexOf('/') == -1 )
						html += '<i title="'+i.replace(/[^a-z-0-9]/g, "")+'" class="'+i.replace(/[^a-z-0-9]/g, "")+'"></i>';
				});
			}
			
			kc.icons = html;

			return html;
			
		},
		
		filter_images : function(str) {
				
			var m, str, regxx = new RegExp('\%SITE\_URL\%(.+?)(\'|\"|\\)|\ )', 'g');

			while (m = regxx.exec( str ) ) {

                if( m[0].indexOf( 'kc_get_thumbn' ) === -1 ){
                    str = str.replace( m[0], kc_ajax_url+'?action=kc_get_thumbn&type=filter_url&id='+encodeURIComponent(m[1])+m[2]);
                }else{
                    str = str.replace( m[0], kc_site_url + m[1]+m[2]);
                }

			}

			return str;
			
		},
		
		reverse : function(s) {
			
		  var o = [];
		  for (var i = 0, len = s.length; i <= len; i++)
		    o.push(s.charAt(len - i));
		  
		  return o.join('');
		  
		}
		
	} );
	
	
} )( jQuery );

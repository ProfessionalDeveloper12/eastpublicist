/*
 * King Composer
 *
 * URI KingComposer.com
 *
 * Copyright king-theme.com
 *
 *
*/

var kc_front = ( function($){

	jQuery.extend( jQuery.easing, {
		easeInOutQuart: function (x, t, b, c, d) {
			if ((t/=d/2) < 1) return c/2*t*t*t*t + b;
			return -c/2 * ((t-=2)*t*t*t - 2) + b;
		},
	});

	var $window = $(window);
    var windowHeight = $window.height();

    $window.resize(function() {
        windowHeight = $window.height();
        kc_front.row_action(true);
    });

    $.fn.kc_parallax = function() {

        var $this = $(this), el_top;
        $this.each(function() { el_top = $this.offset().top; });
        function update() {
            var pos = $window.scrollTop();
            $this.each(function() {
                var $el = $(this), top = $el.offset().top, height = $el.outerHeight(true);
                if (top + height < pos || top > pos + windowHeight || $this.data('kc-parallax') !== true )
                	return;
                $this.css('backgroundPosition', "50% " + Math.round((el_top - pos) * 0.4) + "px");
            })
        }

        $window.on('scroll resize', update).trigger('update');

    };

    $.fn.viewportChecker = function(useroptions){
        // Define options and extend with user
        var options = {
            classToAdd: 'visible',
            offset: 100,
            callbackFunction: function(elem){}
        };
        $.extend(options, useroptions);

        // Cache the given element and height of the browser
        var $elem = this,
            windowHeight = $(window).height();

        this.checkElements = function(){
            // Set some vars to check with
            var scrollElem = ((navigator.userAgent.toLowerCase().indexOf('webkit') != -1) ? window : 'html'),
                viewportTop = $(scrollElem).scrollTop(),
                viewportBottom = (viewportTop + windowHeight);

            $elem.each(function(){
                var $obj = $(this);
                // If class already exists; quit
                if ( $obj.hasClass(options.classToAdd) && options.classToAdd != '' ){
                    return;
                }

                // define the top position of the element and include the offset which makes is appear earlier or later
                var elemTop = Math.round( $obj.offset().top ) + options.offset,
                    elemBottom = elemTop + ($obj.height());

                // Add class if in viewport
                if ((elemTop < viewportBottom) && (elemBottom > viewportTop) && this.done != true){
                    $obj.addClass(options.classToAdd);

                    // Do the callback function. Callback wil send the jQuery object as parameter
                    options.callbackFunction($obj);
                }
            });
        };

        // Run checkelements on load and scroll
        $(window).scroll(this.checkElements);
        this.checkElements();

        // On resize change the height var
        $(window).resize(function(e){
            windowHeight = e.currentTarget.innerHeight;
        });

    };

	$( document ).ready(function($){

		// load js when document is ready
		kc_front.init($);


	});

	return {

		win_height : 0,

		win_width : 0,

		body : $('body'),

		init : function(){

			$('section[data-kc-parallax="true"]').each(function(){
				$(this).kc_parallax();
			});

			this.accordion();

			this.tabs();

			this.youtube_row_background.init();

			if( window.location.href.indexOf('#') > -1 ){
				$('a[href="#'+window.location.href.split('#')[1]+'"]').trigger('click');
			}

			$('.kc_button').add('.kc_tooltip').kcTooltip();

			$('.kc-close-but').on( 'click', function(){
				$(this).parent().parent().hide('slow',function(){$(this).remove();});
			});

			this.google_maps();

			this.blog.masonry();

			this.image_gallery.masonry();

			this.carousel_images();

			this.carousel_post();

			this.countdown_timer();

			this.piechar.init();

			this.progress_bar.run();

			this.ajax_action();

			this.pretty_photo();

			this.tooltips();

			this.image_fade();

			this.smooth_scroll();

			this.animate();

            this.row_action(true);

		},

		refresh: function( el ){

			setTimeout( function( el){

				kc_front.piechar.update( el );
				kc_front.progress_bar.update( el );
				kc_front.image_gallery.masonry( el );

				if($('.kc_video_play').length > 0){
					kc_video_play.refresh( el );
				}

			}, 100, el );

		},

        viewport : function( st ) {
            var d = document;
            if (d.compatMode === 'BackCompat') {
                if (st == 'height') return d.body.clientHeight;
                else return d.body.clientWidth
            } else {
                if (st == 'height') return d.documentElement.clientHeight;
                else return d.documentElement.clientWidth
            }
        },

        row_action : function( force ) {
            var d = document;
            [].forEach.call(d.querySelectorAll('section[data-kc-fullwidth]'), function(el) {

                var kc_clfw = d.querySelectorAll('.kc_clfw')[0], rect;

                if(typeof kc_clfw === 'undefined')
                    return;

                rect = kc_clfw.getBoundingClientRect();

				el.style.left = (-rect.left) + 'px';
				if (el.getAttribute('data-kc-fullwidth') == 'row') {
					el.style.paddingLeft = rect.left + 'px';
					el.style.paddingRight = (kc_front.viewport('width') - rect.width - rect.left) + 'px';
					el.style.width = rect.width + 'px'
				} else {
					el.style.paddingLeft = '0px';
					el.style.width = kc_front.viewport('width') + 'px'
				}

                if (el.nextElementSibling !== null && el.nextElementSibling.tagName == 'SCRIPT') {
                    if (el.nextElementSibling.innerHTML == 'kc_front.row_action(true);') {
                        el.parentNode.removeChild(el.nextElementSibling)
                    }
                }
            })
        },

        google_maps: function( wrp ){

			$('.kc_google_maps').each( function(){

				if( $(this).data('loaded') === true )
					return;
				else $(this).data({ 'loaded' : true });

				var $_this = $( this );

				if( $_this.data('wheel') == 'disable'){
					$_this.click(function () {
						$_this.find('iframe').css("pointer-events", "auto");
					});

					$_this.mouseleave(function() {
						$_this.find('iframe').css("pointer-events", "none");
					});
				}


				$_this.find('.close').on('click', function(){
					$_this.find('.map_popup_contact_form').toggleClass( "hidden" );
					$_this.find('.show_contact_form').fadeIn('slow');
				});

				$_this.find('.show_contact_form').on('click', function(){
					$_this.find('.map_popup_contact_form').toggleClass( "hidden" );
					$_this.find('.show_contact_form').fadeOut('slow');
				});
			});
		},

		accordion: function( wrp ){

			$('.kc_accordion_wrapper').each(function(){

				if( $(this).data('loaded') === true )
					return;
				else $(this).data({ 'loaded' : true });

				var active = $(this).data('tab-active')!==undefined?($(this).data('tab-active')-1):0;

				if ($(this).data('closeall') == true)
					active = '100000';

				$ (this).find('>div.kc_accordion_section>h3.kc_accordion_header>a, >div.kc_accordion_section>h3.kc_accordion_header>.ui-accordion-header-icon')
					.off('click')
					.on('click', function(e)
					{

						var wrp = $(this).closest('.kc_accordion_wrapper'),
							section = $(this).closest('.kc_accordion_section'),
							allowopenall = (true === wrp.data('allowopenall')) ? true : false,
							closeall = (true === wrp.data('closeall')) ? true : false,
							changed = section.find('>h3.kc_accordion_header').hasClass('ui-state-active'),
							clickitself = false;

						if( allowopenall === false ){

							if (!section.find('>h3.kc_accordion_header').hasClass('ui-state-active')) {

								wrp.find( '>.kc_accordion_section>.kc_accordion_content' ).slideUp();
								wrp.find('>.kc_accordion_section>h3.kc_accordion_header').removeClass('ui-state-active');
								wrp.find('>.kc_accordion_section.kc-section-active').removeClass('kc-section-active');

								section.find('>.kc_accordion_content').stop().slideDown( 'normal', function(){ $(this).css({height:''}) } );
								section.find('>h3.kc_accordion_header').addClass('ui-state-active');
								section.addClass('kc-section-active');
							}else{
								wrp.find( '>.kc_accordion_section>.kc_accordion_content' ).slideUp();
								wrp.find('>.kc_accordion_section>h3.kc_accordion_header').removeClass('ui-state-active');
								wrp.find('>.kc_accordion_section>.kc-section-active').removeClass('kc-section-active');
                                section.removeClass('kc-section-active');
							}

						}else{

							if( section.find('>h3.kc_accordion_header').hasClass('ui-state-active') ){
								section.find('>.kc_accordion_content').stop().slideUp();
								section.find('>h3.kc_accordion_header').removeClass('ui-state-active');
								section.removeClass('kc-section-active');
							}else{
								section.find('>.kc_accordion_content').stop().slideDown( 'normal', function(){ $(this).css({height:''}) } );
								section.find('>h3.kc_accordion_header').addClass('ui-state-active');
								section.addClass('kc-section-active');
							}

						}

						if( changed != section.find('>h3.kc_accordion_header').hasClass('ui-state-active') )
							kc_front.refresh( section.find('>.kc_accordion_content') );

						e.preventDefault();

						var index = $(this).closest('.kc_accordion_section');
							index = index.parent().find('>.kc_accordion_section').index( index.get(0) );

						$(this).closest('.kc_accordion_wrapper').data({'tab-active':(index+1)});

					}).eq(active).trigger('click');

			});

		},

		tabs: function( wrp ){

			$('.kc_tabs > .kc_wrapper').each( function( index ){

				if( $(this).data('loaded') === true )
					return;
				else $(this).data({ 'loaded' : true });

				var $_this = $(this),
					tab_group = $_this.parent('.kc_tabs.group'),
					tab_event = ('yes' === tab_group.data('open-on-mouseover')) ? 'mouseover' : 'click',
					effect_option = ('yes' === tab_group.data('effect-option')) ? true : false,
					active_section = parseInt( tab_group.data('tab-active') )-1;

					$( this ).find('>.ui-tabs-nav>li')
						.off('click')
						.on( 'click', function(e){
							e.preventDefault();
						} )
						.off( tab_event )
						.on( tab_event, function(e){

							if( $(this).hasClass('ui-tabs-active') ){
								e.preventDefault();
								return;
							}

							var labels = $(this).closest('.kc_tabs_nav,.ui-tabs-nav').find('>li'),
								index = labels.index( this ),
								tab_list = $(this).closest('.kc_wrapper').find('>.kc_tab'),
								new_panel = tab_list.eq( index );

							labels.removeClass('ui-tabs-active');
							$(this).addClass('ui-tabs-active');

							tab_list.removeClass('ui-tabs-body-active').removeClass('kc-section-active');
							new_panel.addClass('ui-tabs-body-active').addClass('kc-section-active');

							if( effect_option === true)
								new_panel.css({'opacity':0}).animate({opacity:1});

							e.preventDefault();

							$(this).closest('.kc_tabs').data({'tab-active':(index+1)});

						}).eq( active_section ).trigger( tab_event );

			});

			$('.kc_tabs.kc-tabs-slider').each(function(){

				if( $(this).data('loaded') === true )
					return;
				else $(this).data({ 'loaded' : true });

				$( this ).find('.kc-tabs-slider-nav li').each(function( index ){

					if( $(this).data('loaded') === true )
						return;
					else $(this).data({ 'loaded' : true });

					$( this ).on( 'click', index, function( e ){
						$(this).parent().find('.kc-title-active').removeClass('kc-title-active');
						$(this).addClass('kc-title-active');
						console.log(e.data);
						$(this).closest('.kc-tabs-slider').find('.owl-carousel').trigger('owl.goTo', e.data);
						e.preventDefault();
						$(this).closest('.kc_tabs').data({'active':e.data});
					});
					if( index === 0 )
						$( this ).addClass('kc-title-active');
				});

			});

			kc_front.owl_slider();

		},

		counterup : function (){

			$('.counterup').each(function( index ){

				if( $(this).data('loaded') === true )
					return;
				else $(this).data({ 'loaded' : true });

				$(this).counterUp({
					delay: 100,
					time: 2000
				});

			});

		},

		youtube_row_background: {

			init: function(){

				$( '.kc_row, .kc_column' ).each( function () {
					var $row = $( this ),
						youtubeUrl,
						youtubeId;

					if ( $row.data( 'kc-video-bg' ) ) {

						youtubeUrl = $row.data( 'kc-video-bg' );
						youtubeId = kc_front.youtube_row_background.getID( youtubeUrl );

						if ( youtubeId ) {
							$row.find( '.kc_wrap-video-bg' ).remove();
							kc_front.youtube_row_background.add( $row, youtubeId );
						}

					} else {
						$row.find( '.kc_wrap-video-bg' ).remove();
					}
				} );
			},

			getID: function ( url ) {
				if ( 'undefined' === typeof(url) ) {
					return false;
				}

				var id = url.match( /(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/ );
				if ( null !== id ) {
					return id[ 1 ];
				}

				return false;
			},

			add: function( $obj, youtubeId, counter ) {

				if( YT === undefined )
					return;

				if ( 'undefined' === typeof( YT.Player ) ) {

					counter = 'undefined' === typeof( counter ) ? 0 : counter;
					if ( counter > 100 ) {
						console.warn( 'Too many attempts to load YouTube api' );
						return;
					}

					setTimeout( function () {
						kc_front.youtube_row_background.add( $obj, youtubeId, counter++ );
					}, 100 );

					return;
				}

				var player,
					$container = $obj.prepend( '<div class="kc_wrap-video-bg"><div class="ifr_inner"></div></div>' ).find( '.ifr_inner' ),
                    options = $obj.data('kc-video-options'),
                    playerVars = {
                        playlist: youtubeId,
                        iv_load_policy: 3,
                        enablejsapi: 1,
                        disablekb: 1,
                        autoplay: 1,
                        controls: 0,
                        showinfo: 0,
                        rel: 0,
                        loop: 1
                    };

                options = options?JSON.parse('{"' + options.replace(/&/g, '","').replace(/=/g,'":"') + '"}', function(key, value) { return key===""?value:decodeURIComponent(value) }):{};
                if( typeof options == 'object')playerVars = $.extend(playerVars, options);

				player = new YT.Player( $container[0], {
					width: '100%',
					height: '100%',
					videoId: youtubeId,
					playerVars: playerVars,
					events: {
						onReady: function ( e ) {
							if($obj.data('kc-video-mute') == 'yes')
								e.target.mute().setLoop( true );
							e.target.playVideo();
						}
					}
				} );

				kc_front.youtube_row_background.resize( $obj );

				$( window ).on( 'resize', function () {
					kc_front.youtube_row_background.resize( $obj );
				} );
			},

			resize: function( $obj ) {

				var ratio = 1.77, ifr_w, ifr_h,
					marginLeft, marginTop,
					inner_width = $obj.innerWidth(),
					inner_height = $obj.innerHeight();

				if ( ( inner_width / inner_height ) < ratio ) {
					ifr_w = inner_height * ratio;
					ifr_h = inner_height;
				} else {
					ifr_w = inner_width;
					ifr_h = inner_width * (1 / ratio);
				}

				marginLeft = - Math.round( ( ifr_w - inner_width ) / 2 ) + 'px';
				marginTop = - Math.round( ( ifr_h - inner_height ) / 2 ) + 'px';

				ifr_w += 'px';
				ifr_h += 'px';

				$obj.find( '.kc_wrap-video-bg iframe' ).css( {
					maxWidth: '1000%',
					marginLeft: marginLeft,
					marginTop: marginTop,
					width: ifr_w,
					height: ifr_h
				} );
			}

		},

		single_img : {

			refresh : function ( wrp ) {

				kc_front.pretty_photo();

			}

		},

		blog : {

			masonry : function(){

				$('.kc_blog_masonry').each(function(){

					if( $(this).data('loaded') === true )
						return;
					else $(this).data({ 'loaded' : true });

					var wrp 	= $(this),
						imgs 	= wrp.find('img'),
						total 	= imgs.length,
						ready 	= 0;

					if( total > 0 )
					{

						imgs.each(function( ind ){

							var tmpImg = new Image();

							tmpImg.onload = function(){

								ready++;
								if(  ready ==  total ){

									new Masonry( wrp.get( 0 ), {
										itemSelector: '.post-grid',
										columnWidth: '.post-grid',
									});
								}
							};
							tmpImg.src = $(this).attr('src') ;
						});

					}
					else
					{

						new Masonry( wrp.get( 0 ), {
							itemSelector: '.post-grid',
							columnWidth: '.post-grid',
						});

					}

				});

			},

		},

		image_gallery : {

			masonry : function(){

				$('.kc_image_gallery').each(function(){

					if( $(this).data('loaded') === true )
						return;
					else $(this).data({ 'loaded' : true });

					if(( 'yes' === $( this ).data('image_masonry')) ){

						//find all image in gallery
						var imgs 	= $( this ).find('img'),
							total 	= imgs.length,
							ready 	= 0,
							el 		= $( this );

						$(this).data({ 'total' : total });

						imgs.each(function( ind ){

							var tmpImg = new Image();

							tmpImg.onload = function(){

								ready++;

								if(  ready ==  total ){

									new Masonry( el.get( 0 ), {
										itemSelector: '.item-grid',
										columnWidth: '.item-grid',
									});

								}
							};

							tmpImg.src = $(this).attr('src') ;
						});

					}

				});

				kc_front.pretty_photo();

			},

		},

		image_fade : function(){
			$('.image_fadein_slider .image_fadein').each(function(){

				if( $(this).data('loaded') !== true )
					$(this).data({'loaded':true});
				else return;

				var delay = $(this).data('delay')?$(this).data('delay'):'3000';

				window.kc_front.image_fade_delay( delay, $(this).find('img').first() );

			});
		},

		image_fade_delay : function( delay, img ){

			if( img === undefined )
				return;

			img.parent().find('.active').removeClass('active');
			img.addClass('active');

			if( img.next().length > 0 )
				img = img.next();
			else
				img = img.parent().find('img').first();

			var time_out = setTimeout( window.kc_front.image_fade_delay, delay, delay, img );

		},

		carousel_images : function( wrp ){
			/*
			 * Carousel images
			 * For each item Carousel images
			 */

			$( '.kc-carousel-images' ).each( function( index ){

				if( $(this).data('loaded') === true )
					return;
				else $(this).data({ 'loaded' : true });

				var options 		= $( this ).data('owl-i-options'),
					_auto_play 		= ( 'yes' === options.autoplay ) ? true : false,
					_delay	 		= ( options.delay !== undefined ) ? options.delay : 8,
					_navigation 	= ( 'yes' === options.navigation ) ? true : false,
					_pagination 	= ( 'yes' === options.pagination ) ? true : false,
					_speed 			= options.speed,
					_items 			= options.items,
					_auto_height 	= ( 'yes' === options.autoheight ) ? true : false,
					_num_thumb 		= ( options.num_thumb !== undefined ) ? options.num_thumb : 5,
					_show_thumb 	= ( 'yes' === options.showthumb ) ? true : false,
					_progress_bar 	= ( 'yes' === options.progressbar ) ? true : false,
					_singleItem 	= false,
					_tablet 	= false,
					_mobile 	= false;

                if( options.tablet > 0 ){
                    _tablet = [999,options.tablet];
                }
                if( options.mobile > 0 ){
                    _mobile = [479,options.mobile];
                }

				var progressBar = function(){};
				var moved = function(){};
				var pauseOnDragging = function(){};

				if( true === _auto_height || true === _progress_bar || true === _show_thumb )
					_singleItem = true;

				if(_auto_play)
					_auto_play = parseInt( _delay )*1000;

				if( true === _progress_bar )
				{

					var time = _delay; // time in seconds

					var $progressBar,
						$bar,
						$elem,
						isPause,
						tick,
						percentTime;


					progressBar = function( elem ){
						$elem = elem;
						//build progress bar elements
						buildProgressBar();
						//start counting
						start();
					};

					var buildProgressBar =  function(){

						$progressBar = $("<div>",{
							class:"progressBar"
						});

						$bar = $("<div>",{
							class:"bar"
						});

						$progressBar.append($bar).prependTo($elem);

					};

					var start = function() {
						//reset timer
						percentTime = 0;
						isPause = false;
						//run interval every 0.01 second
						tick = setInterval(interval, 10);
					};


					var interval = function() {
						if(isPause === false){
							percentTime += 1 / time;

							$bar.css({
							   width: percentTime+"%"
							});
							//if percentTime is equal or greater than 100

							if(percentTime >= 100){
							  	//slide to next item
							  	$elem.trigger('owl.next');
							}
						}
					};

					pauseOnDragging = function (){
						isPause = true;
					};

					moved =    function(){
						//clear interval
						clearTimeout(tick);
						//start again
						start();
					};
				}

				if( true !== _show_thumb)
				{
					$( this ).owlCarousel({

						autoPlay		: _auto_play,
						navigation 		: _navigation,
						pagination 		: _pagination,
						slideSpeed 		: _speed,
						paginationSpeed : _speed,
						singleItem		: _singleItem,
						autoHeight		: _auto_height,
						items 			: _items,
						itemsDesktop	: false,
						itemsDesktopSmall	: false,
						itemsTablet	    : _tablet,
                        itemsTabletSmall: _tablet,
                        itemsMobile	: _mobile,
						afterInit 		: progressBar,
						afterMove 		: moved,
						startDragging 	: pauseOnDragging

					});
				}
				else
				{
					var sync1 = $( this );
					var sync2 = sync1.next('.kc-sync2');

					var syncPosition =  function(el){
						var current = this.currentItem;

						$(sync2)
							.find(".owl-item")
							.removeClass("synced")
							.eq(current)
							.addClass("synced");

						if($(sync2).data("owlCarousel") !== undefined)
						{
							center(current);
						}
					};

					sync2.on("click", ".owl-item", function(e){
						e.preventDefault();

						var number = $(this).data("owlItem");
						sync1.trigger("owl.goTo",number);
					});

					var center =  function(number){
						var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
						var num = number;
						var found = false;

						for(var i in sync2visible){
							if(num === sync2visible[i])
							{
								found = true;
							}
						}

						if(found===false){
							if( num> sync2visible[sync2visible.length-1] )
							{
								sync2.trigger("owl.goTo", num - sync2visible.length+2);
							}else
							{
								if(num - 1 === -1){
									num = 0;
								}

								sync2.trigger("owl.goTo", num);
							}
						}
						else if(num === sync2visible[sync2visible.length-1])
						{
							sync2.trigger("owl.goTo", sync2visible[1]);
						}
						else if(num === sync2visible[0])
						{
							sync2.trigger("owl.goTo", num-1);
						}

					};

					sync1.owlCarousel({
						autoPlay				: _auto_play,
						singleItem 				: _singleItem,
						slideSpeed 				: _speed,
						paginationSpeed 		: _speed,
						navigation				: _navigation,
						pagination				: _pagination,
						afterAction 			: syncPosition,
						responsiveRefreshRate 	: 200,
						autoHeight				: _auto_height,
						afterInit 				: progressBar,
						afterMove 				: moved,
						startDragging 			: pauseOnDragging
					});

					sync2.owlCarousel({
						items 				: _num_thumb,
						itemsDesktop      	: [1199, 15],
						itemsDesktopSmall   : [979, 12],
						itemsTablet       	: [768, 6],
						itemsMobile       	: [479, 5],
						pagination			: _pagination,
						responsiveRefreshRate : 100,
						afterInit : function(el){
							el.find(".owl-item").eq(0).addClass("synced");
						}
					});
				}

			});

			kc_front.pretty_photo();

		},

        update_option : function ( data_options ){

            $.post( top.kc_ajax_url, {
                'security': top.kc_ajax_nonce,
                'action' : 'kc_update_option',
                'options' : top.kc.tools.base64.encode( JSON.stringify( data_options ) )
            }, function (result) {
                //console.log(result.msg);
            });

        },

		carousel_post : function( wrp ){

			kc_front.owl_slider( '.kc-owl-post-carousel' );

		},

		tooltips : function(){
			// Portfolio
			$('.kc_tooltip').each(function(){

				if( $(this).data('kc-loaded') !== true )
					$(this).data({ 'kc-loaded' : true });
				else return;
				$(this).kcTooltip();
			});
		},

		countdown_timer : function(){

			$( '.kc-countdown-timer' ).each( function( index ){
				var countdown_data = $( this ).data('countdown');

				$(this).countdown(countdown_data.date, function(event) {
			    	$(this).html(event.strftime(countdown_data.template));
			    });

			});
		},

		piechar : {

			init: function(){

				$('.kc_piechart').each(function(index){

					$( this ).viewportChecker({

						callbackFunction: function(elm) {

							kc_front.piechar.load(elm);

						},

						classToAdd: 'kc-pc-loaded'

					});

				});
			},

			load : function( el ){

				if( el.parent('div').width() < 10 )
					return 0;

				var _size 		= el.data( 'size' ),
					_linecap 	= ( 'yes' === el.data( 'linecap' )) ? 'round' : 'square',
					_barColor 	= el.data( 'barcolor' ),
					_trackColor = el.data( 'trackcolor' ),
					_autowidth 	= el.data( 'autowidth' ),
					_linewidth 	= el.data( 'linewidth' );

				if('yes' === _autowidth){
					_size = el.parent('div').width();
					el.data( 'size', _size );
				}

				//Fix percent middle
				var percent_width = el.find('.percent').width() + el.find('.percent:after').width();
				var percent_height = el.find('.percent').height();

				el.easyPieChart({

					barColor: _barColor,
					trackColor: _trackColor,
					lineCap: _linecap,
					easing: 'easeOutBounce',

					onStep: function(from, to, percent) {

						$(this.el).find('.percent').text(Math.round(percent));
						$(this.el).find('.percent').show();
						$( this.el ).css({'width': _size, 'height': _size});

					},

					scaleLength: 0,
					lineWidth: _linewidth,
					size: _size,

				});

			},

			update: function( el ){

				el.find('.kc_piechart').each( function(){

					if( $(this).data('loaded') === true )
						return;
					else $(this).data({ 'loaded' : true });

					kc_front.piechar.load( $( this ) );

				});

			}

		},

		progress_bar : {

			run: function(){

			    $('.kc_progress_bars').each(function(){

			  		$( this ).viewportChecker({

						callbackFunction: function( el ){

							kc_front.progress_bar.update( el );

						},
						classToAdd : 'kc-pb-loaded'
					});

			    });
			},

			update: function( el ){

				$('.kc-progress-bar .kc-ui-progress').each(function(){

					if( $(this).data('loaded') === true )
						return;
					else $(this).data({ 'loaded' : true });

					$( this ).css({ width: '5%' }).
							  stop().
							  animate({
								 		width: this.getAttribute('data-value')+'%'
								 	},{
							  			duration: parseInt( this.getAttribute('data-speed') ),
							  			easing : 'easeInOutQuart',
							  			step : function( st, tl ){
								  			if( tl.now/tl.end > 0.3 )
								  				this.getElementsByClassName('ui-label')[0].style.opacity = tl.now/tl.end;
							  			}
							  		}
							  ).find('.ui-label').css({opacity:0});

				});

			}
		},

		ajax_action : function(){

			$('.kc_facebook_recent_post').each(function(){

				if( this.getAttribute('data-cfg') === null ||
					this.getAttribute('data-cfg') === undefined ||
					this.getAttribute('data-cfg') === '' )
						return;

				var $_this = $( this ),
					data_send = {
						action: 'kc_facebook_recent_post',
						cfg: $( this ).data( 'cfg' )
					};

				this.removeAttribute('data-cfg');

				$.ajax({
					url: kc_script_data.ajax_url,
					method: 'POST',
					dataType: 'json',
					data: data_send,
					success: function( response_data ){
						$_this.find('ul').html(response_data.html).before(response_data.header_html);
					}
				});

			});

			/*
			 * instagram feed images
			 * Send data to shortcode
			 */
			$('.kc_wrap_instagram').each(function(index){

				if( this.getAttribute('data-cfg') === null ||
					this.getAttribute('data-cfg') === undefined ||
					this.getAttribute('data-cfg') === '' )
						return;

				var $_this = $( this ),
					data_send = {
						action: 'kc_instagrams_feed',
						cfg: $( this ).data( 'cfg' )
					};

				this.removeAttribute('data-cfg');

				$.ajax({
					url: kc_script_data.ajax_url,
					method: 'POST',
					dataType: 'json',
					data: data_send,
					success: function( response_data ){
						$_this.find('ul').html(response_data.html);
					}
				});
			});

			/*
			 * Twitter feed sider
			 * For each item Twitter feed sider
			 */
			$( '.kc_twitter_feed' ).each( function( index ) {

				if( this.getAttribute('data-cfg') === null ||
					this.getAttribute('data-cfg') === undefined ||
					this.getAttribute('data-cfg') === '' )
						return;

				var $_this = $( this ),
					atts_data = {
						action: 'kc_twitter_timeline',
						cfg: $( this ).data( 'cfg' )
					};

				this.removeAttribute('data-cfg');

				var owl_option = $( this ).data( 'owl_option' );

				$.ajax({
					url: kc_script_data.ajax_url,
					method: 'POST',
					dataType: 'json',
					data: atts_data,
					success: function( response_data ){
						var display_style = $_this.data( 'display_style' );

						$_this.find('.result_twitter_feed').html( response_data.html );

						$_this.find('.result_twitter_feed').before('<div class="button_follow_wrap">'+response_data.header_data+'</div>');

						var _navigation = ( 'yes' === owl_option.show_navigation )? true : false,
							_pagination = ( 'yes' === owl_option.show_pagination )? true : false,
							_autoHeight = ( 'yes' === owl_option.auto_height )? true : false;

						if( 2 === display_style ){
							$_this.find('.kc-tweet-owl').owlCarousel({
								navigation 		: _navigation,
								pagination 		: _pagination,
								slideSpeed 		: 300,
								paginationSpeed : 400,
								singleItem		: true,
								items 			: 1,
								autoHeight		: _autoHeight
							});
						}
					}
				});

			});
		},

		owl_slider : function(){

			if( typeof $().owlCarousel != 'function' )
				return;

			$('[data-owl-options]').each( function( index ){

				var options = $( this ).data('owl-options');

				if( typeof options !== 'object' )
					return;

				if( $(this).data('loaded') === true )
					return;
				else $(this).data({ 'loaded' : true });

				$( this ).attr({'data-owl-options':null});

				var	_autoplay 			= ( 'yes' === options.autoplay ) ? true : false,
					_navigation 		= ( 'yes' === options.navigation ) ? true : false,
					_pagination 		= ( 'yes' === options.pagination ) ? true : false,
					_speed 				= ( options.speed!==undefined ) ? options.speed : 450,
					_items 				= ( options.items!==undefined ) ? options.items:1,
					_tablet 			= ( options.tablet!==undefined ) ? options.tablet:1,
					_mobile 			= ( options.mobile!==undefined ) ? options.mobile:1,
					_autoheight 		= ( 'yes' === options.autoheight ) ? true : false,
					_showthumb 			= ( 'yes' === options.showthumb ) ? true : false,
					_singleItem 		= false;

				if(_autoheight === true){
					_singleItem = true;
					_items = 1;
				}

				$( this ).owlCarousel({
					autoPlay		: _autoplay,
					navigation 		: _navigation,
					pagination 		: _pagination,
					showthumb 		: _showthumb,
					slideSpeed 		: _speed,
					paginationSpeed : _speed,
					singleItem		: _singleItem,
					autoHeight		: _autoheight,
					items 			: _items,
					itemsCustom : false,
					itemsDesktop : [1199,_items],
					itemsDesktopSmall : [980,_tablet],
					itemsTablet: [640,_mobile],
					itemsTabletSmall: false,
					itemsMobile : [480,_mobile],
				});

			});

			kc_front.pretty_photo();

		},

		pretty_photo : function(){

			if (typeof( $.prettyPhoto ) == 'object') {

				$("a.kc-pretty-photo:not(.kc-pt-loaded)").addClass('kc-pt-loaded').off('click').prettyPhoto({

					theme: 'dark_rounded',
		            allow_resize: true,
		            allow_expand: true,
		            opacity: 0.85,
		            animation_speed: 'fast',
		            deeplinking: false,
		            counter_separator_label: ' / ',
		            show_title: true,
		            autoplay: true,
		            horizontal_padding: 0,
		            overlay_gallery: false,
		            markup: '<div class="pp_pic_holder"> \
		                <div class="pp_content_container"> \
		                  <div class="pp_left"> \
		                  <div class="pp_right"> \
		                    <div class="pp_content"> \
		                      <div class="pp_loaderIcon kc-spinner"></div> \
		                      <div class="pp_fade"> \
		                        <div class="pp_hoverContainer"> \
		                          <a class="pp_next" href="#"><i class="sl-arrow-right"></i></a> \
		                          <a class="pp_previous" href="#"><i class="sl-arrow-left"></i></a> \
		                        </div> \
		                        <div id="pp_full_res"></div> \
		                        <div class="pp_details"> \
		                         <div class="ppt">&nbsp;</div> \
		                          <div class="pp_nav"> \
		                            <p class="currentTextHolder">0 / 0</p> \
		                          </div> \
		                          <p class="pp_description"></p> \
		                          <a class="pp_close" href="#"><i class="sl-close"></i></a> \
		                        </div> \
		                      </div> \
		                    </div> \
		                  </div> \
		                  </div> \
		                </div> \
		              </div> \
		              <div class="pp_overlay"></div>'
				});

			}
		},

		smooth_scroll : function(){

			$('a[href^="#"]').on( 'click', function(e) {

				if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'')
					&& location.hostname == this.hostname
					&& this.hash.indexOf('#!') === 0
				){
					var target = $(this.hash.replace('!', ''));

					if (target.length)
					{
						$('html,body').stop().animate({
							scrollTop: target.offset().top-80
						}, 500);
					}
				}

			});

		},

		animate : function(){

			$('.kc-animated').each(function(index){

				$( this ).viewportChecker({

					callbackFunction: function( el ){

						var clazz = el.get(0).className, delay = 0, speed = '2s', timeout = 0;

						if (clazz.indexOf('kc-animate-delay-') > -1)
						{
							delay = clazz.split('kc-animate-delay-')[1].split(' ')[0];

							el.css({'animation-delay': delay+'ms'});
							el.removeClass('kc-animate-delay-'+delay);

							timeout += parseInt(delay);

						}

						if (clazz.indexOf('kc-animate-speed-') > -1)
						{
							speed = clazz.split('kc-animate-speed-')[1].split(' ')[0];

							el.css({'animation-duration': speed});
							el.removeClass('kc-animate-speed-'+speed);

						}

						if (clazz.indexOf('kc-animate-eff-') > -1)
						{
							var eff = clazz.split('kc-animate-eff-')[1].split(' ')[0];

							timeout += parseFloat(speed)*1000;

							el.removeClass('kc-animated').addClass('animated '+eff);

							setTimeout(function(el, eff){

								el.removeClass('animated kc-animated kc-animate-eff-'+eff+' '+eff);
								el.css({'animation-delay': '', 'animation-duration': ''});

							}, timeout, el, eff);

						}

					},

					classToAdd: 'kc-pc-loaded'

				});

			});

		}

	};

}(jQuery));


(function ( $ ) {
	$.fn.kcTooltip = function() {

		return this.each(function() {

			var rect = this.getBoundingClientRect();
			var tooltip = $(this).data('tooltip'),
				span_w = $(this).find('span').outerWidth(),
				span_h = $(this).find('span').outerHeight(),
				this_w = $(this).outerWidth(),
				this_h = $(this).outerHeight();

			if(typeof(tooltip) == 'undefined'){
				$(this).find('span').css('margin-left', -span_w/2);
				$(this).hover().find('span').css('bottom', this_h+10);
			}else{

				var position = $(this).data('position');
				var ext_bottom = -10;
				if(typeof position == 'undefined')
					position = 'top';

				$(this).addClass(position);

				$(this).find('span').attr({'style':''});

				switch(position) {
					case 'right': {
						var bottom;
						bottom = this_h/2 - span_h/2;

						$(this).find('span').css('left', this_w+10 );
						$(this).find('span').css('bottom', bottom );

						$(this).hover().find('span').css('left', this_w-ext_bottom);
						break;
					}

					case 'bottom': {
						$(this).find('span').css('margin-left', -span_w/2);
						$(this).hover().find('span').css('bottom', -span_h+ext_bottom);
						break;
					}

					case 'left': {
						var bottom, ext_left = 5;
						bottom = this_h/2 - span_h/2;

						$(this).find('span').css('left', -span_w-ext_left );
						$(this).find('span').css('bottom', bottom );

						break;
					}

					default: {

						$(this).find('span').css('margin-left', -span_w/2);
						$(this).hover().find('span').css('bottom', this_h-ext_bottom);
					}
				}
			}

		});

	};
}( jQuery ));

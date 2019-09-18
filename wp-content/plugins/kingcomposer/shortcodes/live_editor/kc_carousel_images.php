<#
var output = '',
	atts = ( data.atts !== undefined ) ? data.atts : {},
	thumb_data = '',
	attachment_data = [],
	attachment_data_full = [],
	el_classes = [],
	wrp_classes = [],
	custom_links_arr = [],
	element_attribute = [], el_classes = [], owl_option = [],
	wrap_class = ( atts['wrap_class'] !== undefined ) ? atts['wrap_class'] : '',
	images = ( atts['images'] !== undefined ) ? atts['images'] : '',
	items_number = ( atts['items_number'] !== undefined ) ? atts['items_number'] : 4,
	tablet = ( atts['tablet'] !== undefined ) ? atts['tablet'] : 2,
	mobile = ( atts['mobile'] !== undefined ) ? atts['mobile'] : 1,
	speed = ( atts['speed'] !== undefined ) ? atts['speed'] : 500,
	navigation = ( atts['navigation'] !== undefined ) ? atts['navigation'] : false,
	pagination = ( atts['pagination'] !== undefined ) ? atts['pagination'] : false,
	auto_height = ( atts['auto_height'] !== undefined ) ? atts['auto_height'] : false,
	progress_bar = ( atts['progress_bar'] !== undefined ) ? atts['progress_bar'] : false,
	delay = ( atts['delay'] !== undefined  && atts['delay'] !== '__empty__' && atts['delay'] !== '' ) ? atts['delay'] : 8,
	autoplay = ( atts['autoplay'] !== undefined ) ? atts['autoplay'] : false,
	show_thumb = ( atts['show_thumb'] !== undefined ) ? atts['show_thumb'] : '',
	auto_play = ( atts['auto_play'] !== undefined ) ? atts['auto_play'] : false,
	onclick = ( atts['onclick'] !== undefined ) ? atts['onclick'] : '',
	custom_links = ( atts['custom_links'] !== undefined ) ? atts['custom_links'] : '',
	num_thumb = ( atts['num_thumb'] !== undefined && atts['num_thumb'] !== '' ) ? atts['num_thumb'] : '5',
	img_size = ( atts['img_size'] !== undefined ) ? atts['img_size'] : 'full';
	
	
wrp_classes = kc.front.el_class( atts );


if( images !== '' ){
	images = images.split(',');
}
#>
<div class="{{{wrp_classes.join(' ')}}}">
<#

if( typeof(images) == 'object' && images !== '' ) {

	for ( var i=0; i< images.length; i++ ){
		image_id = images[i];
		attachment_data.push( ajaxurl + '?action=kc_get_thumbn&id=' + image_id + '&size=' + img_size);
		attachment_data_full.push( ajaxurl + '?action=kc_get_thumbn&size=full&id=' + image_id );
	}


	el_classes.push( 'kc-carousel-images' );
	el_classes.push( 'owl-carousel-images' );
	el_classes.push( 'owl-arrow-nav' );
	el_classes.push( 'kc-sync1' );
	el_classes.push( wrap_class );


	if( atts['nav_style'] !== undefined && atts['nav_style'] !== '' ){
		el_classes.push( 'owl-nav-' + atts['nav_style'] );
	}
	
	owl_option = {
		'items' : items_number,
		'tablet' : tablet,
		'mobile' : mobile,
		'speed' : speed,
		'navigation'  : navigation,
		'pagination' : pagination,
		'autoheight' : auto_height,
		'progressbar' : progress_bar,
		'delay'  : delay,
		'autoplay' : auto_play,
		'showthumb' : show_thumb,
		'num_thumb' : num_thumb,
	};

	owl_option = JSON.stringify( owl_option );

	element_attribute.push('class="' + el_classes.join(' ') + '"');
	element_attribute.push("data-owl-i-options='" + owl_option + "'");

	if( 'custom_link' == onclick && custom_links !== '' ){

		custom_links 		= custom_links.replace('/[\r\n]+/',"\n").replace('/^\n/','').replace('/\n$/','');
		custom_links_arr 	= custom_links.split("\n");

	}

	for(var i=0; i < attachment_data.length; i++){

		image 	= attachment_data[i];
		output 	+= '<div class="item">';

		if( 'none' === onclick ){

			output += '<img src="' + image + '" alt=""/>';

		}else {

			switch( onclick ){

				case 'lightbox':
						output += '<a class="kc-image-link kc-pretty-photo" data-lightbox="kc-lightbox" rel="prettyPhoto[' + atts['_id'] + ']" href="' + attachment_data_full[i] + '"><img src="' + image + '" alt="" /></a>';
					break;

				case 'custom_link':
					if( custom_links_arr[i] !== undefined ){

						output += '<a href="' + custom_links_arr[i] + '" target="' + custom_links_target + '">';
						output += '<img src="' + image + '" alt="" /></a>';

					}else{

						output += '<img src="' + image + '" alt="" />';

					}

					break;

			}

		}
		
		output += '</div>';

	}
	#>
	<div class="kc-carousel_images">
	<div {{{element_attribute.join(' ')}}}>
	{{{output}}}
	</div>
	<# 

	if( show_thumb === 'yes' ){ 

	#>
	<div class="kc-sync2 owl-carousel">
		<# 

		for( var k = 0; k < attachment_data.length; k++ ) {
			image = attachment_data[k];

		#>
			<div class="item">
				<img src="{{{image}}}" alt="" />
			</div>

		<# } #>
	</div>
	<#
	}

	data.callback = function( wrp, $ ){
		kc_front.carousel_images( wrp );
	}

	#>
</div>
<#
}else{
#>
	<h3 class="kc-carousel-no-images">Carousel Images: No images found.</h3>
<#
}
#>
</div>

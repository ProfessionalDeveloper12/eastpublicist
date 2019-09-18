<#

var output          = '', atts = ( data.atts !== undefined ) ? data.atts : {},
	custom_links            = ( atts['custom_links'] !== undefined ) ? atts['custom_links'] : '',
	click_action            = ( atts['click_action'] !== undefined ) ? atts['click_action'] : 'none',
	wrap_class              = ( atts['wrap_class'] !== undefined ) ? atts['wrap_class'] : '',
	type                    = ( atts['type'] !== undefined ) ? atts['type'] : '',
	image_size              = ( atts['image_size'] !== undefined ) ? atts['image_size'] : '',
	images                  = ( atts['images'] !== undefined ) ? atts['images'] : '',
	title                   = ( atts['title'] !== undefined ) ? atts['title'] : '',
	columns                 = ( atts['columns'] !== undefined && atts['columns'] !== '__empty__' ) ? atts['columns'] : '4',
	type_class              = '',
	slider_item_start       = '',
	slider_item_end         = '',
	attachment_data         = [],
	wrp_class               = [],
	attachment_data_full    = [],
	custom_links_arr        = [],
	element_attribute       = [], el_classess = [ ],
	sizes 		            = ['full', 'thumbnail', 'medium', 'large'];

type_class = 'kc-grid';

if( custom_links !== '' && 'custom_link' === click_action ) {

	custom_links = custom_links.replace('/[\r\n]+/',"\n").replace('/^\n/','').replace('/\n$/','');
	custom_links_arr = custom_links.split('\n');
}


wrp_class = kc.front.el_class( atts );
wrp_class = wrp_class.join( ' ' );

el_classess = ['kc_image_gallery',
				type_class,
				wrap_class];
	
if( images === '' )
	images = [];
else
	images = images.split(',');
	

element_attribute.push( 'class="' +  el_classess.join(' ') + '"');
element_attribute.push( 'data-type="' +  type + '"');


if( type == 'image_masonry' )
	element_attribute.push('data-image_masonry="yes"');
else
	element_attribute.push('data-image_masonry=""');

for ( var i=0; i < images.length; i++ ){
	
	image_id = images[i];
	
	if ( sizes.indexOf( image_size ) > -1  ) {
		attachment_data.push( ajaxurl + '?action=kc_get_thumbn&id=' + image_id + '&size=' + image_size );
	}else if( image_size.indexOf('x') > 0 ){
		attachment_data.push( ajaxurl + '?action=kc_get_thumbn_size&id=' + image_id + '&size=' + image_size );
	}else{
		attachment_data.push( ajaxurl + '?action=kc_get_thumbn&id=' + image_id + '&size=full');
	}
	
	attachment_data_full.push( ajaxurl + "?action=kc_get_thumbn&size=full&id="+image_id );
}


if( attachment_data[0] === undefined || attachment_data[0] ==='' ){
	
	output += '<h3 class="kc-image-gallery-title">Images Gallery: No images found</h3>';
	
}else{
	
	var pretty_id = parseInt(Math.random()*100000);
	
	for( var i=0; i< attachment_data.length; i++){

		var image = attachment_data[i];

		switch( click_action ){

			case 'none':
				output += '<div class="item-grid grid-' + columns + '"><img src="' + image + '" /></div>';
				break;

			case 'large_image':
				output += '<div class="item-grid grid-' + columns + '"><a href="' + attachment_data_full[i] + '" target="_blank">';
				output += '<img src="' + image + '" /></a></div>';
				break;

			case 'lightbox':
				output += '<div class="item-grid grid-' + columns + '"><a class="kc-image-link kc-pretty-photo" data-lightbox="kc-lightbox" rel="kc-pretty-photo['+pretty_id+']" href="' + attachment_data_full[i] + '">';
				output += '<img src="' + image + '" /></a></div>';
				break;

			case 'custom_link':

				if( custom_links_arr[i] !== undefined ){
					output += '<div class="item-grid grid-' + columns + '"><a href="' + custom_links_arr[i] + '" target="_blank">';
					output +=  '<img src="'+ image + '" /></a></div>';
				}else{
					output += '<div class="item-grid grid-' + columns + '"><img src="' + image + '" /></div>';
				}

				break;
		}


	}

}

#>
<div class="kc-image-gallery {{{wrp_class}}}">
	<# if( title !== '' ){#>
	<h3 class="kc-title image-gallery-title">{{{title}}}</h3>
	<# } #>
	<div {{{element_attribute.join(' ')}}}>
		{{{output}}}
	</div>
</div>
<#
	data.callback = function( wrp, $){
		if( wrp.find(".kc_image_gallery").data("type") == 'image_masonry' )
			kc_front.image_gallery.masonry( wrp );
	}
#>




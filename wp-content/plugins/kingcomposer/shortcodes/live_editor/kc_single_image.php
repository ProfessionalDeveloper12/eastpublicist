<#

var output 				= '',
	atts 				= ( data.atts !== undefined ) ? data.atts : {},
	default_src 		= kc_url + "/assets/images/get_start.jpg",
	element_attributes 	= [],
	image_attributes 	= [],
	css_classes 		= kc.front.el_class( atts ),
	image_classes 		= [],
	image_url 			= '';
	image_full 			= '';
	link 			    = '',
	target 			    = '',
	title_link 			= '',
	overlay_html 			= '',
	image_id 			= ( atts['image'] !== undefined ) ?  atts['image'] : '',
	overlay 			= ( atts['overlay'] !== undefined ) ?  atts['overlay'] : '',
	icon 			    = ( atts['icon'] !== undefined ) ?  atts['icon'] : 'fa-plus',
	image_size 			= ( atts['image_size'] !== undefined ) ?  atts['image_size'] : '',
	on_click_action 	= ( atts['on_click_action'] !== undefined ) ?  atts['on_click_action'] : '',
	image_source 		= ( atts['image_source'] !== undefined ) ?  atts['image_source'] : '',
	caption 		    = ( atts['caption'] !== undefined &&  atts['caption'] !== '__empty__' ) ?  atts['caption'] : '',
	alt 		        = ( atts['alt'] !== undefined &&  atts['alt'] !== '__empty__' ) ?  atts['alt'] : '',
	image_wrap 			= 'yes',
	data_lightbox 		= '',
	sizes 		= ['full', 'thumbnail', 'medium', 'large'];

css_classes.push( 'kc_shortcode' );
css_classes.push( 'kc_single_image' );


if ( atts['class'] !== undefined && atts['class'] !== ''  )
	css_classes.push( atts['class'] );

if ( atts['ieclass'] !== undefined )
	image_classes.push( atts['ieclass'] );

if( image_source == 'external_link' ) {

	var image_full 	= ( atts['image_external_link'] !== undefined ) ? atts['image_external_link'] : '',
	image_url 	= image_full,
	size 		= ( atts['image_size_el'] !== undefined ) ? atts['image_size_el'] : 'full';

	if( image_url !== '' )
		image_attributes.push( 'src="' + image_url + '"' );
	else
		image_attributes.push( 'src="' + default_src + '"' );

	if( image_full == '' )
		image_full = default_src;

	var regx = /(\d+)x(\d+)/;

	if (  result = regx.exec( size ) ) {
		var width 	= result[1],
		height 	= result[2];

		image_attributes.push( 'width="' + width + '"' );
		image_attributes.push( 'height="' + height + '"' );
	}
} else {

	if( image_source == 'media_library' )
	{
		//image_id 	= image_id.replace( /[^\d]/, '' );
	}
	else
	{
		image_id 	= kc_post_thumnail_ID;
	}

	image_full 	= ajaxurl + '?action=kc_get_thumbn&size=full&id=' + image_id;
	
	if ( sizes.indexOf( image_size ) > -1  ) {
		image_url 	= ajaxurl + '?action=kc_get_thumbn&id=' + image_id + '&size=' + image_size ;
	}else if( image_size.indexOf('x') > 0 ){
		image_url 	= ajaxurl + '?action=kc_get_thumbn_size&id=' + image_id + '&size=' + image_size ;
	}
	
	

	if( image_url !== '' ) {
		image_attributes.push('src="' + image_url + '"');
	} else {
		image_attributes.push('src="' + default_src + '"');
		image_classes.push('kc_image_empty');
	}

	if( image_full === '' )
		image_full = default_src;

}

image_attributes.push( 'class="' + image_classes.join(' ') + '"' );

if( alt !== '' )
	image_attributes.push( 'alt="' + alt + '"');
else
	image_attributes.push( 'alt=""' );
	
title_link = alt;
target = '_self';
	
if( on_click_action == 'lightbox' )
{

	data_lightbox = 'rel="prettyPhoto" class="kc-pretty-photo"';
}
else if( on_click_action == 'open_custom_link' )
{
	link = atts['custom_link'];
	link = link.split('|');
	if( link[0] !== undefined )
		image_full = link[0];
	
	if( link[1] !== undefined )
		title_link =  link[1];
	
	if( link[2] !== undefined )
		target= link[2];
}

element_attributes.push('class="' + css_classes.join(' ') + '"');
	
//overlay layer
if( overlay === 'yes' )
	overlay_html = '<div class="kc-image-overlay"><i class="' + icon + '"></i></div>';
	

if( on_click_action !== '' ) {
		output += '<a ' + data_lightbox + ' href="' + image_full + '" title="' + title_link + '"><img ' + image_attributes.join(' ') + ' target="' + target + '" />' + overlay_html +'</a>';

} else {
	output += '<img ' + image_attributes.join(' ') + '/>' + overlay_html;
}

if( caption !== '' ){
output += '<p class="scapt">' + caption + '</p>';
}
#>
<div {{{element_attributes.join(' ')}}}>
{{{output}}}</div>
<#
data.callback = function( wrp, $ ){
	kc_front.single_img.refresh( wrp );
}
#>

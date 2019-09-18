<#
if( data === undefined )
	var data = {};
	
var atts		= ( data.atts !== undefined ) ? data.atts : {},
	layout			= ( atts['layout'] !== undefined ) ? atts['layout'] : '1',
	position		= ( atts['position'] !== undefined ) ? atts['position'] : '',
	icon 			= ( atts['icon'] !== undefined ) ? atts['icon'] : '',
	text_tooltip 	= ( atts['text_tooltip'] !== undefined && atts['text_tooltip'] !== '__empty__'  ) ? kc.tools.base64.decode( atts['text_tooltip']) : '',
	image 			= ( atts['image'] !== undefined ) ? atts['image'] : '',
	img_size 		= ( atts['img_size'] !== undefined ) ? atts['img_size'] : 'full',
	button_text 	= ( atts['button_text'] !== undefined  && atts['button_text'] !== '__empty__' ) ? atts['button_text'] : '',
	button_link	    = ( atts['button_link'] !== undefined ) ? atts['button_link'] : '||',
	custom_class	= ( atts['custom_class'] !== undefined ) ? atts['custom_class'] : '',
	data_icon 		= '',
	data_button 	= '',
	data_img 		= '',
	data_position 	= '',
	img_link 	    = '',
	button_title 	= '',
	button_target 	= '',
	button_link_text = '',
	button_attr  	= [],
	main_class      = ['kc_tooltip'],
	wrapper_class   = kc.front.el_class( atts );

wrapper_class.push( 'kc-popover-tooltip' );

if ( custom_class !== '' )
	wrapper_class.push( custom_class );

main_class.push( 'style' + layout );

if ( icon !== '' ) {

	data_icon += '<i class="' + icon + ' fati17"></i>';

}

if ( image !='' ) {

	//image 	= image.replace( /[^\d]/, '' );
	
	if ( img_size !== 'full'  ) {
		img_link = ajaxurl + '?action=kc_get_thumbn_size&id=' + image + '&size=' + img_size ;
	} else {
		img_link =  ajaxurl + '?action=kc_get_thumbn&size=full&id=' + image;
	}
	
}else{
	img_link = kc_url + "/assets/images/get_start_s.jpg";
}

data_img += '<img src="' + img_link + '" alt="">';


button_attr.push( 'class="kc_tooltip style3"' );
button_attr.push( 'data-tooltip="true"' );
button_attr.push( 'data-position="' + position + '"' );

if ( button_link !== '' ) {

	button_link_text = button_link.split('|');
	button_link = button_link_text[0];
	if ( button_link_text[1] !== undefined )
		button_title = button_link_text[1];

	if ( button_link_text[2] !== undefined )
		button_target = button_link_text[2];

} else {
	button_link = '#';
}

button_attr.push( 'href="' + button_link + '"' );

if ( button_title !== '' )
	button_attr.push( 'title="' + button_title + '"' );
if ( button_target !== '' )
	button_attr.push( 'target="' + button_target + '"' );

if( button_text === '' )
	button_text = "<?php echo __( 'Read More', 'kingcomposer' );?>";

data_button += '<a ' + button_attr.join(' ') + '>' + button_text + '<span>' + text_tooltip + '</span></a>';

#>

<div class="{{{wrapper_class.join(' ')}}}">

	<#
	switch ( layout ) {
		case '2':
	#>
			<div class="{{{main_class.join(' ')}}}" data-tooltip="true" data-position="{{{position}}}">
				{{{data_img}}}
				<span>{{{text_tooltip}}}</span>
			</div>
	<#
		break;
		case '3':
		#>
			<div class="content-button">
			{{{data_button}}}
			</div>
	<#
		break;
		default:
	#>
			<div class="{{{main_class.join(' ')}}}" data-tooltip="true" data-position="{{{position}}}">
				{{{data_icon}}}
				<span>{{{text_tooltip}}}</span>
			</div>
	<#
	
	}
	#>
		
</div>
<#
	
	data.callback = function( wrp, $ ){
		kc_front.tooltips( wrp );
	}
#>

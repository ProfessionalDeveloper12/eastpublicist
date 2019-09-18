<#

if( data === undefined )
data = {};

var atts		= ( data.atts !== undefined ) ? data.atts : {},

	layout			= ( atts['layout'] !== undefined ) ? atts['layout'] : '1',
	title 			= ( atts['title'] !== undefined  && atts['title']  !== '__empty__') ? atts['title'] : '',
	desc 			= ( atts['desc'] !== undefined  && atts['desc']  !== '__empty__') ? kc.tools.base64.decode( atts['desc']) : '',
	icon 			= ( atts['icon'] !== undefined  && atts['icon'] !== '__empty__') ? atts['icon'] : 'et-envelope',
	image 			= ( atts['image'] !== undefined ) ? atts['image'] : '',
	position 		= ( atts['position'] !== undefined  && atts['position']  !== '__empty__') ? atts['position'] : '',
	show_button 	= ( atts['show_button'] !== undefined ) ? atts['show_button'] : '',
	button_text 	= ( atts['button_text'] !== undefined  && atts['button_text']  !== '__empty__') ? atts['button_text'] : '',
	button_link 	= ( atts['button_link'] !== undefined ) ? atts['button_link'] : '',
	custom_class	= ( atts['custom_class'] !== undefined ) ? atts['custom_class'] : '',
	img_link 		= '',
	but_link_text	= '',
	data_img 		= '',
	data_icon 		= '',
	data_title 		= '',
	data_desc 		= '',
	data_position 	= '',
	data_button 	= '',
	btn_title       = '',
	btn_target       = '',
	wrap_class		= [];

wrap_class = kc.front.el_class( atts );

wrap_class.push( 'kc-feature-boxes kc-fb-layout-' + layout );

if ( custom_class !=='' ) {
	wrap_class.push( custom_class );
}

if ( image !='' ) {
	//image 	= image.replace( /[^\d]/, '' );
	img_link 	= ajaxurl + '?action=kc_get_thumbn&id=' + image + '&size=full';

	data_img += '<figure class="content-image">';
	data_img += '<img src="'+ img_link +'" alt="">';
	data_img += '</figure>';
}

if ( title !== '' ) {
	data_title += '<div class="content-title">'+ title +'</div>';
}

if ( desc !== '' ) {
	data_desc += '<div class="content-desc">'+ desc +'</div>';
}

if ( position !== '' ) {
	data_position += '<div class="content-position">'+ position +'</div>';
}

data_icon += '<div class="content-icon">';
	data_icon += '<i class="'+ icon +'"></i>';
data_icon += '</div>';

if ( show_button == 'yes' ) {
	if ( button_link !== '' ) {
		but_link_text = button_link.split( '|' );
		button_link = but_link_text[0];
	}
	if( but_link_text[1] !== undefined )
		btn_title =  but_link_text[1];
	
	if( but_link_text[2] !== undefined )
		btn_target =  but_link_text[2];
	

	data_button += '<div class="content-button">';
		data_button += '<a href="'+ button_link +'" title="' + btn_title + '" target="' + btn_target + '">'+ button_text +'</a>';
	data_button += '</div>';
}

#>

<div class="{{{wrap_class.join(' ')}}}">

	<#
	switch ( layout ) {
		case '2':
	#>
			{{{data_img}}}
			{{{data_title}}}
			{{{data_desc}}}
			{{{data_button}}}
	<#
		break;
		case '3':
	#>
			{{{data_icon}}}
			<div class="box-right">
				{{{data_title}}}
				{{{data_desc}}}
			</div>
	<#
		break;
		case '4':
	#>
			{{{data_img}}}
			<div class="box-right">
				{{{data_position}}}
				{{{data_title}}}
				{{{data_desc}}}
				{{{data_button}}}
			</div>
	<#
		break;
		case '5':
	#>
			{{{data_position}}}
			{{{data_title}}}
			{{{data_desc}}}
			{{{data_button}}}
	<#
		break;
		default:
	#>
			{{{data_icon}}}
			{{{data_title}}}
			{{{data_desc}}}
			{{{data_button}}}
	<#
		break;
	} #>

</div>

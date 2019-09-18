<#
	
if( data === undefined )
	var data = {};
	
var atts		= ( data.atts !== undefined ) ? data.atts : {},
	desc		= ( atts['desc'] !== undefined )? kc.tools.base64.decode( atts['desc'] ) : '',
	subtitle	= ( atts['subtitle'] !== undefined && atts['subtitle'] !== '__empty__' )? atts['subtitle'] : '',
	title		= ( atts['title'] !== undefined  && atts['title'] !== '__empty__' )? atts['title'] : '',
	image		= ( atts['image'] !== undefined )? atts['image'] : '',
	el_class		= ( atts['custom_class'] !== undefined )? atts['custom_class'] : '',
	img_size	= ( atts['img_size'] !== undefined )? atts['img_size'] : 'full',
	show_button	= ( atts['show_button'] !== undefined )? atts['show_button'] : '',
	button_link	= ( atts['button_link'] !== undefined )? atts['button_link'] : '||',
	button_text = ( atts['button_text'] !== undefined && atts['button_text'] !== '__empty__')? atts['button_text'] : '',
	layout	= ( atts['layout'] !== undefined )? atts['layout'] : '1',
	data_img 	= '',
	img_link 	= '',
	button_link_text = '',
	data_title 	= '',
	data_desc 	= '',
	data_subtitle = '',
	data_socials = '',
	socials 	= '',
	data_button = '',
	icon 		= '',
	social_list = ['facebook', 'twitter', 'google_plus', 'linkedin', 'pinterest', 'flickr',	'instagram', 'dribbble', 'reddit', 'email'],
	sizes 		= ['full', 'thumbnail', 'medium', 'large'],
	wrap_class	= kc.front.el_class( atts );

wrap_class.push( 'kc-team' );
wrap_class.push( 'kc-team-' + layout ) ;

if ( el_class !== '' )
	wrap_class.push( el_class );

if ( image != '' ) {
	//image 	= image.replace( /[^\d]/, '' );

	if ( sizes.indexOf( img_size ) < 0  ) {
		img_link = ajaxurl + '?action=kc_get_thumbn_size&id=' + image + '&size=' + img_size ;
	} else {
		img_link =  ajaxurl + '?action=kc_get_thumbn&size=' + img_size + '&id=' + image;
	}

	data_img += '<figure class="content-image">';
	data_img += '<img src="' + img_link + '" alt="">';
	data_img += '</figure>';

}

if ( title !== '' ) {

	data_title += '<div class="content-title">';
	data_title += title;
	data_title += '</div>';

}

if ( desc !=='' ) {

	data_desc += '<div class="content-desc">';
	data_desc += desc;
	data_desc += '</div>';

}

if ( subtitle !== '' ) {

	data_subtitle += '<div class="content-subtitle">';
	data_subtitle += subtitle;
	data_subtitle += '</div>';

}

if ( show_button === 'yes' ) {

	if ( button_link !== '' ) {
		button_link_text = button_link.split('|');
		button_link = button_link_text[0];
	}

	if( button_text === '' )
		button_text = "<?php echo __( 'Readmore', 'kingcomposer' );?>";

	data_button += '<div class="content-button">';
	data_button += '<a href="' + button_link + '">' + button_text + '</a>';
	data_button += '</div>';

}
	
for (var i = 0; i < social_list.length; i++) {
	if( atts[social_list[i]] !== undefined && atts[social_list[i]] !== '__empty__' ){
		icon = social_list[i];
		icon = icon.replace('_', '-');
		icon = icon.replace('email', 'envelope-o');
		data_socials += '<a href="' + atts[social_list[i]] + '" target="_blank"><i class="fa-' + icon + '"></i></a>';
	}

}

if( data_socials !== '' )
	data_socials = '<div class="content-socials">' + data_socials + '</div>';

#>

<div class="{{{wrap_class.join(' ')}}}">

	<#
	switch ( parseInt( layout ) ) {
		case 2:
		#>
			{{{data_img}}}
			<div class="box-right">
			{{{data_title}}}
			{{{data_subtitle}}}
			{{{data_desc}}}
			{{{data_socials}}}
			</div>
		<#
		break;
		case 3:
		#>
			{{{data_img}}}
			<div class="overlay">
			{{{data_title}}}
			{{{data_subtitle}}}
			{{{data_desc}}}
			{{{data_button}}}
			{{{data_socials}}}
			</div>
		<#
		break;
		default:
		#>
			{{{data_img}}}
			{{{data_title}}}
			{{{data_subtitle}}}
			{{{data_desc}}}
			{{{data_socials}}}
			{{{data_button}}}
		<#
	}
	#>
</div>

<#
if( data === undefined )
	var data = {};
	
var atts 			= ( data.atts !== undefined ) ? data.atts : {},
	layout		= ( atts['layout'] !== undefined )? atts['layout'] : '1',
	title		= ( atts['title'] !== undefined  && atts['title']  !== '__empty__')? atts['title'] : '',
	desc 			= ( atts['desc'] !== undefined  && atts['desc']  !== '__empty__') ? kc.tools.base64.decode( atts['desc']) : '',
	image	= ( atts['image'] !== undefined )? atts['image'] : '',
	event_click	= ( atts['event_click'] !== undefined )? atts['event_click'] : '',
	custom_link	= ( atts['custom_link'] !== undefined )? atts['custom_link'] : '||',
	button_text	= ( atts['button_text'] !== undefined )? atts['button_text'] : '',
	button_link	= ( atts['button_link'] !== undefined )? atts['button_link'] : '||',
	icon		= ( atts['icon'] !== undefined )? atts['icon'] : 'fa-star',
	custom_class	= ( atts['custom_class'] !== undefined )? atts['custom_class'] : '',
	img_size	= ( atts['img_size'] !== undefined )? atts['img_size'] : '1170x700xct',
	img_url = '',
	link_url = '',
	link_title = '',
	link_target = '',
	before_url = '',
	after_url = '',
	data_img = '',
	data_title = '',
	data_desc = '',
	data_button = '',
	button_attr = [],
	img_arr = [],
	link_arr = [],
	wrap_class  = kc.front.el_class( atts );
	

wrap_class.push( 'kc-image-hover-effects kc-img-effects-' + layout );
	
if ( custom_class !== '' )
	wrap_class.push( custom_class );


if ( image != '' ) {
	//image 	= image.replace( /[^\d]/, '' );
	
	if ( img_size !== 'full'  ) {
		img_link = ajaxurl + '?action=kc_get_thumbn_size&id=' + image + '&size=' + img_size ;
		img_full = ajaxurl + '?action=kc_get_thumbn&id=' + image + '&size=full' ;
	} else {
		img_link =  ajaxurl + '?action=kc_get_thumbn&size=full&id=' + image;
		img_full = img_link;
	}
	
} else {
	img_link = kc_url + "/assets/images/get_start.jpg";
	img_full = kc_url + "/assets/images/get_start.jpg";
}

if ( custom_link !== '' ) {
	img_arr = custom_link.split('|');

	if ( img_arr[0] !== '' ) {
		img_url = img_arr[0];
	} else {
		img_url = '#';
	}

} else {
	img_url = '#';
}

if ( button_link !== '' ) {
	
	link_arr = button_link.split('|');
	
	if( link_arr[0] !== undefined )
		link_url = link_arr[0];
	else
		link_url = '#';



	if ( link_arr[1]  !== undefined )
		link_title = link_arr[1];

	if ( link_arr[2]  !== undefined )
		link_target = link_arr[2];

} else {
	link_url = '#';
}


button_attr.push( 'href="' + link_url + '"' );
if ( link_title !== '' )
	button_attr.push( 'title="' + link_title + '"' );
	
if ( link_target !== '' )
	button_attr.push( 'target="' + link_target + '"' );

switch ( event_click ) {
	case 'none':
		data_img = '<figure><img src="' + img_link + '" alt=""/></figure>';
	break;
	case 'custom_link':
		data_img = '<a href="' + img_url + '"><img src="' + img_link + '" alt=""/></a>';
		before_url = '<a href="' + img_url + '">';
		after_url	= '</a>';
	break;
	default:
		data_img = '<a href="' + img_full + '" rel="prettyPhoto" class="kc-pretty-photo"><img src="' + img_link + '" alt=""/></a>';
		before_url = '<a href="' + img_full + '" rel="prettyPhoto" class="kc-pretty-photo">';
		after_url	= '</a>';
	break;
}

if ( title !== '' ) {
	data_title = '<div class="content-title">' + title + '</div>';
}

if ( desc !== '' ) {
	data_desc = '<div class="content-desc">' + desc + '</div>';
}

if ( button_text !== '' ) {
	data_button = '<div class="content-button"><a ' + button_attr.join(' ') + '>' + button_text + '</a></div>';
}

#>

<div class="{{{wrap_class.join(' ')}}}">

	<# switch ( layout ) {
		case '2':
	#>
			{{{data_img}}}
			{{{before_url}}}
			<div class="overlay-effects">
				{{{data_title}}}
				{{{data_desc}}}
			</div>
			{{{after_url}}}
		<#
		break;
		case '3':
		#>
			{{{data_img}}}
			{{{before_url}}}
			<div class="overlay-effects">
				<div class="overlay-content">
					{{{data_title}}}
					{{{data_desc}}}
				</div>
			</div>
			{{{after_url}}}
		<#
		break;
		case '4':
		#>
			{{{data_img}}}
			<div class="overlay-effects">
				{{{data_title}}}
				{{{data_desc}}}
				{{{data_button}}}
			</div>
		<#
		break;
		case '5':
		#>
			{{{data_img}}}
			{{{before_url}}}
			<div class="overlay-effects">
				<i class="{{{icon}}}"></i>
			</div>
			{{{after_url}}}
		<#
		break;
		default:
			#>
			{{{data_img}}}
			{{{before_url}}}
			<div class="overlay-effects">
				{{{data_title}}}
			</div>
			{{{after_url}}}
		<#
		break;
	} #>

</div>
<#
data.callback = function( wrp, $ ){
kc_front.single_img.refresh( wrp );
}
#>

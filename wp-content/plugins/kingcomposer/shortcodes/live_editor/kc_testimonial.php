<#

if( data === undefined )
data = {};

var atts		= ( data.atts !== undefined ) ? data.atts : {},

	layout			= ( atts['layout'] !== undefined ) ? atts['layout'] : '1',
	title 			= ( atts['title'] !== undefined  && atts['title'] !== '__empty__' ) ? atts['title'] : '',
	desc 			= ( atts['desc'] !== undefined && atts['desc'] !== '__empty__'  ) ? kc.tools.base64.decode( atts['desc']) : '',
	image 			= ( atts['image'] !== undefined ) ? atts['image'] : '',
	img_size 		= ( atts['img_size'] !== undefined ) ? atts['img_size'] : 'full',
	position 		= ( atts['position'] !== undefined  && atts['position'] !== '__empty__' ) ? atts['position'] : '',
	custom_class	= ( atts['custom_class'] !== undefined ) ? atts['custom_class'] : '',
	data_title 		= '',
	data_desc 		= '',
	data_img 		= '',
	data_position 	= '',
	wrap_class		= [];

wrap_class = kc.front.el_class( atts );
wrap_class.push( 'kc-testimo kc-testi-layout-' + layout );

if ( custom_class !=='' ) {
	wrap_class.push( custom_class );
}

if ( image !='' ) {
	//image 	= image.replace( /[^\d]/, '' );

	if ( img_size !== 'full'  ) {
		img_link = ajaxurl + '?action=kc_get_thumbn_size&id=' + image + '&size=' + img_size ;
	} else {
		img_link = ajaxurl + '?action=kc_get_thumbn&size=' + img_size + '&id=' + image;
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

if ( position !== '' ) {

	data_position += '<div class="content-position">';
		data_position += position;
	data_position += '</div>';

}

#>

<div class="{{{wrap_class.join(' ')}}}">

	<# switch ( layout ) {
		case '2':
	#>
			{{{data_title}}}
			{{{data_position}}}
			{{{data_desc}}}
	<#
		break;
		case '3':
	#>
			{{{data_img}}}
			{{{data_title}}}
			{{{data_position}}}
			{{{data_desc}}}
	<#
		break;
		case '4':
	#>
			{{{data_img}}}
			<div class="box-right">
				{{{data_desc}}}
				{{{data_position}}}
				{{{data_title}}}
			</div>
	<#
		break;
		case '5':
	#>
			{{{data_desc}}}
			{{{data_img}}}
			<div class="box-right">
				{{{data_title}}}
				{{{data_position}}}
			</div>
	<#
		break;
		default:
	#>
			{{{data_img}}}
			{{{data_desc}}}
			{{{data_title}}}
			{{{data_position}}}
	<#
		break;
	} #>

</div>

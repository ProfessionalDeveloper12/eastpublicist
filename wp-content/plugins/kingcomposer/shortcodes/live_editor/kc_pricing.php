<#

if( data === undefined )
	var data = {};

var atts		= ( data.atts !== undefined ) ? data.atts : {},
	desc		= ( atts['desc'] !== undefined )? kc.tools.base64.decode( atts['desc'] ) : '',
	show_icon_header	= ( atts['show_icon_header'] !== undefined )? atts['show_icon_header'] : '',
	icon_header		= ( atts['icon_header'] !== undefined && atts['icon_header'] !== '__empty__' )? atts['icon_header'] : 'fa-rocket',
	price		= ( atts['price'] !== undefined )? atts['price'] : '',
	title		= ( atts['title'] !== undefined && atts['title'] !== '__empty__' )? atts['title'] : '',
	subtitle	= ( atts['subtitle'] !== undefined && atts['subtitle'] !== '__empty__' )? atts['subtitle'] : '',
	el_class	= ( atts['custom_class'] !== undefined )? atts['custom_class'] : '',
	currency	= ( atts['currency'] !== undefined )? atts['currency'] : '',
	show_on_top	= ( atts['show_on_top'] !== undefined )? atts['show_on_top'] : '',
	duration	= ( atts['duration'] !== undefined )? atts['duration'] : '',
	show_icon	= ( atts['show_icon'] !== undefined )? atts['show_icon'] : '',
	icon	    = ( atts['icon'] !== undefined && atts['icon'] !== '__empty__')? atts['icon'] : 'fa-check',
	button_text = ( atts['button_text'] !== undefined )? atts['button_text'] : '||',
	show_button = ( atts['show_button'] !== undefined )? atts['show_button'] : '||',
	button_link = ( atts['button_link'] !== undefined )? atts['button_link'] : '||',
	layout	    = ( atts['layout'] !== undefined )? atts['layout'] : '1',
	link_url    = '#',
	data_icon_header = '',
	data_title  = '',
	data_price  = '',
	data_currency = '',
	data_duration = '',
	data_desc   = '',
	data_button = '',
	link_arr	= [],
	wrap_class	= kc.front.el_class( atts );

wrap_class.push( 'kc-pricing-tables');
wrap_class.push( 'kc-pricing-layout-' + layout );


if ( el_class !== '' )
	wrap_class.push( el_class );

if ( show_on_top === 'yes' )
	wrap_class.push( 'kc-price-before-currency' );

if ( show_icon_header === 'yes' ) {

	data_icon_header += '<div class="content-icon-header">';
	data_icon_header += '<i class="' + icon_header + '"></i>';
	data_icon_header += '</div>';

}

if ( title !== '' ) {

	data_title += '<div class="content-title">';
		if ( subtitle !== '' ) {

			data_title += '<div>' +  title + '</div>';
			data_title += '<div class="content-sub-title">' +  subtitle + '</div>';

		} else {
			data_title += title;
		}
	data_title += '</div>';

}


if ( price !== '' ) {

	data_price += '<span class="content-price">' + price + '</span>';

}

if ( currency !=='' ) {

	data_currency += '<span class="content-currency">' + currency + '</span>';

}

if ( duration !== '' ) {

	data_duration += '<span class="content-duration">' + duration + '</span>';

}

if ( desc !== '' ) {
	var pros = desc.split("\n");

	if( pros.length > 0 ) {

		data_desc += '<ul class="content-desc">';

		for( var i=0; i < pros.length ; i++) {
			if ( show_icon === 'yes' ) {
				data_desc += '<li><i class="' + icon + '"></i> ' + pros[i] + ' </li>';
			} else {
				data_desc += '<li>' + pros[i] + ' </li>';
			}
		}
		data_desc += '</ul>';

	}

}

if ( show_button === 'yes' ) {

	if ( button_link !== '' ) {
		link_arr = button_link.split('|');

		if ( link_arr[0] !== undefined )
			link_url = link_arr[0];
		else
			link_url = '#';
	}

	data_button += '<div class="content-button">';
	data_button += '<a href="' + link_url + '">' + button_text + '</a>';
	data_button += '</div>';

}

#>

<div class="{{{wrap_class.join(' ')}}}">

	<#

	switch ( parseInt( layout ) ) {
		case 2:
	#>
			<div class="header-pricing">
				{{{data_title}}}
				<div class="kc-pricing-price">
				<#	if ( show_on_top === 'yes' ) { #>
					{{{data_price}}}
					{{{data_currency}}}
					{{{data_duration}}}

				<# } else { #>
					{{{data_currency}}}
					{{{data_price}}}
					{{{data_duration}}}

				<# } #>

				</div>
			</div>
			{{{data_desc}}}
			{{{data_button}}}
		<#
		break;

		case 3:
		#>
			{{{data_title}}}
			{{{data_desc}}}
			<div class="kc-pricing-price">
				<# if ( show_on_top === 'yes' ) { #>
					{{{data_price}}}
					{{{data_currency}}}
					{{{data_duration}}}
				<# } else { #>
					{{{data_currency}}}
					{{{data_price}}}
					{{{data_duration}}}
				<# } #>
			</div>
			{{{data_button}}}
		<#
		break;

		case 4:
		#>
			<div class="header-pricing">
			{{{data_icon_header}}}
			{{{data_title}}}
			<div class="kc-pricing-price">
				<# if ( show_on_top === 'yes' ) { #>
					{{{data_price}}}
					{{{data_currency}}}
					{{{data_duration}}}
				<# } else { #>
					{{{data_currency}}}
					{{{data_price}}}
					{{{data_duration}}}
				<# } #>
			</div>
			</div>
			{{{data_desc}}}
			{{{data_button}}}
		<#
		break;

		default:
		#>
			<div class="header-pricing">
				{{{data_title}}}
				{{{data_icon_header}}}
				<div class="kc-pricing-price">
					<# if ( show_on_top === 'yes' ) { #>
						{{{data_price}}}
						{{{data_currency}}}
						{{{data_duration}}}
						<# } else { #>
							{{{data_currency}}}
							{{{data_price}}}
							{{{data_duration}}}
							<# } #>
				</div>
			</div>
			{{{data_desc}}}
			{{{data_button}}}
	<#
	}
	#>

</div>

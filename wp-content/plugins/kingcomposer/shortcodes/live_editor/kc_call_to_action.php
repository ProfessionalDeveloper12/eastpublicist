<#
if( data === undefined )
	var data = {};

var atts		= ( data.atts !== undefined ) ? data.atts : {},
	layout			= ( atts['layout'] !== undefined ) ? atts['layout'] : '2',
	title 			= ( atts['title'] !== undefined ) ? atts['title'] : '',
	desc 			= ( atts['desc'] !== undefined ) ? kc.tools.base64.decode( atts['desc']) : '',
	button_show 	= ( atts['button_show'] !== undefined ) ? atts['button_show'] : '',
	button_text 	= ( atts['button_text'] !== undefined ) ? atts['button_text'] : '',
	button_link 	= ( atts['button_link'] !== undefined ) ? atts['button_link'] : '',
	icon_show 		= ( atts['icon_show'] !== undefined ) ? atts['icon_show'] : '',
	icon 			= ( atts['icon'] !== undefined && atts['icon'] !== '__empty__' ) ? atts['icon'] : 'fa-rocket',
	custom_class 	= ( atts['custom_class'] !== undefined  ) ? atts['custom_class'] : '',
	link_url 		= '',
	link_title		= '',
	link_target 	= '',
	data_text 		= '',
	data_title 		= '',
	data_button 	= '',
	button_attr 	= [],
	main_class		= kc.front.el_class( atts );

main_class.push( 'kc-call-to-action' );
main_class.push( 'kc-cta-' + layout );

if ( button_show === 'yes' )
	main_class.push( 'kc-is-button' );

if ( custom_class !== '' )
	main_class.push( custom_class );

if ( title !== '' || desc !== '' ) {

	data_text += '<div class="kc-cta-desc">';
		if ( title !== '' ) {
			data_text += '<h2>'  + title + '</h2>';
		}
		if ( desc !== '' ) {
			data_text += '<div class="kc-cta-text">' + desc  + '</div>';
		}
	data_text += '</div>';

}

if (   button_show === 'yes' && button_text !== '' ) {

	if ( button_link !== '' ) {
		link_arr = button_link.split('|');

		if ( link_arr[0] !== undefined ) {
			link_url =   link_arr[0];
		} else {
			link_url = '#';
		}

		if ( link_arr[1] !== undefined )
			link_title =   link_arr[1];

		if ( link_arr[2] !== undefined )
			link_target =   link_arr[2];

	} else {
		link_url = '#';
	}

	if ( icon_show === 'yes' ) {
		button_text += ' <span class="kc-cta-icon"><i class="' + icon + '"></i></span>';
	}

	button_attr.push( 'href="' + link_url + '"' );

	if ( link_title !== '' )
		button_attr.push( 'title="' + link_title + '"' );

	if ( link_target !== '' )
		button_attr.push( 'target="' + link_target + '"' );

	data_button += '<div class="kc-cta-button">';
	data_button += '<a ' + button_attr.join(' ') + '>'  + button_text   + '</a>';
	data_button += '</div>';

}

#>

<div class="{{{main_class.join(' ')}}}">
	{{{data_text}}}
	{{{data_button}}}
</div>

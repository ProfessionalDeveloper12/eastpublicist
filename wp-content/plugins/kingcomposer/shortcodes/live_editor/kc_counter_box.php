<#

if( data === undefined )
data = {};

var atts 				= ( data.atts !== undefined ) ? data.atts : {},
	_before_number = '',
	_after_number = '',
	icon = '',
	label = '',
	el_classess = [];

el_classess 	= kc.front.el_class( atts );;
el_classess.push( 'kc_counter_box' );


if ( atts['wrap_class'] !== undefined )
	el_classess.push( atts['wrap_class'] );

label = ( atts['label'] !== undefined ) ? '<h4>'+ atts['label'] +'</h4>' : '';

if( atts['icon_show'] !== undefined && atts['icon_show'] == 'yes' ) {
	icon = ( atts['icon'] !== undefined ) ? atts['icon'] : 'fa-leaf';
	icon = ( icon !== '' ) ? '<i class="' + icon + ' element-icon"></i>' : '';
} else {
	icon = '';
}

if( atts['label_above'] !== undefined && 'yes' === atts['label_above'] ){

	_before_number = icon + label;

} else {

	_before_number = icon;
	_after_number = label;

}

#>
<div class="{{{el_classess.join(' ')}}}">
	{{{_before_number}}}
	<span class="counterup">{{{atts['number']}}}</span>
	{{{_after_number}}}
</div>
<#
data.callback = function( wrp, $ ){
	kc_front.counterup();
}
#>

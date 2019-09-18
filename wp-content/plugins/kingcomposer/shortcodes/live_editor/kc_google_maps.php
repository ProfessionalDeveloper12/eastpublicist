<#

if( data === undefined )
	data = {};


var output 				= '',
	contact_form = '',
	map_location = '',
	map_height 			= '250px',
	contact_position 	= 'left',
	element_attributes 	= [], map_attributes = [],
	atts 				= ( data.atts !== undefined ) ? data.atts : {};


var css_classes = [];

css_classes = kc.front.el_class( atts );

css_classes.push( 'kc_google_maps' );
css_classes.push( 'kc_shortcode' );

if( atts['contact_position'] !== undefined && atts['contact_area_position'] != '' )
	contact_position = atts['contact_area_position'];

if( atts['wrap_class'] !== undefined && atts['wrap_class'] !== '' )
	css_classes.push( atts['wrap_class'] );

element_attributes.push( 'class="'+ css_classes.join(' ') +'"' );

if( atts['title'] !== undefined && atts['title'] !== '' ){
    output += '<h3 class="map_title">'+ atts['title'] +'</h3>';
}

//Contact form on maps
if( atts['show_ocf'] !== undefined && 'yes' == atts['show_ocf'] ){

    if( atts['contact_form_sc'] !== undefined && atts['contact_form_sc'] != '' ){

        contact_form += '<div class="map_popup_contact_form '+ contact_position +'">';
        contact_form += '<a class="close" href="javascript:;"><i class="sl-close"></i></a>';
        contact_form += kc.tools.base64.decode(atts['contact_form_sc']);
        contact_form += '</div>';
        contact_form += '<a class="show_contact_form" href="javascript:;"><i class="fa fa-bars"></i></a>';
        
    }
}

map_attributes.push( 'class="kc-google-maps"' );
map_attributes.push( 'style="height: '+ parseInt(atts['map_height']) +'px"' );

if( atts['disable_wheel_mouse'] !== undefined && atts['disable_wheel_mouse'] != '' ){
	map_attributes.push( 'data-wheel="disable"' );
}

map_location = '<div style="width: 100%;height:'+atts['map_height']+';" class="disable-view-element"><h3>For best perfomance, the map has been disabled in this editing mode.</h3></div>';

output += '<div '+ element_attributes.join(' ') +'>'+ contact_form +'<div '+ map_attributes.join(' ') +'>'+ map_location +'</div></div>';

#>

<div {{{element_attributes.join(' ')}}}>
	{{{contact_form}}}
	<div {{{map_attributes.join(' ')}}}>{{{map_location}}}</div>
</div>
<#
	data.callback = function( wrp, $ ){
		kc_front.google_maps( wrp.parent() );	
	}
#>

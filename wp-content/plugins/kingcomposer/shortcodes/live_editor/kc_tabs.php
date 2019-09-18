<#

var output = '', tabs_option = {}, tabs_option_data = [], tab_nav_class = '', atts = ( data.atts !== undefined ) ? data.atts : {}, css_class = [];

tabs_option['open-on-mouseover'] = (atts['open_mouseover'] !== undefined) ? atts['open_mouseover'] : '';
tabs_option['tab-active'] = (atts['active_section'] !== undefined) ? atts['active_section'] : '';
tabs_option['effect-option'] = (atts['effect_option'] !== undefined) ? atts['effect_option'] : '';


for( var n in tabs_option ){
	tabs_option_data.push( 'data-'+n+'="'+tabs_option[n]+'"' );
}

css_class = kc.front.el_class( atts );
css_class.push( 'kc_tabs' );
css_class.push( 'group' );


if( undefined !== atts['css'] && atts['css'] !== '' )
	css_class.push( atts['css'].split('|')[0] );
	
if( undefined !== atts['class'] && atts['class'] !== '' )
	css_class.push( atts['class'] );

if( undefined !== atts['type'] && atts['type'] == 'vertical_tabs' )
{
	css_class.push( 'kc_vertical_tabs' );
	
	if( undefined !== atts['vertical_tabs_position'] && atts['vertical_tabs_position'] == 'right' )
		css_class.push( 'tabs_right' );

}
else if( atts['type'] == 'slider_tabs' ){
	
	css_class.push( 'kc-tabs-slider' );
	
	var owl_option = jQuery().extend({
		'items' : 1,
		'tablet' : 1,
		'mobile' : 1,
		'speed' : 450,
		'navigation' : false,
		'pagination' : true,
		'autoheight' : false,
		'autoplay' : false
	}, atts );
	
	owl_option = JSON.stringify( owl_option );
	
}
else{
	tab_nav_class = 'kc_tabs_nav';
}

data.callback = function( wrp ){ kc_front.tabs( wrp ) };



if( undefined !== atts['type'] && atts['type'] == 'slider_tabs' ){
	
#><div class="{{css_class.join(' ')}}"><#
	
	if( atts['title_slider'] !== undefined && atts['title_slider'] == 'yes' ){
		
		#><ul class="kc-tabs-slider-nav kc_clearfix">{{{kc.front.ui.process_tab_titles(data)}}}</ul><#
		
	}
#><div class="owl-carousel" data-owl-options="{{owl_option}}">{{{data.content}}}</div></div><#


}else{
	
	
#><div class="{{css_class.join(' ')}}" {{{tabs_option_data.join(' ')}}}>
	<div class="kc_wrapper ui-tabs kc_clearfix">
		<ul class="{{tab_nav_class}} ui-tabs-nav kc_clearfix">
			{{{kc.front.ui.process_tab_titles(data)}}}
		</ul>
<#

#>{{{data.content}}}</div></div><#

}

#>
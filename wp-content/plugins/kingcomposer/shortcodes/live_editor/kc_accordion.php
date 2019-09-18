<#

if( data === undefined )
	data = {};

var element_attributes = [], css_classes = [], 
	atts = ( data.atts !== undefined ) ? data.atts : {};
	
css_classes = kc.front.el_class( atts );
css_classes.push( 'kc_accordion_wrapper' );

if( atts['class'] !== undefined && atts['class'] !== '' )
	css_classes.push( atts['class'] );

if( atts['open_all'] !== undefined && atts['open_all'] == 'yes' )
	element_attributes.push( 'data-allowopenall="true"' );

if( atts['close_all'] !== undefined && atts['close_all'] == 'yes' )
	element_attributes.push( 'data-closeall="true"' );

element_attributes.push( 'class="'+css_classes.join(' ')+'"' );

#>
<div {{{element_attributes.join(' ')}}}><#
	if( atts['title'] !== undefined && atts['title'] !== '' ){
		#><h3 class="kc-accordion-title">{{atts['title']}}</h3><#
	}
#>{{{data.content}}}</div>
<#
data.callback =  function( wrp ){ kc_front.accordion( wrp ) };
#>

<#

if( data === undefined )
	data = {};

var el_class = kc.front.el_class( atts ), height = 0,
	atts = ( data.atts !== undefined ) ? data.atts : {};

if( atts['class'] !== undefined )
	el_class.push(atts['class']);

if( atts['height'] !== undefined )
	height = parseInt( atts['height'] );

#>
<div class="{{{el_class.join(' ')}}}" style="height: {{{height}}}px; clear: both; width:100%;"></div>

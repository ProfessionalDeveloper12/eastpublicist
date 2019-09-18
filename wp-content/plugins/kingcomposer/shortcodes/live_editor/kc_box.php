<#

var output = '', element_attributes = [], el_classes = [],
	atts = ( data.atts !== undefined ) ? data.atts : {};

el_classes = kc.front.el_class( atts );
el_classes.push( 'kc_box_wrap' );

if( atts['custom_class'] !== undefined && atts['custom_class'] !== '' )
	el_classes.push( atts['custom_class'] );

element_attributes.push( 'class="' + el_classes.join(' ') + '"' );

#>

<div class="{{el_classes.join(' ')}}"><#

data = kc.tools.base64.decode( atts['data'] );
data = data.replace( /\%SITE\_URL\%/g, kc_site_url );

if (data = JSON.parse(data))
{
	#>{{{kc.front.loop_box(data)}}}<#
}
else
{
	#>KC Box: Error content structure<#
}

if( atts['css_code'] !== undefined ){
	#><style type="text/css">{{{atts['css_code']}}}</style><# 
}
#></div>
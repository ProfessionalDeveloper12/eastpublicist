<#

var atts = ( data.atts !== undefined ) ? data.atts : {}, el_class = [];

el_class = kc.front.el_class( atts );
el_class.push( 'kc_text_block' );

if( atts['class'] !== undefined && atts['class'] !== '' )
	el_class.push( atts['class'] );

var content = top.switchEditors.wpautop(data._content);

if (content.indexOf('<p>') === -1)
	content = '<p>'+content+'</p>';
	
#>

<div class="{{el_class.join(' ')}}">{{{content}}}</div>
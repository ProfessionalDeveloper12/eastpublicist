<#
var atts 			= ( data.atts !== undefined ) ? data.atts : {},
    wrap_class 	    = [],
    desc 			= ( atts['desc'] !== undefined ) ? kc.tools.base64.decode( atts['desc'] ) : '',
    custom_class 	= ( atts['custom_class'] !== undefined ) ? atts['custom_class'] : '';

wrap_class 		= kc.front.el_class( atts );
    
wrap_class.push('kc-dropcaps');

if ( custom_class !== '' )
    wrap_class.push( custom_class );

desc = desc.replace(/^(<[a-zA-Z\s\d=\'\"]+>)(\s*[&nbsp;]*)*([a-zA-Z\d]{1})|^(\s*[&nbsp;]*)*([a-zA-Z\d]{1})|^(<[a-zA-Z\s\d=\'\"]+>)(\s*[&nbsp;]*)*([^\x00-\x7F]{1})|^(\s*[&nbsp;]*)*([^\x00-\x7F]{1})/i, '$1<span class="dropcaps-text">$3$5$8$10</span>');

#>
<div class="{{{wrap_class.join(' ')}}}">
    {{{desc}}}
</div>

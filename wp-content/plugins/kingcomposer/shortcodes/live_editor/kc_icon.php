<#
if( typeof( data ) == 'undefined' )
	data = {};

var output 			= '',
	icon_wrap_class = '',
	class_icon 		= [],
	atts 			= ( data.atts !== undefined ) ? data.atts : {},
	icon			= ( atts['icon'] !== undefined )? atts['icon'] : '',
	link			= ( atts['link'] !== undefined )? atts['link'] : '||',
	use_link		= ( atts['use_link'] !== undefined )? atts['use_link'] : '',
	has_link        = false,
	icon_link_attr  = [];


css_class = kc.front.el_class( atts );
	
css_class.push( 'kc-icon-wrapper' );

if ( atts['icon_wrap_class'] !== undefined && atts['icon_wrap_class'] !== '' )
	css_class.push( atts['icon_wrap_class'] );

if( icon === '' )
	icon = 'fa-leaf';

class_icon.push( icon );

if ( atts['class'] !== undefined )
	class_icon.push( atts['class'] );

if( use_link == 'yes' ){
	link = link.split('|');
	
	if( link[0] !== undefined ){
		icon_link_attr.push( 'href="' + link[0] + '"' );
		has_link = true;
	}
	
	if( link[1] !== undefined )
		icon_link_attr.push( 'title="' + link[1] + '"' );
	
	if( link[2] !== undefined )
		icon_link_attr.push( 'target="' + link[2] + '"' );
}
#>

<div class="{{{css_class.join(' ')}}}">
	<# if( has_link === true ){ #>
	<a {{{icon_link_attr.join(' ')}}}>
	<# } #>
	<i class="{{{class_icon.join(' ')}}}"></i>
	<# if( has_link === true ){ #>
	</a>
	<# } #>
</div>

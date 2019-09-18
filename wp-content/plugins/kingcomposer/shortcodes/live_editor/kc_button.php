<#

if( data === undefined )
	data = {};

var atts 				= ( data.atts !== undefined ) ? data.atts : {},
	textbutton 			= kc.std( atts, 'text_title', 'Button Text'),
	link 				= kc.std( atts, 'link', '#'),
	onclick 			= kc.std( atts, 'onclick', ''),
	wrap_class 			= kc.std( atts, 'wrap_class', ''),
	size 				= kc.std( atts, 'size', 'small'),
	show_icon 			= kc.std( atts, 'show_icon', 'no'),
	icon 				= kc.std( atts, 'icon', 'fa-leaf'),
	icon_position 		= kc.std( atts, 'icon_position', 'left'),
	button_attributes 	= [],
	wrapper_class 		= [];

wrapper_class = kc.front.el_class( atts );
wrapper_class.push( wrap_class );

link = link.split('|');

button_attributes.push( 'class="kc_button"' );

if( link[0] !== undefined )
	button_attributes.push( 'href="' + link[0] + '"' );

if( link[1] !== undefined )
	button_attributes.push( 'title="' + link[1] + '"' );

if( link[2] !== undefined )
	button_attributes.push( 'target="' + link[2] + '"' );
	
if( onclick !== undefined && onclick !== '')
	button_attributes.push( 'onclick="' + onclick + '"' );

if('yes' === show_icon){
	if( icon_position == 'left' ){
		textbutton = '<i class="' + icon + '"></i> ' + textbutton;
	}else if( icon_position == 'right'){
		textbutton += ' <i class="' + icon + '"></i>';
	}else{
		textbutton = '<i class="' + icon + '"></i>';
	}
}

#>
<div class="{{{wrapper_class.join(' ')}}}">
<a {{{button_attributes.join(' ')}}}>{{{textbutton}}}</a>
</div>

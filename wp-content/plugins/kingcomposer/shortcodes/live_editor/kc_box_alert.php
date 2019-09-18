<#
var output 			= '',
	atts 			= ( data.atts !== undefined ) ? data.atts : {},
	css_classes 	= [],
	title 			= ( atts['title'] !== undefined ) ? atts['title'] : '',
	icon 			= ( atts['icon'] !== undefined ) ? atts['icon'] : 'fa-leaf',
	show_button 	= ( atts['show_button'] !== undefined ) ? atts['show_button'] : '',
	classes 		= ( atts['class'] !== undefined ) ? atts['class'] : '',
	custom_style 	= '';

css_classes 		= kc.front.el_class( atts );

css_classes.push( classes );

css_classes 		= css_classes.join(' ');

title = kc.tools.base64.decode( title );

#>
<div class="message-boxes {{{css_classes}}}">
	<div class="message-box-wrap">
		<#
		if ( icon !== '' ) { #><i class="{{{icon}}}"></i><#	}

		if ( show_button == 'show' ) {
		#><button class="kc-close-but"><?php echo esc_html__( 'close', 'kingcomposer' );?></button><#
		}
		#>{{{title}}}
	</div>
</div>

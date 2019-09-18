<# 
var output 			= '',
	atts 			= ( data.atts !== undefined ) ? data.atts : {},
	wrap_class 		= '',
	css_classes 	= [],
	style 			= ( atts['style'] !== undefined ) ? atts['style'] : '1',
	icon 			= ( atts['icon'] !== undefined ) ? atts['icon'] : '',
	line_text 			= ( atts['line_text'] !== undefined && atts['line_text'] !== '__empty__' ) ? atts['line_text'] : '',
	classes 		= ( atts['class'] !== undefined ) ? atts['class'] : '';

wrap_class = kc.front.el_class( atts );
wrap_class.push('divider_line');
if( classes !== '')
	wrap_class.push( classes );

#>
	<div class="{{{wrap_class.join(' ')}}}">
		<div class="divider_inner divider_line{{{style}}}">
			<#
			switch ( style ) {
				case '2':
					if ( icon !== '' ){
				#>
				<i class="{{{icon}}}"></i>
				<#	}
					break;
				case '3':
					if ( line_text !== '' ){
				#>
					<span class="line_text">{{{line_text}}}</span>
				<# }
					break;
				default:
					// code...
					break;
			}
			#>
		</div>
	</div>

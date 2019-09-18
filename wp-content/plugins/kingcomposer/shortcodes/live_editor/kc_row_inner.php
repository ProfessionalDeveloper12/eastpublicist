<#

var output = '', atts = ( data.atts !== undefined ) ? data.atts : {},
	css_classes = [], attributes = [];


css_classes = kc.front.el_class( atts );
css_classes.push( 'kc_row' );
css_classes.push( 'kc_row_inner' );

if( undefined !== atts['row_class'] && atts['row_class'] !== '' )
	css_classes.push( atts['row_class'] );

if( atts['css'] !== undefined && atts['css'] !== '' )
	css_classes.push( atts['css'].split('|')[0] );
	
if( undefined !== atts['row_id'] && atts['row_id'] !== '' )
	attributes.push( 'id="'+atts['row_id']+'"' );
	
if( atts['video_bg'] !== undefined && atts['video_bg'] === 'yes' ){
	
	var video_bg_url = atts['video_bg_url'];
	
	if( atts['video_bg_url'] !== undefined ){
		css_classes.push('kc-video-bg');
		attributes.push('data-kc-video-bg="'+atts['video_bg_url']+'"');
	
		if( atts['video_options'] !== undefined ){
			attributes.push('data-kc-video-options="'+atts['video_options']+'"');
		}
	}
}
	
attributes.push( 'class="'+css_classes.join(' ')+'"' );
	
	
if( atts['column_align'] === undefined || atts['column_align'] === '' )
	atts['column_align'] = 'center';
	
if( undefined !== atts['equal_height'] && atts['equal_height'] !== '' )
{
	attributes.push( 'data-kc-equalheight="true"' );
	attributes.push( 'data-kc-row-action="true"' );
	attributes.push( 'data-kc-equalheight-align="' + atts['column_align'] + '"' );
}
	

output += '<div '+attributes.join(' ')+'>';

if( undefined !== atts['row_class_container'] && atts['row_class_container'] !== '' )
	output += '<div class="'+atts['row_class_container']+'">';

output += data.content;

if( undefined !== atts['row_class_container'] && atts['row_class_container'] !== '' )
	output += '</div>';

output += '</div>';

#>

{{{output}}}

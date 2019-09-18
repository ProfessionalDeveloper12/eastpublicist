<#

var output = '', attributes = [], col_in_class_container = 'kc_wrapper', 
	classes = [], atts = ( data.atts !== undefined ) ? data.atts : {};

classes = kc.front.el_class( atts );
classes.push( 'kc_column_inner' );

if (undefined !== atts['col_in_class'] && atts['col_in_class'] !== '')
	classes.push( atts['col_in_class'] );

if (undefined !== atts['css'] && typeof atts['css'] == 'string')
	classes .push( atts['css'].split('|')[0] );

if (atts['width'] !== undefined)
	classes.push( kc.front.ui.column.width_class( atts['width'] ) );

if (undefined !== atts['col_in_class_container'] && atts['col_in_class_container'] !== '')
	col_in_class_container += ' '+atts['col_in_class_container'];
	
	
if( atts['col_id'] !== undefined && atts['col_id'] !== '' )
	attributes.push( 'id="'+ atts['col_id'] +'"' );
	
attributes.push( 'class="'+classes.join(' ')+'"' );

if( data.content === undefined )
	data.content = '';
	
data.content += '<div class="kc-element drag-helper" data-model="-1" droppable="true" draggable="true"><a href="javascript:void(0)" class="kc-add-elements-inner"><i class="sl-plus kc-add-elements-inner"></i></a></div>';


#><div {{{attributes.join(' ')}}}>
	<div class="{{col_in_class_container}}">{{{data.content}}}</div>
</div>

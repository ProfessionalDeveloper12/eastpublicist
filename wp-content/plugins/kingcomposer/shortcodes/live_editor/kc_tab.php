<#

var output = '', css_class = [], 
	atts = ( data.atts !== undefined ) ? data.atts : {};


css_class = kc.front.el_class( atts );
css_class.push( 'kc_tab' );
css_class.push( 'ui-tabs-panel' );
css_class.push( 'kc_ui-tabs-hide' );
css_class.push( 'kc_clearfix' );

if ( atts['tab_id'] !== undefined && atts['tab_id'] !== '' ){
	tab_id = kc.tools.esc_slug( atts['title'] );
}else{
	tab_id = atts['tab_id'];
}

if ( atts['class'] !== undefined && atts['class'] !== '' )
	css_class.push( atts['class'] );

if( data.content === undefined )
	data.content = '';
	
data.content += '<div class="kc-element drag-helper" data-model="-1" droppable="true" draggable="true"><a href="javascript:void(0)" class="kc-add-elements-inner"><i class="sl-plus kc-add-elements-inner"></i></a></div>';

#><div id="{{tab_id}}" class="{{{css_class.join(' ')}}}"><div class="kc_tab_content">{{{data.content}}}</div></div>
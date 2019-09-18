<#

if( data === undefined )
	data = {};

var element_attributes = [], title = 'Title', css_class = [], 
	atts = ( data.atts !== undefined ) ? data.atts : {};

css_class = kc.front.el_class( atts );
css_class.push( 'kc_accordion_section' );
css_class.push( 'group' );

if( atts['title'] !== undefined && atts['title'] !== '' )
	title = atts['title'];

if( atts['icon'] !== undefined && atts['icon'] !== '' )
	title = '<i class="'+atts['icon']+'"></i> '+title;
	
if( atts['class'] !== undefined && atts['class'] !== '' )
	css_class.push( atts['class'] );

if( data.content === undefined )
	data.content = '';
data.content += '<div class="kc-element drag-helper" data-model="-1" droppable="true" draggable="true"><a href="javascript:void(0)" class="kc-add-elements-inner"><i class="sl-plus kc-add-elements-inner"></i></a></div>';	

#>
<div class="{{css_class.join(' ')}}">
	<h3 class="kc_accordion_header ui-accordion-header">
		<span class="ui-accordion-header-icon ui-icon"></span>
		<a href="#{{kc.tools.esc_slug(title)}}" data-prevent="scroll">{{{title}}}</a>
	</h3>
	<div class="kc_accordion_content ui-accordion-content kc_clearfix">
		<div class="kc-panel-body">{{{data.content}}}</div>
	</div>
</div>
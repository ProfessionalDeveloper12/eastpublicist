<#
	
	if( data === undefined )
		data = {};
	var attributes = [], classes = [], 
		cont_class = ['kc-row-container'], 
		css_data = '', output = '', 
		atts = ( data.atts !== undefined ) ? data.atts : {};


classes = kc.front.el_class( atts );
classes.push( 'kc_row' );

if( atts['row_class'] !== undefined && atts['row_class'] !== '' )
	classes.push( atts['row_class'] );

if( atts['full_height'] !== undefined && atts['full_height'] !== '' ){
	
	if( atts['content_placement'] !== undefined && atts['content_placement'] == 'middle' )
		attributes.push( 'data-kc-fullheight="middle-content"' );
	else attributes.push( 'data-kc-fullheight="true"' );
	
}
	
if( atts['column_align'] === undefined || atts['column_align'] === '' )
	atts['column_align'] = 'center';
	
if( atts['equal_height'] !== undefined && atts['equal_height'] !== '' ){
	
	attributes.push( 'data-kc-equalheight="true"' );
	attributes.push( 'data-kc-equalheight-align="' + atts['column_align'] + '"' );
}

if( atts['use_container'] !== undefined && atts['use_container'] == 'yes' )
	cont_class.push( 'kc-container' );

if( atts['container_class'] !== undefined && atts['container_class'] !== '' )
	cont_class.push( atts['container_class'] );

if( atts['css'] !== undefined && atts['css'] !== '' )
	classes .push( atts['css'].split('|')[0] );

if( atts['video_bg'] !== undefined && atts['video_bg'] === 'yes' ){
	
	var video_bg_url = atts['video_bg_url'];

	if( atts['video_bg_url'] !== undefined ){
		
		classes.push('kc-video-bg');
		attributes.push('data-kc-video-bg="'+atts['video_bg_url']+'"');
		css_data += 'position: relative;';
	
		if( atts['video_options'] !== undefined ){
			attributes.push('data-kc-video-options="'+atts['video_options']+'"');
		}
		
	}
} 

if( atts['row_id'] !== undefined && atts['row_id'] !== '' )
	attributes.push( 'id="'+atts['row_id']+'"' );


if( atts['force'] !== undefined && atts['force'] == 'yes'  ){
    if( atts['use_container'] !== undefined && atts['use_container'] == 'yes' )
        attributes.push( 'data-kc-fullwidth="row"' );
    else
        attributes.push( 'data-kc-fullwidth="content"' );
}
    
if( atts['video_bg_url'] === undefined || atts['video_bg'] !== 'yes' )
{
	if( atts['parallax'] !== undefined )
	{

		attributes.push( 'data-kc-parallax="true"' );

		if( atts['parallax'] == 'yes-new' )
		{
			var bg_image = '<?php echo admin_url('/admin-ajax.php?action=kc_get_thumbn&size=full&id='); ?>'+atts['parallax_image'];
			css_data += "background-image:url('"+bg_image+"');";
		}
		
	}
}

attributes.push( 'class="'+classes.join(' ')+'"' );

if( css_data !== '' )
	attributes.push( 'style="'+css_data+'"' );

output += '<section '+attributes.join(' ')+'>';

output += '<div class="'+cont_class.join(' ')+'">';

output += '<div class="kc-wrap-columns">'+data.content+'</div>';

output += '</div>';

output += '</section>';

data.callback = function( wrp, $ ){
	kc_front.init( wrp );
}
	
#>

{{{output}}}

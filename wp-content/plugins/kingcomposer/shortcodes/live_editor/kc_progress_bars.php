<#


var output 				= '', 
	element_attributes 	= [], el_classes = [ ], 
	atts 				= ( data.atts !== undefined ) ? data.atts : {},
	radius 				= '',
	speed 				= 2000,
	margin 				= 20,
	progress_bar_color_default = '#999999',
	value_color_style 	= '',
	label_color_style 	= '';


el_classes = kc.front.el_class( atts );
el_classes.push( 'kc_shortcode' );
el_classes.push( 'kc_progress_bars' );


if( atts['wrap_class'] !== undefined && atts['wrap_class'] !== '' )
	el_classes.push( atts['wrap_class'] );	

if( atts['radius'] !== undefined && atts['radius'] !== '' )
	radius = atts['radius'];

if( atts['speed'] !== undefined && atts['speed'] !== '' )
	speed = atts['speed'];


var style = ( atts['style'] !== undefined ) ? atts['style'] : 1;

try{
	var options = JSON.parse( kc.tools.base64.decode( atts['options'] ).toString().replace( /\%SITE\_URL\%/g, kc_site_url ) );
}catch(ex){
	var options = atts['options'];
}
	
element_attributes.push( 'class="'+el_classes.join(' ')+'"' );
element_attributes.push( 'data-style="'+style+'"' );

output += '<div ' + element_attributes.join(' ') + '>';

if( atts['title'] !== undefined && atts['title'] !== '' )
	output += '<h3>'+atts['title']+'</h3>';

if( options !== null ){
	for( var n in options ){

		var value = ( options[n]['value'] !== undefined && options[n]['value'] !== '' ) ? options[n]['value'] : 50,
			label = ( options[n]['label'] !== undefined && options[n]['label'] !== '' ) ? options[n]['label'] : 'Label default',
			prob_color = ( options[n]['prob_color'] !== undefined && options[n]['prob_color'] !== '' ) ? options[n]['prob_color'] : '',
			prob_style = '',
			value_color_style = '';


		if( prob_color != '')
		{
			prob_style += 'background-color: '+prob_color+';';
		}

		prob_style += 'width: '+atts['progress_animate']+'%';
		
		if( options[n]['value_color'] !== undefined && options[n]['value_color'] !== '' ){
			value_color_style = ' style="color: '+options[n]['value_color']+'"';
		}

		prob_track_attributes = [];
		prob_attributes = [];

		//Progress bars track attributes
		prob_track_css_classes = [
			'kc-ui-progress-bar',
			'kc-ui-progress-bar'+style,
			'kc-progress-bar',
			'kc-ui-container',
		];
		
		if( radius == 'yes' )
			prob_track_css_classes.push( 'kc-progress-radius' );
		
		prob_track_attributes.push( 'class="'+prob_track_css_classes.join(' ')+'"' );

		//Progress bars attributes
		prob_css_classes = [ 'kc-ui-progress', 'kc-ui-progress'+style ];

		var prob_css_class = prob_css_classes.join(' ');
		prob_attributes.push( 'class="'+prob_css_class+'"' );
		prob_attributes.push( 'style="'+prob_style+'"' );

		output +='<div class="progress-item">';

		output += '<span class="label">'+label+'</span>';
		output += '<div '+prob_track_attributes.join(' ')+'>';
			output += '<div '+prob_attributes.join(' ')+' data-value="'+value+'" data-speed="'+speed+'">';
				output += '<div class="ui-label"><span class="value" '+value_color_style+'>'+value+'%</span></div>';
			output += '</div>';
		output += '</div>';

		output += '</div>';

	}
}

output += '</div>';

data.callback = function( wrp, $ ){
	kc_front.refresh( wrp );
} 

#>

{{{output}}}

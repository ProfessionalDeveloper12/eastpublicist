<#

if( data === undefined )
	data = {};

var atts 				= ( data.atts !== undefined ) ? data.atts : {},
	text_align 			= 'center',
	image_size 			= 'full',
	back_data 			= '',
	front_data 			= '',
	show_icon 			= ( atts['show_icon'] !== undefined )? atts['show_icon'] : '',
	icon 				= ( atts['icon'] !== undefined )? atts['icon'] : 'sl-rocket',
	title 				= ( atts['title'] !== undefined )? atts['title'] : '',
	description 		= ( atts['description'] !== undefined )? kc.tools.base64.decode( atts['description'] ) : '',
	show_button 		= ( atts['show_button'] !== undefined )? atts['show_button'] : '',
	text_on_button 		= ( atts['text_on_button'] !== undefined )? atts['text_on_button'] : '',
	direction 			= ( atts['direction'] !== undefined )? atts['direction'] : '',
	
	wrap_class 			= ( atts['wrap_class'] !== undefined )? atts['wrap_class'] : '',
	
	b_show_icon 			= ( atts['b_show_icon'] !== undefined )? atts['b_show_icon'] : '',
	b_icon 			= ( atts['b_icon'] !== undefined )? atts['b_icon'] : '',
	b_title 			= ( atts['b_title'] !== undefined )? atts['b_title'] : '',
	b_description 			= ( atts['b_description'] !== undefined )? kc.tools.base64.decode( atts['b_description'] ) : '',
	b_show_button 			= ( atts['b_show_button'] !== undefined )? atts['b_show_button'] : '',
	b_text_on_button 			= ( atts['b_text_on_button'] !== undefined )? atts['b_text_on_button'] : '',
	b_link 			= ( atts['b_link'] !== undefined )? atts['b_link'] : '',
	btn_title       = '',
	btn_target       = '',
	but_link_text       = '',
	button_link       = '',
	image_data 			= kc_url + '/assets/images/get_start.jpg',
	element_atttribute 	= [],
	el_classess 		= [];

el_classess = kc.front.el_class( atts );

el_classess.push( 'kc-flipbox' );
el_classess.push( 'kc-flip-container' );


if ( wrap_class !== '' )
	el_classess.push( wrap_class );

if( direction !== '' && direction === 'vertical' )
	el_classess.push( 'flip-' + direction );

element_atttribute.push( 'class="' + el_classess.join(' ') + '"' );
element_atttribute.push( 'ontouchstart="this.classList.toggle(\'hover\');"' );

// Front Side Data
if( show_icon === 'yes' && icon !=='' )
	front_data += '<div class="wrap-icon"><i class="' + icon + '"></i></div>';

if( title !== '' )
	front_data += '<h3>' + title + '</h3>';

if( description !== '' )
	front_data += '<p>' + kc.front.do_shortcode( description ) + '</p>';

// Back Side Data
if( b_show_icon == 'yes' && b_icon !== '' )
	back_data += '<div class="wrap-icon"><i class="' + b_icon + '"></i></div>';

if( b_title !== '')
	back_data += '<h3>' + b_title + '</h3>';

if( b_description !== '' )
	back_data += '<p>' +  kc.front.do_shortcode( b_description ) + '</p>';

if( b_show_button == 'yes' ){
	
	if ( b_text_on_button == '' )
		b_text_on_button = "<?php echo __( 'Read more', 'kingcomposer' );?>";

	if ( b_link !== '' ) {
		but_link_text = b_link.split( '|' );
		button_link = but_link_text[0];
	}

	if( but_link_text[1] !== undefined )
		btn_title =  but_link_text[1];
	
	if( but_link_text[2] !== undefined )
		btn_target =  but_link_text[2];
	
	back_data += '<a class="button" href="' + button_link + '  title="' + btn_title + '" target="' + btn_target + '">' + b_text_on_button + '</a>';
	
}

#>

<div {{{element_atttribute.join(' ')}}}>
	<div class="flipper">
		<div class="front">
			<div class="front-content">
				{{{front_data}}}
			</div>
		</div>
		<div class="back">
			<div class="des">
				{{{back_data}}}
			</div>
		</div>
	</div>
</div>

<# var output 			= '',
	atts 				= ( data.atts !== undefined ) ? data.atts : {},
	template 			= '',
	custom_css 			= '',
	el_classes 			= ['kc-countdown-timer'],
	wpelm_class 			= [],
	output 				= '', 
	element_attribute 	= [],
	countdown_data 		= [],
	timer_style 		= ( atts['timer_style'] !== undefined ) ? atts['timer_style'] : '1',
	custom_template 	= ( atts['custom_template'] !== undefined ) ? atts['custom_template'] : '',
	wrap_class 			= ( atts['wrap_class'] !== undefined ) ? atts['wrap_class'] : '',
	title 				= ( atts['title'] !== undefined ) ? atts['title'] : '',
    today = new Date(),
	datetime 			= ( atts['datetime'] !== undefined && atts['datetime'] !== '__empty__' ) ? atts['datetime'] : '';
    if( datetime == ''){
        today.setTime(today.getTime() + 7 * 86400000);
        datetime = today.toDateString();
    }

wpelm_class = kc.front.el_class( atts );

el_classes.push( wrap_class );

switch ( timer_style ) {
	case '1':
	case '2':
		template += '<span class="countdown-style' + timer_style + '">';
		template += '	<span class="group">';
		template += '		<span class="timer days">%D</span>';
		template += '		<span class="unit">days</span>';
		template += '	</span>';
		template += '	<span class="group">';
		template += '		<span class="timer seconds">%H</span>';
		template += '		<span class="unit">hours</span>';
		template += '	</span>';
		template += '	<span class="group">';
		template += '		<span class="timer seconds">%M</span>';
		template += '		<span class="unit">minutes</span>';
		template += '	</span>';
		template += '	<span class="group">';
		template += '		<span class="timer seconds">%S</span>';
		template += '		<span class="unit">seconds</span>';
		template += '	</span>';
		template += '</span>';
	
	break;

	case '3':
	
		if( custom_template !== '' ){
			template = kc.tools.base64.decode(custom_template);
		} else {
			template = '%D days %H:%M:%S';
		}

	break;
}

var d = new Date(datetime);
datetime = d.getFullYear() + "/" + (d.getMonth() + 1) + "/" + d.getDate();


countdown_data = {
	'date' : datetime,
	'template' : template.replace('/\s\s+/', ' ').trim()
};

element_attribute.push('class="' + el_classes.join(' ') + '"');
element_attribute.push("data-countdown='" + JSON.stringify( countdown_data ) + "'");

#>
<div class="{{{wpelm_class.join(' ')}}}">
	<#
	if( title !== '' ){
	#><h3>{{{title}}}</h3><#
	}
	#>
	<div {{{element_attribute.join(' ')}}}></div>
</div>
<#
	data.callback = function( wrp, $){
		kc_front.countdown_timer();
	}
#>

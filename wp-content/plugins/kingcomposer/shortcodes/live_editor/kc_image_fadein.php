<# 
var output 		= '',
	classes 	= ['image_fadein'],
    css_class 	= [],
	attributes 	= [],
	_images 	= [],
	atts 		= ( data.atts !== undefined ) ? data.atts : {},
	title 		= ( atts['title'] !== undefined ) ? atts['title'] : '',
	images 		= ( atts['images'] !== undefined ) ? atts['images'] : '',
	transition 	= ( atts['images'] !== undefined ) ? atts['transition'] : '',
	delay 		= ( atts['delay'] !== undefined ) ? atts['delay'] : '',
	force_size 	= ( atts['force_size'] !== undefined ) ? atts['force_size'] : '',
	width 		= ( atts['width'] !== undefined ) ? atts['width'] : '',
	height 		= ( atts['height'] !== undefined ) ? atts['height'] : '',
	position 	= ( atts['position'] !== undefined ) ? atts['position'] : '',
	wrap_class 	= ( atts['wrap_class'] !== undefined ) ? atts['wrap_class'] : '';


    css_class = kc.front.el_class( atts );

wrap_class 		= wrap_class + ' ' + css_class.join( ' ' );

if( images === '' )
	images = [];
else
	images = images.split(',');

for( var i =0; i < images.length; i++) {

	image_id 	= images[i];
	image 		= ajaxurl + "?action=kc_get_thumbn&size=full&id=" + image_id;

	if( image !== undefined && image !== '' ){

		if( force_size !== undefined && force_size == 'yes' ){

			var att = ['250', '250', 'c'], image_size = '';

			if( width !== '' )
				att[0] = width;

			if( height !== '' )
				att[1] = height;

			if( position !== '' )
				att[2] = position;

			image_size = att.join('x');
			_images.push( ajaxurl +  "?action=kc_get_thumbn_size&id="+image_id+'&size='+image_size);

		}
		else _images.push( image );

	}

}


if( transition !== '' )
	classes.push( transition );

if( delay !== '' )
	attributes.push( 'data-delay="' + delay + '"');

attributes.push( 'class="' + classes.join(' ') + '"');
attributes.push( 'data-images="' + images + '"');
#>

<div class="image_fadein_slider {{{wrap_class}}}">
	<#
		if( title !== '' ){
		#>
		<h3>{{{title}}}</h3>
		<#
		}
	#>
	<div {{{attributes.join(' ')}}}>
	<#

		if( _images.length == 0 ){

		#>
			<h3><?php echo __('Images Fadein Gallery: No images found', 'kingcomposer' );?></h3>
		<#
		
		}else{

			for( var i =0; i < _images.length; i++ ){
				image = _images[i];
			#>
			 <img src="{{{image}}}" />
			<#
			}

		}
	#>
	</div>
</div>
<#
	data.callback = function( wrp, $){
		kc_front.image_fade();
	}
#>

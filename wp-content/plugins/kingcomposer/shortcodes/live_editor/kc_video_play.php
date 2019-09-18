<#

if( data === undefined )
	data = {};

var atts 			= ( data.atts !== undefined ) ? data.atts : {},
	video_height 	= 250,
	video_width 	= '100%',
	video_info 		= '',
	el_classess 	= [];

el_classess 		= kc.front.el_class( atts );

el_classess.push( 'kc_shortcode' );
el_classess.push( 'kc_video_play' );
el_classess.push( 'kc_video_wrapper' );

if( atts['wrap_class'] !== undefined && atts['wrap_class'] !== '' )
	el_classess.push( atts['wrap_class'] );

if( atts['video_height'] !== undefined && atts['video_height'] !== '' )
	video_height = parseInt( atts['video_height'] );

if( atts['video_width'] !== undefined && atts['video_width'] !== '' )
	video_width = parseInt( atts['video_width'] )+'px';

if( atts['title'] !== undefined && atts['title'] !== '' )
	video_info += '<h3>'+atts['title']+'</h3>';

if( atts['description'] !== undefined && atts['description'] !== '' )
	video_info += '<p>'+kc.tools.base64.decode( atts['description'] )+'</p>';

#>
<div class="{{{el_classess.join(' ')}}}">
	<div style="height:{{video_height}}px;width:{{video_width}};" class="disable-view-element">
		<h3>For best perfomance, the video player has been disabled in this editing mode.</h3>
	</div>
	<div class="video-info">{{{video_info}}}</div>
</div>

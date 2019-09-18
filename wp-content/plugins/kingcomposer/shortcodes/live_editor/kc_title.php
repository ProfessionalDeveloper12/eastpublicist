<#
var output 		= '',
	wrap_class 	= [],
	classes 	= ['kc_title'],
	wrp_class 	= [],
	type 		= 'h1',
	atts 		= ( data.atts !== undefined ) ? data.atts : {},
	text		= ( atts['text'] !== undefined )? kc.tools.base64.decode( atts['text'] ) : '',
	post_title	= ( atts['post_title'] !== undefined )? atts['post_title'] : 'no',
	after		= ( atts['after'] !== undefined )? kc.tools.base64.decode( atts['after'] ) : '',
	before		= ( atts['before'] !== undefined )? kc.tools.base64.decode( atts['before'] ) : '';

wrap_class  = kc.front.el_class( atts );
wrap_class.push( 'kc-title-wrap' );

if( atts['class'] !== undefined && atts['class'] !== '' )
	classes.push( atts['class'] );

if( atts['type'] !== undefined && atts['type'] !== '' )
	type = atts['type'];

if ( atts['title_wrap'] == 'yes' && atts['title_wrap_class'] !== undefined )
	wrap_class.push(atts['title_wrap_class']);
	
if ( post_title === 'yes')
	text = kc_post_title;

	
#>
<div class="{{{wrap_class.join(' ')}}}">
	<# if ( atts['title_wrap'] == 'yes' ){ #>

		<# if ( atts['before'] !== undefined ){ #>{{{before}}}<# } #>

		<{{{type}}} class="{{{classes.join(' ')}}}">{{{text}}}</{{{type}}}>

		<# if ( atts['after'] !== undefined ){#>{{{after}}}<#}#>

	<# } else{ #>

		<{{{type}}} class="{{{classes.join(' ')}}}">{{{text}}}</{{{type}}}>

	<# } #>
</div>

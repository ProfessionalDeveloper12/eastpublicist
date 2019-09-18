<?php
if(!defined('ABSPATH')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

// Add King Composer specific CSS class by filter
add_filter( 'body_class', 'kc_add_body_class' );
function kc_add_body_class( $classes ) {
	
	global $post;
	
	if ( isset($post) && has_shortcode( $post->post_content, 'kc_row' ) ){
		$classes[] = 'kingcomposer';
	}

	$classes[] = 'kc-css-system';
	
	return $classes;
}

//define var kc_script_data
function kc_header_js_var(){
	echo '<script type="text/javascript">var kc_script_data={ajax_url:"'. admin_url( 'admin-ajax.php' ) .'"}</script>';
}
add_action('wp_head', 'kc_header_js_var');

//Convert col decimal format to class
function kc_column_width_class( $width ) {
	
	if( empty( $width ) )
		return 'kc_col-sm-12';
		
	if( strpos( $width, '%' ) !== false ){
		$width = (float)$width;
		if( $width < 12 )
			return 'kc_col-sm-1';
		else if( $width < 18 )
			return 'kc_col-sm-2';
		else if( $width < 22.5 )
			return 'kc_col-of-5';
		else if( $width < 29.5 )
			return 'kc_col-sm-3';
		else if( $width < 37 )
			return 'kc_col-sm-4';
		else if( $width < 46 )
			return 'kc_col-sm-5';
		else if( $width < 54.5 )
			return 'kc_col-sm-6';
		else if( $width < 63 )
			return 'kc_col-sm-7';
		else if( $width < 71.5 )
			return 'kc_col-sm-8';
		else if( $width < 79.5 )
			return 'kc_col-sm-9';
		else if( $width < 87.5 )
			return 'kc_col-sm-10';
		else if( $width < 95.5 )
			return 'kc_col-sm-11';
		else return 'kc_col-sm-12';
	}
	
	$matches = explode( '/', $width ); $width_class = ''; $n = 12; $m = 12;

	if( isset( $matches[0] ) && !empty( $matches[0] ) )
		$n = $matches[0];
	if( isset( $matches[1] ) && !empty( $matches[1] ) )
		$m = $matches[1];

	if( $n == 2.4){
		$width_class = 'kc_col-of-5';
	}else{
		if ( $n > 0 && $m > 0 ) {
			$value = ceil( ($n / $m) * 12 );
			if ( $value > 0 && $value <= 12 ) {
				$width_class = 'kc_col-sm-'. $value;
			}
		}
	}

	return $width_class;
}

//Return file assets url
function kc_asset_url($file){
	$file = KC_URL.'/assets/'.$file;
	return esc_url($file);
}

//Check external link
function kc_check_image_external_link($external_link){
	if (@GetImageSize($external_link)) {
		return true;
	} else {
		return false;
	}
}

/*
 * Validate Color to RGBA
 * Takes the user's input color value and returns it only if it's a valid color.
 */
function kc_validate_color_rgba($color) {
	if ($color == "transparent") {
		return $color;
	}
	$color = str_replace('#','', $color);
	if (strlen($color) == 3) {
		$color = $color.$color;
	}
	if (preg_match('/^[a-f0-9]{6}$/i', $color)) {
		$color = '#' . $color;
	}

	return array('hex'=>$color, 'rgba'=> kc_hex2rgba($color));
}

/*
 * Takes the color hex value and converts to a rgba.
 */
function kc_hex2rgba($color, $opacity = false) {

	$default = 'rgb(0,0,0)';

	//Return default if no color provided
	if(empty($color))
          return $default;

	//Sanitize $color if "#" is provided
        if ($color[0] == '#' ) {
        	$color = substr( $color, 1 );
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
                return $default;
        }

        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if($opacity){
        	if(abs($opacity) > 1)
        		$opacity = 1.0;
        	$output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
        	$output = 'rgb('.implode(",",$rgb).')';
        }

        //Return rgb(a) color string
        return $output;
}


function kc_parse_link( $link, $default = array( 'url' => '', 'title' => '', 'target' => '' ) ){

	$result = $default;
	$params_link = explode('|', $link);

	if( !empty($params_link) ){
		$result['url'] = rawurldecode(isset($params_link[0])?$params_link[0]:'#');
		$result['title'] = isset($params_link[1])?$params_link[1]:'';
		$result['target'] = isset($params_link[2])?$params_link[2]:'';
	}

	return $result;
}


function kc_basic_layout_css(){
	
	return '*{-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box}div{display: block}.kc-container{width:100%;max-width:1170px;margin:0 auto;padding-left:15px;padding-right:15px;box-sizing:border-box}.kc-row-container:not(.kc-container){padding-left:0;padding-right:0;width:100%;max-width:100%}.kc-elm{float: left;width: 100%;}.kc_wrap-video-bg{height:100%;left:0;overflow:hidden;pointer-events:none;position:absolute;top:0;width:100%;z-index:0}.kc_single_image img{max-width:100%}.kc-video-bg .kc_column{position:relative}.kc-infinite-loop{text-align:center;padding:50px;font-size:18px;color:red;width:100%;display:inline-block}.kc_row:not(.kc_row_inner){clear:both;display:block;width:100%}.kc-wrap-columns,.kc_row_inner{clear:both}.kc_row.kc_row_inner{width: calc(100% + 30px);}.kc_tab_content>.kc_row_inner{width:100%;margin:0}.kc_column,.kc_column_inner{min-height:1px;position:relative;padding-right:15px;padding-left:15px;width:100%;float:left}div.kc_column,div.kc_column_inner{clear:none}div[data-kc-fullheight]{min-height:100vh}html body div[data-kc-parallax=true]{background-position:50% 0;background-size:100%!important;background-repeat:no-repeat!important;background-attachment:fixed!important}div[data-kc-fullwidth]{margin-left:0!important;margin-right:0!important;position:relative;box-sizing:content-box}.kc_text_block{display:inline-block;clear:both;width:100%}@media screen and (min-width:999px){body div[data-kc-equalheight=true],body div[data-kc-equalheight=true]>.kc-container{display:-webkit-flex!important;display:-ms-flexbox!important;display:flex!important}body div[data-kc-equalheight-align=middle]>.kc-container>.kc-wrap-columns>.kc_column>.kc-col-container{display:-webkit-flex!important;display:-ms-flexbox!important;display:flex!important;align-items:center;flex-wrap:wrap;justify-content:center;height:100%}body div[data-kc-equalheight-align=bottom]>.kc-container>.kc-wrap-columns>.kc_column>.kc-col-container{display:-webkit-flex!important;display:-ms-flexbox!important;display:flex!important;align-items:flex-end;flex-wrap:wrap;justify-content:center;height:100%}body div[data-kc-fullheight=middle-content],body div[data-kc-fullheight=middle-content]>.kc-container{display:-webkit-flex;display:-ms-flexbox;display:flex;align-items:center}.kc-wrap-columns,.kc_row_inner{display:-webkit-flex;display:-ms-flexbox;display:flex}.kc_row_inner, .kc-row-container.kc-container .kc-wrap-columns{width: calc(100% + 30px)}}@media screen and (max-width: 767px){body.kc-css-system .kc_column,body.kc-css-system .kc_column_inner{width: 100%}div.kc_row{display: block}}@media screen and (max-width: 999px){.kc_col-sm-3, div.kc_col-of-5{width: 50%}}.kc_col-sm-1{width: 8.33333%}.kc_col-sm-2{width: 16.6667%}div.kc_col-of-5{width: 20%;float: left}.kc_col-sm-3{width: 25%}.kc_col-sm-4{width: 33.3333%}.kc_col-sm-5{width: 41.6667%}.kc_col-sm-6{width: 50%}.kc_col-sm-7{width: 58.3333%}.kc_col-sm-8{width: 66.6667%}.kc_col-sm-9{width: 75%}.kc_col-sm-10{width: 83.3333%}.kc_col-sm-11{width: 91.6667%}.kc_col-sm-12{width: 100%}.kc-off-notice{display:none;}';
	
}

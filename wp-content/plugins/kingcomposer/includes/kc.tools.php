<?php


class kc_tools {

	public static function get_css( $value = array() ) {

		$css = $prefix = '';

		if ( ! empty( $value ) && is_array( $value ) ) {
			foreach($value as $class => $style){
				$css .= $class.'{';
				foreach ( $style as $key => $value ) {
					if ( $key == "background-image" ) {
						$css .= $key . ":url('" . $value . "');";
					} else {
						$css .= $key . ":" . $value . ";";
					}
				}
				$css .= '}'."\n";
			}
		}

		return $css;
	}

	public static function get_list_menu(){

		global $post;

		$menu_options = array();

	    $menus = get_terms('nav_menu');
	    foreach($menus as $menu) {
			$menu_options[ $menu->slug ] = $menu->name;
	    }

		return $menu_options;
	}

	public static function select( $args ) {

		$args = wp_parse_args( $args, array(
			'id'       => '',
			'name'     => '',
			'class'    => '',
			'multiple' => '',
			'size'     => '',
			'disabled' => '',
			'selected' => '',
			'none'     => '',
			'options'  => array(),
			'style' => '',
			'format'   => 'keyval', // keyval/idtext
			'noselect' => '' // return options without <select> tag
		) );
		$options = array();
		if ( !is_array( $args['options'] ) ) $args['options'] = array();
		if ( $args['id'] ) $args['id'] = ' id="' . $args['id'] . '"';
		if ( $args['name'] ) $args['name'] = ' name="' . $args['name'] . '"';
		if ( $args['class'] ) $args['class'] = ' class="' . $args['class'] . '"';
		if ( $args['style'] ) $args['style'] = ' style="' . esc_attr( $args['style'] ) . '"';
		if ( $args['multiple'] ) $args['multiple'] = ' multiple="multiple"';
		if ( $args['disabled'] ) $args['disabled'] = ' disabled="disabled"';
		if ( $args['size'] ) $args['size'] = ' size="' . $args['size'] . '"';
		if ( $args['none'] && $args['format'] === 'keyval' ) $args['options'][0] = $args['none'];
		if ( $args['none'] && $args['format'] === 'idtext' ) array_unshift( $args['options'], array( 'id' => '0', 'text' => $args['none'] ) );

		if ( $args['format'] === 'keyval' ) foreach ( $args['options'] as $id => $text ) {
			$options[] = '<option value="' . (string) $id . '">' . (string) $text . '</option>';
		} elseif ( $args['format'] === 'idtext' ) foreach ( $args['options'] as $option ) {
			if ( isset( $option['id'] ) && isset( $option['text'] ) )
				$options[] = '<option value="' . (string) $option['id'] . '">' . (string) $option['text'] . '</option>';
		}
		$options = implode( '', $options );
		$options = str_replace( 'value="' . $args['selected'] . '"', 'value="' . $args['selected'] . '" selected="selected"', $options );
		return ( $args['noselect'] ) ? $options : '<select' . $args['id'] . $args['name'] . $args['class'] . $args['multiple'] . $args['size'] . $args['disabled'] . $args['style'] . '>' . $options . '</select>';
	}

	public static function get_categories() {
		$cats = array();
		foreach ( (array) get_terms( 'category', array( 'hide_empty' => false ) ) as $cat ) $cats[$cat->slug] = $cat->name;
		return $cats;
	}

	public static function get_types() {

		$types = array();
		foreach ( (array) get_post_types( '', 'objects' ) as $cpt => $cpt_data ) $types[$cpt] = $cpt_data->label;

		return $types;

	}

	public static function get_users() {

		$users = get_users();
		// Cache results
		set_transient( 'sc/users_cache', $users );
		// Prepare data array
		$data = array();
		// Loop through users
		foreach ( $users as $user ) $data[$user->data->ID] = $user->data->display_name;
		// Return data
		return $data;
	}

	public static function get_taxonomies() {

		$taxes = array();
		foreach ( (array) get_taxonomies( '', 'objects' ) as $tax ) $taxes[$tax->name] = $tax->label;

		return $taxes;

	}

	public static function get_terms( $tax = 'category', $key = 'id', $type = '', $default = '' ) {

		$get_terms = (array) get_terms( $tax, array( 'hide_empty' => false ) );

		if( $type != '' ){
			$get_terms = self::get_terms_by_post_type( array($tax), array($type) );
		}

		$terms = array();

		if( $default != '' ){
			$terms[] = $default;
		}

		if ( $key == 'id' ){
			foreach ( $get_terms as $term ){
				if( isset( $term->term_id ) && isset( $term->name ) ){
					$terms[$term->term_id] = $term->name;
				}
			}
		}else if ( $key == 'slug' ){
			foreach ( $get_terms as $term ){
				if( !empty($term->name) ){
					if( isset( $term->slug ) && isset( $term->name ) ){
						$terms[$term->slug] = $term->name;
					}
				}
			}
		}

		return $terms;

	}

	public static function get_terms_by_post_type( $taxonomies, $post_types ) {

		global $wpdb;

		$query = $wpdb->prepare(
			"SELECT t.*, COUNT(*) from $wpdb->terms AS t
			INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
			INNER JOIN $wpdb->term_relationships AS r ON r.term_taxonomy_id = tt.term_taxonomy_id
			INNER JOIN $wpdb->posts AS p ON p.ID = r.object_id
			WHERE p.post_type IN('%s') AND tt.taxonomy IN('%s')
			GROUP BY t.term_id",
			join( "', '", $post_types ),
			join( "', '", $taxonomies )
		);

		$_terms = $wpdb->get_results( $query );

		return $_terms;

	}

	public static function get_featured_image( $post, $thumbnail = 'single-post-thumbnail' , $first = true ) {

		$featured = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $thumbnail );

		if( empty($featured) )
		{
			if( $first == true )return self::get_first_image( $post->post_content, $post->ID );
			else return $kc->default_image();
		}
		return $featured[0];

	}

	public static function images_attached( $id ){

		$args = array(
			'post_type'   => 'attachment',
			'numberposts' => -1,
			'post_status' => null,
			'post_parent' => $id,
			'exclude'     => get_post_thumbnail_id()
			);

		$attachments = get_posts( $args );
		$output = array();
		if ( $attachments ) {
			foreach ( $attachments as $attachment ) {
				$att = wp_get_attachment_image_src($attachment->ID);
				if(!empty($att))array_push( $output, $att );
			}
		}

		return $output;

	}

	public static function get_first_image( $content, $id = null ) {
		global $kc;
		$first_img = self::get_first_video( $content );

		if( $first_img != null ){
			if( strpos( $first_img, 'youtube' ) !== false )return $first_img;
		}

		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
		if( !empty($matches [1]) )
			if( !empty($matches [1][0]) )
				$first_img = $matches [1] [0];

		if(empty($first_img)){

			if($id != null)$first = self::images_attached( $id );

			if( !empty( $first[0] ) )
				return $first[0][0];

			else $first_img = $kc->default_image();
		}

		return $first_img;

	}

	public static function get_first_video( $content ) {

		$first_video = null;
		$output = preg_match_all('/<ifr'.'ame.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
		if( !empty($matches [1]) ){
			if( !empty($matches [1][0]) ){
				$first_video = $matches [1] [0];
			}
		}

		return 	$first_video;

	}

	public static function createImageSize( $source, $attr ){

		if (strpos($source, KC_SITE) === false || $source == KC_URL.'/assets/images/get_start.jpg')
			return $source;

		$attr = explode( 'x', $attr ); $arg = array();

		if ( !empty( $attr[2] ) ) {

			$arg['w'] = $attr[0];
			$arg['h'] = $attr[1];
			$arg['a'] = $attr[2];
			if( $attr[2] != 'c' ){
				$arg['a'] = $attr[2];
				$attr = '-'.implode('x',$attr);
			}else{
				$attr = '-'.$attr[0].'x'.$attr[1].'xc';
			}

		}else if( !empty( $attr[0] ) && !empty( $attr[1] ) ){
			$arg['w'] = $attr[0];
			$arg['h'] = $attr[1];
			$attr = '-'.$attr[0].'x'.$attr[1].'xc';
		}else{
			return $source;
		}

		$source = strrev( $source );
		$st = strpos( $source, '.');

		if( strpos( $source, strrev( 'images/default.jpg' ) ) === 0 ){
			return strrev( $source );
		}else if( $st === false ){
			return strrev( $source ).$attr;
		}else{

			$file = str_replace( array( untrailingslashit( site_url() ).'/', '\\', '/' ), array( ABSPATH, KDS, KDS ), strrev( $source ) );

			$_return = strrev( substr( $source, 0, $st+1 ).strrev($attr).substr( $source, $st+1 ) );
			$__return = str_replace( array( untrailingslashit( site_url() ).'/', '\\', '/' ), array( ABSPATH, KDS, KDS ), $_return );

			if( file_exists( $file ) && !file_exists( $__return ) ){
				ob_start();
				self::processImage( $file, $arg, $__return );
				ob_end_clean();
			}

			return $_return;

		}
	}

	public static function processImage( $localImage, $params = array(), $tempfile ){

		$sData = getimagesize($localImage);
		$origType = $sData[2];
		$mimeType = $sData['mime'];

		if(! preg_match('/^image\/(?:gif|jpg|jpeg|png)$/i', $mimeType)){
			return "The image being resized is not a valid gif, jpg or png.";
		}

		if (!function_exists ('imagecreatetruecolor')) {
		    return 'GD Library Error: imagecreatetruecolor does not exist - please contact your webhost and ask them to install the GD library';
		}

		if (function_exists ('imagefilter') && defined ('IMG_FILTER_NEGATE')) {
			$imageFilters = array (
				1 => array (IMG_FILTER_NEGATE, 0),
				2 => array (IMG_FILTER_GRAYSCALE, 0),
				3 => array (IMG_FILTER_BRIGHTNESS, 1),
				4 => array (IMG_FILTER_CONTRAST, 1),
				5 => array (IMG_FILTER_COLORIZE, 4),
				6 => array (IMG_FILTER_EDGEDETECT, 0),
				7 => array (IMG_FILTER_EMBOSS, 0),
				8 => array (IMG_FILTER_GAUSSIAN_BLUR, 0),
				9 => array (IMG_FILTER_SELECTIVE_BLUR, 0),
				10 => array (IMG_FILTER_MEAN_REMOVAL, 0),
				11 => array (IMG_FILTER_SMOOTH, 0),
			);
		}

		// get standard input properties
		$new_width =  (int) abs ($params['w']);
		$new_height = (int) abs ($params['h']);
		$zoom_crop = !empty( $params['zc'] )?(int) $params['zc']:1;
		$quality =  !empty( $params['q'] )?(int) $params['q']:100;
		$align = !empty( $params['a'] )? $params['a']: 'c';
		$filters = !empty( $params['f'] )? $params['f']: '';
		$sharpen = !empty( $params['s'] )? (bool)$params['s']: 0;
		$canvas_color = !empty( $params['cc'] )? $params['cc']: 'ffffff';
		$canvas_trans = !empty( $params['ct'] )? (bool)$params['ct']: 1;

		// set default width and height if neither are set already
		if ($new_width == 0 && $new_height == 0) {
		    $new_width = 100;
		    $new_height = 100;
		}

		// ensure size limits can not be abused
		$new_width = min ($new_width, 1500);
		$new_height = min ($new_height, 1500);

		// set memory limit to be able to have enough space to resize larger images
		ini_set('memory_limit', '300M');

		// open the existing image
		switch ($mimeType) {
			case 'image/jpeg':
				$image = imagecreatefromjpeg ($localImage);
				break;

			case 'image/png':
				$image = imagecreatefrompng ($localImage);
				break;

			case 'image/gif':
				$image = imagecreatefromgif ($localImage);
				break;

			default: $image = false; break;

		}

		if ($image === false) {
			return 'Unable to open image.';
		}

		// Get original width and height
		$width = imagesx ($image);
		$height = imagesy ($image);
		$origin_x = 0;
		$origin_y = 0;

		// generate new w/h if not provided
		if ($new_width && !$new_height) {
			$new_height = floor ($height * ($new_width / $width));
		} else if ($new_height && !$new_width) {
			$new_width = floor ($width * ($new_height / $height));
		}

		// scale down and add borders
		if ($zoom_crop == 3) {

			$final_height = $height * ($new_width / $width);

			if ($final_height > $new_height) {
				$new_width = $width * ($new_height / $height);
			} else {
				$new_height = $final_height;
			}

		}

		// create a new true color image
		$canvas = imagecreatetruecolor ($new_width, $new_height);
		imagealphablending ($canvas, false);

		if (strlen($canvas_color) == 3) { //if is 3-char notation, edit string into 6-char notation
			$canvas_color =  str_repeat(substr($canvas_color, 0, 1), 2) . str_repeat(substr($canvas_color, 1, 1), 2) . str_repeat(substr($canvas_color, 2, 1), 2);
		} else if (strlen($canvas_color) != 6) {
			$canvas_color = 'ffffff'; // on error return default canvas color
 		}

		$canvas_color_R = hexdec (substr ($canvas_color, 0, 2));
		$canvas_color_G = hexdec (substr ($canvas_color, 2, 2));
		$canvas_color_B = hexdec (substr ($canvas_color, 4, 2));

		// Create a new transparent color for image
	    // If is a png and PNG_IS_TRANSPARENT is false then remove the alpha transparency
		// (and if is set a canvas color show it in the background)
		if(preg_match('/^image\/png$/i', $mimeType) && $canvas_trans){
			$color = imagecolorallocatealpha ($canvas, $canvas_color_R, $canvas_color_G, $canvas_color_B, 127);
		}else{
			$color = imagecolorallocatealpha ($canvas, $canvas_color_R, $canvas_color_G, $canvas_color_B, 0);
		}


		// Completely fill the background of the new image with allocated color.
		imagefill ($canvas, 0, 0, $color);

		// scale down and add borders
		if ($zoom_crop == 2) {

			$final_height = $height * ($new_width / $width);

			if ($final_height > $new_height) {

				$origin_x = $new_width / 2;
				$new_width = $width * ($new_height / $height);
				$origin_x = round ($origin_x - ($new_width / 2));

			} else {

				$origin_y = $new_height / 2;
				$new_height = $final_height;
				$origin_y = round ($origin_y - ($new_height / 2));

			}

		}

		// Restore transparency blending
		imagesavealpha ($canvas, true);

		if ($zoom_crop > 0) {

			$src_x = $src_y = 0;
			$src_w = $width;
			$src_h = $height;

			$cmp_x = $width / $new_width;
			$cmp_y = $height / $new_height;

			// calculate x or y coordinate and width or height of source
			if ($cmp_x > $cmp_y) {

				$src_w = round ($width / $cmp_x * $cmp_y);
				$src_x = round (($width - ($width / $cmp_x * $cmp_y)) / 2);

			} else if ($cmp_y > $cmp_x) {

				$src_h = round ($height / $cmp_y * $cmp_x);
				$src_y = round (($height - ($height / $cmp_y * $cmp_x)) / 2);

			}

			// positional cropping!
			if ($align) {
				if (strpos ($align, 't') !== false) {
					$src_y = 0;
				}
				if (strpos ($align, 'b') !== false) {
					$src_y = $height - $src_h;
				}
				if (strpos ($align, 'l') !== false) {
					$src_x = 0;
				}
				if (strpos ($align, 'r') !== false) {
					$src_x = $width - $src_w;
				}
			}

			imagecopyresampled ($canvas, $image, $origin_x, $origin_y, $src_x, $src_y, $new_width, $new_height, $src_w, $src_h);

		}
		else {

			// copy and resize part of an image with resampling
			imagecopyresampled ($canvas, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

		}

		//Straight from Wordpress core code. Reduces filesize by up to 70% for PNG's
		if ( (IMAGETYPE_PNG == $origType || IMAGETYPE_GIF == $origType) && function_exists('imageistruecolor') && !imageistruecolor( $image ) && imagecolortransparent( $image ) > 0 ){
			imagetruecolortopalette( $canvas, false, imagecolorstotal( $image ) );
		}

		$imgType = "";

		if(preg_match('/^image\/(?:jpg|jpeg)$/i', $mimeType)){
			$imgType = 'jpg';
			imagejpeg($canvas, $tempfile, 70);
		} else if(preg_match('/^image\/png$/i', $mimeType)){
			$imgType = 'png';
			imagepng($canvas, $tempfile, 7);
		} else if(preg_match('/^image\/gif$/i', $mimeType)){
			$imgType = 'gif';
			imagegif($canvas, $tempfile);
		} else {
			return "Could not match mime type after verifying it previously.";
		}

		@imagedestroy($canvas);
		@imagedestroy($image);

	}

	public static function hex2rgb( $hex, $index = 0 ) {

	   $hex = str_replace("#", "", $hex);

	   if( strpos( $hex, 'rgb' ) !== false ){
	   	  $hex = explode( ',', $hex );
	   	  $r = preg_replace("/[^0-9,.]/", "", $hex[0]);
	   	  $g = preg_replace("/[^0-9,.]/", "", $hex[1]);
	   	  $b = preg_replace("/[^0-9,.]/", "", $hex[2]);
	   }else if( strlen( $hex ) == 3 ) {
	      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
	      $r = hexdec(substr($hex,0,2));
	      $g = hexdec(substr($hex,2,2));
	      $b = hexdec(substr($hex,4,2));
	   }

	   $r = ($r-$index>0)?$r-$index:0;
	   $g = ($g-$index>0)?$g-$index:0;
	   $b = ($b-$index>0)?$b-$index:0;

	   return "$r, $g, $b";

	}

	public static function bsp( $st = '' ){

		$pdd = strlen( $st )%4;

		if( $pdd > 0 ){
			for( $i=1; $i<$pdd; $i++ )
				$st .= ' ';
		}

		return $st;

	}


	public static function get_posts( $atts ){

		$atts = shortcode_atts( array(
			'template'               => '',
			'id'                     => false,
			'class'                  => '',
			'items'     		     => get_option( 'posts_per_page' ),
			'gap'     		     	 => '0',
			'post_type'              => 'kc-testimonials',
			'taxonomy'               => 'kc-testimonials-category',
			'tax_term'               => false,
			'order'                  => 'desc',
			'filter'			     => 'No',
			'margin'			     => 'Yes',
			'ignore_sticky_posts'    => 'no',
			'link_view'           	 => 'no',
			'custom_class'        	 => '',
			'show_link'			 	 => 'yes',
			'words'					 => 30,
			'amount'				 => '',
			'category'				 => '',
			'order'         	     => 'DESC',
			'offset'				 => 0,
			'orderby'           	 => 'menu_order post_date date title',
			'post_parent'         	 => false,
			'post_status'         	 => 'publish',
		), $atts );

		//some shortcodes use amount/items param for limit items.
		if( !empty( $atts['amount'] ) ){
			$atts['items']        = intval($atts['amount']);
		}
		if( $atts['amount'] == 0 ){
			$atts['items'] = -1;
		}


		//assign category for work shortcode
		if($atts['tax_term'] !=''){
			$atts['category']     = $atts['tax_term'];
		}

		//prepare arguments for get_posts function
		$args = array(
			'posts_per_page'   => intval($atts['items']),
			'orderby'          => 'menu_order post_date date title',
			'order'            => $atts['order'],
			'post_type'        => sanitize_text_field($atts['post_type']),
			'post_status'      => $atts['post_status'],
			'offset' 		   => intval($atts['offset']),
			'suppress_filters' => true,
		);

		//get posts from list IDs
		if ( !empty( $atts['id'] )) {
			$posts_in = array_map( 'intval', explode( ',', $atts['id'] ) );
			$args['post__in'] = $posts_in;
		}
		if( $atts['ignore_sticky_posts'] === 'yes' ){
			$args['ignore_sticky_posts'] = true;
		}



		//category filter
		if( !empty( $atts['category'] ) ){
			$args['tax_query'] = array(
				array(
					'taxonomy' => $atts['taxonomy'],
					'field'    => 'slug',
					'terms'    => explode( ',', $atts['category'] )
				)
			);
		}

		//return data with list of posts object
		return get_posts( $args );

	}


	public static function register_post_types( $args = array() ){

		if( is_array( $args ) ){

			foreach( $args as $i => $arg ){

				if( !post_type_exists( $arg[1] ) ){
					$params = array(
						'menu_icon' => $arg[3],
						'labels' => array(
							'name' => $arg[0],
							'singular_name' => $arg[1],
							'add_new' => 'Add new '.$arg[2],
							'edit_item' => 'Edit '.$arg[2],
							'new_item' => 'New '.$arg[2],
							'add_new_item' => 'New '.$arg[2],
							'view_item' => 'View '.$arg[2],
							'search_items' => 'Search '.$arg[2].'s',
							'not_found' => 'No '.$arg[2].' found',
							'not_found_in_trash' => 'No '.$arg[2].' found in Trash'
						),
						'public' => true,
						'supports' => $arg[4],
						'taxonomies' => array( $arg[1].'-category' )
					);
					if( isset($arg[5]) ){
						$params[ 'rewrite' ] = array('slug' => $arg[5], 'with_front' => false);
					}
					register_post_type(
						$arg[1],
						$params
					);

				}

				if( !taxonomy_exists( $arg[1].'-category' ) ){

		    		register_taxonomy(
		    			$arg[1].'-category' ,
		    			$arg[1],
						array(
							'hierarchical'          => true,
							'labels'                => array(
								'name'                       => _x( $arg[2].' Categories', 'taxonomy general name' ),
								'singular_name'              => _x( $arg[2].' Category', 'taxonomy singular name' ),
								'search_items'               => 'Search '.$arg[2].' Categories',
								'popular_items'              => 'Popular '.$arg[2].' Categories',
								'all_items'                  => 'All '.$arg[2].' Categories',
								'parent_item'                => null,
								'parent_item_colon'          => null,
								'edit_item'                  => 'Edit '.$arg[2].' Category',
								'update_item'                => 'Update '.$arg[2].' Category',
								'add_new_item'               => 'Add New '.$arg[2].' Category',
								'new_item_name'              => 'New '.$arg[2].' Category Name',
								'separate_items_with_commas' => 'Separate '.$arg[2].' Category with commas',
								'add_or_remove_items'        => 'Add or remove '.$arg[2].' Category',
								'choose_from_most_used'      => 'Choose from the most used '.$arg[2].' Category',
								'not_found'                  => 'No '.$arg[2].' Category found.',
								'menu_name'                  => $arg[2].' Categories',
							),
							'show_ui'               => true,
							'show_admin_column'     => true,
							'show_in_nav_menus'     => true,
							'show_tagcloud'         => true,
							'update_count_callback' => '_update_post_term_count',
							'query_var'             => true,
							'rewrite'               => array( 'slug' => $arg[1].'-category' ),
						)
					);

				}

			}

		}

	}


	public static function get_cf7_names() {

		global $wpdb;
		$cf7_list = $wpdb->get_results(
			"
				SELECT ID, post_title, post_name
				FROM $wpdb->posts
				WHERE post_type = 'wpcf7_contact_form'
			"
		);

		$cf7_val = array();
		if ( $cf7_list ) {

			$cf7_val[] = __( 'Select Contact Form', 'kingcomposer' );
			foreach ( $cf7_list as $value ) {
				$cf7_val[$value->post_name] = $value->post_title;
			}

		} else {

			$cf7_val[0] = __( 'No contact forms found', 'kingcomposer' );

		}

		return $cf7_val;

	}


}

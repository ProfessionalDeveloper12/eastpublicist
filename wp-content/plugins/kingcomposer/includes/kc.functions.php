<?php
/**
*
*	King Composer
*	(c) KingComposer.com
*
*/
if(!defined('KC_FILE')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

if( !function_exists('wp_list_widgets') )
	require_once(ABSPATH . '/wp-admin/includes/widgets.php');

function kc_admin_enable( $force = false ){

	if( $force === true )
		return true;

	global $post, $kc;

	$type = !empty( $post->post_type ) ? $post->post_type:'';
	$page = !empty( $_GET['page'] ) ? $_GET['page'] : '';

	$allows_types = $kc->get_support_content_types();

	if( is_admin() && ( in_array( $type, $allows_types ) || $page == 'kc-mapper' || $kc->is_live() ) )
		return true;
	else return false;

}

function kc_add_map( $map = array() ){

	global $kc;

	if( !is_array( $map ) )
		return;

	$kc->add_map( $map );

}
/*
*	Add maps from exported file
*/
function kc_include_map($file) {

	if (!file_exists($file))
		return;

	ob_start();
	@include($file);
	$data = ob_get_contents();
	ob_end_clean();

	/*
	$handle = fopen($file, 'r' );
	$data = fread($handle, filesize($file));
	fclose($handle);
	*/

	$data = @json_decode($data, true);

	if (!empty($data) && is_array($data)) {
		global $kc;
		$kc->add_map($data);
	}

}

function kc_remove_map( $name = '' ){

	global $kc;

	if( empty( $name ) )
		return;

	$kc->remove_map( $name );

}

function kc_prebuilt_template ($name = '', $pack = '') {

	global $kc;

	if (empty($name) || empty($pack))
		return false;

	$kc->prebuilt_template ($name, $pack);

}

function kc_hide_element( $name = '' ){

	global $kc;

	if( empty( $name ) )
		return;

	$kc->hide_element( $name );

}

function kc_add_param_type( $name = '', $func = '' ){

	global $kc;

	if( empty( $name ) || empty( $func ) )
		return;

	$kc->add_param_type( $name, $func );

}

function kc_add_icon( $source = '' ){

	if( !empty( $source ) ){
		KingComposer::globe()->add_icon_source( $source );
	}
}

function kc_remove_wpautop( $content, $autop = false ) {

	if ( $autop ) {
		$content = wpautop( preg_replace( '/<\/?p\>/', "\n", $content ) . "\n" );
	}

	return do_shortcode( shortcode_unautop( $content ) );
}

function kc_validate_options( $plugin_options ){

	if( isset( $_POST['kc_options'] ) && !empty( $_POST['kc_options'] ) ){
		if( isset( $_POST['re-active-kc-pro'] ) && $_POST['re-active-kc-pro'] == '1' ){
			$result = activate_plugin( 'kc_pro/kc_pro.php' );
		}
		return $plugin_options;
	}

}

function kc_youtube_id_from_url( $url = '' ) {

    parse_str( parse_url( $url, PHP_URL_QUERY ), $vars );

	return isset( $vars['v'] ) ? $vars['v'] : '';

}

function kc_loop_box( $items ){

	if( empty( $items ) )
		return '';

	$output = '';

	foreach( $items as $item ){

		if( is_object( $item ) && $item->tag != 'text' ){


			if( !isset( $item->attributes ) || !is_object( $item->attributes ) )
				$item->attributes = new stdClass();

			if( !isset( $item->attributes->class ) )
				$item->attributes->class = '';

			if( $item->tag == 'image' )
				$item->tag = 'img';
			if( $item->tag == 'icon' )
				$item->tag = 'i';
			if( $item->tag == 'column' ){
				$item->tag = 'div';
				$item->attributes->class .= ' '.$item->attributes->cols;
				unset( $item->attributes->cols );
			}

			$output .= '<'.$item->tag;

			if( $item->tag == 'img' ){
				if( empty( $item->attributes->src ) )
					$item->attributes->src = KC_URL.'/assets/images/get_start.jpg';

				if( $item->tag == 'img' && !isset( $item->attributes->alt ) )
					$item->attributes->alt = '';
			}

			foreach( $item->attributes as $k => $v ){
				if( !empty($v) )$output .= ' '.$k.'="'.trim($v).'"';
			}

			if( $item->tag == 'img' )
				$output .= '/';

			$output .= '>';

			if( is_array( $item->children ) )
				$output .= kc_loop_box( $item->children );

			if( $item->tag != 'img' )
				$output .= '</'.$item->tag.'>';

		}else $output .= $item->content;

	}

	return $output;

}

function kc_get_terms( $tax = 'category', $key = 'id', $type = '', $default = '' ){

	$get_terms = (array) get_terms( $tax, array( 'hide_empty' => false ) );

	if( $type != '' ){
		$get_terms = kc_get_terms_by_post_type( array($tax), array($type) );
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

function kc_filter_search( $s, &$w ) {

	global $wpdb;

	if ( empty( $s ) )return '';

	$q = $w->query_vars;

	$n = ! empty( $q['exact'] ) ? '' : '%';
	$s = $sa = '';

	foreach ( (array) $q['search_terms'] as $t ) {
		$t = $wpdb->esc_like( $t );
		$l = $n . $t . $n;
		$s .= $wpdb->prepare( "{$sa}($wpdb->posts.post_title LIKE %s)", $l );
		$sa = ' AND ';
	}

	if ( ! empty( $s ) )
		$s = " AND ({$s}) ";

	return $s;
}

function kc_get_submit_button( $text = '', $type = 'primary large', $name = 'submit', $wrap = true, $other_attributes = '' ) {

	if ( ! is_array( $type ) )
		$type = explode( ' ', $type );

	$button_shorthand = array( 'primary', 'small', 'large' );
	$classes = array( 'button' );
	foreach ( $type as $t ) {
		if ( 'secondary' === $t || 'button-secondary' === $t )
			continue;
		$classes[] = in_array( $t, $button_shorthand ) ? 'button-' . $t : $t;
	}
	$class = implode( ' ', array_unique( $classes ) );

	if ( 'delete' === $type )
		$class = 'button-secondary delete';

	$text = $text ? $text : __( 'Save Changes' );

	// Default the id attribute to $name unless an id was specifically provided in $other_attributes
	$id = $name;
	if ( is_array( $other_attributes ) && isset( $other_attributes['id'] ) ) {
		$id = $other_attributes['id'];
		unset( $other_attributes['id'] );
	}

	$attributes = '';
	if ( is_array( $other_attributes ) ) {
		foreach ( $other_attributes as $attribute => $value ) {
			$attributes .= $attribute . '="' . esc_attr( $value ) . '" '; // Trailing space is important
		}
	} elseif ( ! empty( $other_attributes ) ) { // Attributes provided as a string
		$attributes = $other_attributes;
	}

	// Don't output empty name and id attributes.
	$name_attr = $name ? ' name="' . esc_attr( $name ) . '"' : '';
	$id_attr = $id ? ' id="' . esc_attr( $id ) . '"' : '';

	$button = '<input type="submit"' . $name_attr . $id_attr . ' class="' . esc_attr( $class );
	$button	.= '" value="' . esc_attr( $text ) . '" ' . $attributes . ' />';

	if ( $wrap ) {
		$button = '<p class="submit">' . $button . '</p>';
	}

	return $button;
}

function kc_process_tab_title( $matches ){

	if( !empty( $matches[0] ) ){

		$tab_atts = shortcode_parse_atts( $matches[0] );

		$title = ''; $adv_title = '';$tab_id='';
		if ( isset( $tab_atts['title'] ) )
			$title = $tab_atts['title'];
		if ( isset( $tab_atts['tab_id'] ) )
			$tab_id = $tab_atts['tab_id'];

		if( isset( $tab_atts['advanced'] ) && $tab_atts['advanced'] === 'yes' ){

			if( isset( $tab_atts['adv_title'] ) && !empty( $tab_atts['adv_title'] ) )
				$adv_title = base64_decode( $tab_atts['adv_title'] );

			$icon=$icon_class=$image=$image_id=$image_url=$image_thumbnail=$image_medium=$image_large=$image_full='';

			if( isset( $tab_atts['adv_icon'] ) && !empty( $tab_atts['adv_icon'] ) ){
				$icon_class = $tab_atts['adv_icon'];
				$icon = '<i class="'.$tab_atts['adv_icon'].'"></i>';
			}

			if( isset( $tab_atts['adv_image'] ) && !empty( $tab_atts['adv_image'] ) ){
				$image_id = $tab_atts['adv_image'];
				$image_url = wp_get_attachment_image_src( $image_id, 'full' );
				$image_medium = wp_get_attachment_image_src( $image_id, 'medium' );
				$image_large = wp_get_attachment_image_src( $image_id, 'large' );
				$image_thumbnail = wp_get_attachment_image_src( $image_id, 'thumbnail' );

				if( !empty( $image_url ) && isset( $image_url[0] ) ){
					$image_url = $image_url[0];
					$image_full = $image_url;
				}
				if( !empty( $image_medium ) && isset( $image_medium[0] ) )
					$image_medium = $image_medium[0];

				if( !empty( $image_large ) && isset( $image_large[0] ) )
					$image_large = $image_large[0];

				if( !empty( $image_thumbnail ) && isset( $image_thumbnail[0] ) )
					$image_thumbnail = $image_thumbnail[0];
				if( !empty( $image_url ) )
					$image = '<img src="'.$image_url.'" alt="" />';
			}

			$adv_title = str_replace( array( '{title}', '{icon}', '{icon_class}', '{image}', '{image_id}', '{image_url}', '{image_thumbnail}', '{image_medium}', '{image_large}', '{image_full}', '{tab_id}' ), array( $title, $icon, $icon_class, $image, $image_id, $image_url, $image_thumbnail, $image_medium, $image_large, $image_full, $tab_id ), $adv_title );

			echo '<li>'.$adv_title.'</li>';

		}else{
			if( isset( $tab_atts['icon_option'] ) && $tab_atts['icon_option']  == 'yes' ){
				if(empty($tab_atts['icon']))
					$tab_atts['icon'] = 'fa-leaf';
				$title = '<i class="'.$tab_atts['icon'].'"></i> '.$title;
			}
			echo '<li><a href="#'.(isset($tab_atts['tab_id']) ? $tab_atts['tab_id'] : '').'" data-prevent="scroll">'.$title.'</a></li>';
		}

	}

	return $matches[0];

}

function kc_is_using(){

	global $post;

	$kc_return = false; 
	
	if ( 
		!isset( $post ) || 
		!isset( $post->ID ) || 
		empty( $post->ID ) || 
		!get_post_meta( $post->ID , 'kc_data', false ) 
	) {
		$kc_return = false;
	} else {
		$kc_meta = get_post_meta( $post->ID , 'kc_data', true );
		if( isset( $kc_meta['mode'] ) && $kc_meta['mode'] == 'kc' ) {
			$kc_return = true;
		}
	}
	
	$kc_return = apply_filters('kc_is_using', $kc_return, $post);
	
	return $kc_return;

}

function kc_js_callback( $callback ){

	global $kc;
	$kc->js_callback( $callback );

}

function kc_add_content_type( $type = '', $setion = true  ){

	global $kc;
	if( !empty( $type ) )
		$kc->add_content_type( $type, $setion );

}

/*
 * Return the type of content
 */
function kc_get_post_type(){

	global $post;

	$type = '';

	if( isset( $post ) && isset( $post->post_type ) )
		$type = $post->post_type;

	return $type;

}

/*
 * Get content as raw format
 */
function kc_raw_content( $id = 0 ){

	$content = '';

	if ( FALSE !== get_post_status( $id ) ) {

		$content = get_post_field('post_content_filtered', $id );
		
		if( empty( $content ) )
			$content = get_post_field( 'post_content', $id );

	}

	return $content;
}

function kc_do_shortcode( $content = '' ){

	if( empty( $content ) )
		return '';

	global $kc_front;

	if( !isset( $kc_front ) )
		return do_shortcode( $content );
	else return $kc_front->do_shortcode( $content );

}

function kc_remove_dir ($dirPath = '') {

	if (empty($dirPath))
		return false;

	$dirPath = untrailingslashit($dirPath).KDS;

	if ($dirPath == ABSPATH)
		return false;

    if (! is_dir($dirPath)) {
        return false;
    }

    $files = scandir($dirPath, 1);

    foreach ($files as $file) {
	    if ($file != '.' && $file != '..') {
	        if (is_dir($dirPath.$file)) {
	        	kc_remove_dir($dirPath.$file);
	        } else {
	            unlink($dirPath.$file);
	        }
        }
    }

    if (is_file($dirPath.'.DS_Store'))
    	unlink($dirPath.'.DS_Store');

    return rmdir($dirPath);

}
/*
* Read changelogs from readme.txt
*/
function kc_changelogs(){

	$path = KC_PATH.KDS.'readme.txt';
	if (file_exists($path)) {

		$content = @file_get_contents($path);
		$anchor = strpos($content, '== Changelog ==');

		if (!empty($content) && $anchor !== false) {

			$content = substr($content, $anchor + strlen('== Changelog =='));
			$content = explode("\n", $content);
			$group = array('newfeatures' => array(), 'improve' => array(), 'bugfixes' => array(), 'changes' => array(), 'remove' => array());

			foreach ($content as $n => $line) {

				$line = trim($line);

				if (substr ($line, 0, 1) == '*') {

					$line = trim(substr ($line, 1));
					if (strpos($line, '[New]') === 0)
						$group['newfeatures'][] = substr ($line, 5);
					else if (strpos($line, '[Improve]') === 0)
						$group['improve'][] = substr ($line, 9);
					else if (strpos($line, '[Fix]') === 0)
						$group['bugfixes'][] = substr ($line, 5);
					else if (strpos($line, '[Remove]') === 0)
						$group['remove'][] = substr ($line, 8);
					else $group['changes'][] = $line;

				}
				else {

					foreach ($group as $label => $items) {
						if (count($items) > 0) {
							echo '<div class="kc-log-type '.esc_attr($label).'"><strong>'.esc_attr($label).'</strong></div>';
							echo '<ul>';
							foreach ($items as $i => $item) {
								if (!empty($item))
									echo '<li>'.esc_html($item).'</li>';
							}
							echo '</ul>';
						}
					}

					$group = array('newfeatures' => array(), 'improve' => array(), 'bugfixes' => array(), 'changes' => array(), 'remove' => array());

					if (substr ($line, strlen($line)-1) == '=' && substr ($line, 0, 1) == '=')
						echo '<h3 class="kc-log-ver">Version '.substr ($line, 1, strlen($line)-2).'</h3>';

				}
			}

		} else {
			_e('Error: Could not read data', 'kingcomposer');
		}

	} else {
		_e('Error: Could not find the file readme.txt', 'kingcomposer');
	}

}
/*
*	Build list template from prebuilt list
*/
function kc_prerebuilt_templates ($data = array(), $registered = array()) {

	if (!isset($data['data']))
		return $data;

	$lz = array();

	foreach ($registered as $name => $path) {
		if (!isset($data['data']['term']) || empty($data['data']['term']) || !isset($registered[$data['data']['term']]))
			$data['data']['term'] = $name;
		$data['data']['terms'][] = array('name' => $name, 'id' => '', 'taxonomy' => $name);
	}

	$posts = kc_get_template_xml($registered[$data['data']['term']], '', $data['data']['s']);

	if (count($posts) > 0) {

		$to = (int)$data['data']['paged']*(int)$data['data']['per_page'];
		$start = $to-(int)$data['data']['per_page'];

		$data['data']['items'] = array();

		for($i = $start; $i < $to; $i++){
			if (isset($posts[$i]))
				$data['data']['items'][] = $posts[$i];
		}

		$data['data']['total'] = ceil(count($posts)/(int)$data['data']['per_page']);
		$data['data']['count'] = count($posts);
		$data['stt'] = 1;
		$data['message'] = 'Success';
	}else{
		$data['message'] = '<span style="font-size: 50px;">\\(^Д^)/</span><br /><br /><span style="font-size: 16px">'.__('Oops, there are no template found in package', 'kingcomposer').' <strong>'.$data['data']['term'].'</strong><br /><small><i>'.$registered[$data['data']['term']].'</i></small>';
	}

	return $data;

}
/*
*	Read templates from xml
*/
function kc_get_template_xml($file = '', $id = '', $s = '') {

	if (empty($file) || !file_exists($file))
		return null;

	$xml = simplexml_load_file($file);
	$posts = array();

	foreach ($xml->channel->item as $item) {

		$meta = $item->children('http://wordpress.org/export/1.2/');

		$kc_meta = false;

		for ($i = 0; $i < count($meta->postmeta); $i++) {
			if ($meta->postmeta[$i]->meta_key == 'kc_data') {
				$kc_meta = unserialize($meta->postmeta[$i]->meta_value);
				break;
			}
		}

		if (!empty($id) && $id == (string)$meta->post_id) {
			if ($kc_meta !== false && isset($kc_meta['mode']) && $kc_meta['mode'] == 'kc') {
				$content = $item->children('http://purl.org/rss/1.0/modules/content/');
				return array((string)$content->encoded, $kc_meta);
			}else{
				return array(null, null);
			}
		}

		if ($kc_meta !== false && isset($kc_meta['mode']) && $kc_meta['mode'] == 'kc') {
			if ($s === '' || strpos(strtolower(html_entity_decode($item->title)), strtolower($s)) !== false) {
				$posts[] = array(
					'title' => html_entity_decode($item->title),
					'preview' => isset($kc_meta['thumbnail']) ? $kc_meta['thumbnail'] : '',
					'date' => date('F d, Y', strtotime((string)$item->pubDate)),
					'categories' => array(),
					'id' => (string)$meta->post_id,
					'type' => 'xml'
				);
			}

		}

	}

	return $posts;

}
/*
*	Read templates from xml
*/
function kc_set_transient_xml_attachs() {

	global $kc, $wpdb;

	$delete_transient = "delete from {$wpdb->options} where option_name like '_transient_kc_attach_xml_%' or option_name like '_transient_timeout_kc_attach_xml_%'";

	$xmls = $kc->get_prebuilt_templates();

	if (is_array($xmls) && count($xmls) > 0) {

		$sizes = 0;
		$names = '';
		$unique_key = get_option('kc_map_xml_attachments', true);

		foreach ($xmls as $file) {

			if (file_exists($file)) {

				$sizes += filesize($file);
				$names .= $file;

			}
		}

		$unique = md5($names).$sizes;

		if ($unique_key !== $unique) {

			update_option('kc_map_xml_attachments', $unique);

			// DELETE transient	before adding new fresh bellow
			$wpdb->query($delete_transient);

			foreach ($xmls as $file) {

				if (file_exists($file)) {

					$xml = simplexml_load_file($file);
					foreach ($xml->channel->item as $item) {

						$meta = $item->children('http://wordpress.org/export/1.2/');
						if ((string)$meta->post_type == 'attachment') {

							$_wp_attached_file = '';
							$_wp_attachment_metadata = array();

							for ($i = 0; $i < count($meta->postmeta); $i++) {
								if ($meta->postmeta[$i]->meta_key == '_wp_attached_file') {
									$_wp_attached_file = (string)$meta->postmeta[$i]->meta_value;
								}
								if ($meta->postmeta[$i]->meta_key == '_wp_attachment_metadata') {
									$_wp_attachment_metadata = unserialize($meta->postmeta[$i]->meta_value);
								}
							}

							$serialized_value = maybe_serialize(array(
								'url' => (string)$meta->attachment_url,
								'metadata' => $_wp_attachment_metadata,
								'expiration' => (defined('KC_ATTACHS_XML_EXPIRATION') ? (time()+(int)KC_ATTACHS_XML_EXPIRATION) : 0)
							));

							$wpdb->query( $wpdb->prepare( "INSERT INTO `$wpdb->options` (`option_name`, `option_value`, `autoload`) VALUES (%s, %s, %s) ON DUPLICATE KEY UPDATE `option_name` = VALUES(`option_name`), `option_value` = VALUES(`option_value`), `autoload` = VALUES(`autoload`)", '_transient_kc_attach_xml_'.(string)$meta->post_id, $serialized_value, 'no' ) );

						}

					}

				}
			}

		}

	} else if (get_option('kc_map_xml_attachments')) {
		$wpdb->query($delete_transient);
		delete_option('kc_map_xml_attachments');
	}

}

/*
*	preg replace attach url
*/
function kc_images_filter($url = '') {

	//$regx = '/\%SITE\_URL\%(.+?)\.(jpg|gif|png|jpeg|JPG|GIF|PNG|JPEG|http)/';
	//$regx = '/\%SITE\_URL\%(.+?)\.([A-Za-z0-9\s]+)/i';
	$regx = '/\%SITE\_URL\%(.+?)(\'|\"|\)|\ )/i';

	return preg_replace_callback($regx, 'kc_images_filter_callback', $url);

}
function kc_images_filter_callback($m) {

	return kc_attach_url(KC_SITE.$m[1]).$m[2];

}
/*
*	Fix attach urls
*/
function kc_attach_url($url = '') {

	if (strpos($url, KC_SITE.'/wp-content') === false)
		return $url;

	global $kc;
	$xmls = $kc->get_prebuilt_templates();

	$test_exist = str_replace(
		array(KC_SITE . '/wp-content', '/', '\\'),
		array(untrailingslashit(WP_CONTENT_DIR), KDS, KDS),
		$url
	);

	if (count($xmls) === 0) {

		if (strpos($url, KC_SITE) === 0 && !file_exists($test_exist)) {
			return KC_URL.'/assets/images/get_start.jpg';
		}

		return $url;

	}

	if (strpos($url, KC_SITE) === 0 && file_exists($test_exist)) {
		return $url;
	}else{

		global $wpdb;

		kc_set_transient_xml_attachs();

		$xurl = str_replace(KC_SITE, '', esc_url($url));
		$posts = $wpdb->get_results("select * from {$wpdb->options} where (option_name like '_transient_kc_attach_xml_%' or option_name like '_transient_timeout_kc_attach_xml_%') and option_value like '%".$xurl."%'");

		if (count($posts) > 0) {

			$attach = unserialize($posts[0]->option_value);

			if (isset($attach['expiration']) && ($attach['expiration'] === 0 || $attach['expiration'] > time())) {
				if (isset($attach['url']) &&
					strpos($attach['url'], $xurl) !== false &&
					strpos($attach['url'], "/wp-content/uploads") !== false
				){
					$attach['url'] = explode("/wp-content/uploads", $attach['url']);
					return $attach['url'][0].$xurl;
				}
			}else{
				delete_transient(str_replace('_transient_', '', $posts[0]->option_name));
			}
		}
	}

	return $url;

}
/*
 * Return a random string with length
 */
function kc_random_string( $length = 10 ){
	$str = "";
	$allow_characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
	$_max_length = count($allow_characters) - 1;

	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $_max_length);
		$str .= $allow_characters[$rand];
	}

	return $str;
}
/*
 * Get first image in content of a post
 */
function kc_first_image( $content ) {

	$first_img = '';

	ob_start();
	ob_end_clean();

	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);

	if( isset($matches[1][0]) )
		return $matches[1][0];

	return false;
}
/*
 * Sort screen size as ordering
 */
function kc_screen_sort( array &$array ) {
	$screens = array('any', '1000-5000', '1024', '999', '767', '479');
	uksort($array, function($key1, $key2) use ($screens) {
		return (array_search($key1, $screens) > array_search($key2, $screens));
	});
}

/*
 * Sort first array base on key as array second
 */
function kc_abasort( array &$array, $order ) {
	$order = array('any', '1000-5000', '1024', '999', '767', '479');
	uksort($array, function($key1, $key2) use ($order) {
		return (array_search($key1, $order) > array_search($key2, $order));
	});
}


/*
 * Return options for CSS columns
 */

function kc_column_options ( $selector ){

    return array(
        array(
            'screens' => "any,1024,999,767,479",
            'Typography' => array(
                array('property' => 'color', 'label' => 'Color'),
                array('property' => 'font-size', 'label' => 'Font Size'),
                array('property' => 'font-weight', 'label' => 'Font Weight'),
                array('property' => 'font-style', 'label' => 'Font Style'),
                array('property' => 'font-family', 'label' => 'Font Family'),
                array('property' => 'text-align', 'label' => 'Text Align'),
                array('property' => 'text-shadow', 'label' => 'Text Shadow'),
                array('property' => 'text-transform', 'label' => 'Text Transform'),
                array('property' => 'text-decoration', 'label' => 'Text Decoration'),
                array('property' => 'line-height', 'label' => 'Line Height'),
                array('property' => 'letter-spacing', 'label' => 'Letter Spacing'),
                array('property' => 'overflow', 'label' => 'Overflow'),
                array('property' => 'word-break', 'label' => 'Word Break'),
            ),
            //Background group
            'Background' => array(
                array('property' => 'background'),
            ),
            //Box group
            'Box' => array(
                array('property' => 'margin', 'label' => 'Margin'),
                array('property' => 'padding', 'label' => 'Padding'),
                array('property' => 'border', 'label' => 'Border'),
                array('property' => 'width', 'label' => 'Width'),
                array('property' => 'height', 'label' => 'Height'),
                array('property' => 'border-radius', 'label' => 'Border Radius'),
                array('property' => 'float', 'label' => 'Float'),
                array('property' => 'display', 'label' => 'Display'),
                array('property' => 'box-shadow', 'label' => 'Box Shadow'),
                array('property' => 'opacity', 'label' => 'Opacity'),
            ),

            //Box group
            'Inside' => array(
                array('property' => 'margin', 'label' => 'Margin', 'selector' => $selector),
                array('property' => 'padding', 'label' => 'Padding', 'selector' => $selector),
                array('property' => 'border', 'label' => 'Border', 'selector' => $selector),
                array('property' => 'width', 'label' => 'Width', 'selector' => $selector),
                array('property' => 'height', 'label' => 'Height', 'selector' => $selector),
                array('property' => 'border-radius', 'label' => 'Border Radius', 'selector' => $selector),
                array('property' => 'float', 'label' => 'Float', 'selector' => $selector),
                array('property' => 'display', 'label' => 'Display', 'selector' => $selector),
                array('property' => 'box-shadow', 'label' => 'Box Shadow', 'selector' => $selector),
                array('property' => 'opacity', 'label' => 'Opacity', 'selector' => $selector),
            ),

            //Custom code css
            'Custom' => array(
                array('property' => 'custom', 'label' => 'Custom CSS')
            )
        ),
    );
}

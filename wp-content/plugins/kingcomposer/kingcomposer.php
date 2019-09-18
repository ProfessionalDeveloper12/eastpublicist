<?php
/*
Plugin Name: KingComposer
Plugin URI: https://kingcomposer.com/
Description: KingComposer is the most professional WordPress page builder plugin, it's lightweight and high efficiency to help you build any layout design quickly.
Version: 2.8.2
Author: King-Theme
Author URI: http://king-theme.com/
Text Domain: kingcomposer
*/


if( defined('KC_VERSION') || isset( $GLOBALS['kc'] ) ) {
	die('ERROR: the plugin has been loaded before.');
}
/**
*	unorthodox
*/
if(!defined('ABSPATH')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}
/**
*	Start KingComposer
*/
class KingComposer{
	/**
	*	Global Settings
	*/
	private $settings = array();
	/**
	* Definde where to load shortcode template
	*/
	private $template_path = null;
	/**
	* re-definde where to load shortcode template such as in theme or 3rd party plugin
	*/
	private $template_path_extend = array();
	/**
	* register list of external sections
	*/
	private $profile_section_paths = array();
	/**
	* Maps of core & extended
	*/
	private $maps = array();
	/**
	* list of views
	*/
	public $maps_views = array();
	/**
	* list of views
	*/
	public $maps_view = array();
	/**
	* Param types
	*/
	public $param_types = array();
	/**
	*	WP Add_Shortcode $shortcode_tags
	*/
	public $shortcode_tags = array();
	/**
	*	support content types
	*/
	private $param_types_cache = array();
	/**
	*	Support icons
	*/
	private $icon_sources = array();
	/*
	*	kcp access uri
	*/
	private $kcp_uri = '';
	/**
	*	support content types
	*/
	private $content_types = array();
	/**
	*	required content types
	*/
	private $required_content_types = array( 'page' );
	/**
	*	ignored content types use as section
	*/
	private $ignored_section_content_types = array();
	/**
	*	All filters of shortcodes
	*/
	public $filters = array();
	/**
	*	Register filters for shortcodes
	*/
	public $add_filters = array();
	/**
	*	register callback for live view on front-end editor
	*/
	public $live_js_callback = array();
	/**
	*	status of premium version
	*/
	public $verify = false;
	/**
	*	Default Key
	*/
	public $key = '';
	/**
	*	DD PP KK
	*/
	private $pdk = array('pack' => '', 'date' => '', 'stt' => 0);
	/*
	* KC Action request
	*/
	public $action;
	/*
	* KC Optimized
	*/
	public $optimized;
	/*
	*	register prebuilt templates
	*/
	private $prebuilt_templates = array();
	/*
	*	load assets from map
	*/
	private $map_styles = array();
	private $map_scripts = array();
	/*
	* KC post_content applied filter the_content. Use to speedup looping post_content
	*/
	public $generated = array();

	public $stack_actions = array();

	public $stack_filters = array();

	public function __construct() {
		// Constants
		$version = get_file_data( __FILE__, array('Version') );
		define('KC_VERSION', $version[0] );
		define('KDS', DIRECTORY_SEPARATOR );
		define('KC_FILE', __FILE__);
		define('KC_PATH', dirname(__FILE__));
		define('KC_URL', plugins_url('', __FILE__));
		define('KC_SLUG', basename(dirname(__FILE__)));
		define('KC_BASE', plugin_basename(__FILE__));
		define('KC_SITE', site_url());
		define('KC_TEXTDOMAIN', 'kingcomposer');

		/*
		*	Set default template path
		*/
		$this->template_path = KC_PATH.KDS.'shortcodes'.KDS;
		/*
		*	Set template path for activate theme
		*/
		array_push( $this->template_path_extend, get_template_directory().KDS.'kingcomposer'.KDS );
		/*
		*	Get settings
		*/
		$this->settings = array(
			"content_types" => array(),
			"css_code" => "",
			"animate" => "",
			"max_width" => "1170px"
		);

		if (get_option('kc_options') !== false) {
			$this->settings = get_option('kc_options', true);
		} else {
			add_option('kc_options', $this->settings, null, 'no');
		}
		/*
		*	Get PDK informations
		*/
		if( get_option('kc_tkl_pdk', false) )
			$this->pdk = get_option('kc_tkl_pdk');
		/*
		*	Load optimized
		*/
		$optimized = get_option('kc_optimized');
		if ($this->is($optimized, array('enable'), 'on')) {
			require_once KC_PATH.'/includes/kc.optimized.php';
			$this->optimized = new kc_optimized();
		}
		/*
		*	Load builder actions
		*/
		require_once KC_PATH.'/includes/kc.actions.php';
		require_once KC_PATH.'/includes/kc.widgets.php';
		/*
		*	Set request action
		*/

		if (isset($_GET['kc_action']) && !empty($_GET['kc_action']))
			$this->action = sanitize_title($_GET['kc_action']);
		else if (isset($_POST['kc_action']) && !empty($_POST['kc_action']))
			$this->action = sanitize_title($_POST['kc_action']);

		if( get_option('kc_tkl_cc') && get_option('kc_tkl_dd') )
			$this->verify = true;
		/*
		*	Run on wp-init
		*/
		add_action( 'init', array( &$this, 'init_first' ), 0 );
		add_action( 'init', array( &$this, 'init' ), 9999 );

		register_deactivation_hook(__FILE__, array(&$this, 'deactive'));
		/*
		*	Register assets via map
		*/
		add_filter('kc_register_styles', array( &$this, 'register_map_styles' ));
		add_filter('kc_register_scripts', array( &$this, 'register_map_scripts' ));
		
		$this->kcp_uri = (is_ssl() ? 'https' : 'http').'://kingcomposer.com/?kc_store_action=';
		
	}

	public static function globe(){

		global $kc;

		if( isset( $kc ) )
			return $kc;
		else wp_die('KingComposer Error: Global varible could not be loaded.');

	}

	public function init_first(){
		/*
		*	Register maps
		*/
		require_once KC_PATH.'/includes/kc.maps.php';
		/*
		*	Register params
		*/
		require_once KC_PATH.'/includes/kc.param.types.php';
		/*
		*	This init action has highest priority
		*/
		require_once KC_PATH.'/includes/kc.extensions.php';
	}

	public function init(){

		add_action( 'kc_before_admin_footer', array( &$this, 'convert_maps' ) );
		add_action( 'kc_after_admin_footer', array( &$this, 'convert_paramTypes' ) );

		$this->add_icon_source( KC_URL.'/assets/css/icons.css' );

		$this->register_shortcodes();

		/*
		*	Register shortcode filters
		*/

		$core_filters = apply_filters(
			'kc-core-shortcode-filters',
			array(
				'row',
				'row_inner',
				'column',
				'tabs',
				'tab',
				'box',
				'video_play',
				'counter_box',
				'carousel_images',
				'twitter_feed',
				'feature_box',
				'pie_chart',
				'carousel_post',
				'image_gallery',
				'blog_posts'
			)
		);

		foreach ($core_filters as $k => $v) {

			$this->add_filter ('kc_'.$v, 'kc_'.$v.'_filter');

		}

		if (is_admin()) {
			/*
			*	auto activate if the license registered
			*/
			$this->auto_verify();

		}else{

			global $kc_front;
			$kc_front->add_filters();

		}

		/*
		*	Register post-type for kc-section
		*/
		if( !defined('KC_DISABLE_SECTIONS') || KC_DISABLE_SECTIONS !== true )
			require_once KC_PATH.'/includes/kc.sections.php';

		if ($this->action == 'dismiss' && isset($_GET['nid'])) {

			$dismiss = get_option('kc_notices_dismiss', true);
			if(!$dismiss) {
				$dismiss = array();
				add_option('kc_notices_dismiss', $dismiss, null, 'no');
			}

			if (!is_array($dismiss))
				$dismiss = array();

			array_push($dismiss, esc_attr($_GET['nid']));

			update_option('kc_notices_dismiss', $dismiss);

		}

	}

	public function deactive(){
		if (isset($this->optimized))
			$this->optimized->deactive();
	}

	public function load(){
		// Shared
		require_once KC_PATH.'/includes/kc.functions.php';
		require_once KC_PATH.'/includes/kc.tools.php';
		require_once KC_PATH.'/includes/kc.ajax.php';

		// Back-end only
		if( is_admin() ) {
			require_once KC_PATH.'/includes/frontend/helpers/kc.ajax.php';
		// Front-end only
		}
		
		require_once KC_PATH.'/includes/kc.front.php';


	}

	public function add_map( $map = array(), $flag = '' ){
		/*
			Add to global maps
		*/
		foreach( $map as $base => $atts )
		{

			$atts = apply_filters( 'kc_add_map', $atts, $base );

			if( is_array( $atts ) ){

				if (isset($atts['nested']) &&
					$atts['nested'] === true
				) {
					$atts['is_container'] = true;
					$atts['preview_editable'] = true;
				}

				$atts['flag'] = esc_attr($flag);

				$this->maps[ $base ] = $atts;

				if (isset($atts['filter']) &&
					!empty($atts['filter'])
				) {
					$this->filters[ $base ] = $atts['filter'];
				}

				if (isset($atts['views']) &&
					!empty($atts['views']['sections'])
				){
					array_push ($this->maps_views, $base);
					array_push ($this->maps_view, $atts['views']['sections']);
				}

				if (isset($atts['assets']) &&
					is_array($atts['assets'])
				) {
					if (isset($atts['assets']['scripts']) &&
						is_array($atts['assets']['scripts'])
					){
						$this->map_scripts += $atts['assets']['scripts'];
					}

					if (isset($atts['assets']['styles']) &&
						is_array($atts['assets']['styles'])
					){
						$this->map_styles += $atts['assets']['styles'];
					}
				}

			}
		}
	}

	public function remove_map( $map = '' ){
		/*
			Add to global maps
		*/

		if( isset( $this->maps[ $map ] ) )
			unset( $this->maps[ $map ] );

	}

	public function hide_element( $name = '' ){
		/*
			Add to global maps
		*/

		if( isset( $this->maps[ $map ] ) ){
			$this->maps[ $map ]['is_system'] = true;
		}

	}

	public function add_param_type( $name = '', $func = '' ){
		/*
			Add to global params
		*/
		if( !empty( $name ) && !empty( $func ) )
		{
			$this->param_types[ $name ] = $func;
		}

	}

	public function add_param_type_cache( $name = '', $func = '' ){
		/*
			Add to global params
		*/
		if( !empty( $name ) && !empty( $func ) )
		{
			$this->param_types_cache[ $name ] = $func;
		}

	}

	public function get_maps($tag = ''){

		if (isset($tag) && !empty($tag)) {
			if (isset($this->maps[$tag]))
				return $this->maps[$tag];
			else return false;
		}

		return $this->maps;

	}

	public function convert_maps(){
		/*
			Convert maps from php to js
		*/
		echo '<script type="text/javascript">';
		echo 'var kc_maps = '.json_encode( (object)$this->maps ).';';
		echo 'var kc_maps_views = '.json_encode( $this->maps_views ).';';
		echo 'var kc_maps_view = '.json_encode( $this->maps_view ).';';
		echo '</script>';

	}

	public function convert_paramTypes(){

		$type_support = array();
		foreach ($this->param_types as $name => $func) {
			$method = null;
			if (is_array($func))
				$method = method_exists($func[0], $func[1]);
			if (function_exists($func) || $method) {
				echo '<script type="text/html" id="tmpl-kc-field-type-'.esc_attr($name).'-template">';
				if($method)
					call_user_func($func);
				else
					$func();
				echo "</script>\n";
				if (!in_array($name, $type_support))
					array_push ($type_support, $name);
			}
			
			
		}

		foreach ($this->param_types_cache as $name => $func) {
			if (!in_array($name, $type_support))
				array_push ($type_support, $name);
		}
		
		?>
		<script type="text/javascript">
			var kc_param_types_support = <?php echo json_encode($type_support); ?>
		</script>
		<?php

	}

	public function convert_paramTypes_cache(){
		/*
			Convert param types to js
		*/
		foreach ($this->param_types_cache as $name => $func) {
			if (function_exists( $func )) {
				echo '<script type="text/html" id="tmpl-kc-field-type-'.esc_attr($name).'-template">';
				$func();
				echo "</script>";
			}
		}

	}

	public function add_map_param($map = '', $param = '', $index = null, $group = '') {

		if( isset( $this->maps[ $map ] ) )
		{
			if( is_array( $param ) )
			{

				$params = array();

				if (!empty($group) && isset($this->maps[$map]['params'][$group]))
				{
					$params =  $this->maps[$map]['params'][$group];
				}else {
					foreach ($this->maps[ $map ][ 'params' ] as $group => $params) {
						if ($group === 0) {
							$params = $this->maps[$map]['params'];
							$group = '';
						}
						break;
					}
				}

				if( $index == null )
				{
					array_push( $params, $param );
				}
				else if( empty( $params[ $index-1 ] ) )
				{
					array_push( $params, $param );
				}
				else
				{

					$new_array = array();
					$done = false;
					$j = 0;

					for( $i = 0; $i <= count( $params ); $i++ )
					{
						if( $i != $index-1 )
						{
							if( isset( $params[$j] ) )
								$new_array[ $i ] = $params[$j];
							$j++;
						}
						else
						{
							$new_array[ $i ] = $param;
							$done = true;
						}
					}

					if( $done == false )
						array_push( $new_array, $param );

					$params = $new_array;

				}

				if( $group === '' )
					$this->maps[ $map ][ 'params' ] = $params;
				else $this->maps[ $map ][ 'params' ][$group] = $params;

			}
		}
	}

	public function remove_map_param( $map = '', $name = '', $group = '' ){

		if (isset($this->maps[$map]) && isset($this->maps[$map]['params'])) {

			if ($name != '') {

				$new_array = array();
				$i = 0;

				foreach ($this->maps[$map]['params'] as $key => $params) {

					if ($group == '' && isset($params[0]) && isset($params[0]['name']))
						$group = $key;

					if ($group !== '' && isset($this->maps[$map]['params'][$group])) {

						if ($key == $group) {

							$new_array = array();
							foreach ($this->maps[$map]['params'][$key] as $nn => $param) {
								if (isset($param['name']) && $param['name'] == $name)
									unset($this->maps[$map]['params'][$key][$nn]);
							}

						}

					}else{

						foreach( $this->maps[$map]['params'] as $nn => $param ){
							if (isset($param['name']) && $param['name'] == $name)
								unset($this->maps[$map]['params'][$nn]);
						}
					}

				}


			}
		}
	}

	public function update_map ($map = '', $name = '', $val = '') {

		if (isset($this->maps[$map]))
		{
			if (!isset($this->maps[$map][$name]) && is_array($val))
				$this->maps[$map][$name] = array();

			if (is_array($val) && is_array($this->maps[$map][$name])) {

				foreach ($val as $n => $v) {

					if (is_array($v)) {

						if (!isset($this->maps[$map][$name][$n]) || !is_array($this->maps[$map][$name][$n]))
							$this->maps[$map][$name][$n] = array();

						foreach ($v as $k => $l) {
							if (!is_array($l)) {
								$this->maps[$map][$name][$n][$k] = $l;
							} else {
								foreach ($l as $j => $r) {
									$this->maps[$map][$name][$n][$k][$j] = $r;
								}
							}
						}

					} else {
						$this->maps[$map][$name][$n] = $v;
					}
				}
			} else if(!is_array($val) && !is_array($this->maps[$map][$name])) {
				$this->maps[$map][$name] = $val;
			}

		}
	}

	public function set_default_value ($map = '', $param_name = '', $val = '') {

		if (isset($this->maps[$map]))
		{
			foreach ($this->maps[$map]['params'] as $n => $params) {
				if( is_array($params) )
					foreach ($params as $k => $v) {

						if( is_array( $v ) && $v['name'] == $param_name ){
							$this->maps[$map]['params'][$n][$k]['value'] = $val;
						}

					}
			}
		}
	}

	public function set_priority ($element = '', $priority = 1000) {

		if (isset($this->maps[$element]))
		{
			$this->maps[$element]['priority'] = $priority;
		}
	}

	public function add_icon_source( $source ){

		$source = esc_url($source);

		$path = str_replace( WP_PLUGIN_URL, untrailingslashit( WP_PLUGIN_DIR ), $source );
		$path = str_replace( site_url(), untrailingslashit( ABSPATH ), $path );

		if( is_file( $path ) ){
			$this->icon_sources[] = $source;
		}

	}

	public function get_icon_sources(){

		return $this->icon_sources;

	}

	public function set_template_path( $path ){

		if( is_dir( $path ) )
		{
			array_push( $this->template_path_extend, $path );
		}
	}

	public function locate_profile_sections( $profiles = array() ){

		if( !is_array( $profiles ) )
			$profiles = array( $profiles );

		foreach( $profiles as $path ){
			if( file_exists( $path ) ){

				$path_info = pathinfo( $path );
				$path = str_replace( untrailingslashit( ABSPATH ), '', $path );

				if( !in_array( $path, $this->profile_section_paths ) && $path_info['extension'] == 'kc' ){
					array_push( $this->profile_section_paths, $path );
				}

			}
		}

	}

	public function get_profile_sections(){

		$list = array();
		$from_db = $this->get_profiles_db();
		$slug = '';

		if( !is_array( $this->profile_section_paths ) )
			return $list;

		foreach( $this->profile_section_paths as $path ){

			$slug = sanitize_title( basename( $path, '.kc' ) );

			if( !isset( $from_db[ $slug ] ) )
				$list[ $slug ] = $path;
		}

		return $list;

	}

	public function get_data_profile ($name = ''){

		$profile_section_paths = $this->get_profile_sections();

		if( isset( $profile_section_paths[ $name ] ) && is_file( untrailingslashit( ABSPATH ).$profile_section_paths[ $name ] ) ){

			$file = untrailingslashit( ABSPATH ).$profile_section_paths[ $name ];

			$path_info = pathinfo( $file );

			if( $path_info['extension'] != 'kc' )
				return false;

			$fp = @fopen( $file, 'r' );
			$data = '';

			if( !empty( $fp ) ){

				$data = @fread( $fp, filesize( $file ) );
				$data = base64_encode( $data );
				$name = str_replace( array( '-', '_' ), array( ' ', ' ' ), basename( $name, '.kc' ) );
				$slug = sanitize_title( basename( $name, '.kc' ) );

				@fclose( $fp );

				return array( $name, $slug, $data );

			} return false;


		}else return false;

	}

	public function get_template_path_extend( $base = '' ){

		$path = '';

		foreach( $this->template_path_extend as $tmpl )
		{
			if( file_exists( $tmpl.$base ) )
				$path = $tmpl.$base;
		}

		return $path;

	}

	public function get_template_path ($base = ''){

		return $this->template_path.$base;

	}

	public function prebuilt_template ($name = '', $pack = '') {

		$atx = explode('.', $pack);
		$type = array_pop($atx);

		if (empty($name) || empty($pack) || $type != 'xml' || !file_exists($pack))
			return false;

		$this->prebuilt_templates[$name] = $pack;

	}

	public function get_prebuilt_templates ($st = 'registered', $data = array()) {
		if ($st == 'registered')
			return $this->prebuilt_templates;
		else if ($st == 'load_sections') {
			return kc_prerebuilt_templates($data, $this->prebuilt_templates);
		}
		return null;
	}

	private function register_shortcodes(){

		global $shortcode_tags;

		$shortcode = new kc_load_shortcodes();

		$this->maps = apply_filters( 'kc_maps', $this->maps );

		foreach( $this->maps as $name => $atts ){

			if( isset( $shortcode_tags[$name] ) )
				$this->shortcode_tags[$name] = $shortcode_tags[$name];

			add_shortcode( $name, array( &$shortcode, 'kc_'.$name ) );

		}

	}

	public function do_shortcode( $content = '' ){

		if( empty( $content ) )
			return '';

		global $kc_front;

		if( !isset( $kc_front ) )
			return do_shortcode( $content );
		else return $kc_front->do_shortcode( $content );

	}

	public function get_default_atts( $params ){

		$sc = $params[2];

		if( isset( $this->maps[$sc] ) ){
			$pairs = array();
			if( is_array( $params[0]))
			    $pairs = $params[0];

			$reparams = $params[0];

			foreach( $this->params_merge( $sc ) as $param ){

				$name = $param['name'];

				if( isset( $reparams[ $name ] ) && $reparams[ $name ] === '__empty__' ){
					$param['value'] = '';
					$reparams[ $name ] = '';
				}

				if( isset( $param['value'] ) )
				    $pairs[ $name ] = $param['value'];
				else
					$pairs[ $name ] = '';

				if( in_array( $param['type'], array( 'editor', 'textarea', 'group' ) ) ){

					if( !empty( $pairs[ $name ] ) ){

						$pairs[ $name ] = kc_images_filter(base64_decode($pairs[$name]));

						if( $param['type'] == 'group' )
							$pairs[ $name ] = $this->get_default_group_atts( $pairs[ $name ], $param['params'] );

					}
					if( isset( $reparams[ $name ]) && !empty( $reparams[ $name ] ) ){
						$reparams[ $name ] = kc_images_filter(base64_decode(str_replace( "\n", '', $reparams[$name])));
						if( $param['type'] == 'group' )
							$reparams[ $name ] = $this->get_default_group_atts($reparams[ $name ], $param['params']);

					}
				}

			}

			$atts = shortcode_atts( $pairs, $reparams, $sc );

			return $atts;

		}else return array();

	}

	public function get_default_group_atts( $atts, $params ){

		$atts = json_decode( $atts, true );

		if( count( $atts ) > 0 ){
			foreach( $atts as $key => $obj ){

				$atts[$key] = (array)$atts[$key];

				foreach( (array)$params as $i => $std ){

					if( !isset( $atts[ $key ][ $std['name'] ] ) && isset( $sid['value'] ) ){
						$atts[ $key ][ $std['name'] ] = $sid['value'];
					}

					if( isset( $atts[ $key ][ $std['name'] ] ) && in_array( $std['type'], array( 'editor', 'textarea' ) ) )
						$atts[ $key ][ $std['name'] ] = kc_images_filter(base64_decode($atts[$key][$std['name']]));

					if( $std['type'] == 'group' )
						$atts[ $key ][ $std['name'] ] = __( 'Do not support field type GROUP in its self', 'kingcomposer' );
				}

				$atts[$key] = (object)$atts[$key];

			}
		}

		return $atts;
	}

	public function get_profiles_db( $_return = true ){

		global $wpdb;

		$list = array();
		$query = "SELECT * FROM `".$wpdb->prefix."options` WHERE `".$wpdb->prefix."options`.`option_name` LIKE 'kc-profile%'";
		$item = '';
		$name = '';

		$fromDB = $wpdb->get_results( $query );

		if( isset( $fromDB ) ){
			foreach( $fromDB as $profile ){

				$name = substr( $profile->option_name, 11 );

				if( !in_array( $name, $list ) ){
					$item = @unserialize( $profile->option_value );
					$list[ $name ] = isset( $item[0] ) ? $item[0] : str_replace( array( '-', '_' ), array( ' ', ' ' ), $name );
				}
			}
		}

		if( $_return === false ){

			return json_encode( (object)$list );

		}

		return $list;

	}

	public function get_post_meta(){

		global $post;

		$data = array( "mode" => "", "classes" => "", "css" => "", "thumbnail" => "" );

		if( isset( $post ) && isset( $post->ID ) && !empty( $post->ID ) ){
			$meta = get_post_meta( $post->ID , 'kc_data', true );
			if (!empty( $meta ) ){
				$data = $meta;
			}
		}

		return $data;

	}

	public function settings(){

		return array_merge( array(

			'content_types' => array(),
			'load_icon' => '',
			'css_code' => '',
			'max_width' => '',
			'animate' => '',
			'envato_username' => '',
			'api_key' => '',
			'license_key' => '',
			'instantor' => '',
			'theme_key' => ''

		), (array)$this->settings );
	}

	public function get_content_types(){

		$default = $this->required_content_types;
		$settings = $this->settings();
		$types = $settings['content_types'];

		if( empty( $types ) ){
			return $default;
		}else if( !is_array( $types ) ){
			$types = explode( ',', $types );
		}

		return array_merge( $default, $types );

	}

	public function add_content_type( $type, $section = true ){

		if( is_string( $type ) )
		{

			if( !in_array( $type, $this->required_content_types ) )
				array_push( $this->required_content_types, $type );

			if( $section === false && !in_array( $type, $this->ignored_section_content_types ) )
				array_push( $this->ignored_section_content_types, $type );

		}else if( is_array( $type ) ){

			foreach( $type as $item ){

				if( !in_array( $item, $this->required_content_types ) )
					array_push( $this->required_content_types, $item );

				if( $section === false && !in_array( $item, $this->ignored_section_content_types ) )
				 	array_push( $this->ignored_section_content_types, $item );

			}

		}

	}

	public function get_required_content_types(){

		return $this->required_content_types;

	}

	public function get_ignored_section_content_types(){

		return $this->ignored_section_content_types;

	}

	public function add_filter( $name, $callback ){

		if( is_callable( $callback ) ){

			if( !isset( $this->add_filters[$name] ) || !is_array( $this->add_filters[$name] ) )
				$this->add_filters[$name] = array();

			$this->add_filters[$name][] = $callback;

		}
	}

	public function params_merge( $name ){

		if( !isset( $name ) || empty( $name ) || !isset( $this->maps[ $name ] ) )
			return array();

		$params = $this->maps[ $name ]['params'];
		$merge = array();

		if( isset( $params[0] ) ){

			return $params;

		}else{

			foreach( $params as $k => $v ){
				if( isset( $v[0] ) ){

					foreach( $v as $prm )
						array_push( $merge, $prm );
				}
			}

		}

		return $merge;

	}

	public function params_obj( $name ){

		if( !isset( $name ) || empty( $name ) || !isset( $this->maps[ $name ] ) )
			return array();

		$params = $this->maps[ $name ]['params'];
		$merge = array();

		if( isset( $params[0] ) ){

			foreach( $params as $k => $v ){
				$merge[$v['name']] = $v;
			}

		}else{

			foreach( $params as $k => $v ){
				if( isset( $v[0] ) ){

					foreach( $v as $p => $t )
						$merge[$t['name']] = $t;
				}
			}

		}

		return $merge;

	}

	public function js_callback( $func ){

		array_push( $this->live_js_callback,  array( 'callback' => $func ) );

	}

	public function esc( $str ) {

		if( empty( $str ) )
			return '';

	    return str_replace( array('<','>','[',']','"','\''), array( ':lt:', ':gt:', ':lsqb:', ':rsqb:', ':quot:', ':apos:' ), $str );
	}

	public function unesc( $str ){

		return str_replace( array( ':lt:', ':gt:', ':lsqb:', ':rsqb:', ':quot:', ':apos:' ), array('<','>','[',']','"','\''), $str );

	}

	public function user_can_edit( $post = null ){

		global $wp_the_query, $current_user;

		if( !isset( $post ) || empty( $post ) || $post === null )
			global $post;

		if (!is_admin() && (!isset($_GET['kc_action']) || $_GET['kc_action'] != 'live-editor')){
			$post = $wp_the_query->get_queried_object();
		}

		wp_get_current_user();

		if( isset($post) && is_object($post) && isset($post->ID) && isset($post->post_author) &&
			isset($current_user) && is_object($current_user) && isset($current_user->ID) &&
			(current_user_can('edit_others_posts', $post->ID) || ($post->post_author == $current_user->ID))
		){
			return true;
		}
		return false;

	}

	public static function is_live(){

		if( isset( $_GET['kc_action'] ) && $_GET['kc_action'] == 'live-editor' )
			return true;
		else return false;

	}

	public function secrect_storage( $key = '', $mode = '' ){

		if( empty( $key ) )
			return '';

		$kc_secrect_storage = get_option('kc_secrect_storage');

		if( $kc_secrect_storage === false ){
			add_option( 'kc_secrect_storage', array(), null, 'no' );
		}

		if( !is_array( $kc_secrect_storage ) )
			$kc_secrect_storage = array();

		if( $mode != 'hidden' ){

			foreach( $kc_secrect_storage as $secrect => $relate ){
				if( $relate == $key )
					return $secrect;
			}

			/*
			*	If the key has not been hidden yet
			*/

			$mode = 'encrypt';

		}

		if( $mode == 'encrypt' ){

			if( !isset( $kc_secrect_storage[$key] ) ){

				$relate_key = 'kc-secrect-'.rand(4564585,234523453456);
				$kc_secrect_storage[$key] = $relate_key;

				update_option( 'kc_secrect_storage', $kc_secrect_storage );

				return $relate_key;

			}else return $kc_secrect_storage[$key];
		}

	}

	public function enqueue_fonts(){

		$fonts = get_option('kc-fonts');
		$uri = '//fonts.googleapis.com/css?family=';

		if( !is_array( $fonts ) || count( $fonts ) === 0 )
			return;

		foreach( $fonts as $family => $cfg ){

			$params = urldecode( $family );
			$params = str_replace( ' ', '+', $params );

			if( isset( $cfg[3] ) ){
				$params .= ':'.$cfg[3];
			}else $params .= ':'.$cfg[1];

			if( isset( $cfg[2] ) )
				$params .= '&subset='.$cfg[2];
			else $params .= '&subset='.$cfg[0];

			$unique = strtolower( str_replace( ' ', '-', urldecode( $family ) ) );

			wp_enqueue_style( $unique, $uri.$params, false, KC_VERSION );

		}


	}

	public function verify( $code = '' ){

		if(!defined('KC_LICENSE') && strlen($code) == 41)
			define('KC_LICENSE', esc_attr($code));

	}

	public function kcp_remote( $code = '', $act = 'kcp_access' ){

		/*
		*	check valid code
		*/

		if (empty ($code) || strlen ($code) != 41)
			return false;
		/*
		*	prepare info
		*/

		$theme = sanitize_title( basename( get_template_directory() ) );
		$domain = str_replace( '=', '-d', base64_encode( site_url() ) );
		$url = $this->kcp_uri.$act.'&domain='.$domain.'&theme='.$theme.'&license='.$code;
		$date = time()+604800;

		/*
		*	create a request to kcp
		*/
		
		$request = @wp_remote_get($url);
		$response = @wp_remote_retrieve_body( $request );
		if (is_wp_error($request) || empty($response)) {
			$response = @file_get_contents($url);
		}
		
		$response = json_decode( $response, true );

		$data = array('pack'=>'trial', 'theme'=>$theme, 'domain'=>$domain, 'date'=>$date, 'key'=>$code, 'stt'=>0);

		/*
		*	merge with default
		*/
		foreach ($data as $i => $v)
		{
			if (isset ($response[$i]))
				$data[$i] = $response[$i];
		}
		/*
		*	storage
		*/
		if ($data['stt'] == 1)
		{
			if ($act == 'kcp_access')
			{
				if (get_option ('kc_tkl_pdk' ) === false)
					add_option ('kc_tkl_pdk', $data , null, 'no');
				else update_option ('kc_tkl_pdk', $data);
			}
			else if ($act == 'revoke_domain')
			{
				delete_option ('kc_tkl_pdk');
			}
		}

		return $data;

	}

	private function auto_verify(){

		if (defined('KC_LICENSE') && ( $this->pdk['pack'] == 'trial' || $this->check_pdk() != 1))
		{

			$key = KC_LICENSE;
			$time = time();

			/*
			*	if nonactivate + defined license key
			*/

			if (get_option('kc_license_log') === false)
			{
				/*
				*	storage log
				*/

				$kcp_log = array();
				add_option('kc_license_log', $kcp_log, null, 'no' );

			}else $kcp_log = get_option('kc_license_log');

			/*
			*	Make sure that do not sent too much request
			*/

			if (!isset( $kcp_log[$key] ) || ( $kcp_log[$key]['timer'] < $time && $kcp_log[$key]['counter'] < 10))
			{

				$data = $this->kcp_remote($key);

				if(!isset($kcp_log[$key]) || !is_array($kcp_log[$key])){

					$kcp_log[$key] = array( 'timer' => $time+180, 'counter' => 0 );

				}else{

					$kcp_log[$key]['timer'] = $time+180;
					$kcp_log[$key]['counter']++;

				}

				update_option('kc_license_log', $kcp_log);

			}
			else if( $kcp_log[$key]['timer'] < $time-(60*60*24*7) )
			{
				$kcp_log[$key]['timer'] = $time+300;
				$kcp_log[$key]['counter'] = 0;
			}

		}

	}

	public function check_pdk(){

		if( !isset( $this->pdk['pack'] ) || !isset( $this->pdk['date'] ) )
			return 0;
		else if( $this->pdk['date'] < time() ){
			if( $this->pdk['pack'] == 'trial' )
				return 3;
			else return 2;
		}else if( $this->pdk['date'] - time() > 604800 && $this->pdk['pack'] == 'trial' )
			return 3;

		return 1;

	}

	public function get_pdk(){

		return $this->pdk;

	}

	public function get_support_content_types(){

		$settings = $this->settings();

		if( !isset( $settings['content_types'] ) )
			$settings['content_types'] = array();

		$allows_types = array_merge( (array)$settings['content_types'], (array)$this->get_required_content_types() );

		if (count($this->prebuilt_templates) > 0) {
			array_unshift( $allows_types , 'prebuilt-templates-('.count($this->prebuilt_templates).')' );
		}
		return $this->apply_filters('kc_allows_types', $allows_types);
	}

	public function get_sidebars(){

		global $wp_registered_sidebars;
		$sidebars = array();

		if (isset($wp_registered_sidebars))
		{
			foreach ($wp_registered_sidebars as $name => $args)
			{
				$sidebars[$name] = $args['name'];
			}
		}

		return $sidebars;

	}

	public function plugin_active ($plugin = '') {
		 return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );
	}

	/*
	* Check value of an object
	*/

	public function is ($obj, $var, $val) {

		if (count((array)$obj) === 0)
			return false;

		$check = '';
		foreach ($var as $i) {
			if (isset($obj[$i]))
				$check = $obj[$i];
			else return false;
		}

		if ($check == $val)
			return true;
		else return false;

	}

	public function register_map_styles($styles) {
		return $styles+$this->map_styles;
	}

	public function register_map_scripts($scripts) {
		return $scripts+$this->map_scripts;
	}


	public function do_action($tag, $args){
		//some stuff to checking license
		do_action($tag, $args);
	}

	public function apply_filters($tag, $args){
		//some stuff to checking license
		return apply_filters($tag, $args);
	}

	public function default_image(){
		return $this->apply_filters('kc_default_image', KC_URL.'/assets/images/default.jpg');
	}

}

/*
*
*	Use magic method to autoload shortcode templates
*
*/

class kc_load_shortcodes{

	public function __call( $func, $params ){

		global $kc;

		$shortcode = $params[2];
        $content = str_replace( array('&#8221;', '&#8243;' ), array( '"', '"' ), $params[1] );
        $base = $shortcode.'.php';
        $atts = $kc->get_default_atts( $params );
        $path = $kc->get_template_path_extend( $base );

		$content = apply_filters( 'kc_shortcode_content', $content, $shortcode );

		if( isset( $atts['content'] ) && isset( $content ) && !empty( $content ) )
			$atts['content'] = $content;

		$atts = apply_filters( 'kc_shortcode_attributes', $atts, $shortcode );

		if( isset( $kc->shortcode_tags[$shortcode] ) && is_callable( $kc->shortcode_tags[$shortcode] ) ){
			return call_user_func( $kc->shortcode_tags[$shortcode], $atts, $content, $shortcode );
		}

        if( empty( $path ) )
	        $path = $kc->get_template_path( $base );

        if( !file_exists( $path ) ){
        	return __('KingComposer Error: could not find shortcode template: ', 'kingcomposer').get_template_directory().KDS.'kingcomposer'.KDS.$base.' <a href="http://docs.kingcomposer.com/display-the-output-of-the-shortcode/" target="_blank">Read More</a>';
		}

        ob_start();

			include $path;
			$content = ob_get_contents();

	    ob_end_clean();

        return $content;

    }

}

/************************/
	global $kc;
	$kc = new KingComposer();
	// Load kingcomposer core
	$kc->load();
/************************/

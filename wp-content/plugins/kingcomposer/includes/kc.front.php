<?php
/**
*
*	King Composer
*	(c) KingComposer.com
*
*/

if(!defined('ABSPATH')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}


class kc_front{

	public $KC_URL;
	public $storage = array();
	
	private $allows = null;
	private $scripts = array();
	private $styles = array();
	private $css = '';
	private $css_str = '';
	private $css_obj = array();
	private $css_str_master = '';
	private $css_obj_master = array();
	private $css_responsive = array();
	private $js = '';
	private $pattern_filter = '';
	private $tags_filter = array();
	private $action = null;
	private $content_master = true;
	private $prevent_infinite_loop = array();
	private $prevent_duplicate_ids = array();
	private $min = '.min';

	public function __construct(){

		$this->KC_URL = untrailingslashit( KC_URL ).'/assets/frontend/';

		if (defined('KC_DEVELOPMENT') && KC_DEVELOPMENT === true)
			$this->min = '';
		
		add_action( 'wp_enqueue_scripts', array( &$this, 'before_header' ), 9999 );
		add_action( 'wp_head', array( &$this, 'front_head' ), 999 );
		add_filter('body_class', array( &$this, 'body_classes' ) );
		add_filter('kc-el-class', array( &$this, 'el_class'));

		$icl_array = array(
			'helper.functions.php' =>  KC_PATH.'/includes/frontend/helpers/',
			'shortcodes.filters.php' =>  KC_PATH.'/includes/frontend/helpers/'
		);

		foreach( $icl_array as $file => $dir ) {

			if( file_exists( untrailingslashit($dir).KDS.$file ) )
				include untrailingslashit($dir).KDS.$file;

		}
		
		if (isset($_GET['kc_action']) && !empty($_GET['kc_action']))
			$this->action = sanitize_title($_GET['kc_action']);
		else if (isset($_POST['kc_action']) && !empty($_POST['kc_action']))
			$this->action = sanitize_title($_POST['kc_action']);
		
		if( $this->action == 'live-editor' )
			show_admin_bar(false);

	}
	
	public static function globe(){
		
		global $kc_front;
		
		if( isset( $kc_front ) )
			return $kc_front;
		else wp_die('KingComposer Error: Global varible could not be loaded.');
		
	}

	public function before_header(){

		// Get access of curent page
		// Return to $this->allows
		
		$this->register_assets();
		$this->load_scripts();
			
		if( $this->allowed_access() && kc_is_using()){
			
			$this->front_builder_load();
			
			global $post;
			
			if (isset($post) && !empty( $post->post_content_filtered))
			{
				 $post->post_content =  html_entity_decode( stripslashes_deep( $post->post_content_filtered ) );
			}
			
			$post->post_content = apply_filters('kc_raw_content', $post->post_content, $post->ID);
			
			if (isset($post))
			{
				$post_data = get_post_meta( $post->ID , 'kc_data', true );
				
				if (!empty($post_data) && $post_data['mode'] == 'kc')
				{
					$priority = has_filter( 'the_content', 'wpautop' );
					remove_filter('the_content', 'wpautop', $priority);
					$priority = has_filter( 'the_content', 'shortcode_unautop' );
					remove_filter('the_content', 'shortcode_unautop', $priority);
					$priority = has_filter( 'the_content', 'gutenberg_wpautop' );
					remove_filter('the_content', 'gutenberg_wpautop', $priority);
					$priority = has_filter( 'the_content', 'do_blocks' );
					remove_filter('the_content', 'do_blocks', $priority);
					
					$priority = has_filter( 'the_content', '_restore_wpautop_hook' );
					remove_filter( 'the_content', '_restore_wpautop_hook', $priority );
					$priority = has_filter( 'the_content', 'strip_dynamic_blocks' );
					remove_filter( 'the_content', 'strip_dynamic_blocks', $priority );
					
					
				}
			}
			
			$this->css_str = '';
			$this->css_obj = array();
			$this->prevent_infinite_loop = array();
			
			$post->post_content = '<div class="kc_clfw"></div>' . 
				$this->do_filter_shortcode( 
					apply_filters( 'kc-content-before', $post->post_content ), 
					true 
				);
			
			$live_footer = apply_filters('kc-content-after', '');
			
			global $wp_version;
			
			if (
				version_compare($wp_version, '5.0') >= 0 &&
				$live_footer !== '' && 
				strpos($live_footer, "data-content='") !== false &&
				isset($post_data) && 
				!empty($post_data) && 
				$post_data['mode'] == 'kc'
			) {
				$live_footer = explode("data-content='", $live_footer);
				for ($i=1; $i<count($live_footer); $i++) {
					$sc = substr($live_footer[$i], 0, strpos($live_footer[$i], "'"));
					$ex = substr($live_footer[$i], strpos($live_footer[$i], "'"));
					$live_footer[$i] = '[kc_raw_cos code="'.base64_encode(urlencode($sc)).'"]'.$ex;
				}
				$live_footer = implode("data-content='", $live_footer);
			}
			
			$post->post_content .= $live_footer;
			
			$post->kc_processed = true;
			$this->css_str_master = $this->css_str;
			$this->css_obj_master = $this->css_obj;

		}
	}

	public function add_filters(){
	
		global $kc;
		
		if( is_array( $kc->add_filters ) ){
			
			foreach( $kc->add_filters as $name => $filters ){
				
				if( is_array( $filters ) ){
					
					foreach( $filters as $callback ){
						if( is_callable( $callback ) ){
							add_filter( 'shortcode_'.$name, $callback );
						}
					}
					
				}
			}
			
		}

	}

	public function front_builder_load(){
		
		global $kc, $kc_pro, $post;
		
		$content = trim( $post->post_content );
		
		if( $this->action == 'live-editor' ){

			if( $kc->user_can_edit() === false )
				wp_die('<strong>King Composer</strong><br /><br />You do not have permission to edit this page. <a href="'.admin_url().'">Please  login</a> or edit <a href="'.admin_url('edit.php?post_type=page').'">the pages</a> that you have the permission.</p>');

			foreach( $this->scripts as $script )
				wp_enqueue_script( $script );
			
			//masonry enqueue
			wp_enqueue_script( 'masonry' );
			
			
			foreach( $this->styles as $style )
				wp_enqueue_style( $style );
			
			if( isset( $kc_pro ) && is_callable( array( &$kc_pro, 'bottom_builder' ) ) )
				add_filter( 'kc-content-after', array( &$kc_pro, 'bottom_builder' ) );
			
		}
	
	}
	
	public function do_filter_shortcode( $content, $is_master = true ){
		
		global $shortcode_tags;
		
		$this->tags_filter = array();
		$this->content_master = $is_master;
		
		$content = preg_replace_callback( '@\[([^<>&/\[\]\x00-\x20]++)@', array( &$this, 'do_shortcode_alter' ), $content );
		$tagnames = array_intersect( array_keys( $shortcode_tags ), $this->tags_filter );

		if ( empty( $tagnames ) )
			return $content;

		$pattern_filter = get_shortcode_regex( $tagnames );
		
		return preg_replace_callback( "/$pattern_filter/", array( &$this, 'do_shortcode_tag' ), $content );

	}
	
	public function do_shortcode_alter( $m ){

		$al = preg_replace( "/[^\#]/", '', $m[1] );

		if( !empty( $al ) )
			$m[0].= ' __="'.$al.'"';
		else
			array_push( $this->tags_filter, $m[1] );

		return $m[0];

	}

	public function do_shortcode_tag( $m ){

		if ( $m[1] == '[' && $m[6] == ']' )
	        return substr($m[0], 1, -1);
		
		global $kc;
		
	    $tag =  $m[2];
	    $maps = $kc->get_maps($tag);
		$params = $kc->params_obj($tag);
		$css_code = '';	
		
		$atts = (array)$this->shortcode_parse_atts($m[3]);

		$closed = substr($m[0], strlen($m[0]) - strlen($tag) - 3);

		// If this shortcode has been disabled
		if (isset($atts['disabled']) && $atts['disabled'] == 'on' && $this->action != 'live-editor')
			return '';
		
		/*
		*	Row is link to section	
		*/
		
		if ($tag == 'kc_row' && isset( $atts['__section_link']))
		{
			
			if (!isset( $this->prevent_infinite_loop[ $atts['__section_link'] ]))
			{
			
				$this->prevent_infinite_loop[ $atts['__section_link'] ] = true;
				
				$is_master = $this->content_master;
				
				$section = kc_raw_content($atts['__section_link']);
				$section_meta = get_post_meta($atts['__section_link'] , 'kc_data', true);

				if (!empty($section_meta) && !empty($section_meta['css']))
					$this->css .= $section_meta['css'];
				
				if (!empty($section))
					$section = $this->do_filter_shortcode( $section, false );
				else
					$section = '<div class="kc-infinite-loop">'.__('Section content is empty, please edit section to add content', 'kingcomposer').'</div>';
				/*
				*	Set back primary
				*/
				$this->content_master = $is_master;
				
				/*
				*	unset to work for next seciton link
				*/
				unset( $this->prevent_infinite_loop[ $atts['__section_link'] ] );
	
			}
			else
			{
				
				$section = '<div class="kc-infinite-loop">'.__('KingComposer fatal error occurred: Infinite loop when trying to include section','kingcomposer').'</div>';
					
			}
			
			if ($this->action == 'live-editor')
			{
				
				$atts['content'] = '';
				$model = count( $this->storage );
				$storage = array( 
					'args' => $atts, 
					'name' => $tag,
					'content' => '',
					'end' => '[/'.$tag.']', 
					'full' => $m[0]
				);

				$this->storage[ $model ] = $storage;
			
				$section = '<!--kc s '.$model.'-->'.trim($section).'<!--kc e '.$model.'-->';
				
				/*
				*	Add to 
				*/
				
			}
				
			return $section;
			
		}
		
		/*
		*	Render id for each element
		*/
		
		if( !isset( $atts['_id'] ) || empty( $atts['_id'] ) || in_array( $atts['_id'], $this->prevent_duplicate_ids) )
			$atts['_id'] = rand(23035, 4362247);
		/*
		*	Make sure the id of elements is unique
		*/
		array_push( $this->prevent_duplicate_ids, $atts['_id'] );
		
		$atts['_css'] = array();
							
		// Move all custom css to header css
		
		if( isset( $atts['css'] ) ){

			$strs = explode( '|', $atts['css'] );

			if( isset( $strs[1] ) && !empty( $strs[1] ) )
				$strs = explode( ';', $strs[1] );
			else if( !empty( $strs[0] ) )
				$strs = explode( ';', $strs[0] );
			
			foreach( $strs as $str ){
				$str = explode( ':', $str );
				if( !empty($str[0]) && isset($str[1]) && !empty($str[1]) )
					$atts['_css'][] = '`'.$str[0].'`:`'.$str[1].'`';	
			}
			
			unset( $atts['css'] );

		}
		
		// Process width for columns
		if( isset( $atts['width'] ) && strpos($atts['width'], '%') !== false )
			$atts['_css'][] = '`width`:`'.esc_attr($atts['width']).'`';

		if( count( $atts['_css'] ) > 0 ){
			$css_code .= $this->render_element_css( '{`kc-css`:{`1000-5000`:{`group`:{'.esc_attr(implode( ',', $atts['_css'] )).'}}}}', $atts['_id'] );
			unset( $atts['_css'] );
		}

		if (is_array( $atts ))
		{
			
			foreach($atts as $k => $v)
			{
				
				/*
				*	@since ver 2.5 
				*	Process fields have the type is css
				*/
				
				if( ( isset($params[ $k ]) && is_array( $params[ $k ] ) && $params[ $k ]['type'] == 'css' ) || strpos( $k, '_css_inspector' ) === 0 ){
					
					$css_code .= $this->render_element_css( $v, $atts['_id'] );
					
				}else if( is_string( $v ) ){
					if( $k == '__empty__' )
						$atts[$k] = '';
					else $atts[$k] = $kc->unesc( $v );
				}

			}
			
			if( $css_code !== '' )
				$this->css_str .= '/*s'.$atts['_id'].'*/'.$css_code.'/*e'.$atts['_id'].'*/';
			
			unset( $atts['_css'] );
			
		}

		$atts['__name'] = $tag;

		// add # for name of container
		if( isset( $atts['__'] ) ){
			$atts['__name'] .= $atts['__'];
			unset( $atts['__'] );
		}

		if( $closed == '[/'.esc_attr( $tag ).']' ){

			if ( isset( $m[5] ) && !empty( $m[5] ) )
				$atts['__content'] = $this->do_filter_shortcode( str_replace( $tag.'#', $tag, $m[5] ), $this->content_master );
			else
				$atts['__content'] = '';

		}

		$new_atts = '';
		$new_atts = apply_filters( 'shortcode_'.$tag, $atts );
		
		if( !is_array( $new_atts ) )
			$new_atts = $atts;
			
		if ($maps !== false && isset($maps['assets']) && is_array($maps['assets'])) {
			if (isset($maps['assets']['styles']) && is_array($maps['assets']['styles'])) {
				foreach($maps['assets']['styles'] as $key => $url)
					wp_enqueue_style($key);
			}
			if (isset($maps['assets']['scripts']) && is_array($maps['assets']['scripts'])) {
				foreach($maps['assets']['scripts'] as $key => $url){
					wp_enqueue_script($key);
				}
			}
		}
		
		return $m[1] . $this->filter_return( $new_atts ) .$m[6];

	}

	public function shortcode_parse_atts($text) {
		
	    $atts = array();
	    $pattern = '/([a-zA-Z0-9\-\_\.]+)="([^"]+)+"/';
	    $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
	    if ( preg_match_all($pattern, $text, $match, PREG_SET_ORDER) ) {
            foreach ($match as $m) {
                if (!empty($m[1]))
                        $atts[strtolower($m[1])] = stripcslashes($m[2]);
                elseif (!empty($m[3]))
                        $atts[strtolower($m[3])] = stripcslashes($m[4]);
                elseif (!empty($m[5]))
                        $atts[strtolower($m[5])] = stripcslashes($m[6]);
                elseif (isset($m[7]) && strlen($m[7]))
                        $atts[] = stripcslashes($m[7]);
                elseif (isset($m[8]))
                        $atts[] = stripcslashes($m[8]);
            }
            // Reject any unclosed HTML elements
            foreach( $atts as &$value ) {
                if ( false !== strpos( $value, '<' ) ) {
                        if ( 1 !== preg_match( '/^[^<]*+(?:<[^>]*+>[^<]*+)*+$/', $value ) ) {
                                $value = '';
                        }
                }
            }
	    } else {
	            $atts = ltrim($text);
	    }
	    return $atts;
	}

	public function filter_return( $atts ){
	
		global $kc;
		
		$full = '['.$atts['__name'];
		$maps = $kc->get_maps();
		$pure_name = str_replace( '#', '', $atts['__name'] );
		
		foreach( $atts as $k => $v ){
			if( $k != '__name' && $k != '__content' )
				$full .= ' '.$k.'="'.esc_attr($v).'"';
		}

		$full .= ']';
		
		if (in_array($pure_name, array('kc_column', 'kc_column_inner')) || 
			in_array($pure_name, $kc->maps_view) ||
			(isset($maps[$pure_name]['nested']) && $maps[$pure_name]['nested'] === true)
		) $is_nested = true;
		else $is_nested = false;
		
		if (isset($atts['__content']) || $is_nested){

			$full .= isset($atts['__content']) ? $atts['__content'] : '';
			
			if( $this->action == 'live-editor' && $this->content_master === true && $is_nested){
				
				$full .= '<div class="kc-element drag-helper" data-model="-1"><a href="javascript:void(0)" class="kc-add-elements-inner"><i class="sl-plus kc-add-elements-inner"></i></a></div>';
				
			}	
			
			$full .= '[/'.$atts['__name'].']';
		}

		if( $this->action == 'live-editor' && $this->content_master === true ){
			
			if( isset( $atts['__name'] ) )
				$atts['__name'] = explode( '#', $atts['__name'] );
				
			if( isset( $atts['__content'] ) ){
				$atts['content'] = preg_replace( '/<!--(.*)-->/Uis', '', $atts['__content'] );
				unset( $atts['__content'] );
			}
				
			$model = count( $this->storage );
			$storage = array( 'args' => $atts, 'name' => $atts['__name'][0], 'full' => preg_replace( '/<!--(.*)-->/Uis', '', $full ) );
			
			if( isset( $atts['content'] ) )
				$storage['end'] = '[/'.$storage['name'].']';
			
			$this->storage[ $model ] = $storage;
			
			$full = '<!--kc s '.$model.'-->'.trim($full).'<!--kc e '.$model.'-->';
			
		}
		
		return $full;

	}
	
	public function render_element_css( $code, $id ){
		
		global $kc;
		
		$css_code = '';
		$css_any_code = '';
		$css_desktop_code = '';
		$pro_maps = array( 
			'margin' => array('margin-top','margin-right','margin-bottom','margin-left'), 
			'padding' => array('padding-top','padding-right','padding-bottom','padding-left'), 
			'border-radius' => array('border-top-left-radius','border-top-right-radius','border-bottom-right-radius','border-bottom-left-radius')
		);
			
		try{
			
			/*
			*	Decode JSON object
			*/		
			$screens = json_decode( str_replace( '`', '"', $code ), true );
			/*
			*	Sort screens
			*/
			if (is_array( $screens['kc-css']))
			{
				
				kc_screen_sort ($screens['kc-css']);

				foreach ($screens['kc-css'] as $screen => $groups)
				{
				
					$css_array = array(); 
					$css_code_itm = '';
					
					foreach ($groups as $group => $properties)
					{
						foreach ($properties as $sel => $css)
						{
							$sel = explode( '|', $sel );
							
							if ($sel[0] == 'gap')
								$prefix = '';
							else $prefix = 'body.kc-css-system ';
							
							if (!empty( $sel[1]))
							{
								$_sel = explode(',', $sel[1]);
								$selector = array();
								
								foreach ($_sel as $__sel)
								{
									/*
									*	add spacing for selector which is not :hover
									*/			
									
									$__sel = $kc->unesc($__sel);
									
									if (strpos( trim($__sel), '+') === 0)
										$__sel = substr(trim($__sel), 1);
									else if (strpos( trim($__sel), ':') !== 0)
										$__sel = ' '.trim($__sel);
										
									$selector[] = $prefix.'.kc-css-'.$id.$__sel;
								}
								
								$selector = implode (',', $selector);
							
							}
							else if ($sel[0] == 'gap')
							{
								// set low piorit for gap padding
								$selector = '#page .kc-css-'.$id;
							}
							else
							{
								$selector = $prefix.'.kc-css-'.$id;
							}
							
							$gap_selector = $prefix.'.kc-css-'.$id.'>.kc-wrap-columns';
							
							// group properties with same selector into one
								 
							if (!isset($css_array[ $selector ]))
								$css_array[ $selector ] = array();
							
							if (!isset($css_array[$gap_selector]))
								$css_array[ $gap_selector ] = array();
							
							if (isset($pro_maps[$sel[0]]) && strpos($css, 'inherit') !== false)
							{
								$css = explode(' ', $css);
								for ($i=0; $i<4; $i++)
								{
									if (!empty($css[$i]) && trim($css[$i]) != 'inherit')
									{
										if (isset($css[4]))
											$css[$i] .= ' '.$css[4];
											
										array_push( $css_array[ $selector ], $pro_maps[$sel[0]][$i].': '.$css[$i] );
										
									}
								}
									
							}
							else
							{
								if ($sel[0] == 'gap')
								{
									if( intval($css) < 0 )
										$css = '0px';
										
									array_push( $css_array[ $selector ], 'padding-left: '.$css.';padding-right: '.$css );
									array_push( $css_array[ $gap_selector ], 'margin-left: -'.$css.';margin-right: -'.$css.';width: calc(100% + '.(intval($css)*2).'px)' );
								
								}
								else if($sel[0] == 'border')
								{
									
									if (strpos( $css, '|') !== false)
									{	
										$css_line = '';
										
										$css = explode('|', $css);
										$bmap = array('top', 'right', 'bottom', 'left');
										
										for( $cj=0; $cj<4; $cj++ ){
											if( isset( $css[ $cj ] ) && !empty( $css[ $cj ] ) )
												$css_line .= 'border-'.$bmap[$cj].': '.$css[$cj].';';
										}
										
										array_push( $css_array[ $selector ], $css_line );
										
									}else array_push( $css_array[ $selector ], $sel[0].': '.$css );
								
									
								}else if( $sel[0] == 'custom' ){
									
									$css = trim( str_replace( array('"', "'", '[', ']'), array('', '', '', ''), $css ) ).'{{{end}}}';
									
									$css = str_replace( array(';{{{end}}}', '{{{end}}}'), array('', ''), $css );
										
									array_push( $css_array[ $selector ], $css );
								
								}else if( $sel[0] == 'background' ){
									
									$css_obj = array( 
										'color' => 'transparent', 
										'linearGradient' => array('',''), 
										'image' => 'none', 
										'position' => '0% 0%', 
										'size' => 'auto', 
										'repeat' => 'repeat', 
										'attachment' => 'scroll', 
										'advanced' => 0 
									); $val = ''; $imp = '';
									
									if (strpos( $css, ' !important' ) !== false) {
										$imp = ' !important';
										$css = str_replace( ' !important', '', $css);
									}
									
									$json = base64_decode( $css );
									$json = json_decode( $json, true );
									
									if (is_array( $json ))
									{
									
										$css_obj = array_merge( $css_obj, $json );
										
										if ($css_obj['linearGradient'][0] !== '')
										{
											if (strpos($css_obj['linearGradient'][0], 'deg') !== false)
											{
												if (isset($css_obj['linearGradient'][1]) && !empty($css_obj['linearGradient'][1]))
												{
													if (!isset($css_obj['linearGradient'][2]) || empty($css_obj['linearGradient'][2]))
													{
														$css_obj['linearGradient'][2] = $css_obj['linearGradient'][1];
													}
												}
											}
											else if (!isset($css_obj['linearGradient'][1]) || empty($css_obj['linearGradient'][1]))
												$css_obj['linearGradient'][1] = $css_obj['linearGradient'][0];
											
											$css_obj['linearGradient'] = implode(', ', $css_obj['linearGradient']);
											$css_obj['linearGradient'] = str_replace(', ,', ', ', $css_obj['linearGradient']);
											
											$val .= 'linear-gradient('.$css_obj['linearGradient'].')';
											
										}
										
										if ($css_obj['color'] != 'transparent' && $css_obj['color'] !== '')
										{
											if( $val == '' )
												$val .= $css_obj['color'];
											else $val .= ', '.$css_obj['color'];
										}
										
										if ($css_obj['image'] != 'none' && $css_obj['image'] != '')
										{
											
											if( $val == '' )
												$val .= $css_obj['color'];
											else if( $css_obj['color'] == 'transparent' || $css_obj['color'] === '' )
												$val .= ', transparent';
											
											$val .= ' url('.$css_obj['image'].') '.$css_obj['position'].'/'.$css_obj['size'].' '.$css_obj['repeat'].' '.$css_obj['attachment'];
										}
										if (!empty($val))
											array_push( $css_array[ $selector ], $sel[0].': '.$val . $imp );
									
									}
									else if(!empty($css))
									{
										array_push( $css_array[ $selector ], $sel[0].': '.$css . $imp);
									}
									
								}else array_push( $css_array[ $selector ], $sel[0].': '.$css );
									
							}
							
						}
					}
					
					foreach( $css_array as $sel => $pros ){
						if( !empty( $pros ) ){
							$css_code_itm .= $sel.'{'.str_replace( array('{','}'), array('',''), implode( ';', $pros )).';}';
						}
					}
					
					if ($screen != 'any')
					{
						
						if( strpos( $screen, '-' ) === false ){
							$css_code .= '@media only screen and (max-width: '.trim($screen).'px){'.$css_code_itm.'}';
						}else{
							$screenx = explode('-', $screen);
							$css_code .= '@media only screen and (min-width: '.trim($screenx[0]).'px) and (max-width: '.trim($screenx[1]).'px){'.$css_code_itm.'}';
						}
						
					}else{
						$css_any_code .= $css_code_itm;
					}
					
					if( !isset( $this->css_obj[ $screen ] ) || !is_array( $this->css_obj[ $screen ] ) )
						$this->css_obj[ $screen ] = array();
					
					// Group all properties in the same screen
					$this->css_obj[ $screen ][] = $css_code_itm;
					
				}
			
			}
			
		}catch( Exception $e ){
			 echo "\n\n/*Caught exception: ",  $e->getMessage(), "*/\n\n";
		};
		
		return kc_images_filter($css_any_code.$css_code);
		
	}
	
	public function front_head(){

		if( $this->allows ){

			echo '<script type="text/javascript">'.$this->render_dynamic_js().'</script>';
			$this->render_dynamic_css();
			
		}
	}

	public function register_assets() {
	
	
		$this->register_style('prettyPhoto', $this->vendor_script_url('prettyPhoto/css','prettyPhoto.css'));
		$this->register_style('owl-theme', $this->vendor_script_url('owl-carousel','owl.theme.css'));
		$this->register_style('owl-carousel', $this->vendor_script_url('owl-carousel','owl.carousel.css'));
		
		$styles = apply_filters( 'kc_register_styles', array() );
		if( is_array( $styles ) && count( $styles ) ){
			foreach( $styles as $sid => $url ){
				if (!empty($url)) $this->register_style( $sid, $url );
			}
		}
		
		#Register vonder scripts

		$this->register_script('owl-carousel', $this->vendor_script_url('owl-carousel','owl.carousel'.$this->min.'.js'));
		$this->register_script('countdown-timer', $this->vendor_script_url('countdown','jquery.countdown'.$this->min.'.js'));
		$this->register_script('easypie-chart', $this->KC_URL. 'js/easypie.chart'.$this->min.'.js');
		$this->register_script('waypoints', $this->vendor_script_url('waypoints','waypoints.min.js'));
		$this->register_script('counter-up', $this->KC_URL. 'js/counter.up.min.js');

		$this->register_script('kc-youtube-api', 'https://www.youtube.com/iframe_api');
		$this->register_script('kc-vimeo-api', 'https://f.vimeocdn.com/js/froogaloop2.min.js');
		$this->register_script('kc-video-play', $this->KC_URL . 'js/video.play'.$this->min.'.js');

		//lightbox script have to add latest
		$this->register_script('prettyPhoto', $this->vendor_script_url('prettyPhoto/js','jquery.prettyPhoto.js') );
		
		$scripts = apply_filters( 'kc_register_scripts', array() );

		if( is_array( $scripts ) && count( $scripts ) ){
			foreach( $scripts as $sid => $url ){
				if (!empty($url)) $this->register_script( $sid, $url );
			}
		}

	}

	public function load_scripts(){

		global $kc;
		$settings = $kc->settings();
		
		/*
		*	enqueue fonts from general settings
		*/
		$kc->enqueue_fonts();
		
		$styles = array(
			'kc-general' => array(
				'src'     => $this->KC_URL.'css/kingcomposer'.$this->min.'.css',
				'deps'    => '',
				'version' => KC_VERSION,
				'media'   => 'all'
			)
		);
		
		if (!isset($settings['animate']) || $settings['animate'] != 'disabled')
		{
			$styles['kc-animate'] = array(
				'src'     => untrailingslashit(KC_URL).'/assets/css/animate.css',
				'deps'    => '',
				'version' => KC_VERSION,
				'media'   => 'all'
			);
		}
		
		if( $this->action == 'live-editor' ){
			
			$styles['kc-backend-builder'] = array(
				'src'     => str_replace( array( 'http:', 'https:' ), '', untrailingslashit( KC_URL ) ) . 
							 '/assets/css/kc.builder.css',
				'deps'    => '',
				'version' => KC_VERSION,
				'media'   => 'all'
			);
		}
		
		$icon_sources = $kc->get_icon_sources();
		if( is_array( $icon_sources ) && count( $icon_sources ) > 0 ){
			$i = 1;
			foreach( $icon_sources as $icon_source ){
				$styles['kc-icon-'.$i++] = array(
					'src'     => $icon_source,
					'deps'    => '',
					'version' => KC_VERSION,
					'media'   => 'all'
				);
			}
		}

		foreach ( apply_filters( 'kc_enqueue_styles', $styles ) as $handle => $args ) {
			wp_enqueue_style( $handle, $args['src'], $args['deps'], $args['version'], $args['media'] );
		}
		
		$js_path = $this->KC_URL . 'js/kingcomposer'.$this->min.'.js';
		
		$scripts = array(
			'kc-front-scripts' => $js_path
		);
		
		foreach ( apply_filters( 'kc_enqueue_scripts', $scripts ) as $uid => $url ) {
			$this->enqueue_script( $uid, $url );
		}

	}
	
	public function el_class( $atts ){
		
		global $kc;
		$settings = $kc->settings();
		$el_class = array('kc-elm');
		
		if (!empty($atts['css']))
			$el_class[] = $atts['css'];
		
		if (!empty($atts['_id']))
			$el_class[] = 'kc-css-'.$atts['_id'];
		
		if (isset($atts['width']))
			$el_class[] = kc_column_width_class($atts['width']);	
		
		if (!isset($settings['animate']) || $settings['animate'] != 'disabled')
		{
			if (isset($atts['animate']) && !empty($atts['animate']))
			{
				$ani = explode('|', $atts['animate']);
				if (isset($ani[0]) && !empty($ani[0]))
					$el_class[] = 'kc-animated kc-animate-eff-'.esc_attr($ani[0]);	
				if (isset($ani[1]) && !empty($ani[1]))
					$el_class[] = 'kc-animate-delay-'.esc_attr($ani[1]);	
				if (isset($ani[2]) && !empty($ani[2]))
					$el_class[] = 'kc-animate-speed-'.esc_attr($ani[2]);
			}
		}
		
		return $el_class;
		
	}
	
	public function do_shortcode( $content ){
		
		//$this->css_str = '';
		$this->css_obj = array();
		$this->prevent_infinite_loop = array();
		
		$html = $this->do_filter_shortcode( $content, false );

		return '<style type="text/css">'.$this->render_css( $this->css_obj ).'</style>'.do_shortcode( $html );
		
	}
	
	public function render_css( $obj ){
		
		$any = ''; $css = ''; $item = '';
		
		if( is_array( $obj ) ){
			
			kc_screen_sort( $obj );

			foreach( $obj as $screen => $properties ){
				
				$item = '';
				
				if( $screen == 'any' ){
					$any .= implode('', $properties);
				}else{
					
					if( strpos( $screen, '-' ) === false ){
						$item .= '@media only screen and (max-width: '.trim($screen).'px){'.implode('', $properties).'}';
					}else{
						$screen = explode('-', $screen);
						$item .= '@media only screen and (min-width: '.trim($screen[0]).'px) and (max-width: '.trim($screen[1]).'px){'.implode('', $properties).'}';
					}
					
					if (is_array($screen))
						$screen = implode('-', $screen);
					
					if ($screen == '1000-5000')
						$any = $item.$any;
					else $css .= $item;
					
				}
			}
			
		}
		
		return kc_images_filter($any.$css);
		
	}
	
	public function body_classes( $classes ) {

		global $post;

		if( !empty( $post->ID ) )
		{
			$post_data = get_post_meta( $post->ID , 'kc_data', true );

			if( !empty( $post_data['classes'] ) )
				$classes[] = $post_data['classes'];
		}
        return $classes;

	}

	public function vendor_script_url($vendor_dir, $srcipt_file){
		return untrailingslashit(KC_URL).'/includes/frontend/vendors/'.$vendor_dir.'/'.$srcipt_file;
	}

	public function register_script( $handle, $path, $deps = array( 'jquery' ), $version = KC_VERSION, $in_footer = true ) {
		$this->scripts[] = $handle;
		wp_register_script( $handle, $path, $deps, $version, $in_footer );
	}

	public function register_style( $handle, $path, $deps = array(), $version = KC_VERSION, $media = 'all' ) {
		$this->styles[] = $handle;
		wp_register_style( $handle, $path, $deps, $version, $media );
	}

	public function enqueue_script( $handle, $path = '', $deps = array( 'jquery' ), $version = KC_VERSION, $in_footer = true ) {
		
		if ( ! in_array( $handle, $this->scripts ) && $path ) {
			$this->register_script( $handle, $path, $deps, $version, $in_footer );
		}
		wp_enqueue_script( $handle );
	}

	private function allowed_access(){

		global $kc;

		$settings = $kc->settings();


		if( !isset( $settings['content_types'] ) )
			$settings['content_types'] = array();

		$content_types = array_merge( (array)$settings['content_types'], (array)$kc->get_required_content_types() );

		$this->allows = is_singular( $content_types );
		
		$this->allows = apply_filters('kc_allows', $this->allows);
		
		return $this->allows;

	}

	private function render_dynamic_js(){
		if( !empty( $this->js ) )
			printf( $this->js );
	}

	public function add_header_js( $js = '' ){
		if( !empty( $js ) )
			$this->js .= $js;
	}

	public function add_header_css( $css = '' ){
		if( !empty( $css ) )
			$this->css .= $css;
	}

	public function add_header_css_responsive( $screen = '', $css = '' ){

		if( !empty( $screen ) && !empty( $css ) ){

			if( !isset( $this->css_responsive[ $screen ] ) )
				$this->css_responsive[ $screen ] = array();

			array_push( $this->css_responsive[ $screen ], $css );

		}
	}

	private function render_dynamic_css(){

		global $post, $kc;
		
		$post_id = apply_filters('kc_get_dynamic_css', $post->ID);
		$post_data = get_post_meta ($post_id , 'kc_data', true);
		$settings = $kc->settings();

		if (!empty($post_data) && !empty($post_data['css']))
			$this->css .= $post_data['css'];
			
		if (!empty( $settings['css_code']))
			$this->css .= $settings['css_code'];
		
		if (!empty($post_data) && isset($post_data['max_width']) && !empty($post_data['max_width']))
			$this->css .= '.kc-container{max-width: '.esc_attr($post_data['max_width']).';}';
		else if (!empty($settings['max_width']) && isset($settings['max_width']) && !empty($settings['max_width']))
			$this->css .= '.kc-container{max-width: '.esc_attr($settings['max_width']).';}';
		
		$this->css = esc_html ($this->css);
		$this->css = str_replace(
					array( "\n","  ", ": ", " {", "  ", '&gt;', '&lt;', '&quot;', '&#039;', "</style>", "<style", "<script", "</script"),
					array( '', ' ', ':', '{', " ", '>', '<', '"', "'", "&lt;/style&quot;", "&lt;style", "&lt;script", "&lt;/script"),
					$this->css
				);
		
		$this->css = kc_images_filter($this->css);
					
		echo '<style type="text/css" id="kc-css-general">.kc-off-notice{display: inline-block !important;}'.$this->css.'</style>';
		
		/*
		*	Start render CSS of all elements
		*/
		
		if ($this->action == 'live-editor')
			$this->css = $this->css_str;
		else
			$this->css = $this->render_css ($this->css_obj_master);
		
		$this->css = str_replace(
					array( "\n","  ", ": ", " {", "  ", '&gt;', '&lt;', '&quot;', '&#039;', "</style>", "<style", "<script", "</script"),
					array( '', ' ', ':', '{', " ", '>', '<', '"', "'", "&lt;/style&quot;", "&lt;style", "&lt;script", "&lt;/script"),
					$this->css
				);
				
		$this->css = kc_images_filter($this->css);
		echo '<style type="text/css" id="kc-css-render">'.$this->css.'</style>';


	}

	public function preg_match_css( $matches ){

		if( !empty( $matches[1] ) ){

			if( strpos( $matches[1], '|' ) !== false ){

				$class = substr( $matches[1], 0, strpos( $matches[1], '|' ) );
				if( strpos( $this->css, '.'.$class.'{' ) === false )
				{
					$this->css .= '.'.$class.'{'.substr( $matches[1], strpos( $matches[1], '|' ) + 1 ).'}';
				}
				return ' css="'.$class.'"';
			}
			else
			{
				$this->css .= $matches[1];
				return '';
			}
		}
		else return $matches[0];

	}
	
	public function get_tags_filter(){
		return $this->tags_filter;
	}
	
	public function get_global_css(){
		return $this->css = kc_images_filter($this->css);
	} 
	
}

/*
*-------------------------------
*/

global $kc_front;
$kc_front = new kc_front();

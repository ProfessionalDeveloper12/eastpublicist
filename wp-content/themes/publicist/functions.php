<?php
/**
 * Theme functions and definitions
*/

/* Include Core */
require get_template_directory() . '/include/inc.php';

/* Include Admin */
require get_template_directory() . '/admin/inc.php';

/* ************************************************************************ */

if( !function_exists('publicist_get_the_ID') ) :

	function publicist_get_the_ID() {

		if( is_home() && get_option( 'page_for_posts' ) != "0" ) {
			$post_id = get_option( 'page_for_posts' );
		}
		elseif( class_exists( 'WooCommerce' ) && wc_get_page_id('shop') != "-1" && is_shop() ) {
			$post_id = wc_get_page_id('shop');
		}
		else {
			$post_id = get_the_ID();
		}

		return ! empty( $post_id ) ? $post_id : false;
	}
endif;

/* ************************************************************************ */

/* Redux Options */
if( !function_exists('publicist_options') ) :

	function publicist_options( $option, $arr = null ) {

		global $publicist_option;

		if( $arr ) {

			if( isset( $publicist_option[$option][$arr] ) ) {
				return $publicist_option[$option][$arr];
			}
		}
		else {
			if( isset( $publicist_option[$option] ) ) {
				return $publicist_option[$option];
			}
		}
	}

endif;

/* ************************************************************************ */

if( ! function_exists('publicist_add_allowed_tags') ) {

	function publicist_add_allowed_tags( $tags ) {
	
		$tags['h1'] = array( 'class' => array(), 'style' => array() );
		$tags['h2'] = array( 'class' => array(), 'style' => array() );
		$tags['h3'] = array( 'class' => array(), 'style' => array() );
		$tags['h4'] = array( 'class' => array(), 'style' => array() );
		$tags['h5'] = array( 'class' => array(), 'style' => array() );
		$tags['h6'] = array( 'class' => array(), 'style' => array() );
		$tags['em'] = array( 'class' => array(), 'style' => array() );
		$tags['li'] = array( 'class' => array(), 'style' => array() );
		$tags['ul'] = array( 'class' => array(), 'style' => array() );		
		$tags['ol'] = array( 'class' => array(), 'style' => array() );
		$tags['p'] = array( 'class' => array(), 'style' => array() );
		$tags['span'] = array( 'class' => array(), 'style' => array() );
		$tags['ins'] = array( 'datetime' => array() );
		$tags['img'] = array( 'class' => array(), 'src' => array(), 'alt' => array(), 'style' => array() );
		$tags['a'] = array( 'class' => array(), 'href' => array(), 'target' => array(), 'title' => array(), 'style' => array() );
	
		return $tags;
	}
	add_filter('wp_kses_allowed_html', 'publicist_add_allowed_tags');
}

/* ************************************************************************ */

/**
 * Set up the content width value based on the theme's design.
 *
 * @see publicist_content_width()
 *
 * @since Publicist 1.0
 */
if ( ! isset( $content_width ) ) { $content_width = 474; }


/**
 * Adjust content_width value for image attachment template.
 *
 * @since Publicist 1.0
 */
if( !function_exists('publicist_content_width') ) :

	function publicist_content_width() {
		if ( is_attachment() && wp_attachment_is_image() ) { $GLOBALS['content_width'] = 810; }
	}
	add_action( 'template_redirect', 'publicist_content_width' );
endif;

/* ************************************************************************ */

/**
 * Theme setup.
 *
 * Set up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support post thumbnails.
 *
 * @since Publicist 1.0
 */
if( !function_exists('publicist_theme_setup') ) :

	function publicist_theme_setup() {

		/* load theme languages */
		load_theme_textdomain( "publicist", get_template_directory() . '/languages' );

		/* Image Sizes */
		set_post_thumbnail_size( 730, 374, true ); /* Default Featured Image */
		add_image_size( 'publicist_1920_275', 1920, 275, true  ); /* Post width No Sidebar */	
		add_image_size( 'publicist_1110_568', 1110, 568, true  ); /* Post width No Sidebar */	
		add_image_size( 'publicist_730_374', 730, 374, true  ); /* Post width No Sidebar */	
		
		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus( array(
			'publicist_primary'   => esc_html__( 'Primary Menu', "publicist" ),
		) );

		add_theme_support( 'automatic-feed-links' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );

		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
		
		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array( 'video', 'gallery', 'audio' ) );
	}
	add_action( 'after_setup_theme', 'publicist_theme_setup' );
endif;

/* ************************************************************************ */

/* Google Font */
if( !function_exists('publicist_fonts_url') ) :

	function publicist_fonts_url() {

		$fonts_url = '';

		$montserrat = _x( 'on', 'Montserrat font: on or off', "publicist" );
	
		if ( 'off' !== $montserrat ) {

			$font_families = array(); 
			
			if ( 'off' !== $montserrat ) {
				$font_families[] = 'Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i';
			}

			$query_args = array(
				'family' => urlencode( implode( '|', $font_families ) ),
				'subset' => urlencode( 'latin,latin-ext' ),
			);

			$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
		}

		return esc_url_raw( $fonts_url );
	}
endif;

/* ************************************************************************ */

/**
 * Enqueue scripts and styles for the front end.
 *
 * @since Publicist 1.0
 */
if( !function_exists('publicist_enqueue_scripts') ) :

	function publicist_enqueue_scripts() {

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		/* Google Font */
		if( function_exists('publicist_fonts_url') ) :
			wp_enqueue_style( 'publicist-fonts', publicist_fonts_url() );
		endif;
		
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css');
		wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css');
		wp_enqueue_style( 'elements', get_template_directory_uri() . '/assets/css/elements.css');
		wp_enqueue_style( 'header', get_template_directory_uri() . '/assets/css/header.css');
		wp_enqueue_style( 'publicist-stylesheet', get_template_directory_uri() . '/style.css' );
		
		wp_enqueue_script( 'popper', get_template_directory_uri() . '/assets/js/popper.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/assets/js/modernizr-min.js', array( 'jquery' ) );
		wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'sticky-menu', get_template_directory_uri() . '/assets/js/sticky-menu.js', array( 'jquery' ) );
		
		wp_enqueue_script( 'publicist-functions', get_template_directory_uri() . '/assets/js/functions.js', array( 'jquery' ) );
	
		/* Logo Image Width and height Desktop*/
		$logow = "";
		$logow = publicist_options('opt_logo_size',"width");
		
		$logoh = "";
		$logoh = publicist_options('opt_logo_size',"height");
		$logoh_s = "";
		if( $logoh != "" && $logoh != "px") {
			$logoh_s = 'max-height: '.$logoh.';';
		}

		$logow_s = "";
		if( $logow != "" && $logow != "px" ) {
			$logow_s = 'max-width: '.$logow.';';
		}
		$logohw = "";
		if($logoh != "" && $logoh != "px" || ( $logow != "" && $logoh != "px" ) ) {
			$logohw = '.header_s a.image-logo img { '.$logoh_s.$logow_s.' } ';
		}
		
		/* Logo Image Width and height Responsive */
		
		$reslogow = "";
		$reslogow = publicist_options('opt_reslogo_size',"width");
		
		$reslogoh = "";
		$reslogoh = publicist_options('opt_reslogo_size',"height");
		$reslogoh_s = "";
		if( $reslogoh != "" && $reslogoh != "px") {
			$reslogoh_s = 'max-height: '.$reslogoh.';';
		}

		$reslogow_s = "";
		if( $reslogow != "" && $reslogow != "px" ) {
			$reslogow_s = 'max-width: '.$reslogow.';';
		}
		$reslogohw = "";
		if($reslogoh != "" && $reslogoh != "px" || ( $reslogow != "" && $reslogoh != "px" ) ) {
			$reslogohw = '.header_s a.image-logo img { '.$reslogoh_s.$reslogow_s.' } ';
		}
	
		/* Page Header Top Space */
		$tcss = "";
		$topph = publicist_options("opt_pageheader_topheight");
		
		if( $topph != "" ) {
			$tcss = '.page-banner{ padding-top:'.$topph.'px; }';
		}
		
		/* Page Header Bottom Space */
		$btmcss = "";
		$btmph = publicist_options("opt_pageheader_bottomheight");
		if( $btmph != "" ) {
			$btmcss = '.page-banner{ padding-bottom:'.$btmph.'px; }';
		}
		
		/* Custom CSS */
		$custom_css = "
			@media (min-width: 992px) {
				".$tcss.$btmcss.$logohw."
			}
			@media (max-width: 991px) {
				".$reslogohw."
			}
		";
		wp_add_inline_style( 'publicist-stylesheet', $custom_css );
	}
	add_action( 'wp_enqueue_scripts', 'publicist_enqueue_scripts' );
endif;

/* ************************************************************************ */
/**
 * Extend the default WordPress body classes.
 *
 * @since Publicist 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
if( !function_exists('publicist_body_classes') ) :

	function publicist_body_classes( $classes ) {

		if ( is_singular() && ! is_front_page() ) {
			$classes[] = "singular";
		}

		if( is_front_page() && !is_home() ) {
			$classes[] = "front-page";
		}

		if( is_404() ) {
			$classes[] = "404-template";
		}

		return $classes;
	}
	add_filter( 'body_class', 'publicist_body_classes' );

endif;

/* ************************************************************************ */

/**
 * Extend the default WordPress post classes.
 *
 * Adds a post class to denote:
 * Non-password protected page with a post thumbnail.
 *
 * @since Publicist 1.0
 *
 * @param array $classes A list of existing post class values.
 * @return array The filtered post class list.
 */
if( !function_exists('publicist_post_classes') ) :

	function publicist_post_classes( $classes ) {
		if ( ! is_attachment() && has_post_thumbnail() ) { $classes[] = 'has-post-thumbnail'; }
		return $classes;
	}
	add_filter( 'post_class', 'publicist_post_classes' );

endif;

/* ************************************************************************ */

if( class_exists("kingcomposer") ) {
	
	function publicist_templates_init(){
		global $kc;
		$kc->set_template_path( get_template_directory().'/kc_templates/' );
	}
	add_action('init', 'publicist_templates_init', 99 );
}

/* ************************************************************************ */

function publicist_get_excerpt( $count ) {
	$excerpt = get_the_content();
	$excerpt = strip_tags( $excerpt );
	$excerpt = substr( $excerpt, 0, $count );
	$excerpt = substr( $excerpt, 0, strripos( $excerpt, " " ) );
	$excerpt = $excerpt.' [...]';
	return $excerpt;
}

/* ************************************************************************ */

if( !function_exists('publicist_clean_excerpt') )  {

	function publicist_clean_excerpt( $excerpt ) {

		$patterns = "/\[[\/]?vc_[^\]]*\]/";
		$replacements = "";

		return preg_replace($patterns, $replacements, $excerpt);
	}
}

/* ************************************************************************ */

if( !function_exists('publicist_excerpt') ) {

	function publicist_excerpt($excerpt_length = 25) {

		global $post;
	 
		$word_count =  "";	 
		$word_count = isset( $word_count ) && $word_count != "" ? $word_count : $excerpt_length;

		$post_excerpt = $post->post_excerpt != "" ? $post->post_excerpt : strip_tags( $post->post_content ); $clean_excerpt = strpos( $post_excerpt, '[...]' ) ? strstr( $post_excerpt, '[...]', true ) : $post_excerpt;

		if( $clean_excerpt != "" ) {

			$clean_excerpt = strip_shortcodes( publicist_clean_excerpt($clean_excerpt) );

			$excerpt_all = explode(' ',$clean_excerpt );
			$excerpt_words = array_slice( $excerpt_all, 0, $word_count );

			$excerpt = implode(' ', $excerpt_words );

			$trim_excerpt = trim( $excerpt );

			$cnt = count( $excerpt_all );

			$excpt = "";

			if( $trim_excerpt != "" ) {

				$excpt .= $trim_excerpt;
				
				if( $cnt > $word_count ) {
					$excpt .= ' [...]';
				}
			}
			echo wp_kses( $excpt, array() );
		}
	}
}

/* ************************************************************************ */
if( !function_exists ('wpb_set_post_views') ) {
	
	function wpb_set_post_views($postID) {
		
		$count_key = 'wpb_post_views_count';
		
		$count = get_post_meta($postID, $count_key, true);
		
		if($count==''){
			
			$count = 0;
			
			delete_post_meta($postID, $count_key);
			
			add_post_meta($postID, $count_key, '0');
			
		}
		else{
			
			$count++;
			
			update_post_meta($postID, $count_key, $count);
		}
	}
}
if( !function_exists('wpb_track_post_views') ) {
	
	function wpb_track_post_views ($post_id) {
		
		if ( !is_single() ) {
			return;
		}
		
		if ( empty ( $post_id) ) {

			$post_id = get_the_ID();    
		}
		wpb_set_post_views($post_id);
	}
	add_action( 'wp_head', 'wpb_track_post_views');
}

if( !function_exists('wpb_get_post_views') ) {
	
	function wpb_get_post_views($postID){
		
		$count_key = 'wpb_post_views_count';
		
		$count = get_post_meta($postID, $count_key, true);
		
		if($count==''){
			
			delete_post_meta($postID, $count_key);
			
			add_post_meta($postID, $count_key, '0');
			
			return esc_html__("0 View","publicist");
		}

		return $count.' Views';

	}
}

if( !function_exists('publicist_widget_title') ) {
	
	function publicist_widget_title($title) {
		$temp = explode(' ', $title);
		if( isset($temp[1]) ) {
			$temp[1] = '<span>' . $temp[1] . '</span>';
			$title = implode(' ', $temp);
		}
		return $title;
	}
	add_filter('widget_title', 'publicist_widget_title');
} 

function add_author_custom_meta_box() {
	add_meta_box(
		'author_custom_meta_box', // $id
		'Custom Author Title', // $title
		'show_author_custom_meta_box', // $callback
		'post', // $screen
		'normal', // $context
		'high' // $priority
	);
}
add_action( 'add_meta_boxes', 'add_author_custom_meta_box' );

function show_author_custom_meta_box() {
	global $post;
	$meta = get_post_meta( $post->ID, 'author_custom', true );
	?>
	<input type="hidden" name="ow_metabox_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">
	<!-- All fields will go here -->
	<p>
    	<input placeholder="Custom Author Title" type="text" name="author_custom[text]" id="author_custom[text]" class="regular-text" value="<?php echo $meta['text']; ?>">
    </p>
	<?php
}
function save_author_custom_meta( $post_id ) {
	// verify nonce
	if ( !wp_verify_nonce( $_POST['ow_metabox_nonce'], basename(__FILE__) ) ) {
		return $post_id;
	}
	// check autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}
	// check permissions
	if ( 'page' === $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		} elseif ( !current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
	}
	$old = get_post_meta( $post_id, 'author_custom', true );
	$new = $_POST['author_custom'];
	if ( $new && $new !== $old ) {
		update_post_meta( $post_id, 'author_custom', $new );
	} elseif ( '' === $new && $old ) {
		delete_post_meta( $post_id, 'author_custom', $old );
	}
}
add_action( 'save_post', 'save_author_custom_meta' );
?>
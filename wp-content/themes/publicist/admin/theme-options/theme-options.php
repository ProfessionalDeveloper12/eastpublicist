<?php
/**
 * ReduxFramework Sample Config File
 * For full documentation, please visit: http://docs.reduxframework.com/
 */

if ( ! class_exists( 'Redux' ) ) {
	return;
}

// This is your option name where all the Redux data is stored.
$opt_name = "publicist_option";

// This line is only for altering the demo. Can be easily removed.
$opt_name = apply_filters( 'publicist_option/opt_name', $opt_name );

/**
 * ---> SET ARGUMENTS
 * All the possible arguments for Redux.
 * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
 * */

$theme = wp_get_theme(); // For use with some settings. Not necessary.

$args = array(
	// TYPICAL -> Change these values as you need/desire
	'opt_name'             => $opt_name,
	// This is where your data is stored in the database and also becomes your global variable name.
	'display_name'         => $theme->get( 'Name' ),
	// Name that appears at the top of your panel
	'display_version'      => $theme->get( 'Version' ),
	// Version that appears at the top of your panel
	'menu_type'            => 'menu',
	//Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
	'allow_sub_menu'       => true,
	// Show the sections below the admin menu item or not
	'menu_title'           => esc_html__( 'Theme Options', "publicist" ),
	'page_title'           => esc_html__( 'Theme Options', "publicist" ),
	// You will need to generate a Google API key to use this feature.
	// Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
	'google_api_key'       => '',
	// Set it you want google fonts to update weekly. A google_api_key value is required.
	'google_update_weekly' => false,
	// Must be defined to add google fonts to the typography module
	'async_typography'     => true,
	// Use a asynchronous font on the front end or font string
	//'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
	'admin_bar'            => true,
	// Show the panel pages on the admin bar
	'admin_bar_icon'       => 'dashicons-portfolio',
	// Choose an icon for the admin bar menu
	'admin_bar_priority'   => 50,
	// Choose an priority for the admin bar menu
	'global_variable'      => '',
	// Set a different name for your global variable other than the opt_name
	'dev_mode'             => false,
	// Show the time the page took to load, etc
	'update_notice'        => true,
	// If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
	'customizer'           => true,
	// Enable basic customizer support
	//'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
	//'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

	// OPTIONAL -> Give you extra features
	'page_priority'        => null,
	// Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
	'page_parent'          => 'themes.php',
	// For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
	'page_permissions'     => 'manage_options',
	// Permissions needed to access the options panel.
	'menu_icon'            => '',
	// Specify a custom URL to an icon
	'last_tab'             => '',
	// Force your panel to always open to a specific tab (by id)
	'page_icon'            => 'icon-themes',
	// Icon displayed in the admin panel next to your menu_title
	'page_slug'            => '',
	// Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
	'save_defaults'        => true,
	// On load save the defaults to DB before user clicks save or not
	'default_show'         => false,
	// If true, shows the default value next to each field that is not the default value.
	'default_mark'         => '',
	// What to print by the field's title if the value shown is default. Suggested: *
	'show_import_export'   => true,
	// Shows the Import/Export panel when not used as a field.

	// CAREFUL -> These options are for advanced use only
	'transient_time'       => 60 * MINUTE_IN_SECONDS,
	'output'               => true,
	// Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
	'output_tag'           => true,
	// Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
	// 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

	// FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
	'database'             => '',
	// possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
	'use_cdn'              => true,
	// If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

	// HINTS
	'hints'                => array(
		'icon'          => 'el el-question-sign',
		'icon_position' => 'right',
		'icon_color'    => 'lightgray',
		'icon_size'     => 'normal',
		'tip_style'     => array(
			'color'   => 'red',
			'shadow'  => true,
			'rounded' => false,
			'style'   => '',
		),
		'tip_position'  => array(
			'my' => 'top left',
			'at' => 'bottom right',
		),
		'tip_effect'    => array(
			'show' => array(
				'effect'   => 'slide',
				'duration' => '500',
				'event'    => 'mouseover',
			),
			'hide' => array(
				'effect'   => 'slide',
				'duration' => '500',
				'event'    => 'click mouseleave',
			),
		),
	)
);

Redux::setArgs( $opt_name, $args );

// If Redux is running as a plugin, this will remove the demo notice and links
add_action( 'redux/loaded', 'publicist_remove_demo' );

Redux::setSection( $opt_name, array(
	'title'      => esc_html__( 'General Settings', "publicist" ),
	'icon'         => 'fa fa-cogs',
	'id'         => 'general_settings',
	'subsection' => false,
	'fields'     => array(

	),
));

/* Social Icon */
Redux::setSection( $opt_name, array(
	'title'      => esc_html( 'Social Icons', "publicist" ),
	'icon'         => 'fa fa-share-alt',
	'id'         => 'social_icons',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'info_social_icon',
			'type'     => 'info',
			'title'    => esc_html__( 'Social Icons', "publicist" ),
		),
		array(
			'id'       => 'opt_icon_onoff',
			'type'     => 'switch',
			'title'    => esc_html__( 'Enable/Disable', "publicist" ),
			'default'  => "1",
		),
		
		array(
			'id'             => 'opt_social_icons',
			'type'           => 'ow_repeater',
			'textOne'        => true,
			'image'          => false,
			'title'          => esc_html__( 'Social Icon Entries', "publicist" ),
			'subtitle'       => __( '<u>Here you can use css class like following :</u><br><br>- Elegant Icons ( "<b>social_facebook</b>" )<br>- Stroke Gap ( "<b>icon icon-Like</b>" )<br>- Font Awesome ( "<b>fab fa-facebook</b>" )', "publicist" ),
			'placeholder'    => array(
				'textOne'  => "Font Icon CSS Class",
			),
			'required' => array( 'opt_icon_onoff', '=', '1' )
		),
	),
));

/* Page Header */
Redux::setSection( $opt_name, array(
	'title'      => esc_html__( 'Page Header', "publicist" ),
	'icon'         => 'fa fa-credit-card-alt',
	'id'         => 'page_header',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'opt_pageheader_img',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__( 'Page Header Image( Default )', "publicist" ),
			'default'  => array( 'url' => esc_url( PUBLICIST_IMGURI )  . '/background-header.jpg' ),
		),
		array(
			'id'       => 'opt_pageheaderbg_overlay',
			'type'     => 'color_rgba',
			'title'   => esc_html__( 'Background Overlay', "publicist" ),
			'subtitle' => esc_html__( 'Pick a color.', "publicist" ),
			'options' => array(
				'show_alpha_only'    => true
			),
			'output'   => array( "background-color" => ".page-banner:before" ),
		),
		
		array(
			'id'        => 'opt_pageheader_topheight',
			'type'      => 'slider',
			'title'     => esc_html__('Page Header Top Height', "publicist" ),
			"default"   => 180,
			"min"       => 50,
			"step"      => 1,
			"max"       => 300,
			'display_value' => 'label'
		),
		array(
			'id'        => 'opt_pageheader_bottomheight',
			'type'      => 'slider',
			'title'     => esc_html__('Page Header Bottom Padding', "publicist" ),
			"default"   => 180,
			"min"       => 50,
			"step"      => 1,
			"max"       => 300,
			'display_value' => 'label'
		),
	),
));

/* Layout Settings */
Redux::setSection( $opt_name, array(
	'title'      => esc_html__( 'Layout Settings', "publicist" ),
	'icon'         => 'fa fa-desktop',
	'id'         => 'layout_settings',
	'fields'     => array(
		
	),
));

/* Body Layout */
Redux::setSection( $opt_name, array(
	'title'      => esc_html__( 'Body Layout', "publicist" ),
	'icon'         => 'fa fa-desktop',
	'id'         => 'body_layout',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'info_layout_body',
			'type'     => 'info',
			'title'    => esc_html__( 'Body Layout', "publicist" ),
		),
		array(
			'id'       => 'layout_body',
			'type'     => 'image_select',
			'icon'     => 'fa fa-tasks',
			'title'    => esc_html__( 'Body Layout', "publicist" ),
			'options'  => array(
				'fixed' => array(
					'alt' => 'Boxed',
					'img' => esc_url( PUBLICIST_IMGURI ) . '/layout/boxed.png'
				),
				'fluid' => array(
					'alt' => 'Full',
					'img' => esc_url( PUBLICIST_IMGURI ) . '/layout/full.png'
				),
			),			
			'default'  => 'fixed'
		),
		array(
			'id'       => 'info_sidebar_layout',
			'type'     => 'info',
			'title'    => esc_html__( 'Sidebar Layout', "publicist" ),
		),
		array(
			'id'       => 'layout_sidebar',
			'type'     => 'image_select',
			'icon'     => 'fa fa-tasks',
			'title'    => esc_html__( 'Sidebar Settings', "publicist" ),
			'options'  => array(
				'right_sidebar' => array(
					'alt' => 'Right Sidebar',
					'img' => esc_url( PUBLICIST_IMGURI ) . '/layout/right_side.png'
				),
				'left_sidebar' => array(
					'alt' => 'Left Sidebar',
					'img' => esc_url( PUBLICIST_IMGURI ) . '/layout/left_side.png'
				),
				'no_sidebar' => array(
					'alt' => 'No Sidebar',
					'img' => esc_url( PUBLICIST_IMGURI ) . '/layout/none.png'
				),
			),			
			'default'  => 'right_sidebar'
		),
	),
));

/* Header/Footer */
Redux::setSection( $opt_name, array(
	'title'      => esc_html__( 'Header/Footer', "publicist" ),
	'icon'         => 'fa fa-archive',
	'id'         => 'site_headerfooter',
	'fields'     => array(
	),
));

/* Header */
Redux::setSection( $opt_name, array(
	'title'      => esc_html__( 'Header', "publicist" ),
	'icon'         => 'fa fa-credit-card',
	'subsection' => true,
	'id'         => 'site_header',
	'fields'     => array(
	
		array(
			'id'       => 'info_sticky',
			'type'     => 'info',
			'title'    => esc_html__( 'Sticky Menu', "publicist" ),
		),
		array(
			'id'       => 'opt_sticky_menu',
			'type'     => 'switch',
			'title'    => esc_html__( 'Sticky Menu', "publicist" ),
			'default'  => "1",
		),
		array(
			'id'       => 'info_cntinfo',
			'type'     => 'info',
			'title'    => esc_html__( 'Top Header', "publicist" ),
		),		
		array(
			'id'       => 'opt_top_header',
			'type'     => 'switch',
			'title'    => esc_html__( 'Top Header', 'publicist' ),
			'default'  => "1",
		),
		/* Header Search */
		array(
			'id'       => 'info_hdr_search',
			'type'     => 'info',
			'title'    => esc_html__( 'Search', "publicist" ),
			'required' => array( 'opt_top_header', '=', '1' )
		),		
		array(
			'id'       => 'opt_hdr_search',
			'type'     => 'switch',
			'title'    => esc_html__( 'Top Header Search', 'publicist' ),
			'default'  => "1",
			'required' => array( 'opt_top_header', '=', '1' )
		),
		
		/* Logo */
		array(
			'id'       => 'info_sitelogo',
			'type'     => 'info',
			'notice' => true,
			'style' => 'critical',
			'icon' => 'fa fa-cube',
			'title'    => esc_html__( 'Site Logo', "publicist" ),
			'subtitle' => esc_html__( 'Choose Logo Type', "publicist" ),
		),
		array(
			'id'             => 'opt_logo_size',
			'type'           => 'dimensions',
			'units'          => array( 'px' ),    // You can specify a unit value. Possible: px, em, %
			'height'         => true,
			'units_extended' => 'true',  // Allow users to select any type of unit
			'title'          => esc_html__( 'Logo Option', "publicist" ),
			'required' => array( 'opt_logotype', '=', '2' ),
		),
		
		array(
			'id'             => 'opt_reslogo_size',
			'type'           => 'dimensions',
			'units'          => array( 'px' ),    // You can specify a unit value. Possible: px, em, %
			'height'         => true,
			'units_extended' => 'true',  // Allow users to select any type of unit
			'title'          => esc_html__( 'Responsive Logo Option', "publicist" ),
			'required' => array( 'opt_logotype', '=', '2' ),
		),
		
		array(
			'id'       => 'opt_logotype',
			'type'     => 'select',
			'title'    => esc_html__( 'Logo Type', "publicist" ),
			'options'  => array(
				'1' => 'Site Title',
				'2' => 'Image',
				'3' => 'Custom Text',
			),
			'default'  => '3',
		),
		array(
			'id'=>'opt_logoimg',
			'type' => 'media',
			'title' => esc_html__('Logo Upload', "publicist" ),
			'required' => array( 'opt_logotype', '=', '2' ),
			'default'  => array( 'url' => esc_url( PUBLICIST_IMGURI ) . 'images/logo.png' ),
		),
		array(
			'id'=>'opt_customtxt',
			'type' => 'text',
			'title' => esc_html__('Custom Text', "publicist" ),
			'required' => array( 'opt_logotype', '=', '3' ),
			'default'  => "The Publicist East Africa"
		),
	),
));

/* Footer */
Redux::setSection( $opt_name, array(
	'title'      => esc_html__( 'Footer', "publicist" ),
	'icon'         => 'fa fa-window-maximize',
	'id'         => 'site_footer',
	'subsection' => true,
	'fields'     => array(
		
		array(
			'id'       => 'info_newsletter',
			'type'     => 'info',
			'title'    => esc_html__( 'Newsletter', "publicist" ),
		),
		
		array(
			'id'       => 'opt_news_onoff',
			'type'     => 'switch',
			'title'    => esc_html__( 'Newsletter Enable/Disable', "publicist" ),
			'default'  => "1",
		),
		
		array(
			'id'       => 'opt_news_bgimg',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__( 'Newsletter Background Image( Default )', "publicist" ),
			'default'  => array( 'url' => esc_url( PUBLICIST_IMGURI )  . '/news-bg.jpg' ),
			'required' => array( 'opt_news_onoff', '=', '1' )
		),
		
		array(
			'id'       => 'opt_newsletter_title',
			'type'     => 'text',
			'title'    => esc_html__( 'Newsletter Title', "publicist" ),
			'default'  => esc_html__('Newsletter Subscription',"publicist"),
			'required' => array( 'opt_news_onoff', '=', '1' )
		),
		
		array(
			'id'       => 'opt_newsletter_subtitle',
			'type'     => 'text',
			'title'    => esc_html__( 'Newsletter Sub Title', "publicist" ),
			'default'  => esc_html__('Enter Your email address to get regular email updates',"publicist"),
			'required' => array( 'opt_news_onoff', '=', '1' )
		),
		array(
			'id'       => 'opt_newsletter_id',
			'type'     => 'text',
			'title'    => esc_html__( 'Newsletter Form ID', "publicist" ),
			'default'  => esc_html__('37 ',"publicist"),
			'description' => esc_html__( 'Newsletter Shortcode id. Go To MailChimp form id, for example: 37', 'publicist' ),
			'required' => array( 'opt_news_onoff', '=', '1' )
		),
	
		/* Copyright Text */
		array(
			'id'       => 'opt_footer_copyright',
			'type'     => 'editor',
			'title'    => esc_html__( 'Copyright Text', "publicist" ),
			'subtitle' => esc_html__( 'Use any of the features of WordPress editor inside your panel!', "publicist" ),
			'default'  => '&copy;[year]. <a href="#">The Publicist East Africa</a>. All Rights Reserved.',
			 'args'   => array(
				'teeny'            => true,
				'textarea_rows'    => 10
			),
		),
	),
));

/* Other Pages */
Redux::setSection( $opt_name, array(
	'title'      => esc_html__( 'Other Pages', "publicist" ),
	'icon'         => 'el el-file',
	'id'         => 'other_pages',
	'fields'     => array(),
));

/* Archive Page */
Redux::setSection( $opt_name, array(
	'title'      => esc_html__( 'Archive Page', "publicist" ),
	'icon'         => 'fa fa-commenting-o',
	'id'         => 'blog_post',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'info_readmore',
			'type'     => 'info',
			'title'    => esc_html__( 'Read More Text', "publicist" ),
		),
		
		/* Listing Page Read More Text */
		array(
			'id'       => 'opt_readmore_text',
			'type'     => 'text',
			'title'    => esc_html__( 'Post Read More Button Text', "publicist" ),
			'default'  => esc_html__('Read More',"publicist"),
		),
		
		array(
			'id'       => 'info_excerpt_length',
			'type'     => 'info',
			'title'    => esc_html__( 'Excerpt Length', "publicist" ),
		),
		
		/* Listing Page Excerpt Length */
		array(
			'id'        => 'opt_excerpt_length',
			'type'      => 'slider',
			'title'     => esc_html__('Increase/Decrease Post Excerpt Length', 'publicist'),
			"default"   => 25,
			"min"       => 1,
			"step"      => 1,
			"max"       => 55,
			'display_value' => 'label'
		),
		
		array(
			'id'       => 'info_archive_meta',
			'type'     => 'info',
			'title'    => esc_html__( 'Post Meta', "publicist" ),
		),		
		array(
			'id'       => 'opt_archive_meta',
			'type'     => 'switch',
			'title'    => esc_html__( 'Enable/Disable', 'publicist' ),
			'default'  => "1",
		),
	),
));

/* Single Post */
Redux::setSection( $opt_name, array(
	'title'      => esc_html__( 'Single Post', "publicist" ),
	'icon'         => 'fa fa-commenting-o',
	'id'         => 'single_post',
	'subsection' => true,
	'fields'     => array(
	
		array(
			'id'       => 'info_post_meta',
			'type'     => 'info',
			'title'    => esc_html__( 'Post Meta', "publicist" ),
		),		
		array(
			'id'       => 'opt_single_meta',
			'type'     => 'switch',
			'title'    => esc_html__( 'Enable/Disable', 'publicist' ),
			'default'  => "1",
		),

		array(
			'id'       => 'info_tags',
			'type'     => 'info',
			'title'    => esc_html__( 'Tags', "publicist" ),
		),		
		array(
			'id'       => 'opt_post_tag',
			'type'     => 'switch',
			'title'    => esc_html__( 'Tags Enable/Disable', 'publicist' ),
			'default'  => "1",
		),
		
		array(
			'id'       => 'info_categories',
			'type'     => 'info',
			'title'    => esc_html__( 'Categories', "publicist" ),
		),		
		array(
			'id'       => 'opt_post_categories',
			'type'     => 'switch',
			'title'    => esc_html__( 'Categories Enable/Disable', 'publicist' ),
			'default'  => "1",
		),
		
	),
));

/* 404 Page */
Redux::setSection( $opt_name, array(
	'title'      => esc_html__( '404 Page', "publicist" ),
	'icon'         => 'fa fa-exclamation-triangle',
	'id'         => 'page_error',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'opt_error_title',
			'type'     => 'text',
			'title'    => esc_html__( 'Error Title', "publicist" ),
			'default'  => esc_html__('404',"publicist"),
		),
		array(
			'id'       => 'opt_error_subtitle',
			'type'     => 'text',
			'title'    => esc_html__( 'Error Sub Title', "publicist" ),
			'default'  => esc_html__('Oops! The page You are searching was not found',"publicist"),
		),
	),
));

/* Admin Login */
Redux::setSection( $opt_name, array(
	'title'      => esc_html__( 'Admin Login Page', "publicist" ),
	'icon'         => 'fa fa-lock',
	'id'         => 'page_admin',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'opt_adminlogo',
			'type'     => 'media',
			'title'    => esc_html__( 'Logo Image', "publicist" ),
		),
		array(
			'id'       => 'opt_adminbg_color',
			'type'     => 'color',
			'title'    => esc_html__( 'Background Color', "publicist" ),
		),
		array(
			'id'       => 'opt_adminbg_img',
			'type'     => 'media',
			'title'    => esc_html__( 'Background Image', "publicist" ),
		),
		array(
			'id'       => 'opt_admincolor',
			'type'     => 'color',
			'title'    => esc_html__( 'Text Color', "publicist" ),
		),
	),
));

/* Typography Css */
Redux::setSection( $opt_name, array(
	'title'      => esc_html__( 'Typography', "publicist" ),
	'icon'         => 'fa fa-text-height ',
	'id'         => 'site_typography',
	'subsection' => false,
	'fields'     => array(
		array(
			'id'       => 'info_body_font',
			'type'     => 'info',
			'title'    => esc_html__( 'Body Font Settings', "publicist" ),
		),
		array(
			'id'          => 'opt_body_font',
			'type'        => 'typography', 
			'title'       => esc_html__('Body Style', "publicist"),
			'google'      => true, 
			'font-backup' => false,
			'subsets'      => false,
			'text-align'      => false,
			'line-height'      => false,
			'output'      => array('body'),
			'units'       =>'px',
			'subtitle'    => esc_html__('Body Style', "publicist"),
		),
		array(
			'id' => 'notice_critical11',
			'type' => 'info',
			'notice' => true,
			'style' => 'critical',
			'icon' => 'fa fa-font',
			'title' => esc_html__('H1 to H6 Styling', "publicist"),
			'subtitle' => esc_html__('Typography settings H1 to H6 Tags',"publicist"),
		),
		array(
			'id' => 'h1-font',
			'type' => 'typography',
			'title' => esc_html__('H1', 'publicist'),
			'subtitle' => esc_html__('Specify the Heading font properties.', "publicist"),
			'google' => true,
			'text-align' =>false,
			'output' => 'h1',
		),
		array(
			'id' => 'h2-font',
			'type' => 'typography',
			'title' => esc_html__('H2', 'publicist'),
			'subtitle' => esc_html__('Specify the Heading font properties.',"publicist"),
			'google' => true,
			'text-align' =>false,
			'output' => 'h2',
		),
		array(
			'id' => 'h3-font',
			'type' => 'typography',
			'title' => esc_html__('H3', 'publicist'),
			'subtitle' => esc_html__('Specify the Heading font properties.',"publicist"),
			'google' => true,
			'text-align' =>false,
			'output' => 'h3',
		),
		array(
			'id' => 'h4-font',
			'type' => 'typography',
			'title' => esc_html__('H4', 'publicist'),
			'subtitle' => esc_html__('Specify the Heading font properties.', "publicist"),
			'google' => true,
			'text-align' =>false,
			'output' => 'h4',
		),
		array(
			'id' => 'h5-font',
			'type' => 'typography',
			'title' => esc_html__('H5', 'publicist'),
			'subtitle' => esc_html__('Specify the Heading font properties.', "publicist"),
			'google' => true,
			'text-align' =>false,
			'output' => 'h5',
		),
		array(
			'id' => 'h6-font',
			'type' => 'typography',
			'title' => esc_html__('H6', 'publicist'),
			'subtitle' => esc_html__('Specify the Heading font properties.', "publicist"),
			'google' => true,
			'text-align' =>false,
			'output' => 'h6',
		),
	),
));

/**
 * Removes the demo link and the notice of integrated demo from the redux-framework plugin
 */
if ( ! function_exists( 'publicist_remove_demo' ) ) {
	function publicist_remove_demo() {
		// Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
		if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
			remove_filter( 'plugin_row_meta', array(
				ReduxFrameworkPlugin::instance(),
				'plugin_metalinks'
			), null, 2 );

			// Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
			remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
		}
	}
}
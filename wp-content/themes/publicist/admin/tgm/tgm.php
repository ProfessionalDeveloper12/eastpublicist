<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1 for parent theme
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
*/

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once get_template_directory() . '/admin/tgm/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'publicist_register_required_plugins' );
function publicist_register_required_plugins() {

	$plugins = array(

		array(
			'name'               => esc_html__('KingComposer', 'publicist'), // The plugin name.
			'slug'               => 'kingcomposer', // The plugin slug (typically the folder name).
			'required'           => true,
		),
		array(
			'name'               => esc_html__('CMB2', 'publicist'), // The plugin name.
			'slug'               => 'cmb2', // The plugin slug (typically the folder name).
			'required'           => true,
		),
		array(
			'name'               => esc_html__('Redux Framework', 'publicist'), // The plugin name.
			'slug'               => 'redux-framework', // The plugin slug (typically the folder name).
			'required'           => true,
		),
		array(
			'name'               => esc_html__('MailChimp for WordPress', 'publicist'), // The plugin name.
			'slug'               => 'mailchimp-for-wp', // The plugin slug (typically the folder name).
			'required'           => true,
		),
		array(
			'name'               => esc_html__('Events Manager', 'publicist'), // The plugin name.
			'slug'               => 'events-manager', // The plugin slug (typically the folder name).
			'required'           => true,
		),	
	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'publicist',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',               // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}
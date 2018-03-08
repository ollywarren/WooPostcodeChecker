<?php

/**
 * Class Boot
 * Handles the bootstrapping for the plugin
 *
 * @package     WooPostcodeChecker\Boot
 * @author      Olly Warren <olly@ollywarren.com>
 * @copyright   2018 Olly Warren
 * @version     1.0.0
 */

namespace WooPostcodeChecker;

// If this file is access directly lets kill ourselves!
if ( ! defined( 'WPINC' ) ) {
    die;
}

class Boot {

	/**
	 * On_boot
	 *
	 * Setup of the plugin when it boots.
	 *
	 * @package     WooPostcodeChecker\Boot
 	 * @author      Olly Warren <olly@ollywarren.com>
 	 * @copyright   2018 Olly Warren
 	 * @version     1.0.0
	 */
	public static function on_boot() {
		
		// Enqueue Scripts and Styles
		add_action( 'wp_enqueue_scripts', [ 'WooPostcodeChecker\Boot', 'queue_styles' ] );
		add_action( 'wp_enqueue_scripts', [ 'WooPostcodeChecker\Boot', 'queue_scripts' ] );
		
		// Register the AJAX Callbacks.
		add_action( 'wp_ajax_nopriv_post_check_postcode', [ 'WooPostcodeChecker\PC', 'post_check_postcode' ]);
		add_action( 'wp_ajax_post_check_postcode', [ 'WooPostcodeChecker\PC', 'post_check_postcode' ]);

		// Register the Shortcode
		add_shortcode( 'postcode-checker', [ 'WooPostcodeChecker\PC', 'render_shortcode' ] );

	}

	/**
	 * Activate the Plugin and runs
	 * the installation Methods.
	 * 
	 * Add any required Installation steps in this method.
	 * 
	 * @package     WooPostcodeChecker\Boot
 	 * @author      Olly Warren <olly@ollywarren.com>
 	 * @copyright   2018 Olly Warren
 	 * @version     1.0.0
	 */
	public static function activate() {}

	/**
	 * Deactivate the Plugin and runs
	 * the uninstallation Methods.
	 * 
	 * Add any required Deactivation steps in this method.
	 * 
	 * @package     WooPostcodeChecker\Boot
 	 * @author      Olly Warren <olly@ollywarren.com>
 	 * @copyright   2018 Olly Warren
 	 * @version     1.0.0
	 */
	public static function deactivate(){}
	
	
	/**
	 * Queue and Localize the Script for the JS Callbacks.
	 * 
	 * @package     WooPostcodeChecker\Boot
 	 * @author      Olly Warren <olly@ollywarren.com>
 	 * @copyright   2018 Olly Warren
 	 * @version     1.0.0
	 */
    public static function queue_scripts() {
		wp_enqueue_script( 'postcode_checker_script', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/postcode-checker.js', array('jquery'), 1.0, true );
		wp_localize_script( 'postcode_checker_script', 'post_check_postcode', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'ajax_nonce' => wp_create_nonce('check_postcode_nonce') ) );
	}
	
	/**
	 * Queue The CSS We Need for the plugin
	 * 
	 * @package     WooPostcodeChecker\Boot
 	 * @author      Olly Warren <olly@ollywarren.com>
 	 * @copyright   2018 Olly Warren
 	 * @version     1.0.0
	 */
	public static function queue_styles() {

	}
}
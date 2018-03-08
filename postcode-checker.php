<?php
/**
 * WooCommerce Postcode Checker
 *
 * @package     WooPostcodeChecker\PC
 * @author      Olly Warren
 * @copyright   2018 Olly Warren
 * @version     1.0
 *
 * @wordpress-plugin
 * Plugin Name: WooCommerce Postcode Checker
 * Plugin URI:  https://github,com/ollywarren
 * Description: Implements a Postcode Checker Widget for WooCommerce.
 * Version:     1.0
 * Author:      Olly Warren
 * Author URI:  https://github.com/ollywarren
 * Text Domain: postcode-checker
 */

// If this file is access directly lets kill ourselves!
if ( ! defined( 'WPINC' ) ) {
	die;
}
define( 'POSTCODE_CHECKER_PLUGIN_VERSION', '1.0' );
define( 'POSTCODE_CHECKER_AUTOLOAD_PATH', plugin_dir_path( __FILE__ ) );
// Autoload Classes.
require_once POSTCODE_CHECKER_AUTOLOAD_PATH . 'autoload.php';

/**
 * Activates the plugin.
 *
 * @method wp_sudo_activate_plugin
 * @author Olly Warren
 * @return void
 */
function postcode_checker_activate_plugin() {
	WooPostcodeChecker\Boot::activate();
}
/**
 * Deactivates the plugin
 *
 * @method wp_wakatime_deactivate_plugin
 * @return void
 */
function postcode_checker_deactivate_plugin() {
	WooPostcodeChecker\Boot::deactivate();
}
register_activation_hook( __FILE__, 'postcode_checker_activate_plugin' );
register_deactivation_hook( __FILE__, 'postcode_checker_deactivate_plugin' );
// Boot the Plugin.
WooPostcodeChecker\Boot::on_boot();
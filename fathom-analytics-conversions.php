<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.sixfive.com.au
 * @since             1.0.0
 * @package           Fathom_Analytics_Conversions
 *
 * @wordpress-plugin
 * Plugin Name:       Fathom Analytics Conversions
 * Plugin URI:        https://www.sixfive.com.au/fathom-analytics-conversions
 * Description:       Easily add conversions in Wordpress plugins to Fathom Analytics
 * Version:           1.0.0
 * Author:            Duncan Isaksen-Loxton
 * Author URI:        https://www.sixfive.com.au
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       fathom-analytics-conversions
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'FATHOM_ANALYTICS_CONVERSIONS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-fathom-analytics-conversions-activator.php
 */
function activate_fathom_analytics_conversions() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-fathom-analytics-conversions-activator.php';
	Fathom_Analytics_Conversions_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-fathom-analytics-conversions-deactivator.php
 */
function deactivate_fathom_analytics_conversions() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-fathom-analytics-conversions-deactivator.php';
	Fathom_Analytics_Conversions_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_fathom_analytics_conversions' );
register_deactivation_hook( __FILE__, 'deactivate_fathom_analytics_conversions' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-fathom-analytics-conversions.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_fathom_analytics_conversions() {

	$plugin = new Fathom_Analytics_Conversions();
	$plugin->run();

}
run_fathom_analytics_conversions();

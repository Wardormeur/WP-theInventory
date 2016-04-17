<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           the_Inventory
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress theInventory
 * Plugin URI:        theinventory.net/plugins/wordpress
 * Description:       Configuration and sync of products data
 * Version:           1.0.0
 * Author:            Wardormeur
 * Author URI:				getlost.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       the-inventory
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-the-inventory-activator.php
 */
function activate_the_Inventory() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-the-inventory-activator.php';
	the_Inventory_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-the-inventory-deactivator.php
 */
function deactivate_the_Inventory() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-the-inventory-deactivator.php';
	the_Inventory_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_the_Inventory' );
register_deactivation_hook( __FILE__, 'deactivate_the_Inventory' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-the-inventory.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_the_Inventory() {

	$plugin = new the_Inventory();
	$plugin->run();

}
run_the_Inventory();

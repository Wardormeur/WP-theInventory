<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    the_Inventory
 * @subpackage the_Inventory/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    the_Inventory
 * @subpackage the_Inventory/includes
 * @author     Your Name <email@example.com>
 */
class the_Inventory_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		// wp_schedule_event(time(), 'hourly', 'the_inventory_sync_hook', $args);
	}

}

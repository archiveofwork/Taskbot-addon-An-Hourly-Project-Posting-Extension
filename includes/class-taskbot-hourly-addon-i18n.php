<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://amentotech.com/
 * @since      1.0.0
 *
 * @package    Taskbot_Hourly_Addon
 * @subpackage Taskbot_Hourly_Addon/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Taskbot_Hourly_Addon
 * @subpackage Taskbot_Hourly_Addon/includes
 * @author     Amento Tech <info@amentotech.com>
 */
class Taskbot_Hourly_Addon_i18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'taskbot-hourly-addon',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}
}

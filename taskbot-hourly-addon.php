<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://amentotech.com/
 * @since             1.0.0
 * @package           Taskbot_Hourly_Addon
 *
 * @wordpress-plugin
 * Plugin Name:       Taskbot - Hourly project posting addon 
 * Plugin URI:        https://codecanyon.net/item/taskbot-a-freelancer-marketplace-wordpress-plugin/35344021
 * Description:       This addon will allow the buyers to post the hourly projects and freelancers will be able to log the time by days/weeks/monts and then generate the invoices
 * Version:           1.6
 * Author:            Amento Tech
 * Author URI:        https://amentotech.com/
 * Text Domain:       taskbot-hourly-addon
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
define( 'TASKBOT_HOURLY_ADDON_VERSION', '1.6' );
define( 'TASKBOT_HOURLY_ADDON_URI', plugin_dir_url( __FILE__ ));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-taskbot-hourly-addon-activator.php
 */
function taskbot_hourly_addon_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-taskbot-hourly-addon-activator.php';
	Taskbot_Hourly_Addon_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-taskbot-hourly-addon-deactivator.php
 */
function taskbot_hourly_addon_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-taskbot-hourly-addon-deactivator.php';
	Taskbot_Hourly_Addon_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'taskbot_hourly_addon_activate' );
register_deactivation_hook( __FILE__, 'taskbot_hourly_addon_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'helpers/hourly-addon-emails.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-taskbot-hourly-addon.php';
require plugin_dir_path( __FILE__ ) . 'includes/public-function.php';
require plugin_dir_path( __FILE__ ) . 'includes/ajax-hooks.php';
require plugin_dir_path( __FILE__ ) . 'includes/hooks.php';
require plugin_dir_path( __FILE__ ) . 'includes/interval-hooks.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function taskbot_hourly_addon_run() {
	$plugin = new Taskbot_Hourly_Addon();
	$plugin->run();

}
taskbot_hourly_addon_run();

/**
 * Load plugin textdomain
 *
 * @since 1.0.0
 */
add_action( 'init', 'taskbot_hourly_addon_load_textdomain' );
function taskbot_hourly_addon_load_textdomain() {
  load_plugin_textdomain( 'taskbot-hourly-addon', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

/**
 * Admin notice
 *
 * @since 1.0.0
 */
if (!function_exists('taskbot_hourly_activation_notice')) {
	function taskbot_hourly_activation_notice(){?>
		<div class="error">
			<p><?php echo wp_kses( __( 'Please install the <a href="https://codecanyon.net/item/taskbot-a-freelancer-marketplace-wordpress-plugin/35344021?s_rank=7">Taskbot</a> parent plugin to use this hourly addon', 'taskbot-hourly-addon'),array('a'	=> array('href'  => array(),'title' => array())));?></p>
		</div>
	<?php
	}
}

/**
 * Taskbot plugin activation check
 *
 * @since 1.0.0
 */
if (function_exists('is_plugin_active')) {
	if ( !is_plugin_active('taskbot/init.php') ) {
		deactivate_plugins('taskbot-hourly-addon/taskbot-hourly-addon.php');
		add_action( 'admin_notices', 'taskbot_hourly_activation_notice' );
	}
}
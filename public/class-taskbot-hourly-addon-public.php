<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://amentotech.com/
 * @since      1.0.0
 *
 * @package    Taskbot_Hourly_Addon
 * @subpackage Taskbot_Hourly_Addon/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Taskbot_Hourly_Addon
 * @subpackage Taskbot_Hourly_Addon/public
 * @author     Amento Tech <info@amentotech.com>
 */
class Taskbot_Hourly_Addon_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Taskbot_Hourly_Addon_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Taskbot_Hourly_Addon_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Taskbot_Hourly_Addon_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Taskbot_Hourly_Addon_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script( 'taskbot-hourly-project', plugin_dir_url( __FILE__ ) . 'js/taskbot-hourly-addon-public.js', array( 'jquery' ), $this->version, true );
		wp_register_script('inputmask', plugin_dir_url( __FILE__ ) . 'js/jquery.inputmask.bundle.js', array(), $this->version, true);
		wp_enqueue_script('taskbot-hourly-project');

		$ref	= !empty($_GET['ref']) ? $_GET['ref'] : '';
		$mode	= !empty($_GET['mode']) ? $_GET['mode'] : '';

		if( is_page_template( 'templates/dashboard.php') && !empty($ref) && $ref === 'projects' && !empty($mode) && $mode === 'activity'){
			wp_enqueue_script('inputmask');
		}

		$data = array(
			'hourly_invoice_title'      	=> esc_html__('Submit hours', 'taskbot-hourly-addon'),
			'hourly_invoice_detail'      	=> esc_html__('Are you sure you want to submit hours request?', 'taskbot-hourly-addon'),
			'approved_time_title'      		=> esc_html__('Submit hours', 'taskbot-hourly-addon'),
			'approved_time_detail'      	=> esc_html__('Are you sure you want to approved this hours request?', 'taskbot-hourly-addon'),
		);

		wp_localize_script('taskbot-hourly-project', 'hourly_scripts_vars', $data );
	}

}

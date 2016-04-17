<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    the_Inventory
 * @subpackage the_Inventory/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    the_Inventory
 * @subpackage the_Inventory/admin
 * @author     Your Name <email@example.com>
 */
class the_Inventory_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $the_Inventory    The ID of this plugin.
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
	 * The ressource URL
	 *
	 * @since		1.0.0
	 * @access	private
	 * @var 		string		$url		the default endpoint for our ressouces
	 */
	 private $url;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $the_Inventory       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in the_Inventory_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The the_Inventory_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/the-inventory-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in the_Inventory_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The the_Inventory_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/the-inventory-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {

		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'theInventory Settings', 'the-inventory' ),
			__( 'The Inventory', 'the-inventory' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page' )
		);

	}


	/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_options_page() {
		include_once 'partials/the-inventory-admin-display.php';
	}


	/**
	 * Hook handler to sync the products
	 *
	 * @since  1.0.0
	 */
	public function sync_products() {

		//TODO: customize URL for personal instances
		// https://codex.wordpress.org/Function_Reference/wp_remote_post
		$params = array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(),
			'body'=> array(
				'key' => '',
				'latest' => '',
				'tags' => ''
			)
		);
		$response = wp_safe_remote_post($this->url, $params);
		if ( is_wp_error( $response ) ) {
		   $error_message = $response->get_error_message();
			 admin_noticer();
		}


	}

	/**
	 * Display a top bar to notify the user of the issues
	 *
	 */
	function admin_noticer() {
		$class = 'notice notice-error';
		$message = __( 'Irks! An error has occurred.', 'the-inventory' );

		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
	}

}

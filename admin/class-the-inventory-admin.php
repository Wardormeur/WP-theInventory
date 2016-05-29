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

	/*	Verify the presence of other plugins which are required*/
	function check_plugin_dep() {
    if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'pods/init.php' ) ) {
        add_action( 'admin_notices', 'admin_noticer' );

        deactivate_plugins( plugin_basename( __FILE__ ) );

        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }
	}

	/**
	 * Display a top bar to notify the user of the issues
	 *
	 */
	function admin_noticer() {
		$class = 'notice notice-error';
		$message = __( 'the_Inventory requires Pods to run, please install Pods before theInventory.', 'the-inventory' );

		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
	}

	function product_edit_definition( $pod ){
		pods_ui($pod);
		if(isset($_GET['id'])){
			$property = pods('propertyInstance');
			echo '<form class="property-box postbox">
			<div class="properties inside">';
			//display existing properties

			echo '<h3 class="hndle ui-sortable-handle"><span>'.__('Properties', 'the-inventory').'</span></h3>';
			$properties = $pod->field('properties');
			if($properties){
				foreach($properties as $propertyComb) {
					$propertyInstance = pods('propertyInstance', $propertyComb['id']);
					$propertyDefinition = pods('property', $propertyInstance->field('property')['id']);
					$this->get_property_template($propertyDefinition, $propertyInstance);
					unset($propertyInstance);
					unset($propertyDefinition);
					unset($propertyComb);
				}
			}
			//form for new one
			echo '<div class="inline">';
			echo $property->form(['fields_only'=> true, 'fields'=>['property', 'value']]);
			echo '<input type="hidden" name="edit_propertyInstance_id" value=""/>';
			echo '<button type="submit" class="save-prop button pod-align">Save</button>';
			echo '</div></div>';
			echo '<a href="">Add new property definition</a>';
			// echo '<button type="submit" class="new-prop" >New property</button>';
			echo '</form>';
		}
	}


	//	Ref : https://codex.wordpress.org/AJAX_in_Plugins
	function add_propertyInstance_template_callback () {
		$property = pods('propertyInstance');
		echo $property->form();
		wp_die(); // this is required to terminate immediately and return a proper response
	}

	//	Ref : https://codex.wordpress.org/AJAX_in_Plugins
	function save_property_callback () {
		$product_id = intval($_POST['product_id']);
		$exists = isset($_POST['propertyInstance_id']);
		$property_id = intval($_POST['property']);
		$property_value = intval($_POST['value']);
		if($exists){
			$propertyInstance_id = $_POST['propertyInstance_id'];
			if($propertyInstance_id != ''){
				$property = pods('propertyInstance', intval($propertyInstance_id));
			}else{
				$property = pods('propertyInstance');
			}
		} else {
			$property = pods('propertyInstance');
		}
		$property->save(["property" => $property_id, "value" => $property_value, "product_id"=> $product_id]);
		$product = pods('product', $product_id);
		$product->add_to('properties', $property->field('id'));
		$this->get_property_template( pods('property', $property_id) , $property);
		wp_die();
	}

	//	Ref : https://codex.wordpress.org/AJAX_in_Plugins
	function delete_property_callback () {
		$product_id = intval($_POST['product_id']);
		$propertyInstance_id = intval($_POST['id']);
		$property = pods('propertyInstance', $propertyInstance_id);
		$property->delete();
		wp_die();
	}


	function get_property_template($propertyDefinition, $propertyInstance){
		echo '<div class="pods-form-fields">';
		echo '<input type="text" disabled="disabled" name="name" value="'.$propertyDefinition->display('name').'"/>';
		echo '<input type="text" disabled="disabled" name="value" value="'.$propertyInstance->display('value').'"/>';
		echo '<input type="text" disabled="disabled" name="unit" value="'.$propertyDefinition->display('unit').'"/>';
		echo '<input type="hidden" name="property_id" value="'.$propertyDefinition->field('id').'"/>';
		echo '<input type="hidden" name="propertyInstance_id" value="'.$propertyInstance->field('id').'"/>';
		echo '<button type="button" class="edit-prop button" >'.__('Edit', 'the-inventory').'</button>';
		echo '<button type="button" class="delete-prop button" >'.__('Delete', 'the-inventory').'</button>';
		echo '</div>';
	}



}

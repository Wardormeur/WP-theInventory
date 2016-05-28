<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    the_Inventory
 * @subpackage the_Inventory/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    the_Inventory
 * @subpackage the_Inventory/public
 * @author     Your Name <email@example.com>
 */
class the_Inventory_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $the_Inventory    The ID of this plugin.
	 */
	private $the_Inventory;

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
	 * @param      string    $the_Inventory       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $the_Inventory, $version ) {

		$this->plugin_name = $the_Inventory;
		$this->version = $version;
		// Add your templates to this array.
		$this->templates = array(
				'partials/listing-product.php'     => 'Listing products',
				'partials/content-product.php'     => 'Product content',
				'partials/page-product.php'     => 'Product Page',
		);

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
		 * defined in the_Inventory_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The the_Inventory_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/the-inventory-public.css', array(), $this->version, 'all' );

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
		 * defined in the_Inventory_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The the_Inventory_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/the-inventory-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Adds our template to the pages cache in order to trick WordPress
	 * into thinking the template file exists where it doens't really exist.
	 *
	 */

	public function register_project_templates( $atts ) {

					// Create the key used for the themes cache
					$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

					// Retrieve the cache list.
					// If it doesn't exist, or it's empty prepare an array
					$templates = wp_get_theme()->get_page_templates();
					if ( empty( $templates ) ) {
									$templates = array();
					}

					// New cache, therefore remove the old one
					wp_cache_delete( $cache_key , 'themes');

					// Now add our template to the list of templates by merging our templates
					// with the existing templates array from the cache.
					$templates = array_merge( $templates, $this->templates );

					// Add the modified cache to allow WordPress to pick it up for listing
					// available templates
					wp_cache_add( $cache_key, $templates, 'themes', 2000 );
					return $atts;

	}


		/**
		 * Adds our template to the pages cache in order to trick WordPress
		 * into thinking the template file exists where it doens't really exist.
		 *
		 */

		public function apply_filter_add_templates( $atts ) {

						return array_flip($this->templates);
		}


		public function redirect_custom_template(){
	    // Check if a custom template exists in the theme folder, if not, load the plugin template file
			return function( $template ){
				if ( $theme_file = locate_template( array( 'plugin_template/' . $template ) ) ) {
		        $file = $theme_file;
		    }
		    else {
			        $file = plugin_dir_path(__FILE__).$template;
				}
				load_template( $file );
			};
		}


	/**
	 * Checks if the template is assigned to the page
	 */
	public function view_project_template( $template ) {

					global $post;
					if(in_array($template, $this->templates)){

						if (!isset($this->templates[get_post_meta(
						$post->ID, '_wp_page_template', true
						)] ) ) {
							return $template;
						}

						$file = plugin_dir_path(__FILE__). get_post_meta(
							$post->ID, '_wp_page_template', true
						);

						// Just to be safe, we check if the file exist first
						if( file_exists( $file ) ) {
							return $file;
						}
						else { echo $file; }

					}

					return $template ;

	}



}

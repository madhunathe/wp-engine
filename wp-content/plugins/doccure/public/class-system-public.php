<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://themeforest.net/user/dreamstechnologies/portfolio
 * @since      1.0.0
 *
 * @package    doccure
 * @subpackage doccure/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    doccure
 * @subpackage doccure/public
 * @author     Dreams Technologies<support@dreamstechnologies.com>
 */
class doccure_Public {

    public function __construct() {

        $this->plugin_name = doccureGlobalSettings::get_plugin_name();
        $this->version = doccureGlobalSettings::get_plugin_verion();
        $this->plugin_path = doccureGlobalSettings::get_plugin_path();
        $this->plugin_url = doccureGlobalSettings::get_plugin_url();
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
         * defined in doccure_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The doccure_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        //wp_enqueue_style('system-public', plugin_dir_url(__FILE__) . 'css/system-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
		global $doccure_options;
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in doccure_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The doccure_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_register_script('doccure_core', plugin_dir_url(__FILE__) . 'js/system-public.js', array('jquery'), $this->version, false);	       
        //wp_enqueue_script('doccure_core');	  
		
		$dependencies	= array('jquery');
		if( !empty( $doccure_options['chat'] ) && $doccure_options['chat'] === 'chat' ){
			$dependencies	= array('jquery','socket.io');
		}
		
		if ( ( is_page_template('directory/dashboard.php') && isset($_GET['ref']) && $_GET['ref'] === 'chat' ) || is_singular('doctors') ) {
			if( !empty( $doccure_options['chat'] ) && $doccure_options['chat'] !== 'guppy' ){
				wp_enqueue_script('doccure_chat_module', plugin_dir_url(__FILE__) . 'js/doccure_chat_module.js', $dependencies, $this->version, false);
			}
        }
    }

}

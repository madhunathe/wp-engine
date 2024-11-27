<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1 for parent theme doccure for publication on WordPress.org
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */
/**
 * Include the TGM_Plugin_Activation class.
 */
require_once get_template_directory() . '/inc/tgm-plugin-activation/class-tgm-plugin-activation.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
add_action( 'tgmpa_register', 'doccure_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variables passed to the `tgmpa()` function should be:
 * - an array of plugin arrays;
 * - optionally a configuration array.
 * If you are not changing anything in the configuration array, you can remove the array and remove the
 * variable from the function call: `tgmpa( $plugins );`.
 * In that case, the TGMPA default settings will be used.
 *
 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
 */
function doccure_register_required_plugins() {
    /*
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(
        array(
            'name'               => esc_html__( 'Advanced Category and Custom Image', 'doccure' ),
            'slug'               => 'advanced-category-and-custom-taxonomy-image',
            'source'             => get_parent_theme_file_path( '/inc/tgm-plugin-activation/plugins/advanced-category-and-custom-taxonomy-image.zip' ),
            'required'           => true,
        ),
        array(
            'name'               => esc_html__( 'Dynamic Featured Image', 'doccure' ),
            'slug'               => 'dynamic-featured-image',
            'source'             => get_parent_theme_file_path( '/inc/tgm-plugin-activation/plugins/dynamic-featured-image.zip' ),
            'required'           => true,
        ),
        // array(
        //     'name'               => esc_html__( 'Frontend Reset Password', 'doccure' ),
        //     'slug'               => 'frontend-reset-password',
        //     'source'             => get_parent_theme_file_path( '/inc/tgm-plugin-activation/plugins/frontend-reset-password.zip' ),
        //     'required'           => true,
        // ),       
        array(
            'name'               => esc_html__( 'Doccure Core', 'doccure' ),
            'slug'               => 'doccure',
            'source'             => get_parent_theme_file_path( '/inc/tgm-plugin-activation/plugins/doccure.zip' ),
            'version'            => '1.5.1',
            'required'           => true,
        ),

        array(
            'name'               => esc_html__( 'Dreams Zoom Integration', 'doccure' ),
            'slug'               => 'dreams-zoom-integration',
            'source'             => get_parent_theme_file_path( '/inc/tgm-plugin-activation/plugins/dreams-zoom-integration.zip' ),
            'version'            => '1.0.0',
            'required'           => true,
        ),

        // array(
        //     'name'               => esc_html__( 'Doccure Cron', 'doccure' ),
        //     'slug'               => 'doccure_cron',
        //     'source'             => get_parent_theme_file_path( '/inc/tgm-plugin-activation/plugins/doccure_cron.zip' ),
        //     'version'            => '1.5.9',
        //     'required'           => true,
        // ),
        
        array(
            'name'               => esc_html__( 'One Click Demo Import', 'doccure' ),
            'slug'               => 'one-click-demo-import',
            'required'           => true,
        ),
       
        array(
            'name'     => esc_html__( 'Redux Framework', 'doccure' ),
            'slug'     => 'redux-framework',
            'required' => true,
        ),
        
        array(
            'name'     => esc_html__( 'Contact Form 7', 'doccure' ),
            'slug'     => 'contact-form-7',
            'required' => true,
        ),
       
        array(
			'name'      			=> 'Elementor',
			'slug'      			=> 'elementor',
			'required'  			=> true,
		
        ),
          array(
            'name'     => esc_html__( 'Woocommerce', 'doccure' ),
            'slug'     => 'woocommerce',
            // 'external_url' => esc_url('https://downloads.wordpress.org/plugin/woocommerce.6.7.0.zip'),
            'required' => true,
        ),
        array(
	        'name'                  => 'Doccure Elementor',
	        'slug'                  => 'doccure-elementor',
	        'source'                => get_template_directory() . '/inc/tgm-plugin-activation/plugins/doccure-elementor.zip',
	        'version'               => '1.0.1',
	        'required'              => true,
		 ),
    );
    $plugins = apply_filters( 'doccure/required_plugins', $plugins );
    /*
     * Array of configuration settings. Amend each line as needed.
     *
     * TGMPA will start providing localized text strings soon. If you already have translations of our standard
     * strings available, please help us make TGMPA even better by giving us access to these translations or by
     * sending in a pull-request with .po file(s) with the translations.
     *
     * Only uncomment the strings in the config array if you want to customize the strings.
     */
    $config = array(
        'id'           => 'doccure-recommended-plugins',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
    );
    tgmpa( $plugins, $config );
}

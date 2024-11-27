<?php
/*
 * Plugin Name: Doccure Elementor
 * Version: 1.0.1
 * Plugin URI: https://doccure-wp.dreamstechnologies.com/
 * Description: Doccure widgets for Elementor
 * Author: Doccure
 * Author URI: https://doccure-wp.dreamstechnologies.com/
 *
 * Text Domain: doccure_elementor
 * Domain Path: /languages/
 *
 * @package WordPress
 * @author Doccure
 * @since 1.0.0
 */


define( 'ELEMENTOR_DOCCURE', __FILE__ );


/**
 * Include the Elementor_Doccure class.
 */
require plugin_dir_path( ELEMENTOR_DOCCURE ) . 'class-elementor-doccure.php';
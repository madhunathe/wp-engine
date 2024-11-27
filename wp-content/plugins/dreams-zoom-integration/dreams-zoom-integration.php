<?php
/**
 * Plugin Name: Dreams Zoom Integration
 * Description: A plugin to integrate Zoom with WordPress.
 * Version: 1.0.0
 * Author: Dreams Technologies
 * Author URI: www.dreamstechnologies.com
 * Text Domain: doccure_zoom
 * Domain Path: /languages/
 */

if ( !defined( 'ABSPATH' ) ) exit;

define( 'DZI_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// For Main Settings
require_once DZI_PLUGIN_DIR . 'includes/class-dz-main-controller.php';

// Function for Zoom
require_once DZI_PLUGIN_DIR . 'inc/functions.php';
require_once DZI_PLUGIN_DIR . 'inc/doccure-create-customtable-zoom.php';
require_once DZI_PLUGIN_DIR . 'inc/generate-zoom-meeting.php';

function dz_activate_plugin() {
    DZ_Main_Controller::activate();
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'zoom_links';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        order_id varchar(255) NOT NULL,
        product_id varchar(255) NOT NULL,
        slots varchar(255) NOT NULL,
        appointment_date varchar(255) NOT NULL,
        doctor_id varchar(255) NOT NULL,
        patient_id varchar(255) NOT NULL,
        meeting_id varchar(255) NOT NULL,
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";



    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_activation_hook( __FILE__, 'dz_activate_plugin' );

add_action( 'plugins_loaded', array( 'DZ_Main_Controller', 'init' ) );

$options_files = array(
    DZI_PLUGIN_DIR . '/redux/zoom-settings.php',
);

foreach ($options_files as $option_file) {
    if (file_exists($option_file)) {
        $option_data = include($option_file); 
        if ($option_data && is_array($option_data)) {
            Redux::setSection('doccure_options', $option_data);
        }
    }
}


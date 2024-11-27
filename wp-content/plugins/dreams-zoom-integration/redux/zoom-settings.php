<?php
/**
 * Zoom Settings
 *
 * @package doccure
 */
if (!class_exists('Redux')) {
    return;
}

global $opt_name;
$opt_name = "doccure_options";

$opt_name = apply_filters('redux_demo/opt_name', $opt_name); 

if ( class_exists( 'Redux' ) ) {
    return array(
        'title' => 'Zoom Settings',
        'desc' => esc_html__('This section controls the Zoom Integration', 'doccure'),
        'id' => 'zoom-settings',
        'customizer_width' => '400px',
        'icon' => 'el el-video',
        'fields' => array(
            array(
                'id' => 'zoom_enable_disable_settings',
                'type' => 'switch',
                'title' => esc_html__('Enable Zoom Integration', 'doccure'),
                'default' => 0,
            ),
            array(
                'id' => 'zoom_oauth_account_id',
                'type' => 'text',
                'title' => esc_html__('Oauth Account ID', 'doccure'),
                'default' => '',
                'required' => array('zoom_enable_disable_settings', '=', 1),
            ),
            
            array(
                'id' => 'zoom_oauth_client_id',
                'type' => 'text',
                'title' => esc_html__('Oauth Client ID', 'doccure'),
                'default' => '',
                'required' => array('zoom_enable_disable_settings', '=', 1),
            ),

            array(
                'id' => 'zoom_oauth_client_secret',
                'type' => 'text',
                'title' => esc_html__('Oauth Client Secret', 'doccure'),
                'default' => '',
                'required' => array('zoom_enable_disable_settings', '=', 1),
            ),

            array(
                'id' => 'zoom_client_id',
                'type' => 'text',
                'title' => esc_html__('Zoom Client ID', 'doccure'),
                'default' => '',
                'required' => array('zoom_enable_disable_settings', '=', 1),
            ),
            array(
                'id' => 'zoom_client_secret',
                'type' => 'text',
                'title' => esc_html__('Zoom Client Secret', 'doccure'),
                'default' => '',
                'required' => array('zoom_enable_disable_settings', '=', 1),
            ),
            array(
                'id' => 'zoom_connect_button',
                'type' => 'button',
                'title' => esc_html__('Connect to Zoom', 'doccure'),
                'text' => esc_html__('Connect', 'doccure'),
                'required' => array('zoom_enable_disable_settings', '=', 1),
            ),
        ),
    );
} else {
    return array();
}

$options_files = array(
   
   
    plugins_url() . '/redux-options/options/zoom-settings.php',

);


foreach ($options_files as $option_file) {
    if (file_exists($option_file)) {
        $option_data = include($option_file);
        if ($option_data && is_array($option_data)) {
            Redux::setSection('doccure_options', $option_data);
        }
    }
}

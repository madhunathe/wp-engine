<?php
/**
 * Theme Layout
 *
 * @package doccure
 */
return array(
    'title' => 'Theme Layout',
    'desc' => esc_html__('This section control the theme\'s layout', 'doccure'),
    'id' => 'theme-layout-options',
    'customizer_width' => '400px',
    'icon' => 'el el-screen',
    'fields' => array(
        array(
            'id' => 'preloader_divider',
            'type' => 'divide'
        ),
        array(
            'id' => 'preloader_enable',
            'type' => 'switch',
            'title' => esc_html__('Enable Preloader', 'doccure'),
            'default' => 1,
        ),
        array(
            'id' => 'preloader_style',
            'type' => 'select',
            'title' => esc_html__('Preloader style', 'doccure'),
            'subtitle' => esc_html__('Please choose the preloader style', 'doccure'),
            'options' => array(
                'default' => esc_html__('Default', 'doccure'),
                'eclipse' => esc_html__('Eclipse', 'doccure'),
                'spinner' => esc_html__('Spinner', 'doccure'),
                'diamond' => esc_html__('Diamond', 'doccure'),
                'ripple' => esc_html__('Ripple', 'doccure'),
                'gear' => esc_html__('Gear', 'doccure'),
                'pulse' => esc_html__('Pulse', 'doccure'),
                'squares' => esc_html__('Squares', 'doccure'),
                'dual' => esc_html__('Dual', 'doccure'),
            ),
            'required' => array('preloader_enable', '=', '1'),
            'default' => 'default',
        ),
        array(
            'id' => 'back_to_top_divider',
            'type' => 'divide'
        ),
        array(
            'id' => 'back_to_top',
            'type' => 'switch',
            'title' => esc_html__('Enable Back to Top', 'doccure'),
            'default' => 1,
        ),
        
       
        
    ),
);

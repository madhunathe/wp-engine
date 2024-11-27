<?php
/**
 * WooCommerce Settings
 *
 * @package doccure
 */
return array(
    'title' => esc_html__('WooCommerce settings', 'doccure'),
    'id' => 'woocommerce_settings',
    'customizer_width' => '400px',
    'icon' => 'el el-shopping-cart',
    'fields' => array(
        array(
            'id' => 'shop_layout',
            'type' => 'select',
            'title' => esc_html__('Shop Layout', 'doccure'),
            'options' => array(
                'container' => esc_html__('Boxed', 'doccure'),
                'container-fluid' => esc_html__('Full Width', 'doccure'),
            ),
            'default' => 'container',
        ),
        array(
            'id' => 'shop-sidebar',
            'type' => 'select',
            'title' => esc_html__('Shop Sidebar', 'doccure'),
            'subtitle' => esc_html__('Select the shop sidebar position.', 'doccure'),
            'options' => array(
                'full-width' => esc_html__('No Sidebar', 'doccure'),
                'left-sidebar' => esc_html__('Left Sidebar', 'doccure'),
                'right-sidebar' => esc_html__('Right Sidebar', 'doccure'),
            ),
            'default' => 'right-sidebar',
        ),
        array(
            'id' => 'all_cats_thumb',
            'type' => 'media',
            'url' => true,
            'title' => esc_html__('Custom Image for All Categories', 'doccure'),
            'subtitle' => esc_html__('Upload a custom image for the All Categories section', 'doccure'),
            'compiler' => 'true',
            'default' => '',
            'required' => array('show_category_strip', '=', '1'),
        ),
    ),
);

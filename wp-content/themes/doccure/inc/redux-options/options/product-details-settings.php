<?php
/**
 * Product Details Settings
 *
 * @package doccure
 */
return array(
    'title' => esc_html__('Product Details', 'doccure'),
    'id' => 'product_details_settings',
    'customizer_width' => '400px',
    'subsection' => true,
    'fields' => array(
        array(
            'id' => 'product_details_style',
            'type' => 'select',
            'title' => esc_html__('Product details style', 'doccure'),
            'options' => array(
                'style-1' => esc_html__('Style 1', 'doccure'),
            ),
            'default' => 'style-1',
        ),
    ),
);

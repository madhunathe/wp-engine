<?php
/**
 * Product Settings
 *
 * @package doccure
 */
return array(
    'title' => esc_html__('Product', 'doccure'),
    'id' => 'product_settings',
    'customizer_width' => '400px',
    'subsection' => true,
    'fields' => array(
        array(
            'id' => 'product_style',
            'type' => 'select',
            'title' => esc_html__('Product card style', 'doccure'),
            'options' => array(
                'style-1' => esc_html__('Style 1', 'doccure')
            ),
            'default' => 'style-1',
        )
       
    )
);

<?php
/**
 * My Account page Settings
 *
 * @package doccure
 */
return array(
    'title' => esc_html__('My Account settings', 'doccure'),
    'id' => 'my_account_settings',
    'customizer_width' => '400px',
    'icon' => 'el el-lock',
    'fields' => array(
        array(
            'id' => 'my_account_style',
            'type' => 'select',
            'title' => esc_html__('My Account Style', 'doccure'),
            'options' => array(
                'style-1' => esc_html__('Style 1', 'doccure')
            ),
            'default' => 'style-1',
        )
       
    )
);

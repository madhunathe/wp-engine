<?php
/**
 * Footer Settings
 *
 * @package doccure
 */
return array(
    'title' => esc_html('Footer Settings', 'doccure'),
    'id' => 'Footer_settings',
    'icon' => 'el el-file-edit',
    'fields' => array(
        array(
            'id' => 'footer_copyright',
            'type' => 'text',
            'title' => esc_html__('Footer copyright', 'doccure'),
            'description' => esc_html__('Copyright text here', 'doccure'),
            'default' => '© 2023 Doccure. All rights reserved.',
        ),
        
        
    ),
);

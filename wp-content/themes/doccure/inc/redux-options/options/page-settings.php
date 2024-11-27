<?php
/**
 * Page Settings
 *
 * @package doccure
 */
return array(
    'title' => esc_html__('Page Settings', 'doccure'),
    'id' => 'page_settings',
    'icon' => 'el el-file-edit',
    'fields' => array(
        array(
            'id' => 'page_sidebar',
            'type' => 'select',
            'title' => esc_html__('Page Sidebar', 'doccure'),
            'subtitle' => esc_html__('Select the Page sidebar position.', 'doccure'),
            'options' => array(
                'full-width' => esc_html__('No Sidebar', 'doccure'),
                'left-sidebar' => esc_html__('Left Sidebar', 'doccure'),
                'right-sidebar' => esc_html__('Right Sidebar', 'doccure'),
            ),
            'default' => 'right-sidebar',
        ),
        array(
            'id' => 'page_layout',
            'type' => 'select',
            'title' => esc_html__('Select Page Layout', 'doccure'),
            'subtitle' => esc_html__('Please select the page layout.', 'doccure'),
            'options' => array(
                'container' => esc_html__('Boxed', 'doccure'),
                'container-fluid' => esc_html__('Full Width', 'doccure'),
            ),
            'description' => esc_html__('Only works in pages that do not have WP Bakery content.', 'doccure'),
            'default' => 'container',
        ),
    ),
);

<?php
/**
 * Download settings
 *
 * @package Doccure
 */
return array(
    'title' => esc_html__('Download Settings', 'doccure'),
    'id' => 'download_settings',
    'icon' => 'el el-graph',
    'fields' => array(
        array(
            'id' => 'download_sidebar',
            'type' => 'select',
            'title' => esc_html__('Download Sidebar', 'doccure'),
            'subtitle' => esc_html__('Select the Download sidebar position.', 'doccure'),
            'options' => array(
                'left-sidebar' => esc_html__('Left Sidebar', 'doccure'),
                'right-sidebar' => esc_html__('Right Sidebar', 'doccure'),
            ),
            'default' => 'right-sidebar',
        ),
        array(
            'id' => 'download-style',
            'type' => 'select',
            'title' => esc_html__('Select Download Style', 'doccure'),
            'subtitle' => esc_html__('Please select the Download archive style to display.', 'doccure'),
            'options' => array(
                'style-1' => esc_html__('Style 1', 'doccure'),
                'style-2' => esc_html__('Style 2', 'doccure'),
                'style-3' => esc_html__('Style 3', 'doccure'),
                'style-4' => esc_html__('Style 4', 'doccure'),
                'style-5' => esc_html__('Style 5', 'doccure'),
                'style-6' => esc_html__('Style 6', 'doccure'),
                'style-7' => esc_html__('Style 7', 'doccure'),
                'style-8' => esc_html__('Style 8', 'doccure'),
                'style-9' => esc_html__('Style 9', 'doccure'),
                'style-10' => esc_html__('Style 10', 'doccure'),
                'style-11' => esc_html__('Style 11', 'doccure'),
                'style-12' => esc_html__('Style 12', 'doccure'),
                'style-13' => esc_html__('Style 13', 'doccure'),
                'style-14' => esc_html__('Style 14', 'doccure'),
                'style-15' => esc_html__('Style 15', 'doccure'),
                'style-16' => esc_html__('Style 16', 'doccure'),
                'style-17' => esc_html__('Style 17', 'doccure'),
                'style-18' => esc_html__('Style 18', 'doccure'),
                'style-19' => esc_html__('Style 19', 'doccure'),
            ),
            'default' => 'style-1',
        ),
        array(
            'id' => 'download-columns',
            'type' => 'select',
            'title' => esc_html__('Number of Columns', 'doccure'),
            'subtitle' => esc_html__('Please select the number of columns in download archive.', 'doccure'),
            'options' => array(
                'col-lg-12' => esc_html__('1 Column', 'doccure'),
                'col-lg-6 col-md-6' => esc_html__('2 Columns', 'doccure'),
                'col-lg-4 col-md-6' => esc_html__('3 Columns', 'doccure'),
                'col-lg-3 col-md-6' => esc_html__('4 Columns', 'doccure'),
            ),
            'default' => 'col-lg-6',
        ),
        array(
            'id' => 'show_download_icon',
            'type' => 'switch',
            'title' => esc_html__('Show Download Icon', 'doccure'),
            'subtitle' => esc_html__('Enable to show download icon.', 'doccure'),
            'default' => true,
            'required' => array('download-style', '=', array('style-1', 'style-2', 'style-3', 'style-4', 'style-5', 'style-6', 'style-7', 'style-9', 'style-11', 'style-12', 'style-13', 'style-15', 'style-16', 'style-17', 'style-19')),
        ),
        array(
            'id' => 'show_download_features',
            'type' => 'switch',
            'title' => esc_html__('Show Download Features', 'doccure'),
            'subtitle' => esc_html__('Enable to show download features.', 'doccure'),
            'default' => true,
            'required' => array('download-style', '=', array('style-1', 'style-3')),
        ),
        array(
            'id' => 'show_download_excerpt',
            'type' => 'switch',
            'title' => esc_html__('Show Download Excerpt', 'doccure'),
            'subtitle' => esc_html__('Enable to show download excerpt.', 'doccure'),
            'default' => true,
        ),
        array(
            'id' => 'download_excerpt_length',
            'type' => 'text',
            'title' => esc_html__('Excerpt Length', 'doccure'),
            'subtitle' => esc_html__('Enter no of words to show in download excerpt.', 'doccure'),
            'default' => 20,
            'required' => array('show_download_excerpt', '=', true),
        ),
        array(
            'id' => 'download-details-style',
            'type' => 'select',
            'title' => esc_html__('Select Download Details Style', 'doccure'),
            'subtitle' => esc_html__('Please select download details style to display.', 'doccure'),
            'options' => array(
                'style-1' => esc_html__('Style 1', 'doccure'),
                'style-2' => esc_html__('Style 2', 'doccure'),
            ),
            'default' => 'style-1',
        ),
        array(
            'id' => 'download_details_sidebar',
            'type' => 'select',
            'title' => esc_html__('Download Details Sidebar', 'doccure'),
            'subtitle' => esc_html__('Select the Download Details sidebar position.', 'doccure'),
            'options' => array(
                'left-sidebar' => esc_html__('Left Sidebar', 'doccure'),
                'right-sidebar' => esc_html__('Right Sidebar', 'doccure'),
            ),
            'default' => 'right-sidebar',
        ),
    ),
);

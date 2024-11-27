<?php

/**
 * 404 Settings
 *
 * @package doccure
 */

return array(

    'title' => esc_html__('404 Page', 'doccure'),

    'id' => '404_page',

    'customizer_width' => '400px',

    'icon' => 'el el-warning-sign',

    'fields' => array(

        array(
            'id' => '404_type',
            'type' => 'select',
            'title' => esc_html__('404 Page type', 'doccure'),
            'options' => array(
                'static' => esc_html__('Static', 'doccure')
            ),
            'default' => 'static',
        ),
        array(

            'id' => 'fof_page_title',

            'type' => 'text',

            'title' => esc_html__('Page Title', 'doccure'),

            'desc' => esc_html__('Enter 404 page title.', 'doccure'),

            'default' => esc_html__('404', 'doccure'),

            'required' => array('404_type', '=', 'static')
        ),

        array(

            'id' => 'fof_page_description',

            'type' => 'textarea',

            'title' => esc_html__('Page Description', 'doccure'),

            'desc' => esc_html__('Enter 404 page description.', 'doccure'),

            'validate' => 'html_custom',

            'default' => 'It looks like nothing was found at this location',

            'required' => array('404_type', '=', 'static')
        ),

        array(

            'id' => 'fof_page_background',

            'type' => 'background',

            'title' => esc_html__('404 Background', 'doccure'),

            'desc' => esc_html__('Set 404 background.', 'doccure'),

            'preview_media' => true,

            'output' => '.fof-page-container',

            'required' => array('404_type', '=', 'static')
        ),

        array(
            'id' => '404_title_color',
            'type' => 'color',
            'title' => esc_html__('404 Title color', 'doccure'),
            'subtitle' => esc_html__('Set color for the 404 page title', 'doccure'),
        ),
        array(
            'id' => '404_desc_color',
            'type' => 'color',
            'title' => esc_html__('404 Description color', 'doccure'),
            'subtitle' => esc_html__('Set color for the 404 page description', 'doccure'),
        ),
        array(

            'id' => 'fof_page_back_to_home',

            'type' => 'switch',

            'title' => esc_html__('Back to Home', 'doccure'),

            'default' => true,

            'required' => array('404_type', '=', 'static')
        ),

        array(
            'id' => '404_hide_header',
            'type' => 'switch',
            'title' => esc_html__('Hide Header?', 'doccure'),
            'subtitle' => esc_html__('Enable to hide header on 404 page', 'doccure'),
            'default' => false,
            'required' => array('404_type', '=', 'static')
        ),
        array(
            'id' => '404_hide_footer',
            'type' => 'switch',
            'title' => esc_html__('Hide Footer?', 'doccure'),
            'subtitle' => esc_html__('Enable to hide footer on 404 page', 'doccure'),
            'default' => false,
            'required' => array('404_type', '=', 'static')
        ),
    ),

);

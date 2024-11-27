<?php
/**
 * Header Settings
 *
 * @package doccure
 */
return array(
    'title' => esc_html('Header Settings', 'doccure'),
    'id' => 'header_settings',
    'icon' => 'el el-credit-card',
    'fields' => array(
        array(
            'id' => 'header_type',
            'type' => 'select',
            'title' => esc_html__('Header type', 'doccure'),
            'options' => array(
                'static' => esc_html__('Static', 'doccure'),
                //'page-template' => esc_html__('Page Template', 'doccure'),
            ),
            'default' => 'static',
        ),
        array(
            'id' => 'header_type_page_template',
            'title' => esc_html__('Select Page Template', 'doccure'),
            'subtitle' => esc_html__('Please select a page template to show in the header', 'doccure'),
            'type' => 'select',
            'multi' => false,
            'data' => 'posts',
            'args' => array('post_type' => 'doccure_templates', 'numberposts' => -1),
            'required' => array('header_type', '=', 'page-template')
        ),
        array(
            'id' => 'header-layout',
            'type' => 'image_select',
            'title' => esc_html__('Select Header Layout', 'doccure'),
            'subtitle' => esc_html__('Please select the header style to display.', 'doccure'),
            'options' => array(
                
                'layout-10' => array(
                    'img' => get_parent_theme_file_uri('assets/images/theme-options/header-layouts/header-10.jpg'),
                    'alt' => esc_html__('Header Layout 10', 'doccure'),
                ),
            ),
            'default' => 'layout-1',
            'required' => array('header_type', '=', 'static')
        ),

        array(
            'id' => 'header-position',
            'type' => 'select',
            'title' => esc_html__('Select Header Position', 'doccure'),
            'subtitle' => esc_html__('Please select the header position', 'doccure'),
            'options' => array(
                //'header-absolute' => esc_html__('Absolute', 'doccure'),
                'header-relative' => esc_html__('Relative', 'doccure'),
            ),
            'default' => 'header-relative',
            'required' => array('header_type', '=', 'static')
        ),
   
       
        
    
        
        array(
            'id' => 'header_main_cta_button',
            'type' => 'divide',
            'required' => array('header_type', '=', 'static')
        ),
        array(
            'id' => 'display-cta-button',
            'type' => 'switch',
            'title' => esc_html__('Display Call to action Button', 'doccure'),
            'default' => 0,
            'subtitle' => esc_html__('Enable to display the call to action button.', 'doccure'),
            'required' => array('header-layout', '=', array('layout-5', 'layout-6', 'layout-10')),
        ),
        array(
            'id' => 'header_cta_btn_title',
            'type' => 'text',
            'title' => esc_html__('Login', 'doccure'),
            'subtitle' => esc_html__('Please enter call to action button title.', 'doccure'),
            'required' => array('display-cta-button', '=', '1'),
        ),
        array(
            'id' => 'header_cta_btn_link',
            'type' => 'text',
            'title' => esc_html__('Login Link', 'doccure'),
            'subtitle' => esc_html__('Please enter call to action button link.', 'doccure'),
            'required' => array('display-cta-button', '=', '1'),
        ),

        array(
            'id' => 'header_cta_rbtn_title',
            'type' => 'text',
            'title' => esc_html__('Register', 'doccure'),
            'subtitle' => esc_html__('Please enter call to action button title.', 'doccure'),
            'required' => array('display-cta-button', '=', '1'),
        ),
        array(
            'id' => 'header_cta_rbtn_link',
            'type' => 'text',
            'title' => esc_html__('Register Link', 'doccure'),
            'subtitle' => esc_html__('Please enter call to action button link.', 'doccure'),
            'required' => array('display-cta-button', '=', '1'),
        ),
        
        array(
            'id' => 'header_cta_color',
            'type' => 'color',
            'title' => esc_html__('Button Color', 'doccure'),
            'subtitle' => esc_html__('Set text color for the cta button.', 'doccure'),
            'required' => array('display-cta-button', '=', '1'),
        ),
        array(
            'id' => 'header_cta_hover_bg_color',
            'type' => 'color',
            'title' => esc_html__('Button Hover Background Color', 'doccure'),
            'subtitle' => esc_html__('Set hover background color for the cta button.', 'doccure'),
            'required' => array('display-cta-button', '=', '1'),
        ),
        array(
            'id' => 'header_cta_hover_color',
            'type' => 'color',
            'title' => esc_html__('Button Hover Color', 'doccure'),
            'subtitle' => esc_html__('Set hover text color for the cta button.', 'doccure'),
            'required' => array('display-cta-button', '=', '1'),
        ),
        array(
            'id' => 'header_colors_divider',
            'type' => 'divide',
            'required' => array('header_type', '=', 'static')
        ),
        
    ),
);

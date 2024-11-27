<?php
/**
 * Subheader Settings
 *
 * @package doccure
 */
return array(
    'title' => esc_html__('Subheader Settings', 'doccure'),
    'id' => 'subheader_settings',
    'icon' => 'el el-file-edit',
    'fields' => array(
        array(
            'id' => 'display_subheader',
            'type' => 'switch',
            'title' => esc_html__('Display Subheader', 'doccure'),
            'default' => 1,
        ),
        array(
            'id' => 'subheader_type',
            'type' => 'select',
            'title' => esc_html__('Subheader type', 'doccure'),
            'options' => array(
                'static' => esc_html__('Static', 'doccure'),
                //'page-template' => esc_html__('Page Template', 'doccure'),
            ),
            'default' => 'static',
            'required' => array('display_subheader', '=', '1'),
        ),
        array(
            'id' => 'subheader_type_page_template',
            'title' => esc_html__('Select Page Template', 'doccure'),
            'subtitle' => esc_html__('Please select a page template to show in the subheader', 'doccure'),
            'type' => 'select',
            'multi' => false,
            'data' => 'posts',
            'args' => array('post_type' => 'doccure_templates', 'numberposts' => -1),
            'required' => array('subheader_type', '=', 'page-template')
        ),
        array(
            'id' => 'subheader_style',
            'type' => 'image_select',
            'title' => esc_html__('Subheader Style', 'doccure'),
            'options' => array(
                'style-1' => array(
                    'img' => get_parent_theme_file_uri('assets/images/theme-options/subheader-settings/subheader-style-1.jpg'),
                    'alt' => esc_html__('Style 1', 'doccure'),
                ),
                
            ),
            'default' => 'style-1',
            'required' => array(
                array('display_subheader', '=', '1'),
                array('subheader_type', '=', 'static')
            ),
        ),
        array(
            'id' => 'display_breadcrumb',
            'type' => 'switch',
            'title' => esc_html__('Display Breadcrumb ?', 'doccure'),
            'default' => 1,
            'required' => array(
                array('display_subheader', '=', '1'),
                array('subheader_type', '=', 'static'),
                array('subheader_style', '!=', 'style-6'),
            ),
        ),
        array(
            'id' => 'breadcrumb_custom_separator',
            'type' => 'switch',
            'title' => esc_html__('Custom breadcrumb separator?', 'doccure'),
            'default' => 1,
            'required' => array(
                array('display_breadcrumb', '=', 1),
                array('subheader_type', '=', 'static')
            ),
        ),
        array(
            'id' => 'breadcrumb_custom_icon',
            'type' => 'select',
            'title' => esc_html__('Select Icon', 'doccure'),
            'subtitle' => esc_html__('Select a custom icon as a seperator between breadcrumb items', 'doccure'),
            'options' => doccure_get_fa_icons(),
            'required' => array(
                array('display_subheader', '=', 1),
                array('breadcrumb_custom_separator', '=', '1'),
                array('subheader_type', '=', 'static')
            ),
        ),
        array(
            'id' => 'breadcrumb_position',
            'type' => 'select',
            'title' => esc_html__('Breadcrumbs Position', 'doccure'),
            'options' => array(
                'before-title' => esc_html__('Before title', 'doccure'),
                'after-title' => esc_html__('After title', 'doccure'),
               // 'below-image' => esc_html__('Below Subheader', 'doccure'),
            ),
            'required' => array(
                array('subheader_style', '=', array('style-1', 'style-5')),
                array('display_breadcrumb', '=', 1),

            ),
            'default' => 'after-title',
        ),
        array(
            'id' => 'breadcrumb_color',
            'type' => 'color',
            'title' => esc_html__('Breadcrumbs text color', 'doccure'),
            'subtitle' => esc_html__('Set the text color for your breadcrumbs', 'doccure'),
            'default' => '#1f2937',
            'required' => array('display_breadcrumb', '=', 1),
            
        ),
        array(
            'id' => 'breadcrumb_link_color',
            'type' => 'color',
            'title' => esc_html__('Breadcrumbs link color', 'doccure'),
            'subtitle' => esc_html__('Set the link color for your breadcrumbs', 'doccure'),
            'default' => '#4b5563',
            'required' => array('display_breadcrumb', '=', 1),
        ),
        array(
            'id' => 'breadcrumb_link_color_hover',
            'type' => 'color',
            'title' => esc_html__('Breadcrumbs link color on hover', 'doccure'),
            'subtitle' => esc_html__('Set the link color on hover for your breadcrumbs', 'doccure'),
            'default' => '#0e82fd',
            'required' => array('display_breadcrumb', '=', 1),
        ),
        array(
            'id' => 'breadcrumb_bg',
            'type' => 'color',
            'title' => esc_html__('Breadcrumbs background color', 'doccure'),
            'subtitle' => esc_html__('Set the background color for your breadcrumbs', 'doccure'),
            'required' => array(
                array('display_breadcrumb', '=', 1),
                array('breadcrumb_position', '=', 'below-image'),
            ),
        ),
        array(
            'id' => 'breadcrumb_bg_color',
            'type' => 'color',
            'title' => esc_html__('Breadcrumbs background color', 'doccure'),
            'subtitle' => esc_html__('Set the background color for your breadcrumbs', 'doccure'),
            'required' => array(
                array('display_breadcrumb', '=', 1),
                array('subheader_style', '=', 'style-2'),
            ),
        ),
        array(
            'id' => 'subheader_alignment',
            'type' => 'select',
            'title' => esc_html__('Subheader Alignment', 'doccure'),
            'options' => array(
                'text-left' => esc_html__('Left', 'doccure'),
                'text-center' => esc_html__('Center', 'doccure'),
                'text-right' => esc_html__('Right', 'doccure'),
            ),
            'default' => 'text-left',
            'required' => array(
                array('display_subheader', '=', '1'),
                array('subheader_type', '=', 'static'),
                array('subheader_style', '=', array('style-1', 'style-2', 'style-3', 'style-5')),
            ),
        ),
       
     
        array(
            'id' => 'subheader_title_color',
            'type' => 'color',
            'title' => esc_html__('Subheader title color', 'doccure'),
            'subtitle' => esc_html__('Set the title color for your subheader', 'doccure'),
            'default' => '#272b41',
            'required' => array(
                array('display_subheader', '=', '1'),
                array('subheader_type', '=', 'static')
            ),
            'output' => array(
                '.doccure-subheader .page-title',
            )
        ),
        array(
            'id' => 'enable_title_stroke',
            'type' => 'switch',
            'title' => esc_html__('Enable Stroke on Title?', 'doccure'),
            'default' => 0,
            'required' => array(
                array('display_subheader', '=', '1'),
                array('subheader_type', '=', 'static'),
                array('subheader_style', '=', 'style-5'),
            ),
        ),
       
        array(
            'id' => 'display_subheader_shapes',
            'type' => 'switch',
            'title' => esc_html__('Display Subheader Shapes?', 'doccure'),
            'default' => 1,
            'required' => array(
                array('display_subheader', '=', 1),
                array('subheader_style', '=', 'style-3')
            ),
        ),
        array(
            'id' => 'shape_style',
            'type' => 'select',
            'title' => esc_html__('Subheader Shape Style', 'doccure'),
            'options' => array(
                'curve' => esc_html__('Curve', 'doccure'),
                'skew' => esc_html__('Skew', 'doccure'),
            ),
            'default' => 'curve',
            'required' => array('display_subheader_shapes', '=', 1),
        ),
      
    ),
);

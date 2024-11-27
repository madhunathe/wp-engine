<?php
/**
 * Header Logo Settings
 *
 * @package doccure
 */
return array(
    'title' => esc_html('Header Logo Settings', 'doccure'),
    'id' => 'header_logo_settings',
    'subsection' => true,
    'fields' => array(
        array(
            'id' => 'site-logo',
            'type' => 'media',
            'url' => true,
            'title' => esc_html__('Site Logo', 'doccure'),
            'compiler' => 'true',
            'default' => array('url' => get_parent_theme_file_uri('assets/images/logo.png')),
            'subtitle' => esc_html__('Upload your logo', 'doccure'),
        ),
        array(
            'id' => 'sticky-logo',
            'type' => 'media',
            'url' => true,
            'title' => esc_html__('Sticky Logo', 'doccure'),
            'compiler' => 'true',
            'default' => array('url' => get_parent_theme_file_uri('assets/images/logo.png')),
            'subtitle' => esc_html__('Will display a secondary logo when header is sticky and scrolling the page. ONLY available if you have Sticky Header enabled in Header settings.', 'doccure'),
        ),
        array(
            'id' => 'mobile-logo',
            'type' => 'media',
            'url' => true,
            'title' => esc_html__('Mobile Logo', 'doccure'),
            'compiler' => 'true',
            'default' => array('url' => get_parent_theme_file_uri('assets/images/logo.png')),
            'subtitle' => esc_html__('Upload your mobile logo', 'doccure'),
        ),
        
    ),
);

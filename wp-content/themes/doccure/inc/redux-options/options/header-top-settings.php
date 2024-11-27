<?php
/**
 * Header Logo Settings
 *
 * @package doccure
 */
return array(
    'title' => esc_html('Header Top Settings', 'doccure'),
    'id' => 'header_top_settings',
    'subsection' => true,
    'fields' => array(
        array(
            'id' => 'display_top_header',
            'type' => 'switch',
            'title' => esc_html__('Display Top Header', 'doccure'),
            'subtitle' => esc_html__('Please choose if you want to display the top header or not.', 'doccure'),
            'default' => 0,
            'required' => array('header-layout', '!=', 'layout-9'),
        ),
        array(
            'id' => 'adjust-custom-header-top-width',
            'type' => 'switch',
            'title' => esc_html__('Set Custom Header Top Width', 'doccure'),
            'default' => false,
            'subtitle' => esc_html__('Enable to set custom header top width.', 'doccure'),
            'required' => array(
                array('display_top_header', '=', '1'),
            ),
        ),
        array(
            'id' => 'header-top-width-style',
            'type' => 'select',
            'title' => esc_html__('Select Top Header Width', 'doccure'),
            'options' => array(
                'full-width' => esc_html__('Full Width', 'doccure'),
                'custom-width' => esc_html__('Custom Width', 'doccure'),
            ),
            'required' => array('adjust-custom-header-top-width', '=', '1'),
        ),
        array(
            'id' => 'header_top_content_size_custom',
            'type' => 'slider',
            'title' => esc_html__('Header Top Custom Content Size', 'doccure'),
            'subtitle' => esc_html__('Select your desired Header top content size', 'doccure'),
            'min' => 720,
            'step' => 1,
            'max' => 1900,
            'resolution' => 1,
            'display_value' => 'text',
            'required' => array('header-top-width-style', '=', 'custom-width'),
        ),
        array(
            'id' => 'top_header_cntct_details',
            'type' => 'divide'
        ),
        array(
            'id' => 'display_top_header_contact_info',
            'type' => 'switch',
            'title' => esc_html__('Display Contact Info', 'doccure'),
            'subtitle' => esc_html__('Please choose if you want to display the contact info in top header.', 'doccure'),
            'default' => 0,
            'required' => array(
                array('display_top_header', '=', '1'),
                array('header-layout', '=', array('layout-1', 'layout-2', 'layout-5', 'layout-7', 'layout-8', 'layout-10')),
            ),
        ),
        array(
            'id' => 'display_top_email_address',
            'type' => 'switch',
            'title' => esc_html__('Display Email Address', 'doccure'),
            'default' => 0,
            'subtitle' => esc_html__('Enable to display your email address', 'doccure'),
            'desc' => esc_html__('Note: This field should be filled from the Contact Information tab', 'doccure'),
            'required' => array('display_top_header_contact_info', '=', '1'),
        ),
        array(
            'id' => 'display_top_phone_number',
            'type' => 'switch',
            'title' => esc_html__('Display Phone Number', 'doccure'),
            'default' => 0,
            'subtitle' => esc_html__('Enable to display your phone number', 'doccure'),
            'desc' => esc_html__('Note: This field should be filled from the Contact Information tab', 'doccure'),
            'required' => array('display_top_header_contact_info', '=', '1'),
        ),
        array(
            'id' => 'top_header_cta',
            'type' => 'divide'
        ),
        array(
            'id' => 'display_top_cta',
            'type' => 'switch',
            'title' => esc_html__('Display Call To Action', 'doccure'),
            'subtitle' => esc_html__('Please enable if you want to display the call to action button in top header.', 'doccure'),
            'default' => 0,
            'required' => array(
                array('display_top_header', '=', '1'),
                array('header-layout', '=', array('layout-1', 'layout-2', 'layout-3', 'layout-4', 'layout-6', 'layout-7', 'layout-8')),
            ),
        ),
        array(
            'id' => 'top_cta_btn_title',
            'type' => 'text',
            'title' => esc_html__('CTA Title', 'doccure'),
            'subtitle' => esc_html__('Please enter call to action button text.', 'doccure'),
            'required' => array('display_top_cta', '=', '1'),
        ),
        array(
            'id' => 'top_cta_btn_link',
            'type' => 'text',
            'title' => esc_html__('CTA Link', 'doccure'),
            'subtitle' => esc_html__('Please enter call to action button link.', 'doccure'),
            'required' => array('display_top_cta', '=', '1'),
            'validate' => 'url'
        ),
        array(
            'id' => 'header_top_btn_bg_color',
            'type' => 'color',
            'title' => esc_html__('Header CTA Background Color', 'doccure'),
            'subtitle' => esc_html__('Set background color for call to action button.', 'doccure'),
            'required' => array('display_top_cta', '=', '1'),
        ),
        array(
            'id' => 'header_top_btn_color',
            'type' => 'color',
            'title' => esc_html__('Header CTA Color', 'doccure'),
            'subtitle' => esc_html__('Set color for call to action button.', 'doccure'),
            'required' => array('display_top_cta', '=', '1'),
        ),
        array(
            'id' => 'top_header_social_links',
            'type' => 'divide'
        ),
        array(
            'id' => 'display_social_media',
            'type' => 'switch',
            'title' => esc_html__('Display Social Links', 'doccure'),
            'subtitle' => esc_html__('Please choose if you want to display social media links.', 'doccure'),
            'default' => 0,
            'required' => array(
                array('display_top_header', '=', '1'),
                array('header-layout', '=', array('layout-1', 'layout-3', 'layout-4', 'layout-5', 'layout-6', 'layout-7', 'layout-8', 'layout-10')),
            ),
        ),
        array(
            'id' => 'header_top_social_bg_color',
            'type' => 'color',
            'title' => esc_html__('Social Link Background Color', 'doccure'),
            'subtitle' => esc_html__('Set background color for the top header social links.', 'doccure'),
            'required' => array(
                array('display_social_media', '=', '1'),
                array('header-layout', '!=', 'layout-5'),
                array('header-layout', '!=', 'layout-7'),
                array('header-layout', '!=', 'layout-8'),
                array('header-layout', '!=', 'layout-10'),
            ),
        ),
        array(
            'id' => 'header_top_social_color',
            'type' => 'color',
            'title' => esc_html__('Social Link Color', 'doccure'),
            'subtitle' => esc_html__('Set color for the top header social links.', 'doccure'),
            'required' => array('display_social_media', '=', '1'),
        ),
        array(
            'id' => 'header_top_social_border_color',
            'type' => 'color',
            'title' => esc_html__('Social Link Border Color', 'doccure'),
            'subtitle' => esc_html__('Set border color for the top header social links.', 'doccure'),
            'required' => array(
                array('display_social_media', '=', '1'),
                array('header-layout', '!=', 'layout-5'),
                array('header-layout', '!=', 'layout-7'),
                array('header-layout', '!=', 'layout-8'),
                array('header-layout', '!=', 'layout-10'),
            ),
        ),
        array(
            'id' => 'header_top_social_hover_bg_color',
            'type' => 'color',
            'title' => esc_html__('Social Link Hover Background Color', 'doccure'),
            'subtitle' => esc_html__('Set hover background color for the top header social links.', 'doccure'),
            'required' => array(
                array('display_social_media', '=', '1'),
                array('header-layout', '!=', 'layout-5'),
                array('header-layout', '!=', 'layout-7'),
                array('header-layout', '!=', 'layout-8'),
                array('header-layout', '!=', 'layout-10'),
            ),
        ),
        array(
            'id' => 'header_top_social_hover_color',
            'type' => 'color',
            'title' => esc_html__('Social Link Hover Color', 'doccure'),
            'subtitle' => esc_html__('Set hover color for the top header social links.', 'doccure'),
            'required' => array('display_social_media', '=', '1'),
        ),
        array(
            'id' => 'header_top_social_hover_border_color',
            'type' => 'color',
            'title' => esc_html__('Social Link Hover Border Color', 'doccure'),
            'subtitle' => esc_html__('Set hover border color for the top header social links.', 'doccure'),
            'required' => array(
                array('display_social_media', '=', '1'),
                array('header-layout', '!=', 'layout-5'),
                array('header-layout', '!=', 'layout-7'),
                array('header-layout', '!=', 'layout-8'),
                array('header-layout', '!=', 'layout-10'),
            ),
        ),
        array(
            'id' => 'top_header_color_options',
            'type' => 'divide'
        ),
        array(
            'id' => 'header_top_bg',
            'type' => 'color',
            'title' => esc_html__('Header top background color', 'doccure'),
            'subtitle' => esc_html__('Set background color for the top header.', 'doccure'),
            'required' => array('display_top_header', '=', '1'),
        ),
        array(
            'id' => 'header_top_color',
            'type' => 'color',
            'title' => esc_html__('Header top color', 'doccure'),
            'subtitle' => esc_html__('Set color for the top header.', 'doccure'),
            'required' => array('display_top_header', '=', '1'),
            'required' => array(
                array('display_top_header', '=', '1'),
                array('header-layout', '!=', 'layout-5'),
                array('header-layout', '!=', 'layout-7'),
                array('header-layout', '!=', 'layout-8'),
                array('header-layout', '!=', 'layout-10'),
            ),
        ),
        array(
            'id' => 'header_top_color_hover',
            'type' => 'color',
            'title' => esc_html__('Header top color on hover', 'doccure'),
            'subtitle' => esc_html__('Set color on hover for the top header.', 'doccure'),
            'required' => array(
                array('display_top_header', '=', '1'),
                array('header-layout', '!=', 'layout-5'),
                array('header-layout', '!=', 'layout-7'),
                array('header-layout', '!=', 'layout-8'),
                array('header-layout', '!=', 'layout-10'),
            ),
        ),
        array(
            'id' => 'header_top_border_color',
            'type' => 'color',
            'title' => esc_html__('Header border color', 'doccure'),
            'subtitle' => esc_html__('Set bottom border color top header.', 'doccure'),
            'required' => array(
              array('display_top_header', '=', '1'),
            ),
        ),
    ),
);

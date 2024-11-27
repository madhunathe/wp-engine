<?php

/**
 * Contact Info
 *
 * @package doccure
 */

return array(

    'title' => esc_html__('Header Information', 'doccure'),

    'id' => 'site_info',

    'customizer_width' => '400px',

    'icon' => 'el el-envelope',

    'fields' => array(

        array(

            'id' => 'contact_email',

            'type' => 'text',

            'title' => esc_html__('Company Email', 'doccure'),

            'subtitle' => esc_html__('Please enter contact email address.', 'doccure'),

            'validate' => 'email',

            'msg' => esc_html__('Please enter valid email.', 'doccure'),

        ),

        array(

            'id' => 'contact_phone',

            'type' => 'text',

            'title' => esc_html__('Company Phone Number', 'doccure'),

            'subtitle' => esc_html__('Please enter contact phone number.', 'doccure'),

        ),
        array(

            'id' => 'social_infos',

            'type' => 'select',

            'options' => array(

                'facebook-f' => esc_html__('Facebook', 'doccure'),

                'twitter' => esc_html__('Twitter', 'doccure'),

                'dribbble' => esc_html__('Dribbble', 'doccure'),

                'vimeo-v' => esc_html__('Vimeo', 'doccure'),

                'pinterest-p' => esc_html__('Pinterest', 'doccure'),

                'linkedin-in' => esc_html__('LinkedIn', 'doccure'),

                'youtube' => esc_html__('Youtube', 'doccure'),

                'instagram' => esc_html__('Instagram', 'doccure'),

            ),

            'multi' => true,

            'sortable' => true,

            'title' => esc_html__('Social info to display.', 'doccure'),

            'subtitle' => esc_html__('Arrange the fields you wanted to display.', 'doccure'),

        ),

        array(

            'id' => 'facebook-f_link',

            'type' => 'text',

            'title' => esc_html__('Facebook Url', 'doccure'),

            'subtitle' => esc_html__('Enter facebook url.', 'doccure'),

        ),

        array(

            'id' => 'twitter_link',

            'type' => 'text',

            'title' => esc_html__('Twitter Url', 'doccure'),

            'subtitle' => esc_html__('Enter twitter url.', 'doccure'),

        ),

        array(

            'id' => 'dribbble_link',

            'type' => 'text',

            'title' => esc_html__('Dribbble Url', 'doccure'),

            'subtitle' => esc_html__('Enter dribbble url.', 'doccure'),

        ),

        array(

            'id' => 'vimeo-v_link',

            'type' => 'text',

            'title' => esc_html__('Vimeo Url', 'doccure'),

            'subtitle' => esc_html__('Enter vimeo url.', 'doccure'),

        ),

        array(

            'id' => 'pinterest-p_link',

            'type' => 'text',

            'title' => esc_html__('Pinterest Url', 'doccure'),

            'subtitle' => esc_html__('Enter pinterest url.', 'doccure'),

        ),

        array(

            'id' => 'linkedin-in_link',

            'type' => 'text',

            'title' => esc_html__('LinkedIn Url', 'doccure'),

            'subtitle' => esc_html__('Enter linkedin url.', 'doccure'),

        ),

        array(

            'id' => 'youtube_link',

            'type' => 'text',

            'title' => esc_html__('Youtube Url', 'doccure'),

            'subtitle' => esc_html__('Enter youtube url.', 'doccure'),

        ),

        array(

            'id' => 'instagram_link',

            'type' => 'text',

            'title' => esc_html__('Instagram Url', 'doccure'),

            'subtitle' => esc_html__('Enter instagram url.', 'doccure'),

        ),

        array(

            'id' => 'pinterest_link',

            'type' => 'text',

            'title' => esc_html__('Pinterest Url', 'doccure'),

            'subtitle' => esc_html__('Enter pinterest url.', 'doccure'),

        ),

    ),

);


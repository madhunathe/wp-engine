<?php
/**
 * Stylings Settings
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */

 return array(
        'title'            => esc_html__( 'Stylings', 'doccure' ),
        'id'               => 'styling_settings',
        'subsection'       => false,
		'icon'			   => 'el el-css',
        'fields'           => array(
			array(
                'id'       => 'site_colors',
                'type'     => 'switch',
                'title'    => esc_html__( 'Site colors', 'doccure' ),
                'default'  => false,
				'desc'     => esc_html__( '', 'doccure' ),
            ),	
			array(
				'id'       => 'theme_header_color',
				'type'     => 'color',
				'title'    => esc_html__('Theme header Color', 'doccure'), 
				'subtitle' => esc_html__('Pick a theme header color for the theme (default: #0e82fd).', 'doccure'),
				'default'  => '#0e82fd',
				'required' => array( 'site_colors', '=', true ),
			),

			array(
				'id'       => 'theme_mheader_color',
				'type'     => 'color',
				'title'    => esc_html__('Theme mobile header background', 'doccure'), 
				'subtitle' => esc_html__('Pick a mobile header background color for the theme (default: #0e82fd).', 'doccure'),
				'default'  => '#0e82fd',
				'required' => array( 'site_colors', '=', true ),
			),

			array(
				'id'       => 'theme_breadcrumb_color',
				'type'     => 'color',
				'title'    => esc_html__('Theme breadcrumb background', 'doccure'), 
				'subtitle' => esc_html__('Breadcrumb background color for the theme (default: #15558d).', 'doccure'),
				'default'  => '#15558d',
				'required' => array( 'site_colors', '=', true ),
			),
			 
			 
 
			array(
				'id'       => 'theme_primary_color',
				'type'     => 'color',
				'title'    => esc_html__('Theme Primary Color', 'doccure'), 
				'subtitle' => esc_html__('Pick a theme color for the theme (default: #0e82fd).', 'doccure'),
				'default'  => '#0e82fd',
				'required' => array( 'site_colors', '=', true ),
			),
			array(
				'id'       => 'theme_secondary_color',
				'type'     => 'color',
				'title'    => esc_html__('Theme Secondary Color', 'doccure'), 
				'subtitle' => esc_html__('Pick a Secondary color for the theme (default: #09e5ab).', 'doccure'),
				'default'  => '#09e5ab',
				'required' => array( 'site_colors', '=', true ),
			),
			array(
				'id'       => 'thm_base',
				'type'     => 'color',
				'title'    => esc_html__('Theme Base Color', 'doccure'), 
				'subtitle' => esc_html__('Pick a base color for the theme (default: #15558d).', 'doccure'),
				'default'  => '#15558d',
				'required' => array( 'site_colors', '=', true ),
			),
			array(
				'id'       => 'thm_base_hover',
				'type'     => 'color',
				'title'    => esc_html__('Theme Base Hover Color', 'doccure'), 
				'subtitle' => esc_html__('Pick a base hover color for the theme (default: #09dca4).', 'doccure'),
				'default'  => '#09dca4',
				'required' => array( 'site_colors', '=', true ),
			),

			array(
				'id'       => 'theme_tertiary_color',
				'type'     => 'color',
				'title'    => esc_html__('Theme Tertiary Color', 'doccure'), 
				'subtitle' => esc_html__('Pick a theme color for the theme (default: #6B7280).', 'doccure'),
				'default'  => '#6B7280',
				'required' => array( 'site_colors', '=', true ),
			),
			array(
				'id'       => 'theme_footer_color',
				'type'     => 'color',
				'title'    => esc_html__('Theme Footer Color', 'doccure'), 
				'subtitle' => esc_html__('Pick a footer color for the theme (default: #F9FAFB).', 'doccure'),
				'default'  => '#F9FAFB',
				'required' => array( 'site_colors', '=', true ),
			),
			array(
				'id'       => 'theme_footer_tcolor',
				'type'     => 'color',
				'title'    => esc_html__('Theme Footer Text Color', 'doccure'), 
				'subtitle' => esc_html__('Pick a footer color for the theme (default: #1F2937).', 'doccure'),
				'default'  => '#1F2937',
				'required' => array( 'site_colors', '=', true ),
			)
		)
 );
 
<?php
/**
 * Article Settings
 *
 * @throws error
 * @return 
 */

Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Article Settings', 'doccure' ),
        'id'               => 'article_settings',
		'desc'       	   => '',
		'subsection'       => false,
		'icon' 			   => 'el el-edit',
        'fields'           => array(
			array(
				'id'       => 'article_option',
				'type'     => 'select',
				'title'    => esc_html__( 'Article status?', 'doccure' ),
				'desc'     => esc_html__( 'Select either articles should be published or needs the admin approval before publish.', 'doccure' ),
				'options'  => array(
					'pending' 	=> esc_html__('Needs approval', 'doccure'),
					'publish' 	=> esc_html__('Auto published', 'doccure') 
				),
				'default'  => 'pending',
			),
		)
	)
);
<?php
/**
 * Directory Settings
 *
 * @package doccure
 */ 

Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Directory Settings', 'doccure' ),
        'id'               => 'directory_general_settings',
        'subsection'       => false,
		'icon'			   => 'el el-time',
        'fields'           => array(
			array(
				'id'   	=>'directories_divider',
				'type' 	=> 'info',
				'title' => esc_html__('General Settings', 'doccure'),
				'style' => 'info',
			),
			array(
				'id'       => 'doctor_location',
				'type'     => 'select',
				'title'    => esc_html__('Doctors locations', 'doccure'), 
				'desc' => esc_html__('Doctors availability locations where he provide the services. It could be only one clinic or both at the same time.', 'doccure'),
				'options'  => array(
					'clinic' 		=> esc_html__('Doctors own clinic', 'doccure')
				),
				'default'  => 'clinic',
			),
			array(
				'id'       => 'listing_type',
				'type'     => 'select',
				'title'    => esc_html__('System Access type?', 'doccure'), 
				'desc' => wp_kses( __( 'Please select only one of the following options.<br/>
1) In "Paid Listings" doctors have to buy a package to get online appointments<br/>
2) In "Free listings" All the features are free to use', 'doccure' ),array(
																'a' => array(
																	'href' => array(),
																	'title' => array()
																),
																'br' => array(),
																'em' => array(),
																'strong' => array(),
															)),
				'options'  => array(
				    'paid' 	=> esc_html__('Paid Listing', 'doccure'),
					'free' 	=> esc_html__('Free Listing', 'doccure') 
				),
				'default'  => 'free',
			),
			
			
			
			
			array(
                'id'       => 'base_name_disable',
                'type'     => 'switch',
                'title'    => esc_html__( 'Enable/Disable Base name', 'doccure' ),
                'default'  => false,
				'desc'     => esc_html__( 'Enable or Disable Base name for front-end pages.', 'doccure' ),
			),
			array(
				'id'    => 'name_base_doctors',
				'type'  => 'multi_text',
				'show_empty'  => false,
				'title' => esc_html__( 'Names base for doctors', 'doccure' ),
				'desc'	=> esc_html__('Add name base for doctors like Dr, Prof. etc','doccure'),
				'required' => array( 'base_name_disable', '=', true ),
			),
			array(
				'id'    => 'name_base_users',
				'type'  => 'multi_text',
				'show_empty'  => false,
				'title' => esc_html__( 'Names base for regular users', 'doccure' ),
				'desc'	=> esc_html__('Add name base for regular users like Mr, Miss. etc','doccure'),
				'required' => array( 'base_name_disable', '=', true ),
			),
			array(
				'id'    => 'dashboard_tpl',
				'type'  => 'select',
				'title' => esc_html__( 'Select dashboard page', 'doccure' ), 
				'data'  => 'pages'
			),
			array(
				'id'    => 'dir_datasize',
				'type'  => 'text',
				'title' => esc_html__( 'Add upload size', 'doccure' ), 
				'desc' => esc_html__('Maximum image upload size. Max 5MB, add in bytes. for example 5MB = 5242880','doccure'),
				'default' => '5242880',
			),
			
			
			array(
				'title' 	=> esc_html__( 'Calendar Date Format', 'doccure' ),
				'id'  		=> 'calendar_format',
				'type'  	=> 'select',
				'value'  	=> 'Y-m-d',
				'desc' 		=> esc_html__('Select your calendar date format.', 'doccure'),
				'options'	=> array(
					'Y-m-d'	  => 'Y-m-d',
					'Y/m/d'	  => 'Y/m/d',
				)
			),
			
			
			array(
				'id'    => 'feedback_questions',
				'type'  => 'multi_text',
				'title' => esc_html__( 'Feedback Question', 'doccure' ),
				'desc'	=> esc_html__('Add feedback questions.','doccure')
			),
			
			array(
                'id'       => 'enable_gallery',
                'type'     => 'switch',
                'title'    => esc_html__( 'Enable/Disable Gallery', 'doccure' ),
                'default'  => true,
				'desc'     => esc_html__( 'Enable or Disable gallery for doctor.', 'doccure' ),
			),
			array(
                'id'       => 'enable_seo',
                'type'     => 'switch',
                'title'    => esc_html__( 'Enable/Disable SEO', 'doccure' ),
                'default'  => true,
				'desc'     => esc_html__( 'Enable or Disable seo for post and pages.', 'doccure' ),
			),
		 
			array(
                'id'       => 'hide_services_by_package',
                'type'     => 'select',
                'title'    => esc_html__('Services according to package', 'doccure'),
                'desc'     => esc_html__('Hide services according to package limit on doctor detail page', 'doccure'),
                'options'  => array(
                    'yes' 	=> esc_html__('Yes', 'doccure'),
                    'no' 	=> esc_html__('No', 'doccure')
                ),
                'default'  => 'no',
            ),
			array(
				'id'   	=>'directories_divider2',
				'type' 	=> 'info',
				'title' => esc_html__('Chat Settings', 'doccure'),
				'style' => 'info',
			),
			array(
				'id'       => 'chat',
				'type'     => 'select',
				'title'    => esc_html__('Real Time Chat?', 'doccure'), 
				'desc'     => esc_html__('Enable real time chat or use simple inbox system.', 'doccure'),
				'options'  => array(
					'inbox' 	=> esc_html__('Inbox', 'doccure')
				),
			),
			array(
				'id'       => 'host',
				'type'     => 'text',
				'title'    => esc_html__( 'Host?', 'doccure' ),
				'desc'     => __( 'Please add the host, default would be http://localhost <br>1. Host could be either http://localhost <br>2. OR could be http://yourdomain.com', 'doccure' ),
				'default'  => '',
				'required' => array( 'chat', '=', 'chat' ),
			),
			array(
				'id'       => 'port',
				'type'     => 'text',
				'title'    => esc_html__( 'Port?', 'doccure' ),
				'desc'     => esc_html__( '', 'doccure' ),
				'default'  => '3000',
				'required' => array( 'chat', '=', 'chat' ),
			),
			array(
				'id'       => 'guppy',
				'type'     => 'info',
				'title'    => esc_html__( 'WP Guppy Chat Solution', 'doccure' ),
				'desc' => wp_kses( __( 'Install the WP Guppy plugin first. <a href="https://wp-guppy.com/" target="_blank">Get WP Guppy plugin</a>
									', 'doccure'),array(
														'a' => array(
															'href' => array(),
															'title' => array(),
															'target' => array()
														),
														'br' => array(),
														'em' => array(),
														'strong' => array(),
													)),
				'required' => array( 'chat', '=', 'guppy' ),
			),
		)
	)
);



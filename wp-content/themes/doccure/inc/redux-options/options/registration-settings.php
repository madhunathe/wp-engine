<?php
/**
 * REGISTRATION Settings
 *
 * @package doccure
 */ 

$doctros_pages	= apply_filters( 'doccure_doctor_redirect_after_login','');
Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Registration Settings', 'doccure' ),
        'id'               => 'registration_settings',
		'desc'       	   => '',
		'icon' 			   => 'el el-child',
		'subsection'       => false,
        'fields'           => array(
			array(
				'id'		=> 'step_image',
				'type' 		=> 'media',
				'url'       => false,
				'title' 	=> esc_html__('Image', 'doccure'),
				'desc' 		=> esc_html__('Upload Image to be shown on the registration form', 'doccure'),
			),
			array(
				'id'       => 'step_title',
				'type'     => 'text',
				'title'    => esc_html__( 'title', 'doccure' ),
				'desc'     => esc_html__( 'Add title, which will serve as title on registration form', 'doccure'),
				'default'  => 'Join For a Good Start',
			),
			array(
				'id'       => 'step_description',
				'type'     => 'textarea',
				'title'    => esc_html__( 'Description', 'doccure' ),
				'desc'     => esc_html__( 'Add description, which will serve as description on registration form', 'doccure'),
				'default'  => '',
			),
			array(
				'id'       => 'user_registration',
				'type'     => 'switch',
				'title'    => esc_html__( 'Login/Register', 'doccure' ),
				'default'  => false,
				'desc'     => esc_html__( 'Enable/Disable registration', 'doccure' ),
			),	
			array(
				'id'       => 'registration_form',
				'type'     => 'switch',
				'title'    => esc_html__( 'Enable Registration Form?', 'doccure' ),
				'default'  => false,
				'desc'     => esc_html__( 'Enable/Disable registration form', 'doccure' ),
				'required' => array( 'user_registration', '=', true ),
			),
			array(
				'id'       => 'user_type_registration',
				'type'     => 'select',
				'multi'    => true,
				'title'    => esc_html__('Show Registration type ?', 'doccure'), 
				'desc'     => esc_html__('Show registration type by role. You can either select all to enable all the types of registration or you can remove from available list. For example hide Patients registration from front-end. At-least one registration type would be required', 'doccure'),
				'options'  => array('doctors'=> esc_html__('Doctors','doccure'),
									// 'hospitals'=> esc_html__('Hospitals','doccure'),
									'regular_users'=> esc_html__('Patients','doccure'),
									// 'seller'=> esc_html__('Pharmacy(Vendor)','doccure')
								   ),
				'default'  => array('doctors','regular_users'),
				'required' => array( 'user_registration', '=', true ),
			),
			array(
				'id'       	=> 'login_form',
				'type'     	=> 'switch',
				'title'    	=> esc_html__( 'Login / Register?', 'doccure' ),
				'default'  	=> false,
				'desc'		=> esc_html__('Enable Login / Register form.','doccure'),
				'required' 	=> array( 'user_registration', '=', true ),
			),
			array(
				'id'    => 'login_page',
				'type'  => 'select',
				'title' => esc_html__( 'Choose Page', 'doccure' ), 
				'desc'	=> esc_html__('Choose registeration template page.','doccure'),
				'data'  => 'pages',
				'required' => array( 'user_registration', '=', true ),
			),
			array(
				'id'       => 'verify_user',
				'type'     => 'select',
				'title'    => esc_html__( 'Verification', 'doccure' ),
				'desc' => esc_html__('Note: If you select "Need to verify, after registration" then user will not be shown in search result until user will be verified by site owner. If you select "Verify by email" then users will get an email for verification. After clicking link user will be verified and available at the website.', 'doccure'),
				'options'	=> array(
					//'yes'   => esc_html__('Verify by email', 'doccure'),
					//'no'	=> esc_html__('Need to verify, after registration by admin', 'doccure'),
					'remove'   => esc_html__('Remove verification all over the site', 'doccure'),
				),
				'default'  => 'remove',
				'required' => array( 'user_registration', '=', true ),
			),

			array(
				'id'       => 'term_text',
				'type'     => 'textarea',
				'title'    => esc_html__( 'Term text', 'doccure' ),
				'desc'     => esc_html__( '', 'doccure' ),
				'default'  => ''
			),
			array(
				'id'    => 'term_page',
				'type'  => 'select',
				'title' => esc_html__( 'Select Term Page', 'doccure' ), 
				'data'  => 'pages'
			),
	
			array(
				'id'       => 'remove_location',
				'type'     => 'select',
				'title'    => esc_html__( 'Remove location field', 'doccure' ),
				'desc' 		=> esc_html__('Remove location field from registration form', 'doccure'),
				'options'	=> array(
					'yes'   => esc_html__('Yes', 'doccure'),
					'no'	=> esc_html__('No', 'doccure'),
				),
				'default'  => 'no',
			),
			array(
				'id'       => 'doctors_redirect_page',
				'type'     => 'select',
				'title'    => esc_html__('Redirect URL for doctor', 'doccure'), 
				'desc'     => esc_html__('Redirect URL for doctor after login and registration.', 'doccure'),
				'options'  => $doctros_pages,
				'default'  => 'dashboard',
			)
		)
	)
);
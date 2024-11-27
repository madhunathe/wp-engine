<?php
/**
 * Email Settings
 *
 * @package doccure 
 */

Redux::setSection( $opt_name, array(
	'title' => esc_html__( 'Email Settings', 'doccure' ),
	'id' => 'email_settings',
	'desc' => '',
	'icon' => 'el el-inbox',
	'subsection' => false,
	'fields' => array(
		array(
			'id' => 'divider_1',
			'type' => 'info',
			'title' => esc_html__( 'General Settings', 'doccure' ),
			'style' => 'info',
		),
		array(
			'id' => 'email_logo',
			'type' => 'media',
			'compiler' => 'true',
			'url' => true,
			'title' => esc_html__( 'Email Logo', 'doccure' ),
			'desc' => esc_html__( 'Upload your email logo here.', 'doccure' ),
		),
		array(
			'id' 		=> 'email_logo_width',
			'type' 		=> 'slider',
			'title' 	=> esc_html__('Set logo width', 'doccure'),
			'desc' 		=> esc_html__('Leave it empty to use default', 'doccure'),
			"default" 	=> 100,
			"min" 		=> 0,
			"step" 		=> 1,
			"max" 		=> 500,
			'display_value' => 'label',
		),
		array(
			'id' => 'email_banner',
			'type' => 'media',
			'compiler' => 'true',
			'title' => esc_html__( 'Email Banner', 'doccure' ),
			'desc' => esc_html__( 'Upload your email banner here.', 'doccure' ),
		),
		array(
			'id' => 'email_sender_avatar',
			'type' => 'media',
			'compiler' => 'true',
			'title' => esc_html__( 'Email Sender Avatar', 'doccure' ),
			'desc' => esc_html__( 'Upload email sender picture here.', 'doccure' ),
		),
		array(
			'id' => 'email_copyrights',
			'type' => 'textarea',
			'title' => esc_html__( 'Footer copyright text', 'doccure' ),
			'desc' => esc_html__( 'Add copyright text for the emails in footer', 'doccure' ),
		),
		array(
			'id' => 'email_sender_name',
			'type' => 'text',
			'title' => esc_html__( 'Email Sender Name', 'doccure' ),
			'desc' => esc_html__( 'Add email sender name here like: Shawn Biyeam. Default your site name will be used.', 'doccure' ),
			'default' => 'Dreamstechnologies.Pvt.ltd',
		),
		array(
			'id' => 'email_sender_tagline',
			'type' => 'text',
			'title' => esc_html__( 'Email Sender Tagline', 'doccure' ),
			'desc' => esc_html__( 'Add email sender tagline here like: Team doccure. Default your site tagline will be used.', 'doccure' ),
			'default' => esc_html__( 'Your software partner', 'doccure' ),
		),
		array(
			'id' => 'email_sender_url',
			'type' => 'text',
			'title' => esc_html__( 'Email Sender URL', 'doccure' ),
			'desc' => esc_html__( 'Add email sender url here.', 'doccure' ),
			'default' => 'dreamstechnologies.com',
		),
		array(
			'id' => 'footer_bg_color',
			'type' => 'color',
			'default' => '#3d4461',
			'title' => esc_html__( 'Footer background color', 'doccure' ),
			'desc' => esc_html__( 'Add email footer background color', 'doccure' )
		),
		array(
			'id' => 'footer_text_color',
			'type' => 'color',
			'default' => '#FFF',
			'title' => esc_html__( 'Footer text color', 'doccure' ),
			'desc' => esc_html__( 'Add email footer text color', 'doccure' )
		),
	)
) );

Redux::setSection( $opt_name, array(
	'title' => esc_html__( 'General Templates', 'doccure' ),
	'id' => 'general_templates',
	'desc' => esc_html__( 'Registration templates', 'doccure' ),
	'subsection' => true,
	'fields' => array(

		
		array(
			'id' => 'divider_general_templates',
			'type' => 'info',
			'title' => esc_html__( 'Email template for doctor registration', 'doccure' ),
			'style' => 'info',
		),
		array(
			'id' => 'doctor_registration_subject',
			'type' => 'text',
			'default' => esc_html__( 'Thank you for registeration!', 'doccure' ),
			'title' => esc_html__( 'Subject', 'doccure' ),
			'desc' => esc_html__( 'Please add subject for email', 'doccure' )
		),
		array(
			'id' => 'divider_general_information',
			'desc' => wp_kses( __( '%name% — To display the doctor name.<br>
%email% — To display the doctor email address.<br>
%password% — To display the password for login.<br>
%verification_link% — To display verification link.<br>
%site% — To display the site name.<br>
%signature% — To display site logo.', 'doccure' ), array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			) ),
			'title' => esc_html__( 'Email setting variables', 'doccure' ),
			'type' => 'info',
			'class' => 'dc-center-content',
			'icon' => 'el el-info-circle'
		),
		array(
			'id' => 'doctor_registration_content',
			'type' => 'editor',
			'default' => 'Hello %name%!
							Thanks for registeration on our %site%. You can now verify your account by clicking below link
							%verification_link%
							%signature%',
			'title' => esc_html__( 'Email Contents', 'doccure' )
		),
		
		array(
			'id' => 'divider_regular_user_templates',
			'type' => 'info',
			'title' => esc_html__( 'Email template for Patients', 'doccure' ),
			'style' => 'info',
		),
		array(
			'id' => 'regular_registration_subject',
			'type' => 'text',
			'default' => esc_html__( 'Thank you for registeration!', 'doccure' ),
			'title' => esc_html__( 'Subject', 'doccure' ),
			'desc' => esc_html__( 'Please add subject for email', 'doccure' )
		),
		array(
			'id' => 'divider_regular_registration_information',
			'desc' => wp_kses( __( '%name% — To display the hospital name.<br>
%email% — To display the hospital email address.<br>
%password% — To display the password for login.<br>
%verification_link% — To display verification link.<br>
%site% — To display the site name.<br>
%signature% — To display site logo.', 'doccure' ), array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			) ),
			'title' => esc_html__( 'Email setting variables', 'doccure' ),
			'type' => 'info',
			'class' => 'dc-center-content',
			'icon' => 'el el-info-circle'
		),
		array(
			'id' => 'regular_registration_content',
			'type' => 'editor',
			'default' => 'Hello %name%!
							Thanks for registeration on our %site%. You can now login to manage your account using the below details credentials:
							Email: %email%
							Password: %password%
							%signature%',
			'title' => esc_html__( 'Email Contents', 'doccure' )
		),
		
		//start new
		

		array(
			'id' => 'divider_remove_account_templates',
			'type' => 'info',
			'title' => esc_html__( 'Email template to delete account', 'doccure' ),
			'style' => 'info',
		),
		array(
			'id' => 'remove_account_email',
			'type' => 'text',
			'default' => esc_html__( 'Remove account', 'doccure' ),
			'title' => esc_html__( 'Remove account email', 'doccure' ),
			'desc' => esc_html__( 'Please add email address to receive notice.', 'doccure' )
		),
		array(
			'id' => 'remove_account_subject',
			'type' => 'text',
			'default' => 'Account has been deleted!',
			'title' => esc_html__( 'Subject', 'doccure' ),
			'desc' => esc_html__( 'Please add subject for email', 'doccure' )
		),
		array(
			'id' => 'divider_remove_account_information',
			'desc' => wp_kses( __( '%name% — To display the user name.<br>
%message% — To display content or message.<br>
%reason% — To display link reopen account.<br>
%signature% — To display site logo.', 'doccure' ), array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			) ),
			'title' => esc_html__( 'Email setting variables', 'doccure' ),
			'type' => 'info',
			'class' => 'dc-center-content',
			'icon' => 'el el-info-circle'
		),
		array(
			'id' => 'remove_account_content',
			'type' => 'editor',
			'default' => 'Hi,
							An existing user has deleted the account due to the following reason: 
							
							%reason%
							
							%signature%,',
			'title' => esc_html__( 'Email Contents', 'doccure' )
		),
	
		array(
			'id' 	=> 'divider_resend_templates',
			'type' 	=> 'info',
			'title' => esc_html__( 'Resend verification email', 'doccure' ),
			'style' => 'info',
		),
		array(
			'id' => 'resend_subject',
			'type' => 'text',
			'default' => 'Email Verification Link',
			'title' => esc_html__( 'Subject', 'doccure' ),
			'desc' => esc_html__( 'Please add subject for email', 'doccure' )
		),
		array(
			'id' => 'divider_resend_information',
			'desc' => wp_kses( __( '%name% — To display the doctor name.<br>
%email% — To display the doctor email address.<br>
%password% — To display the password for login.<br>
%verification_link% — To display verification link.<br>
%site% — To display the site name.<br>
%signature% — To display site logo.', 'doccure' ), array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			) ),
			'title' => esc_html__( 'Email setting variables', 'doccure' ),
			'type' => 'info',
			'class' => 'dc-center-content',
			'icon' => 'el el-info-circle'
		),
		array(
			'id' => 'resend_content',
			'type' => 'editor',
			'default' => 'Hello %name%!<br/>
						Your account has created on %site%. Verification is required, To verify your account please use below link:<br> 
						Verification Link: %verification_link%<br/>

						%signature%',
			'title' => esc_html__( 'Email Contents', 'doccure' )
		),
		
		
		array(
			'id' 	=> 'divider_chat_notify',
			'type' 	=> 'info',
			'title' => esc_html__( 'Chat message email', 'doccure' ),
			'style' => 'info',
		),
		array(
			'id'       => 'chat_notify_enable',
			'type'     => 'select',
			'title'    => esc_html__('Chat notification', 'doccure'), 
			'desc' =>  __( 'Enable/Disable receiver chat notifications. If enabled message email will be sent to the receiver.', 'doccure' ),
			'options'  => array(
				'yes' 	=> esc_html__('Yes', 'doccure'),
				'no' 	=> esc_html__('No', 'doccure') 
			),
			'default'  => 'no',
		),
		array(
			'id' => 'chat_notify_subject',
			'type' => 'text',
			'default' => 'A new message received',
			'title' => esc_html__( 'Subject', 'doccure' ),
			'desc' => esc_html__( 'Please add subject for email', 'doccure' )
		),
		array(
			'id' => 'divider_chat_notify_information',
			'desc' => wp_kses( __( '%username% — To display message receiver name.
%sender_name% — To display sender name.
%message% — To display message.
%signature% — To display site logo.', 'doccure' ), array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			) ),
			'title' => esc_html__( 'Email setting variables', 'doccure' ),
			'type' => 'info',
			'class' => 'dc-center-content',
			'icon' => 'el el-info-circle'
		),
		array(
			'id' => 'chat_notify_content',
			'type' => 'editor',
			'default' => 'Hi %username%!
You have received a new message from %sender_name%, below is the message
%message%

%signature%',
			'title' => esc_html__( 'Email Contents', 'doccure' )
		),
	
		array(
			'id' 	=> 'divider_approve_account',
			'type' 	=> 'info',
			'title' => esc_html__( 'Account approved', 'doccure' ),
			'style' => 'info',
		),
		array(
			'id' => 'approve_account_subject',
			'type' => 'text',
			'default' => 'Your account has been approved',
			'title' => esc_html__( 'Subject', 'doccure' ),
			'desc' => esc_html__( 'Please add subject for email', 'doccure' )
		),
		array(
			'id' => 'divider_approve_account_information',
			'desc' => wp_kses( __( '%username% — To display message receiver name.
%sender_name% — To display sender name.
%message% — To display message.
%signature% — To display site logo.', 'doccure' ), array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			) ),
			'title' => esc_html__( 'Email setting variables', 'doccure' ),
			'type' => 'info',
			'class' => 'dc-center-content',
			'icon' => 'el el-info-circle'
		),
		array(
			'id' => 'approve_account_content',
			'type' => 'editor',
			'default' => 'Hello %name%
Your account has been approved. You can now login to setup your profile.

<a href="%site_url%">Login Now</a>

%signature%',
			'title' => esc_html__( 'Email Contents', 'doccure' )
		)
	)
) );

Redux::setSection( $opt_name, array(
	'title' => esc_html__( 'Admin Templates', 'doccure' ),
	'id' => 'admin_templates',
	'desc' => esc_html__( 'Admin Templates', 'doccure' ),
	'subsection' => true,
	'fields' => array(
		array(
			'id' => 'admin_email',
			'type' => 'text',
			'default' => 'info@yourdomain.com',
			'title' => esc_html__( 'Admin email address', 'doccure' ),
			'desc' => esc_html__( 'Please add admin email address, leave it empty to get email address from WordPress Settings.', 'doccure' )
		),
		array(
			'id' => 'divider_general_admin_templates',
			'type' => 'info',
			'title' => esc_html__( 'Email template for new user to admin', 'doccure' ),
			'style' => 'info',
		),
		array(
			'id' => 'admin_register_subject',
			'type' => 'text',
			'default' => esc_html__( 'New registration', 'doccure' ),
			'title' => esc_html__( 'Admin new user subject', 'doccure' ),
			'desc' => esc_html__( 'Please add new user subject.', 'doccure' )
		),
		array(
			'id' => 'divider_general_admin_new_user_information',
			'desc' => wp_kses( __( '%name% — To display new registered  user name.<br>
%email% — To display the email address of registered user.<br>
%signature% — To display site logo.', 'doccure' ), array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			) ),
			'title' => esc_html__( 'Email setting variables', 'doccure' ),
			'type' => 'info',
			'class' => 'dc-center-content',
			'icon' => 'el el-info-circle'
		),
		array(
			'id' => 'admin_register_content',
			'type' => 'editor',
			'default' => 'Hello!
						A new user "%name%" with email address "%email%" has been registered on your website.
						Please login to check user detail.
						%signature%',
			'title' => esc_html__( 'Email Contents', 'doccure' )
		),
		
		array(
			'id' => 'divider_admin_article_pending_templates',
			'type' => 'info',
			'title' => esc_html__( 'Article Email template with pending status', 'doccure' ),
			'style' => 'info',
		),
		array(
			'id' => 'admin_article_pending_subject',
			'type' => 'text',
			'default' => esc_html__( 'Article needs approval', 'doccure' ),
			'title' => esc_html__( 'Article pending subject', 'doccure' ),
			'desc' => esc_html__( 'Please add new user subject.', 'doccure' )
		),
		array(
			'id' => 'admin_article_pending_information',
			'desc' => wp_kses( __( '%doctor_name% — To display Doctor name.<br>
			%article_title% - To display Article title.<br>
			%signature% - To display site logo.', 'doccure' ), array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			) ),
			'title' => esc_html__( 'Email setting variables', 'doccure' ),
			'type' => 'info',
			'class' => 'dc-center-content',
			'icon' => 'el el-info-circle'
		),
		array(
			'id' => 'admin_article_pending_content',
			'type' => 'editor',
			'title' => esc_html__( 'Article content', 'doccure' ),
			'default' => 'Hello admin,
							A new article "%article_title%" has been submitted by a doctor %doctor_name%, it required your approval to make it publish.
							%signature%,',
		),
		
	)
) );


Redux::setSection( $opt_name, array(
	'title' => 'Booking Templates',
	'id' => 'hospital_booking_templates',
	'desc' => esc_html__( 'Booking Templates', 'doccure' ),
	'subsection' => true,
	'fields' => array(
		array(
			'id' => 'divider_booking_verification_templates',
			'type' => 'info',
			'title' => esc_html__( 'Email verification code for booking', 'doccure' ),
			'style' => 'info',
		),
		array(
			'id' => 'booking_verify_subject',
			'type' => 'text',
			'default' => 'verification code for booking',
			'title' => esc_html__( 'Subject', 'doccure' ),
			'desc' => esc_html__( 'Please add subject for email', 'doccure' )
		),
		array(
			'id' => 'divider_booking_verification_information',
			'desc' => wp_kses( __( '%name% — To display the user name.<br>
				%email% — To display the user email address.<br>
				%verification_code% — To display the verification code.<br>
				%signature% — To display site logo.', 'doccure' ), array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			) ),
			'title' => esc_html__( 'Email setting variables', 'doccure' ),
			'type' => 'info',
			'class' => 'dc-center-content',
			'icon' => 'el el-info-circle'
		),
		array(
			'id' => 'booking_verify_content',
			'type' => 'editor',
			'default' => 'Hello %name%
							To complete your booking please add the below authentication code.
							Your verification code is : %verification_code%
							%signature%',
			'title' => esc_html__( 'Email Contents', 'doccure' )
		),
		array(
			'id' => 'divider_booking_request_templates',
			'type' => 'info',
			'title' => esc_html__( 'Email to user for receiving appointment booking request.', 'doccure' ),
			'style' => 'info',
		),
		array(
			'id' => 'booking_request_subject',
			'type' => 'text',
			'default' => 'Appointment confirmation',
			'title' => esc_html__( 'Subject', 'doccure' ),
			'desc' => esc_html__( 'Please add subject for email', 'doccure' )
		),
		array(
			'id' => 'divider_booking_request_information',
			'desc' => wp_kses( __( '%user_name% — To display the user name.<br>
				%doctor_name% — To display the Doctor name.<br>
				%doctor_link% — To display the Doctor profile url.<br>
				%hospital_name% — To display the Hospital name.<br>
				%hospital_link% — To display the Hospital profile url.<br>
				%appointment_date% — To display the Appointment date.<br>
				%appointment_time% — To display the Appointment time.<br>
				%price% — To display the Booking total price.<br>
				%consultant_fee% — To display the consultation fee.<br>
				%description% — To display the booking description.<br>
				%email% — To display the User email.<br>
				%signature% — To display site logo.', 'doccure' ), array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			) ),
			'title' => esc_html__( 'Email setting variables', 'doccure' ),
			'type' => 'info',
			'class' => 'dc-center-content',
			'icon' => 'el el-info-circle'
		),
		array(
			'id' => 'booking_request_content',
			'type' => 'editor',
			'default' => wp_kses( __( 'Hello %user_name%<br/>

			Your appointment booking request has been scheduled with the following details<br/>
			Appointment date 	: %appointment_date% <br>
			Appointment time 	: %appointment_time% <br>
			Doctor name 		: %doctor_name% <br>
			Hospital name 		: %hospital_name% <br>
			consultation fee 	: %consultant_fee% <br>
			Price 				: %price% <br>
			Description 		: %description% <br>
			%signature%,<br/>,', 'doccure' ), array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			) ),
			'title' => esc_html__( 'Email Contents', 'doccure' )
		),
		array(
			'id' => 'divider_doctor_booking_request_templates',
			'type' => 'info',
			'title' => esc_html__( 'Doctors receive appointment', 'doccure' ),
			'style' => 'info',
		),
		array(
			'id' => 'doctor_booking_request_subject',
			'type' => 'text',
			'default' => 'New appointment received!',
			'title' => esc_html__( 'Subject', 'doccure' ),
			'desc' => esc_html__( 'Please add subject for email', 'doccure' )
		),
		array(
			'id' => 'divider_doctor_booking_request_information',
			'desc' => wp_kses( __( '%user_name% — To display the user name.<br>
%doctor_name% — To display the Doctor name.<br>
%doctor_link% — To display the Doctor profile url.<br>
%hospital_name% — To display the Hospital name.<br>
%hospital_link% — To display the Hospital profile url.<br>
%appointment_date% — To display the Appointment date.<br>
%appointment_time% — To display the Appointment time.<br>
%price% — To display the Booking total price.<br>
%consultant_fee% — To display the consultation fee.<br>
%description% — To display the booking description.<br>
%email% — To display the User email.<br>
%signature% — To display site logo.', 'doccure' ), array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			) ),
			'title' => esc_html__( 'Email setting variables', 'doccure' ),
			'type' => 'info',
			'class' => 'dc-center-content',
			'icon' => 'el el-info-circle'
		),
		array(
			'id' => 'doctor_booking_request_content',
			'type' => 'editor',
			'default' => 'Hello %doctor_name%
							%user_name% is request you for appointment in hospital %hospital_link% on %appointment_date% at %appointment_time%
							%signature%,',
			'title' => esc_html__( 'Email Contents', 'doccure' )
		),

		// add approved/cancelled email
		array(
			'id' => 'divider_booking_approved_templates',
			'type' => 'info',
			'title' => esc_html__( 'Email Patient when booking is approved', 'doccure' ),
			'style' => 'info',
		),
		array(
			'id' => 'approved_booking_request_subject',
			'type' => 'text',
			'default' => esc_html__( 'Approved appoinment', 'doccure' ),
			'title' => esc_html__( 'Subject', 'doccure' ),
			'desc' => esc_html__( 'Please add subject for email', 'doccure' )
		),
		array(
			'id' => 'divider_approved_booking_information',
			'desc' => wp_kses( __( '%user_name% — To display the user name.<br>
%doctor_name% — To display the Doctor name.<br>
%doctor_link% — To display the Doctor profile link.<br>
%hospital_name% — To display the hospital name.<br>
%hospital_link% — To display the hospital profile link.<br>
%appointment_time% — To display the appointment time.<br>
%appointment_date% — To display the appointment date.<br>
%signature% — To display site logo.', 'doccure' ), array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			) ),
			'title' => esc_html__( 'Email setting variables', 'doccure' ),
			'type' => 'info',
			'class' => 'dc-center-content',
			'icon' => 'el el-info-circle'
		),
		array(
			'id' => 'approved_booking_request_content',
			'type' => 'editor',
			'default' => wp_kses( __( 'Hello %user_name%<br/>
							%doctor_name% is approved to your appoinment on date  %appointment_date% at %appointment_time% <br/>
							%signature%,<br/>', 'doccure' ), array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			) ),
			'title' => esc_html__( 'Email Contents', 'doccure' )
		),

		array(
			'id' => 'divider_booking_approved_templates',
			'type' => 'info',
			'title' => esc_html__( 'Email Patient when booking is approved', 'doccure' ),
			'style' => 'info',
		),
		array(
			'id' => 'approved_booking_request_subject',
			'type' => 'text',
			'default' => esc_html__( 'Approved appoinment', 'doccure' ),
			'title' => esc_html__( 'Subject', 'doccure' ),
			'desc' => esc_html__( 'Please add subject for email', 'doccure' )
		),
		array(
			'id' => 'divider_approved_booking_information',
			'desc' => wp_kses( __( '%user_name% — To display the user name.<br>
%doctor_name% — To display the Doctor name.<br>
%doctor_link% — To display the Doctor profile link.<br>
%hospital_name% — To display the hospital name.<br>
%hospital_link% — To display the hospital profile link.<br>
%appointment_time% — To display the appointment time.<br>
%appointment_date% — To display the appointment date.<br>
%signature% — To display site logo.', 'doccure' ), array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			) ),
			'title' => esc_html__( 'Email setting variables', 'doccure' ),
			'type' => 'info',
			'class' => 'dc-center-content',
			'icon' => 'el el-info-circle'
		),
		array(
			'id' => 'approved_booking_request_content',
			'type' => 'editor',
			'default' => wp_kses( __( 'Hello %user_name%<br/>
							%doctor_name% is approved your appoinment on date  %appointment_date% at %appointment_time% <br/>
							%signature%,<br/>', 'doccure' ), array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			) ),
			'title' => esc_html__( 'Email Contents', 'doccure' )
		),

		array(
			'id' => 'divider_booking_cancelled_templates',
			'type' => 'info',
			'title' => esc_html__( 'Email Patient when booking is cancelled', 'doccure' ),
			'style' => 'info',
		),
		array(
			'id' => 'cancelled_booking_request_subject',
			'type' => 'text',
			'default' => esc_html__( 'cancelled appoinment', 'doccure' ),
			'title' => esc_html__( 'Subject', 'doccure' ),
			'desc' => esc_html__( 'Please add subject for email', 'doccure' )
		),
		array(
			'id' => 'divider_cancelled_booking_information',
			'desc' => wp_kses( __( '%user_name% — To display the user name.<br>
%doctor_name% — To display the Doctor name.<br>
%doctor_link% — To display the Doctor profile link.<br>
%hospital_name% — To display the hospital name.<br>
%hospital_link% — To display the hospital profile link.<br>
%appointment_time% — To display the appointment time.<br>
%appointment_date% — To display the appointment date.<br>
%signature% — To display site logo.', 'doccure' ), array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			) ),
			'title' => esc_html__( 'Email setting variables', 'doccure' ),
			'type' => 'info',
			'class' => 'dc-center-content',
			'icon' => 'el el-info-circle'
		),
		array(
			'id' => 'cancelled_booking_request_content',
			'type' => 'editor',
			'default' => wp_kses( __( 'Hello %user_name%<br/>
							%doctor_name% is cancelled your appoinment on date  %appointment_date% at %appointment_time% <br/>
							%signature%,<br/>', 'doccure' ), array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			) ),
			'title' => esc_html__( 'Email Contents', 'doccure' )
		),
		// end approved email
		array(
			'id' => 'divider_feedback_templates',
			'type' => 'info',
			'title' => esc_html__( 'Email to doctor after feedback from user.', 'doccure' ),
			'style' => 'info',
		),
		array(
			'id' => 'feedback_subject',
			'type' => 'text',
			'default' => 'Feedback received',
			'title' => esc_html__( 'Subject', 'doccure' ),
			'desc' => esc_html__( 'Please add subject for email', 'doccure' )
		),
		array(
			'id' => 'divider_feedback_information',
			'desc' => wp_kses( __( '%user_name% — To display the user name.<br>
%doctor_name% — To display the Doctor name.<br>
%rating% 	— To display ratings.<br>
%recommend% 	— To display user recommend or not.<br>
%waiting_time% — To display the waiting time.<br>
%description% — To display the feedback description.<br>
%signature% — To display site logo.', 'doccure' ), array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			) ),
			'title' => esc_html__( 'Email setting variables', 'doccure' ),
			'type' => 'info',
			'class' => 'dc-center-content',
			'icon' => 'el el-info-circle'
		),
		array(
			'id' => 'feedback_content',
			'type' => 'editor',
			'default' => wp_kses( __( 'Hello %doctor_name%<br>
							%user_name% has given the feedback with the following details :<br>
							Recommend 			: %recommend% <br>
							Waiting time 		: %waiting_time% <br>
							Rating				: %rating% <br>
							Description 		: %description% <br>
							%signature%,<br/>
							%signature%,', 'doccure' ), array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			) ),
			'title' => esc_html__( 'Email Contents', 'doccure' )
		)
	)
) );


Redux::setSection( $opt_name, array(
	'title' => esc_html__( 'Article Templates', 'doccure' ),
	'id' => 'article_templates',
	'desc' => esc_html__( 'Article Templates', 'doccure' ),
	'subsection' => true,
	'fields' => array(
		array(
			'id' => 'divider_article_pending_templates',
			'type' => 'info',
			'title' => esc_html__( 'Email template for pending article', 'doccure' ),
			'style' => 'info',
		),
		array(
			'id' => 'article_pending_subject',
			'type' => 'text',
			'default' => esc_html__( 'Your submitted needs approval', 'doccure' ),
			'title' => esc_html__( 'Article pending subject', 'doccure' ),
			'desc' => esc_html__( 'Please add new user subject.', 'doccure' )
		),
		array(
			'id' => 'article_pending_information',
			'desc' => wp_kses( __( '%doctor_name% — To display Doctor name.<br>
%article_title% - To display Article title.<br>
%signature% - To display site logo.', 'doccure' ), array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			) ),
			'title' => esc_html__( 'Email setting variables', 'doccure' ),
			'type' => 'info',
			'class' => 'dc-center-content',
			'icon' => 'el el-info-circle'
		),
		array(
			'id' => 'article_pending_content',
			'type' => 'editor',
			'title' => esc_html__( 'Article content', 'doccure' ),
			'default' => 'Hello %doctor_name%<br>
			Your article %article_title% has been received, Your article will be published after the review
			%signature%,',
		),
		array(
			'id' => 'divider_article_publish_templates',
			'type' => 'info',
			'title' => esc_html__( 'Email template with publish status', 'doccure' ),
			'style' => 'info',
		),
		array(
			'id' => 'article_publish_subject',
			'type' => 'text',
			'default' => esc_html__( 'Your article has been published', 'doccure' ),
			'title' => esc_html__( 'Article publish subject', 'doccure' ),
			'desc' => esc_html__( 'Please add new user subject.', 'doccure' )
		),
		array(
			'id' => 'article_publish_information',
			'desc' => wp_kses( __( '%doctor_name% — To display Doctor name.<br>
%article_title% - To display Article title.<br>
%signature% - To display site logo.', 'doccure' ), array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			) ),
			'title' => esc_html__( 'Email setting variables', 'doccure' ),
			'type' => 'info',
			'class' => 'dc-center-content',
			'icon' => 'el el-info-circle'
		),
		array(
			'id' => 'article_publish_content',
			'type' => 'editor',
			'title' => esc_html__( 'Article content', 'doccure' ),
			'default' => 'Hello %doctor_name%<br>
							Your article %article_title% has been published.
							%signature%,'
		)
	)
) );
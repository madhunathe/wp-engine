<?php
/**
 * Payment Settings
 *
 * @throws error
 * @return 
 */
$schedules_list	=  get_transient( 'cron-interval-list' );

$payment_method	= array('paypal' => esc_html__('Paypal','doccure'),'bacs' => esc_html__('Direct Bank Transfer (BACS)', 'doccure'));
Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Payment Settings', 'doccure' ),
        'id'               => 'payment_settings',
        'subsection'       => false,
		'icon'			   => 'el el-credit-card',
        'fields'           => array(
			
			array(
				'id'       => 'payment_type',
				'type'     => 'select',
				'title'    => esc_html__('Select payment type', 'doccure'), 
				'options'  => array(
					'online' 	=> esc_html__('Online', 'doccure'),
					'offline' 	=> esc_html__('Offline', 'doccure') 
				),
				'default'  => 'online',
			),
	
		
			
			array(
                'id'       => 'enable_checkout_page',
                'type'     => 'select',
                'title'    => esc_html__( 'Enable WooCommerce checkout', 'doccure' ),
                'default'  => 'hide',
				'options'  => array(
					'hide' 	=> esc_html__('Hide checkout page', 'doccure'),
					'show' 	=> esc_html__('Use WooCommerce checkout', 'doccure') 
				),
				'desc'     => esc_html__( 'If you will hide Woocommerce checkout then system will remove the woocommerce payment method', 'doccure' ),
				'required' => array( 'payment_type', '=', 'offline' )
			),
			array(
                'id'       => 'success_title',
                'type'     => 'text',
                'title'    => esc_html__( 'Success Title?', 'doccure' ),
                'desc'     => esc_html__( 'Add success title or leave it empty to hide', 'doccure' ),
				'required' => array( 'enable_checkout_page', '=', false )
            ),
			array(
                'id'       => 'success_desc',
                'type'     => 'editor',
                'title'    => esc_html__( 'Success Description?', 'doccure' ),
                'desc'     => esc_html__( 'Add success description or leave it empty to hide', 'doccure' ),
				'required' => array( 'enable_checkout_page', '=', false )
            ),
			
			
			
			array(
				'id'       => 'booking_system_contact',
				'type'     => 'select',
				'default'  => 'doctor',
				'title'    => esc_html__('Appointments type?', 'doccure'), 
				'desc'     => esc_html__('Either phone calls received to admin or allow doctors to show their own phone numbers to get appointments.', 'doccure'),
				'options'  => array(
					'admin' 	=> esc_html__('By Admin', 'doccure'),
					'doctor' 	=> esc_html__('By Doctors', 'doccure') 
				),
				'required' => array( 'system_booking_oncall', '=', true ),
			),
			array(
				'id'    => 'booking_contact_numbers',
				'type'  => 'multi_text',
				'title' => esc_html__( 'Contact numbers', 'doccure' ),
				'desc'	=> esc_html__('Add contact number.','doccure'),
				'required' => array( 'booking_system_contact', '=', 'admin' )
			),
			array(
				'id'       => 'booking_contact_detail',
				'type'     => 'editor',
				'title'    => esc_html__( 'Details', 'doccure' ),
				'desc'     => esc_html__( 'Add booking details', 'doccure' ),
				'default'  => '',
				'required' => array( 'booking_system_contact', '=', 'admin' )
			),

		)
	)
);

Redux::setSection( $opt_name, array(
	'title'            => esc_html__( 'Payout Settings ', 'doccure' ),
	'id'               => 'payout_settings',
	'desc'       	   => '',
	'subsection'       => true,
	'icon'			   => 'el el-braille',	
	'fields'           => array(	
			array(
				'id' 		=> 'admin_commision',
				'type' 		=> 'slider',
				'title' 	=> esc_html__('Set admin commision', 'doccure'),
				'desc' 		=> esc_html__('Select Service commission in percentage ( % ), set it to 0 to make commission free website', 'doccure'),
				"default" 	=> 1,
				"min" 		=> 0,
				"step" 		=> 1,
				"max" 		=> 100,
				'display_value' => 'label',
			),
			array(
				'id'       => 'min_amount',
				'type'     => 'text',
				'title'    => esc_html__('Add min amount', 'doccure'), 
				'desc'     => esc_html__('', 'doccure'),
				'default'  => '',
			),
			array(
				'id'       => 'cron_interval',
				'type'     => 'select',
				'title'    => esc_html__('Cron job interval', 'doccure'), 
				'desc'     => esc_html__('Select interval for payouts.', 'doccure'),
				'desc' 	=> wp_kses( __( 'Select interval for payouts.<br> '.esc_html__('Get interval list','doccure').' <a href="#" class="am-get-list">'.esc_html__('Click here','doccure').'</a>', 'doccure' ), array(
							'a' => array(
								'href' => array(),
								'class' => array(),
								'title' => array()
							),
							'br' => array(),
							'em' => array(),
							'strong' => array(),
						) ),
				'options'  => $schedules_list,
			),
			array(
				'id'       => 'payout_setting',
				'type'     => 'select',
				'multi'    => true,
				'title'    => esc_html__('Payout settings', 'doccure'), 
				'desc'     => esc_html__('Please select payouts methods, at-least one payout method is required. Default would be PayPal', 'doccure'),
				'options'  => $payment_method,
			),
		)
	)
);

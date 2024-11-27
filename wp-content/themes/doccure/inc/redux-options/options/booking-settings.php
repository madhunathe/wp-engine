<?php
/**
 * BOOKINGS settings
 *
 * @package doccure
 */

Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Booking Settings', 'doccure' ),
        'id'               => 'booking_settings',
        'subsection'       => false,
		'icon'			   => 'el el-time',
        'fields'           => array(
			array(
				'id'    => 'appointment_prefix',
				'type'  => 'text',
				'title' => esc_html__( 'Appointment', 'doccure' ), 
				'default' => 'APP#',
			),
			array(
				'id'       => 'allow_consultation_zero',
				'type'     => 'select',
				'title'    => esc_html__('Allow consultation fee to 0', 'doccure'), 
				'desc' =>  __( 'Allow consultation fee to 0, while adding location', 'doccure' ),
				'options'  => array(
					'yes' 	=> esc_html__('Yes', 'doccure'),
					'no' 	=> esc_html__('No', 'doccure') 
				),
				'default'  => 'no',
			),
			array(
				'id'       => 'allow_booking_zero',
				'type'     => 'select',
				'title'    => esc_html__('Allow booking fee to 0', 'doccure'), 
				'desc' =>  __( 'Allow booking fee to 0, while booking with doctor', 'doccure' ),
				'options'  => array(
					'yes' 	=> esc_html__('Yes', 'doccure'),
					'no' 	=> esc_html__('No', 'doccure') 
				),
				'default'  => 'no',
			),
			array(
                'id'       => 'dashboad_booking_option',
                'type'     => 'switch',
                'title'    => esc_html__( 'Add Booking', 'doccure' ),
                'default'  => false,
				'desc'     => esc_html__( 'Enable it to add custom bookings from doctor dashboard', 'doccure' ),
			),
			array(
                'id'       => 'feedback_option',
                'type'     => 'switch',
                'title'    => esc_html__( 'Feedback to doctors', 'doccure' ),
                'default'  => false,
				'desc'     => esc_html__( 'By enable this patient will be able to add feedback having atleast 1 booking with the doctor. By defult any user can post a feedback.', 'doccure' )
			),
			array(
                'id'       => 'booking_verification',
                'type'     => 'switch',
                'title'    => esc_html__('Remove user verification', 'doccure' ),
                'default'  => true,
				'desc'     => esc_html__('If this switch is enabled then verification steps will appear, you can disable this options to disable verification steps in the booking process', 'doccure' )
			),
		)
	)
);
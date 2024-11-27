<?php
/**
 * Search Doctors Settings
 *
 *
 * @package doccure
 */ 

Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Search Doctors Settings', 'doccure' ),
        'id'               => 'search_doctors_settings',
        'desc'       	   => '',
		'icon' 			   => 'el el-search',
		'subsection'       => false,
        'fields'           => array(
			array(
                'id'       => 'search_form',
                'type'     => 'switch',
                'title'    => esc_html__( 'Search form', 'doccure' ),
                'default'  => false,
				'desc'     => esc_html__( 'Enable search form in header', 'doccure' ),
			),	
			array(
				'title' 	=> esc_html__( 'Home search bar', 'doccure' ),
				'id'  		=> 'show_search_bar',
				'type'  	=> 'select',
				'default'  => 'no',
				'desc' 		=> esc_html__('Show search bar on home page', 'doccure'),
				'required' => array( 'search_form', '=', true ),
				'options'	=> array(
					'yes'	  => esc_html__( 'Yes', 'doccure' ),
					'no'	  => esc_html__( 'No', 'doccure' ),
				)
			),
			array(
				'id'       => 'search_type',
				'type'     => 'select',
				'title'    => esc_html__('Search type', 'doccure'), 
				'desc'     => esc_html__('Select defult search type.', 'doccure'),
				'options'  => array(
					'both' 			=> esc_html__('Doctors', 'doccure'), 
					//'doctors' 		=> esc_html__('Doctors', 'doccure'), 
					//'hospitals' 	=> esc_html__('Hospitals', 'doccure'), 
				),
				'default'  => 'both',
				'required' => array( 'search_form', '=', true ),
			),
		
			array(
                'id'       => 'gender_search',
                'type'     => 'switch',
                'title'    => esc_html__( 'Search Gender', 'doccure' ),
                'default'  => true,
				'desc'     => esc_html__( 'Enable/disable Gender search option', 'doccure' ),
				//'required' => array( 'search_type', '=', 'doctors' ),
            ),

			 
			array(
				'id'       => 'hide_location',
				'type'     => 'select',
				'title'    => esc_html__('Hide location', 'doccure'), 
				'desc'     => esc_html__('Hide location dropdown.', 'doccure'),
				'options'  => array(
					'no' 		=> esc_html__('No', 'doccure'), 
					'yes' 		=> esc_html__('Yes', 'doccure'), 
				),
				'default'  => 'no',
				'required' => array( 'search_form', '=', true ),
			),
			array(
				'id'       => 'redirect_unverified',
				'type'     => 'select',
				'title'    => esc_html__('Redirect users detail page', 'doccure'), 
				'desc'     => esc_html__('Redirect the visitors to see user detail page if user account is not verified or deactive profiles', 'doccure'),
				'options'  => array(
					'no' 		=> esc_html__('No', 'doccure'), 
					'yes' 		=> esc_html__('Yes', 'doccure'), 
				),
				'default'  => 'no',
				'required' => array( 'search_form', '=', true ),
			),
			array(
				'id'    	=> 'search_result_page',
				'type'  	=> 'select',
				'title' 	=> esc_html__( 'Search result page', 'doccure' ), 
				'data'  	=> 'pages',
				'desc'     => esc_html__('Select search result page.', 'doccure'),
			),
			// array(
            //     'id'       => 'dashboard_search',
            //     'type'     => 'switch',
            //     'title'    => esc_html__( 'Dashboard search', 'doccure' ),
            //     'default'  => false,
			// 	'desc'     => esc_html__( 'Enable dashboard header search option', 'doccure' ),
            // ),	
			array(
				'id'       => 'show_add',
				'type'     => 'select',
				'title'    => esc_html__('Select Ads Section', 'doccure'), 
				'desc'     => esc_html__('Please select Ads Section.', 'doccure'),
				'options'  => array(
					'top' 		=> esc_html__('Top', 'doccure'), 
					'middle' 	=> esc_html__('Middle', 'doccure'), 
					'bottom' 	=> esc_html__('Bottom', 'doccure'), 
				),
				'default'  => 'default',
				'required' => array( 'add_settings', '=', true ),
			),	
			array(
				'id'       => 'add_code',
				'type'     => 'textarea',
                'title'    => esc_html__( 'Ad code', 'doccure' ),
                'desc'     => esc_html__( 'Enter ad code here.', 'doccure' ),
                'default'  => '',
				'required' => array( 'add_settings', '=', true ),
			),
		)
	)
);
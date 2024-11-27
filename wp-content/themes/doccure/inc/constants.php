<?php

/**
 *  Contants
 */
if (!function_exists('doccure_prepare_constants')) {

    function doccure_prepare_constants() {
		global $current_user,$doccure_options;
		$is_loggedin = 'false';
		$user_type = '';
		$startweekday		= get_option('start_of_week');
		$startweekday		=  !empty( $startweekday ) ?  $startweekday : 0;
		$calendar_format	= !empty( $doccure_options['calendar_format'] ) ? $doccure_options['calendar_format'] : 'Y-m-d';
		
        if (is_user_logged_in()) {
            $is_loggedin 	= 'true';
			$user_type		= apply_filters('doccure_get_user_type', $current_user->ID );
        }

		$dir_map_marker		= !empty( $doccure_options['dir_map_marker'] ) ? $doccure_options['dir_map_marker'] : get_template_directory_uri() . '/images/marker.png';
		$dir_map_type		= !empty( $doccure_options['dir_map_type'] ) ? $doccure_options['dir_map_type'] : 'ROADMAP';
		$dir_zoom			= !empty( $doccure_options['dir_zoom'] ) ? $doccure_options['dir_zoom'] : '12';
		$dir_longitude		= !empty( $doccure_options['dir_longitude'] ) ? $doccure_options['dir_longitude'] : '-0.1262362';
		$dir_latitude		= !empty( $doccure_options['dir_latitude'] ) ? $doccure_options['dir_latitude'] : '51.5001524';
		$dir_map_scroll		= !empty( $doccure_options['dir_map_scroll'] ) ? $doccure_options['dir_map_scroll'] : 'false';
		$map_styles			= !empty( $doccure_options['map_styles'] ) ? $doccure_options['map_styles'] : 'none';
		$dir_datasize		= !empty( $doccure_options['dir_datasize'] ) ? $doccure_options['dir_datasize'] : '5242880';
	$calendar_locale	= !empty( $doccure_options['calendar_locale'] ) ? $doccure_options['calendar_locale'] : 'en';
		$loader_duration	= !empty( $doccure_options['loader_duration'] ) ? $doccure_options['loader_duration'] : '';
        
		$tip_content_bg		=  !empty( $doccure_options['tip_content_bg'] ) ?  $doccure_options['tip_content_bg']  : '';
		$tip_content_color	=  !empty( $doccure_options['tip_content_color']  ) ?  $doccure_options['tip_content_color'] : '';
		$tip_title_bg		=  !empty( $doccure_options['tip_title_bg'] ) ?  $doccure_options['tip_title_bg'] : '';
		$tip_title_color	=  !empty( $doccure_options['tip_title_color'] ) ?  $doccure_options['tip_title_color'] : '';
		
		if( $dir_datasize >= 1024 ){
			 $dir_datasize		= trim($dir_datasize);
			 $data_size_in_kb 	= $dir_datasize / 1024;
		} else{
			$data_size_in_kb = 5242880;
		}
		
		$chat_settings	= !empty( $doccure_options['chat'] )  ? $doccure_options['chat'] : '';
		$chat_host		=  !empty( $doccure_options['host'] ) ?  $doccure_options['host'] : 'http://localhost';
		$chat_port		=  !empty( $doccure_options['port'] ) ?  $doccure_options['port'] : '81';
		
		$chat_feature	= 'inbox';	
		$chat_page		= 'no';	
		
		if( !empty( $doccure_options['chat'] ) && $doccure_options['chat'] === 'chat' ){
			$chat_feature	= 'chat';	
		}else if( !empty( $doccure_options['chat'] ) && $doccure_options['chat'] === 'guppy' ){
			$chat_feature	= 'guppy';	
		}
		
		$current_user_id	= !empty( $current_user->ID ) ? $current_user->ID : '';
		$listing_type		= doccure_theme_option('listing_type');
		if ( function_exists( 'doccure_is_feature_value' )) {
			$dc_services		= doccure_is_feature_value( 'dc_services', $current_user_id);
			$dc_downloads		= doccure_is_feature_value( 'dc_downloads', $current_user_id);
			$dc_articles		= doccure_is_feature_value( 'dc_articles', $current_user_id);
			$dc_awards			= doccure_is_feature_value( 'dc_awards', $current_user_id);
			$dc_memberships		= doccure_is_feature_value( 'dc_memberships', $current_user_id);
        } else {
			$dc_services	= false;
			$dc_downloads	= false;
			$dc_articles	= false;
			$dc_awards		= false;
			$dc_memberships	= false;
		}
				
		$chat_page		= 'no';	
		
		if ( ( is_page_template('directory/dashboard.php') && isset($_GET['ref']) && $_GET['ref'] === 'chat' ) || is_singular('doctors') ) {
			$chat_page		= 'yes';	
        }
		
		$is_rtl					= doccure_rtl_check();
		$currency_symbols		= doccure_get_current_currency();
		$currency_symbols		= $currency_symbols['symbol'];
		$dir_spinner 			= get_template_directory_uri() . '/images/spinner.gif';
		$chatloader 			= get_template_directory_uri() . '/assets/images/chatloader.gif';
		
        wp_localize_script('doccure-callback', 'scripts_vars', array(
            'ajaxurl'           => admin_url('admin-ajax.php'),           
            'valid_email'       => esc_html__('Please add a valid email address.','doccure'),          
            'forgot_password'   => esc_html__('Reset Password','doccure'),          
            'login'             => esc_html__('Login','doccure'), 
            'is_loggedin'       => $is_loggedin,
			'user_type'       	=> $user_type,
			'copy_profile_msg'	=> esc_html__('You are successfully copy the user profile.', 'doccure'),
			'allow_booking'  	=> esc_html__('You are not allowed for appointment.', 'doccure'),
			'allow_feedback'  	=> esc_html__('You are not allowed to add feedback.', 'doccure'),
			'booking_message'  	=> esc_html__('Please login to book this doctor', 'doccure'),
			'feedback_message'  => esc_html__('Please login to add the feedback.', 'doccure'),
            'wishlist_message'  => esc_html__('Please login to save this users into your wishlist', 'doccure'),
            'message_error'     => esc_html__('No kiddies please', 'doccure'),
            'award_image'       => esc_html__('Image title', 'doccure'),
            'data_size_in_kb'   => $data_size_in_kb . 'kb',
            'award_image_title' => esc_html__('Your image title', 'doccure'),
            'award_image_size'  => esc_html__('File size', 'doccure'),
            'document_title'    => esc_html__('Document Title', 'doccure'),
			'emptyCancelReason' => esc_html__('Cancelled reason is required', 'doccure'),
			'package_update'	=> esc_html__('Please update your package to access this service.', 'doccure'),
			'location_required'	=> esc_html__('Please select the location.', 'doccure'),
			'speciality_required'	=> esc_html__('Select a speciality.','doccure'),
			'email_required'		=> esc_html__('Email is required.','doccure'),
			'update_booking'				=> esc_html__('Change booking status', 'doccure'),
			'update_booking_status_message'	=> esc_html__('Are you sure you want to change this booking status?','doccure'),
			'spinner'   	=> '<img class="sp-spin" src="'.esc_url($dir_spinner).'">',
			'chatloader'   	=> '<img class="sp-chatspin" src="'.esc_url($chatloader).'">',
			'nothing' 		=> esc_html__('Oops, nothing found!','doccure'),
			'days' 			=> esc_html__('Days','doccure'),
			'hours' 		=> esc_html__('Hours','doccure'),
			'minutes' 		=> esc_html__('Minutes','doccure'),
			'expired' 		=> esc_html__('EXPIRED','doccure'),
			'min_and' 		=> esc_html__('Minutes and','doccure'),
			'seconds' 		=> esc_html__('Seconds','doccure'),
			'yes' 			=> esc_html__('Yes','doccure'),
			'no' 			=> esc_html__('No','doccure'),
			'order' 		=> esc_html__('Add to cart','doccure'),
			'order_message' => esc_html__('Are you sure you want to buy this package?','doccure'),
			'slots_remove' 			=> esc_html__('Remove slot(s)','doccure'),
			'slots_remove_message' 	=> esc_html__('Are you sure you want to remove this slot(s)?','doccure'),
			'change_status' 		=> esc_html__('Change status','doccure'),
			'change_status_message' => esc_html__('Are you sure you want to change team member status?','doccure'),
			'location_remove' 			=> esc_html__('Remove Location','doccure'),
			'location_remove_message'	=> esc_html__('Are you sure you want to remove this location.','doccure'),
			'download_attachments' 		=> esc_html__('Add Attachments', 'doccure'),				
			'cache_title' 				=> esc_html__('Confirm?','doccure'),
			'cache_message' 			=> esc_html__('Never show this message again','doccure'),
			'delete_account'    		=> esc_html__('Delete Account', 'doccure'),
			'delete_account_message'    => esc_html__('Are you sure you want to delete your account?', 'doccure'),
			'delete_article'    		=> esc_html__('Delete Article', 'doccure'),
			'delete_article_message'    => esc_html__('Are you sure you want to delete your article?', 'doccure'),
			'empty_spaces_message'    	=> esc_html__('There are no spaces for remove.', 'doccure'),
			'remove_itme' 				=> esc_html__('Remove from Saved', 'doccure'),
			'remove_itme_message' 		=> esc_html__('Are you sure you want to remove this?', 'doccure'),
			'required_field' 			=> esc_html__('field is required','doccure'),
			'remove_payouts'			=> esc_html__('Remove Payouts Settings','doccure'),
			'remove_payouts_message'	=> esc_html__('Are you sure you want to remove Payouts Settings','doccure'),
			'empty_message'				=> esc_html__('Message is required','doccure'),
			'account_verification'				=> esc_html__('Your account has been verified.','doccure'),
			'listing_type'		=> $listing_type,
            'dir_map_marker' 	=> $dir_map_marker,
            'dir_map_type' 		=> $dir_map_type,
            'dir_zoom' 			=> $dir_zoom,
            'dir_longitude' 	=> $dir_longitude,
            'dir_latitude' 		=> $dir_latitude,
            'dir_map_scroll' 	=> $dir_map_scroll,
            'map_styles' 		=> $map_styles,
			'currency_symbols'	=> $currency_symbols,
			'dc_services'		=> $dc_services,
			'dc_downloads'		=> $dc_downloads,
			'dc_articles'		=> $dc_articles,
			'dc_awards'			=> $dc_awards,
			'dc_memberships'	=> $dc_memberships,
			'chat_page'			=> $chat_page,
			'chat_host' 		=> $chat_host,
			'chat_port' 		=> $chat_port,
			'chat_settings' 	=> $chat_settings,
			'loader_duration'	=> $loader_duration,
			'tip_content_bg' 	=> $tip_content_bg,
			'tip_content_color' => $tip_content_color,
			'tip_title_bg' 		=> $tip_title_bg,
			'tip_title_color' 	=> $tip_title_color,
			'calendar_format' 	=> $calendar_format,
			'calendar_locale' 	=> $calendar_locale,
			'startweekday' 		=> $startweekday,
			'is_rtl' 			=> $is_rtl,
			'ajax_nonce' 		=> wp_create_nonce('ajax_nonce'),
        ));
    }

    add_action('wp_enqueue_scripts', 'doccure_prepare_constants', 90);
}
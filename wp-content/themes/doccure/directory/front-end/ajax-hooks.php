<?php
/**
 *
 * Ajax request hooks
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @since 1.0
 */
/**
 * Get Lost Password
 *
 * @throws error
 * @author Dreams Technologies <support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_ajax_lp')) {

    function doccure_ajax_lp() {
        global $wpdb;
        $json = array();     
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
        $user_input = !empty($_POST['email']) ? $_POST['email'] : '';

        if (empty($user_input)) {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Please add email address.', 'doccure');
            wp_send_json($json);
        } else if (!is_email($user_input)) {
            $json['type'] = "error";
            $json['message'] = esc_html__("Please add a valid email address.", 'doccure');
            wp_send_json($json);
        }      

        $user_data = get_user_by('email',$user_input);
        if (empty($user_data) ) {
            $json['type'] = "error";
            $json['message'] = esc_html__("Invalid E-mail address!", 'doccure');
            wp_send_json($json);
        }

        $user_id    = $user_data->ID;
        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;
        $username   = doccure_get_username( $user_id );

        $key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));

        if (empty($key)) {
            //generate reset key
            $key = wp_generate_password(20, false);
            $wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
        }

        $protocol 		= is_ssl() ? 'https' : 'http';
        $reset_link 	= esc_url(add_query_arg(array('action' => 'reset_pwd', 'key' => $key, 'login' => $user_login), home_url('/', $protocol)));

        //Send email to user
        if (class_exists('doccure_Email_helper')) {
            if (class_exists('doccureGetPasswordNotify')) {
                $email_helper = new doccureGetPasswordNotify();
                $emailData = array();
				$emailData['username']  = $username;
				$emailData['name']  	= $username;
                $emailData['email']     = $user_email;
                $emailData['link']      = $reset_link;
                $email_helper->send($emailData);
            }
        }     

        $json['type'] = "success";
        $json['message'] = esc_html__("A link has been sent, please check your email.", 'doccure');
        wp_send_json($json);
    }

    add_action('wp_ajax_doccure_ajax_lp', 'doccure_ajax_lp');
    add_action('wp_ajax_nopriv_doccure_ajax_lp', 'doccure_ajax_lp');
}

/**
 * Reset Password
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_ajax_reset_password')) {

    function doccure_ajax_reset_password() {
        global $wpdb;
        $json = array();   
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}

        //Form Validation
        if (isset($_POST['password'])) {
            if ($_POST['password'] != $_POST['verify_password']) {
                // Passwords don't match
                $json['type'] = "error";
                $json['message'] = esc_html__("Oops! password is not matched", 'doccure');
                wp_send_json($json);
            }

            if (empty($_POST['password'])) {
                $json['type'] = "error";
                $json['message'] = esc_html__("Oops! password should not be empty", 'doccure');
                wp_send_json($json);
            }
        } else {
            $json['type'] = "error";
            $json['message'] = esc_html__("Oops! Invalid request", 'doccure');
            wp_send_json($json);
        }     


        if (!empty($_POST['key']) &&
                ( isset($_POST['reset_action']) && $_POST['reset_action'] == "reset_pwd" ) &&
                (!empty($_POST['login']) )
        ) {

            $reset_key  = sanitize_text_field($_POST['key']);
            $user_login = sanitize_text_field($_POST['login']);

            $user_data = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $reset_key, $user_login));

            $user_login = $user_data->user_login;
            $user_email = $user_data->user_email;

            if (!empty($reset_key) && !empty($user_data)) {
                $new_password = sanitize_text_field( $_POST['password'] );

                wp_set_password($new_password, $user_data->ID);

                $json['redirect_url'] = home_url('/');
                $json['type'] = "success";
                $json['message'] = esc_html__("Congratulation! your password has been changed.", 'doccure');
                wp_send_json($json);
            } else {
                $json['type'] = "error";
                $json['message'] = esc_html__("Oops! Invalid request", 'doccure');
                wp_send_json($json);
            }
        } else {
        	$json['type'] = 'error';
        	$json['message'] = esc_html__('Something went wrong, please conntat to administrator', 'doccure');
        	wp_send_json($json);
        }
    }

    add_action('wp_ajax_doccure_ajax_reset_password', 'doccure_ajax_reset_password');
    add_action('wp_ajax_nopriv_doccure_ajax_reset_password', 'doccure_ajax_reset_password');
}

/**
 * File uploader
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_temp_file_uploader')) {

    function doccure_temp_file_uploader() {       
        global $current_user, $wp_roles, $userdata, $post;
        $user_identity 		= $current_user->ID;
        $ajax_response  	= array();
        $upload 			= wp_upload_dir();
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in
		
		
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$ajax_response['type'] = 'error';
			$ajax_response['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $ajax_response );
		}

        $upload_dir = $upload['basedir'];
        $upload_dir = $upload_dir . '/doccure-temp/';
        
        //create directory if not exists
        if (! is_dir($upload_dir)) {
			wp_mkdir_p( $upload_dir );
        }
       
        $submitted_file = $_FILES['file_name'];
        $name = preg_replace("/[^A-Z0-9._-]/i", "_", $submitted_file["name"]);
		
		//file type check
		$filetype 		= wp_check_filetype($submitted_file['name']);
		$allowed_types	= array('php','javascript','js','exe','text/javascript','text/php');
	    $file_ext		= !empty($filetype['ext']) ? $filetype['ext'] : ''; 
		
		if(!empty($file_ext)){
			if(in_array($file_ext,$allowed_types)){
				$ajax_response['message'] = esc_html__('These file types are not allowed', 'doccure');
				$ajax_response['type']    = 'error';
				wp_send_json($ajax_response);
			}	
		}elseif(empty($file_ext)){
			if(in_array($submitted_file['type'],$allowed_types)){
				$ajax_response['message'] = esc_html__('These file types are not allowed', 'doccure');
				$ajax_response['type']    = 'error';
				wp_send_json($ajax_response);
			}
		}
		
        $i = 0;
        $parts = pathinfo($name);
        while (file_exists($upload_dir . $name)) {
            $i++;
            $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
        }
        
        //move files
        $is_moved = move_uploaded_file($submitted_file["tmp_name"], $upload_dir . '/'.$name); 
		
        if( $is_moved ){
            $size       = $submitted_file['size'];
            $file_size  = size_format($size, 2);           
            $ajax_response['type']    = 'success';
            $ajax_response['message'] = esc_html__('File uploaded!', 'doccure');
            $url = $upload['baseurl'].'/doccure-temp/'.$name;
            $ajax_response['thumbnail'] = $upload['baseurl'].'/doccure-temp/'.$name;
            $ajax_response['name']    = $name;
            $ajax_response['size']    = $file_size;
        } else{
            $ajax_response['message'] = esc_html__('Some error occur, please try again later', 'doccure');
            $ajax_response['type']    = 'error';
        }
		
        wp_send_json($ajax_response);
    }

    add_action('wp_ajax_doccure_temp_file_uploader', 'doccure_temp_file_uploader');
    add_action('wp_ajax_nopriv_doccure_temp_file_uploader', 'doccure_temp_file_uploader');
}

/**
 * Generate QR code
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_generate_qr_code' ) ) {
    function doccure_generate_qr_code(){
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
        $user_id = !empty( $_POST['key'] ) ? $_POST['key'] : '';  
        $type    = !empty( $_POST['type'] ) ? $_POST['type'] : '';        
        if( file_exists( WP_PLUGIN_DIR. '/doccure/libraries/phpqrcode/phpqrcode.php' ) ){
            if( !empty( $user_id ) && !empty( $type ) ) {  
                require_once(WP_PLUGIN_DIR. '/doccure/libraries/phpqrcode/phpqrcode.php' );
                $user_link      = get_permalink( $user_id );
                $data_type 		= $type.'-';
				
                $tempDir        = wp_upload_dir();                  
                $codeContents   = esc_url($user_link);      
                $tempUrl    = trailingslashit($tempDir['baseurl']);
                $tempUrl    = $tempUrl.'/qr-code/'.$data_type.$user_id.'/';            
                $upload_dir = trailingslashit($tempDir['basedir']);
                $upload_dir = $upload_dir .'qr-code/';
				
                if (! is_dir($upload_dir)) {
					wp_mkdir_p( $upload_dir );
					
                    //qr-code directory created
                    $upload_folder = $upload_dir.$data_type.$user_id.'/';                
                    if (! is_dir($upload_folder)) {
						wp_mkdir_p( $upload_folder );
						
                        //Create image
                        $fileName = $user_id.'.png';      
                        $qrAbsoluteFilePath = $upload_folder.$fileName; 
                        $qrRelativeFilePath = $tempUrl.$fileName;     
                    } 
                } else {
                    //create user directory
                    $upload_folder = $upload_dir.$data_type.$user_id.'/';              
                    if (! is_dir($upload_folder)) {
						wp_mkdir_p( $upload_folder );
                        //Create image
                        $fileName = $user_id.'.png';      
                        $qrAbsoluteFilePath = $upload_folder.$fileName; 
                        $qrRelativeFilePath = $tempUrl.$fileName;     
                    } else {
                        $fileName = $user_id.'.png';      
                        $qrAbsoluteFilePath = $upload_folder.$fileName; 
                        $qrRelativeFilePath = $tempUrl.$fileName;     
                    }
                }                
                //Delete if exists
                if (file_exists($qrAbsoluteFilePath)) { 
                    wp_delete_file( $qrAbsoluteFilePath );
                    QRcode::png($codeContents, $qrAbsoluteFilePath, QR_ECLEVEL_L, 3);                        
                } else {
                    QRcode::png($codeContents, $qrAbsoluteFilePath, QR_ECLEVEL_L, 3);            
                }           
                
                if( !empty( $qrRelativeFilePath ) ) {
                        $json['type'] = 'success';
                        $json['message'] = esc_html__('', 'doccure');
                        $json['key'] = $qrRelativeFilePath;
                        wp_send_json($json);
                }  
				
                $json['type'] = 'error';
                $json['message'] = esc_html__('Some thing went wrong.', 'doccure');
                wp_send_json($json);  
            } else {
                $json['type'] = 'error';
                $json['message'] = esc_html__('Something went wrong.', 'doccure');
                wp_send_json($json);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Please update/install required plugins', 'doccure');
            wp_send_json($json);
        }
    }
    add_action('wp_ajax_doccure_generate_qr_code', 'doccure_generate_qr_code');
    add_action('wp_ajax_nopriv_doccure_generate_qr_code', 'doccure_generate_qr_code');
}

/**
 * Remove slot
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_remove_location' ) ){
    function doccure_remove_location(){
		global $current_user;
		$json 				= array();
		$post_id		= !empty( $_POST['id'] ) ? intval($_POST['id']) : '';
		
		if( function_exists('doccure_validate_privileges') ) { 
			doccure_validate_privileges($post_id);
		} //if user is logged in
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}

		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}

		if( empty( $post_id ) ){
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Post ID is not set.','doccure');        
            wp_send_json($json);
		}
		
		wp_delete_post($post_id, true);
		$json['type']    	= 'success';
		$json['url'] 		= doccure_Profile_Menu::doccure_profile_menu_link('appointment', $current_user->ID, true,'setting');
        $json['message'] 	= esc_html__('You are successfully remove location', 'doccure');   
		wp_send_json($json);
	}
	add_action('wp_ajax_doccure_remove_location', 'doccure_remove_location');
    add_action('wp_ajax_nopriv_doccure_remove_location', 'doccure_remove_location');
}

/**
 * Remove slot
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_remove_slot' ) ){
    function doccure_remove_slot(){
		$json 				= array();
		$post_meta			= array();
		$post_array			= array();
		
		$post_id		= !empty( $_POST['id'] ) ? sanitize_text_field( $_POST['id']) : '';
		$dkey			= !empty( $_POST['key'] ) ? sanitize_text_field( $_POST['key']) : '';
		$day			= !empty( $_POST['day'] ) ? sanitize_text_field( $_POST['day']) : '';
		
		if( function_exists('doccure_validate_privileges') ) { 
			doccure_validate_privileges($post_id);
		} //if user is logged in and have privileges
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; 
		
		$required	= array(
						'id' 		=> esc_html__('Post ID is required.','doccure'),
						'key' 		=> esc_html__('Date is required.','doccure'),
						'day' 		=> esc_html__('Day key is required.','doccure')
					);
		
		foreach ($required as $key => $value) {
           if( empty( ($_POST[$key] ) )){
				$json['type'] 		= 'error';
				$json['message'] 	= $value;        
				wp_send_json($json);
           }
        }

		$default_slots 			= get_post_meta($post_id, 'am_slots_data', true);
		$default_slots			= !empty( $default_slots ) ? $default_slots : array();
		unset($default_slots[$day]['slots'][$dkey]);

		
		$update	= update_post_meta( $post_id,'am_slots_data', $default_slots );
		$json['type']    = 'success';
        $json['message'] = esc_html__('You are successfully remove slot(s).', 'doccure');   
		wp_send_json($json);
	}
	add_action('wp_ajax_doccure_remove_slot', 'doccure_remove_slot');
    add_action('wp_ajax_nopriv_doccure_remove_slot', 'doccure_remove_slot');
}

/**
 * Remove slot
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_remove_allslots' ) ){
    function doccure_remove_allslots(){
		$json 				= array();
		$post_meta			= array();
		$post_array			= array();
		
		$post_id		= !empty( $_POST['id'] ) ? sanitize_text_field( $_POST['id']) : '';
		$day			= !empty( $_POST['day'] ) ? sanitize_text_field( $_POST['day']) : '';
		
		if( function_exists('doccure_validate_privileges') ) { 
			doccure_validate_privileges($post_id);
		} //if user is logged in and have privileges
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; 
		
		$required	= array(
							'id' 		=> esc_html__('Post ID is required.','doccure'),
							'day' 		=> esc_html__('Day key is required.','doccure')
						);
		
		foreach ($required as $key => $value) {
           if( empty( ($_POST[$key] ) )){
				$json['type'] 		= 'error';
				$json['message'] 	= $value;        
				wp_send_json($json);
           }
        }

		$default_slots 			= get_post_meta($post_id, 'am_slots_data', true);
		$default_slots			= !empty( $default_slots ) ? $default_slots : array();
		unset($default_slots[$day]);
		$update	= update_post_meta( $post_id,'am_slots_data', $default_slots );
		$json['type']    = 'success';
        $json['message'] = esc_html__('You are successfully remove slot(s).', 'doccure');   
		wp_send_json($json);
	}
	add_action('wp_ajax_doccure_remove_allslots', 'doccure_remove_allslots');
    add_action('wp_ajax_nopriv_doccure_remove_allslots', 'doccure_remove_allslots');
}

/**
 * add appointment
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_update_appointment' ) ){
    function doccure_update_appointment(){
		$json 				= array();
		$slots				= array();
		$post_id		= !empty( $_POST['post_id'] ) ? sanitize_text_field( $_POST['post_id']) : '';
		$spaces			= !empty( $_POST['spaces'] ) ? sanitize_text_field( $_POST['spaces']) : '';
		$start_time		= !empty( $_POST['start_time'] ) ?  $_POST['start_time']  : '';
		$end_time		= !empty( $_POST['end_time'] ) ?  	$_POST['end_time']  : '';
		
		if( function_exists('doccure_validate_privileges') ) { 
			doccure_validate_privileges($post_id);
		} //if user is logged in and have privileges
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		} 
		
		$required	= array(
							'post_id' 		=> esc_html__('Please add your location first to add time slots.','doccure'),
							'start_time' 	=> esc_html__('Start time is required.','doccure'),
							'end_time' 		=> esc_html__('End time is required.','doccure'),
							'spaces' 		=> esc_html__('Check Appointment Spaces.','doccure'),
							'week_day' 		=> esc_html__('Day is required.','doccure'),
						);
		
		foreach ($required as $key => $value) {
           if( empty( ($_POST[$key] ) )){
				$json['type'] 		= 'error';
				$json['message'] 	= $value;        
				wp_send_json($json);
           }
        }

		if( $start_time > $end_time) {
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('start time is less then end time.','doccure');        
			wp_send_json($json);
		}
		
		if( !empty( $spaces ) && $spaces === 'others' ) {
			if( empty( $_POST['custom_spaces'] )) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Custom spaces value is requird.','doccure');        
				wp_send_json($json);
			} else {
				$post_meta['am_custom_spaces']	= sanitize_text_field( $_POST['custom_spaces'] );
				$spaces				= !empty( $post_meta['am_custom_spaces'] ) ?  	$post_meta['am_custom_spaces']  	: '1';
			}
		}
		
		$day				= !empty( $_POST['week_day'] ) ? sanitize_text_field( $_POST['week_day']) : '';
		$intervals			= !empty( $_POST['intervals'] ) ? 	$_POST['intervals'] : '';
		$durations			= !empty( $_POST['durations'] ) ? 	$_POST['durations'] : '';
		
		$total_duration		= intval($durations) + intval($intervals);
		$diff_time			= ((intval($end_time) - intval($start_time))/100)*60;
		$check_interval		= $diff_time - $total_duration;
		
		if( $start_time > $end_time || $check_interval <  0 ) {
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Your end date is less then time interval.','doccure');        
            wp_send_json($json);
		}

		$default_slots 		= get_post_meta($post_id, 'am_slots_data', true);
		$default_slots		= !empty( $default_slots ) ? $default_slots : array();
		$slots				= $default_slots[$day]['slots'];
				
		if( !empty( $slots ) ){
			$slots_keys	= array_keys($slots);
			foreach( $slots_keys as $slot ) {
				$slot_vals  = explode('-', $slot);
				$count_slot	= $slot_vals[0].$slot_vals[1];
				if( ($start_time <= $slot_vals[0]) && ( $slot_vals[0] <= $end_time) || ($start_time <= $slot_vals[1]) && ( $slot_vals[1] <= $end_time) ) {
					unset($slots[$slot]);
				}
			}
		}
		
		$spaces_data['spaces'] = $spaces;
			
		do {
			
            $newStartTime 	= date("Hi", strtotime('+' . $durations . ' minutes', strtotime($start_time)));
            $slots[$start_time . '-' . $newStartTime] = $spaces_data;

            if ($intervals):
                $time_to_add = $intervals + $durations;
            else :
                $time_to_add = $durations;
            endif;

            $start_time = date("Hi", strtotime('+' . $time_to_add . ' minutes', strtotime($start_time)));
            if ($start_time == '0000'):
                $start_time = '2400';
            endif;
        } while ($start_time < $end_time);
		
		$default_slots[$day]['slots'] = $slots;
		
		$update	= update_post_meta( $post_id,'am_slots_data', $default_slots );
		$json['slots']	= doccure_get_day_spaces($day,$post_id);		
		$json['type']    = 'success';
        $json['message'] = esc_html__('Slot(s) successfully updated.', 'doccure');   
		wp_send_json($json);
	}
	add_action('wp_ajax_doccure_update_appointment', 'doccure_update_appointment');
    add_action('wp_ajax_nopriv_doccure_update_appointment', 'doccure_update_appointment');
}

/**
 * add Hospital team
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_add_hospital_team' ) ){
    function doccure_add_hospital_team(){       
        global $current_user,$doccure_options;               
        $json 				= array();
		$emailData 			= array();
		$post_meta			= array();
		$post_array			= array();
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; 
		
		$user_id		= $current_user->ID;
		$post_id  		= doccure_get_linked_profile_id($user_id);
		$doctor_name	= doccure_full_name($post_id);
		$doctor_name	= !empty( $doctor_name ) ? esc_html($doctor_name) : get_the_title($post_id);
		$doctor_link	= get_the_permalink($post_id);
		$doctor_link	= !empty( $doctor_link ) ? esc_url( $doctor_link ) : '';
	
		$required	= array(
							'hospital_id' 	=> esc_html__('Hospital Name is required.','doccure'),
							'start_time' 	=> esc_html__('Start time is required.','doccure'),
							'end_time' 		=> esc_html__('End time is required.','doccure'),
							'spaces' 		=> esc_html__('Check Appointment Spaces.','doccure'),
							'week_days' 	=> esc_html__('Check atleast one day.','doccure'),
							'consultant_fee' 	=> esc_html__('Consultation fee is required.','doccure'),
						);
		
		//consultation fee
		$consultant_fee_require	= !empty($doccure_options['allow_consultation_zero'] ) ? $doccure_options['allow_consultation_zero'] : '';
		if(!empty($consultant_fee_require) && $consultant_fee_require === 'yes'){
			unset($required['consultant_fee']);
		}
		
		foreach ($required as $key => $value) {
           if( empty( ($_POST[$key] ) )){
				$json['type'] 		= 'error';
				$json['message'] 	= $value;        
				wp_send_json($json);
           }
        }
		
		$hospital_id		= !empty( $_POST['hospital_id'] ) ? sanitize_text_field( $_POST['hospital_id']) : '';
		$start_time			= !empty( $_POST['start_time'] ) ?  $_POST['start_time']  : '';
		$post_content		= !empty( $_POST['content'] ) ? sanitize_textarea_field( $_POST['content'] ) : '';
		$end_time			= !empty( $_POST['end_time'] ) ?  	$_POST['end_time']  : '';
		$intervals			= !empty( $_POST['intervals'] ) ? 	$_POST['intervals'] : 0;
		$durations			= !empty( $_POST['durations'] ) ? 	$_POST['durations'] : '';
		$services			= !empty( $_POST['service'] ) ? 	$_POST['service']  : array();
		$spaces				= !empty( $_POST['spaces'] ) ?  	$_POST['spaces']  	: '';
		$consultant_fee		= !empty( $_POST['consultant_fee'] ) ?  $_POST['consultant_fee']  	: '';
		$week_days			= !empty( $_POST['week_days'] ) ?  	$_POST['week_days'] : array();
		$total_duration		= intval($durations) + intval($intervals);
		$diff_time			= ((intval($end_time) - intval($start_time))/100)*60;
		$check_interval		= $diff_time - $total_duration;
		
		if( $start_time > $end_time || $check_interval <  0 ) {
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Your end date is less then time interval.','doccure');        
            wp_send_json($json);
		}
		
		$team_prefix		= !empty( $doccure_options['hospital_team_prefix'] ) ? $doccure_options['hospital_team_prefix'] : esc_html__('TEAM #','doccure');
		$uniqe_id			= dc_unique_increment();
		$post_title			= !empty( $hospital_id ) ? $team_prefix.$uniqe_id : '';
		$team_status		=  'pending';
		
				
		if( !empty( $spaces ) && $spaces === 'others' ) {
			if( empty( $_POST['custom_spaces'] )) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Custom spaces value is requird.','doccure');        
				wp_send_json($json);
			} else {
				$post_meta['am_custom_spaces']	= sanitize_text_field( $_POST['custom_spaces'] );
				$spaces				= !empty( $post_meta['am_custom_spaces'] ) ?  	$post_meta['am_custom_spaces']  	: '1';
			}
		} 
		
		$default_slots 			= get_post_meta($post_id, 'am_slots_data', true);
		$default_slots			= !empty( $default_slots ) ? $default_slots : array();
		$space_data				= array();
		$slots_array			= array();
		$space_data['spaces']	= $spaces;
		$start_time_slot		= $start_time;
		
		$default_slots['start_time']	= $start_time;
		$default_slots['end_time']		= $end_time;
		$default_slots['durations']		= $durations;
		$default_slots['intervals']		= $intervals;
		$default_slots['spaces']		= $spaces;
		do {
			
            $newStartTime = date("Hi", strtotime('+' . $durations . ' minutes', strtotime($start_time_slot)));
            $default_slots['slots'][$start_time_slot . '-' . $newStartTime] = $space_data;

            if ($intervals):
                $time_to_add = $intervals + $durations;
            else :
                $time_to_add = $durations;
            endif;

            $start_time_slot = date("Hi", strtotime('+' . $time_to_add . ' minutes', strtotime($start_time_slot)));
            if ($start_time_slot == '0000'):
                $start_time_slot = '2400';
            endif;
        } while ($start_time_slot < $end_time);
		
		if( !empty( $week_days ) ){
			foreach( $week_days as $day ) {
				$slots_array[$day]	= $default_slots;
			}
		}
				
		if( empty( $post_title ) ){
			
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Appointment title is required.', 'doccure');
            wp_send_json($json);
		} else {
			$post_array['post_title']		= $post_title;
			$post_array['post_content']		= $post_content;
			$post_array['post_author']		= $user_id;
			$post_array['post_type']		= 'hospitals_team';
			$post_array['post_status']		= $team_status;
			$team_id 						= wp_insert_post($post_array);
			
			if( $team_id ) {
				$post_meta['am_consultant_fee']	= $consultant_fee;
				$post_meta['am_start_time']		= $start_time;
				$post_meta['am_end_time']		= $end_time;
				$post_meta['am_durations']		= $durations;
				$post_meta['am_intervals']		= $intervals;
				$post_meta['am_spaces']			= $spaces;
				$post_meta['am_week_days']		= $week_days;
				update_post_meta( $team_id ,'_consultant_fee',$consultant_fee);
				update_post_meta( $team_id,'am_hospitals_team_data', $post_meta );
				update_post_meta( $team_id,'am_team_id', $uniqe_id );
				update_post_meta( $team_id,'am_slots_data', $slots_array );
				update_post_meta( $team_id,'hospital_id',$hospital_id );
				update_post_meta( $team_id,'_team_services',$services);
				
				$hospital_name		= doccure_full_name($hospital_id);
				$hospital_name		= !empty( $hospital_name ) ? esc_html( $hospital_name ) : get_the_title($hospital_id);
				$hospital_user_id	= doccure_get_linked_profile_id($hospital_id,'post');
				$hospital_info		= get_userdata($hospital_user_id);
				
				$emailData['email'] 				= $hospital_info->user_email;
				$emailData['doctor_link'] 			= $doctor_link;
				$emailData['doctor_name'] 			= $doctor_name;
				$emailData['hospital_name'] 		= $hospital_name;
				
				// emai to hospital 
				if (class_exists('doccure_Email_helper')) {
					if (class_exists('doccureHospitalTeamNotify')) {
						$email_helper = new doccureHospitalTeamNotify();
						$email_helper->send_request_email($emailData);
					}
				}
				
				$json['type']    = 'success';
        		$json['message'] = esc_html__('Appointment is submmitted successfully.', 'doccure');    
			}
		}
		
		wp_send_json($json);
        
    }
            
    add_action('wp_ajax_doccure_add_hospital_team', 'doccure_add_hospital_team');
    add_action('wp_ajax_nopriv_doccure_add_hospital_team', 'doccure_add_hospital_team');
}

/**
 * add article
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_add_article' ) ){
    function doccure_add_article(){       
        global $current_user, $doccure_options;               
        $json = array();
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		$user_id		 	= $current_user->ID;
		$profile_id  		= doccure_get_linked_profile_id($user_id);
		$dc_articles		= doccure_is_feature_value( 'dc_articles', $user_id);
		
		if($dc_articles < 1 ){
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Your package limit for submitting articles has reached to maximum. Please upgrade or buy package to submit more articles.', 'doccure');
            wp_send_json($json);
		}
        
		
		$post_title			= !empty( $_POST['post_title'] ) ? sanitize_text_field( $_POST['post_title']) : '';
		$post_content		= !empty( $_POST['post_content'] ) ?  $_POST['post_content'] : '';
		$post_tags			= !empty( $_POST['post_tags'] ) ?  $_POST['post_tags'] : array(0);
		$post_categories	= !empty( $_POST['post_categories'] ) ? $_POST['post_categories'] : array(0);
		$update_post_id		= !empty( $_POST['post_id'] ) ? sanitize_text_field( $_POST['post_id']) : '';
		$article_setting	= !empty( $doccure_options['article_option'] ) ? $doccure_options['article_option'] : 'pending';
		
		if( empty( $post_title ) ){
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Post title is required.', 'doccure');
            wp_send_json($json);
		} else {
			$post_array['post_title']		= wp_strip_all_tags( $post_title );
			$post_array['post_content']		= $post_content;
			$post_array['post_author']		= $user_id;
			$post_array['post_type']		= 'post';
			if( empty( $update_post_id ) ){
				
				$post_array['post_status']		= $article_setting;
				$post_id 						= wp_insert_post($post_array);
				doccure_update_package_attribute_value($user_id,'dc_articles');
				if (class_exists('doccure_Email_helper') && !empty( $post_id )) {
					$emailData	= array();
					if (class_exists('doccureArticleNotify')) {
						
						$emailData['email']			= $current_user->user_email;
						$emailData['article_title']	= wp_strip_all_tags( $post_title );
						$emailData['doctor_name']	= doccure_full_name( $profile_id );
						
						$email_helper = new doccureArticleNotify();
						
						if( $article_setting === 'publish' ) {
							$email_helper->send_article_publish_email($emailData);
						} else {
							$email_helper->send_article_pending_email($emailData);
							$email_helper->send_admin_pending_email($emailData);
						}
					}
				}
				
			} else{
				if( function_exists('doccure_validate_privileges') ) { 
					doccure_validate_privileges($update_post_id);
				} //if user is logged in and have privileges
				
				$post_array['ID']				= $update_post_id;
				$post_id 						= wp_update_post($post_array);
			}
			
			if( $post_id ) {
				
				if( !empty( $_POST['basics']['avatar']['attachment_id'] ) ){
					$profile_avatar = $_POST['basics']['avatar'];
				} else {                                
					if( !empty( $_POST['basics']['avatar'] ) ){
						$profile_avatar = doccure_temp_upload_to_media($_POST['basics']['avatar'], $post_id);
					}
				}
				
				//delete prevoius attachment ID
				$pre_attachment_id = get_post_thumbnail_id($post_id);
				if ( !empty($pre_attachment_id) && !empty( $profile_avatar['attachment_id'] ) && intval($pre_attachment_id) != intval($profile_avatar['attachment_id'])) {
					wp_delete_attachment($pre_attachment_id, true);
				}

				//update thumbnail
				if (!empty($profile_avatar['attachment_id'])) {
					delete_post_thumbnail($post_id);
					set_post_thumbnail($post_id, $profile_avatar['attachment_id']);
				} else {
					wp_delete_attachment( $pre_attachment_id, true );
				}
				
				wp_set_post_tags( $post_id, $post_tags );
				wp_set_post_categories( $post_id, $post_categories);
				$json['type']    = 'success';
        		$json['message'] = esc_html__('Article is submitted successfully.', 'doccure');    
			}
		}
		
		wp_send_json($json);
        
    }
            
    add_action('wp_ajax_doccure_add_article', 'doccure_add_article');
    add_action('wp_ajax_nopriv_doccure_add_article', 'doccure_add_article');
}

/**
 * Remove article
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_remove_article' ) ){
    function doccure_remove_article(){       
        global $current_user;               
        $json = array();
		$user_id		= $current_user->ID;
		$article_id		= !empty( $_POST['id'] ) ? intval($_POST['id']) : '';
		
		if( function_exists('doccure_validate_privileges') ) { 
			doccure_validate_privileges($article_id);
		} //if user is logged in and have privileges
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; 

		if( $article_id ) {
			$post_author	= get_post_field('post_author', $article_id);
			$post_author	= !empty( $post_author ) ? intval($post_author) : '';
			
			if( !empty( $post_author ) && $post_author === $user_id ) {
				wp_delete_post($article_id);
				$json['type']    = 'success';
        		$json['message'] = esc_html__('You are successfully remove this article.', 'doccure');  
			} else {
				$json['type'] 		= 'error';
            	$json['message'] 	= esc_html__('You are not allowed to remove this article.', 'doccure');
			}
		}
		wp_send_json($json);
    }
            
    add_action('wp_ajax_doccure_remove_article', 'doccure_remove_article');
    add_action('wp_ajax_nopriv_doccure_remove_article', 'doccure_remove_article');
}

/**
 * Update doctor Profile location
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_update_doctor_profile_location' ) ){
    function doccure_update_doctor_profile_location(){       
        global $current_user,$doccure_options;               
        $json = array();
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		$user_id		 = $current_user->ID;
		$post_id  		 = doccure_get_linked_profile_id($user_id);
		
        if( function_exists('doccure_validate_privileges') ) { 
			doccure_validate_privileges($post_id);
		} //if user is logged in and have privileges

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		
		$doctor_location	= !empty($doccure_options['doctor_location']) ? $doccure_options['doctor_location'] : '';
		$location 				= !empty($_POST['location']) ? $_POST['location'] : '';
		$address				= !empty($_POST['address'] ) ? $_POST['address'] : '';
		$longitude				= !empty($_POST['longitude'] ) ? $_POST['longitude'] : '';
		$latitude				= !empty($_POST['latitude'] ) ? $_POST['latitude'] : '';
		
		if(!empty($doctor_location) && $doctor_location !== 'hospitals'){
			$location_id	= get_post_meta($post_id, '_doctor_location', true);
			$location_id	= !empty( $location_id ) ? intval( $location_id ) : '';
			$location_title	= !empty( $_POST['location_title'] ) ? $_POST['location_title'] : '';
			
			if ( 'publish' !== get_post_status ( $location_id ) ) {
				$location_id = '';
			}
			
			if( empty($location_id)  ){
				$doctor_location = array(
									'post_title'   	=> $location_title,
									'post_type'		=> 'dc_locations',
									'post_status'	=> 'publish',
									'post_author'	=> $user_id
								);
				$location_id	= wp_insert_post( $doctor_location );
				update_post_meta( $post_id, '_doctor_location', $location_id );
			} else {
				$doctor_location = array(
									'ID'           => $location_id,
									'post_title'   => $location_title
								);
				wp_update_post( $doctor_location );
			}

			//Profile avatar
			$profile_avatar = array();
			if( !empty( $_POST['basics']['avatar']['attachment_id'] ) ){
				$profile_avatar = $_POST['basics']['avatar'];
			} else {                                
				if( !empty( $_POST['basics']['avatar'] ) ){
					$profile_avatar = doccure_temp_upload_to_media($_POST['basics']['avatar'], $location_id);
				}
			}

			//delete prevoius attachment ID
			$pre_attachment_id = get_post_thumbnail_id($location_id);
			if ( !empty($pre_attachment_id) && !empty( $profile_avatar['attachment_id'] ) && intval($pre_attachment_id) != intval($profile_avatar['attachment_id'])) {
				wp_delete_attachment($pre_attachment_id, true);
			}
			
			//update thumbnail
			if (!empty($profile_avatar['attachment_id'])) {
				delete_post_thumbnail($location_id);
				set_post_thumbnail($location_id, $profile_avatar['attachment_id']);
			} else {
				wp_delete_attachment( $pre_attachment_id, true );
			}
			
			
			if( !empty($location) && is_array($location) ){
				$location_ids	= array();
				foreach($location as $key => $item){
					$location_ids[] = doccure_get_term_by_type('slug',sanitize_text_field($item),'locations' );
				}
				wp_set_post_terms( $post_id, $location_ids, 'locations' );
			}else{
				$location_ids 	= doccure_get_term_by_type('slug',$location,'locations' );
				wp_set_post_terms( $post_id, $location_ids, 'locations' );
			}
			
			update_post_meta($location_id, '_address', $address);
			update_post_meta($location_id, '_longitude', $longitude);
			update_post_meta($location_id, '_latitude', $latitude);
		}

		update_post_meta($post_id, '_address', $address);
		update_post_meta($post_id, '_longitude', $longitude);
		update_post_meta($post_id, '_latitude', $latitude);
				
				
        $json['type']    = 'success';
        $json['message'] = esc_html__('Settings Updated.', 'doccure');        
        wp_send_json($json);
    }
            
    add_action('wp_ajax_doccure_update_doctor_profile_location', 'doccure_update_doctor_profile_location');
    add_action('wp_ajax_nopriv_doccure_update_doctor_profile_location', 'doccure_update_doctor_profile_location');
}

/**
 * Update doctor Profile
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_update_doctor_profile' ) ){
    function doccure_update_doctor_profile(){       
        global $current_user,$doccure_options;               
        $json = array();
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		$user_id		 = $current_user->ID;
		$post_id  		 = doccure_get_linked_profile_id($user_id);
		
        if( function_exists('doccure_validate_privileges') ) { 
			doccure_validate_privileges($post_id);
		} //if user is logged in and have privileges

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}	

        $required_fields = array(
            'am_first_name'   	=> esc_html__('First  name is required', 'doccure'),
			'am_last_name'  	=> esc_html__('Last name is required', 'doccure'),
			'am_mobile_number'  => esc_html__('Personal mobile number is required', 'doccure'),
        );

        foreach ($required_fields as $key => $value) {
           if( empty( $_POST[$key] ) ){
            $json['type'] 		= 'error';
            $json['message'] 	= $value;        
            wp_send_json($json);
           }
        }
		
		$post_meta		= doccure_get_post_meta( $post_id);
		$post_type		= get_post_type($post_id);

		$enable_options		= !empty($doccure_options['doctors_contactinfo']) ? $doccure_options['doctors_contactinfo'] : '';
		
        //Form data
        $display_name 		= !empty($_POST['display_name']) ? ($_POST['display_name']) : '';
		$am_first_name 		= !empty($_POST['am_first_name']) ? ($_POST['am_first_name']) : '';
		$am_mobile_number 	= !empty($_POST['am_mobile_number']) ? ($_POST['am_mobile_number']) : '';
        $am_last_name  		= !empty($_POST['am_last_name'] ) ? ($_POST['am_last_name']) : '';
		$am_name_base  		= !empty($_POST['am_name_base'] ) ? ($_POST['am_name_base']) : '';
		$am_gender  		= !empty($_POST['am_gender'] ) ? ($_POST['am_gender']) : '';
		$am_web_url			= !empty( $_POST['am_web_url'] ) ?  $_POST['am_web_url']  : '';

		$am_sub_heading  		= !empty($_POST['am_sub_heading'] ) ? ($_POST['am_sub_heading']) : '';
		$am_starting_price  	= !empty($_POST['am_starting_price'] ) ? ($_POST['am_starting_price']) : '';
		$am_short_description  	= !empty($_POST['am_short_description'] ) ? sanitize_textarea_field($_POST['am_short_description']) : '';
		$am_memberships_name  	= !empty($_POST['am_memberships_name'] ) ? $_POST['am_memberships_name'] : array();
		$am_phone_numbers  		= !empty($_POST['am_phone_numbers'] ) ? $_POST['am_phone_numbers'] : array();
        $content				= !empty($_POST['content'] ) ? $_POST['content'] : '';

		update_post_meta($post_id, 'am_gender', $am_gender);
		
        //Update user meta
        update_user_meta($user_id, 'first_name', $am_first_name);
		update_user_meta($user_id, 'last_name', $am_last_name);
		update_user_meta($user_id, 'mobile_number', $am_mobile_number);
		
		$post_meta['am_first_name']		= $am_first_name;
		$post_meta['am_mobile_number']	= $am_mobile_number;
		$post_meta['am_last_name']		= $am_last_name;
		$post_meta['am_name_base']		= $am_name_base;
		$post_meta['am_gender']			= $am_gender;
		
		$post_meta['am_starting_price']		= $am_starting_price;
		$post_meta['am_sub_heading']		= $am_sub_heading;
		$post_meta['am_short_description']	= $am_short_description;
		$post_meta['am_web_url']			= $am_web_url;
		$post_meta['am_memberships_name']	= $am_memberships_name;
		
		if( !empty($enable_options) && $enable_options === 'yes' ){
			$post_meta['am_phone_numbers']		= $am_phone_numbers;
		}
		
		update_post_meta($post_id, 'am_' . $post_type . '_data', $post_meta);
		
		$display_name	= !empty($display_name) ? $display_name : $am_first_name.' '.$am_last_name;
		
        //Update Doctor Post        
        $doctor_user = array(
            'ID'           => $post_id,
            'post_title'   => $display_name,
            'post_content' => $content,
        );
		wp_update_post( $doctor_user );
		
		//update languages
		$lang		= array();
		if( !empty( $_POST['settings']['languages'] ) ){
			foreach( $_POST['settings']['languages'] as $key => $item ){
				$lang[] = $item;
			}
		}
		
		wp_set_post_terms($post_id, $lang, 'languages');
		
		
		//Profile avatar
        $profile_avatar = array();
        if( !empty( $_POST['basics']['avatar']['attachment_id'] ) ){
            $profile_avatar = $_POST['basics']['avatar'];
        } else {                                
            if( !empty( $_POST['basics']['avatar'] ) ){
                $profile_avatar = doccure_temp_upload_to_media($_POST['basics']['avatar'], $post_id);
            }
        }
		//delete prevoius attachment ID
		$pre_attachment_id = get_post_thumbnail_id($post_id);
		if ( !empty($pre_attachment_id) && !empty( $profile_avatar['attachment_id'] ) && intval($pre_attachment_id) != intval($profile_avatar['attachment_id'])) {
			wp_delete_attachment($pre_attachment_id, true);
		}
		
		//update thumbnail
		if (!empty($profile_avatar['attachment_id'])) {
			delete_post_thumbnail($post_id);
			set_post_thumbnail($post_id, $profile_avatar['attachment_id']);
		} else {
			wp_delete_attachment( $pre_attachment_id, true );
		}
		
        $json['type']    = 'success';
        $json['message'] = esc_html__('Settings Updated.', 'doccure');        
        wp_send_json($json);
    }
            
    add_action('wp_ajax_doccure_update_doctor_profile', 'doccure_update_doctor_profile');
    add_action('wp_ajax_nopriv_doccure_update_doctor_profile', 'doccure_update_doctor_profile');
}

/**
 * Update patient Profile
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_update_patient_profile' ) ){
    function doccure_update_patient_profile(){       
        global $current_user,$doccure_options;               
        $json = array();
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		$user_id		 = $current_user->ID;
		$post_id  		 = doccure_get_linked_profile_id($user_id);
		
        if( function_exists('doccure_validate_privileges') ) { 
			doccure_validate_privileges($post_id);
		} //if user is logged in and have privileges
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}

        $required_fields = array(
            'am_first_name'   	=> esc_html__('First  name is required', 'doccure'),
			'am_last_name'  	=> esc_html__('Last name is required', 'doccure'),
			'am_mobile_number'  => esc_html__('Personal mobile number is required', 'doccure'),
        );

        foreach ($required_fields as $key => $value) {
           if( empty( $_POST[$key] ) ){
            $json['type'] 		= 'error';
            $json['message'] 	= $value;        
            wp_send_json($json);
           }
        }
		
		$post_meta		= doccure_get_post_meta( $post_id);
		$post_type		= get_post_type($post_id);

        //Form data
        $display_name 		= !empty($_POST['display_name']) ? sanitize_text_field($_POST['display_name']) : '';
		$am_first_name 		= !empty($_POST['am_first_name']) ? sanitize_text_field($_POST['am_first_name']) : '';
		$am_mobile_number 	= !empty($_POST['am_mobile_number']) ? sanitize_text_field($_POST['am_mobile_number']) : '';
        $am_last_name  		= !empty($_POST['am_last_name'] ) ? sanitize_text_field($_POST['am_last_name']) : '';
		$am_name_base  		= !empty($_POST['am_name_base'] ) ? sanitize_text_field($_POST['am_name_base']) : '';
		

		$am_sub_heading  		= !empty($_POST['am_sub_heading'] ) ? sanitize_text_field($_POST['am_sub_heading']) : '';
		
		$am_short_description  	= !empty($_POST['am_short_description'] ) ? sanitize_textarea_field($_POST['am_short_description']) : '';
		
		$location 				= !empty($_POST['location']) ? doccure_get_term_by_type('slug',sanitize_text_field($_POST['location']),'locations' ): '';
		$address				= !empty($_POST['address'] ) ? $_POST['address'] : '';
		$longitude				= !empty($_POST['longitude'] ) ? $_POST['longitude'] : '';
		$latitude				= !empty($_POST['latitude'] ) ? $_POST['latitude'] : '';
		$content				= !empty($_POST['content'] ) ? $_POST['content'] : '';
		
		wp_set_post_terms( $post_id, $location, 'locations' );
		update_post_meta($post_id, '_address', $address);
		update_post_meta($post_id, '_longitude', $longitude);
		update_post_meta($post_id, '_latitude', $latitude);
		update_post_meta($post_id, '_mobile_number', $am_mobile_number);
				
        //Update user meta
        update_user_meta($user_id, 'first_name', $am_first_name);
		update_user_meta($user_id, 'last_name', $am_last_name);
		update_user_meta($user_id, 'mobile_number', $am_mobile_number);
		
		$post_meta['am_first_name']		= $am_first_name;
		$post_meta['am_mobile_number']	= $am_mobile_number;
		$post_meta['am_last_name']		= $am_last_name;
		$post_meta['am_name_base']		= $am_name_base;
		
		$post_meta['am_sub_heading']		= $am_sub_heading;
		$post_meta['am_short_description']	= $am_short_description;
		
		update_post_meta($post_id, 'am_' . $post_type . '_data', $post_meta);
		
        //Update Doctor Post        
        $doctor_user = array(
            'ID'           => $post_id,
            'post_title'   => $display_name,
            'post_content' => $content,
        );
		wp_update_post( $doctor_user );
		
		//Profile avatar
        $profile_avatar = array();
        if( !empty( $_POST['basics']['avatar']['attachment_id'] ) ){
            $profile_avatar = $_POST['basics']['avatar'];
        } else {                                
            if( !empty( $_POST['basics']['avatar'] ) ){
                $profile_avatar = doccure_temp_upload_to_media($_POST['basics']['avatar'], $post_id);
            }
        }
		//delete prevoius attachment ID
		$pre_attachment_id = get_post_thumbnail_id($post_id);
		if ( !empty($pre_attachment_id) && !empty( $profile_avatar['attachment_id'] ) && intval($pre_attachment_id) != intval($profile_avatar['attachment_id'])) {
			wp_delete_attachment($pre_attachment_id, true);
		}
		
		//update thumbnail
		if (!empty($profile_avatar['attachment_id'])) {
			delete_post_thumbnail($post_id);
			set_post_thumbnail($post_id, $profile_avatar['attachment_id']);
		} else {
			wp_delete_attachment( $pre_attachment_id, true );
		}
		
        $json['type']    = 'success';
        $json['message'] = esc_html__('Settings Updated.', 'doccure');        
        wp_send_json($json);
    }
            
    add_action('wp_ajax_doccure_update_patient_profile', 'doccure_update_patient_profile');
    add_action('wp_ajax_nopriv_doccure_update_patient_profile', 'doccure_update_patient_profile');
}

/**
 * Update doctor update booking
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_update_doctor_booking_options' ) ){
    function doccure_update_doctor_booking_options(){  
		global $current_user;               
        $json = array();
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		$user_id		 = $current_user->ID;
		$post_id  		 = doccure_get_linked_profile_id($user_id);
		
        if( function_exists('doccure_validate_privileges') ) { 
			doccure_validate_privileges($post_id);
		} //if user is logged in and have privileges

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		$post_meta		= doccure_get_post_meta( $post_id);
		$post_type		= get_post_type($post_id);
		$am_booking_contact	= !empty($_POST['am_booking_contact']) ? $_POST['am_booking_contact'] : '';
		$am_booking_detail	= !empty($_POST['am_booking_detail']) ? $_POST['am_booking_detail'] : '';
		$post_meta['am_booking_contact']	= $am_booking_contact;
		$post_meta['am_booking_detail']		= $am_booking_detail;
		update_post_meta($post_id, 'am_' . $post_type . '_data', $post_meta);
		$json['type']    = 'success';
		$json['message'] = esc_html__('Settings Updated.', 'doccure');        

		wp_send_json($json);
	}
	add_action('wp_ajax_doccure_update_doctor_booking_options', 'doccure_update_doctor_booking_options');
    add_action('wp_ajax_nopriv_doccure_update_doctor_booking_options', 'doccure_update_doctor_booking_options');
}
/**
 * Update doctor Profile Education & Exprience
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_update_doctor_education' ) ){
    function doccure_update_doctor_education(){       
        global $current_user;               
        $json = array();
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		$user_id		 = $current_user->ID;
		$post_id  		 = doccure_get_linked_profile_id($user_id);
		
        if( function_exists('doccure_validate_privileges') ) { 
			doccure_validate_privileges($post_id);
		} //if user is logged in and have privileges
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		$post_meta		= doccure_get_post_meta( $post_id);
		$post_type		= get_post_type($post_id);
		
		if( $_POST['am_education']) {
			$post_meta['am_education']	= $_POST['am_education'];
			update_post_meta($post_id, 'am_' . $post_type . '_data', $post_meta);
		}else{
			$post_meta['am_education']	= array();
			update_post_meta($post_id, 'am_' . $post_type . '_data', $post_meta);
		}
		
		if( $_POST['am_experiences']) {
			$post_meta['am_experiences']	= $_POST['am_experiences'];
			update_post_meta($post_id, 'am_' . $post_type . '_data', $post_meta);    
		}else{
			$post_meta['am_experiences']	= array();
			update_post_meta($post_id, 'am_' . $post_type . '_data', $post_meta);    
		}
		
		$json['type']    = 'success';
		$json['message'] = esc_html__('Settings Updated.', 'doccure');        
		wp_send_json($json);
        
    }
            
    add_action('wp_ajax_doccure_update_doctor_education', 'doccure_update_doctor_education');
    add_action('wp_ajax_nopriv_doccure_update_doctor_education', 'doccure_update_doctor_education');
}

/**
 * Update doctor Profile Education & Exprience
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_update_doctor_award' ) ){
    function doccure_update_doctor_award(){       
        global $current_user;               
        $json = array();
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		$user_id		 = $current_user->ID;
		$post_id  		 = doccure_get_linked_profile_id($user_id);
		
        if( function_exists('doccure_validate_privileges') ) { 
			doccure_validate_privileges($post_id);
		} //if user is logged in and have privileges

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		$dc_downloads	= doccure_is_feature_value( 'dc_downloads', $user_id);
		$dc_awards		= doccure_is_feature_value( 'dc_awards', $user_id);

		$post_meta		= doccure_get_post_meta( $post_id);
		$post_type		= get_post_type($post_id);
		$awards			= !empty( $_POST['am_award'] ) ? $_POST['am_award'] : array();
		$download_files	= !empty( $_POST['am_downloads'] ) ? $_POST['am_downloads'] : array();

		$dc_total_files		= count($download_files);
		$dc_total_awards	= count($awards);

		$dc_total_files		= !empty($dc_total_files) ? intval($dc_total_files) : 0;
		$dc_total_awards	= !empty($dc_total_awards) ? intval($dc_total_awards) : 0;
		
		if( !empty( $awards ) ) {
			foreach( $awards as $key => $award ) {
				if( empty( $award['title'] ) ){
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Award title is required', 'doccure');
					wp_send_json($json);
				}
			}
		}

		if(empty($dc_downloads) || $dc_total_files > $dc_downloads ){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('Your package limit for submitting downloads has reached to maximum. Please upgrade or buy package to submit more downloads.', 'doccure');
			wp_send_json($json);
		}

		if( empty($dc_awards) || $dc_total_awards > $dc_awards ){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('Your package limit for submitting awards has reached to maximum. Please upgrade or buy package to submit more awards.', 'doccure');
			wp_send_json($json);
		}
		
		$post_meta['am_award']	= $awards;
		
		if( $download_files || $awards ) {
			$downloads	= $download_files;
			
			if( !empty( $downloads ) ) {
				$download_array	= array();
				foreach( $downloads as $key => $download ) {
					
					if( !empty( $_POST['am_downloads'][$key]['attachment_id'] ) ){
						$download_array[$key]['media'] 			= $download['media'];
						$download_array[$key]['id'] 			= $download['attachment_id'];
						
					} else {
						$new_uploaded_file 						= doccure_temp_upload_to_media($download['media'], $post_id);
						$download_array[$key]['media'] 			= $new_uploaded_file['url'];
						$download_array[$key]['id'] 			= $new_uploaded_file['attachment_id'];
					}
				}
				
			}
			
			
			$post_meta['am_downloads']	= $download_array;
			update_post_meta($post_id, 'am_' . $post_type . '_data', $post_meta);
			$json['type']    = 'success';
			$json['message'] = esc_html__('Settings Updated.', 'doccure');   
		}
		wp_send_json($json);
        
    }
            
    add_action('wp_ajax_doccure_update_doctor_award', 'doccure_update_doctor_award');
    add_action('wp_ajax_nopriv_doccure_update_doctor_award', 'doccure_update_doctor_award');
}

/**
 * Update doctor Profile Education & Exprience
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_update_ap_location' ) ){
    function doccure_update_ap_location(){   
        global $current_user,$doccure_options;               
        $json = array();
		$post_id		= !empty( $_POST['post_id'] ) ? sanitize_text_field($_POST['post_id']) : '';
		$services		= !empty( $_POST['service'] ) ? $_POST['service'] : array();
		$consultant_fee	 = !empty( $_POST['consultant_fee'] ) ? sanitize_text_field( $_POST['consultant_fee'] ) : 0;
		$user_id		 = $current_user->ID;
		
		if( function_exists('doccure_validate_privileges') ) { 
			doccure_validate_privileges($post_id);
		} //if user is logged in and have privileges
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}

		if( empty($post_id)) {
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Something went wrong, please contact to administrator', 'doccure');
            wp_send_json($json);
		}

		$allow_consultation_zero	 = !empty( $doccure_options['allow_consultation_zero'] ) ? $doccure_options['allow_consultation_zero'] : 'no';

		if( !empty($allow_consultation_zero) && $allow_consultation_zero === 'no' ){
			if( empty($consultant_fee)) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Consultation fee is required', 'doccure');
				wp_send_json($json);
			}
		}

        $post_author	= get_post_field('post_author', $post_id);
		
		if( $post_author != $user_id) {
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('You are not an authorized user to update this.', 'doccure');
            wp_send_json($json);
		}
		
		if( !empty( $post_id ) ){
			update_post_meta( $post_id ,'_consultant_fee',$consultant_fee);
			update_post_meta( $post_id,'_team_services',$services);
			$json['type']    = 'success';
			$json['message'] = esc_html__('Settings have been updated', 'doccure');

			wp_send_json($json);
		}
		 
    }
            
    add_action('wp_ajax_doccure_update_ap_location', 'doccure_update_ap_location');
    add_action('wp_ajax_nopriv_doccure_update_ap_location', 'doccure_update_ap_location');
}

/**
 * Update doctor Profile Education & Exprience
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_update_ap_services' ) ){
    function doccure_update_ap_services(){              
		global $current_user,$doccure_options;               
        $json = array();
		$post_id		= !empty( $_POST['post_id'] ) ? sanitize_text_field($_POST['post_id']) : '';
		$services		= !empty( $_POST['service'] ) ? $_POST['service'] : array();
		$consultant_fee	 = !empty( $_POST['consultant_fee'] ) ? sanitize_text_field( $_POST['consultant_fee'] ) : 0;
		$allow_consultation_zero	 = !empty( $doccure_options['allow_consultation_zero'] ) ? $doccure_options['allow_consultation_zero'] : 'no';
		$user_id		 = $current_user->ID;
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		if( function_exists('doccure_validate_privileges') ) { 
			doccure_validate_privileges($post_id);
		} //if user is logged in and have privileges
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}

		if( empty($post_id)) {
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Something went wrong, please contact to administrator', 'doccure');
            wp_send_json($json);
		}
		
		if(!empty($allow_consultation_zero) && allow_consultation_zero === 'no'){
			if( empty($consultant_fee)) {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Consultation fee is required', 'doccure');
				wp_send_json($json);
			}
		}
		
        $post_author	= get_post_field('post_author', $post_id);
		
		if( $post_author != $user_id) {
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('You are not an authorized user to update this.', 'doccure');
            wp_send_json($json);
		}
		
		if( !empty( $post_id ) ){
			update_post_meta( $post_id ,'_consultant_fee',$consultant_fee);
			update_post_meta( $post_id,'_team_services',$services);
			$json['type']    = 'success';
			$json['message'] = esc_html__('Providing Services are Updated.', 'doccure');

			wp_send_json($json);
		}
		 
    }
            
    add_action('wp_ajax_doccure_update_ap_services', 'doccure_update_ap_services');
    add_action('wp_ajax_nopriv_doccure_update_ap_services', 'doccure_update_ap_services');
}

/**
 * Update doctor Profile Education & Exprience
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_update_gallery' ) ){
    function doccure_update_gallery(){       
        global $current_user, $post;               
        $json 				= array();
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		$user_id		 = $current_user->ID;
		$post_id  		 = doccure_get_linked_profile_id($user_id);
		
        if( function_exists('doccure_validate_privileges') ) { 
			doccure_validate_privileges($post_id);
		} //if user is logged in and have privileges

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		if( function_exists('doccure_check_video_url') ){
			if( !empty($_POST['am_videos']) ){
				foreach( $_POST['am_videos'] as $video_url ){
					$check_video = doccure_check_video_url($video_url);
					if( empty($check_video) || $check_video === false ){
						$json['type'] 		= 'error';
						$json['message'] 	= esc_html__('Please add valid video URL','doccure');        
						wp_send_json($json);
					}
					
				}
			}
		}
		
		$post_meta		= doccure_get_post_meta( $post_id);
		$post_type		= get_post_type($post_id);
		$am_gallery		= !empty($_POST['am_gallery']) ? $_POST['am_gallery'] : array();
		$am_videos		= !empty($_POST['am_videos']) ? $_POST['am_videos'] : array();
		$gallery		= !empty($_POST['gallery']['images_gallery_new']) ? $_POST['gallery']['images_gallery_new'] : array();

		if( !empty($am_gallery) || !empty( $gallery ) ) {
			$post_meta['am_gallery']	= $am_gallery;
			if( !empty( $gallery ) ){
				$new_index	= !empty($post_meta['am_gallery']) ?  max(array_keys($post_meta['am_gallery'])) : 0;
				foreach( $gallery as $new_gallery ){
					$new_index ++;
					$profile_gallery 							= doccure_temp_upload_to_media($new_gallery, $post_id);
					$post_meta['am_gallery'][$new_index]		= $profile_gallery;
				}
			}
		}else{
			$post_meta['am_gallery']	= array();
		}
		
		
		$post_meta['am_videos']	= $am_videos;
		update_post_meta($post_id, 'am_' . $post_type . '_data', $post_meta);  
		
		$json['type']    = 'success';
		$json['message'] = esc_html__('Settings Updated.', 'doccure');    
		
		wp_send_json($json);
        
    }
            
    add_action('wp_ajax_doccure_update_gallery', 'doccure_update_gallery');
    add_action('wp_ajax_nopriv_doccure_update_gallery', 'doccure_update_gallery');
}


/**
 * Update doctor social profiles
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_social_profiles' ) ){
    function doccure_social_profiles(){       
        global $current_user, $post;               
        $json 				= array();
		$user_id		 = $current_user->ID;
		$post_id  		 = doccure_get_linked_profile_id($user_id);
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		if( function_exists('doccure_validate_privileges') ) { 
			doccure_validate_privileges($post_id);
		} //if user is logged in and have privileges
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}

		$post_meta		= doccure_get_post_meta( $post_id);
		$post_type		= get_post_type($post_id);
		$post_meta['am_socials']		= !empty($_POST['basics']) ? $_POST['basics'] : array();
		update_post_meta($post_id, 'am_' . $post_type . '_data', $post_meta);  
		
		$json['type']    = 'success';
		$json['message'] = esc_html__('Settings Updated.', 'doccure');    
		
		wp_send_json($json);
        
    }
            
    add_action('wp_ajax_doccure_social_profiles', 'doccure_social_profiles');
    add_action('wp_ajax_nopriv_doccure_social_profiles', 'doccure_social_profiles');
}
/**
 * Update doctor Profile Education & Exprience
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_update_doctor_registrations' ) ){
    function doccure_update_doctor_registrations(){       
        global $current_user, $post;               
        $json 				= array();
		$am_documents_array	= array();
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		$user_id		 = $current_user->ID;
		$post_id  		 = doccure_get_linked_profile_id($user_id);
		
        if( function_exists('doccure_validate_privileges') ) { 
			doccure_validate_privileges($post_id);
		} //if user is logged in and have privileges

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		$post_meta		= doccure_get_post_meta( $post_id);
		$post_type		= get_post_type($post_id);
		
		if( $_POST['am_registration_number']) {
			$post_meta['am_registration_number']	= $_POST['am_registration_number'];      
		}
		
		if( $_POST['am_document']) {
			$am_documents	= $_POST['am_document'];
			if( !empty( $am_documents ) ) {
				if( !array_key_exists("id",$am_documents) && !empty(  $am_documents['url']  ) ) {
					$uploaded_file 					= doccure_temp_upload_to_media($am_documents['url'], $post_id);
					$am_documents_array['url'] 			= $uploaded_file['url'];
					$am_documents_array['id'] 			= $uploaded_file['attachment_id'];
				} else {
					$am_documents_array['url'] 			= $am_documents['url'];
					$am_documents_array['id'] 			= $am_documents['id'];
				}
			}
			
			$post_meta['am_document']	= $am_documents_array;
			update_post_meta($post_id, 'am_' . $post_type . '_data', $post_meta);
			$json['type']    = 'success';
			$json['message'] = esc_html__('Settings Updated.', 'doccure');        
		}
		wp_send_json($json);
        
    }
            
    add_action('wp_ajax_doccure_update_doctor_registrations', 'doccure_update_doctor_registrations');
    add_action('wp_ajax_nopriv_doccure_update_doctor_registrations', 'doccure_update_doctor_registrations');
}

/**
 * Update doctor Profile Education & Exprience
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_update_specialities' ) ){
    function doccure_update_specialities(){       
        global $current_user;               
        
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		$json 		= array();
		$meta_data 	= array();

		$user_id	= $current_user->ID;
		$post_id  	= doccure_get_linked_profile_id($user_id);
		$dc_services		= doccure_is_feature_value( 'dc_services', $user_id);
		
        if( function_exists('doccure_validate_privileges') ) { 
			doccure_validate_privileges($post_id);
		} //if user is logged in and have privileges

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		$post_meta		= doccure_get_post_meta( $post_id );
		$post_type		= get_post_type($post_id);
		$post_meta		= !empty( $post_meta ) ? $post_meta : array();
		$specialities	= !empty( $_POST['am_specialities'] ) ? $_POST['am_specialities'] : array();

		
		$specialities_array	= array();
		
		if( !empty( $specialities ) ){
			foreach( $specialities as $keys => $vals ){
				if( !empty( $vals['speciality_id'] ) ){
					$specialities_array[] = $vals['speciality_id'];
					$meta_data[$vals['speciality_id']] = array();
					$service			= array();
					if( !empty( $vals['services'] ) ) {
						foreach( $vals['services'] as $key => $val ) {
							if( !empty( $val['service'] ) ){
								$service[] = $val['service'];
								$meta_data[$vals['speciality_id']][$val['service']] = $val;
								
								if( !empty($post_type) && ($post_type ==='doctors') ){
									$service_count	= count($service);
									$service_count	= !empty($service_count) ? intval($service_count) : 0;
									$dc_services	= doccure_is_feature_value( 'dc_services', $user_id);
									if( ( empty($dc_services) ) || ( $service_count > $dc_services )  ){
										$json['type'] 		= 'error';
										$json['message'] 	= sprintf( esc_html__('Your package has a limit(%s) for submitting services under the speciality', 'doccure'),$dc_services);
										wp_send_json($json);
									} 
								}
							}
						}
					}
				}
			}
		}

		$post_meta['am_specialities']	= $meta_data;
		update_post_meta($post_id, 'am_' . $post_type . '_data', $post_meta);
		
		if( !empty( $service ) ){
			wp_set_post_terms( $post_id, $service, 'services' );
		}
		
		if( !empty( $specialities_array ) ){
			wp_set_post_terms( $post_id, $specialities_array, 'specialities' );
		}
		
		$json['type']    = 'success';
		$json['message'] = esc_html__('Services are Updated.', 'doccure');
		
		wp_send_json($json);
        
    }
            
    add_action('wp_ajax_doccure_update_specialities', 'doccure_update_specialities');
    add_action('wp_ajax_nopriv_doccure_update_specialities', 'doccure_update_specialities');
}

/**
 * Update account settings
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_update_account_settings' ) ){
    function doccure_update_account_settings(){       
        global $current_user, $post;               
        $json = array();
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		$user_id		 = $current_user->ID;
		$post_id  		 = doccure_get_linked_profile_id($user_id);
		
        if( function_exists('doccure_validate_privileges') ) { 
			doccure_validate_privileges($post_id);
		} //if user is logged in and have privileges

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}

		$post_type		= get_post_type($post_id);
		$settings		= doccure_get_account_settings($post_type);
		
		if( !empty( $settings ) ){
			foreach( $settings as $key => $value ){
				$save_val 	= !empty( $_POST['settings'][$key] ) ? $_POST['settings'][$key] : '';
				$db_val 	= !empty( $save_val ) ?  $save_val : 'off';
				update_post_meta($post_id, $key, $db_val);
			}
			$json['type']    = 'success';
			$json['message'] = esc_html__('Account settings are Updated.', 'doccure'); 
		}
		wp_send_json($json);
        
    }
            
    add_action('wp_ajax_doccure_update_account_settings', 'doccure_update_account_settings');
    add_action('wp_ajax_nopriv_doccure_update_account_settings', 'doccure_update_account_settings');
}

/**
 * Update hospitals Profile
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_update_hospitals_profile' ) ){
    function doccure_update_hospitals_profile(){       
        global $current_user, $post;               
		$json 			= array();
		$post_meta 		= array();
		$user_id		 = $current_user->ID;
		$post_id  		 = doccure_get_linked_profile_id($user_id);
		
        if( function_exists('doccure_validate_privileges') ) { 
			doccure_validate_privileges($post_id);
		} //if user is logged in and have privileges

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}

        $required_fields = array(
            'am_first_name'   	=> esc_html__('First  name is required', 'doccure'),
            'am_last_name'  	=> esc_html__('Last name is required', 'doccure'),
        );

        foreach ($required_fields as $key => $value) {
           if( empty( $_POST[$key] ) ){
            $json['type'] 		= 'error';
            $json['message'] 	= $value;        
            wp_send_json($json);
           }
        }
		$post_type		= get_post_type($post_id);
		$post_meta		= get_post_meta($post_id, 'am_' . $post_type . '_data',true);
		
		
		if( !empty( $post_type ) && $post_type === 'hospitals' ){
			$post_meta['am_week_days']		= !empty( $_POST['am_week_days'] ) ?  $_POST['am_week_days']  : array();
			$post_meta['am_mobile_number']	= !empty( $_POST['am_mobile_number'] ) ?  $_POST['am_mobile_number']  : '';
			$post_meta['am_phone_numbers']	= !empty( $_POST['am_phone_numbers'] ) ?  $_POST['am_phone_numbers']  : array();
			$post_meta['am_web_url']		= !empty( $_POST['am_web_url'] ) ?  $_POST['am_web_url']  : '';

			$post_meta['am_availability']	= !empty( $_POST['am_availability'] ) ? sanitize_text_field( $_POST['am_availability'] ) : '';
			$post_meta['am_sub_heading']	= !empty( $_POST['am_sub_heading'] ) ? sanitize_text_field( $_POST['am_sub_heading'] ) : '';
			
			if( !empty( $_POST['am_other_time'] ) ) {
				$post_meta['am_other_time']	= sanitize_text_field( $_POST['am_other_time'] );
			} else {
				$post_meta['am_other_time']	= '';
			}
		}
		
        //Form data
        $am_first_name 			= !empty($_POST['am_first_name']) ? sanitize_text_field($_POST['am_first_name']) : '';
        $am_last_name  			= !empty($_POST['am_last_name'] ) ? sanitize_text_field($_POST['am_last_name']) : '';
		$am_short_description 	= !empty($_POST['am_short_description'] ) ? sanitize_text_field($_POST['am_short_description']) : '';
		$display_name 			= !empty($_POST['display_name']) ? sanitize_text_field($_POST['display_name']) : '';
		$location 				= !empty($_POST['location']) ? doccure_get_term_by_type('slug',sanitize_text_field($_POST['location']),'locations' ): '';
		$address				= !empty($_POST['address'] ) ? $_POST['address'] : '';
		$longitude				= !empty($_POST['longitude'] ) ? $_POST['longitude'] : '';
		$latitude				= !empty($_POST['latitude'] ) ? $_POST['latitude'] : '';
		$am_sub_heading  		= !empty($_POST['am_sub_heading'] ) ? sanitize_text_field($_POST['am_sub_heading']) : '';
        $content				= !empty($_POST['content'] ) ? $_POST['content'] : '';
		
        //Update user meta
        update_user_meta($user_id, 'first_name', $am_first_name);
        update_user_meta($user_id, 'last_name', $am_last_name);
		
		$post_meta['am_first_name']			= $am_first_name;
		$post_meta['am_last_name']			= $am_last_name;
		$post_meta['am_sub_heading']		= $am_sub_heading;
		$post_meta['am_short_description']	= $am_short_description;

		update_post_meta($post_id, 'am_' . $post_type . '_data', $post_meta);
		
		wp_set_post_terms( $post_id, $location, 'locations' );
		update_post_meta($post_id, '_address', $address);
		update_post_meta($post_id, '_longitude', $longitude);
		update_post_meta($post_id, '_latitude', $latitude);
		
        //Update Hospital Post        
        $hospital_profile = array(
            'ID'           => $post_id,
            'post_title'   => $display_name,
            'post_content' => $content,
        );
		wp_update_post( $hospital_profile );
		
		//update languages
		$lang		= array();
		if( !empty( $_POST['settings']['languages'] ) ){
			foreach( $_POST['settings']['languages'] as $key => $item ){
				$lang[] = $item;
			}
		}
		
		wp_set_post_terms($post_id, $lang, 'languages');
		
		//Profile avatar
        $profile_avatar = array();
        if( !empty( $_POST['basics']['avatar']['attachment_id'] ) ){
            $profile_avatar = $_POST['basics']['avatar'];
        } else {                                
            if( !empty( $_POST['basics']['avatar'] ) ){
                $profile_avatar = doccure_temp_upload_to_media($_POST['basics']['avatar'], $post_id);
            }
        }
		
		//delete prevoius attachment ID
		$pre_attachment_id = get_post_thumbnail_id($post_id);
		if ( !empty($pre_attachment_id) && !empty( $profile_avatar['attachment_id'] ) && intval($pre_attachment_id) != intval($profile_avatar['attachment_id'])) {
			wp_delete_attachment($pre_attachment_id, true);
		}
		
		//update thumbnail
		if (!empty($profile_avatar['attachment_id'])) {
			delete_post_thumbnail($post_id);
			set_post_thumbnail($post_id, $profile_avatar['attachment_id']);
		} else {
			wp_delete_attachment( $pre_attachment_id, true );
		}
		
        $json['type']    = 'success';
        $json['message'] = esc_html__('Settings Updated.', 'doccure');        
        wp_send_json($json);
    }
            
    add_action('wp_ajax_doccure_update_hospitals_profile', 'doccure_update_hospitals_profile');
    add_action('wp_ajax_nopriv_doccure_update_hospitals_profile', 'doccure_update_hospitals_profile');
}
/**
 * delete account
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_user_by_email' ) ) {
	function doccure_user_by_email() {
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		$json	= array();
		$email	= !empty( $_POST['email'] ) ? is_email( $_POST['email'] )  : '';
		
		if( empty($email) ){
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Email address is invalid','doccure');        
            wp_send_json($json);
		} else {
			
			$user_info 		= get_user_by('email',$email);
			
			$user_type		= !empty($user_info->roles[0]) ? $user_info->roles[0] : '';
			if( !empty($user_type) && $user_type !='regular_users' ){
				$json['type'] 			= 'success';
				$json['success_type'] 	= 'other';
				$json['message'] 		= esc_html__('This email address is being used for one of the other user other than patient. Please user another email address to find or add patient.','doccure');
			} else if(!empty($user_info) && $user_type ==='regular_users' ){
				$last_name	= get_user_meta($user_info->ID, 'last_name', true );
				$first_name	= get_user_meta($user_info->ID, 'first_name', true );
				$mobile_number	= get_user_meta($user_info->ID, 'mobile_number', true );
				$json['type'] 			= 'success';
				$json['success_type'] 	= 'registered';
				$json['first_name'] 	= !empty($first_name) ? $first_name :'';
				$json['last_name'] 		= !empty($last_name) ? $last_name : '';
				$json['mobile_number'] 	= !empty($mobile_number) ? $mobile_number : '';
				$json['user_id'] 		= $user_info->ID;
				$json['message'] 		= esc_html__('Patient exist','doccure');
			} else {
				$json['type'] 			= 'success';
				$json['success_type'] 	= 'new';
			}
			wp_send_json($json);
		}
	}
	add_action('wp_ajax_doccure_user_by_email', 'doccure_user_by_email');
    add_action('wp_ajax_nopriv_doccure_user_by_email', 'doccure_user_by_email');
}
/**
 * delete account
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_delete_account' ) ) {

	function doccure_delete_account() {
		global $current_user;
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		$post_id	= doccure_get_linked_profile_id($current_user->ID);
		$user 		= wp_get_current_user(); //trace($user);
		$json 		= array();

		if( function_exists('doccure_validate_privileges') ) { 
			doccure_validate_privileges($post_id);
		} //if user is logged in and have privileges

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		$required = array(
            'password'   	=> esc_html__('Password is required', 'doccure'),
            'retype'  		=> esc_html__('Retype your password', 'doccure'),
            'reason' 		=> esc_html__('Select reason to delete your account', 'doccure'),
        );

        foreach ($required as $key => $value) {
           if( empty( sanitize_text_field($_POST['delete'][$key] ) )){
            $json['type'] = 'error';
            $json['message'] = $value;        
            wp_send_json($json);
           }
        }
		
		$password	= !empty( $_POST['delete']['password'] ) ? sanitize_text_field( $_POST['delete']['password'] )  : '';
		$retype		= !empty( $_POST['delete']['retype'] ) ? sanitize_text_field( $_POST['delete']['retype'] )  : '';
		if (empty($password) || empty($retype)) {
            $json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Please add your password and retype password.', 'doccure');
            wp_send_json( $json );
        }
		
		$user_name 	 = doccure_get_username($user->data->ID);
		$user_email	 = $user->user_email;
        $is_password = wp_check_password($password, $user->user_pass, $user->data->ID);
		
	
		if( $is_password ){
			wp_delete_user($user->data->ID);
			wp_delete_post($post_id,true);
			
			extract($_POST['delete']);
			$reason		 = doccure_get_account_delete_reasons($reason);
			
			//Send email to users
			if (class_exists('doccure_Email_helper')) {
				if (class_exists('doccureDeleteAccount')) {
					$email_helper 	= new doccureDeleteAccount();
					$emailData 		= array();
					
					$emailData['username'] 			= esc_html( $user_name );
					$emailData['reason'] 			= esc_html( $reason );
					$emailData['email'] 			= esc_html( $user_email );
					$emailData['description'] 		= sanitize_textarea_field( $description );
					$email_helper->send($emailData);
				}
			}

			$json['type'] = 'success';
			$json['message'] = esc_html__('You account has been deleted.', 'doccure');

			wp_send_json( $json );
		} else{
			$json['type'] = 'error';
			$json['message'] = esc_html__('Password doesn\'t match', 'doccure');
			wp_send_json( $json );
		}
	}

	add_action( 'wp_ajax_doccure_delete_account', 'doccure_delete_account' );
	add_action( 'wp_ajax_nopriv_doccure_delete_account', 'doccure_delete_account' );
}

/**
 * Update User Password
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_change_user_password')) {

    function doccure_change_user_password() {
        global $current_user;
        $user_identity = $current_user->ID;
        $json = array();
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
        if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		$old_password	= !empty( $_POST['password'] ) ? sanitize_text_field($_POST['password']) : '';
		$password		= !empty( $_POST['retype'] ) ? sanitize_text_field($_POST['retype']) : '';
		if( empty( $old_password ) || empty( $password ) ){
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Current and new password fields are required.', 'doccure');
            wp_send_json( $json );
		}
		
        $user 			= wp_get_current_user(); //trace($user);
        $is_password 	= wp_check_password($old_password, $user->user_pass, $user->data->ID);

        if ($is_password) {

            if (empty($old_password) ) {
                $json['type'] 		= 'error';
                $json['message'] 	= esc_html__('Please add your new password.', 'doccure');
             } else {
				wp_update_user(array('ID' => $user_identity, 'user_pass' => $password));
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Password Updated.', 'doccure');
			}
			
        } else {
            $json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Old Password doesn\'t matched with the existing password', 'doccure');
        }

       wp_send_json( $json );
    }

    add_action('wp_ajax_doccure_change_user_password', 'doccure_change_user_password');
    add_action('wp_ajax_nopriv_doccure_change_user_password', 'doccure_change_user_password');
}


/**
 * Update User email
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_change_user_email')) {

    function doccure_change_user_email() {
		global $current_user;
		$user_identity = $current_user->ID;
		$useremail	= !empty( $_POST['useremail'] ) ? sanitize_text_field($_POST['useremail']) : '';
		$json = array();

		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent

		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		if(!is_email($useremail)){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('Please add a valid email address', 'doccure');
			wp_send_json( $json );
		}

		$user_data	= wp_update_user(array('ID' => $user_identity, 'user_email' => $useremail));

		if ( is_wp_error( $user_data ) ) {
			$error_string = $user_data->get_error_message();
			$json['type'] 		= 'error';
			$json['message'] 	= $error_string;
			wp_send_json( $json );
		} else {
			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Email has been updated', 'doccure');
			wp_send_json( $json );
		}
		

		wp_send_json( $json );
    }

    add_action('wp_ajax_doccure_change_user_email', 'doccure_change_user_email');
    add_action('wp_ajax_nopriv_doccure_change_user_email', 'doccure_change_user_email');
}

/**
 * Remove single Save item
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_remove_save_item' ) ) {

	function doccure_remove_save_item() {
		$json			=  array();
		$post_id		= !empty( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : '';
		$item_id		= !empty( $_POST['item_id'] ) ? array(intval( $_POST['item_id'] )) : array();
		$item_type		= !empty( $_POST['item_type'] ) ? ( $_POST['item_type'] ) : '';
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		if( function_exists('doccure_validate_privileges') ) { 
			doccure_validate_privileges($post_id);
		} //if user is logged in and have privileges

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		if( !empty($post_id) && !empty($item_type) && !empty($item_id) ){
			$save_items_ids		= get_post_meta( $post_id, $item_type, true);
			$updated_values 	= array_diff(  $save_items_ids , $item_id);
			update_post_meta( $post_id, $item_type, $updated_values );
			
			$json['type'] 		= 'success';
            $json['message'] 	= esc_html__('Remove save item successfully.', 'doccure');
            wp_send_json($json);
		} else{
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Some error occur, please try again later', 'doccure');
            wp_send_json($json);
		}		
	}

	add_action( 'wp_ajax_doccure_remove_save_item', 'doccure_remove_save_item' );
	add_action( 'wp_ajax_nopriv_doccure_remove_save_item', 'doccure_remove_save_item' );
}

/**
 * Remove Multiple Save item
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_remove_save_multipuleitems' ) ) {

	function doccure_remove_save_multipuleitems() {
		$json			=  array();
		$post_id		= !empty( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : '';
		$item_type		= !empty( $_POST['item_type'] ) ? sanitize_text_field( $_POST['item_type'] ) : '';
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		if( function_exists('doccure_validate_privileges') ) { 
			doccure_validate_privileges($post_id);
		} //if user is logged in and have privileges

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		if( !empty($post_id) && !empty($item_type) && !empty($item_type) ){
			update_post_meta( $post_id, $item_type, '' );
			
			$json['type'] 		= 'success';
            $json['message'] 	= esc_html__('Remove save items successfully.', 'doccure');
            wp_send_json($json);
		} else{
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Some error occur, please try again later', 'doccure');
            wp_send_json($json);
		}		
	}

	add_action( 'wp_ajax_doccure_remove_save_multipuleitems', 'doccure_remove_save_multipuleitems' );
	add_action( 'wp_ajax_nopriv_doccure_remove_save_multipuleitems', 'doccure_remove_save_multipuleitems' );
}

/**
 * Add to Cart
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_update_cart' ) ) {

	function doccure_update_cart() {
		$json				=  array();
		$product_id		= !empty( $_POST['id'] ) ? intval( $_POST['id'] ) : '';
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		if( !empty( $product_id )) {
			if ( class_exists('WooCommerce') ) {
			
				global $current_user, $woocommerce;
				
				$woocommerce->cart->empty_cart(); //empty cart before update cart
				$user_id			= $current_user->ID;
				$is_cart_matched	= doccure_matched_cart_items($product_id);
				
				if ( isset( $is_cart_matched ) && $is_cart_matched > 0) {
					$json = array();
					$json['type'] 			= 'success';
					$json['message'] 		= esc_html__('You have already in cart, We are redirecting to checkout', 'doccure');
					$json['checkout_url'] 	= wc_get_cart_url();
					wp_send_json($json);
				}
				
				$cart_meta					= array();
				$user_type					= doccure_get_user_type( $user_id );
				$pakeges_features			= doccure_get_pakages_features();
				
				if ( !empty ( $pakeges_features )) {
					
					foreach( $pakeges_features as $key => $vals ) {
						
						if( $vals['user_type'] === $user_type || $vals['user_type'] === 'common' ) {
							$item			= get_post_meta($product_id,$key,true);
							$text			=  !empty( $vals['text'] ) ? ' '.sanitize_text_field($vals['text']) : '';
							
							if( $key === 'dc_duration' ) {
								$feature 	= doccure_get_duration_types($item,'title');
							}else if( $key === 'dc_duration_days' ) {
								$pkg_duration	= get_post_meta($product_id,'dc_duration',true);
								$duration 		= doccure_get_duration_types($pkg_duration,'title');
								if( $duration === 'others') {
									$feature 	= doccure_get_duration_types($item,'value');
								} else {
									$feature	= '';	
									$key		= '';
								}
							}else {
								$feature 	= $item;
							}
							
							if( !empty( $key )){
								$cart_meta[$key]	= $feature.$text;
							}
						}
					}
				}
				
				$cart_data = array(
					'product_id' 		=> $product_id,
					'cart_data'     	=> $cart_meta,
					'payment_type'     	=> 'subscription',
				);

				$woocommerce->cart->empty_cart();
				$cart_item_data = $cart_data;
				WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);

				$json = array();
				$json['type'] 			= 'success';
				$json['message'] 		= esc_html__('Please wait you are redirecting to checkout page.', 'doccure');
				$json['checkout_url']	= wc_get_cart_url();
				wp_send_json($json);
			} else {
				$json = array();
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('Please install WooCommerce plugin to process this order', 'doccure');
			}
			
		} else{
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Some error occur, please try again later', 'doccure');
            wp_send_json($json);
		}	
		
	}

	add_action( 'wp_ajax_doccure_update_cart', 'doccure_update_cart' );
	add_action( 'wp_ajax_nopriv_doccure_update_cart', 'doccure_update_cart' );
}

/**
 * FAQ support
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_support_faq' ) ) {

	function doccure_support_faq() {
		$json			=  array();
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		$query_type		= !empty( $_POST['query_type'] ) ? $_POST['query_type'] : '';
		$details		= !empty( $_POST['details'] ) ? $_POST['details'] : '';
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		if( empty($details) ) {
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Message is required.', 'doccure');
            wp_send_json($json);
		} else if( empty($query_type) ) {
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Query type is required.', 'doccure');
            wp_send_json($json);
		}else if( !empty(details) && !empty($query_type) ){
			$json['type'] 		= 'success';
            $json['message'] 	= esc_html__('Remove save items successfully.', 'doccure');
            wp_send_json($json);
		} else{
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Some error occur, please try again later', 'doccure');
            wp_send_json($json);
		}
		
	}

	add_action( 'wp_ajax_doccure_support_faq', 'doccure_support_faq' );
	add_action( 'wp_ajax_nopriv_doccure_support_faq', 'doccure_support_faq' );
}


/**
 * follow action
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_follow_doctors' ) ) {

	function doccure_follow_doctors() {
		global $current_user;
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		$post_id = !empty( $_POST['id'] ) ? intval( $_POST['id'] ) : '';
		$json = array();

		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		$linked_profile   	= doccure_get_linked_profile_id($current_user->ID);
		$post_type			= get_post_type($post_id);
		$post_key			= '_saved_'.$post_type;
		$saved_doctors 		= get_post_meta($linked_profile, $post_key, true);
		
		$json       = array();
        $wishlist   = array();
        $wishlist   = !empty( $saved_doctors ) && is_array( $saved_doctors ) ? $saved_doctors : array();

        if (!empty($post_id)) {
            if( in_array($post_id, $wishlist ) ){             
                $json['type'] 		= 'error';
                $json['message'] 	= esc_html__('This is already to your Favorites', 'doccure');
                wp_send_json( $json );
            } else {
				$wishlist[] = $post_id;
				$wishlist   = array_unique( $wishlist );
				update_post_meta( $linked_profile, $post_key, $wishlist );

				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Successfully! added to your Favorites', 'doccure');
				wp_send_json( $json );
			}
        }
        
        $json['type'] = 'error';
        $json['message'] = esc_html__('Oops! something is going wrong.', 'doccure');
        wp_send_json( $json );
	}

	add_action( 'wp_ajax_doccure_follow_doctors', 'doccure_follow_doctors' );
	add_action( 'wp_ajax_nopriv_doccure_follow_doctors', 'doccure_follow_doctors' );
}

/**
 * add question 
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_question_submit' ) ){
    function doccure_question_submit(){       
        global $current_user, $post, $doccure_options;               
        $json = array();
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		$user_id		 	= $current_user->ID;
		$post_setting		= !empty( $doccure_options['forum_question_status'] ) ?  $doccure_options['forum_question_status'] : 'pending';
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in

        //security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		if( empty($_POST['speciality']) || empty($_POST['title']) || empty($_POST['description']) ) {
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('All fields are required.', 'doccure');
            wp_send_json($json);
		}
		
		if( empty($current_user)) {
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Please login to submit question.', 'doccure');
            wp_send_json($json);
		}
		
		$post_title			= !empty( $_POST['title'] ) ? sanitize_text_field( $_POST['title']) : '';
		$post_content		= !empty( $_POST['description'] ) ?  $_POST['description'] : '';
		$speciality			= !empty( $_POST['speciality'] ) ? $_POST['speciality'] : array(0);
		
		if(! empty( $post_title ) ){
			$post_array['post_title']		= $post_title;
			$post_array['post_content']		= $post_content;
			$post_array['post_author']		= $user_id;
			$post_array['post_type']		= 'healthforum';
			$post_array['post_status']		= $post_setting;
			$post_id 						= wp_insert_post($post_array);
			
			//Send email to user
			if (class_exists('doccure_Email_helper')) {
				if (class_exists('doccureForum')) {
					$term_data	= get_term_by('slug',$speciality,'specialities' );
					$email_helper = new doccureForum();
					$emailData = array();
					$emailData['question'] = $post_title;
					$emailData['description']  	= $post_content;
					$emailData['name']     		= doccure_get_username( $user_id );
					$emailData['category']      = !empty($term_data->name) ? $term_data->name : $speciality;
					$email_helper->send($emailData);
				}
			} 
			
			if( $post_id ) {
				wp_set_object_terms($post_id,$speciality,'specialities');
				$json['type']    = 'success';
        		$json['message'] = esc_html__('Question is submitted successfully.', 'doccure');    
			}
		}
		
		wp_send_json($json);
        
    }
            
    add_action('wp_ajax_doccure_question_submit', 'doccure_question_submit');
    add_action('wp_ajax_nopriv_doccure_question_submit', 'doccure_question_submit');
}

/**
 * Get hospitals by key change
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_get_hospitals' ) ) {

	function doccure_get_hospitals() {
		global $current_user;
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in

		$s	 		= sanitize_text_field($_REQUEST['term']);
		$results 	= new WP_Query( array( 'posts_per_page' => -1, 's' => esc_html( $s ), 'post_type' => 'hospitals' ) );
		$items 		= array();
		
		if ( !empty( $results->posts ) ) {
			foreach ( $results->posts as $result ) {
				$suggestion 			= array();
				$suggestion['label'] 	= $result->post_title;
				$suggestion['id'] 		= $result->ID;
				$exist_post				= doccure_get_total_posts_by_meta( 'hospitals_team','hospital_id',$result->ID,array( 'publish','pending' ), $current_user->ID );
				
				if( empty( $exist_post )) {
					$items[] = $suggestion;
				} 
				
			}
		}

		$response = $_GET["callback"] . "(" . json_encode($items) . ")";
		echo do_shortcode($response);
		exit;
	}

	add_action( 'wp_ajax_doccure_get_hospitals', 'doccure_get_hospitals' );
	add_action( 'wp_ajax_nopriv_doccure_get_hospitals', 'doccure_get_hospitals' );
}

/**
 * Change post status
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_change_post_status' ) ) {

	function doccure_change_post_status() {
		global $current_user;
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		$post_id 	= !empty( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : '';
		$status 	= !empty( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : '';
		
		$json 		= array();
		$emailData	= array();

		if( empty( $post_id ) ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__( 'Doctor ID is missing.', 'doccure' );
			wp_send_json( $json );
		}
		
		if( empty( $status ) ) {
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__( 'Doctor status is required.', 'doccure' );
			wp_send_json( $json );
		}
		
		$doctor_id 		= get_post_field( 'post_author', $post_id );
		$doctor_profile	= doccure_get_linked_profile_id( $doctor_id);
		$doctor_name	= doccure_full_name($doctor_profile);
		$doctor_name	= !empty( $doctor_name ) ? esc_html( $doctor_name ) : '';
		$author_id 		= get_post_meta( $post_id ,'hospital_id', true);
		$hospital_link	= get_the_permalink( $author_id );
		$hospital_link	= !empty( $hospital_link ) ? esc_url( $hospital_link ) : '';
		$hospital_name	= doccure_full_name($author_id);
		$hospital_name	= !empty( $hospital_name ) ? esc_html( $hospital_name ) : '';
		$author_id		= doccure_get_linked_profile_id( $author_id,'post');
		
		$total_price 		= get_post_meta( $post_id ,'hospital_id', true);
		$consultant_fee 		= get_post_meta( $post_id ,'hospital_id', true);
		$contents 		= get_post_meta( $post_id ,'hospital_id', true);
		

 				
		$am_booking_new = get_post_meta($post_id, '_am_booking', true); 
				$post_meta = maybe_unserialize($am_booking_new);
				$consultant_fee = $post_meta['_consultant_fee'];
				$total_price = $post_meta['_price'];
				 

		if( $current_user->ID != intval( $author_id ) ) {
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__( 'You have no permission for this change.', 'doccure' );
			wp_send_json( $json );
		}
		
		if( !empty($post_id) && !empty( $status ) ){
		   $post_data 		= array(
								  'ID'           => $post_id,
								  'post_status'  => $status
							  );

			wp_update_post( $post_data );
			
			if( !empty( $post_id ) && !empty( $status ) ) {
				
				$doctor_info				= get_userdata($doctor_id);
				$emailData['email']			= $doctor_info->user_email;
				$emailData['doctor_name']	= $doctor_name;
				$emailData['hospital_link']	= $hospital_link;
				$emailData['hospital_name']	= $hospital_name;

				$emailData['price']				= doccure_price_format($total_price,'return');
				$emailData['consultant_fee']	= doccure_price_format($consultant_fee,'return');
				$emailData['description']		= "TEST1";
				
				if (class_exists('doccure_Email_helper')) {
					if (class_exists('doccureHospitalTeamNotify')) {
						$email_helper = new doccureHospitalTeamNotify();
						if( $status === 'publish' ){
							$email_helper->send_approved_email($emailData);
						} else if( $status === 'trash' ){
							$email_helper->send_cancelled_email($emailData);
						}
					}
				}
				
				$json['url'] 		= doccure_Profile_Menu::doccure_profile_menu_link('team', $current_user->ID,'manage',true);
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('you have successfully update this doctor status.', 'doccure');
				wp_send_json( $json );
				
			}
			
		} else {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Oops! something is going wrong.', 'doccure');
			wp_send_json( $json );
		}
	}

	add_action( 'wp_ajax_doccure_change_post_status', 'doccure_change_post_status' );
	add_action( 'wp_ajax_nopriv_doccure_change_post_status', 'doccure_change_post_status' );
}

/**
 * Get Booking data
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_get_booking_data' ) ) {

	function doccure_get_booking_data() {
		$post_id 			= !empty( $_POST['id'] ) ? intval( $_POST['id'] ) : '';
		$doctor_id 			= !empty( $_POST['doctor_id'] ) ? intval( $_POST['doctor_id'] ) : '';
		$json 				= array();
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		if(!empty( $post_id ) ){
			
			$json['consultant_fee'] = '';
			$am_consultant_fee		= get_post_meta( $post_id ,'_consultant_fee',true);
			$consultant_fee			= !empty( $am_consultant_fee ) ? doccure_price_format( $am_consultant_fee,'return') : doccure_price_format( 0,'return');
			
			if( isset( $consultant_fee ) ) {
				$json['consultant_fee'] = '<ul class="at-taxesfees"><li id="consultant_fee"><span>'.esc_html__('Consultation fee','doccure').'<em>'.$consultant_fee.'<span class=" dc-consultant-fee dc-service-price" data-price="'.$am_consultant_fee.'" data-tipso="Verified user"></span></em></span></li><li class="at-toteltextfee"><span>'.esc_html__('Total','doccure').'<em id="dc-total-price" data-price="'.$am_consultant_fee.'">'.$consultant_fee.'</em></span></li></ul>';
			}
			
			$service_html			= '';
			$day					= strtolower(date('D'));
			$date					= date('Y-m-d');
			$reponse_slots			= doccure_get_time_slots_spaces($post_id,$day,$date);
			$norecourd_found		= apply_filters('doccure_empty_records_html','dc-empty-articls dc-emptyholder-sm',esc_html__( 'There are no any sloat available.', 'doccure' ),true);
			$reponse_slots			= !empty($reponse_slots) ? $reponse_slots : $norecourd_found;
			$json['time_slots']		= $reponse_slots;

			$service_html			= apply_filters('doccure_get_group_services_with_speciality',$post_id,'','return','location',$doctor_id);

			$json['type'] 				= 'success';
			$json['booking_services'] 	= $service_html;
			wp_send_json( $json );
		}else{
			$json['type'] 				= 'error';
			$json['message'] 			= esc_html__('You need to select hospital.', 'doccure');
			wp_send_json( $json );
		}
	}

	add_action( 'wp_ajax_doccure_get_booking_data', 'doccure_get_booking_data' );
	add_action( 'wp_ajax_nopriv_doccure_get_booking_data', 'doccure_get_booking_data' );
}

/**
 * Get Booking data
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_get_slots' ) ) {

	function doccure_get_slots() {
		$_date 					= !empty( $_POST['_date'] ) ? ( $_POST['_date'] ) : '';
		$_hospital_id 			= !empty( $_POST['_hospital_id'] ) ? ( $_POST['_hospital_id'] ) : '';
		$json 		= array();
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		if(!empty( $_hospital_id ) ){
			$json['type'] 		= 'success';
			$day				= strtolower(date('D',strtotime($_date)));
			$reponse_slots		= doccure_get_time_slots_spaces($_hospital_id,$day,$_date);

			$norecourd_found		= apply_filters('doccure_empty_records_html','dc-empty-articls dc-emptyholder-sm',esc_html__( 'There are no any sloat available.', 'doccure' ),true);
			$reponse_slots			= !empty($reponse_slots) ? $reponse_slots : $norecourd_found;
			$json['time_slots']		= $reponse_slots;
			wp_send_json( $json );
		}else{
			$json['type'] 				= 'error';
			$json['message'] 			= esc_html__('You need to select hospital.', 'doccure');
			wp_send_json( $json );
		}
	}

	add_action( 'wp_ajax_doccure_get_slots', 'doccure_get_slots' );
	add_action( 'wp_ajax_nopriv_doccure_get_slots', 'doccure_get_slots' );
}


if ( !function_exists( 'doccure_booking_doctor_reschedule' ) ) {

	function doccure_booking_doctor_reschedule() {
		global $doccure_options,$current_user,$wpdb;
		$user_id			= !empty( $_POST['user_id'] ) ? sanitize_text_field( $_POST['user_id'] ) : '';
		$order_post_id			= !empty( $_POST['order_post_id'] ) ? sanitize_text_field( $_POST['order_post_id'] ) : '';
		$post_user_id		= doccure_get_linked_profile_id( $current_user->ID );
		
		$is_verified 		= get_post_meta($post_user_id, '_is_verified', true);
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in
		
		if( empty( $is_verified ) || $is_verified === 'no' ){
			$json['type'] = 'error';
			$json['message'] = esc_html__('You are not verified user, so you can\'t create a appointment', 'doccure');
			wp_send_json( $json );
		}
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		$json 				= array();
 		$post_meta			= array();
		$date_formate		= get_option('date_format');
		$time_format 		= get_option('time_format');
 		$booking_hospitals	= !empty( $_POST['booking_hospitals'] ) ? sanitize_text_field( $_POST['booking_hospitals'] ) : '';
		$doctor_id			= !empty( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : '';
		$appointment_date	= !empty( $_POST['appointment_date'] ) ? sanitize_text_field( $_POST['appointment_date'] ) : '';
    	$booking_slot 		= !empty( $_POST['booking_slot'] ) ? sanitize_text_field( $_POST['booking_slot'] ) : '';
 		
		$email				= !empty( $_POST['email'] ) ? is_email( $_POST['email'] ) : '';
		$phone				= !empty( $_POST['phone'] ) ? ( $_POST['phone'] ) : '';
		$first_name			= !empty( $_POST['first_name'] ) ? sanitize_text_field( $_POST['first_name'] ) : '';
		$last_name			= !empty( $_POST['last_name'] ) ? sanitize_text_field( $_POST['last_name'] ) : '';
		$total_price		= !empty( $_POST['total_price'] ) ? sanitize_text_field( $_POST['total_price'] ) : 0;
		$doctor_id			= doccure_get_linked_profile_id($current_user->ID);
		$rand_val			= rand(1, 9999);

		$am_specialities 		= doccure_get_post_meta( $doctor_id,'am_specialities');
		$am_specialities		= !empty( $am_specialities ) ? $am_specialities : array();

  

		
		$update_services	= array();
		if( !empty($booking_service) ){
			
			foreach($booking_service as $key => $service_single){
				if( !empty( $service_single ) ){
					foreach( $service_single as $service ){
						$price		= !empty( $am_specialities[$key][$service]['price'] ) ?  $am_specialities[$key][$service]['price'] : 0;
						$price		= !empty( $price ) ? $price : 0;
						$update_services[$key][$service]	= $price;
					}
				}
			}
		}

		

		
		if( !empty( $booking_slot ) && !empty( $appointment_date )) {
 			 
 			$booking_id =  $order_post_id;
			
			if(!empty($booking_id)){
				$am_booking_new = get_post_meta($booking_id, '_am_booking', true); 
				
		 
				$post_meta = maybe_unserialize($am_booking_new);

				$am_consultant_fee	= get_post_meta( $booking_hospitals ,'_consultant_fee',true);
  				$price								= !empty( $am_consultant_fee ) ? $am_consultant_fee : 0;
 				$post_meta['_consultant_fee']		= $price;
				$post_meta['_price']				= $total_price;
				$post_meta['_appointment_date']		= $appointment_date;
				$post_meta['_slots']				= $booking_slot;
				$post_meta['_hospital_id']			= $booking_hospitals;
				
  
				
 $order_id = get_post_meta($booking_id, '_order_id', true); 
 if( $order_id) {
$item_meta_key = 'cus_woo_product_data';  

 $new_appointment_date = $appointment_date;  
$new_slots = $booking_slot;  

 $order = wc_get_order($order_id);

 if ($order) {
     $items = $order->get_items();
    
     $item = reset($items); 

    if ($item) {
         $meta_value = $item->get_meta($item_meta_key, true);
        $cus_woo_product_data = maybe_unserialize($meta_value);

         $cus_woo_product_data['appointment_date'] = $new_appointment_date;
        $cus_woo_product_data['slots'] = $new_slots;

         $updated_meta_value = maybe_serialize($cus_woo_product_data);

         $item->update_meta_data($item_meta_key, $updated_meta_value);
        $item->save();  
       }  
}  
 
}
 
  				update_post_meta($booking_id,'_appointment_date',$post_meta['_appointment_date'] );
 				update_post_meta($booking_id,'_price',$total_price );
 				update_post_meta($booking_id,'_booking_slot',$post_meta['_slots'] );
				 $post_meta_new = maybe_serialize($post_meta);
				update_post_meta($booking_id,'_am_booking',$post_meta_new );


 $time = !empty($post_meta['_slots']) ? explode('-',$post_meta['_slots']) : array();
 $start_time = !empty($time[0]) ? date($time_format, strtotime('2016-01-01' .$time[0])) : '';
 $end_time = !empty($time[1]) ? date($time_format, strtotime('2016-01-01' .$time[1])) : '';

$appointment_time = $start_time.' '.esc_html__('to','doccure').' '.$end_time;
$tprice = doccure_price_format($post_meta['_price'],'return');
$consultant_fee = doccure_price_format($post_meta['_consultant_fee'],'return');

 global $doccure_options;
 


 
$role = $current_user->roles[0];

$user_id = get_post_meta($booking_id, '_patient_id', true); 
$user_info = get_userdata($user_id);
$user_name	= $user_info->display_name;


$doctor_id = get_post_meta($booking_id, '_doctor_id', true);  
$post = get_post($doctor_id);
$author_id =  $post->post_author;
$doctor_info = get_userdata($author_id);
$doctor_name	= $doctor_info->display_name;



if($role=='doctors') { 
	$order_id = get_post_meta($booking_id, '_order_id', true); 
	if($order_id) { 
		$bk_email = get_post_meta($booking_id,'bk_email', true);
		$user_name	= get_post_meta($booking_id,'bk_username', true);
		
	} else {
		$bk_email = $user_info->user_email;
		$user_name	= $user_info->display_name;
	}

 
 $is_enabled = $doccure_options['new_order_email_enabled_redoc'];
$subject = $doccure_options['new_order_email_subject_redoc'];
$email_content = $doccure_options['new_order_email_content_redoc'];

} else {

	$order_id = get_post_meta($booking_id, '_order_id', true); 
	if($order_id) { 
 		$user_name	= get_post_meta($booking_id,'bk_username', true);
		
	} else {
 		$user_name	= $user_info->display_name;
	}
	

$bk_email = $doctor_info->user_email;
$is_enabled = $doccure_options['new_order_email_enabled_repat'];
$subject = $doccure_options['new_order_email_subject_repat'];
$email_content = $doccure_options['new_order_email_content_repat'];
}


$from_email = $doccure_options['emails_from_email'] ?? get_bloginfo('admin_email');
$email_logo = $doccure_options['email_logo'];
$email_logo_url = $email_logo['url'];

// Check if the notification is enabled
if (!$is_enabled) {
	return;
}
  $product_details = ''; // Variable to hold all product details if multiple products are purchased

 // Build product details for each item in HTML table format with titles at the top
$product_details .= '<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">';
$product_details .= '<tr style="background-color: #f2f2f2;">';
$product_details .= '<th>Patient Name</th>';
$product_details .= '<th>Doctor Name</th>';
$product_details .= '<th>Appointment Date</th>';
$product_details .= '<th>Appointment Time</th>';
$product_details .= '<th>Consultant Fee</th>';
$product_details .= '<th>Total Price</th>';
//$product_details .= '<th>Description</th>';
$product_details .= '</tr>';
 // Loop through each order item to get metadata and product details
 $product_details .= '<tr>';
$product_details .= '<td >' . esc_html($user_name) . '</td>';
$product_details .= '<td >' . esc_html($doctor_name) . '</td>';
$product_details .= '<td >' . esc_html($post_meta['_appointment_date']) . '</td>';
$product_details .= '<td >' . esc_html($appointment_time) . '</td>';


$product_details .= '<td >' . esc_html($consultant_fee) . '</td>';
$product_details .= '<td >' . esc_html($tprice) . '</td>';
//$product_details .= '<td >' . esc_html($post_meta['_slots']) . '</td>';
 $product_details .= '</tr>';


$product_details .= '</table>';

// Replace placeholders in the email content
$replacements = array(
	'{patient_name}'    => esc_html($user_name),
	'{doctor_name}'    => esc_html($doctor_name),
	'{appointment_date}'    => esc_html($post_meta['_appointment_date']),
	'{appointment_time}'    => esc_html($appointment_time),
	'{consultant_fee}'    => esc_html($consultant_fee),
	'{total_price}'    => esc_html($tprice),
	//'{description}'    => esc_html($post_meta['_slots']),
	'{booking_details}'  => $product_details // Insert the product details into the content
);
$email_content = strtr($email_content, $replacements);

// Email headers for HTML format
$headers = [
	'Content-Type: text/html; charset=UTF-8',
	'From: ' . get_bloginfo('name') . ' <' . $from_email . '>'
];

// Build the HTML message
$message = '<!DOCTYPE html>
			<html>
			<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<style>
					body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
					.container { max-width: 100%; margin: 0 auto; padding: 20px; background-color: #f9f9f9; border: 1px solid #ddd; }
					.header { text-align: center; margin-bottom: 30px; }
					.content { background: #fff; padding: 20px; border-radius: 5px; }
					.content ul{ padding-left: 0px; }
					.footer { margin-top: 30px; text-align: center; color: #999; font-size: 12px; }
					.content table th {padding: 8px; border: 1px solid #ddd;}
					.content  table td{padding: 8px; border: 1px solid #ddd;}
				</style>
			</head>
			<body>
				<div class="container">
					<div class="header">';
if (!empty($email_logo_url)) {
	$message .= '<img src="' . esc_url($email_logo_url) . '" alt="Logo">';
}
$message .= '</div>
					<div class="content">'
						. $email_content . 
					'</div>
					<div class="footer">
						&copy; ' . date("Y") . ' ' . get_bloginfo('name') . '. All rights reserved.
					</div>
				</div>
			</body>
			</html>';
 // Send the email to the customer
wp_mail($bk_email, $subject, $message, $headers);

 }
			
			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__( 'Your booking has been successfully submitted.', 'doccure' );
			wp_send_json( $json );





		}
		
		
	}

	add_action( 'wp_ajax_doccure_booking_doctor_reschedule', 'doccure_booking_doctor_reschedule' );
	add_action( 'wp_ajax_nopriv_doccure_booking_doctor_reschedule', 'doccure_booking_doctor_reschedule' );
}

/**
 * Booking step 1
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_booking_doctor' ) ) {

	function doccure_booking_doctor() {
		global $doccure_options,$current_user,$wpdb;
		$user_id			= !empty( $_POST['user_id'] ) ? sanitize_text_field( $_POST['user_id'] ) : '';
		$post_user_id		= doccure_get_linked_profile_id( $current_user->ID );
		
		$is_verified 		= get_post_meta($post_user_id, '_is_verified', true);
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in
		
		if( empty( $is_verified ) || $is_verified === 'no' ){
			$json['type'] = 'error';
			$json['message'] = esc_html__('You are not verified user, so you can\'t create a appointment', 'doccure');
			wp_send_json( $json );
		}
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		$json 				= array();
		$required			= array();
		$post_meta			= array();
		$date_formate		= get_option('date_format');
		$time_format 		= get_option('time_format');

		$required	= array(
			'booking_hospitals' => esc_html__( 'Please select the hospital', 'doccure' ),
			'booking_slot' 		=> esc_html__( 'Please select the time slot', 'doccure' ),
			'appointment_date' 	=> esc_html__( 'Please select appointment date', 'doccure' ),
			'email' 			=> 	esc_html__( 'Email is required field', 'doccure' )
		);

		$required	= apply_filters( 'doccure_doccure_booking_doctor_validation', $required );

		if(empty($_POST['user_id'])){
			$required['email']		= esc_html__( 'Email is required field', 'doccure' );
			$required['first_name']	= esc_html__( 'First name is required field', 'doccure' );
			$required['last_name']	= esc_html__( 'Last name is required field', 'doccure' );
		}

		foreach($required as $key => $req){
			if( empty($_POST[$key]) ) {
				$json['type'] 		= 'error';
				$json['message'] 	= $req;
				wp_send_json( $json );
			}
		}

		$booking_hospitals	= !empty( $_POST['booking_hospitals'] ) ? sanitize_text_field( $_POST['booking_hospitals'] ) : '';
		$doctor_id			= !empty( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : '';
		$appointment_date	= !empty( $_POST['appointment_date'] ) ? sanitize_text_field( $_POST['appointment_date'] ) : '';
		$myself				= !empty( $_POST['myself'] ) ? sanitize_text_field( $_POST['myself'] ) : '';
		$other_name			= !empty( $_POST['other_name'] ) ? sanitize_text_field( $_POST['other_name'] ) : '';
		$relation			= !empty( $_POST['relation'] ) ? sanitize_text_field( $_POST['relation'] ) : '';
		$booking_service 	= !empty( $_POST['service'] ) ? ( $_POST['service'] ) : array();
		$booking_content 	= !empty( $_POST['booking_content'] ) ? sanitize_textarea_field( $_POST['booking_content'] ) : '';
		$booking_slot 		= !empty( $_POST['booking_slot'] ) ? sanitize_text_field( $_POST['booking_slot'] ) : '';
		$create_user 		= !empty( $_POST['create_user'] ) ? sanitize_text_field( $_POST['create_user'] ) : '';
		
		$email				= !empty( $_POST['email'] ) ? is_email( $_POST['email'] ) : '';
		$phone				= !empty( $_POST['phone'] ) ? ( $_POST['phone'] ) : '';
		$first_name			= !empty( $_POST['first_name'] ) ? sanitize_text_field( $_POST['first_name'] ) : '';
		$last_name			= !empty( $_POST['last_name'] ) ? sanitize_text_field( $_POST['last_name'] ) : '';
		$total_price		= !empty( $_POST['total_price'] ) ? sanitize_text_field( $_POST['total_price'] ) : 0;
		$doctor_id			= doccure_get_linked_profile_id($current_user->ID);
		$rand_val			= rand(1, 9999);

		$am_specialities 		= doccure_get_post_meta( $doctor_id,'am_specialities');
		$am_specialities		= !empty( $am_specialities ) ? $am_specialities : array();

		$update_services	= array();
		if( !empty($booking_service) ){
			
			foreach($booking_service as $key => $service_single){
				if( !empty( $service_single ) ){
					foreach( $service_single as $service ){
						$price		= !empty( $am_specialities[$key][$service]['price'] ) ?  $am_specialities[$key][$service]['price'] : 0;
						$price		= !empty( $price ) ? $price : 0;
						$update_services[$key][$service]	= $price;
					}
				}
			}
		}

		
		if( !empty( $booking_hospitals ) && !empty( $booking_slot ) && !empty( $appointment_date )) {
			
			if(!empty($user_id)){
				$auther_id	= $user_id;
			} else {
				$auther_id		= 1;
				if(!empty($create_user)){
					$user_type		 	= 'regular_users';
					$random_password 	= rand(900,10000);
					$display_name		= explode('@',$email);
					$display_name		= !empty($display_name[0]) ? $display_name[0] : $first_name;
					$user_nicename   	= sanitize_title( $display_name );
					$userdata = array(
						'user_login'  		=> $display_name,
						'user_pass'    		=> $random_password,
						'user_email'   		=> $email,  
						'user_nicename'   	=> $user_nicename,  
						'display_name'		=> $display_name
					);
					
					$user_identity 	 = wp_insert_user( $userdata );
					
					if ( is_wp_error( $user_identity ) ) {
						$json['type'] 		= "error";
						$json['message'] 	= esc_html__("User already exists. Please try another one.", 'doccure');
						wp_send_json($json);
					} else {
						wp_update_user( array('ID' => esc_sql( $user_identity ), 'role' => esc_sql( $user_type ), 'user_status' => 1 ) );

						$wpdb->update(
								$wpdb->prefix . 'users', array('user_status' => 1), array('ID' => esc_sql($user_identity))
						);
						
						$auther_id		= $user_identity;
						update_user_meta( $user_identity, 'first_name', $first_name );
						update_user_meta( $user_identity, 'last_name', $last_name ); 
						update_user_meta( $user_identity, 'phone', $phone ); 
						update_user_meta( $user_identity, '_is_verified', 'yes' );
						//update_user_meta( $user_identity, 'show_admin_bar_front', false);
						
						//Create Post
						$user_post = array(
							'post_title'    => wp_strip_all_tags( $display_name ),
							'post_status'   => 'publish',
							'post_author'   => $user_identity,
							'post_type'     => $user_type,
						);
			
						$post_id    = wp_insert_post( $user_post );
						
						if( !is_wp_error( $post_id ) ) {
							
							$profile_data	= array();
							$profile_data['am_first_name']	= $first_name;
							$profile_data['am_last_name']	= $last_name;
							update_post_meta($post_id, 'am_' . $user_type . '_data', $profile_data);
							
							//Update user linked profile
							update_user_meta( $user_identity, '_linked_profile', $post_id );
							update_post_meta($post_id, '_is_verified', 'yes');					
							update_post_meta($post_id, '_linked_profile', $user_identity);
							update_post_meta( $post_id, 'is_featured', 0 );

							if( function_exists('doccure_full_name') ) {
								$name	= doccure_full_name($post_id);
							} else {
								$name	= $first_name;
							}

							$user_name	= $name;
							//Send email to users
							if (class_exists('doccure_Email_helper')) {
								$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
								$emailData = array();
								
								$emailData['name'] 							= $name;
								$emailData['password'] 						= $random_password;
								$emailData['email'] 						= $email;
								
								$emailData['site'] 							= $blogname;
								//Send code
								if (class_exists('doccureRegisterNotify')) {
									$email_helper = new doccureRegisterNotify();
									if( !empty($user_type) && $user_type === 'regular_users' ){
										$email_helper->send_regular_user_email($emailData);
									}
								}
								
								//Send admin email
								if (class_exists('doccureRegisterNotify')) {
									$email_helper = new doccureRegisterNotify();
									$email_helper->send_admin_email($emailData);
								}
							}
						}
					}
				}
			}

			$post_title		= !empty( $doccure_options['appointment_prefix'] ) ? $doccure_options['appointment_prefix'] : esc_html__('APP#','doccure');
			$contents		= !empty( $booking_content ) ? $booking_content : '';
			$booking_post 	= array(
								'post_title'    => wp_strip_all_tags( $post_title ).'-'.$rand_val,
								'post_status'   => 'publish',
								'post_author'   => intval($auther_id),
								'post_type'     => 'booking',
								'post_content'	=> $contents
							);
			
			$booking_id = wp_insert_post( $booking_post );
 			if(!empty($booking_id)){
				$post_meta['_with_patient']['relation']			= !empty( $relation ) ? $relation : '';
				$post_meta['_with_patient']['other_name']		= !empty( $other_name ) ? $other_name : '';

				

				if(empty($user_id)){
					
					update_post_meta($booking_id,'bk_phone',$phone );
					update_post_meta($booking_id,'bk_email',$email );
					update_post_meta($booking_id,'bk_username',$first_name.' '.$last_name );
					if(!empty($create_user)){
						update_post_meta($booking_id,'_user_type','regular_users' );
					} else {
						update_post_meta($booking_id,'_user_type','guest' );
						$user_name									= !empty($first_name) ? $first_name.' '.$last_name : '';
						$post_meta['_user_details']['user_type']	= 'guest';
						$post_meta['_user_details']['full_name']	= $user_name;
						$post_meta['_user_details']['first_name']	= $first_name;
						$post_meta['_user_details']['last_name']	= $last_name;
						$post_meta['_user_details']['email']		= $email;
					}
				} else {
					$patient_profile_id	= doccure_get_linked_profile_id($user_id);
					$name			= doccure_full_name($patient_profile_id);
					$user_details	= get_userdata($user_id);
					$phone			= get_user_meta( $user_id, 'phone', true );
					update_post_meta($booking_id,'_user_type','regular_users' );
					
					update_post_meta($booking_id,'bk_phone',$phone );
					update_post_meta($booking_id,'bk_email',$user_details->user_email );
					update_post_meta($booking_id,'bk_username',$name );
				}

				$am_consultant_fee	= get_post_meta( $booking_hospitals ,'_consultant_fee',true);
				

				$price								= !empty( $am_consultant_fee ) ? $am_consultant_fee : 0;
				
				$post_meta['_services']				= $update_services;
				$post_meta['_consultant_fee']		= $price;
				$post_meta['_price']				= $total_price;
				$post_meta['_appointment_date']		= $appointment_date;
				$post_meta['_slots']				= $booking_slot;
				$post_meta['_hospital_id']			= $booking_hospitals;
				
				//changes
				$hospital_id				= $post_meta['_hospital_id'];
				
				update_post_meta($booking_id,'_order_id',$rand_val);

				update_post_meta($booking_id,'_appointment_date',$post_meta['_appointment_date'] );
				update_post_meta($booking_id,'_booking_type','doctor' );
				
				update_post_meta($booking_id,'_price',$total_price );
				update_post_meta($booking_id,'_booking_service',$post_meta['_services'] );
				update_post_meta($booking_id,'_booking_slot',$post_meta['_slots'] );
				update_post_meta($booking_id,'_booking_hospitals',$post_meta['_hospital_id'] );
				update_post_meta($booking_id,'_hospital_id',$hospital_id );
				update_post_meta($booking_id,'_doctor_id',$doctor_id );
				update_post_meta($booking_id,'_patient_id',$author_id );
				update_post_meta($booking_id,'_product_rand_offline',$rand_val );

				update_post_meta($booking_id,'_am_booking',$post_meta );

				if( function_exists('doccure_send_booking_message') ){
					doccure_send_booking_message($booking_id);
				}
				
				if (class_exists('doccure_Email_helper')) {
					$emailData	= array();
					$emailData['user_name']		= $user_name;
					$time						= !empty($post_meta['_slots']) ? explode('-',$post_meta['_slots']) : array();
					$start_time					= !empty($time[0]) ? date($time_format, strtotime('2016-01-01' .$time[0])) : '';
					$end_time					= !empty($time[1]) ? date($time_format, strtotime('2016-01-01' .$time[1])) : '';
					$hospital_id				= get_post_meta($post_meta['_hospital_id'],'hospital_id',true);

					$emailData['doctor_name']	= doccure_full_name($doctor_id);
					$emailData['doctor_link']	= get_the_permalink($doctor_id);
					$emailData['hospital_name']	= doccure_full_name($hospital_id);
					$emailData['hospital_link']	= get_the_permalink($hospital_id);
					
					$emailData['appointment_date']	= !empty($post_meta['_appointment_date']) ? date($date_formate,strtotime($post_meta['_appointment_date'])) : '';
					$emailData['appointment_time']	= $start_time.' '.esc_html__('to','doccure').' '.$end_time;
					$emailData['price']				= doccure_price_format($total_price,'return');
					$emailData['consultant_fee']	= doccure_price_format($post_meta['_consultant_fee'],'return');
					$emailData['description']		= $contents;

					if (class_exists('doccureBookingNotify')) {
						$email_helper				= new doccureBookingNotify();
						$emailData['email']			= $email;
						$email_helper->send_approved_email($emailData);
					}
				}
			}
			
			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__( 'Your booking has been successfully submitted.', 'doccure' );
			wp_send_json( $json );
		}
		
		
	}

	add_action( 'wp_ajax_doccure_booking_doctor', 'doccure_booking_doctor' );
	add_action( 'wp_ajax_nopriv_doccure_booking_doctor', 'doccure_booking_doctor' );
}

/**
 * Booking step 1
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_booking_step1' ) ) {

	function doccure_booking_step1() {
		global $doccure_options;
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		if(!isset($_SESSION)){ session_start(array('user_data'));}
		
		$booking_verification	= !empty( $doccure_options['booking_verification'] ) ? $doccure_options['booking_verification'] : '';
		$json 				= array();
		$booking_hospitals	= !empty( $_POST['booking_hospitals'] ) ? sanitize_text_field( $_POST['booking_hospitals'] ) : '';
		$doctor_id			= !empty( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : '';
		$appointment_date	= !empty( $_POST['appointment_date'] ) ? sanitize_text_field( $_POST['appointment_date'] ) : '';
		$myself				= !empty( $_POST['myself'] ) ? sanitize_text_field( $_POST['myself'] ) : '';
		$other_name			= !empty( $_POST['other_name'] ) ? sanitize_text_field( $_POST['other_name'] ) : '';
		$relation			= !empty( $_POST['relation'] ) ? sanitize_text_field( $_POST['relation'] ) : '';
		$booking_service 	= !empty( $_POST['service'] ) ? ( $_POST['service'] ) : array();
		$booking_content 	= !empty( $_POST['booking_content'] ) ? sanitize_textarea_field( $_POST['booking_content'] ) : '';
		$booking_slot 		= !empty( $_POST['booking_slot'] ) ? sanitize_text_field( $_POST['booking_slot'] ) : '';

		$bk_email			= !empty( $_POST['bk_email'] ) ? sanitize_text_field( $_POST['bk_email'] ) : '';
		$bk_phone			= !empty( $_POST['bk_phone'] ) ? sanitize_text_field( $_POST['bk_phone'] ) : '';
		
		if( empty( $other_name ) ){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__( 'Patient name is required', 'doccure' );
			wp_send_json( $json );
		}
		
		if( empty( $bk_email ) ){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__( 'Email address is required', 'doccure' );
			wp_send_json( $json );
		} elseif( !is_email( $bk_email ) ){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__( 'Please add a valid email address', 'doccure' );
			wp_send_json( $json );
		}
		
		if( empty( $bk_phone ) ){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__( 'Phone number is required', 'doccure' );
			wp_send_json( $json );
		}else if(!filter_var($bk_phone, FILTER_SANITIZE_NUMBER_INT)){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__( 'Please add valid phone number', 'doccure' );
			wp_send_json( $json );
		}
		
		if( empty( $appointment_date ) ){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__( 'Please select the appointment date', 'doccure' );
			wp_send_json( $json );
		}
		
		if( empty( $booking_hospitals ) ){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__( 'Please select the hospital', 'doccure' );
			wp_send_json( $json );
		}
		
		if( empty( $booking_slot ) ){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__( 'Please select the time slot', 'doccure' );
			wp_send_json( $json );
		}
		
		if( empty( $appointment_date ) ){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__( 'Please select the appointment date', 'doccure' );
			wp_send_json( $json );
		}
		
		if( !empty( $booking_hospitals ) && !empty( $booking_slot ) && !empty( $appointment_date )) {
			$user_data										= array();
			$user_data['booking']['post_title']				= get_the_title( $booking_hospitals );
			$user_data['booking']['post_content']			= $booking_content;
			$user_data['booking']['_booking_service']		= $booking_service;
			$user_data['booking']['_booking_slot']			= $booking_slot;
			$user_data['booking']['_booking_hospitals']		= $booking_hospitals;
			$user_data['booking']['_appointment_date']		= $appointment_date;
			$user_data['booking']['_doctor_id']				= $doctor_id;
			$user_data['booking']['_myself']				= $myself;
			
			$user_data['booking']['_relation']				= $relation;
			$user_data['booking']['bk_email']				= $bk_email;
			$user_data['booking']['bk_phone']				= $bk_phone;
			$user_data['booking']['other_name']				= $other_name;

			$_SESSION['user_data'] = $user_data;
			
			if( empty($booking_verification) ){
				doccure_booking_complete();
			}

			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__( 'Your booking is successfully submited.', 'doccure' );
			wp_send_json( $json );


			
		}
		
		
	}

	add_action( 'wp_ajax_doccure_booking_step1', 'doccure_booking_step1' );
	add_action( 'wp_ajax_nopriv_doccure_booking_step1', 'doccure_booking_step1' );
}

/**
 * Booking Resend Code
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_booking_resend_code' ) ) {

	function doccure_booking_resend_code() {
		global $current_user;

		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		if(!isset($_SESSION)){ session_start(array('user_data'));}
		
		$json	= array();

		if( $current_user->ID ) {
			$key_hash 		= rand( 1000, 9999 );
			$json['email']								= $current_user->user_email;
			$json['type'] 								= 'success';
			$json['message'] 							= esc_html__( 'Verification code has sent on your email', 'doccure' );
			$user_data									= isset($_SESSION['user_data']) ? $_SESSION['user_data'] : array();
			$user_data['booking']['email']				= $current_user->user_email;
			$user_data['booking']['user_type']			= 'registered';
			$user_data['booking']['authentication_code']	= $key_hash;
			
			$_SESSION['user_data'] = $user_data;

			//update booking
			update_user_meta($current_user->ID,'booking_auth',$key_hash);
			
			$profile_id		= doccure_get_linked_profile_id( $current_user->ID );
			$name			= doccure_full_name( $profile_id );
			$name			= !empty( $name ) ? esc_html( $name ) : '';
			
			//Send verification code
			if (class_exists('doccure_Email_helper')) {
				if ( class_exists('doccureBookingNotify') ) {
					$email_helper 					= new doccureBookingNotify();
					$emailData['name'] 				= $name;
					$emailData['email']				= $current_user->user_email;
					$emailData['verification_code'] = $key_hash;
					$email_helper->send_verification($emailData);
				} 
			}
			
			wp_send_json( $json );
		}

	}

	add_action( 'wp_ajax_doccure_booking_resend_code', 'doccure_booking_resend_code' );
	add_action( 'wp_ajax_nopriv_doccure_booking_resend_code', 'doccure_booking_resend_code' );
}
/**
 * Booking step 2
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_booking_step2' ) ) {

	function doccure_booking_step2() {
		global $current_user;
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		if(!isset($_SESSION)){ session_start(array('user_data'));}
		
		$json 			= array();
		$key_hash 		= rand( 1000, 9999 );
		$emailData 		= array();
		$validations	= array();

		if( $current_user->ID ) {
			$password			= !empty( $_POST['password'] ) ? ( $_POST['password'] ) : '';
			$retype_password	= !empty( $_POST['retype_password'] ) ? ( $_POST['retype_password'] ) : '';

			$validations		= array(
				'password'			=> esc_html__( 'Password is required.', 'doccure' ),
				'retype_password'	=> esc_html__( 'Retype password is required.', 'doccure' )
			);
			
			$validations	= apply_filters( 'doccure_doccure_booking_step2_validation', $validations );

			foreach( $validations as $key => $val ){
				if( empty( $_POST[$key] ) ){
					$json['type'] 		= 'error';
					$json['message'] 	= $val;
					wp_send_json( $json );
				}
			}

			if(  $password != $retype_password ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__( 'Password does not match.', 'doccure' );
				wp_send_json( $json );
			}

			$user_data										= isset($_SESSION['user_data']) ? $_SESSION['user_data'] : array();

			if( !empty( $password ) && !empty( $retype_password ) && $password === $retype_password ) {
				if( wp_check_password( $password, $current_user->user_pass, $current_user->ID ) ) {
					
					$json['email']								= $current_user->user_email;
					$json['type'] 								= 'success';
					$json['message'] 							= esc_html__( 'Your informations are correct.', 'doccure' );

					$user_data['booking']['email']					= $current_user->user_email;
					$user_data['booking']['user_type']				= 'registered';
					$user_data['booking']['authentication_code']	= $key_hash;
					
					$_SESSION['user_data'] = $user_data;

					//update booking
					update_user_meta($current_user->ID,'booking_auth',$key_hash);
					
					$profile_id		= doccure_get_linked_profile_id( $current_user->ID );
					$name			= doccure_full_name( $profile_id );
					$name			= !empty( $name ) ? esc_html( $name ) : '';
					
					//Send verification code
					if (class_exists('doccure_Email_helper')) {
						if ( class_exists('doccureBookingNotify') ) {
							$email_helper 					= new doccureBookingNotify();
							$emailData['name'] 				= $name;
							$emailData['email']				= $current_user->user_email;
							$emailData['verification_code'] = $key_hash;
							$email_helper->send_verification($emailData);
						} 
					}
					
					wp_send_json( $json );
				} else {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__( 'Password is invalid.', 'doccure' );
					wp_send_json( $json );
				}
			}
		} else {
			$full_name			= !empty( $_POST['full_name'] ) ? ( $_POST['full_name'] ) : '';
			$phone_number		= !empty( $_POST['phone_number'] ) ? ( $_POST['phone_number'] ) : '';
			$email				= !empty( $_POST['email'] ) ? ( $_POST['email'] ) : '';

			if( empty( $full_name ) ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__( 'Name is required.', 'doccure' );
				wp_send_json( $json );
			}
			
			if( empty( $email ) ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__( 'Email is required.', 'doccure' );
				wp_send_json( $json );
			}

			if( empty( $phone_number ) ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__( 'Phone number is required.', 'doccure' );
				wp_send_json( $json );
			}	
			
			if( !empty( $email ) && !is_email($email) ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__( 'Email is invalid.', 'doccure' );
				wp_send_json( $json );
			}
			
			if( !empty( $email ) && !empty( $full_name ) && is_email($email) && !empty( $phone_number )) {
				
				$user_data['booking']['email']					= $email;
				$user_data['booking']['user_type']				= 'guest';
				$user_data['booking']['full_name']				= $full_name;
				$user_data['booking']['phone_number']			= $phone_number;
				$user_data['booking']['authentication_code']	= $key_hash;
				$_SESSION['user_data'] = $user_data;

				//update booking
				update_user_meta($current_user->ID,'booking_auth',$key_hash);
				
				$json['email']		= $email;
				
				//Send verification code
				if (class_exists('doccure_Email_helper')) {
					if (class_exists('doccureBookingNotify')) {
						$email_helper 					= new doccureBookingNotify();
						$emailData['name'] 				= $full_name;
						$emailData['email']				= $email;
						$emailData['verification_code'] = $key_hash;
						$email_helper->send_verification($emailData);
					} 
				}
				
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__( 'Your informations are correct.', 'doccure' );
				
				wp_send_json( $json );
			}
		}		
	}

	add_action( 'wp_ajax_doccure_booking_step2', 'doccure_booking_step2' );
	add_action( 'wp_ajax_nopriv_doccure_booking_step2', 'doccure_booking_step2' );
}

/**
 * Booking step 3
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_booking_step3' ) ) {

	function doccure_booking_step3() {
		global $woocommerce ,$doccure_options,$current_user;
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		} //if demo site then prevent
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		$json 			= array();
		$date_formate	= get_option('date_format');
		$time_format 	= get_option('time_format');
		$code			= !empty( $_POST['authentication_code'] ) ? ( $_POST['authentication_code'] ) : '';
		
		if(!isset($_SESSION)){ session_start(array('user_data'));}
		
		$user_data		= isset($_SESSION['user_data']) ? $_SESSION['user_data'] : array();
		
		if( empty( $code ) ) {
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__( 'Please enter authentication code.', 'doccure' );
			wp_send_json( $json );
		} else {
			if(isset( $user_data['booking']['authentication_code'] ) ) {
				
				if( trim( $user_data['booking']['authentication_code'] ) === trim( $code ) ) {
					doccure_booking_complete();
				} else {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__("Authentication code is incorrect.", 'doccure');
					wp_send_json( $json );
				}
			} else {
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__("Oops! ", 'doccure');
				wp_send_json( $json );
			}
		}
		
	}

	add_action( 'wp_ajax_doccure_booking_step3', 'doccure_booking_step3' );
	add_action( 'wp_ajax_nopriv_doccure_booking_step3', 'doccure_booking_step3' );
}


/**
 * load booking
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_get_booking_byID' ) ) {

	function doccure_get_booking_byID() {
		global $current_user,$doccure_options;
		$json				= array();
		$booking_id			= !empty( $_POST['id'] ) ? intval( $_POST['id'] ) : '';
		$is_dashboard		= !empty( $_POST['dashboard'] ) ? esc_html( $_POST['dashboard'] ) : '';
		$is_type			= !empty( $_POST['type'] ) ? esc_html( $_POST['type'] ) : '';
		$hide_prescription			= !empty( $doccure_options['hide_prescription'] ) ? esc_html( $doccure_options['hide_prescription'] ) : 'no';
		$url_identity		= $current_user->ID;
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		} //if user is logged in
		
		if(!empty($is_type) && $is_type === 'patient') {
			if( function_exists('doccure_validate_privileges') ) { 
				doccure_validate_privileges($booking_id);
			} //if user is logged in and have privileges
		}else if(!empty($is_type) && $is_type === 'doctor') {
			$doctor_id		= get_post_meta($booking_id,'_doctor_id', true);
			$doctor_user_id			= doccure_get_linked_profile_id($doctor_id,'post');
			
			if( isset($doctor_user_id) && intval( $doctor_user_id ) !== $current_user->ID  ){
				$json['type'] 	 = 'error';
				$json['message'] = esc_html__('You are not authorized to view this booking details', 'doccure');
				wp_send_json( $json );
			}
		}else{
			if(!is_admin()){
				$data = get_userdata($current_user->ID);
				if(isset($data->roles) && is_array($data->roles) && in_array('administrator',$data->roles)){
					//do nothing
				}else{
					$json['type'] 	 = 'error';
					$json['message'] = esc_html__('You are not authorized to view this booking details', 'doccure');
					wp_send_json( $json );
				}
			}
		}
	
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		$width		= 100;
		$height		= 100;
		$current_user_type	= apply_filters('doccure_get_user_type', $url_identity );
		
		if(!empty($booking_id)) {
			ob_start();
			$date_format	= get_option('date_format');
			$time_format 	= get_option('time_format');
			$doctor_id		= get_post_meta($booking_id,'_doctor_id', true);
			
			$booking_date	= get_post_meta($booking_id,'_am_booking', true);
			$hospital_id	= get_post_meta($booking_id,'_booking_hospitals', true);
			
			$slots			= get_post_meta($booking_id,'_booking_slot', true);
			$slots			= !empty( $slots ) ? explode('-', $slots) : '';
			$tine_slot		= $slots;
			if( !empty( $slots ) ) {
				$slots	= date( $time_format,strtotime('2016-01-01' . $slots[0]) );
			}
			
			$user_types		= doccure_list_user_types();
			$content		= get_post_field('post_content',$booking_id );
			$contents		= !empty( $content ) ? $content : '';
			$booking_slot	= get_post_meta($booking_id,'_booking_slot', true);
			$booking_slot	= !empty( $booking_slot ) ? $booking_slot : '';
			$services		= get_post_meta($booking_id,'_booking_service', true);
			$services		= !empty( $services ) ? $services : array();
			$post_auter		= get_post_field( 'post_author',$booking_id );

			$booking_user_type		= get_post_meta( $booking_id,'_user_type',true);
			$thumbnail				= '';

			$booking_array	= get_post_meta( $booking_id, '_am_booking',true);
			$total_price	= !empty($booking_array['_price']) ? $booking_array['_price'] : 0;
			$consultant_fee	= !empty($booking_array['_consultant_fee']) ? $booking_array['_consultant_fee'] : 0;
			
			if( empty($booking_user_type) || $booking_user_type ==='regular_users' ){
				$link_id		= doccure_get_linked_profile_id( $post_auter );
				$thumbnail      = doccure_prepare_thumbnail($link_id, $width, $height);
				$user_type		= apply_filters('doccure_get_user_type', $post_auter );
				$user_type		= $user_types[$user_type];
				$user_type		= !empty( $user_type ) ? $user_type : '';
				$location		= doccure_get_location($link_id);
				$country		= !empty( $location['_country'] ) ? $location['_country'] : '';
			} else {
				$am_booking	= get_post_meta( $booking_id,'_am_booking',true);
				$user_type	= !empty($am_booking['_user_details']['user_type']) ? $am_booking['_user_details']['user_type'] : '';
			}

			$name		= get_post_meta($booking_id,'bk_username', true);
			$email		= get_post_meta($booking_id,'bk_email', true);
			$phone		= get_post_meta($booking_id,'bk_phone', true);

			$name		= !empty($name) ? $name : '';
			$email		= !empty($email) ? $email : '';
			$phone		= !empty($phone) ? $phone : '';

			$post_status		= get_post_status( $booking_id );
			$post_status_key	= $post_status;
			
			if($post_status === 'pending'){
				$post_status	= esc_html__('Pending','doccure');
			} elseif($post_status === 'publish'){
				$post_status	= esc_html__('Confirmed','doccure');
			} elseif($post_status === 'draft'){
				$post_status	= esc_html__('Pending','doccure');
			} elseif($post_status === 'cancelled'){
				$post_status	= esc_html__('Cancelled','doccure');
			}
			
			$relation			= doccure_patient_relationship();
			
			$posttype			= get_post_type($hospital_id);
			if( !empty($posttype) && $posttype === 'hospitals_team' ){
				$hospital_id		= get_post_meta($hospital_id,'hospital_id',true);
				$location_title 	= esc_html( get_the_title( $hospital_id ) );
			} else {
				$location_title 	= esc_html( get_the_title( $hospital_id ) );
			}
		
			$am_specialities 		= doccure_get_post_meta( $doctor_id,'am_specialities');
			$am_specialities		= !empty( $am_specialities ) ? $am_specialities : array();
			
			$google_calender		= '';
			$yahoo_calender			= '';
			$appointment_date		= get_post_meta($booking_id,'_appointment_date', true);
			
			if( !empty( $appointment_date ) && !empty( $tine_slot[0] ) && !empty( $tine_slot[1] ) ) {
				$startTime 	= new DateTime($appointment_date.' '.$tine_slot[0]);
				$startTime	= $startTime->format('Y-m-d H:i');

				$endTime 	= new DateTime($appointment_date.' '.$tine_slot[1]);
				$endTime	= $endTime->format('Y-m-d H:i');

				$google_calender	= doccure_generate_GoogleLink($name,$startTime,$endTime,$contents,$location_title);
				$yahoo_calender		= doccure_generate_YahooLink($name,$startTime,$endTime,$contents,$location_title);
			}
			
			$doctor_user_id			= doccure_get_linked_profile_id($doctor_id,'post');

			if( !empty($user_type) && $user_type === 'patients'){
				$user_type_title	= esc_html__('patient','doccure');
			} else {
				$user_type_title	= $user_type;
			}
			
			$prescription_id	= get_post_meta( $booking_id, '_prescription_id', true );
			$prescription_url	= !empty($booking_id) ? doccure_Profile_Menu::doccure_profile_menu_link('prescription', $current_user->ID,true,'view').'&booking_id='.$booking_id : '';
			$user_type_access		= apply_filters('doccure_get_user_type', $current_user->ID );
			?>

<?php 
$role = $current_user->roles[0];
?>	

			<div class="dc-user-header">
				<?php
				if($role=='doctors') {  ?>
			<div class="dc-user-header-inner">
				<?php if( !empty( $thumbnail ) ){?>
					
						<figure class="dc-user-img">
							<img src="<?php echo esc_url( $thumbnail );?>" alt="<?php echo esc_attr( $name );?>">
						</figure>
					
				<?php } ?>
				<div class="dc-title pateintview-details">
					<?php if( !empty( $name ) ){?>
						<h3>
							<?php 
								echo esc_html( $name ); 
								if(!empty($post_auter) && $post_auter !=1 ){
									doccure_get_verification_check($post_auter);
								}
							?>
						</h3>
						<h5>
						<?php if( !empty($email) ){?>
							<i class="far fa-envelope"></i> <?php echo esc_html($email);?>
						<?php } ?>
						</h5>
						<h5>
						<?php if( !empty($phone) ){?>
							<i class="fas fa-phone"></i> <?php echo esc_html($phone);?>
						<?php } ?>
						</h5>
					<?php } ?>
					<h5>
					<?php if(!empty($post_auter) && $post_auter !=1 ){ ?>
						<i class="feather-map-pin"></i> <?php echo esc_html( $country );?>
					<?php } ?>
					</h5>
				</div>
				</div>


				<?php } else { 
					
					$doctor_id	= get_post_meta( $booking_id,'_doctor_id',true);

					$name		= doccure_full_name( $doctor_id );
						$name		= !empty( $name ) ? $name : ''; 
						
					$thumbnail   	= doccure_prepare_thumbnail($doctor_id, $width, $height);
					
					$email		= get_post_meta($booking_id,'bk_email', true);
						$phone		= get_post_meta($booking_id,'bk_phone', true);

						$location		= doccure_get_location($doctor_id);
						$country		= !empty( $location['_country'] ) ? $location['_country'] : '';?> 

					<div class="dc-user-header-inner">
				<?php if( !empty( $thumbnail ) ){?>
					
						<figure class="dc-user-img">
							<img src="<?php echo esc_url( $thumbnail );?>" alt="<?php echo esc_attr( $name );?>">
						</figure>
					
				<?php } ?>
				<div class="dc-title pateintview-details">
					<?php if( !empty( $name ) ){?>
						<h3>
							<?php 
								echo esc_html( $name ); 
								if(!empty($post_auter) && $post_auter !=1 ){
									doccure_get_verification_check($post_auter);
								}
							?>
						</h3>
						<h5>
						<?php if( !empty($email) ){?>
							<i class="far fa-envelope"></i> <?php echo esc_html($email);?>
						<?php } ?>
						</h5>
						<h5>
						<?php if( !empty($phone) ){?>
							<i class="fas fa-phone"></i> <?php echo esc_html($phone);?>
						<?php } ?>
						</h5>
					<?php } ?>
					<h5>
					<?php if(!empty($post_auter) && $post_auter !=1 ){ ?>
						<i class="feather-map-pin"></i> <?php echo esc_html( $country );?>
					<?php } ?>
					</h5>
				</div>
				</div>
<?php }?>


				<?php if( !empty( $post_status ) ){ ?>
					<div class="dc-status-test">
						<div class="dc-rightarea dc-status">
							<span><?php echo esc_html(ucwords( $post_status ) );?></span>
							<em><?php esc_html_e('Status','doccure');?></em>
											<!-- status links -->
				<?php if ( is_user_logged_in() && ( $user_type_access === 'doctors' || $user_type_access === 'hospitals' || $user_type_access === 'regular_users' ) ) {?>
				<div class="dc-user-steps">
					<div class="dc-btnarea toolip-wrapo dc-print-options">

						<?php if( !empty( $booking_id ) && !empty( $current_user_type ) && $current_user_type != 'regular_users' ) {
							if( $post_status_key === 'pending' ){?>
								<a href="javascript:;" class="dc-btn dc-update-status btn btn-sm bg-success-light" data-status="publish" data-id="<?php echo intval($booking_id);?>"><i class="fas fa-check"></i><?php esc_html_e('Accept','doccure');?></a>

								<a href="javascript:;" class="dc-btn dc-deleteinfo dc-update-status btn btn-sm bg-danger-light" data-status="cancelled" data-id="<?php echo intval($booking_id);?>"><i class="fas fa-times"></i><?php esc_html_e('Cancel','doccure');?></a>
								
							<?php } 
						     if( $post_status_key === 'publish' && !empty($hide_prescription) && $hide_prescription == 'no' ){?>
								<a href="<?php echo esc_url($prescription_url);?>" class="dc-btn dc-filebtn add-new-btn generate-prescription"><?php esc_html_e('Add Prescription','doccure');?></a>
								<?php if( !empty($prescription_id) ){ ?>
								<form method="post" name="download_pdf">
									<input type="hidden" name="pdf_booking_id" value="<?php echo intval($booking_id);?>">
									<a href="javascript:;" onclick="document.forms['download_pdf'].submit(); return false;" class="dc-btn add-new-btn dc-pdfbtn"><?php esc_html_e('Download Prescription','doccure');?></a>
								</form>
							<?php } ?>
							<?php } 
							
								} else if( $is_dashboard === 'yes' && !empty( $current_user_type ) && $current_user_type === 'regular_users' ){
									?>
									<?php
								$base_url = get_site_url() . "/dashboard/";
								$user_identity 	= $current_user->ID;
								$full_url = $base_url . "?ref=chat&identity=" . $user_identity;
								?>

								<a href="javascript:;" onclick="window.open('<?php echo esc_url($full_url); ?>', '_blank'); return false;" class="dc-btn add-new-btn dc-chatbtn">
									<?php esc_html_e('Chat', 'doccure'); ?>
								</a>

								<?php if( !empty($prescription_id) ){ ?>

								<form method="post" name="download_pdf">
									<input type="hidden" name="pdf_booking_id" value="<?php echo intval($booking_id);?>">
									<a href="javascript:;" onclick="document.forms['download_pdf'].submit(); return false;" class="dc-btn add-new-btn dc-pdfbtn"><?php esc_html_e('Download Prescription','doccure');?></a>
								</form>
							<?php } ?>
						<?php } ?>
						
                        <?php 
						$get_order_id = get_post_meta($booking_id, '_order_id', true);
						
						if (empty($get_order_id)) {
							$get_order_id = get_post_meta($booking_id, '_product_rand_offline', true);
						} 
                        global $wpdb;
						$table_name = $wpdb->prefix . 'zoom_links';
						$meeting_link = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE order_id = %d", $get_order_id));
						?>
						<?php 
                                $post_status_reschedule = get_post_status( $booking_id ); 
                                if($post_status_reschedule == 'publish' && $meeting_link->meeting_id!=''){ ?>
 <a class="dc-btn meeting_button btn dc-btn-blocks dc-filebtn" href="<?php echo $meeting_link->meeting_id; ?>" target="_blank"><?php esc_html_e('Join Meeting','doccure');?></a>
 <?php } ?>
					</div>
				</div>
			<?php }?>


			<!-- ends status links -->

						</div>

					</div>
				<?php } ?>
			</div>

			

			<div class="dc-user-details">
				<div class="dc-user-grid">
					<?php if( !empty( $booking_date['_with_patient']['other_name'] ) ){?>
						<div class="dc-user-info dc-person-patient">
							<div class="dc-title">
								<h4><?php esc_html_e('Person with patient','doccure');?> :</h4>
								<span><?php echo esc_html( $booking_date['_with_patient']['other_name'] );?></span>
							</div>
						</div>
					<?php } ?>
					<?php if( !empty( $booking_date['_with_patient']['relation'] ) ){?>
						<div class="dc-user-info dc-person-relation">
							<div class="dc-title">
								<h4><?php esc_html_e('Relation with patient','doccure');?> :</h4>
								<span><?php echo esc_html( $relation[$booking_date['_with_patient']['relation']] );?></span>
							</div>
						</div>
					<?php } ?>
					<?php if( !empty( $location_title ) ){?>
						<div class="dc-user-info dc-location-title">
							<div class="dc-title">
								<h4><?php esc_html_e('Appointment location','doccure');?> :</h4>
								<span><?php echo esc_html( $location_title );?></span>
							</div>
						</div>
					<?php } ?>
					<?php if( !empty( $appointment_date ) && !empty( $slots ) ){?>
						<div class="dc-user-info dc-apt-detail-date">
							<div class="dc-title">
								<h4><?php esc_html_e('Appointment date','doccure');?> :</h4>
								<span><?php echo date_i18n( $date_format,strtotime( $appointment_date ) );?> - <?php echo esc_html($slots);?> </span>
							</div>
						</div>
					<?php } ?>
					
					<?php if( !empty( $services ) ) {?>
						<div class="dc-user-info dc-info-required dc-services-wrap">
							<div class="dc-title">
								<h4><?php esc_html_e('Services required','doccure');?>:</h4>
							</div>
							<?php 
								foreach( $services as $spe => $sers) {
									if( !empty( $spe ) ){ ?>
										<div class="dc-spec-wrap">
											<div class="dc-title">
												<span><?php echo doccure_get_term_name( $spe ,'specialities');?></span>
											</div>
											<?php if( !empty( $sers ) ){?>
											<ul class="dc-required-details">
												<?php foreach( $sers as $k => $val) {
														$single_price	 = 0;
														if( !empty($k) && $k === $val ){
															$am_specialities 	= !empty($doctor_id) ? doccure_get_post_meta( $doctor_id,'am_specialities') : array();
															$am_specialities	= !empty( $am_specialities ) ? $am_specialities : array();
															$single_price		= !empty($am_specialities[$spe][$k]['price']) ? $am_specialities[$spe][$k]['price'] : 0;
														} else {
															$single_price	= $val;
														}
													?>
													<li>
														<span>
															<?php
																echo doccure_get_term_name( $k ,'services');
																if( !empty($single_price)){ ?>
																	<em>(<?php doccure_price_format($single_price);?>)</em>
																<?php } ?>
														</span>
													</li>
												<?php } ?>
											</ul>
											<?php } ?>
										</div>
								<?php } ?>
							<?php } ?>
						</div>
					<?php }?>
					<?php if( !empty( $contents ) ){ ?>
						<div class="dc-required-info dc-apt-comments">
							<div class="dc-title">
								<h4><?php esc_html_e('Comments','doccure');?></h4>
							</div>
							<div class="dc-description"><p><?php echo esc_html( $contents );?></p></div>
						</div>
					<?php } ?>
					<?php if(isset($consultant_fee)){?>
						<div class="dc-user-info dc-apt-consult-fee">
							<div class="dc-title">
								<h4><?php esc_html_e('Consultant fee','doccure');?> :</h4>
								<span><?php doccure_price_format($consultant_fee);?></span>
							</div>
						</div>
					<?php } ?>
					<?php if( !empty( $total_price ) ){?>
						<div class="dc-user-info dc-total-fee">
							<div class="dc-title">
								<h4><?php esc_html_e('Total price','doccure');?>:</h4>
								<span>
									<?php doccure_price_format($total_price);?>
								</span>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
						<!-- Modal -->
			<div class="modal fade dc-appointmentpopup dc-feedbackpopup dc-bookappointment" role="dialog" id="send_message"> 
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="dc-modalcontent modal-content">	
						<div class="dc-popuptitle">
							<h3><?php esc_html_e('Send Message','doccure');?></h3>
							<a href="javascript:;" class="dc-closebtn close dc-close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e('Close','doccure');?>"><i class="ti-close"></i></a>
						</div>
						<div class="dc-formtheme dc-vistingdocinfo">
							<fieldset>
								<div class="form-group">
									<textarea id="dc-booking-msg" class="form-control" placeholder="<?php esc_attr_e('Message','doccure');?>" name="message"></textarea>
								</div>
							</fieldset>
						</div>
						<div class="modal-footer dc-modal-footer">
							<a href="javascript:;" class="btn dc-btn btn-primary dc-send_message-btn" data-id="<?php echo intval($booking_id);?>"><?php esc_html_e('Send','doccure');?></a>
						</div>			
					</div>
				</div>
			</div> 
		<?php
			$booking				= ob_get_clean();
			$json['type'] 			= 'success';
			$json['booking_data'] 	= $booking;
		} else{
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('No more review', 'doccure');
			$json['reviews'] 	= 'null';
		}
		wp_send_json($json);			
	}

	add_action( 'wp_ajax_doccure_get_booking_byID', 'doccure_get_booking_byID' );
	add_action( 'wp_ajax_nopriv_doccure_get_booking_byID', 'doccure_get_booking_byID' );
}

/**
 * Update booking status
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_update_booking_status' ) ) {

	function doccure_update_booking_status() {
		global $current_user;
		$post_id		= !empty( $_POST['id'] ) ? ( $_POST['id'] ) : '';
		$status 		= !empty( $_POST['status'] ) ? ( $_POST['status'] ) : '';
		$offline_package	= doccure_theme_option('payment_type');
		$time_format 	= get_option('time_format');
		$json 			= array();
		$update_post	= array();
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in
		
		$doctor_id			= get_post_meta($post_id,'_doctor_id', true);
		$doctor_user_id		= doccure_get_linked_profile_id($doctor_id,'post');

		if( isset($doctor_user_id) && intval( $doctor_user_id ) !== $current_user->ID  ){
			$json['type'] 	 = 'error';
			$json['message'] = esc_html__('You are not authorized to update the details', 'doccure');
			wp_send_json( $json );
		}

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}

		
		if( empty( $status ) ){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('Post status is required.', 'doccure');
			wp_send_json($json);
		}
		
		if( empty( $post_id ) ){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('Post ID is required.', 'doccure');
			wp_send_json($json);
		}
		
		if( !empty( $post_id ) && !empty( $status ) ){
			// for offline 
			if( !empty($offline_package) && $offline_package === 'offline' ){
				$order_id	= get_post_meta( $post_id, '_order_id', true );
				if( !empty($order_id) && class_exists('WC_Order') ){
					$order = new WC_Order($order_id);
					
					if (!empty($order)) {
						if( $status === 'publish' ){
							$order->update_status( 'completed' );
							$order->save();
						} else if($status === 'cancelled' ){
							$order->update_status( 'cancelled' );
							$order->save();
						}
					}
				}
			}
			
			
			$update_post['ID'] 			= $post_id;
			$update_post['post_status'] = $status;

			// Update the post into the database
			wp_update_post( $update_post );

			do_action('doccure_after_order_meta_update_offline', $post_id);


			$appointment_date		= get_post_meta($post_id,'_appointment_date',true);
			$appointment_date		= !empty( $appointment_date ) ? $appointment_date : '';
			
			$booking_slot			= get_post_meta($post_id,'_booking_slot',true);
			$booking_slot			= !empty( $booking_slot ) ? $booking_slot : array();
			
			$slot_key_val 			= explode('-', $booking_slot);
			$start_time				= date($time_format, strtotime('2016-01-01' . $slot_key_val[0]));
			$end_time				= date($time_format, strtotime('2016-01-01' . $slot_key_val[1]));

			$start_time				= !empty( $start_time ) ? $start_time : '';
			$end_time				= !empty( $end_time ) ? $end_time : '';
			
			$booking_hospitals		= get_post_meta($post_id,'_booking_hospitals',true);
			$hospital_id			= get_post_meta($booking_hospitals,'hospital_id',true);
			$hospital_name			= doccure_full_name($hospital_id);
			$hospital_name			= !empty( $hospital_name ) ? $hospital_name : '';
			$doctor_id				= get_post_meta($post_id,'_doctor_id',true);
			$doctor_id				= !empty( $doctor_id ) ? $doctor_id : '';
			$doctor_name			= doccure_full_name($doctor_id);
			$doctor_name			= !empty( $doctor_name ) ? $doctor_name : '';
			$author_id 				= get_post_field( 'post_author', $post_id );
			$user_profile_id		= doccure_get_linked_profile_id($author_id);
			$user_info				= get_userdata($author_id);
			
			if( !empty( $user_info ) ) {
				$emailData['email']			= $user_info->user_email;
				$emailData['user_name']		= doccure_full_name($user_profile_id);
			}

			$am_booking_new = get_post_meta($post_id, '_am_booking', true);
			$post_meta = maybe_unserialize($am_booking_new);
				$consultant_fee = $post_meta['_consultant_fee'];
				$total_price = $post_meta['_price'];

			$emailData['doctor_name']		= $doctor_name;
			$emailData['doctor_link']		= get_the_permalink( $doctor_id );
			$emailData['hospital_link']		= get_the_permalink( $hospital_id );
			$emailData['hospital_name']		= $hospital_name;
			$emailData['description']		= get_the_content($post_id);
			$emailData['appointment_date']	= $appointment_date;
			$emailData['appointment_time']	= $start_time.' '.esc_html__('to', 'doccure').' '.$end_time;
			
			$emailData['price']				= doccure_price_format($total_price,'return');
			$emailData['consultant_fee']	= doccure_price_format($consultant_fee,'return');
 				
			if (class_exists('doccure_Email_helper')) {
				if (class_exists('doccureBookingNotify')) {
					$email_helper = new doccureBookingNotify();
					if( $status === 'publish' ){
						$email_helper->send_approved_email($emailData);
						if( function_exists('doccure_send_booking_message') ){
							doccure_send_booking_message($post_id);
						}
					} else if( $status === 'cancelled' ){
						$email_helper->send_cancelled_email($emailData);
					}
				}
			}
			
			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Booking status has been updated.', 'doccure');
		}


		wp_send_json( $json );

	}

	add_action( 'wp_ajax_doccure_update_booking_status', 'doccure_update_booking_status' );
	add_action( 'wp_ajax_nopriv_doccure_update_booking_status', 'doccure_update_booking_status' );
}

/**
 * Update booking status
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_send_message' ) ) {

	function doccure_send_message() {
		global $current_user;
		$booking_id		= !empty( $_POST['id'] ) ? ( $_POST['id'] ) : '';
		$message 		= !empty( $_POST['msg'] ) ? ( $_POST['msg'] ) : '';
		
		$post_author 	= get_post( $booking_id );
		$post_author_id		= !empty($post_author->post_author) ? intval( $post_author->post_author ) : 0;
			
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in
		
		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		$doctor_id			= get_post_meta($booking_id,'_doctor_id', true);
		$doctor_user_id		= doccure_get_linked_profile_id($doctor_id,'post');
		
		$doctor_user_id		= !empty($doctor_user_id) ? intval( $doctor_user_id ) : 0;
		$current_user_id	= !empty($current_user->ID) ? intval( $current_user->ID ) : 0;
		$allowed_id			= array($doctor_user_id,$post_author_id);
		
		if( !empty($doctor_user_id) 
		   && !empty($post_author_id) 
		   && ( !in_array($current_user_id,$allowed_id)) 
		){
			$json['type'] 	 = 'error';
			$json['message'] = esc_html__('You are not authorized to update the details', 'doccure');
			wp_send_json( $json );
		}

		if( !empty($message) && !empty($booking_id) ){
			if( function_exists('doccure_send_booking_message') ){
				$active_id			= doccure_send_booking_message($booking_id,$message);
				$json['url'] 	 	= doccure_Profile_Menu::doccure_profile_menu_link('chat', $current_user->ID,true,'settings',$active_id);
				$json['type'] 		= 'success';
				$json['message'] 	= esc_html__('Message send successfuly.', 'doccure');
			
				wp_send_json( $json );

			}
		}
	}

	add_action( 'wp_ajax_doccure_send_message', 'doccure_send_message' );
	add_action( 'wp_ajax_nopriv_doccure_send_message', 'doccure_send_message' );
}

/**
 * Update Payrols
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_payrols_settings')) {

    function doccure_payrols_settings() {
        global $current_user;
        $user_identity 	= $current_user->ID;
        $json 			= array();
		$data 			= array();
		$payrols		= doccure_get_payouts_lists();
		$fields			= !empty( $payrols[$_POST['payout_settings']['type']]['fields'] ) ? $payrols[$_POST['payout_settings']['type']]['fields'] : array();
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}

		if( !empty($fields) ) {
			foreach( $fields as $key => $field ){
				if( $field['required'] === true && empty( $_POST['payout_settings'][$key] ) ){
					$json['type'] 		= 'error';
					$json['message'] 	= $field['message'];
					wp_send_json( $json );
				}
			}
		}
		
		update_user_meta($user_identity,'payrols',$_POST['payout_settings']);
		$json['url'] 	 = doccure_Profile_Menu::doccure_profile_menu_link('payouts', $user_identity,true,'settings');
		$json['type'] 	 = 'success';
		$json['message'] = esc_html__('Payout settings have been updated.', 'doccure');

       wp_send_json( $json );
    }

    add_action('wp_ajax_doccure_payrols_settings', 'doccure_payrols_settings');
    add_action('wp_ajax_nopriv_doccure_payrols_settings', 'doccure_payrols_settings');
}

/**
 * Remove Payrols settings
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_payrols_remove_settings')) {

    function doccure_payrols_remove_settings() {
        global $current_user;
        $user_identity 	= $current_user->ID;
        
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		update_user_meta($user_identity,'payrols',array());
		$json['type'] 	 = 'success';
		$json['message'] = esc_html__('Payout settings have been removed.', 'doccure');

       wp_send_json( $json );
    }

    add_action('wp_ajax_doccure_payrols_remove_settings', 'doccure_payrols_remove_settings');
    add_action('wp_ajax_nopriv_doccure_payrols_remove_settings', 'doccure_payrols_remove_settings');
}


/**
 * check feedback
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_check_feedback')) {

    function doccure_check_feedback() {
		global $current_user,$doccure_options;
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent

        $user_identity 			= $current_user->ID;
		$user_type	 			= apply_filters('doccure_get_user_type', $user_identity );
		$id						= !empty( $_POST['id'] ) ? sanitize_text_field($_POST['id']) : '';
		$metadata		= array();

		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}		
		
		if( empty( $id ) ) {
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('Post ID is required','doccure');
			wp_send_json( $json );
		}
		
		//check if patients only
		if( !empty( $user_type ) && $user_type != 'regular_users') {
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('You are not allowed to add feedback.','doccure');
			wp_send_json( $json );
		}
		
		$doctor_id				= doccure_get_linked_profile_id($id,'post');
		
		$user_reviews = array(
				'posts_per_page' 	=> 1,
				'post_type' 		=> 'reviews',
				'author' 			=> $doctor_id,
				'meta_key' 			=> '_user_id',
				'meta_value' 		=> $user_identity,
				'meta_compare' 		=> "=",
				'orderby' 			=> 'meta_value',
				'order' 			=> 'ASC',
			);

		$reviews_query = new WP_Query($user_reviews);
		$reviews_count = $reviews_query->post_count;

		if (isset($reviews_count) && $reviews_count > 0) {
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('You have already submit a review.', 'doccure');
			wp_send_json($json);
		}
		
		if( $user_type === 'regular_users' && !empty( $id ) ) {
			$feedback_option	= !empty($doccure_options['feedback_option']) ? $doccure_options['feedback_option'] : '';
			if( empty($feedback_option) ){
				$json['type'] 	 = 'success';
				$json['message'] = esc_html__('Please add your feed back.', 'doccure');
			} else {
				$metadata['_doctor_id']	= $id;
				$bookings				= doccure_get_total_posts_by_multiple_meta('booking','publish',$metadata,$user_identity);
				if( !empty( $bookings ) && $bookings > 0 ) {
					$json['type'] 	 = 'success';
					$json['message'] = esc_html__('Please add your feed back.', 'doccure');
				} else {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('You need to complete atleast 1 appointment to add feedback.','doccure');
				}
			}
			wp_send_json( $json );
		} else {
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('Something went wrong, please contact to administrator','doccure');
			wp_send_json( $json );
		}
		
    }

    add_action('wp_ajax_doccure_check_feedback', 'doccure_check_feedback');
    add_action('wp_ajax_nopriv_doccure_check_feedback', 'doccure_check_feedback');
}

/**
 * On call contact details
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_bookings_details')) {

    function doccure_bookings_details() {
		global $doccure_options;
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		$doctor_profile_id		= !empty( $_POST['id'] ) ? sanitize_text_field($_POST['id']) : '';
		if(empty($doctor_profile_id)){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('Doctor profile is not found','doccure');
		} else {
			$html	= '';
			$booking_option	= !empty($doccure_options['booking_system_contact']) ? $doccure_options['booking_system_contact'] : '';
			
			if(empty($booking_option) || $booking_option === 'admin'){
				$contact_numbers	= !empty( $doccure_options['booking_contact_numbers'] ) ? $doccure_options['booking_contact_numbers'] : array();
				$booking_detail		= !empty($doccure_options['booking_contact_detail']) ? $doccure_options['booking_contact_detail'] : '';

			} else {
				$contact_numbers	= doccure_get_post_meta( $doctor_profile_id,'am_booking_contact');
				$booking_detail		= doccure_get_post_meta( $doctor_profile_id,'am_booking_detail');
			}
			
			$html	.= '<div class="dc-tell-numbers">';
			if(!empty($booking_detail)){
				$html	.= '<span>'.$booking_detail.'</span>';
			}
			
			if(!empty($contact_numbers)){
				foreach( $contact_numbers as $contact_number ){
					if(!empty($contact_number)){
						$html	.= '<a href="tel:+'.$contact_number.'" class="gh-numpopup">'.$contact_number.'</a>';
					}
				}
			}
			
			$html	.= '</div>';
			
			if( empty($contact_numbers) && empty($booking_detail) ){
				$json['type'] 		= 'error';
				$json['message'] 	= esc_html__('We are sorry, but there is no contact information has been added.','doccure');
				
			} else {
				$json['type'] 		= 'success';
				$json['html'] 		= $html;
				$json['message'] 	= esc_html__('Booking contact details.','doccure');
			}

			
		}
		wp_send_json( $json );
	}
	add_action('wp_ajax_doccure_bookings_details', 'doccure_bookings_details');
    add_action('wp_ajax_nopriv_doccure_bookings_details', 'doccure_bookings_details');
}
/**
 * Add doctor feedback
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_users_invitations')) {

    function doccure_users_invitations() {
		global $current_user;
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		$fields			= array(
			'emails' 	=> esc_html('Email is required field.','doccure')
		);

		foreach($fields as $key => $val ) {
			if( empty( $_POST[$key] ) ){
				$json['type'] 		= 'error';
				$json['message'] 	= $val;        
				wp_send_json($json);
			}
		}

		$emails		= !empty($_POST['emails']) ? $_POST['emails'] : array();
		$content	= !empty($_POST['content']) ? $_POST['content'] : '';

		$user_name			= doccure_get_username($current_user->ID);
		$user_detail		= get_userdata($current_user->ID);
		$user_type			= doccure_get_user_type( $current_user->ID );
		$linked_profile   	= doccure_get_linked_profile_id($current_user->ID);
		$profile_url		= get_the_permalink( $linked_profile );
		
		if (class_exists('doccure_Email_helper')) {
            if (class_exists('doccureInvitationsNotify')) {
				$email_helper = new doccureInvitationsNotify();
				if(!empty($emails)){
					$signup_page_url = doccure_get_signup_page_url();
					$signup_page_url	= !empty($signup_page_url) ? $signup_page_url : home_url('/');
					foreach($emails as $email){
						if( is_email($email) ){
							$emailData = array();
							
							$emailData['email']     				= $email;
							$emailData['invitation_content']     	= $content;
							$emailData['invitation_link']     		= $signup_page_url;
							
							if(!empty($user_type) && $user_type === 'doctors' ){
								$emailData['doctor_name']				= $user_name;
								$emailData['doctor_profile_url']		= $profile_url;
								$emailData['doctor_email']				= $user_detail->user_email;
								$emailData['invited_hospital_email']	= $email;
								$email_helper->send_hospitals_email($emailData);
							} else if(!empty($user_type) && $user_type === 'hospitals'){
								$emailData['hospital_name']				= $user_name;
								$emailData['hospital_profile_url']		= $profile_url;
								$emailData['hospital_email']			= $user_detail->user_email;
								$emailData['invited_docor_email']		= $email;
								$email_helper->send_doctors_email($emailData);
							}
						}
					}
				}
               
				$json['type'] 	 = 'success';
				$json['message'] = esc_html__('Your invitation is send to your email address.', 'doccure');
				wp_send_json( $json );
            } 
        }

	}
	add_action('wp_ajax_doccure_users_invitations', 'doccure_users_invitations');
    add_action('wp_ajax_nopriv_doccure_users_invitations', 'doccure_users_invitations');
}
/**
 * Add doctor feedback
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_add_feedback')) {

    function doccure_add_feedback() {
        global $current_user,$wpdb;
        $user_identity 	= $current_user->ID;
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		//check if user is patient only
		$current_user_type	= apply_filters('doccure_get_user_type', $user_identity );
		if(isset($current_user_type) && $current_user_type != 'regular_users'){
			$json['type'] = 'error';
			$json['message'] = esc_html__('You are not authorized to add the review', 'doccure');
			wp_send_json( $json );
		}
		
		$fields			= array(
								'feedback_recommend' 	=> esc_html('Recommend is required field.','doccure'),
								'waiting_time' 			=> esc_html('Select the waiting time.','doccure'),
								'feedback' 				=> esc_html('Rating is required.','doccure'),
								'feedback_description' 	=> esc_html('Description is required field.','doccure'),
								'doctor_id'				=> esc_html('Doctor ID is required.','doccure'),
							);
		
		foreach($fields as $key => $val ) {
			if( empty( $_POST[$key] ) ){
				$json['type'] 		= 'error';
				$json['message'] 	= $val;        
				wp_send_json($json);
			 }
		}
		
		$contents 				= !empty( $_POST['feedback_description'] ) ? sanitize_textarea_field($_POST['feedback_description']) : '';
		$recommend 				= !empty( $_POST['feedback_recommend'] ) ? sanitize_text_field($_POST['feedback_recommend']) : '';
		$waiting_time			= !empty( $_POST['waiting_time'] ) ? sanitize_text_field($_POST['waiting_time']) : '';
		$doctor_profile_id		= !empty( $_POST['doctor_id'] ) ? sanitize_text_field($_POST['doctor_id']) : '';
		$feedbackpublicly		= !empty( $_POST['feedbackpublicly'] ) ? sanitize_text_field($_POST['feedbackpublicly']) : '';
		$reviews 				= !empty( $_POST['feedback'] ) ? $_POST['feedback'] : array();
		$review_title			= get_the_title($doctor_profile_id);
		$doctor_id				= doccure_get_linked_profile_id($doctor_profile_id,'post');
		
		$user_reviews = array(
				'posts_per_page' 	=> 1,
				'post_type' 		=> 'reviews',
				'author' 			=> $doctor_id,
				'meta_key' 			=> '_user_id',
				'meta_value' 		=> $user_identity,
				'meta_compare' 		=> "=",
				'orderby' 			=> 'meta_value',
				'order' 			=> 'ASC',
			);

		$reviews_query = new WP_Query($user_reviews);
		$reviews_count = $reviews_query->post_count;

		if (isset($reviews_count) && $reviews_count > 0) {
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('You have already submit a review.', 'doccure');
			wp_send_json($json);
		} else{
			$review_post = array(
				'post_title' 		=> $review_title,
				'post_status' 		=> 'publish',
				'post_content' 		=> $contents,
				'post_author' 		=> $doctor_id,
				'post_type' 		=> 'reviews',
				'post_date' 		=> current_time('Y-m-d H:i:s')
			);
			
			$post_id = wp_insert_post($review_post);

			/* Get the rating headings */
			$rating_evaluation 			= doccure_doctor_ratings();
			$rating_evaluation_count 	= !empty($rating_evaluation) ? doccure_count_items($rating_evaluation) : 0;

			$review_extra_meta = array();
			$rating 		= 0;
			$user_rating 	= 0;

			if (!empty($rating_evaluation)) {
				foreach ($rating_evaluation as $slug => $label) {
					if (isset($reviews[$slug])) {
						$review_extra_meta[$slug] = esc_html($reviews[$slug]);
						update_post_meta($post_id, $slug, esc_html($reviews[$slug]));
						$rating += (int) $reviews[$slug];
					}
				}
			}

			update_post_meta($post_id, '_user_id', $user_identity);
			update_post_meta($post_id, '_waiting_time', $waiting_time);
			update_post_meta($post_id, '_feedback_recommend', $recommend);
			update_post_meta($post_id, '_feedbackpublicly', $feedbackpublicly);

			if( !empty( $rating ) ){
				$user_rating = $rating / $rating_evaluation_count;
			}
			
			$user_profile_id		= doccure_get_linked_profile_id($user_identity);
			$user_rating 			= number_format((float) $user_rating, 2, '.', '');
			$single_user_user_rating	= $user_rating;
			$review_meta 			= array(
				'user_rating' 		=> $user_rating,
				'user_from' 		=> $user_profile_id,
				'user_to' 			=> $doctor_profile_id,
				'review_date' 		=> current_time('Y-m-d H:i:s'),
			);
			$review_meta = array_merge($review_meta, $review_extra_meta);

			//Update post meta
			foreach ($review_meta as $key => $value) {
				update_post_meta($post_id, $key, $value);
			}
			
			$table_review 	= $wpdb->prefix . "posts";
			$table_meta 	= $wpdb->prefix . "postmeta";

			$db_rating_query = $wpdb->get_row( "
				SELECT p.ID,
				SUM( pm2.meta_value ) AS db_rating,
				count( p.ID ) AS db_total
				FROM ".$table_review." p 
				LEFT JOIN ".$table_meta." pm1 ON (pm1.post_id = p.ID AND pm1.meta_key = 'user_to') 
				LEFT JOIN ".$table_meta." pm2 ON (pm2.post_id = p.ID AND pm2.meta_key = 'user_rating')
				WHERE post_status = 'publish'
				AND pm1.meta_value = ".$doctor_profile_id."
				AND p.post_type = 'reviews'
				",ARRAY_A);

			//$user_rating = '0';

			if( empty( $db_rating_query ) ){
				$user_db_reviews['dc_average_rating'] 	= 0;
				$user_db_reviews['dc_total_rating'] 	= 0;
				$user_db_reviews['dc_total_percentage'] = 0;
				$user_db_reviews['wt_rating_count'] 	= 0;
			} else{

				$rating			= !empty( $db_rating_query['db_rating'] ) ? $db_rating_query['db_rating']/$db_rating_query['db_total'] : 0;
				$user_rating 	= number_format((float) $rating, 2, '.', '');

				$user_db_reviews['dc_average_rating'] 	= $user_rating;
				$user_db_reviews['dc_total_rating'] 	= !empty( $db_rating_query['db_total'] ) ? $db_rating_query['db_total'] : '';
				$user_db_reviews['dc_total_percentage'] = $user_rating * 20;
				$user_db_reviews['dc_rating_count'] 	= !empty( $db_rating_query['db_rating'] ) ? $db_rating_query['db_rating'] : '';
			}

			update_post_meta($doctor_profile_id, 'review_data', $user_db_reviews);
			update_post_meta($doctor_profile_id, 'rating_filter', $user_rating);
			
			$total_rating	= get_post_meta($doctor_profile_id, '_total_voting', true);
			$total_rating	= !empty( $total_rating ) ? $total_rating + 1 : 0;
			
			$total_recommend	= get_post_meta($doctor_profile_id, '_recommend', true);
			$total_recommend	= !empty( $total_recommend ) ? $total_recommend : 0 ;
			$total_recommend	= !empty( $recommend ) && $recommend === 'yes' ? $total_recommend +1 : $total_recommend;
			
			update_post_meta($doctor_profile_id, '_recommend', $total_recommend);
			update_post_meta($doctor_profile_id, '_total_voting', $total_rating);
			
			//Send email to users
			if (class_exists('doccure_Email_helper')) {
				if (class_exists('doccureFeedbackNotify')) {
					$email_helper 						= new doccureFeedbackNotify();
					$doctor_details						= !empty($doctor_id) ? get_userdata( $doctor_id ) : array();
					$emailData 	  						= array();
					$waiting_time_array					= doccure_get_waiting_time();
					$emailData['email'] 				= !empty($doctor_details->user_email) ? $doctor_details->user_email : '';
					$emailData['user_name'] 			= !empty($user_profile_id) ? doccure_full_name($user_profile_id) : '';
					$emailData['doctor_name'] 			= !empty($doctor_profile_id) ? doccure_full_name($doctor_profile_id) : '';
					$emailData['waiting_time'] 			= !empty($waiting_time_array[$waiting_time]) ? esc_html($waiting_time_array[$waiting_time]) : '';
					$emailData['recommend'] 			= !empty($recommend) ? ucfirst($recommend) : '';
					$emailData['rating'] 				=  !empty($single_user_user_rating) ? $single_user_user_rating : 0;
					$emailData['description'] 			= sanitize_textarea_field( $contents );

					$email_helper->send_feedback_email_doctor($emailData);
				}
			}
			$json['type'] 	 = 'success';
			$json['message'] = esc_html__('Your feedback is successfully submitted.', 'doccure');
			wp_send_json( $json );
		}
    }

    add_action('wp_ajax_doccure_add_feedback', 'doccure_add_feedback');
    add_action('wp_ajax_nopriv_doccure_add_feedback', 'doccure_add_feedback');
}

/**
 * Send app url
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_get_app_link')) {

    function doccure_get_app_link() {
		$app_eamil	= !empty( $_POST['app_eamil'] ) ? $_POST['app_eamil'] : '';
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}
		
		if( empty( $app_eamil ) ) {
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('Email is required.','doccure');
			wp_send_json( $json );
		} 

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		if( is_email( $app_eamil ) ) {
			//Send email to user
			if (class_exists('doccure_Email_helper')) {
				if (class_exists('doccureAppLinkNotify')) {
					$email_helper = new doccureAppLinkNotify();
					$emailData = array();
					$emailData['email']     = $app_eamil;
					$email_helper->send_applink_email($emailData);
					$json['type'] 	 = 'success';
					$json['message'] = esc_html__('App link is send to your email address.', 'doccure');
					wp_send_json( $json );
				} 
			} 
		} else {
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('Please enter a valid email address.','doccure');
			wp_send_json( $json );
		}
		
    }

    add_action('wp_ajax_doccure_get_app_link', 'doccure_get_app_link');
    add_action('wp_ajax_nopriv_doccure_get_app_link', 'doccure_get_app_link');
}

/**
 * Update prescription
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_update_prescription')) {

    function doccure_update_prescription() {
		global $current_user;
		$booking_id				= !empty($_POST['booking_id']) ? sanitize_text_field($_POST['booking_id']) : '';
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		$doctor_id			= get_post_meta($booking_id,'_doctor_id', true);
		$doctor_user_id		= doccure_get_linked_profile_id($doctor_id,'post');

		if( isset($doctor_user_id) && intval( $doctor_user_id ) !== intval( $current_user->ID )  ){
			$json['type'] 	 = 'error';
			$json['message'] = esc_html__('You are not authorized to update the details', 'doccure');
			wp_send_json( $json );
		}
		
		$json		= array();
		$fields		= array(
						'patient_name' 		=> esc_html('Name is required.','doccure'),
						'medical_history' 	=> esc_html('Medical history is required.','doccure'),
						'booking_id' 		=> esc_html('Booking ID is required.','doccure')
					);
		
		foreach($fields as $key => $val ) {
			if( empty( $_POST[$key] ) ){
				$json['type'] 		= 'error';
				$json['message'] 	= $val;        
				wp_send_json($json);
			 }
		}
		
		
		$patient_name			= !empty($_POST['patient_name']) ? sanitize_text_field($_POST['patient_name']) : '';
		$phone					= !empty($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
		$age					= !empty($_POST['age']) ? sanitize_text_field($_POST['age']) : '';
		$address				= !empty($_POST['address']) ? sanitize_text_field($_POST['address']) : '';
		$location				= !empty($_POST['location']) ? doccure_get_term_by_type('slug',sanitize_text_field($_POST['location']),'locations' ) : '';
		$gender					= !empty($_POST['gender']) ? sanitize_text_field($_POST['gender']) : '';
		$marital_status			= !empty($_POST['marital_status']) ? ($_POST['marital_status']) : '';
		$childhood_illness		= !empty($_POST['childhood_illness']) ? ($_POST['childhood_illness']) : array();
		$laboratory_tests		= !empty($_POST['laboratory_tests']) ? ($_POST['laboratory_tests']) : array();
		$vital_signs			= !empty($_POST['vital_signs']) ? ($_POST['vital_signs']) : '';
		$medical_history		= !empty($_POST['medical_history']) ? sanitize_text_field($_POST['medical_history']) : '';
		$medicine				= !empty($_POST['medicine']) ? ($_POST['medicine']) : array();
	
		$diseases				= !empty($_POST['diseases']) ? ($_POST['diseases']) : array();
		$medical_history		= !empty($_POST['medical_history']) ? sanitize_textarea_field($_POST['medical_history']) : '';
		
		$doctor_id				= get_post_meta( $booking_id, '_doctor_id', true );
		$doctor_id				= doccure_get_linked_profile_id($doctor_id,'post');
		$hospital_id			= get_post_meta( $booking_id, '_hospital_id', true );
		
		$prescription_id		= get_post_meta( $booking_id, '_prescription_id', true );
		$am_booking				= get_post_meta( $booking_id, '_am_booking', true );
		$patient_id				= get_post_field( 'post_author', $booking_id );

		$myself					= !empty($am_booking['myself']) ? $am_booking['myself'] : '';

		if( !empty($doctor_id) && ($doctor_id != $current_user->ID) ){
			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__('You are not allwod to add prescription.','doccure');        
			wp_send_json($json);
		}

		$post_array					= array();
		$post_array['post_title']	=	$patient_name;
		if( empty($prescription_id) ){
			$post_array['post_type']	= 'prescription';
			$post_array['post_status']	= 'publish';
			$prescription_id = wp_insert_post($post_array);
		} else {
			wp_update_post($post_array);
		}

		$post_meta						= array();
		if( !empty($laboratory_tests) ){
			$laboratory_tests_array	= array();
			foreach($laboratory_tests as $laboratory_test ){
				$term 	= doccure_get_term_by_type( 'id',$laboratory_test, 'laboratory_tests','id' );
				if ( !empty($term) ) {
					$laboratory_tests_id	= $laboratory_test;
				} else {
					wp_insert_term($laboratory_test,'laboratory_tests');
					$term 					= doccure_get_term_by_type( 'name',$laboratory_test, 'laboratory_tests','id' );
					$laboratory_tests_id	= !empty($term) ? $term : '';
				}

				if( !empty( $laboratory_tests_id ) ){
					$laboratory_tests_array[] = $laboratory_tests_id;
				}
			}
			if( !empty( $laboratory_tests_array ) ){
				wp_set_post_terms( $prescription_id, $laboratory_tests_array, 'laboratory_tests' );
			}
			$post_meta['_laboratory_tests']		= $laboratory_tests_array;
		}
		
		$post_meta['_patient_name']		= $patient_name;
		$post_meta['_phone']			= $phone;
		$post_meta['_age']				= $age;
		$post_meta['_address']			= $address;
		$post_meta['_location']			= $location;
		$post_meta['_gender']			= $gender;

		$post_meta['_marital_status']		= $marital_status;
		$post_meta['_childhood_illness']	= $childhood_illness;
		$post_meta['_vital_signs']			= $vital_signs;
		$post_meta['_medical_history']		= $medical_history;
		$post_meta['_medicine']				= $medicine;
		$post_meta['_diseases']				= $diseases;

		$signs_keys		= !empty($vital_signs) ? array_keys($vital_signs) : array();
		$signs_keys		= !empty($signs_keys) ? array_unique($signs_keys): array();
		
		wp_set_post_terms( $prescription_id, array($location), 'locations' );
		wp_set_post_terms( $prescription_id, $signs_keys, 'vital_signs' );
		wp_set_post_terms( $prescription_id, $childhood_illness, 'childhood_illness' );
		wp_set_post_terms( $prescription_id, array($marital_status), 'marital_status' );
		wp_set_post_terms( $prescription_id, $diseases, 'diseases' );
		
		update_post_meta( $prescription_id, '_hospital_id',$hospital_id );
		update_post_meta( $prescription_id, '_medicine',$medicine );
		update_post_meta( $prescription_id, '_doctor_id',$doctor_id );
		update_post_meta( $prescription_id, '_booking_id',$booking_id );
		update_post_meta( $prescription_id, '_patient_id',$patient_id );
		update_post_meta( $prescription_id, '_myself',$myself );
		update_post_meta( $prescription_id, '_detail',$post_meta );
		
		update_post_meta( $prescription_id, '_childhood_illness',$childhood_illness );
		update_post_meta( $prescription_id, '_marital_status',$marital_status );

		update_post_meta( $booking_id, '_prescription_id',$prescription_id );

		$json['type'] 	 	= 'success';
		$json['message'] 	= esc_html__('Prescription has been updated successfully.', 'doccure');
		$json['url']		= doccure_Profile_Menu::doccure_profile_menu_link('appointment', $current_user->ID,true,'listing',$booking_id);
		wp_send_json( $json );

    }

    add_action('wp_ajax_doccure_update_prescription', 'doccure_update_prescription');
    add_action('wp_ajax_nopriv_doccure_update_prescription', 'doccure_update_prescription');
}

/**
 * Send app url
 *
 * @throws error
 * @return 
 */
if (!function_exists('doccure_calcute_price')) {

    function doccure_calcute_price() {
		
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		$json			= array();
		$consultant_fee		= !empty( $_POST['consultant_fee'] ) ? $_POST['consultant_fee'] : 0;
		$allprices			= !empty( $_POST['allprices'] ) ? $_POST['allprices'] : '';
		$price				= !empty( $_POST['price'] ) ? $_POST['price'] : 0;
		
		if( !empty( $allprices ) && is_array($allprices) ){
			$total_price	= array_sum($allprices) + $consultant_fee ;
		} else {
			$allprices="0";
			$total_price	= ($allprices) + $consultant_fee ;
		}
		
		$json['total_price']			= $total_price;
		$json['total_price_format']		= doccure_price_format($total_price,'return');
		$json['price_format']			= doccure_price_format($price,'return');
		$json['type'] 	 	= 'success';
		wp_send_json( $json );
    }

    add_action('wp_ajax_doccure_calcute_price', 'doccure_calcute_price');
    add_action('wp_ajax_nopriv_doccure_calcute_price', 'doccure_calcute_price');
}

/**
 * Re-send verification email
 *
 * @throws error
 * @return 
 */
if (!function_exists('doccure_resend_verification')) {

    function doccure_resend_verification() {
		global $current_user;
		$json		= array();
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
		//Send verification code
		if (class_exists('doccure_Email_helper')) {
			if (class_exists('doccureRegisterNotify')) {
				$email_helper 					= new doccureRegisterNotify();
				
				$key_hash = md5(uniqid(openssl_random_pseudo_bytes(32)));
				update_user_meta( $current_user->ID, 'confirmation_key', $key_hash);
				$protocol = is_ssl() ? 'https' : 'http';
				$verify_link = esc_url(add_query_arg(array('key' => $key_hash.'&verifyemail='.$current_user->user_email), home_url('/', $protocol)));

				
				$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
				$emailData = array();
				$emailData['name'] 				= doccure_get_username( $current_user->ID );;
				$emailData['email'] 			= $current_user->user_email;
				$emailData['site'] 				= $blogname;
				$emailData['verification_link'] = $verify_link;
				
				$email_helper->send_verification($emailData);
			} 
		}
		$json['type'] 	 	= 'success';
		$json['message'] 	 	= esc_html__('Verification email has been sent to your email address', 'doccure');
		wp_send_json( $json );
    }

    add_action('wp_ajax_doccure_resend_verification', 'doccure_resend_verification');
    add_action('wp_ajax_nopriv_doccure_resend_verification', 'doccure_resend_verification');
}

/**
 * Post Likes
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_post_likes')) {

    function doccure_post_likes() {
		$post_id	= !empty( $_POST['id'] ) ? $_POST['id'] : '';
		$json		= array();
		
		if( function_exists('doccure_validate_user') ) { 
			doccure_validate_user();
		}; //if user is logged in

		//security check
		$do_check = check_ajax_referer('ajax_nonce', 'security', false);
		if ( $do_check == false ) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure');
			wp_send_json( $json );
		}
		
        if (empty($post_id)) {
            $json['type'] 	 = 'error';
			$json['message'] = esc_html__('Post ID is required', 'doccure');
			wp_send_json( $json );
        }

		$key	= 'post_liked_';
		
        if (!isset($_COOKIE[$key . $post_id])) {
            setcookie($key . $post_id, $key, time() + ( 365 * 24 * 60 * 60));
            $view_key = 'post_likes';

            $count = get_post_meta($post_id, $view_key, true);

            if (empty($count)) {
                $count = 1;
                add_post_meta($post_id, $view_key, 1);
            } else {
                $count++;
                update_post_meta($post_id, $view_key, $count);
            }
			
			$json['html'] 	 = sprintf( _n( '<i class="ti-heart"></i>%s Like', '<i class="ti-heart"></i>%s Likes', $count, 'doccure' ), $count );
		
			$json['type'] 	 = 'success';
			$json['message'] = esc_html__('Post has been liked', 'doccure');
			wp_send_json( $json );
        } else{
			$json['type'] 	 = 'error';
			$json['message'] = esc_html__('You have already liked this post', 'doccure');
			wp_send_json( $json );
		}
    }

    add_action('wp_ajax_doccure_post_likes', 'doccure_post_likes');
    add_action('wp_ajax_nopriv_doccure_post_likes', 'doccure_post_likes');
}
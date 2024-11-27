<?php
/**
 * Get Earnigs Status
 *
 * @throws error
 * @author Dreams Technologies
 * @return 
 */
if( !function_exists(  'doccure_get_earning_status_list' ) ) {
	function doccure_get_earning_status_list(){
		$list	= array(
			'pending' 	=> esc_html__('Pending','doccure'),
			'completed' => esc_html__('Completed','doccure'),
			'cancelled' => esc_html__('Cancelled','doccure'),
			'processed' => esc_html__('Processed','doccure')
		);
		
		return $list;
	}
}


/**
 * @Validte if user is logged  and right privileges
 * @return {}
 */

/** if (!function_exists('doccure_validate_privileges')) {
	function doccure_validate_privileges($post_id,$message=''){
		global $current_user;
		$json = array();
		
		$message	=  !empty($message) ? $message : esc_html__('You are not authorized to perform this action', 'doccure');
		
		if (!is_user_logged_in()) {
			$json['type'] 	 = 'error';
            $json['message'] = $message;
            wp_send_json( $json );
		}
		
		$post_data 		= get_post( $post_id );
     	$post_author	= !empty( $post_data->post_author ) ? intval( $post_data->post_author ) : 0;

		if(isset($post_author) && $post_author  !== $current_user->ID ){
			$json['type'] 	 = 'error';
            $json['message'] = $message;
            wp_send_json( $json );
		}
	}
} */

/**
 * Complete booking 
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_booking_complete' ) ) {

	function doccure_booking_complete() {
		global $woocommerce ,$doccure_options,$current_user;

		$date_formate		= get_option('date_format');
		$time_format 		= get_option('time_format');
		if(!isset($_SESSION)){ session_start(array('user_data'));}
		
		$json					= array();
		$author_id 				= $current_user->ID;
		$user_data				= isset($_SESSION['user_data']) ? $_SESSION['user_data'] : array();
		
		$booking_verification	= !empty( $doccure_options['booking_verification'] ) ? $doccure_options['booking_verification'] : '';
		$services				= !empty( $user_data['booking']['_booking_service'] ) ? $user_data['booking']['_booking_service'] : array();
		$doctor_id				= !empty( $user_data['booking']['_doctor_id'] ) ? doccure_get_linked_profile_id( $user_data['booking']['_doctor_id'] ) : '';
		
		$doct_hospital			= !empty( $user_data['booking']['_booking_hospitals'] ) ? $user_data['booking']['_booking_hospitals'] : '';
		$email					= !empty( $user_data['booking']['bk_email'] ) ? $user_data['booking']['bk_email'] : '';
		$am_consultant_fee		= get_post_meta( $doct_hospital ,'_consultant_fee',true);
		$price					= !empty( $am_consultant_fee ) ? $am_consultant_fee : 0;
		$linked_profile_id		= doccure_get_linked_profile_id($author_id);
		$am_specialities 		= doccure_get_post_meta( $doctor_id,'am_specialities');
		$am_specialities		= !empty( $am_specialities ) ? $am_specialities : array();
		$booking_slot			= !empty( $user_data['booking']['_booking_slot'] ) ? $user_data['booking']['_booking_slot'] : '';
		$appointment_date		= !empty( $user_data['booking']['_appointment_date'] ) ? $user_data['booking']['_appointment_date'] : '';
		$rand_val				= rand(1, 9999);
		
		$total_price = !empty($price) ? $price : 0;
		$new_services	= array();
		if(!empty($services)) {
			foreach( $services as $key => $vals ) {
				foreach( $vals as $k => $v ) {
					$new_priec				= !empty($am_specialities[$key][$k]['price']) ? $am_specialities[$key][$k]['price'] : 0;
					$new_services[$key][$k]	= $new_priec;
					$total_price			= $total_price  + $new_priec;
				}
			}
		}
		
		$payment_type			= !empty( $doccure_options['payment_type'] ) ? $doccure_options['payment_type'] : '';
		$enable_checkout_page	= !empty( $doccure_options['enable_checkout_page'] ) ? $doccure_options['enable_checkout_page'] : '';
		
		if( !empty($booking_verification)){
			$json['booking_verification'] 				= 'verification';
		} else {
			$json['booking_verification'] 				= 'skipe';
		}
		
		$other_name	= !empty( $user_data['booking']['other_name'] ) ? $user_data['booking']['other_name'] : '';
		$bk_email	= !empty( $user_data['booking']['bk_email'] ) ? $user_data['booking']['bk_email'] : '';
		$bk_phone	= !empty( $user_data['booking']['bk_phone'] ) ? $user_data['booking']['bk_phone'] : '';

		if(!empty($payment_type) && $payment_type == 'offline' && ( empty($enable_checkout_page) || $enable_checkout_page == 'hide') ){
			
			$myself			= !empty( $user_data['booking']['_myself'] ) ? $user_data['booking']['_myself'] : '';
			$contents		= !empty( $user_data['booking']['post_content'] ) ? $user_data['booking']['post_content'] : '';
			$post_title		= !empty( $doccure_options['appointment_prefix'] ) ? $doccure_options['appointment_prefix'] : esc_html__('APP#','doccure');
			$booking_post 	= array(
								'post_title'    => wp_strip_all_tags( $post_title ).'-'.$rand_val,
								'post_status'   => 'pending',
								'post_author'   => intval($author_id),
								'post_type'     => 'booking',
								'post_content'	=> $contents
							);
			
			$booking_id		= wp_insert_post( $booking_post );
			
			if(!empty($booking_id)){
				$relation	= !empty( $user_data['booking']['_relation'] ) ? $user_data['booking']['_relation'] : '';
				
				$post_meta['_with_patient']['relation']			= !empty( $relation ) ? $relation : '';
				$post_meta['_with_patient']['other_name']		= !empty( $other_name ) ? $other_name : '';
				$post_meta['_with_patient']['bk_email']			= !empty( $bk_email ) ? $bk_email : '';
				$post_meta['_with_patient']['bk_phone']			= !empty( $bk_phone ) ? $bk_phone : '';

				$name	= doccure_full_name($linked_profile_id);
				update_post_meta($booking_id,'_user_type','regular_users' );

				$am_consultant_fee					= get_post_meta( $doct_hospital ,'_consultant_fee',true);

				$price								= !empty( $am_consultant_fee ) ? $am_consultant_fee : 0;
				$post_meta['_services']				= $new_services;
				$post_meta['_consultant_fee']		= $price;
				$post_meta['_price']				= $total_price;
				$post_meta['_appointment_date']		= $appointment_date;
				$post_meta['_slots']				= $booking_slot;
				$post_meta['_hospital_id']			= $doct_hospital;

				$doctor_location	= !empty($doccure_options['doctor_location']) ? $doccure_options['doctor_location'] : '';
				if(!empty($doctor_location) && $doctor_location === 'hospitals'){
					$hospital_id		= get_post_meta( $doct_hospital, 'hospital_id', true );
				} else {
					$hospital_id		= $post_meta['_hospital_id'];
				}
				
				update_post_meta($booking_id,'_booking_type','doctor' );
				update_post_meta($booking_id,'_price',$total_price );
				update_post_meta($booking_id,'_hospital_id',$hospital_id );
				update_post_meta($booking_id,'_doctor_id',$doctor_id );
				update_post_meta($booking_id,'_patient_id',$author_id );
				update_post_meta($booking_id,'_am_booking',$post_meta );
				update_post_meta($booking_id,'_product_rand_offline',$rand_val );

				update_post_meta($booking_id,'_appointment_date',$post_meta['_appointment_date'] );
				update_post_meta($booking_id,'_booking_service',$post_meta['_services'] );
				update_post_meta($booking_id,'_booking_slot',$post_meta['_slots'] );
				update_post_meta($booking_id,'_booking_hospitals',$post_meta['_hospital_id'] );
				
				update_post_meta($booking_id,'bk_username',$other_name );
				update_post_meta($booking_id,'bk_email',$bk_email );
				update_post_meta($booking_id,'bk_phone',$bk_phone );

				if (class_exists('doccure_Email_helper')) {
					$emailData['user_name']		= $name;
					$time						= !empty($post_meta['_slots']) ? explode('-',$post_meta['_slots']) : array();
					$start_time					= !empty($time[0]) ? date_i18n($time_format, strtotime('2016-01-01' .$time[0])) : '';
					$end_time					= !empty($time[1]) ? date_i18n($time_format, strtotime('2016-01-01' .$time[1])) : '';
					
					$emailData['doctor_name']	= doccure_full_name($doctor_id);
					$emailData['doctor_link']	= get_the_permalink($doctor_id);
					$emailData['hospital_name']	= doccure_full_name($hospital_id);
					$emailData['hospital_link']	= get_the_permalink($hospital_id);

					$emailData['appointment_date']	= !empty($post_meta['_appointment_date']) ? date_i18n($date_formate,strtotime($post_meta['_appointment_date'])) : '';
					$emailData['appointment_time']	= $start_time.' '.esc_html__('to','doccure').' '.$end_time;
					$emailData['price']				= doccure_price_format($total_price,'return');
					$emailData['consultant_fee']	= doccure_price_format($post_meta['_consultant_fee'],'return');
					$emailData['description']		= $contents;

					if (class_exists('doccureBookingNotify')) {
						$email_helper				= new doccureBookingNotify();
						$emailData['email']			= $email;
						$email_helper->send_request_email($emailData);
						$user_id					= doccure_get_linked_profile_id($doctor_id,'post');
						$user_details				= get_userdata($user_id);
						
						if( !empty($user_details->user_email) ){
							$emailData['email']			= $user_details->user_email;
							$email_helper->send_doctor_email($emailData);
						}
					}
				}
			}
			
			$json['type'] 				= 'success';
			$json['message'] 			= esc_html__( 'Your booking is successfully submited.', 'doccure' );
			$json['checkout_option']	= 'no';
			$json['booking_id']			= $booking_id;

			wp_send_json( $json );
		} else if( !empty( $payment_type ) ){
			
			$allow_booking_zero	= !empty($doccure_options['allow_booking_zero'] ) ? $doccure_options['allow_booking_zero'] : 'no';
			if(!empty($consultant_fee_require) && $consultant_fee_require === 'no'){
				if(empty($total_price)){
					$json['type'] 				= 'error';
					$json['message'] 			= esc_html__('Total price should not be 0', 'doccure');
					wp_send_json($json);
				}
			}

			///payment online
			$product_id			= doccure_get_booking_product_id();
			$woocommerce->cart->empty_cart(); 
			$is_cart_matched	= doccure_matched_cart_items($product_id);

			if ( isset( $is_cart_matched ) && $is_cart_matched > 0) {
				$json = array();

				$json['type'] 				= 'success';
				$json['message'] 			= esc_html__('You have already in cart, We are redirecting to checkout', 'doccure');
				$json['checkout_option'] 	= 'yes';
				$json['checkout_url'] 		= wc_get_checkout_url();
				wp_send_json($json);
			}
			
			$cart_meta					= array();
			$admin_shares 				= 0.0;

			if( isset( $doccure_options['admin_commision'] ) && $doccure_options['admin_commision'] > 0 ){
				$admin_shares 		= $total_price/100*$doccure_options['admin_commision'];
				$doctors_shares 	= $total_price - $admin_shares;
				$admin_shares 		= number_format($admin_shares,2,'.', '');
				$doctors_shares 	= number_format($doctors_shares,2,'.', '');
			} else{
				$admin_shares 		= 0.0;
				$doctors_shares 	= $total_price;
				$admin_shares 		= number_format($admin_shares,2,'.', '');
				$doctors_shares 	= number_format($doctors_shares,2,'.', '');
			}

			$cart_meta['service']			= $services;
			$cart_meta['consultant_fee']	= $am_consultant_fee;
			$cart_meta['price']				= $total_price;
			$cart_meta['slots']				= !empty( $user_data['booking']['_booking_slot'] ) ?  $user_data['booking']['_booking_slot'] : '';
			$cart_meta['appointment_date']	= !empty( $user_data['booking']['_appointment_date'] ) ?  $user_data['booking']['_appointment_date'] : '';
			$cart_meta['hospital']			= $doct_hospital;
			$cart_meta['doctor_id']			= $doctor_id;
			$cart_meta['content']			= !empty( $user_data['booking']['post_content'] ) ?  $user_data['booking']['post_content'] : '';
			$cart_meta['myself']			= !empty( $user_data['booking']['_myself'] ) ?  $user_data['booking']['_myself'] : '';

			$cart_meta['other_name']	= !empty( $user_data['booking']['other_name'] ) ? $user_data['booking']['other_name'] : '';
			$cart_meta['bk_email']		= !empty( $user_data['booking']['bk_email'] ) ? $user_data['booking']['bk_email'] : '';
			$cart_meta['bk_phone']		= !empty( $user_data['booking']['bk_phone'] ) ? $user_data['booking']['bk_phone'] : '';
			$cart_meta['relation']		= !empty( $user_data['booking']['_relation'] ) ?  $user_data['booking']['_relation'] : '';
			
			if( empty( $current_user->ID ) ) {
				$cart_meta['user_type']		= !empty( $user_data['booking']['user_type'] ) ?  $user_data['booking']['user_type'] : '';
				$cart_meta['full_name']		= !empty( $user_data['booking']['full_name'] ) ?  $user_data['booking']['full_name'] : '';
				$cart_meta['phone_number']	= !empty( $user_data['booking']['phone_number'] ) ?  $user_data['booking']['phone_number'] : '';
				$cart_meta['email']			= !empty( $user_data['booking']['email'] ) ?  $user_data['booking']['email'] : '';
			}

			$cart_data = array(
				'product_id' 		=> $product_id,
				'cart_data'     	=> $cart_meta,
				'price'				=> doccure_price_format($price,'return'),
				'payment_type'     	=> 'bookings'
			);
			
			$cart_data['admin_shares']		= $admin_shares;
			$cart_data['doctors_shares']	= $doctors_shares;
			$cart_data['doctor_id']			= $doctor_id;
			$cart_data['patient_id']		= $author_id;

			$woocommerce->cart->empty_cart();
			$cart_item_data = $cart_data;
			WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);
			

			$json['type'] 				= 'success';
			$json['message'] 			= esc_html__('Please wait you are redirecting to checkout page.', 'doccure');
			$json['checkout_option'] 	= 'yes';
			$json['checkout_url']		= wc_get_checkout_url();
			wp_send_json($json);

		}
	}
}

/**
 * @Check user if logged in
 * @return {}
 */
if (!function_exists('doccure_validate_user')) {
	function doccure_validate_user($message=''){
		$json = array();

		if (!is_user_logged_in()) {
			$json['type']	    =  "error";
			$json['message']	=  esc_html__("You must be logged in to perform this action",'doccure' );;
			wp_send_json($json);
		}
	}
}

/**
 * Upload temp files to WordPress media
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_temp_upload_to_media')) {
    function doccure_temp_upload_to_media($file_url, $post_id) {
		global $wp_filesystem;
		if (empty($wp_filesystem)) {
			require_once (ABSPATH . '/wp-admin/includes/file.php');
			WP_Filesystem();
		}
		
        $json   =  array();
        $upload_dir = wp_upload_dir();
		$folderRalativePath = $upload_dir['baseurl']."/doccure-temp";
		$folderAbsolutePath = $upload_dir['basedir']."/doccure-temp";

		$args = array(
			'timeout'     => 15,
			'headers' => array('Accept-Encoding' => ''),
			'sslverify' => false
		);
		
		$response   	= wp_remote_get( $file_url, $args );
		$file_data		= wp_remote_retrieve_body($response);
		
		if(empty($file_data)){
			$json['attachment_id']  = '';
			$json['url']            = '';
			$json['name']			= '';
			return $json;
		}
		
        $filename 		= basename($file_url);
		
        if (wp_mkdir_p($upload_dir['path'])){
			 $file = $upload_dir['path'] . '/' . $filename;
		}  else {
            $file = $upload_dir['basedir'] . '/' . $filename;
		}

		//Rename file before update
		if (file_exists($file)) { 
			$i			= 1; 
			$new_path	= $file;

			while (file_exists($new_path)) { 
				$extension 		= pathinfo($file, PATHINFO_EXTENSION); 
				$actual_filename 	= pathinfo($file, PATHINFO_FILENAME); 
				$new_filename 	= $actual_filename . '-' . $i . '.' . $extension; 
				$new_path 		= $upload_dir['path'] . '/' . $new_filename; 
				$i++;  
			}
			
			$file	= $new_path;
		}
		
		$filename 			= basename($file);
		$actual_filename 	= pathinfo($file, PATHINFO_FILENAME); 
		
		//put content to the file
		file_put_contents($file, $file_data);
		
        $wp_filetype = wp_check_filetype($filename, null);
        $attachment = array(
            'post_mime_type' 	=> $wp_filetype['type'],
            'post_title' 		=> sanitize_file_name($actual_filename),
            'post_content' 		=> '',
            'post_status' 		=> 'inherit'
        );
        
        $attach_id = wp_insert_attachment($attachment, $file, $post_id);

        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $file);
        wp_update_attachment_metadata($attach_id, $attach_data);
        
        $json['attachment_id']  = $attach_id;
        $json['url']            = $upload_dir['url'] . '/' . basename( $filename );
		$json['name']			= $filename;
		$target_path = $folderAbsolutePath . "/" . $filename;
        unlink($target_path); //delete file after upload
        return $json;
    }
}

/**
 * Prepare social sharing links for job
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_get_term_name') ){
    function doccure_get_term_name($term_id = '', $taxonomy = ''){
        if( !empty( $term_id ) && !empty( $taxonomy ) ){
            if(is_string($term_id)){
				$term = get_term_by( 'slug', $term_id, $taxonomy);  
			}else {
				$term = get_term_by( 'id', $term_id, $taxonomy);  
			}
			
            if( !empty( $term ) ){
                return $term->name;
            }
        }
        return '';
    }
}

/**
 * Get waiting time
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists(  'doccure_get_waiting_time' ) ) {
	function doccure_get_waiting_time(){
		$list	= array(
			'1' 	=> esc_html__('0 < 15 min','doccure'),
			'2' 	=> esc_html__('15 to 30 min','doccure'),
			'3' 	=> esc_html__('30 to 1 hr','doccure'),
			'4' 	=> esc_html__('More then hr','doccure')
		);
		
		return $list;
	}
}

/**
 * Get user review meta data
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_get_review_data')) {

    function doccure_get_review_data($user_id, $review_key = '', $type = '') {
        $review_meta = get_user_meta($user_id, 'review_data', true);
        if ($type === 'value') {
            return !empty($review_meta[$review_key]) ? $review_meta[$review_key] : '';
        }
        return !empty($review_meta) ? $review_meta : array();
    }

}

/**
 * Get Average Ratings
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_get_everage_rating')) {

    function doccure_get_everage_rating($user_id = '') {
		$data = array();
        $meta_query_args = array('relation' => 'AND');
        $meta_query_args[] = array(
            'key' 		=> 'user_to',
            'value' 	=> $user_id,
            'compare' 	=> '=',
            'type' 		=> 'NUMERIC'
        );

        $args = array('posts_per_page' => -1,
            'post_type' 		=> 'reviews',
            'post_status' 		=> 'publish',
            'order' 			=> 'ASC',
        );

        $args['meta_query'] = $meta_query_args;

        $average_rating = 0;
        $total_rating   = 0;
		
        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) : $query->the_post();
                global $post;
                $user_rating = get_post_meta($post->ID, 'user_rating', true);
			
                $average_rating = $average_rating + $user_rating;
                $total_rating++;

            endwhile;
            wp_reset_postdata();
        }

        $data['wt_average_rating'] 			= 0;
        $data['wt_total_rating'] 			= 0;
        $data['wt_total_percentage'] 		= 0;
		
        if (isset($average_rating) && $average_rating > 0) {
            $data['wt_average_rating'] 			= $average_rating / $total_rating;
            $data['wt_total_rating'] 			= $total_rating;
            $data['wt_total_percentage'] 		= ( $average_rating / $total_rating) * 5;
        }

        return $data;
    }

}

/**
 * Count items in array
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_count_items')) {
    function doccure_count_items($items) {
        if( is_array($items) ){
			return count($items);
		} else{
			return 0;
		}
    }
}

/**
 * Get doctor Ratings Headings
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_doctor_ratings' ) ) {
	function doccure_doctor_ratings(){
		global $doccure_options;
		if ( $doccure_options ) {
			$ratings_headings	= !empty( $doccure_options['feedback_questions'] ) ? $doccure_options['feedback_questions'] : array();
			
			if( !empty( $ratings_headings ) ){
				$ratings_headings = array_filter($ratings_headings);
				$ratings_headings = array_combine(array_map('sanitize_title', $ratings_headings), $ratings_headings);
				return $ratings_headings;
			} else{
				return array();
			}
			
		} else {
			return array();
		}
	}
}

/**
 * Get search page uri
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_get_search_page_uri' ) ) {
    function doccure_get_search_page_uri( $type = '' ) {
		global $doccure_options;
		$tpl_dashboard 	= !empty( $doccure_options['dashboard_tpl'] ) ? get_permalink( (int) $doccure_options['dashboard_tpl']) : '';
		$tpl_search 	= !empty( $doccure_options['search_result_page'] ) ? get_permalink( (int) $doccure_options['search_result_page']) : '';
               
        $search_page = '';
		
        if ( !empty( $type ) && ( $type === 'doctors' || $type === 'hospitals' ) ) {
            $search_page = esc_url( $tpl_search );
        }  elseif ( !empty( $type ) && $type === 'dashboard' ) {
            $search_page = esc_url( $tpl_dashboard ) ;           
        }
        
        return $search_page;
    }
}

/**
 * Redirect page URL
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_redirect_after_login_page' ) ) {
    function doccure_redirect_after_login_page( $user_id = '' ) {
		global $doccure_options;
		$user_type		= !empty($user_id) ? apply_filters('doccure_get_user_type', $user_id ) : '';
		$page_posts		= array();
				
        if ( !empty( $user_type ) && ( $user_type == 'doctors') ) {
			$doctors_redirect_page 	= !empty( $doccure_options['doctors_redirect_page'] ) ? $doccure_options['doctors_redirect_page'] : '';
			
            $page_posts 			= apply_filters('doccure_doctor_redirect_after_login',$doctors_redirect_page); 
        }  elseif ( !empty( $user_type ) && $user_type === 'hospitals' ) {
            $hospital_redirect_page 	= !empty( $doccure_options['hospital_redirect_page'] ) ? $doccure_options['hospital_redirect_page'] : '';
            $page_posts 				= apply_filters('doccure_doctor_redirect_after_login',$hospital_redirect_page);           
        }
		
        $page_redirect	=  doccure_Profile_Menu::doccure_profile_menu_link('insights', $user_id, true);
		if( !empty($page_posts) ){
			if( !empty($page_posts['ref']) && !empty($page_posts['mode'])){
				$page_redirect	= doccure_Profile_Menu::doccure_profile_menu_link($page_posts['ref'], $user_id,true,$page_posts['mode']);
			} else if( !empty($page_posts['ref']) ){
				$page_redirect	=  doccure_Profile_Menu::doccure_profile_menu_link($page_posts['ref'], $user_id, true);
			}
		}
        return $page_redirect;
    }
}

/**
 * Match Cart items
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_matched_cart_items')) {

    function doccure_matched_cart_items($product_id) {
        // Initialise the count
        $count = 0;

        if (!WC()->cart->is_empty()) {
            foreach (WC()->cart->get_cart() as $cart_item):
                $items_id = $cart_item['product_id'];

                // for a unique product ID (integer or string value)
                if ($product_id == $items_id) {
                    $count++; // incrementing the counted items
                }
            endforeach;
            // returning counted items 
            return $count;
        }

        return $count;
    }

}

/**
 * Get package type
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_get_package_type')) {

	 function doccure_get_package_type($key, $value) {
		global $wpdb;
		$meta_query_args = array();
		$args = array(
			'post_type' 			=> 'product',
			'posts_per_page' 		=> 1,
			'order' 				=> 'DESC',
			'orderby' 				=> 'ID',
			'post_status' 			=> 'publish',
			'ignore_sticky_posts' 	=> 1
		);
		 
		$meta_query_args[] = array(
			'key' 			=> $key,
			'value' 		=> $value,
			'compare' 		=> '=',
		);	
		 
		$query_relation 		= array('relation' => 'AND',);
		$meta_query_args 		= array_merge($query_relation, $meta_query_args);
		$args['meta_query'] 	= $meta_query_args;
		
		$trial_product = get_posts($args);
		
		if (!empty($trial_product)) {
            return (int) $trial_product[0]->ID;
        } else{
			 return 0;
		}
	}
	
}

/**
 * Get subscription metadata
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_get_subscription_metadata')) {

    function doccure_get_subscription_metadata($key = '', $user_id='') {
		$listing_type	= doccure_theme_option('listing_type');
		if( $listing_type === 'free' ){
			return 'yes';
		}

        $dc_subscription 	= get_user_meta($user_id, 'dc_subscription', true);
		$current_date 		= current_time('mysql');
        if ( is_array( $dc_subscription ) && !empty( $dc_subscription[$key] ) ) {			
			if (!empty($dc_subscription['subscription_package_string']) && $dc_subscription['subscription_package_string'] > strtotime($current_date)) {
				return $dc_subscription[$key];
			} else {
				return '';
			}
        } else {
			return '';	
		}
    }

}

/**
 * Update Package vaule
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_update_package_attribute_value' ) ) {

	function doccure_update_package_attribute_value( $user_id, $attribute,$min_val=1) {
		$dc_subscription 	= get_user_meta($user_id, 'dc_subscription', true);
		$attribut_val		= !empty($dc_subscription) ? intval($dc_subscription[$attribute]) : 0;
		if(!empty($attribute) && !empty($dc_subscription)){
			$dc_subscription[$attribute]	= $attribut_val - $min_val;
			update_user_meta( $user_id, 'dc_subscription',$dc_subscription );
		}
	}
}

/**
 * Get Packages Type 
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_packages_types' ) ) {

	function doccure_packages_types( $post = '') {
		if ( !empty( $post ) ) {
			$package_type	= get_post_meta( $post->ID , 'package_type', true);
		}
		
		$packages						= array();
		$packages[0]					= esc_html__('Package Type', 'doccure');
		$packages['doctors']			= esc_html__('For doctors', 'doccure');
		$trail_doctors_package_id		= doccure_get_package_type( 'package_type','trail_doctors');
		
		if( empty($trail_doctors_package_id ) || ($trail_doctors_package_id == $post->ID) ) {
			$packages['trail_doctors']		= esc_html__('Trial for doctors', 'doccure');
		}
		
		return $packages;
	}
}

/**
 * Get Pakages Featured attribute
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_get_pakages_features_attributes')) {

    function doccure_get_pakages_features_attributes( $key ='' , $attr = 'title' ) {
		$features		= doccure_get_pakages_features();
		if( !empty ( $key ) && !empty ( $attr )) {
			$attribute	= $features[$key][$attr];
		} else {
			$attribute ='';
		}
		
		return $attribute;
	}
}

/**
 * Get user profile ID
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_get_linked_profile_id')) {

    function doccure_get_linked_profile_id($user_identity, $type='users') {
		if( $type === 'post') {
			$linked_profile   	= get_post_meta($user_identity, '_linked_profile', true);
		}else {
			$linked_profile   	= get_user_meta($user_identity, '_linked_profile', true);
		}
		
        $linked_profile	= !empty( $linked_profile ) ? $linked_profile : '';
		
        return intval( $linked_profile );
    }
}

/**
 * Filter dashboard menu
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_get_dashboard_menu' ) ) {
	function doccure_get_dashboard_menu() {
		global $current_user;
		
		$menu_settings = get_option( 'dc_dashboard_menu_settings' );
		
		$list	= array(		
			'insights'	=> array(
				'title' => esc_html__('Dashboard','doccure'),
				'type'	=> 'none'
			),
			'appointments-listing'	=> array(
				'title' => esc_html__('Appointment Listing','doccure'),
				'type'	=> 'doctors'
			),
			'appointments-listings'	=> array(
				'title' => esc_html__('Appointment Listing','doccure'),
				'type'	=> 'regular_users'
			),
			'appointments-settings'	=> array(
				'title' => esc_html__('Available Timings','doccure'),
				'type'	=> 'doctors'
			),
			/*'view-profile'	=> array(
				'title' => esc_html__('View My Profile','doccure'),
				'type'	=> 'doctors'
			),
			*/
			'chat'	=> array(
				'title' => esc_html__('Messages','doccure'),
				'type'	=> 'none'
			),
			'profile-settings'	=> array(
				'title' => esc_html__('Edit my profile','doccure'),
				'type'	=> 'none'
			),
			'specialities'	=> array(
				'title' => esc_html__('Specialities &amp; Services','doccure'),
				'type'	=> 'none'
			),
			
			
			'manage-article'	=> array(
				'title' => esc_html__('Manage Articles','doccure'),
				'type'	=> 'doctors'
			),
			
			'payouts-settings'	=> array(
				'title' => esc_html__('Payouts Settings','doccure'),
				'type'	=> 'doctors'
			), 
			'manage-team'	=> array(
				'title' => esc_html__('Manage Team','doccure'),
				'type'	=> 'hospitals'
			),
			
			'saved'	=> array(
				'title' => esc_html__('My Saved Items','doccure'),
				'type'	=> 'none'
			),
			
			'packages'	=> array(
				'title' => esc_html__('Packages','doccure'),
				'type'	=> 'doctors'
			),
			
			'invoices'	=> array(
				'title' => esc_html__('Invoices','doccure'),
				'type'	=> 'doctors'
			),
			
			'invoices-regular-users'	=> array(
				'title' => esc_html__('Invoices','doccure'),
				'type'	=> 'regular_users'
			),
			'account-settings'	=> array(
				'title' => esc_html__('Change Password','doccure'),
				'type'	=> 'none'
			),
			
			'logout'	=> array(
				'title' => esc_html__('Logout','doccure'),
				'type'	=> 'none'
			)
		);
		
		$remove_payouts	= doccure_theme_option('enable_checkout_page');
		$payment_type	= doccure_theme_option('payment_type');
		
		
		if( !empty($payment_type) && $payment_type == 'offline' ){
			if( empty($remove_payouts) || $remove_payouts == 'hide'){
				unset($list['payouts-settings']);
			}
		}

		$listing_type	= doccure_theme_option('listing_type');
		if( !empty($listing_type) && $listing_type ==='free' ){
			unset($list['packages']);
		}

		$final_list	= !empty( $menu_settings ) ? $menu_settings : $list;
		$menu_list 	= apply_filters('doccure_filter_dashboard_menu',$final_list);
		return $menu_list;
	}
}


/**
 * Get doctor avatar
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_get_doctor_avatar' ) ) {
	function doccure_get_doctor_avatar( $sizes = array(), $user_identity = '' ) {
		extract( shortcode_atts( array(
			"width" => '200',
			"height" => '200',
		), $sizes ) );
		
		global $doccure_options;
		
		$default_avatar = !empty($doccure_options['default_doctor_avatar'])  ? $doccure_options['default_doctor_avatar'] : array();
		
		$thumb_id 		= get_post_thumbnail_id( $user_identity );
		
		if ( !empty( $thumb_id ) ) {
			$thumb_url = wp_get_attachment_image_src( $thumb_id, array( $width, $height ), true );
			if ( $thumb_url[1] == $width and $thumb_url[2] == $height ) {
				return !empty( $thumb_url[0] ) ? $thumb_url[0] : '';
			} else {
				$thumb_url = wp_get_attachment_image_src( $thumb_id, 'full', true );
				if (strpos($thumb_url[0],'media/default.png') !== false) {
					return '';
				} else{
					return !empty( $thumb_url[0] ) ? $thumb_url[0] : '';
				}
			}
		} else {
			if ( !empty( $default_avatar['id'] ) ) {
				$thumb_url = wp_get_attachment_image_src( $default_avatar['id'], array( $width, $height ), true );

				if ( $thumb_url[1] == $width and $thumb_url[2] == $height ) {
					return $thumb_url[0];
				} else {
					$thumb_url = wp_get_attachment_image_src( $default_avatar['id'], "full", true );
					if (strpos($thumb_url[0],'media/default.png') !== false) {
						return '';
					} else{
						if ( !empty( $thumb_url[0] ) ) {
							return $thumb_url[0];
						} else {
							return false;
						}
					}
				}
			} else {
				return false;
			}
		}
	}
}

/**
 * Get doctor avatar
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_get_others_avatar' ) ) {
	function doccure_get_others_avatar( $sizes = array(), $user_identity = '' ) {
		extract( shortcode_atts( array(
			"width" => '100',
			"height" => '100',
		), $sizes ) );
		
		global $doccure_options;
		
		$default_avatar = !empty($doccure_options['default_others_users'])  ? $doccure_options['default_others_users'] : array();
		
		$thumb_id 		= get_post_thumbnail_id( $user_identity );
		
		if ( !empty( $thumb_id ) ) {
			$thumb_url = wp_get_attachment_image_src( $thumb_id, array( $width, $height ), true );
			if ( $thumb_url[1] == $width and $thumb_url[2] == $height ) {
				return !empty( $thumb_url[0] ) ? $thumb_url[0] : '';
			} else {
				$thumb_url = wp_get_attachment_image_src( $thumb_id, 'full', true );
				if (strpos($thumb_url[0],'media/default.png') !== false) {
					return '';
				} else{
					return !empty( $thumb_url[0] ) ? $thumb_url[0] : '';
				}
			}
		} else {
			if ( !empty( $default_avatar['id'] ) ) {
				$thumb_url = wp_get_attachment_image_src( $default_avatar['id'], array( $width, $height ), true );

				if ( $thumb_url[1] == $width and $thumb_url[2] == $height ) {
					return $thumb_url[0];
				} else {
					$thumb_url = wp_get_attachment_image_src( $default_avatar['id'], "full", true );
					if (strpos($thumb_url[0],'media/default.png') !== false) {
						return '';
					} else{
						if ( !empty( $thumb_url[0] ) ) {
							return $thumb_url[0];
						} else {
							return false;
						}
					}
				}
			} else {
				return false;
			}
		}
	}
}

/**
 * User verification check
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists(  'doccure_get_username' ) ) {
	function doccure_get_username( $user_id = '' , $linked_profile = '' ){
		
		if( !empty( $linked_profile ) ){
			return get_the_title($linked_profile);
		} 
		
		if ( empty($user_id) ) {
            return esc_html__('unnamed', 'doccure');
        }
		
        $userdata = get_userdata($user_id);
        $user_role = '';
        if (!empty($userdata->roles[0])) {
            $user_role = $userdata->roles[0];
        }

        if (!empty($user_role) && $user_role === 'doctors' || $user_role === 'hospitals' || $user_role === 'regular_users' ) {
			$linked_profile   	= doccure_get_linked_profile_id($user_id);
			if( !empty( $linked_profile ) ){
				return doccure_full_name($linked_profile);
			} else{
				if (!empty($userdata->first_name) && !empty($userdata->last_name)) {
					return $userdata->first_name . ' ' . $userdata->last_name;
				} else if (!empty($userdata->first_name) && empty($userdata->last_name)) {
					return $userdata->first_name;
				} else if (empty($userdata->first_name) && !empty($userdata->last_name)) {
					return $userdata->last_name;
				} else {
					return esc_html__('No Name', 'doccure');
				}
			}
			
		} else{
			if (!empty($userdata->first_name) && !empty($userdata->last_name)) {
                return $userdata->first_name . ' ' . $userdata->last_name;
            } else if (!empty($userdata->first_name) && empty($userdata->last_name)) {
                return $userdata->first_name;
            } else if (empty($userdata->first_name) && !empty($userdata->last_name)) {
                return $userdata->last_name;
            } else {
                return esc_html__('No Name', 'doccure');
            }
		}
	}
}

/**
 * Report reasons
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists(  'doccure_get_report_reasons' ) ) {
	function doccure_get_report_reasons(){
		$list	= array(
			'fake' 		=> esc_html__('This is the fake', 'doccure'),
			'bahavior' 	=> esc_html__('Their behavior is inappropriate or abusive', 'doccure'),
			'Other' 	=> esc_html__('Other', 'doccure'),
		);
		
		$list	= apply_filters('doccure_filter_reasons',$list);
		return $list;
	}
}

/**
 * Get user avatar
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_get_hospital_avatar' ) ) {
	function doccure_get_hospital_avatar( $sizes = array(), $user_identity = '' ) {
		global $doccure_options;
		extract( shortcode_atts( array(
			"width" => '100',
			"height" => '100',
		), $sizes ) );

		$default_avatar = !empty( $doccure_options['default_hospital_image'] ) ? $doccure_options['default_hospital_image'] : '';

		$thumb_id = get_post_thumbnail_id( $user_identity );
		
		if ( !empty( $thumb_id ) ) {
			$thumb_url = wp_get_attachment_image_src( $thumb_id, array( $width, $height ), true );
			if ( $thumb_url[1] == $width and $thumb_url[2] == $height ) {
				return !empty( $thumb_url[0] ) ? $thumb_url[0] : '';
			} else {
				$thumb_url = wp_get_attachment_image_src( $thumb_id, 'full', true );
				if (strpos($thumb_url[0],'media/default.png') !== false) {
					return '';
				} else{
					return !empty( $thumb_url[0] ) ? $thumb_url[0] : '';
				}
			}
		} else {
			if ( !empty( $default_avatar['id'] ) ) {
				$thumb_url = wp_get_attachment_image_src( $default_avatar['id'], array( $width, $height ), true );

				if ( $thumb_url[1] == $width and $thumb_url[2] == $height ) {
					return $thumb_url[0];
				} else {
					$thumb_url = wp_get_attachment_image_src( $default_avatar['id'], "full", true );
					if (strpos($thumb_url[0],'media/default.png') !== false) {
						return '';
					} else{
						if ( !empty( $thumb_url[0] ) ) {
							return $thumb_url[0];
						} else {
							return false;
						}
					}
				}
			} else {
				return false;
			}
		}
	}
}

/**
 * Add http from URL
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_add_http')) {

    function doccure_add_http($url) {
        $protolcol = is_ssl() ? "https" : "http";
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = $protolcol . ':' . $url;
        }
        return $url;
    }

}

/**
 * Get page id by slug
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_get_page_by_slug')) {

    function doccure_get_page_by_slug($slug = '', $post_type = 'post', $return = 'id') {
        $args = array(
            'name' => $slug,
            'post_type' => $post_type,
            'post_status' => 'publish',
            'posts_per_page' => 1
        );

        $post_data = get_posts($args);

        if (!empty($post_data)) {
            return (int) $post_data[0]->ID;
        }

        return false;
    }

}

/**
 * Add http from URL
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_matched_cart_items')) {

    function doccure_matched_cart_items($product_id) {
        // Initialise the count
        $count = 0;

        if (!WC()->cart->is_empty()) {
            foreach (WC()->cart->get_cart() as $cart_item):
                $items_id = $cart_item['product_id'];

                // for a unique product ID (integer or string value)
                if ($product_id == $items_id) {
                    $count++; // incrementing the counted items
                }
            endforeach;
            // returning counted items 
            return $count;
        }

        return $count;
    }

}

/**
 * Get the terms
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_get_taxonomy_options')) {

    function doccure_get_taxonomy_options($current = '', $taxonomyName = '', $parent = '') {
		
		if( taxonomy_exists($taxonomyName) ){
			//This gets top layer terms only.  This is done by setting parent to 0.  
			$parent_terms = get_terms($taxonomyName, array('parent' => 0, 'orderby' => 'slug', 'hide_empty' => false));


			$options = '';
			if (!empty($parent_terms)) {
				foreach ($parent_terms as $pterm) {
					$selected = '';
					if (!empty($current) && is_array($current) && in_array($pterm->slug, $current)) {
						$selected = 'selected';
					} else if (!empty($current) && !is_array($current) && $pterm->slug == $current) {
						$selected = 'selected';
					}

					$options .= '<option ' . $selected . ' value="' . $pterm->slug . '">' . $pterm->name . '</option>';
				}
			}

			echo do_shortcode($options);
		}else{
			echo '';
		}
    }

}

/**
 * Get taxonomy array
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_get_taxonomy_array')) {

    function doccure_get_taxonomy_array($taxonomyName = '',$parent='') {
		
		if( taxonomy_exists($taxonomyName) ){
			if(!empty( $parent )){
				return get_terms($taxonomyName, array('parent' => $parent, 'orderby' => 'slug', 'hide_empty' => false));
			} else{
				return get_terms($taxonomyName, array('orderby' => 'slug', 'hide_empty' => false));
			}
			
		} else{
			return array();
		}  
	}
}

/**
 * List user specilities and services
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */

if( !function_exists(  'doccure_list_services_with_specility' ) ) {
	
	function doccure_list_services_with_specility( $profile_id = ''){
		$specialities_array	= array();
		
		if( !empty($profile_id) ){
			$am_specialities 		= doccure_get_post_meta( $profile_id,'am_specialities');
			
			if( !empty( $am_specialities ) ) {
				foreach( $am_specialities as $key => $values ){ 
					$specialities_title	= doccure_get_term_name($key ,'specialities');

					$logo 			= get_term_meta( $key, 'logo', true );
					$current_logo	= !empty( $logo['url'] ) ? esc_url($logo['url']) : '';
					$specialities_array[$key]['id']			= $key;
					$specialities_array[$key]['title']		= $specialities_title;
					$specialities_array[$key]['logo']		= $current_logo;

					$services_array		= array();
					if( !empty( $values ) ) {
						$service_index	= 0;
						foreach( $values as $index => $val ) {
							$service_index	++;
							$service_title							= doccure_get_term_name($index ,'services');
							$services_array[$service_index]['title']		= $service_title;
							$services_array[$service_index]['service_id']	= $index;
							$services_array[$service_index]['price']		= !empty($val['price']) ? $val['price'] : '';
							$services_array[$service_index]['description']	= !empty($val['description']) ? $val['description'] : '';
						}
					}
					$specialities_array[$key]['services']	= array_values($services_array);
				}
			}
		}
		return $specialities_array;
	}
}


/**
 * Get the list hospital
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_get_list_hospital')) {
    function doccure_get_list_hospital( $type = '', $author = '') {
        $args = array(
				'posts_per_page' 	=> '-1',
				'post_type' 		=> $type,
				'post_status' 		=> 'publish',
				'suppress_filters' 	=> false,
				'author'			=> $author
			);
		
        $options = '';
        $cust_query = get_posts($args);

        if (!empty($cust_query)) {
            foreach ($cust_query as $key => $dir) {
				$hospital_id	= get_post_meta( $dir->ID, 'hospital_id',true);
                $options .= '<option value="' . $dir->ID . '">' . get_the_title($hospital_id) . '</option>';
            }
        }

        echo do_shortcode($options);
    }

}

/**
 * Get time slots for booking app
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_get_time_slots_slots')) {
    function doccure_get_time_slots_slots( $post_id = '', $day = '',$date ='') {
		$time_format 	= get_option('time_format');
		$slots			= get_post_meta($post_id,'am_slots_data',true);
		$slot_list		= array();
		if( !empty( $slots ) ){
			$slot_array	= $slots[$day]['slots'];
			
			if( !empty( $slot_array ) ) {
				$slots_array	= array();
				foreach( $slot_array as $key	=> $val ) {
					$post_meta		= array(
											'_appointment_date'		=> $date,
											'_booking_slot' 		=> $key ,
											'_booking_hospitals' 	=> $post_id ,
										   );
					$count_posts	= doccure_get_total_posts_by_multiple_meta('booking',array('publish','pending'),$post_meta);
					
					$spaces			= $val['spaces'];
					if( $count_posts >= $spaces ) { 
						$disabled	= 'disabled'; 
						$spaces		= 0;
					} else { 
						$spaces		= $spaces - $count_posts;
						$disabled 	= ''; 
					}
					
					$slot_key_val 	= explode('-', $key);
					$slots_array['start_time']		= !empty($slot_key_val[0]) ? date($time_format, strtotime('2016-01-01' . $slot_key_val[0])) : '';
					$slots_array['end_time']		= !empty($slot_key_val[1]) ? date($time_format, strtotime('2016-01-01' . $slot_key_val[1])) : '';
					$slots_array['key']				= $key;
					$slots_array['status']			= $disabled;
					$slots_array['spaces']			= $spaces;
					$slot_list[]					= $slots_array;	
				}
			}
		} 	

        return $slot_list;
    }

}

/**
 * Get time slots for booking
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_get_time_slots_spaces')) {
    function doccure_get_time_slots_spaces( $post_id = '', $day = '',$date ='') {
		$time_format 	= get_option('time_format');
		$date_format 	= get_option('date_format');
		
		$current_time	= current_time('timestamp' );
		$current_time	= date('Hi',$current_time);
		$slots			= get_post_meta($post_id,'am_slots_data',true);
		$slots_html		= '';
		
		if( !empty( $slots ) ){
			$slot_array	= !empty($slots[$day]['slots']) ? $slots[$day]['slots'] : array();
			if( !empty( $slot_array ) ) {
				foreach( $slot_array as $key	=> $val ) {
					$post_meta		= array(
											'_appointment_date'		=> $date,
											'_booking_slot' 		=> $key ,
											'_booking_hospitals' 	=> $post_id ,
										   );
					$count_posts			= doccure_get_total_posts_by_multiple_meta('booking',array('publish','pending'),$post_meta);
					$slot_key_val 			= explode('-', $key);
					$spaces					= !empty($val['spaces']) ? $val['spaces'] : 0;
					$current_number 		= strtotime(date_i18n($date_format));
					$date_number			= strtotime($date);
					$slotnumber				= !empty($slot_key_val[0]) ? $slot_key_val[0] : 0;
					
					if( ($count_posts >= $spaces) ) { 
						$disabled	= 'disabled'; 
						$spaces		= 0;
					} else { 
						$spaces		= $spaces - $count_posts;
						$disabled 	= ''; 
					}
					
					if( ( $current_number == $date_number ) && ($current_time >= $slotnumber) ){
						$disabled	= 'disabled';
					}
					if( ($spaces >= '1') ) { 
					$slots_html	.= '<span class="dc-radio next-step"> ';
						$slots_html	.= '<input type="radio" id="firstavailableslot'.$key.'" name="booking_slot" value="'.$key.'" '.$disabled.'>';
						$slots_html	.= '<label for="firstavailableslot'.$key.'"><span>'.date($time_format, strtotime('2016-01-01' . $slot_key_val[0])).'</span><em>'.esc_html__('Slots','doccure').':'.$spaces.'</em></label>';
					$slots_html	.= '</span>';
					}
				}
			}
		} 	

        return do_shortcode($slots_html);
    }

}

/**
 * Get total post by multiple meta
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_get_total_posts_by_multiple_meta')) {

    function doccure_get_total_posts_by_multiple_meta($type='booking',$status='',$metas='',$post_author='' ) {
		if( !empty( $metas ) ) {
			foreach( $metas as $key => $val ) {
				$meta_query_args[] = array(
					'key' 			=> $key,
					'value' 		=> $val,
					'compare' 		=> '='
				);
			}
		}
		
		$query_args = array(
			'posts_per_page'      => -1,
			'post_type' 	      => $type,
			'post_status'	 	  => $status,
			'ignore_sticky_posts' => 1
		);
		
		if(!empty ( $post_author ) ){
			$query_args['author']	= $post_author;
		}
		
		if (!empty($meta_query_args)) {
			$query_relation = array('relation' => 'AND',);
			$meta_query_args = array_merge($query_relation, $meta_query_args);
			$query_args['meta_query'] = $meta_query_args;
		}
		
        $query = new WP_Query($query_args);
        return $query->post_count;
    }
}

/**
 * Prepare Business Hours Settings
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_prepare_business_hours_settings')) {

    function doccure_prepare_business_hours_settings() {
        return array(
            'monday' 	=> esc_html__('Monday', 'doccure'),
            'tuesday' 	=> esc_html__('Tuesday', 'doccure'),
            'wednesday' => esc_html__('Wednesday', 'doccure'),
            'thursday' 	=> esc_html__('Thursday', 'doccure'),
            'friday' 	=> esc_html__('Friday', 'doccure'),
            'saturday' 	=> esc_html__('Saturday', 'doccure'),
            'sunday' 	=> esc_html__('Sunday', 'doccure')
        );
    }

}

/**
 * Get Week Array
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_get_week_array')) {

    function doccure_get_week_array() {
        return array(
          	'mon' => esc_html__('Monday', 'doccure'),
            'tue' => esc_html__('Tuesday', 'doccure'),
            'wed' => esc_html__('Wednesday', 'doccure'),
            'thu' => esc_html__('Thursday', 'doccure'),
            'fri' => esc_html__('Friday', 'doccure'),
            'sat' => esc_html__('Saturday', 'doccure'),
            'sun' => esc_html__('Sunday', 'doccure'),
        );
    }

}

/**
 * Get Week keys translation
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_get_week_keys_translation')) {

    function doccure_get_week_keys_translation($key='') {
        $list	= array(
					'mon' => esc_html__('Mon', 'doccure'),
					'tue' => esc_html__('Tue', 'doccure'),
					'wed' => esc_html__('Wed', 'doccure'),
					'thu' => esc_html__('Thu', 'doccure'),
					'fri' => esc_html__('Fri', 'doccure'),
					'sat' => esc_html__('Sat', 'doccure'),
					'sun' => esc_html__('Sun', 'doccure'),
				);
		
		return !empty($list[$key]) ? $list[$key] : '';
    }

}
/**
 * Time formate
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_date_24midnight')) {

    function doccure_date_24midnight($format, $ts) {
        if (date("Hi", $ts) == "0000") {
            $replace = array(
                "H" => "24",
                "G" => "24",
                "i" => "00",
            );

            return date(
                    str_replace(
                            array_keys($replace), $replace, $format
                    ), $ts - 60 // take a full minute off, not just 1 second
            );
        } else {
            return date($format, $ts);
        }
    }

}

/**
 * Get distance between two points
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_GetDistanceBetweenPoints')) {
	function doccure_GetDistanceBetweenPoints($latitude1, $longitude1, $latitude2, $longitude2, $unit = 'Km') {
		$unit	= doccure_get_distance_scale();
		
		$theta = $longitude1 - $longitude2;
		$distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
		$distance = acos($distance);
		$distance = rad2deg($distance);
		$distance = $distance * 60 * 1.1515; switch($unit) {
		  case 'Mi': break;
		  case 'Km' : $distance = $distance * 1.60934;
		}
		return (round($distance,2)).'&nbsp;'. strtolower( $unit );
	}
}

/**
 * Get distance between two points
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_get_distance_scale')) {
	function doccure_get_distance_scale() {
		global $doccure_options;
		$dir_distance_type = !empty( $doccure_options['dir_distance_type'] ) ? $doccure_options['dir_distance_type']: 'km';
		$unit = !empty( $dir_distance_type ) && $dir_distance_type === 'mi' ? 'Mi' : 'Km';
		
		return $unit;
	}
}

/**
 * Get min/max lat/long
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_get_min_max_lat_lon')) {
	function doccure_get_min_max_lat_lon(){
		global $doccure_options;
		$radius		= !empty( $_GET['geo_distance'] ) ? esc_html( $_GET['geo_distance'] ) : 10;
		$dir_distance_type = !empty( $doccure_options['dir_distance_type'] ) ? $doccure_options['dir_distance_type']: 'km';
		
		if ($dir_distance_type === 'km') {
			$radius = $radius * 0.621371;
		}

		$Latitude	= !empty( $_GET['lat'] ) ? esc_html( $_GET['lat'] ) : '';
		$Longitude	= !empty( $_GET['long'] ) ?  esc_html( $_GET['long'] ) : '';

		$minLat = $maxLat = $minLong = $maxLong = 0;
		if( !empty( $Latitude ) && !empty( $Longitude ) ){
			$zcdRadius = new RadiusCheck($Latitude, $Longitude, $radius);
			$minLat = $zcdRadius->MinLatitude();
			$maxLat = $zcdRadius->MaxLatitude();
			$minLong = $zcdRadius->MinLongitude();
			$maxLong = $zcdRadius->MaxLongitude();
		}
		
		$data	= array(
			'default_lat'   => $Latitude,
			'default_long'  => $Longitude,
			'minLat'  => $minLat,
			'maxLat'  => $maxLat,
			'minLong' => $minLong,
			'maxLong' => $maxLong,
		);
		
		return $data;
	}
}

/**
 * Get atitude and longitude for search
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_get_location_lat_long' ) ) {
	function doccure_get_location_lat_long() {
		global $doccure_options;
		$protocol 		= is_ssl() ? 'https' : 'http';
		$dir_longitude = !empty( $doccure_options['dir_longitude'] ) ? $doccure_options['dir_longitude']: '-0.1262362';
		$dir_latitude = !empty( $doccure_options['dir_latitude'] ) ? $doccure_options['dir_latitude']: '51.5001524';
		
		$current_latitude	= $dir_latitude;
		$current_longitude	= $dir_longitude;

		if( !empty( $_GET['lat'] ) && !empty( $_GET['long'] ) ){
			$current_latitude	= esc_html( $_GET['lat'] );
			$current_longitude	= esc_html( $_GET['long'] );
		} else{
			
			$args = array(
				'timeout'     => 15,
				'headers' => array('Accept-Encoding' => ''),
				'sslverify' => false
			);
			
			$address	= !empty($_GET['geo']) ?  $_GET['geo'] : '';
			$prepAddr	= str_replace(' ','+',$address);
			
			$url	    = $protocol.'://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false';
			$response   = wp_remote_get( $url, $args );
			$geocode	= wp_remote_retrieve_body($response);

			$output	  = json_decode($geocode);

			if( isset( $output->results ) && !empty( $output->results ) ) {
				$Latitude	 = $output->results[0]->geometry->location->lat;
				$Longitude   = $output->results[0]->geometry->location->lng;
			}
			
			if( !empty( $Latitude ) && !empty( $Longitude ) ){
				$current_latitude	= $Latitude;
				$current_longitude	= $Longitude;
			} else{
				$current_latitude	= $dir_latitude;
				$current_longitude	= $dir_longitude;
			}
		}
		
		$location	= array();
		
		$location['lat']	= $current_latitude;
		$location['long']	= $current_longitude;
		
		return $location;
	}
}

/**
 * Get woocommmerce currency settings
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_get_current_currency' ) ) {
	function doccure_get_current_currency() {
		$currency	= array();
		
		if (class_exists('WooCommerce')) {
			$currency['code']	= get_woocommerce_currency();
			$currency['symbol']	= get_woocommerce_currency_symbol();
		} else{
			$currency['code']	= 'USD';
			$currency['symbol']	= '$';
		}
		
		return $currency;
	}
}

/**
 * Get calendar date format
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_get_calendar_format' ) ) {
	function doccure_get_calendar_format() {
		global $doccure_options;
		$calendar_format = !empty( $doccure_options['calendar_format'] ) ? $doccure_options['calendar_format']: 'Y-m-d';
		
		return $calendar_format;
	}
}


/**
 * Get term by slug
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_get_term_by_type')) {

    function doccure_get_term_by_type($from = 'slug', $value = "", $taxonomy = 'sub_category', $return = 'id') {

        $term = get_term_by($from, $value, $taxonomy);
        if (!empty($term)) {
            if ($from === 'slug' && $return === 'id') {
                return $term->term_id;
            } elseif ($from === 'id' && $return === 'slug') {
                return $term->slug;
            } elseif ($from === 'name' && $return === 'id') {
                return $term->term_id;
            } elseif ($from === 'id' && $return === 'name') {
                return $term->name;
            } elseif ($from === 'name' && $return === 'slug') {
                return $term->slug;
            } elseif ($from === 'slug' && $return === 'name') {
                return $term->name;
            }elseif ($from === 'id' && $return === 'all') {
                return $term;
            } else {
                return $term->term_id;
            }
        }

        return false;
    }
}

/**
 * Get total post by user id
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_get_total_posts_by_user')) {

    function doccure_get_total_posts_by_user($user_id = '',$type='sp_ads',$status='publish') {
        if (empty($user_id)) {
            return 0;
        }

        $args = array(
			'posts_per_page'	=> '-1',
            'post_type' 		=> $type,
            'post_status' 		=> $status,
            'author' 			=> $user_id,
            'suppress_filters' 	=> false
        );
        $query = new WP_Query($args);
        return $query->post_count;
    }
}

/**
 * Get total post by met key and value
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_get_total_posts_by_meta')) {

    function doccure_get_total_posts_by_meta($type='doctors',$meta_key='',$meta_val='',$status='',$post_author='' ) {
		$meta_query_args	= array();
		
        //default
		$meta_query_args[] = array(
				'key' 			=> $meta_key,
				'value' 		=> $meta_val,
				'compare' 		=> '='
			); 

		$query_args = array(
			'posts_per_page'      => -1,
			'post_type' 	      => $type,
			'post_status'	 	  => $status,
			'ignore_sticky_posts' => 1
		);
		
		if(!empty ( $post_author ) ){
			$query_args['author']	= $post_author;
		}

		//Meta Query
		if (!empty($meta_query_args)) {
			$query_relation = array('relation' => 'AND',);
			$meta_query_args = array_merge($query_relation, $meta_query_args);
			$query_args['meta_query'] = $meta_query_args;
		}
		
        $query = new WP_Query($query_args);
        return $query->post_count;
    }
}

/**
 * Get Tag Line
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if(!function_exists('doccure_get_tagline') ) {
	function doccure_get_tagline($post_id ='') {
		$shoert_des		= doccure_get_post_meta( $post_id, 'am_short_description');
		$shoert_des		= !empty( $shoert_des ) ? esc_html( $shoert_des ) : '';
		return $shoert_des;
	} 
}

/**
 * Get Location
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if(!function_exists('doccure_get_location') ) {
	function doccure_get_location($post_id ='') {
		global $doccure_options;
		$multiple_locations			= !empty( $doccure_options['multiple_locations'] ) ? $doccure_options['multiple_locations'] : 'no';	
		$args	= array();
		$terms 						= apply_filters('doccure_get_tax_query',array(),$post_id,'locations',$args);

		if(!empty($multiple_locations) && $multiple_locations === 'yes'){
			$locations_name		= !empty( $terms ) ?  implode(',',wp_list_pluck($terms,'name'))  : '';
		}else{
			$locations_name		= !empty( $terms[0]->name ) ?  $terms[0]->name  : '';
		}
		
		if(!empty($locations_name) ) {
			$item['_country']	= $locations_name;
		} else {
			$item['_country']	= '';
		}
		
		return $item;
	} 
}

/**
 * Get doctors days
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if(!function_exists('doccure_get_booking_days') ) {
	function doccure_get_booking_days( $user_identity ='' ) {
		global $doccure_options;
		$doctor_location	= !empty($doccure_options['doctor_location']) ? $doccure_options['doctor_location'] : '';
		$days				= array();
		$slots				= array();
		
		if(!empty($doctor_location) && $doctor_location == 'hospitals'){
			$args 	= array(
						'fields'          	=> 'ids',
						'post_type'      	=> 'hospitals_team',
						'author' 			=> $user_identity,
						'post_status'    	=> 'publish',
						'posts_per_page' 	=> -1
					);

			$team_hospitals = get_posts( $args );
			if( !empty( $team_hospitals ) ){

				foreach( $team_hospitals as $item ){
					$slots	= get_post_meta( $item,'am_slots_data',true);
					if(!empty($slots) && is_array($slots)){
						if( !empty( $days ) ){
							$days	= array_merge($days, array_keys( $slots ));
						} else {
							$days	=  array_keys( $slots );
						}
					}

				}

			}
		}else if(!empty($doctor_location) && $doctor_location == 'clinic'){
			$post_id  		 = doccure_get_linked_profile_id($user_identity);
			$location_id	= get_post_meta($post_id, '_doctor_location', true);
			
			$slots	= get_post_meta( $location_id,'am_slots_data',true);
			if(!empty($slots) && is_array($slots)){
				$days	=  array_keys( $slots );
			}
		}else{
			
			$post_id  		 = doccure_get_linked_profile_id($user_identity);
			$location_id	= get_post_meta($post_id, '_doctor_location', true);
			
			$slots	= get_post_meta( $location_id,'am_slots_data',true);
			if(!empty($slots) && is_array($slots)){
				$days	=  array_keys( $slots );
			}
			
			$args 	= array(
						'fields'          	=> 'ids',
						'post_type'      	=> 'hospitals_team',
						'author' 			=> $user_identity,
						'post_status'    	=> 'publish',
						'posts_per_page' 	=> -1
					);

			$team_hospitals = get_posts( $args );
			if( !empty( $team_hospitals ) ){

				foreach( $team_hospitals as $item ){
					$slots	= get_post_meta( $item,'am_slots_data',true);
					if(!empty($slots) && is_array($slots)){
						if( !empty( $days ) ){
							$days	= array_merge($days, array_keys( $slots ));
						} else {
							$days	=  array_keys( $slots );
						}
					}

				}

			}
		}

		if( !empty( $days ) && is_array($slots) ){
			$days	= array_unique( $days );
		}

		
		return $days;
		
	} 
}

/**
 * Get doctors days
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if(!function_exists('doccure_get_booking_clinic_days') ) {
	function doccure_get_booking_clinic_days( $user_identity ='' ) {
		global $doccure_options;
		$days				= array();
		$sloats				= array();

		$args 	= array(
					'fields'          	=> 'ids',
					'post_type'      	=> 'dc_locations',
					'author' 			=> $user_identity,
					'post_status'    	=> 'publish',
					'posts_per_page' 	=> -1
				);

		$team_hospitals = get_posts( $args );
		if( !empty( $team_hospitals ) ){
			
			foreach( $team_hospitals as $item ){
				$sloats	= get_post_meta( $item,'am_slots_data',true);
				if(!empty($sloats) && is_array($sloats)){
					if( !empty( $days ) ){
						$days	= array_merge($days, array_keys( $sloats ));
					} else {
						$days	=  array_keys( $sloats );
					}
				}
				
			}
			
		}
		
		if( !empty( $days ) && is_array($sloats) ){
			$days	= array_unique( $days );
		}
		
		return $days;
		
	} 
}

/**
 * Get signup uri
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists( 'doccure_get_signup_page_url' ) ) {    

    function doccure_get_signup_page_url($key = 'step', $slug = '1') {
		global $doccure_options;
        $login_register		= !empty( $doccure_options['registration_form'] ) && !empty( $doccure_options['login_page'] ) ? $doccure_options['login_page'] : '';

        if(!empty( $login_register )){
            $signup_page_slug = esc_url(get_permalink((int) $login_register));            
        }

        if( !empty( $signup_page_slug ) ){
            return $signup_page_slug;
        }

        return '';
    }
}


/**
 * List Months
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_list_month' ) ) {
    function doccure_list_month( ) {
		$month_names = array(
						'01'	=> esc_html__("January",'doccure'),
						'02'	=> esc_html__("February",'doccure'),
						'03' 	=> esc_html__("March",'doccure'),
						'04'	=> esc_html__("April",'doccure'),
						'05'	=> esc_html__("May",'doccure'),
						'06'	=> esc_html__("June",'doccure'),
						'07'	=> esc_html__("July",'doccure'),
						'08'	=> esc_html__("August",'doccure'),
						'09'	=> esc_html__("September",'doccure'),
						'10'	=> esc_html__("October",'doccure'),
						'11'	=> esc_html__("November",'doccure'),
						'12'	=> esc_html__("December",'doccure'),
					);
		
		return $month_names;
		
	}
}

/**
 * List Users Types
 *
 */
if ( ! function_exists( 'doccure_list_user_types' ) ) {
    function doccure_list_user_types( ) {
		global $doccure_options;
		$user_types_names = array(
						//'hospitals'		=> esc_html__("Hospital",'doccure'),
						'doctors'		=> esc_html__("Doctor",'doccure'),
						'regular_users' => esc_html__("Patient",'doccure')
						//'seller'		=> esc_html__('Pharmacy(Vendor)','doccure')
					);

		return $user_types_names;
		
	}
}

/**
 * List services by specialities
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_list_service_by_specialities' ) ) {
    function doccure_list_service_by_specialities($speciality_id ) {
		$args = array(
			'hide_empty' => false, // also retrieve terms which are not used yet
			'orderby' 	 => 'name',
            'order' 	 => 'ASC',
			'meta_query' => array(
				array(
				   'key'       => 'speciality',
				   'value'     => $speciality_id,
				   'compare'   => '='
				)
			)
		);
		
		$services_array	 = array();
		if( taxonomy_exists('services') ){
			$services = get_terms( 'services', $args );

			if( !empty( $services ) ){
				foreach( $services as $service ) {
					$services_array[$service->slug] = $service;
				}
			}
		}
		
		return $services_array;
	}
}

/**
 * Get full Dr name
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_full_name' ) ) {
    function doccure_full_name( $post_id ) {
		global $doccure_options;
		$base_name_disable		= !empty( $doccure_options['base_name_disable'] ) ? $doccure_options['base_name_disable'] : '';
			
		$title 		= get_the_title($post_id);
		$title		= !empty( $title ) ? $title : '';
		if( !empty($base_name_disable) ){
			$dr_name	= doccure_get_post_meta($post_id,'am_name_base');
			$user_identity	= doccure_get_linked_profile_id($post_id,'post');
			$user_type		= apply_filters('doccure_get_user_type', $user_identity );
			
			if( !empty( $dr_name ) && $user_type === 'doctors' ){
				$name_bases	= doccure_get_name_bases($dr_name,'doctor');
				$dr_name	= $name_bases;
				$full_name	= $dr_name.' '.$title;
			} else {
				$full_name	= $title;
			}
		} else {
			$full_name	= $title;
		}
		
		return ucfirst( $full_name );
	}
}

/**
 * Get user post meta
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_get_post_meta' ) ) {
    function doccure_get_post_meta( $post_id ='' , $meta_key = '') {
		$post_meta = array();
		
		if( !empty( $post_id )) {
			$post_type		= get_post_type($post_id);
			$post_meta		= get_post_meta($post_id, 'am_' . $post_type . '_data',true);	
			$post_meta		= !empty( $post_meta) ? $post_meta : array();
		}
		
		if( !empty( $meta_key ) ){
			$post_meta		= !empty( $post_meta[$meta_key] ) ? $post_meta[$meta_key] : array();
		}
		
		return $post_meta;
	}
}

/**
 * Check wishlist
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_check_wishlist' ) ) {
    function doccure_check_wishlist( $post_id,$key = '' ) {
		global $current_user;
		$return = false;
		$linked_profile   	= doccure_get_linked_profile_id($current_user->ID);
		$saved_doctors 		= get_post_meta($linked_profile, $key, true);
		$wishlist   		= !empty( $saved_doctors ) && is_array( $saved_doctors ) ? $saved_doctors : array();
		
		if( !empty( $post_id ) ) {
			if( in_array( $post_id, $wishlist ) ){ 
				$return = true;
			} else {
				$return = false;
			}
		}
		
		return $return;
	}
}

/**
 * Get account settings
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_get_account_settings' ) ) {
	function doccure_get_account_settings($key='') {
		global $current_user;
		$settings = array(
			'doctors' => array(
				'_profile_blocked' 		=> esc_html__('Disable my account temporarily','doccure'),
			),
			'hospitals' => array(
				'_profile_blocked' 		=> esc_html__('Disable my account temporarily','doccure'),
			),
		);

		$settings	= apply_filters('doccure_filters_account_settings',$settings);
		
		return !empty( $settings[$key] ) ? $settings[$key] : array();
	}
}

/**
 * Get leave reasons list
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_get_account_delete_reasons' ) ) {
	function doccure_get_account_delete_reasons($key='') {
		global $current_user;
		$list = array(
			'not_satisfied' => esc_html__('No satisfied with the system','doccure'),
			'support' 		=> esc_html__('Support is not good','doccure'),
			'other' 		=> esc_html__('Others','doccure'),
		);

		$reasons	= apply_filters('doccure_filters_account_delete_reasons',$list);
		
		if( !empty( $key ) ){
			return !empty( $list[$key] ) ? $list[$key] : '';
		}
		
		return $reasons;
	}
}

/**
 * Get Search page
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_get_search_page' ) ) {
	function doccure_get_search_page( $type='') {
		global $doccure_options;
		$search_settings	= !empty( $doccure_options['search_form'] ) ? $doccure_options['search_form'] : '';
		$search_page		= !empty( $doccure_options[$type] ) && !empty( $search_settings )  ? get_the_permalink($doccure_options[$type]) : '';
		
		return $search_page;
	}
}

/**
 * Get profile ID by post ID
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_get_pofile_ID_by_post' ) ) {
	function doccure_get_pofile_ID_by_post( $post_id='') {
		$profile_id	= '';
		if( !empty( $post_id ) ){
			$author_id = get_post_field( 'post_author', $post_id );
			if( !empty( $author_id ) ){
				$profile_id	= doccure_get_linked_profile_id($author_id);
			}
		}
		
		return $profile_id;
	}
}

/**
 * Get time
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_get_time' ) ) {
	function doccure_get_time() {
		$time_settings = get_option( 'dc_time_settings' );
		
		$list	= array(		
					'0000'	=> esc_html__('12:00 am','doccure'),
					'0100'	=> esc_html__('1:00 am','doccure'),
					'0200'	=> esc_html__('2:00 am','doccure'),
					'0300'	=> esc_html__('3:00 am','doccure'),
					'0400'	=> esc_html__('4:00 am','doccure'),
					'0500'	=> esc_html__('5:00 am','doccure'),
					'0600'	=> esc_html__('6:00 am','doccure'),
					'0700'	=> esc_html__('7:00 am','doccure'),
					'0800'	=> esc_html__('8:00 am','doccure'),
					'0900'	=> esc_html__('9:00 am','doccure'),
					'1000'	=> esc_html__('10:00 am','doccure'),
					'1100'	=> esc_html__('11:00 am','doccure'),
					'1200'	=> esc_html__('12:00 pm','doccure'),
					'1300'	=> esc_html__('1:00 pm','doccure'),
					'1400'	=> esc_html__('2:00 pm','doccure'),
					'1500'	=> esc_html__('3:00 pm','doccure'),
					'1600'	=> esc_html__('4:00 pm','doccure'),
					'1700'	=> esc_html__('5:00 pm','doccure'),
					'1800'	=> esc_html__('6:00 pm','doccure'),
					'1900'	=> esc_html__('7:00 pm','doccure'),
					'2000'	=> esc_html__('8:00 pm','doccure'),
					'2100'	=> esc_html__('9:00 pm','doccure'),
					'2200'	=> esc_html__('10:00 pm','doccure'),
					'2300'	=> esc_html__('11:00 pm','doccure'),
					'2400'	=> esc_html__('12:00 pm (night)','doccure')			
				);
		
		$final_list	= !empty( $time_settings ) ? $time_settings : $list;
		$time_list 	= apply_filters('doccure_filter_time',$final_list);
		return $time_list;
	}
}

/**
 * Get time slots
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_get_time_slots' ) ) {
	function doccure_get_time_slots() {
		$slots_settings = get_option( 'dc_time_slots_settings' );
		
		$list	= array(		
					'1'	=> esc_html__('1 time slots','doccure'),
					'2'	=> esc_html__('2 time slots','doccure'),
					'3'	=> esc_html__('3 time slots','doccure'),
					'4'	=> esc_html__('4 time slots','doccure'),
					'5'	=> esc_html__('5 time slots','doccure'),
					'6'	=> esc_html__('6 time slots','doccure'),
					'7'	=> esc_html__('7 time slots','doccure'),
					'8'	=> esc_html__('8 time slots','doccure'),
					'9'	=> esc_html__('9 time slots','doccure'),
					'10'	=> esc_html__('10 time slots','doccure'),
					'11'	=> esc_html__('11 time slots','doccure'),
					'12'	=> esc_html__('12 time slots','doccure'),
					'13'	=> esc_html__('13 time slots','doccure'),
					'14'	=> esc_html__('14 time slots','doccure'),
					'15'	=> esc_html__('15 time slots','doccure'),
					'16'	=> esc_html__('16 time slots','doccure'),
					'17'	=> esc_html__('17 time slots','doccure'),
					'18'	=> esc_html__('18 time slots','doccure'),
					'19'	=> esc_html__('19 time slots','doccure'),
					'20'	=> esc_html__('20 time slots','doccure')
			
				);
		
		$final_list		= !empty( $slots_settings ) ? $slots_settings : $list;
		$slots_list 	= apply_filters('doccure_filter_time_slots',$final_list);
		return $slots_list;
	}
}

/**
 * Get time slots
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_get_meeting_time' ) ) {
	function doccure_get_meeting_time() {
		$slots_settings = get_option( 'dc_meeting_time_settings' );
		
		$list	= array(		
					''	=> esc_html__('Appointment Durations','doccure'),
					'5'	=> esc_html__('5 minutes','doccure'),
					'10'	=> esc_html__('10 minutes','doccure'),
					'15'	=> esc_html__('15 minutes','doccure'),
					'20'	=> esc_html__('20 minutes','doccure'),
					'30'	=> esc_html__('30 minutes','doccure'),
					'45'	=> esc_html__('45 minutes','doccure'),
					'60'	=> esc_html__('1 hours','doccure'),
					'90'	=> esc_html__('1 hours, 30 minutes','doccure'),
					'120'	=> esc_html__('2 hours','doccure'),
					'180'	=> esc_html__('3 hours','doccure'),
					'240'	=> esc_html__('4 hours','doccure'),
					'300'	=> esc_html__('5 hours','doccure'),
					'360'	=> esc_html__('6 hours','doccure'),
					'420'	=> esc_html__('7 hours','doccure'),
					'480'	=> esc_html__('8 hours','doccure')
				);
		
		$final_list		= !empty( $slots_settings ) ? $slots_settings : $list;
		$slots_list 	= apply_filters('doccure_filter_meeting_time',$final_list);
		return $slots_list;
	}
}

/**
 * Get time padding
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_get_padding_time' ) ) {
	function doccure_get_padding_time() {
		$slots_settings = get_option( 'dc_padding_time_settings' );
		
		$list	= array(		
					''	=> esc_html__('Appointment Intervals','doccure'),
					'0'	=> esc_html__('0 minutes','doccure'),
					'5'	=> esc_html__('5 minutes','doccure'),
					'10'	=> esc_html__('10 minutes','doccure'),
					'15'	=> esc_html__('15 minutes','doccure'),
					'20'	=> esc_html__('20 minutes','doccure'),
					'30'	=> esc_html__('30 minutes','doccure'),
					'45'	=> esc_html__('45 minutes','doccure'),
					'60'	=> esc_html__('1 hours','doccure'),
					'90'	=> esc_html__('1 hours, 30 minutes','doccure'),
					'120'	=> esc_html__('2 hours','doccure'),
				);
		
		$final_list		= !empty( $slots_settings ) ? $slots_settings : $list;
		$slots_list 	= apply_filters('doccure_filter_padding_time',$final_list);
		return $slots_list;
	}
}

/**
 * Get slots by day and post id
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_get_day_spaces' ) ) {
	function doccure_get_day_spaces( $day = '', $post_id = '') {
		
		$li_data	= '';
		if( !empty( $day ) && !empty( $post_id ) ) {
			$time_format 		= get_option('time_format');
			$am_slots_data 		= get_post_meta( $post_id,'am_slots_data',true);
			$am_slots_data		= !empty( $am_slots_data ) ? $am_slots_data : array();
			$slots				= $am_slots_data[$day]['slots'];
			if( !empty( $slots ) ){
				foreach( $slots as $slot_key => $slot_val ) { 
					$slot_key_val = explode('-', $slot_key);
					$li_data .='<li>
					<a href="javascript:;" class="dc-spaces">
						<span>'.date($time_format, strtotime('2016-01-01' . $slot_key_val[0])).'</span>
						<span>'.esc_html('Spaces','doccure').': '. esc_html( $slot_val["spaces"] ).'</span>
						<i class="fa fa-close" data-id="'.intval( $post_id ).'" data-day="'.esc_attr( $day ).'" data-key="'.esc_attr( $slot_key ).'"></i>
					</a>
				</li>';
				}
			} 
		} 
		
		return $li_data;
	}
}

/**
 * Generate google link
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_generate_GoogleLink' ) ) {
	function doccure_generate_GoogleLink ($title,$from,$to,$description,$address){
		$start  = new DateTime($from);
		$end 	= new DateTime($to);
		$from	= $start->format('Ymd\THis');
		$to		= $end->format('Ymd\THis');
		$protolcol  = is_ssl() ? "https" : "http";
		$url 		= $protolcol.'://calendar.google.com/calendar/render?action=TEMPLATE';


		$url .= '&text='.urlencode($title);
		$url .= '&dates='.$from.'/'.$to;

		if ($description) {
			$url .= '&details='.urlencode($description);
		}

		if ($address) {
			$url .= '&location='.urlencode($address);
		}

		$url .= '&sprop=&sprop=name:';

		return $url;
	}
}

/**
 * Generate google link
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_generate_YahooLink' ) ) {
	function doccure_generate_YahooLink ($title,$from,$to,$description,$address){
		$start  = new DateTime($from);
		$end 	= new DateTime($to);
		$protolcol  = is_ssl() ? "https" : "http";
		$url 		= $protolcol.'://calendar.yahoo.com/?v=60&view=d&type=20';

		$url .= '&title='.urlencode($title);
		$url .= '&st='.$start->format('Ymd\THis\Z');
		$url .= '&dur='.date_diff($start, $end)->format('%H%I');

		if ($description) {
			$url .= '&desc='.urlencode($description);
		}

		if ($address) {
			$url .= '&in_loc='.urlencode($address);
		}

		return $url;
	}
}
/*
**
 * Get total earning for doctor
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_get_total_earning_doctor' ) ) {
    function doccure_get_total_earning_doctor( $user_id='',$status='',$colum_name='') {
		global $wpdb;
		$table_name = $wpdb->prefix . "dc_earnings";
		
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
			if( !empty($user_id) && !empty($status) && !empty($colum_name) ) {
				$e_query	= $wpdb->prepare("SELECT sum(".$colum_name.") FROM ".$table_name." WHERE user_id = %d and ( status = %s || status = %s )",$user_id,$status[0],$status[1]);
				$total_earning	= $wpdb->get_var( $e_query );
			} else {
				$total_earning	= 0;
			}
		} else{
			$total_earning	= 0;
		}
		
		return $total_earning;
		
	}
}

/**
 * Get earning for doccure
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_get_payments_doccure' ) ) {
    function doccure_get_payments_doccure( $user_identity,$limit=6  ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "dc_payouts_history";
		$month		= date('m');
		
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
			if( !empty($user_identity) ) {
				$e_query	= $wpdb->prepare("SELECT * FROM $table_name where ( user_id =%d and status= 'completed' AND month=%d) ORDER BY id DESC LIMIT %d",$user_identity,$month,$limit);
				$payments = $wpdb->get_results( $e_query );
			} else {
				$payments	= 0;
			}
		} else{
			$payments	= 0;
		}
		
		return $payments;
		
	}
}

/**
 * Get sum payments for doctor
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_get_sum_payments_doctor' ) ) {
    function doccure_get_sum_payments_doctor( $user_id='',$status='',$colum_name='') {
		global $wpdb;

		return $current_balance	= doccure_get_total_earning_doctor($user_id,array('completed','processed'),'doctor_amount');
	}
}

/**
 * Get total earning for doctor
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_get_total_earning_doctor' ) ) {
    function doccure_get_total_earning_doctor( $user_id='',$status='',$colum_name='') {
		global $wpdb;
		$table_name = $wpdb->prefix . "dc_earnings";
		
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
			if( !empty($user_id) && !empty($status) && !empty($colum_name) ) {
				$e_query	= $wpdb->prepare("SELECT sum(".$colum_name.") FROM ".$table_name." WHERE user_id = %d and ( status = %s || status = %s )",$user_id,$status[0],$status[1]);
				$total_earning	= $wpdb->get_var( $e_query );
			} else {
				$total_earning	= 0;
			}
		} else{
			$total_earning	= 0;
		}
		
		return $total_earning;
		
	}
}

/**
 * Get sum earning for doctor
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_get_sum_earning_doctor' ) ) {
    function doccure_get_sum_earning_doctor( $user_id='',$status='',$colum_name='') {
		global $wpdb;
		$table_name = $wpdb->prefix . "dc_earnings";
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
			if( !empty($user_id) && !empty($status) && !empty($colum_name) ) {
				$e_query	= $wpdb->prepare("SELECT sum(".$colum_name.") FROM ".$table_name." WHERE user_id = %d and status = %s",$user_id,$status);
				$total_earning	= $wpdb->get_var( $e_query );
			} else {
				$total_earning	= 0;
			}
		} else{
			$total_earning	= 0;
		}
		
		return $total_earning;
		
	}
}

/**
 * Get prefix
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('dc_unique_increment')) {

    function dc_unique_increment($length = 5) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}

/**
 * Get sum earning for payouts
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'doccure_sum_earning_doctor_payouts' ) ) {
    function doccure_sum_earning_doctor_payouts( $status='',$colum_name='') {
		global $wpdb;
		$table_name = $wpdb->prefix . "dc_earnings";
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
			if( !empty($status) && !empty($colum_name) ) {
				$e_query	= $wpdb->prepare("SELECT user_id, sum(".$colum_name.") as total_amount FROM ".$table_name." WHERE status = %s GROUP BY user_id",$status);
				$total_earning	= $wpdb->get_results( $e_query );
			} else {
				$total_earning	= 0;
			}
		} else{
			$total_earning	= 0;
		}
		
		return $total_earning;
		
	}
}

/**
 * Update doctor earning
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_update_earning' ) ) {

	function doccure_update_earning( $where, $update, $table_name ) {
		
		global $wpdb;
		if( !empty($where) && !empty($update) && !empty($table_name) ) {
			$wpdb->update($wpdb->prefix.$table_name, $update, $where);
		} else {
			return false;
		}
	}
}

/**
 * theme setting options
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_theme_option')) {

    function doccure_theme_option($option_type='system_booking_oncall') {
		global $doccure_options;
		$theme_option	= !empty($doccure_options[$option_type]) ? $doccure_options[$option_type] : '';
		return $theme_option;
    }
}

/**
 * System booking on call option
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_get_booking_oncall_option')) {

    function doccure_get_booking_oncall_option($is_active='') {

		$payment_type				= doccure_theme_option('payment_type');
		$system_booking_oncall		= doccure_theme_option('system_booking_oncall');
		$booking_option				= (!empty($payment_type) && $payment_type === 'offline') && !empty($system_booking_oncall) ? $system_booking_oncall : '';
		
		if(!empty($booking_option) && empty($is_active)){
			$booking_option			= doccure_theme_option('booking_system_contact');
		}

		return $booking_option;
    }
}

/**
 * System booking on call doctors option
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_get_booking_oncall_doctors_option')) {

    function doccure_get_booking_oncall_doctors_option() {

		$payment_type				= doccure_theme_option('payment_type');
		$booking_option				= 1;
		
		if( (!empty($payment_type) && $payment_type ==='offline' ) ){
			$system_booking_oncall		= doccure_theme_option('system_booking_oncall');
			if( !empty($system_booking_oncall) ){
				$booking_option			= doccure_theme_option('booking_system_contact');
				
				$booking_option = !empty($booking_option) && $booking_option === 'doctor' ? false : true;
			}
		}

		return $booking_option;
    }
}

if( !function_exists('doccure_send_booking_message')){
	function doccure_send_booking_message($post_id,$defult_message = ''){
		global $current_user,$doccure_options;
		
		$patient_id		= get_post_field( 'post_author', $post_id );
		$date_format	= get_option('date_format');
		$current_time   = current_time('mysql');
		$gmt_time       = get_gmt_from_date($current_time);
		
		$doctor_profile_id	= get_post_meta( $post_id, '_doctor_id', true );
		$doctor_id			= doccure_get_linked_profile_id($doctor_profile_id,'post');
		
		$patient_profile_id	= doccure_get_linked_profile_id($patient_id);
		$patient_name		= doccure_full_name($patient_profile_id);
		
		$sender_id		= $doctor_id;
		$receiver_id	= $patient_id;
		
		if( !empty($defult_message) ){
			if( $current_user->ID == $patient_id ){
				$sender_id		= $patient_id;
				$receiver_id	= $doctor_id;
			}
			
			$message		= $defult_message;
			
		} else {
			$appointment_date	= get_post_meta( $post_id, '_appointment_date', true );
			$appointment_date	= !empty($appointment_date) ?  date_i18n($date_format, strtotime($appointment_date)) : '';
		
			$message		= esc_html__('Hi %username%, your booking has been received on %date%','doccure');
			$message 	= str_replace("%username%", $patient_name, $message); 
			$message 	= str_replace("%date%", $appointment_date, $message); 
		}

		$insert_data = array(
			'sender_id' 		=> $sender_id,
			'receiver_id' 		=> $receiver_id,
			'chat_message' 		=> $message,
			'status' 			=> 1,
			'timestamp' 		=> time(),
			'time_gmt' 			=> $gmt_time,
		);
			
		//plugin core active
		if( !empty( $doccure_options['chat'] ) && $doccure_options['chat'] === 'guppy' ){
			do_action('wpguppy_send_message_to_user',$sender_id,$receiver_id,$message);
		}else{
			if (class_exists('ChatSystem')) {
				$msg_id = ChatSystem::getUsersThreadListData($doctor_id, $patient_id, 'insert_msg', $insert_data, '');
				if( !empty($defult_message) ){
					return $receiver_id;
				}
			}
		}
	}
}

/**
 * Return order status text
 * PDF header
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */

if( !function_exists('doccure_get_order_status_text') ){
	function doccure_get_order_status_text($key = ''){
		$order_status	= wc_get_order_statuses();
		$order_status	= !empty($order_status[$key]) ? $order_status[$key] : '';
		return esc_html($order_status);
	}
}
/**
 * Return order payment gateways text
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if( !function_exists('doccure_get_payment_gateways_text') ){
	function doccure_get_payment_gateways_text($key = '') {
		global $woocommerce;
		$active_gateways = array();
		$gateways        = WC()->payment_gateways->payment_gateways();
		foreach ( $gateways as $id => $gateway ) {
			if ( isset( $gateway->enabled ) && 'yes' === $gateway->enabled && $id === $key ) {
				$active_gateways	= $gateway->title;
			}
		}
		return esc_html($active_gateways);
	}
}


/**
 * Render header
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */

if( !function_exists('doccure_pdf')){ 

	
	function doccure_pdf($booknig_id=''){
		global $doccure_options;
		$precription_details	= !empty($doccure_options['precription_details']) ? $doccure_options['precription_details'] : '';
		
		//$border_image 		= get_template_directory().'/images/pdf/shape-02.png';
		$logo_image 		= !empty($doccure_options['pdf_logo']) ? $doccure_options['pdf_logo']['url'] : '';
		$prescription_id	= get_post_meta( $booknig_id, '_prescription_id', true );
		$doctor_id			= get_post_meta( $prescription_id, '_doctor_id', true );
		$doctor_profile_id	= !empty($doctor_id) ? doccure_get_linked_profile_id($doctor_id) : '';

		$doctordata 		= get_userdata($doctor_id);
		$doctor_email		= !empty($doctordata->user_email) ? $doctordata->user_email : '';

		$doctor_name		= doccure_full_name($doctor_profile_id);
		$doctor_name		= !empty($doctor_name) ? $doctor_name : '';
		$doctor_location	= !empty($doccure_options['doctor_location']) ? $doccure_options['doctor_location'] : '';
		$web_url			= '';
		
		if(!empty($doctor_location) && $doctor_location === 'hospitals'){
			$hospital_location_id	= get_post_meta( $prescription_id, '_hospital_id',  true);
			$mobile_number			= doccure_get_post_meta( $hospital_location_id,'am_mobile_number' );
			$web_url				= doccure_get_post_meta( $hospital_location_id,'am_web_url' );
			$hospital_id			= !empty($hospital_location_id) ?  doccure_get_linked_profile_id($hospital_location_id,'post') : '';
			$user_details			= !empty($hospital_id) ?  get_userdata($hospital_id) : '';
		}else{
			$hospital_location_id	= get_post_meta( $doctor_profile_id, '_doctor_location',  true);
			$mobile_number			= doccure_get_post_meta( $doctor_profile_id,'am_mobile_number' );
			$user_details			= !empty($doctor_id) ?  get_userdata($doctor_id) : '';
		}

		$email_info				= !empty($user_details->user_email) ? $user_details->user_email : '';
		$prescription_details	= get_post_meta( $prescription_id, '_detail', true );
		$prescription_details	= !empty($prescription_details) ? $prescription_details : array();

		$medicine				= !empty($prescription_details['_medicine']) ? $prescription_details['_medicine'] : array();

		$hospital_location_id	= !empty($hospital_location_id) ? $hospital_location_id : '';
		$location_title			= !empty($hospital_location_id) ? get_the_title($hospital_location_id) : '';

		$address		= get_post_meta( $hospital_location_id , '_address',true );
		$address		= !empty( $address ) ? $address : '';

		$laboratory_tests_obj_list 	= get_the_terms( $prescription_id, 'laboratory_tests' );
		$laboratory_tests_name		= !empty($laboratory_tests_obj_list) ? join(', ', wp_list_pluck($laboratory_tests_obj_list, 'name')) : '';
		
		if( !empty($precription_details) && $precription_details === 'doctor' ){
			$attachment_id 			= get_post_thumbnail_id($doctor_profile_id);
			$image_url 				= !empty( $attachment_id ) ? wp_get_attachment_url( $attachment_id, 'doccure_doctors_type', true ) : '';
			$logo_image				= !empty($image_url) ? wp_make_link_relative($image_url) : $logo_image;
			$logo_image				= get_attached_file($attachment_id);
			
			$location_title			= doccure_get_username($doctor_id);
			$address				= get_post_meta( $doctor_profile_id , '_address',true );
			$address				= !empty( $address ) ? $address : '';
			$registration_number	= doccure_get_post_meta( $doctor_profile_id,'am_registration_number' );

			$web_url				= doccure_get_post_meta( $doctor_profile_id,'am_web_url' );
			$mobile_number			= doccure_get_post_meta( $doctor_profile_id,'am_mobile_number' );
			$user_details			= !empty($doctor_id) ?  get_userdata($doctor_id) : '';
			$email_info				= !empty($user_details->user_email) ? $user_details->user_email : '';
		}else{
			if( !empty($hospital_location_id) && has_post_thumbnail($hospital_location_id) ){
				$attachment_id 			= get_post_thumbnail_id($hospital_location_id);
				$image_url 				= !empty( $attachment_id ) ? wp_get_attachment_url( $attachment_id, 'doccure_doctors_type', true ) : '';
				$logo_image				= !empty($image_url) ? wp_make_link_relative($image_url) : $logo_image;
				$logo_image				= get_attached_file($attachment_id);
			}
		}
		

		$html_new = '<html>
    <head>
        <style>
            /** 
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
            @page {
                margin: 0cm 0cm;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                margin-top: 2cm;
                margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 1cm;
            }
			body header h2 { margin-top:0px;}

            /** Define the header rules **/
            header {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
                height: 2cm;

                /** Extra personal styles **/
                background-color: #0e82fd;
                color: white;
                text-align: center;
                line-height: 1.5cm;
            }

            /** Define the footer rules **/
            footer {
                position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 2cm;

                /** Extra personal styles **/
                background-color: #0e82fd;
                color: white;
                text-align: center;
                line-height: 1.5cm;
            }
        </style>
    </head>
    <body>';
		$html_new	.= '<header><h2>'.esc_html__('Prescription','doccure').'</h2></header>';
		 
       
		$html_new	.= '<main>
 			

				<table style="width:96%;  margin:20px auto 0; table-layout: fixed; ">
					<tr style="text-align:left;">
						<td width="100%">';
 							 
			
							if( !empty($location_title) ){
								$html_new	.= '<b>'.$location_title.'</b><br>';
							}
		
							if( !empty($registration_number) ){
								$html_new	.= '<b>'.esc_html__('Doctor Registration ID:','doccure').' '.$registration_number.'</b><br>';
							}
		
							if( !empty($address) ){
								$html_new	.= ''.$address.'<br>';
							}
				
							if( !empty($location) ){
								$html_new	.= ''.$location.'<br>';
							}
				
							if( !empty($mobile_number) ){
								$html_new	.= ''.$mobile_number.'<br>';
							}
				
							if( !empty($email_info) ){
								$html_new	.= ''.is_email($email_info).'<br>';
							}
				
							if( !empty($web_url) ){
								$html_new	.= ''.esc_url($web_url).'';
							}
							
						$html_new	.= '</td>
					</tr>
				</table>';
				$html_new	.='<table style="width:96%; margin:20px auto 0;table-layout: fixed;" >
								<tr style="text-align:left;">';
									if( !empty($prescription_details['_patient_name'])){
										$html_new	.= '<td width="100%" style="text-align:left;box-sizing: border-box;"><b>'.esc_html__('Name:','doccure').'</b>'.$prescription_details['_patient_name'].'</td>';
									}

									if( !empty($prescription_details['_age'])){

										$html_new		.= '<td width="100%" style="text-align:left;box-sizing: border-box;"><b>'.esc_html__('Age:','doccure').'</b>'.$prescription_details['_age'].' '.esc_html__('year','doccure').'</td>';

									}
		
					$html_new		.='</tr>';
				
				if( !empty($prescription_details['_gender'])  ){
					$html_new		.='<tr style="text-align:left;">';
						if( !empty($prescription_details['_gender'])){
							$gender_title	= $prescription_details['_gender'] === 'female' ? esc_html__('Female','doccure') : esc_html__('Male','doccure');
							$html_new		.= '<td width="100%" style="text-align:left;box-sizing: border-box;"><b>'.esc_html__('Gender:','doccure').'</b>'.$gender_title.'</td>';
						}

						if( !empty($prescription_details['_marital_status'])){
							$term 			= !empty($prescription_details['_marital_status']) ? get_term( $prescription_details['_marital_status'], 'marital_status' ) : '';
							$status_name	= !empty($term->name) ? $term->name : '';
	
							$html_new		.= '<td width="100%" style="text-align:left;box-sizing: border-box;"><b>'.esc_html__('Marital status:','doccure').'</b>'.$status_name.'</td>';
	
						}

						

					$html_new		.='</tr>';
				}

				 

		 
				if(   !empty($prescription_details['_phone'])){
					$html_new		.='<tr style="text-align:left;">';
				if( !empty($prescription_details['_phone'])){
					$html_new		.= '<td width="100%" style="text-align:left;box-sizing: border-box;"><br><b>'.esc_html__('Phone:','doccure').'</b><br>'.$prescription_details['_phone'].'</td>';

				}
				$html_new		.='</tr>';
			}


				if(   !empty($prescription_details['_address'])){
					$html_new		.='<tr style="text-align:left;">';
				if( !empty($prescription_details['_address'])){
					$html_new		.= '<td width="100%" style="text-align:left;box-sizing: border-box;"><br><b>'.esc_html__('Address:','doccure').'</b><br>'.$prescription_details['_address'].'</td>';

				}
				$html_new		.='</tr>';
			}
				if( !empty($prescription_details['_marital_status']) || !empty($prescription_details['_childhood_illness'])){
					$html_new		.='<tr style="text-align:left;"><br>';
					


					if( !empty($prescription_details['_childhood_illness'])){
						$child_illness		= '';
						$counter_illness	= 0;
						$total_illness		= count($prescription_details['_childhood_illness']);
						foreach($prescription_details['_childhood_illness'] as $illness){
							$counter_illness++;
							$term 			= !empty($illness) ? get_term_by('id', $illness, 'childhood_illness') : '';
							$illness_name	= !empty($term->name) ? $term->name : '';
							$child_illness	.= $total_illness > $counter_illness ? $illness_name.',' : $illness_name;
						}

						$html_new		.= '<td width="100%" style="text-align:left;box-sizing: border-box;"><br><b>'.esc_html__('Child illness:','doccure').'</b><br>'.esc_html($child_illness).'</td>';

					}

					$html_new		.='</tr>';
				}
				
				if( !empty($prescription_details['_diseases']) || !empty( $prescription_details['_vital_signs'] )){
					$html_new		.='<tr style="text-align:left;">';

					if( !empty($prescription_details['_diseases'])){
						$diseases_name		= '';
						$counter_diseases	= 0;
						$total_diseases		= count($prescription_details['_diseases']);

						foreach($prescription_details['_diseases'] as $diseases){
							$counter_diseases++;
							$term 			= !empty($diseases) ? get_term_by('id', $diseases, 'diseases') : '';
							$dis_name		= !empty($term->name) ? $term->name : '';
							$diseases_name	.= $total_diseases > $counter_diseases ? $dis_name.',' : $dis_name;
						}


						$html_new		.= '<td width="100%" style="text-align:left;box-sizing: border-box;"><br><b>'.esc_html__('Diseases:','doccure').'</b><br>'.esc_html($diseases_name).'</td>';

					}


					if( !empty( $prescription_details['_vital_signs'] ) ){

						$counter_sign		= 0;
						$vital_signs_name	= array();
						$total_sign			= count($prescription_details['_vital_signs']);
						foreach($prescription_details['_vital_signs'] as $key => $val ) { 
							$counter_sign++;
							if( !empty($val) ){
								$term 				= !empty($key) ? get_term_by('id', $key, 'vital_signs') : '';
								$sing_val			= !empty($val['value']) ? $val['value'] : '';
								$vital_signs		= !empty($term->name) ? $term->name. '('.$sing_val.')' : '';
								$vital_signs_name[]	.= $vital_signs;

							}
						}

						$html_new		.= '<td width="100%" style="text-align:left;box-sizing: border-box;"><br><b>'.esc_html__('Vital signs:','doccure').'</b><br>'.nl2br(implode(',<br>',$vital_signs_name)).'</td>';

					}

					$html_new	.='</tr>';
				}
					
				$html_new	.='</table>';
				
				$html_new	.='<table style="width:96%;  margin:20px auto 0; table-layout: fixed; ">
				<tr style="text-align:left;">
					<td width="100%">';

				if( !empty($prescription_details['_medical_history'] ) ){
					$html_new	.= '<b>'.esc_html__('Diagnosis:','doccure').'</b><br>';
					$html_new	.= ''.esc_html($prescription_details['_medical_history']).' ';
				}
				$html_new	.= '</td>
				</tr>
			</table>';
				if( !empty( $medicine ) ){

					$html_new	.='<table style="width:96%;  margin:20px auto 0; table-layout: fixed; ">
				<tr style="text-align:left;">
					<td width="100%">';

					$html_new	.= '<b>'.esc_html__('Medications:','doccure').'</b>';
                $html_new	.= '</td>
				</tr>
			</table>';

					$html_new	.= '<table style="width: 95%; margin: 20px auto;font-family: sans-serif;table-layout: fixed;">';
					$html_new .= '<thead>
						<tr style="text-align: left; border-radius:5px 0 0;">
							<th style="width:10%; padding: 15px 20px;background: #f5f5f5; font-size:14px;">'.esc_html__('Name','doccure').'</th>
							<th style="width:10%; padding: 15px 20px;background: #f5f5f5; font-size:14px;">'.esc_html__('Types','doccure').'</th>
							<th style="width:15%; padding: 15px 20px;background: #f5f5f5; font-size:14px;">'.esc_html__('Duration','doccure').'</th>
							<th style="width:15%; padding: 15px 20px;background: #f5f5f5; font-size:14px;">'.esc_html__('Usage','doccure').'</th>
							<th style="width:25%; padding: 15px 20px;background: #f5f5f5; font-size:14px;">'.esc_html__('Details','doccure').'</th>
						</tr>
					</thead>
					<tbody>';
					foreach($medicine as $vals ) { 
						$name					= !empty($vals['name']) ? esc_html($vals['name']) : '';
						$medicine_duration 		= !empty($vals['medicine_duration']) ? get_term_by('id', $vals['medicine_duration'], 'medicine_duration',ARRAY_A) : '';
						$medicine_duration		= !empty($medicine_duration['name']) ? $medicine_duration['name'] : '';
						$medicine_types 		= !empty($vals['medicine_types']) ? doccure_get_term_by_type('id', $vals['medicine_types'], 'medicine_types','name') : '';
						$medicine_types			= !empty($medicine_types) ? $medicine_types : '';

						$medicine_usage 			= !empty($vals['medicine_usage']) ? doccure_get_term_by_type('id', $vals['medicine_usage'], 'medicine_usage','name') : '';
						$medicine_usage				= !empty($medicine_usage) ? $medicine_usage : '';

						$detail						= !empty($vals['detail']) ? esc_html($vals['detail']) : '';

						$html_new	.= '<tr>';
						if( !empty($vals) ){
								$html_new	.= '<td style="padding: 15px 20px; border-top: 1px solid #e2e2e2; font-size:14px;">'.esc_html($name).'</td>';
								$html_new	.= '<td style="padding: 15px 20px; border-top: 1px solid #e2e2e2; font-size:14px;">'.esc_html($medicine_types).'</td>';
								$html_new	.= '<td style="padding: 15px 20px; border-top: 1px solid #e2e2e2; font-size:14px;">'.esc_html($medicine_duration).'</td>';
								$html_new	.= '<td style="padding: 15px 20px; border-top: 1px solid #e2e2e2; font-size:14px;">'.esc_html($medicine_usage).'</td>';
								$html_new	.= '<td style="padding: 15px 20px; border-top: 1px solid #e2e2e2; font-size:14px;">'.esc_html($detail).'</td>';
							}			
						$html_new	.= '</tr>';
					}
					
					$html_new	.= '</table>';
				}
		
				if( !empty( $laboratory_tests_name ) ){

					$html_new	.='<table style="width:96%;  margin:20px auto 0; table-layout: fixed; ">
					<tr style="text-align:left;">
						<td width="100%">';

					$html_new	.= ''.esc_html__('Required laboratory tests*','doccure').'<br>';
					$html_new		.= ''.esc_html($laboratory_tests_name).'';
					$html_new	.= '</td>
				</tr>
			</table>';
				}
		
		 
        $html_new	.= '</main>
    </body>
</html>';



		$html = '<html>
		<head>
			<style>
				@page {
					margin: 10px 0px 50px 0px;
				}
				@page { margin: 0; }
 				*{box-sizing: border-box;}
 				 body { margin: 0px;  word-wrap:break-word;  }
				  body  h4{  position:relative; display:block;  }

				header {
					top: -30px;
					left: 0px;
					right: 0px;
					height: auto;
					position:relative;
					border-radius:5px;
					font-family: sans-serif;
					 
					background-position: top;
					background-size: 100% 100%;
					background-repeat: no-repeat;
				}
				table { border-collapse: collapse; display:table;}
			</style>
		</head>
		<body style="font-family: sans-serif; margin-top:0;padding-top:30px;">

		

			<div style="width:100%;  text-align:center; font-family: sans-serif;padding:0 0 30px;"> ';
			
			 
 	$html	.= '
 			

				<table style="width:96%;  margin:20px auto 0; table-layout: fixed; ">
					<tr style="text-align:left;">
						<td width="100%">';
 							 
			
							if( !empty($location_title) ){
								$html	.= '<b>'.$location_title.'</b><br>';
							}
		
							if( !empty($registration_number) ){
								$html	.= '<b>'.esc_html__('Doctor Registration ID:','doccure').' '.$registration_number.'</b><br>';
							}
		
							if( !empty($address) ){
								$html	.= ''.$address.'<br>';
							}
				
							if( !empty($location) ){
								$html	.= ''.$location.'<br>';
							}
				
							if( !empty($mobile_number) ){
								$html	.= ''.$mobile_number.'<br>';
							}
				
							if( !empty($email_info) ){
								$html	.= ''.is_email($email_info).'<br>';
							}
				
							if( !empty($web_url) ){
								$html	.= ''.esc_url($web_url).'';
							}
							
						$html	.= '</td>
					</tr>
				</table>';
				$html	.='<table style="width:96%; margin:20px auto 0;table-layout: fixed;" >
								<tr style="text-align:left;">';
									if( !empty($prescription_details['_patient_name'])){
										$html	.= '<td width="100%" style="text-align:left;box-sizing: border-box;"><b>'.esc_html__('Name:','doccure').'</b>'.$prescription_details['_patient_name'].'</td>';
									}

									if( !empty($prescription_details['_age'])){

										$html		.= '<td width="100%" style="text-align:left;box-sizing: border-box;"><b>'.esc_html__('Age:','doccure').'</b>'.$prescription_details['_age'].' '.esc_html__('year','doccure').'</td>';

									}
		
					$html		.='</tr>';
				
				if( !empty($prescription_details['_gender'])  ){
					$html		.='<tr style="text-align:left;">';
						if( !empty($prescription_details['_gender'])){
							$gender_title	= $prescription_details['_gender'] === 'female' ? esc_html__('Female','doccure') : esc_html__('Male','doccure');
							$html		.= '<td width="100%" style="text-align:left;box-sizing: border-box;"><b>'.esc_html__('Gender:','doccure').'</b>'.$gender_title.'</td>';
						}

						if( !empty($prescription_details['_marital_status'])){
							$term 			= !empty($prescription_details['_marital_status']) ? get_term( $prescription_details['_marital_status'], 'marital_status' ) : '';
							$status_name	= !empty($term->name) ? $term->name : '';
	
							$html		.= '<td width="100%" style="text-align:left;box-sizing: border-box;"><b>'.esc_html__('Marital status:','doccure').'</b>'.$status_name.'</td>';
	
						}

						

					$html		.='</tr>';
				}
				if(   !empty($prescription_details['_address'])){
					$html		.='<tr style="text-align:left;">';
				if( !empty($prescription_details['_address'])){
					$html		.= '<td width="100%" style="text-align:left;box-sizing: border-box;"><br><b>'.esc_html__('Address:','doccure').'</b><br>'.$prescription_details['_address'].'</td>';

				}
				$html		.='</tr>';
			}
				if( !empty($prescription_details['_marital_status']) || !empty($prescription_details['_childhood_illness'])){
					$html		.='<tr style="text-align:left;"><br>';
					


					if( !empty($prescription_details['_childhood_illness'])){
						$child_illness		= '';
						$counter_illness	= 0;
						$total_illness		= count($prescription_details['_childhood_illness']);
						foreach($prescription_details['_childhood_illness'] as $illness){
							$counter_illness++;
							$term 			= !empty($illness) ? get_term_by('id', $illness, 'childhood_illness') : '';
							$illness_name	= !empty($term->name) ? $term->name : '';
							$child_illness	.= $total_illness > $counter_illness ? $illness_name.',' : $illness_name;
						}

						$html		.= '<td width="100%" style="text-align:left;box-sizing: border-box;"><br><b>'.esc_html__('Child illness:','doccure').'</b><br>'.esc_html($child_illness).'</td>';

					}

					$html		.='</tr>';
				}
				
				if( !empty($prescription_details['_diseases']) || !empty( $prescription_details['_vital_signs'] )){
					$html		.='<tr style="text-align:left;">';

					if( !empty($prescription_details['_diseases'])){
						$diseases_name		= '';
						$counter_diseases	= 0;
						$total_diseases		= count($prescription_details['_diseases']);

						foreach($prescription_details['_diseases'] as $diseases){
							$counter_diseases++;
							$term 			= !empty($diseases) ? get_term_by('id', $diseases, 'diseases') : '';
							$dis_name		= !empty($term->name) ? $term->name : '';
							$diseases_name	.= $total_diseases > $counter_diseases ? $dis_name.',' : $dis_name;
						}


						$html		.= '<td width="100%" style="text-align:left;box-sizing: border-box;"><br><b>'.esc_html__('Diseases:','doccure').'</b><br>'.esc_html($diseases_name).'</td>';

					}


					if( !empty( $prescription_details['_vital_signs'] ) ){

						$counter_sign		= 0;
						$vital_signs_name	= array();
						$total_sign			= count($prescription_details['_vital_signs']);
						foreach($prescription_details['_vital_signs'] as $key => $val ) { 
							$counter_sign++;
							if( !empty($val) ){
								$term 				= !empty($key) ? get_term_by('id', $key, 'vital_signs') : '';
								$sing_val			= !empty($val['value']) ? $val['value'] : '';
								$vital_signs		= !empty($term->name) ? $term->name. '('.$sing_val.')' : '';
								$vital_signs_name[]	.= $vital_signs;

							}
						}

						$html		.= '<td width="100%" style="text-align:left;box-sizing: border-box;"><br><b>'.esc_html__('Vital signs:','doccure').'</b><br>'.nl2br(implode(',<br>',$vital_signs_name)).'</td>';

					}

					$html	.='</tr>';
				}
					
				$html	.='</table>';
				
				$html	.='<table style="width:96%;  margin:20px auto 0; table-layout: fixed; ">
				<tr style="text-align:left;">
					<td width="100%">';

				if( !empty($prescription_details['_medical_history'] ) ){
					$html	.= '<b>'.esc_html__('Diagnosis:','doccure').'</b><br>';
					$html	.= ''.esc_html($prescription_details['_medical_history']).' ';
				}
				$html	.= '</td>
				</tr>
			</table>';
				if( !empty( $medicine ) ){

					$html	.='<table style="width:96%;  margin:20px auto 0; table-layout: fixed; ">
				<tr style="text-align:left;">
					<td width="100%">';

					$html	.= '<b>'.esc_html__('Medications:','doccure').'</b>';
                $html	.= '</td>
				</tr>
			</table>';

					$html	.= '<table style="width: 95%; margin: 20px auto;font-family: sans-serif;table-layout: fixed;">';
					$html .= '<thead>
						<tr style="text-align: left; border-radius:5px 0 0;">
							<th style="width:10%; padding: 15px 20px;background: #f5f5f5; font-size:14px;">'.esc_html__('Name','doccure').'</th>
							<th style="width:10%; padding: 15px 20px;background: #f5f5f5; font-size:14px;">'.esc_html__('Types','doccure').'</th>
							<th style="width:15%; padding: 15px 20px;background: #f5f5f5; font-size:14px;">'.esc_html__('Duration','doccure').'</th>
							<th style="width:15%; padding: 15px 20px;background: #f5f5f5; font-size:14px;">'.esc_html__('Usage','doccure').'</th>
							<th style="width:25%; padding: 15px 20px;background: #f5f5f5; font-size:14px;">'.esc_html__('Details','doccure').'</th>
						</tr>
					</thead>
					<tbody>';
					foreach($medicine as $vals ) { 
						$name					= !empty($vals['name']) ? esc_html($vals['name']) : '';
						$medicine_duration 		= !empty($vals['medicine_duration']) ? get_term_by('id', $vals['medicine_duration'], 'medicine_duration',ARRAY_A) : '';
						$medicine_duration		= !empty($medicine_duration['name']) ? $medicine_duration['name'] : '';
						$medicine_types 		= !empty($vals['medicine_types']) ? doccure_get_term_by_type('id', $vals['medicine_types'], 'medicine_types','name') : '';
						$medicine_types			= !empty($medicine_types) ? $medicine_types : '';

						$medicine_usage 			= !empty($vals['medicine_usage']) ? doccure_get_term_by_type('id', $vals['medicine_usage'], 'medicine_usage','name') : '';
						$medicine_usage				= !empty($medicine_usage) ? $medicine_usage : '';

						$detail						= !empty($vals['detail']) ? esc_html($vals['detail']) : '';

						$html	.= '<tr>';
						if( !empty($vals) ){
								$html	.= '<td style="padding: 15px 20px; border-top: 1px solid #e2e2e2; font-size:14px;">'.esc_html($name).'</td>';
								$html	.= '<td style="padding: 15px 20px; border-top: 1px solid #e2e2e2; font-size:14px;">'.esc_html($medicine_types).'</td>';
								$html	.= '<td style="padding: 15px 20px; border-top: 1px solid #e2e2e2; font-size:14px;">'.esc_html($medicine_duration).'</td>';
								$html	.= '<td style="padding: 15px 20px; border-top: 1px solid #e2e2e2; font-size:14px;">'.esc_html($medicine_usage).'</td>';
								$html	.= '<td style="padding: 15px 20px; border-top: 1px solid #e2e2e2; font-size:14px;">'.esc_html($detail).'</td>';
							}			
						$html	.= '</tr>';
					}
					
					$html	.= '</table>';
				}
		
				if( !empty( $laboratory_tests_name ) ){

					$html	.='<table style="width:96%;  margin:20px auto 0; table-layout: fixed; ">
					<tr style="text-align:left;">
						<td width="100%">';

					$html	.= ''.esc_html__('Required laboratory tests*','doccure').'<br>';
					$html		.= ''.esc_html($laboratory_tests_name).'';
					$html	.= '</td>
				</tr>
			</table>';
				}
		
			$html	.='</div>';
			$html .= '</body></html>';
		return $html_new;
	}
	add_filter('doccure_pdf', 'doccure_pdf',10,1);
}

/**
 * Check Video URL
*/
if(!function_exists('doccure_check_video_url')) {
    function doccure_check_video_url($url){
		$return 	= false;
		$video_platform 	= array('youtube','vimeo','dailymotion','yahoo','bliptv','veoh','viddler');
		$video_platform		= apply_filters('doccure_filter_video_url',$video_platform);
		if (filter_var($url, FILTER_VALIDATE_URL)) {
			foreach ($video_platform as $val) {
				if (strpos($url, $val) !== FALSE) { 
					$return = true;
				}
			}
		}
		return $return;
	}
}
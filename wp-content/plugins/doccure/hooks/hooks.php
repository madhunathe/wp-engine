<?php
/**
 *
 * @package   Doccure Core
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @since 1.0
 */

/**
 * @Wp Login
 * @return 
 */
if (!function_exists('doccure_ajax_login')) {

    function doccure_ajax_login() {        
        $user_array 	= array();
		$json 			= array();
        $user_array['user_login'] 		= sanitize_text_field($_POST['username']);
        $user_array['user_password'] 	= sanitize_text_field($_POST['password']);
		
		//security check
		if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure_core');
			wp_send_json( $json );
		}
		
		$redirect		= !empty( $_POST['redirect'] ) ? esc_url( $_POST['redirect'] ) : '';
		$redirect_id	= !empty( $_POST['redirect_id'] ) ? esc_attr( $_POST['redirect_id'] ) : '';

        if (isset($_POST['rememberme'])) {
            $remember = sanitize_text_field($_POST['rememberme']);
        } else {
            $remember = '';
        }

        if ($remember) {
            $user_array['remember'] = true;
        } else {
            $user_array['remember'] = false;
        }

        if ($user_array['user_login'] == '') {
            echo json_encode(array('type' => 'error', 'loggedin' => false, 'message' => esc_html__('Username should not be empty.', 'doccure_core')));
            exit();
        } elseif ($user_array['user_password'] == '') {
            echo json_encode(array('type' => 'error', 'loggedin' => false, 'message' => esc_html__('Password should not be empty.', 'doccure_core')));
            exit();
        } else {
			$user = wp_signon($user_array, false);
			if (is_wp_error($user)) {
				echo json_encode(array('type' => 'error', 'loggedin' => false, 'message' => esc_html__('Wrong email/username or password.', 'doccure_core')));
			} else {
				
				if( !empty($user->roles[0]) && $user->roles[0] == 'seller' ){
					if(apply_filters('doccure_dokan_active',false) === true){
						$my_dashboard_id = dokan_get_option( 'dashboard', 'dokan_pages' );
						$redirect    = get_the_permalink($my_dashboard_id);
					}
				}else{
					if(!empty($redirect_id)){
						$redirect    = get_the_permalink($redirect_id);
					}elseif(!empty($redirect)){
						$redirect    = $redirect;
					}else{
						$profile_url 		= doccure_get_search_page_uri('doctors');
						
						if( function_exists('doccure_redirect_after_login_page') ){
							$profile_url   	= doccure_redirect_after_login_page($user->ID);
						} 
						$redirect   = $profile_url;
					}
				}

				echo json_encode(array('type' => 'success', 'redirect' => $redirect, 'url' => home_url('/'), 'loggedin' => true, 'message' => esc_html__('Successfully Logged in', 'doccure_core')));
			}
			
        }

        die();
    }

    add_action('wp_ajax_doccure_ajax_login', 'doccure_ajax_login');
    add_action('wp_ajax_nopriv_doccure_ajax_login', 'doccure_ajax_login');
}

/**
 * @Approve Profile 
 * @return 
 */
if( !function_exists( 'doccure_approve_profile' ) ){
	add_action('wp_ajax_doccure_approve_profile', 'doccure_approve_profile');
    add_action('wp_ajax_nopriv_doccure_approve_profile', 'doccure_approve_profile');
	function doccure_approve_profile(){
		//security check
		if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'doccure_core');
			wp_send_json( $json );
		}
		
		$user_profile_id 	= !empty( $_POST['id'] ) ? $_POST['id'] : '';
		$type 				= !empty( $_POST['type'] ) ? $_POST['type'] : '';
		$user_id 			= !empty( $_POST['user_id'] ) ? $_POST['user_id'] : '';
		
		$is_verified 			= get_post_meta($user_profile_id, '_is_verified',true);
		

		if(!empty($user_id)){
			update_post_meta($user_profile_id,'_linked_profile', $user_id);
		}

		if( isset( $type ) && $type === 'reject' ){
			
			update_user_meta($user_id,'_is_verified', 'no');
			update_post_meta($user_profile_id,'_is_verified', 'no');
			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Account has been disabled', 'doccure_core');
			
            wp_send_json($json);
		} else{
			$user_id   	= doccure_get_linked_profile_id($user_profile_id,'post');
			$user_id	= !empty($user_id) ?  intval($user_id) : '';
			$user_meta	= get_userdata($user_id);
			
			if( empty( $user_meta ) ){
				$json['type'] = 'error';
				$json['message'] = esc_html__('No user exists', 'doccure_core');
				wp_send_json($json);
			}

			//Send verification email
			if (class_exists('doccure_Published')) {
				$email_helper = new doccure_Published();

				update_post_meta($user_profile_id,'_is_verified', 'yes');
				update_user_meta($user_id,'_is_verified', 'yes');


				$emailData 						= array();
				$name  							= doccure_get_username( '' ,$user_profile_id );
				$emailData['name'] 				= $name;
				$emailData['email_to']			= $user_meta->user_email;
				$emailData['site_url'] 			= esc_url(home_url('/'));
				$email_helper->publish_approve_user_acount($emailData);
			}

			
			$json = array();
			$json['type'] 		= 'success';
			$json['message'] 	= esc_html__('Account has been approved and email has been sent to user.', 'doccure_core');        
			wp_send_json($json);
		}
	}
}

/**
 * @Registration process Step One
 * @return 
 */
if( !function_exists( 'doccure_process_registration' ) ){
	function doccure_process_registration(){
		global $doccure_options;
		if( function_exists('doccure_is_demo_site') ) { 
			doccure_is_demo_site() ;
		}; //if demo site then prevent
		
		$verify_user			= !empty( $doccure_options['verify_user'] ) ? $doccure_options['verify_user'] : '';
		$remove_location 		= !empty( $doccure_options['remove_location'] ) ? $doccure_options['remove_location'] : 'no';
		
		//security check
		if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure_core');
			wp_send_json( $json );
		}

		//Validation
		$validations = array(
            'first_name' 	=> esc_html__('First Name is required', 'doccure_core'),
			'first_name' 	=> esc_html__('First Name is required', 'doccure_core'),
            'last_name' 	=> esc_html__('Last Name is required.', 'doccure_core'),
            'username'  	=> esc_html__('Username field is required.', 'doccure_core'),
			'location' 			=> esc_html__('Location field is required', 'doccure_core'),
			'password' 			=> esc_html__('Password field is required', 'doccure_core'),
            'verify_password' 	=> esc_html__('Verify Password field is required.', 'doccure_core'),
            'user_type'  		=> esc_html__('User type field is required.', 'doccure_core'),            
            'termsconditions'  	=> esc_html__('You should agree to terms and conditions.', 'doccure_core'),    
			'display_name'  => esc_html__('Your name field is required.', 'doccure_core'),
        );
		
		//unset location if settings true 
		if(!empty($remove_location) && $remove_location == 'yes'){
			unset( $validations['location'] );
		}
		
		//start validating
        foreach ( $validations as $key => $value ) {
            if ( empty( $_POST[$key] ) ) {
                $json['type'] 		= 'error';
                $json['message'] 	= $value;
                wp_send_json( $json );
            }

            //Validate email address
            if ( $key === 'email' ) {
                if ( !is_email( $_POST['email'] ) ) {
                    $json['type'] 		= 'error';
                    $json['message'] 	= esc_html__('Please add a valid email address.', 'doccure_core');
                     wp_send_json( $json );
            	}
       		}
			
			if ($key === 'password') {
                if ( strlen( $_POST[$key] ) < 6 ) {
                    $json['type'] 	 = 'error';
                    $json['message'] = esc_html__('Password length should be minimum 6', 'doccure_core');
                    wp_send_json( $json );
                }
            } 
			
			
            if ($key === 'verify_password') {
                if ( $_POST['password'] != $_POST['verify_password']) {
                    $json['type'] 		= 'error';
                    $json['message'] 	= esc_html__('Password does not match.', 'doccure_core');
                    wp_send_json( $json );
                }
            }    
       	}
		
		extract($_POST);
		
		$email			= !empty( $email ) ? $email : '';
		$display_name	= !empty( $display_name ) ? $display_name : '';
		$first_name		= !empty( $first_name ) ? $first_name : '';
		$last_name		= !empty( $last_name ) ? $last_name : '';
		$username		= !empty( $username ) ? $username : '';
		$location   	= !empty( $location ) ? ( $location ) : '';
       	$password  		= !empty( $password ) ? $password : '';
       	$user_type 		= !empty( $user_type ) ? ( $user_type ) : '';

		$username_exist 	 = username_exists( $username );
       	$user_exists 		 = email_exists( $email );
		
		if(!is_email($email)){
			$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Please add valid email address', 'doccure_core');
            wp_send_json( $json );
		}
		
		if( $username_exist ){
       		$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('Username already registered', 'doccure_core');
            wp_send_json( $json );
       	}
		
		//check exists
       	if( $user_exists ){
       		$json['type'] 		= 'error';
            $json['message'] 	= esc_html__('This email already registered', 'doccure_core');
            wp_send_json( $json );
       	}
		
		//Get user data from session
		
		//Session data validation
		if( empty( $username ) 
		   || empty( $first_name ) 
		   || empty( $last_name ) 
		   || empty( $email ) 
		   || empty( $display_name ) 
		 ) {


			$json['type'] 		= 'error';
			$json['message'] 	= esc_html__( 'Please add all the required fields', 'doccure_core' );
			wp_send_json( $json );
		}		
		
		$post_type		 = $user_type;
		$random_password = $password;
		$user_nicename   = sanitize_title( $display_name );
		
		$userdata = array(
			'user_login'  		=> $username,
			'user_pass'    		=> $random_password,
			'user_email'   		=> $email,  
			'user_nicename'   	=> $user_nicename,  
			'display_name'		=> $display_name
		);
		
        $user_identity 	 = wp_insert_user( $userdata );
		
        if ( is_wp_error( $user_identity ) ) {
            $json['type'] 		= "error";
            $json['message'] 	= esc_html__("Some error occurs, please try again later", 'doccure_core');
            wp_send_json($json);
        } else {
        	global $wpdb;
            wp_update_user( array('ID' => esc_sql( $user_identity ), 'role' => esc_sql( $user_type ), 'user_status' => 1 ) );

            $wpdb->update(
                    $wpdb->prefix . 'users', array('user_status' => 1), array('ID' => esc_sql($user_identity))
            );

            update_user_meta( $user_identity, 'first_name', $first_name );
            update_user_meta( $user_identity, 'last_name', $last_name );  
			update_user_meta( $user_identity, '_is_verified', 'no' );
			update_user_meta($user_identity, 'show_admin_bar_front', false);
			
			//verification link
			$key_hash = md5(uniqid(openssl_random_pseudo_bytes(32)));
			update_user_meta( $user_identity, 'confirmation_key', $key_hash);
			$protocol = is_ssl() ? 'https' : 'http';
			$verify_link = esc_url(add_query_arg(array('key' => $key_hash.'&verifyemail='.$email), home_url('/', $protocol)));

			if( !empty($user_type) && $user_type === 'seller'){
				$vendor_details	= array();
				$vendor_details['store_name']	= $display_name;

				update_user_meta( $user_identity, 'dokan_profile_settings', $vendor_details );

				$blogname 	= wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
				$emailData = array();
				$emailData['name'] 				= $display_name;
				$emailData['password'] 			= $random_password;
				$emailData['email'] 			= $email;
				$emailData['username'] 			= $username;
				$emailData['site'] 				= $blogname;
				$emailData['verification_link'] = $verify_link;
				
				if (class_exists('doccureRegisterNotify')) {
					$email_helper = new doccureRegisterNotify();
					$email_helper->send_seller_user_email($emailData);
				}
				
			}else{
				//Create Post
				$user_post = array(
					'post_title'    => wp_strip_all_tags( $display_name ),
					'post_status'   => 'publish',
					'post_author'   => $user_identity,
					'post_type'     => $post_type,
				);

				$post_id    = wp_insert_post( $user_post );

				if( !is_wp_error( $post_id ) ) {

					$profile_data	= array();
					$profile_data['am_first_name']	= $first_name;
					$profile_data['am_last_name']	= $last_name;
					update_post_meta($post_id, 'am_' . $post_type . '_data', $profile_data);

					//Update user linked profile
					update_user_meta( $user_identity, '_linked_profile', $post_id );
					update_post_meta( $post_id, '_is_verified', 'no' );						
					update_post_meta($post_id, '_linked_profile', $user_identity);
					update_post_meta( $post_id, 'is_featured', 0 );
					
					if( !empty( $location ) ){
						$locations = get_term_by( 'slug', $location, 'locations' );
						$location_data = array();
						if( !empty( $locations ) ){
							$location_data[0] = $locations->term_id;
							wp_set_post_terms( $post_id, $locations->term_id, 'locations' );
						}
					}

					//update privacy settings
					$settings		 = doccure_get_account_settings($user_type);
					if( !empty( $settings ) ){
						foreach( $settings as $key => $value ){
							$val = !empty($key) && $key === '_profile_blocked' ? 'off' : 'on';
							update_post_meta($post_id, $key, $val);
						}
					}

					$user_type	= doccure_get_user_type( $user_identity );
					if( !empty( $user_type ) && $user_type === 'doctors' ) {
						if( function_exists('doccure_get_package_type') ){
							$trail_doctors_id	= doccure_get_package_type( 'package_type','trail_doctors');
							if( !empty( $trail_doctors_id ) ){
								doccure_update_package_data( $trail_doctors_id ,$user_identity,'',1 );
							}
						}
					}

					if( function_exists('doccure_full_name') ) {
						$name	= doccure_full_name($post_id);
					} else {
						$name	= $first_name;
					}

					//Send email to users
					if (class_exists('doccure_Email_helper')) {
						$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
						$emailData = array();
						$emailData['name'] 				= $name;
						$emailData['password'] 			= $random_password;
						$emailData['email'] 			= $email;
						$emailData['username'] 			= $username;
						$emailData['site'] 				= $blogname;
						$emailData['verification_link'] = $verify_link;

						//Send code
						if (class_exists('doccureRegisterNotify')) {
							$email_helper = new doccureRegisterNotify();
							if( !empty($user_type) && $user_type === 'doctors' ){
								$email_helper->send_doctor_email($emailData);
							} else if( !empty($user_type) && $user_type === 'hospitals' ){
								$email_helper->send_hospital_email($emailData);
							}else if( !empty($user_type) && $user_type === 'regular_users' ){
								$email_helper->send_regular_user_email($emailData);
								update_post_meta( $post_id, '_is_verified', 'yes' );
								update_user_meta( $user_identity, '_is_verified', 'yes' );
							}
						}

					}		    

				} else {
					$json['type'] 		= 'error';
					$json['message'] 	= esc_html__('Some error occurs, please try again later', 'doccure_core');                
					wp_send_json($json);
				}
			}
			
			//Send admin email
			if (class_exists('doccureRegisterNotify')) {
				$email_helper = new doccureRegisterNotify();
				$email_helper->send_admin_email($emailData);
			}

			//verification
			if( empty( $verify_user ) || $verify_user == 'remove'){
				update_post_meta( $post_id, '_is_verified', 'yes' );
				update_user_meta( $user_identity, '_is_verified', 'yes' );
				if( !empty($user_type) && $user_type == 'seller' ){
					update_user_meta( $user_identity, 'dokan_enable_selling', 'yes' );
				}
			}
						
		}
		
		//User Login
		$user_array = array();
		$user_array['user_login'] 		= $email;
		$user_array['user_password'] 	= $random_password;
		wp_signon($user_array, false);

		if( empty( $verify_user ) || $verify_user === 'yes'){	
			$json_message 		= esc_html__("Your account has been created. Please check your email for the verification", 'doccure_core');
		} else if( empty( $verify_user ) || $verify_user === 'remove'){
			$json_message 		= esc_html__("Thank you so much for the registration.", 'doccure_core');
		}else{
			$json_message 		= esc_html__("Your account has been created. After the verification your will be do anything on the site", 'doccure_core');
		}	       
		
		if( !empty($user_type) && $user_type == 'seller' ){
			if(apply_filters('doccure_dokan_active',false) === true){
				$my_dashboard_id = dokan_get_option( 'dashboard', 'dokan_pages' );
				$return_page    = get_the_permalink($my_dashboard_id);
			}
		}else{
			$return_page = doccure_get_search_page_uri('dashboard'); 
			if( function_exists('doccure_redirect_after_login_page') ){
				$return_page   	= doccure_redirect_after_login_page($user_identity);
			}
		}
		
		$json['type'] 			= 'success';
		$json['message'] 		= $json_message;
		$json['retrun_url'] 	= htmlspecialchars_decode($return_page);
		wp_send_json($json);
	}
	add_action('wp_ajax_doccure_process_registration', 'doccure_process_registration');
    add_action('wp_ajax_nopriv_doccure_process_registration', 'doccure_process_registration');
}


/**
 * @Mailchimp List
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_mailchimp_list')) {

    function doccure_mailchimp_list() {
		global $doccure_options;
        $mailchimp_list 	= array();
        $mailchimp_list[0] 	= esc_html__('Select List', 'doccure_core');
		$mailchimp_option 	= !empty( $doccure_options['mailchimp_key'] ) ? $doccure_options['mailchimp_key'] : '';

        if (!empty($mailchimp_option)) {
            if (class_exists('doccure_MailChimp')) {
                $mailchim_obj = new doccure_MailChimp();
                $lists = $mailchim_obj->doccure_mailchimp_list($mailchimp_option);

                if (is_array($lists) && isset($lists['data'])) {
                    foreach ($lists['data'] as $list) {
                        if (!empty($list['name'])) :
                            $mailchimp_list[$list['id']] = $list['name'];
                        endif;
                    }
                }
            }
        }
        return $mailchimp_list;
    }

}


/**
 * @User nav
 * @return 
 */
if( !function_exists( 'doccure_print_user_nav' ) ){
	add_action('doccure_print_user_nav', 'doccure_print_user_nav', 10);
	function doccure_print_user_nav(){
		$doccure_meu	= new doccure_Profile_Menu;
		$doccure_meu->doccure_profile_menu_top();
	}
}

/**
 * @Login/Form
 * @return 
 */
if( !function_exists( 'doccure_print_login_form' ) ){
	add_action('doccure_print_login_form', 'doccure_print_login_form', 10);
	function doccure_print_login_form(){
		global $doccure_options,$post;
		$is_auth		= !empty( $doccure_options['user_registration'] ) ? $doccure_options['user_registration'] : '';
		$is_register	= !empty( $doccure_options['registration_form'] ) ? $doccure_options['registration_form'] : '';
		$is_login		= !empty( $doccure_options['login_form'] ) ? $doccure_options['login_form'] : '';
		$redirect		= !empty( $_GET['redirect'] ) ? esc_url( $_GET['redirect'] ) : '';
		
		$current_page	= '';
		if ( is_singular('doctors')){
			$current_page	= !empty( $post->ID ) ? esc_attr( $post->ID ) : '';
		}
			
		$signup_page_slug   = doccure_get_signup_page_url();  
		ob_start(); 
		
		if ( is_user_logged_in() ) {
			$doccure_meu	= new doccure_Profile_Menu;
			$doccure_meu->doccure_profile_menu_top();
		} else{
			
		if( !empty( $is_auth ) ){?>
		
			<div class="dc-loginarea">
				<?php if( !empty( $is_login ) ) {?>
					<figure class="dc-userimg">
						<img src="<?php echo esc_url(get_template_directory_uri());?>/images/user.png" alt="<?php esc_html_e('user', 'doccure_core'); ?>">
					</figure>
					<div class="dc-loginoption">
						<a href="javascript:;" data-toggle="modal" data-target="#dc-loginpopup" class="dc-loginbtn"><?php esc_html_e('Login','doccure_core');?></a>
					</div>
				<?php } ?>
				<?php if ( !empty($is_register) ) {?>
					<a href="<?php echo esc_url(  $signup_page_slug ); ?>" class="dc-btn"><?php esc_html_e('Join Now','doccure_core');?></a>
				<?php }?> 
			</div>
			<?php }
		}
	}
}


/**
 * @Login user form
 * @type delete
 */
if (!function_exists('doccure_login_form')) {
	add_action( 'doccure_login_form', 'doccure_login_form' );
    function doccure_login_form() {
		global $doccure_options,$post;
		$is_auth		= !empty( $doccure_options['user_registration'] ) ? $doccure_options['user_registration'] : '';
		$is_register	= !empty( $doccure_options['registration_form'] ) ? $doccure_options['registration_form'] : '';
		$is_login		= !empty( $doccure_options['login_form'] ) ? $doccure_options['login_form'] : '';
		$redirect		= !empty( $_GET['redirect'] ) ? esc_url( $_GET['redirect'] ) : '';
		
		$current_page	= '';
		if ( is_singular('doctors')){
			$current_page	= !empty( $post->ID ) ? esc_attr( $post->ID ) : '';
		}
			
		$signup_page_slug   = doccure_get_signup_page_url();
		?>
		<div class="modal fade dc-loginformpop" role="dialog" id="dc-loginpopup"> 
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="dc-modalcontent modal-content">	
					<div class="dc-loginformholds">
						<div class="dc-loginheader">
							<span><?php esc_html_e('Login','doccure_core');?></span>
							<a href="javascript:;" class="dc-closebtn close dc-close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e('Close','doccure_core');?>"><i class="fa fa-times"></i></a>
						</div>
						<form class="dc-formtheme dc-loginform do-login-form">
							<fieldset>
								<div class="form-group">
									<input type="text" name="username" class="form-control" placeholder="<?php esc_html_e('Username', 'doccure_core'); ?>">
								</div>
								<div class="form-group">
									<input type="password" name="password" class="form-control" placeholder="<?php esc_html_e('Password', 'doccure_core'); ?>">
								</div>
								<div class="dc-logininfo">
									<span class="dc-checkbox">
										<input id="dc-login" type="checkbox" name="rememberme">
										<label for="dc-login"><?php esc_html_e('Keep me logged in','doccure_core');?></label>
									</span>
									<input type="submit" class="dc-btn do-login-button" data-id="<?php echo intval($current_page);?>" value="<?php esc_attr_e('Login','doccure_core');?>">
								</div>
								<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect );?>">
								<input type="hidden" name="redirect_id" value="<?php echo intval($current_page);?>">
							</fieldset>
							<div class="dc-loginfooterinfo">
								<a href="javascript:;" class="dc-forgot-password"><?php esc_html_e('Forgot password?','doccure_core');?></a>
								<?php if ( !empty($is_register) ) {?>
									<a href="<?php echo esc_url(  $signup_page_slug ); ?>"><?php esc_html_e('Create account','doccure_core');?></a>
								<?php }?>
							</div>
						</form>
						<form class="dc-formtheme dc-loginform do-forgot-password-form dc-hide-form">
							<fieldset>
								<div class="form-group">
									<input type="email" name="email" class="form-control get_password" placeholder="<?php esc_html_e('Email', 'doccure_core'); ?>">
								</div>

								<div class="dc-logininfo">
									<a href="javascript:;" class="dc-btn do-get-password"><?php esc_html_e('Get Pasword','doccure_core');?></a>
								</div>                                                               
							</fieldset>
							<div class="dc-loginfooterinfo">
								<a href="javascript:;" class="dc-show-login" data-toggle="modal" data-target="#dc-loginpopup"><?php esc_html_e('Login Now','doccure_core');?></a>
								<?php if ( !empty($is_register) ) {?>
									<a href="<?php echo esc_url(  $signup_page_slug ); ?>"><?php esc_html_e('Create account','doccure_core');?></a>
								<?php }?>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}


/**
 * @save project post meta data
 * @type delete
 */
if (!function_exists('doccure_delete_wp_user')) {
	add_action( 'delete_user', 'doccure_delete_wp_user' );
    function doccure_delete_wp_user($user_id) {
		$linked_profile   	= doccure_get_linked_profile_id($user_id);
		if( !empty( $linked_profile ) ){
		 	wp_delete_post( $linked_profile, true);
		}
	}
}

/**
 * @get default color schemes
 * @return 
 */
if (!function_exists('doccure_get_page_color')) {
	add_filter('doccure_get_page_color','doccure_get_page_color',10,1);
	function doccure_get_page_color($color='#5dc560'){
		$post_name = doccure_get_post_name();
		$pages_color	= array(
			'home-v5'		=> '#5dc560',
			'home-page-8'	=> '#017EBE',
			'home-v2'		=> '#5dc560',
			'header-v2'		=> '#5dc560',
		);

		if( isset( $_SERVER["SERVER_NAME"] ) && $_SERVER["SERVER_NAME"] === 'dreamstechnologies.com' ){
			if( isset( $pages_color[$post_name] ) ){
				return $pages_color[$post_name];
			} else{
				return $color;
			}
		} else{
			return $color;
		}
	}
}

/**
 * @taxonomy admin radio button
 * @return {}
 */
/**
 * @taxonomy admin radio button
 * @return {}
 */
if (!function_exists('doccure_Walker_Category_Radio_Checklist')) {
	add_filter( 'wp_terms_checklist_args', 'doccure_Walker_Category_Radio_Checklist', 10, 2 );
	function doccure_Walker_Category_Radio_Checklist( $args, $post_id ) {
		if ( ! empty( $args['taxonomy'] ) && $args['taxonomy'] === 'locations' ) {
			global $doccure_options;
		
			$multiple_locations			= !empty( $doccure_options['multiple_locations'] ) ? $doccure_options['multiple_locations'] : 'no';
			if(!empty($multiple_locations) && $multiple_locations === 'no'){
				if ( empty( $args['walker'] ) || is_a( $args['walker'], 'Walker' ) ) { 
				if ( ! class_exists( 'doccure_Walker_Category_Radio' ) ) {
					
					class doccure_Walker_Category_Radio extends Walker_Category_Checklist {
						public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
							
							if ( empty( $args['taxonomy'] ) ) {
								$taxonomy = 'category';
							} else {
								$taxonomy = $args['taxonomy'];
							}

							if ( $taxonomy == 'category' ) {
								$name = 'post_category';
							} else {
								$name = 'tax_input[' . $taxonomy . ']';
							}

							$args['popular_cats'] = empty( $args['popular_cats'] ) ? array() : $args['popular_cats'];
							$class = in_array( $category->term_id, $args['popular_cats'] ) ? ' class="main-category"' : '';

							$args['selected_cats'] = empty( $args['selected_cats'] ) ? array() : $args['selected_cats'];
							if ( ! empty( $args['list_only'] ) ) {
								$is_checked 	= 'false';
								$main_class 	= 'category';

								if ( in_array( $category->term_id, $args['selected_cats'] ) ) {
									$main_class 	.= ' selected';
									$is_checked 	 = 'true';
								}

								$output .= "\n" . '<li' . $class . '>' .
									'<div class="' . $main_class . '" data-term-id=' . $category->term_id .
									' tabindex="0" role="checkbox" aria-checked="' . $is_checked . '">' .
									esc_html( apply_filters( 'the_category', $category->name ) ) . '</div>';
							} else {
								$output .= "\n<li id='{$taxonomy}-{$category->term_id}'$class>" .
								'<label class="dc-radios"><input value="' . $category->term_id . '" type="radio" name="'.$name.'[]" id="dc-'.$taxonomy.'-' . $category->term_id . '"' .
								checked( in_array( $category->term_id, $args['selected_cats'] ), true, false ) .
								disabled( empty( $args['disabled'] ), false, false ) . ' /> ' .
								esc_html( apply_filters( 'the_category', $category->name ) ) . '</label>';
							}
						}
					}
				}
				
				$args['walker'] = new doccure_Walker_Category_Radio;
			}
			}
		}
		return $args;
	}
}

/**
 * @get default color schemes
 * @return 
 */
if (!function_exists('doccure_get_domain')) {
	add_filter('doccure_get_domain','doccure_get_domain',10,1);
	function doccure_get_domain(){
		if( isset( $_SERVER["SERVER_NAME"] ) && $_SERVER["SERVER_NAME"] === 'dreamstechnologies.com' ){
			return true;
		} else{
			return false;
		}
	}
}

/**
 * @Demo Ready
 * @return {}
 */
if (!function_exists('doccure_is_demo_site')) {
	function doccure_is_demo_site($message=''){
		$json = array();
		$message	= !empty( $message ) ? $message : esc_html__("Sorry! you are restricted to perform this action on demo site.",'doccure_core' );
		
		if( isset( $_SERVER["SERVER_NAME"] ) 
			&& $_SERVER["SERVER_NAME"] === 'dreamstechnologies.com' ){
			$json['type']	    =  "error";
			$json['message']	=  $message;
			wp_send_json($json);
		}
	}
}
/**
 * @get default color schemes
 * @return 
 */
if (!function_exists('doccure_doccure_options_js')) {
	add_action('wp_footer','doccure_doccure_options_js',90);
	function doccure_doccure_options_js(){
		global $doccure_options;
		if( !empty( $doccure_options['custom_js'] ) ){?>
			<script>
				<?php echo do_shortcode($doccure_options['custom_js']); ?>
			</script>
			<?php
		}
	}
}

/**
 * Data print
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( ! function_exists( 'pre_print' ) ) {
    function pre_print( $data ) {
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
}

/**
 * Prepare social sharing links 
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_prepare_profile_social_sharing')) {

    function doccure_prepare_profile_social_sharing($default_icon = 'false', $social_title = 'Share', $title_enable = 'true', $classes = '', $thumbnail = '') {        
        global $wp_query,$doccure_options;
        $output    = '';
		$twitter_username = !empty($doccure_options['twitter_username']) ? $doccure_options['twitter_username'] : 'twitter';
                        
        $permalink  = get_the_permalink();
        $title      =  get_the_title();

        $output .= "<div class='dc-widgetcontent'><ul class='dc-socialiconssimple'>";
        if ($title_enable == 'true' && !empty( $social_title )) {
            $output .= '<li class="dc-sharejob"><span>' . $social_title . ':</span></li>';
        }       
            $output .= '<li class="dc-facebook"><a href="//www.facebook.com/sharer.php?u=' . urlencode(esc_url($permalink)) . '" onclick="window.open(this.href, \'post-share\',\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fab fa-facebook-f"></i><span>'.esc_html__("Share on Facebook", 'doccure_core').'</span></a></li>';               
            $output .= '<li class="dc-twitter"><a href="//twitter.com/intent/tweet?text=' . htmlspecialchars(urlencode(html_entity_decode($title, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8') . '&url=' . urlencode(esc_url($permalink)) . '&via=' . urlencode(!empty($twitter_username) ? $twitter_username : get_bloginfo('name') ) . '"  ><i class="fab fa-twitter"></i><span>'.esc_html__("Share on Twitter", 'doccure_core').'</span></a></li>';
            $tweets = '!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");';
            wp_add_inline_script('doccure-callback', $tweets);       
            $output .= '<li class="dc-googleplus"><a href="//plus.google.com/share?url=' . esc_url($permalink) . '" onclick="window.open(this.href, \'post-share\',\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fab fa-google-plus-g"></i><span>'.esc_html__("Share on Google", 'doccure_core').'</span></a></li>';        
            $output .= '<li class="dc-pinterestp"><a href="//pinterest.com/pin/create/button/?url=' . esc_url($permalink) . '&amp;media=' . (!empty($thumbnail) ? $thumbnail : '' ) . '&description=' . htmlspecialchars(urlencode(html_entity_decode($title, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8') . '" onclick="window.open(this.href, \'post-share\',\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fab fa-pinterest-p"></i><span>'.esc_html__("Share on Pinterest", 'doccure_core').'</span></a></li>';
        $output .= '</ul></div>';
		
        echo do_shortcode($output, true);
    }
}

/**
 * Prepare social sharing links for job
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_prepare_social_sharing')) {

    function doccure_prepare_social_sharing($default_icon = 'false', $social_title = '', $title_enable = 'true', $classes = '', $thumbnail = '') {        
        global $wp_query,$doccure_options;
        $output    = '';
		$twitter_username = !empty($doccure_options['twitter_username']) ? $doccure_options['twitter_username'] : 'twitter';
		$social_facebook 	= !empty($doccure_options['social_facebook']) ? $doccure_options['social_facebook'] : '';
		$social_gmail 		= !empty($doccure_options['social_gmail']) ? $doccure_options['social_gmail'] : '';
		$social_pinterest 	= !empty($doccure_options['social_pinterest']) ? $doccure_options['social_pinterest'] : '';
		$social_twitter 	= !empty($doccure_options['social_twitter']) ? $doccure_options['social_twitter'] : '';
                        
        $permalink  = get_the_permalink();
        $title      =  get_the_title();

        $output .= "<ul class='d-flex dc-tags-social'>";
        if ( $title_enable == 'true' && !empty( $social_title )) {
            $output .= '<li class="dc-sharejob">' . $social_title . ':</li>';
		}       
		if( !empty($social_facebook) ){
            $output .= '<li class="dc-fb"><a href="//www.facebook.com/sharer.php?u=' . urlencode(esc_url($permalink)) . '" onclick="window.open(this.href, \'post-share\',\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fab fa-facebook-f"></i></a></li>';               
		}
		if( !empty($social_twitter) ){
			$output .= '<li class="dc-twit"><a href="//twitter.com/intent/tweet?text=' . htmlspecialchars(urlencode(html_entity_decode($title, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8') . '&url=' . urlencode(esc_url($permalink)) . '&via=' . urlencode(!empty($twitter_username) ? $twitter_username : get_bloginfo('name') ) . '"  ><i class="fab fa-twitter"></i></a></li>';
		
            $tweets = '!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");';
			wp_add_inline_script('doccure-callback', $tweets);  
		}     
		   
		if(!empty($social_gmail)){
			$output .= '<li class="dc-google-plus"><a href="//plus.google.com/share?url=' . esc_url($permalink) . '" onclick="window.open(this.href, \'post-share\',\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fab fa-google-plus-g"></i></a></li>';        
		}

		if( !empty($social_pinterest) ){
			$output .= '<li class="dc-instg"><a href="//pinterest.com/pin/create/button/?url=' . esc_url($permalink) . '&amp;media=' . (!empty($thumbnail) ? $thumbnail : '' ) . '&description=' . htmlspecialchars(urlencode(html_entity_decode($title, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8') . '" onclick="window.open(this.href, \'post-share\',\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fab fa-pinterest-p"></i></a></li>';
		}
		$output .= '</ul>';
		
        echo do_shortcode($output, true);
    }
}

/**
 * @Set Post Views
 * @return {}
 */
if (!function_exists('doccure_post_views')) {

    function doccure_post_views($post_id = '',$key='set_blog_view') {

        if (!is_single())
            return;

        if (empty($post_id)) {
            global $post;
            $post_id = $post->ID;
        }

        if (!isset($_COOKIE[$key . $post_id])) {
            setcookie($key . $post_id, $key, time() + 3600);
            $view_key = $key;

            $count = get_post_meta($post_id, $view_key, true);

            if ($count == '') {
                $count = 0;
                delete_post_meta($post_id, $view_key);
                add_post_meta($post_id, $view_key, 1);
            } else {
                $count++;
                update_post_meta($post_id, $view_key, $count);
            }
        }
    }

    add_action('doccure_post_views', 'doccure_post_views', 5, 2);
}

/**
 * @User social fields
 * @return fields
 */
if( !function_exists('doccure_user_social_fields')){
	function doccure_user_social_fields($user_fields) {
		$user_fields['twitter'] = esc_html__('Twitter', 'doccure_core');
		$user_fields['facebook'] = esc_html__('Facebook', 'doccure_core');
		$user_fields['google'] = esc_html__('Google+', 'doccure_core');
		$user_fields['tumblr'] = esc_html__('Tumbler', 'doccure_core');
		$user_fields['instagram'] = esc_html__('Instagram', 'doccure_core');
		$user_fields['pinterest'] = esc_html__('Pinterest', 'doccure_core');
		$user_fields['skype'] = esc_html__('Skype', 'doccure_core');
		$user_fields['linkedin'] = esc_html__('Linkedin', 'doccure_core');

		return $user_fields;
	}
	add_filter('user_contactmethods', 'doccure_user_social_fields');
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
		if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
			$json['type'] = 'error';
			$json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it againe', 'doccure_core');
			wp_send_json( $json );
		}
		
        if (empty($post_id)) {
            $json['type'] 	 = 'error';
			$json['message'] = esc_html__('Post id is required', 'doccure_core');
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
			
			$json['html'] 	 = sprintf( _n( '<i class="ti-heart"></i> %s Like', '<i class="ti-heart"></i> %s Likes', $count, 'doccure_core' ), $count );
		
			$json['type'] 	 = 'success';
			$json['message'] = esc_html__('Post has been liked', 'doccure_core');
			wp_send_json( $json );
        } else{
			$json['type'] 	 = 'error';
			$json['message'] = esc_html__('You have already liked this post', 'doccure_core');
			wp_send_json( $json );
		}
    }

    add_action('wp_ajax_doccure_post_likes', 'doccure_post_likes');
    add_action('wp_ajax_nopriv_doccure_post_likes', 'doccure_post_likes');
}


/**
 * @Create profile from admin create user
 * @type delete
 */
if (!function_exists('doccure_create_wp_user')) {
	add_action( 'user_register', 'doccure_create_wp_user' );
    function doccure_create_wp_user($user_id) {
		if( !empty( $user_id )  ) {
			$user_meta	= get_userdata($user_id);
			$title		= $user_meta->first_name.' '.$user_meta->last_name;
			$post_type	= !empty($user_meta->roles[0]) ? esc_attr($user_meta->roles[0]) : '';

			if( !empty($post_type) && ( $post_type === 'doctors' || $post_type	=== 'hospitals' || $post_type	=== 'regular_users' ) ){
				$post_data	= array(
								'post_title'	=> wp_strip_all_tags($title),
								'post_author'	=> $user_id,
								'post_status'   => 'publish',
								'post_type'		=> $post_type,
							);

				$post_id	= wp_insert_post( $post_data );

				if( !empty( $post_id ) ) {
					update_post_meta($post_id, '_linked_profile',intval($user_id));
					add_user_meta( $user_id, '_linked_profile', $post_id);
					
					update_user_meta( $user_identity, 'first_name', $user_meta->first_name );
					update_user_meta( $user_identity, 'last_name', $user_meta->last_name );  
					update_user_meta( $user_identity, 'show_admin_bar_front', false);
					
					$fw_options = array();
	
					//Update user linked profile
					update_user_meta( $user_id, '_linked_profile', $post_id );
					update_post_meta( $post_id, '_is_verified', 'yes' );
					update_post_meta( $post_id, 'is_featured', 0 );
					update_user_meta( $user_id, '_is_verified', 'yes' );
					update_post_meta($post_id, '_profile_blocked', 'off');

					if( $post_type == 'doctors' ){
						$user_type	= 'doctors';
						update_post_meta($post_id, '_user_type', $post_type);
					} elseif( $post_type == 'hospitals' ){
						$user_type	= 'hospitals';
						update_post_meta($post_id, '_user_type', $post_type);
					} elseif( $post_type == 'regular_users' ){
						$user_type	= 'regular_users';
						update_post_meta($post_id, '_user_type', $post_type);
					}

					//update privacy settings
					$settings		 = doccure_get_account_settings($user_type);
					if( !empty( $settings ) ){
						foreach( $settings as $key => $value ){
							$val = $key === '_profile_blocked' ? 'off' : 'on';
							update_post_meta($post_id, $key, $val);
						}
					}

					$package_id			= doccure_get_package_type( 'package_type','trail_doctors');
					if( $user_type === 'doctors' && !empty($package_id) ) {
						doccure_update_package_data( $package_id ,$user_id,'',1 );
					}
				}
			}
		}
	}
}

/**
 * @Redirect pagination for single pages
 * @type delete
 */

if (!function_exists('doccure_redirect_canonical')) {
	add_filter('redirect_canonical','doccure_redirect_canonical');

	function doccure_redirect_canonical($redirect_url) {
		if (is_singular()){
			$redirect_url = false;
		}
		
		return $redirect_url;
	}
}

/**
 * @Redirect pagination for single pages
 * @type delete
 */

if (!function_exists('doccure_get_tax_query')) {
	add_filter('doccure_get_tax_query','doccure_get_tax_query',10,4);

	function doccure_get_tax_query($default,$id,$tag,$args) {
		if (!empty( $args )){
			$term_data	= wp_get_post_terms($id, $tag,$args);
		} else{
			$term_data	= wp_get_post_terms($id, $tag);
		}

		return $term_data;
	}
}

/**
 * @get tooltip settings
 * @return 
 */
if (!function_exists('doccure_get_tooltip')) {
	function doccure_get_tooltip($type,$element){
		if( empty( $element ) ){return;}
		$type	= !empty( $type ) ? $type : 'element';	
		$tipso =  true;
		
		if (is_page_template('directory/dashboard.php') || $tipso === true ) {
				global	$doccure_options;
				$tip_title = !empty( $doccure_options['tip_'.$element] ) ? $doccure_options['tip_'.$element] : '';
		
				if( !empty( $tip_title ) ){
					if( !empty( $tip_title ) ){?>
						<span class="dc-<?php echo esc_attr( $type );?>-hint"><i data-tipso="<?php echo esc_attr( $tip_title );?>" class="fa fa-question-circle template-content tipso_style dc-tipso"></i></span>
					<?php 
					}

				}
		}
	}
	add_action('doccure_get_tooltip', 'doccure_get_tooltip',10,2);
}

/**
 * @get tooltip settings
 * @return 
 */
if (!function_exists('doccure_get_tooltip_data')) {
	function doccure_get_tooltip_data($type,$element){
		if( empty( $element ) ){return;}
		$type	= !empty( $type ) ? $type : 'element';	
		$tipso =  true;

		global	$doccure_options;
		$tip_title = !empty( $doccure_options['tip_'.$element] ) ? $doccure_options['tip_'.$element] : '';

		if( !empty( $tip_title ) ){
			echo 'data-tipso="'.esc_attr( $tip_title ).'"';
		}
	}
	add_action('doccure_get_tooltip_data', 'doccure_get_tooltip_data',10,2);
}
/**
 * Update tootip fields
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if ( !function_exists( 'doccure_tooltip_fields' ) ) {

	function doccure_tooltip_fields( $key	= '' ) {
		$list 	= array(
					'am_sub_heading' => esc_html__('Sub Heading','doccure_core'),
					'am_first_name'  => esc_html__('First Name','doccure_core'),
					'am_last_name'  => esc_html__('Last Name','doccure_core'),
					'display_name'  => esc_html__('Display Name','doccure_core'),
					'am_web_url' 	=> esc_html__('Web url','doccure_core'),
					'am_phone_numbers' 	=> esc_html__('Phone numbers','doccure_core'),
					'am_starting_price'  => esc_html__('Doctor checkup starting price','doccure_core'),
					'longitude'  	=> esc_html__('Longitude','doccure_core'),
					'latitude'  	=> esc_html__('Latitude','doccure_core'),
					'am_registration_number'  => esc_html__('Registration number','doccure_core'),
					'paypal_email'  => esc_html__('PayPal Email Address','doccure_core'),
					'bank_account_name'  => esc_html__('Bank Account Name ','doccure_core'),
					'bank_account_number'  => esc_html__('Bank Account Number ','doccure_core'),
					'bank_name'  => esc_html__('Bank Name','doccure_core'),
					'bank_routing_number'  => esc_html__('Bank Routing Number','doccure_core'),
					'bank_iban'  => esc_html__('Bank IBAN','doccure_core'),
					'bank_bic_swift'  => esc_html__('Bank BIC/SWIFT','doccure_core'),
					'post_title'  => esc_html__('Post title','doccure_core'),
					'generate_prescription'  => esc_html__('Generate prescription','doccure_core'),
					'download_prescription'  => esc_html__('Download prescription','doccure_core'),
					'start_chat'  => esc_html__('Start chat','doccure_core'),
				);
		
		$fields	= apply_filters('doccure_filters_tooltip_fields',$list);
		
		if( !empty( $key ) ){
			return !empty( $list[$key] ) ? $list[$key] : '';
		}
		
		return $fields;
	}
}

/**
 * List social media
 *
 * @throws error
 * @author Dreams Technologies<support@dreamstechnologies.com>
 * @return 
 */
if (!function_exists('doccure_list_socila_media')) {

    function doccure_list_socila_media( ) {
		$social_profile = array ( 
						'facebook'	=> array (
											'class'	=> 'dc-facebook',
											'icon'	=> 'fab fa-facebook-f',
											'lable' => esc_html__('Facebook','doccure_core'),
										),
						'twitter'	=> array (
											'class'	=> 'dc-twitter',
											'icon'	=> 'fab fa-twitter',
											'lable' => esc_html__('Twitter','doccure_core'),
										),
						'linkedin'	=> array (
											'class'	=> 'dc-linkedin',
											'icon'	=> 'fab fa-linkedin-in',
											'lable' => esc_html__('LinkedIn','doccure_core'),
										),
						'googleplus'=> array (
											'class'	=> 'dc-googleplus',
											'icon'	=> 'fab fa-google-plus-g',
											'lable' => esc_html__('Google Plus','doccure_core'),
										),
						'youtube'=> array (
											'class'	=> 'dc-youtube',
											'icon'	=> 'fab fa-youtube',
											'lable' => esc_html__('Google Plus','doccure_core'),
										),
						'instagram'=> array (
											'class'	=> 'dc-instagram',
											'icon'	=> 'fab fa-instagram',
											'lable' => esc_html__('Instagram','doccure_core'),
										)
		
						);
		
		$social_profile	= apply_filters('doccure_filter_list_socila_media',$social_profile);
		
		return $social_profile;
    }
	
	add_action('doccure_list_socila_media', 'doccure_list_socila_media');
}


/**
 * @Social media icons
 * @return link
 */
if (!function_exists('doccure_get_social_media_icons_list')) {
    function doccure_get_social_media_icons_list($settings='') {
        $list	= array(
			'facebook'	=> array(
				'title' 		=> esc_html__('Facebook Link?', 'doccure_core'),
				'placeholder' 	=> esc_html__('Facebook Link', 'doccure_core'),
				'is_url'   		=> true,
				'icon'			=> 'fab fa-facebook',
				'classses'		=> 'wt-facebook',
				'color'			=> '#3b5998',
			),
			'twitter'	=> array(
				'title' 	=> esc_html__('Twitter Link?', 'doccure_core'),
				'placeholder' 	=> esc_html__('Twitter Link', 'doccure_core'),
				'is_url'   		=> true,
				'icon'			=> 'fab fa-twitter',
				'classses'		=> 'wt-twitter',
				'color'			=> '#55acee',
			),
			'linkedin'	=> array(
				'title' 	=> esc_html__('Linkedin Link?', 'doccure_core'),
				'placeholder' 	=> esc_html__('Linkedin Link', 'doccure_core'),
				'is_url'   	=> true,
				'icon'		=> 'fab fa-linkedin',
				'classses'		=> 'wt-linkedin',
				'color'			=> '#0177b5',
			),
			'skype'	=> array(
				'title' 	=> esc_html__('Skype ID?', 'doccure_core'),
				'placeholder' 	=> esc_html__('Skype ID', 'doccure_core'),
				'is_url'   	=> false,
				'icon'		=> 'fab fa-skype',
				'classses'		=> 'wt-skype',
				'color'			=> '#00aff0',
			),
			'pinterest'	=> array(
				'title' 	=> esc_html__('Pinterest Link?', 'doccure_core'),
				'placeholder' 	=> esc_html__('Pinterest Link', 'doccure_core'),
				'is_url'   	=> true,
				'icon'		=> 'fab fa-pinterest-p',
				'classses'		=> 'wt-pinterestp',
				'color'			=> '#bd081c',
			),
			'tumblr'	=> array(
				'title' 	=> esc_html__('Tumblr Link?', 'doccure_core'),
				'placeholder' 	=> esc_html__('Tumblr Link', 'doccure_core'),
				'is_url'   	=> true,
				'icon'		=> 'fab fa-tumblr',
				'classses'		=> 'wt-tumblr',
				'color'			=> '#36465d',
			),
			'instagram'	=> array(
				'title' 	=> esc_html__('Instagram Link?', 'doccure_core'),
				'placeholder' 	=> esc_html__('Instagram Link', 'doccure_core'),
				'is_url'   	=> true,
				'icon'		=> 'fab fa-instagram',
				'classses'		=> 'wt-instagram',
				'color'			=> '#c53081',
			),
			'flickr'	=> array(
				'title' 	=> esc_html__('Flickr Link?', 'doccure_core'),
				'placeholder' 	=> esc_html__('Flickr Link', 'doccure_core'),
				'is_url'   	=> true,
				'icon'		=> 'fab fa-flickr',
				'classses'		=> 'wt-flickr',
				'color'			=> '#ff0084',
			),
			'medium'	=> array(
				'title' 	=> esc_html__('Medium Link?', 'doccure_core'),
				'placeholder' 	=> esc_html__('Medium Link', 'doccure_core'),
				'is_url'   	=> true,
				'icon'		=> 'fab fa-medium',
				'classses'		=> 'wt-medium',
				'color'			=> '#02b875',
			),
			'tripadvisor'	=> array(
				'title' 	=> esc_html__('Tripadvisor Link?', 'doccure_core'),
				'placeholder' 	=> esc_html__('Tripadvisor Link', 'doccure_core'),
				'is_url'   	=> true,
				'icon'		=> 'fab fa-tripadvisor',
				'classses'		=> 'wt-tripadvisor',
				'color'			=> '#FF0000',
			),
			'wikipedia'	=> array(
				'title' 	=> esc_html__('Wikipedia Link?', 'doccure_core'),
				'placeholder' 	=> esc_html__('Wikipedia Link', 'doccure_core'),
				'is_url'   	=> true,
				'icon'		=> 'fab fa-wikipedia-w',
				'classses'		=> 'wt-wikipedia',
				'color'			=> '#5a5b5c',
			),
			'vimeo'	=> array(
				'title' 	=> esc_html__('Vimeo Link?', 'doccure_core'),
				'placeholder' 	=> esc_html__('Vimeo Link', 'doccure_core'),
				'is_url'  	 => true,
				'icon'		=> 'fab fa-vimeo-square',
				'classses'		=> 'wt-vimeo',
				'color'			=> '#00adef',
			),
			'youtube'	=> array(
				'title' 	=> esc_html__('Youtube Link?', 'doccure_core'),
				'placeholder' 	=> esc_html__('Youtube Link', 'doccure_core'),
				'is_url'   	=> true,
				'icon'		=> 'fab fa-youtube',
				'classses'		=> 'wt-youtube',
				'color'			=> '#cd201f',
			),
			'whatsapp'	=> array(
				'title' 	=> esc_html__('Whatsapp Number?', 'doccure_core'),
				'placeholder' 	=> esc_html__('Whatsapp Number', 'doccure_core'),
				'is_url'   	=> false,
				'icon'		=> 'fab fa-whatsapp',
				'classses'		=> 'wt-whatsapp',
				'color'			=> '#0dc143',
			),
			'vkontakte'	=> array(
				'title' 	=> esc_html__('Vkontakte Link?', 'doccure_core'),
				'placeholder' 	=> esc_html__('Vkontakte Link', 'doccure_core'),
				'is_url'   	=> false,
				'icon'		=> 'fab fa-vk',
				'classses'		=> 'wt-vkontakte',
				'color'			=> '#5A80A7',
			),
			'odnoklassniki'	=> array(
				'title' 	=> esc_html__('Odnoklassniki Link?', 'doccure_core'),
				'placeholder' 	=> esc_html__('Odnoklassniki Link', 'doccure_core'),
				'is_url'    => true,
				'icon'		=> 'fab fa-odnoklassniki',
				'classses'		=> 'wt-odnoklassniki',
				'color'			=> '#f58220',
			),
		);

		$list	= apply_filters('doccure_exclude_social_media_icons',$list);

		if( !empty($settings) && $settings ==='yes' ) {
			$list	= wp_list_pluck($list,'title');
		}
		
		return $list;
    }
    add_filter('doccure_get_social_media_icons_list', 'doccure_get_social_media_icons_list', 10,1);
}

/**
 * Doctor redirect after login
 * @return slug
 */
if (!function_exists('doccure_doctor_redirect_after_login')) {
	function doccure_doctor_redirect_after_login( $page_key='') {
		$redirect_pages = array(
            'dashboard'         => array(
										'key' 	=> esc_html__('Dasboard','doccure_core'),
										'ref' 	=> 'insights',
										'mode'	=> ''
									),
            'profile'           => array(
									'key' 	=> esc_html__('Profile Settings','doccure_core'),
									'ref' 	=> 'profile',
									'mode'	=> 'settings'
								),
            'account'            => array(
									'key' 	=> esc_html__('Account Settings','doccure_core'),
									'ref' 	=> 'account-settings',
									'mode'	=> 'manage'
								),
            'saved_items'       => array(
									'key' 	=> esc_html__('Saved Items','doccure_core'),
									'ref' 	=> 'saved',
									'mode'	=> ''
								)
        );

		$list	= array();
        if( empty($page_key) ){
			foreach($redirect_pages as $key => $val ){
				$list[$key]	= !empty($val['key']) ? $val['key'] : '';
			}
		} else if( !empty($page_key) ){
			$list	= !empty($redirect_pages[$page_key]) ? $redirect_pages[$page_key] : array(); 
		}

		$list 	= apply_filters('doccure_filter_doctor_redirect_after_login',$list);
		
        return $list;

	}

	add_filter( 'doccure_doctor_redirect_after_login', 'doccure_doctor_redirect_after_login',10,1);
}
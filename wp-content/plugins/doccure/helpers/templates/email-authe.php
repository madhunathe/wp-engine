<?php
/**
 * Email Helper To Send Email
 * @since    1.0.0
 */
if (!class_exists('doccureRegisterNotify')) {

    class doccureRegisterNotify extends doccure_Email_helper{

        public function __construct() {
			//do stuff here
        }
		
		/**
		 * @Send User to approved
		 *
		 * @since 1.0.0
		 */
		public function send_approved_user_email($params = '') {
			
			global $doccure_options;
			extract($params);
			$email_to 			= $email;
			$subject_default 	= esc_html__('Your account is approved', 'doccure_core');
			$contact_default 	= 'Hello %username%!<br/>
									Your account has been approved by the admin. You can now login and check your dashboard<br/>
									%signature%';
			
			$subject		= !empty( $doccure_options['approved_user_subject'] ) ? $doccure_options['approved_user_subject'] : $subject_default;
			
			$email_content	= !empty( $doccure_options['approved_user_content'] ) ? $doccure_options['approved_user_content'] : $contact_default;
			                      

			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%username%", $username, $email_content); 
			$email_content = str_replace("%email%", $email, $email_content); 
			$email_content = str_replace("%site%", $site, $email_content);  
			$email_content = str_replace("%signature%", $sender_info, $email_content);

			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= wpautop( $email_content );
			$body .= '</div>';
			$body .= '</div>';

			$body .= $this->prepare_email_footers();
			wp_mail($email_to, $subject, $body);
		}

		/**
		 * @Send welcome doctor email
		 *
		 * @since 1.0.0
		 */
		public function send_doctor_email($params = '') {
			
			global $doccure_options;
			extract($params);
			  $email 			= $email;
			  $username = $username;
 			 // Get Redux options
			 $from_name = isset($doccure_options['emails_name']) && $doccure_options['emails_name'] 
             ? $doccure_options['emails_name'] 
             : get_bloginfo('name');

$from_email = isset($doccure_options['emails_from_email']) && $doccure_options['emails_from_email'] 
              ? $doccure_options['emails_from_email'] 
              : get_bloginfo('admin_email');

			 $disable_welcome_email = $doccure_options['welcome_email_enabled'];
		 
			 $login_link = $doccure_options['header_cta_btn_link'];
			 $email_logo = $doccure_options['email_logo'];
			 $email_logo_url = $email_logo['url'];
			 $email_subject_get = $doccure_options['listing_welcome_email_subject'];
			 $email_subject = isset($email_subject_get) && $email_subject_get 
			 ? $email_subject_get 
			 : __('Welcome to {site_name}', 'dreamsrent');
			 $email_content_raw = isset($doccure_options['listing_welcome_email_content']) && $doccure_options['listing_welcome_email_content'] 
			 ? $doccure_options['listing_welcome_email_content'] 
			 : 'Hi {user_name}, Welcome to our website.';

			 // Only send the welcome email if it’s not disabled
			 if ($disable_welcome_email) {
				 // Replace placeholders in the email content
				 $email_subject = str_replace('{site_name}', get_bloginfo('name'), $email_subject);
				 $email_content = str_replace(
					 ['{user_mail}', '{user_name}', '{site_name}','{login}'],
					 [$email, $username, get_bloginfo('name'), $login_link],
					 $email_content_raw
				 );
  
 
				 $message = '<!DOCTYPE html>
				 <html>
				 <head>
					 <meta charset="UTF-8">
					 <meta name="viewport" content="width=device-width, initial-scale=1.0">
					 <style>
						 body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
						 .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; border: 1px solid #ddd; }
						 .header { text-align: center; margin-bottom: 30px; }
						 .header img { max-width: 150px; }
						 .content { background: #fff; padding: 20px; border-radius: 5px; }
						 .content h2 { color: #333; }
						 .content p { margin-bottom: 20px; }
						 .content ul { padding-left:0px; }
						 .button { text-align: center; margin-top: 20px; }
						 .button a { background-color: #0073e6; color: #fff; padding: 12px 20px; border-radius: 5px; text-decoration: none; font-weight: bold; }
						 .footer { margin-top: 30px; text-align: center; color: #999; font-size: 12px; }
					 </style>
				 </head>
				 <body>
					 <div class="container">
						 <div class="header">';
				 if (!empty($email_logo_url)) {
					 $message .= '<img src="' . esc_url($email_logo_url) . '" alt="Logo">';
				 }
				 $message .= '</div>
 
						 <div class="content">' . $email_content . '</div>
 
						 <div class="footer">
							 &copy; ' . date("Y") . ' ' . get_bloginfo('name') . '. All rights reserved.
						 </div>
					 </div>
				 </body>
				 </html>';
 
				 // Prepare email headers
				 $headers = [
					 'Content-Type: text/html; charset=UTF-8',
					 'From: ' . $from_name . ' <' . $from_email . '>',
				 ];
 
				 $email_sent = wp_mail($email, $email_subject, $message, $headers);
		}
	}
		
		/**
		 * @Send welcome hospital email
		 *
		 * @since 1.0.0
		 */
		public function send_hospital_email($params = '') {
			global $doccure_options;
			extract($params);
			$email_to 			= $email;
			$subject_default 	= esc_html__('Thank you for registering', 'doccure_core');
			$contact_default 	= 'Hello %name%!<br/>

									Thanks for registering at %site%. You can now login to manage your account using the following credentials:<br/>
									Email: %email%<br/>
									Password: %password%<br/><br/>
									%signature%';
			
			$subject		= !empty( $doccure_options['hospital_registration_subject'] ) ? $doccure_options['hospital_registration_subject'] : $subject_default;
			
			$email_content	= !empty( $doccure_options['hospital_registration_content'] ) ? $doccure_options['hospital_registration_content'] : $contact_default;
			                     
			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%name%", $name, $email_content); 
			$email_content = str_replace("%password%", $password, $email_content); 
			$email_content = str_replace("%email%", $email, $email_content); 
			$email_content = str_replace("%site%", $site, $email_content); 
			$email_content = str_replace("%verification_link%", $verification_link, $email_content);
			$email_content = str_replace("%signature%", $sender_info, $email_content);

			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= wpautop( $email_content );
			$body .= '</div>';
			$body .= '</div>';

			$body .= $this->prepare_email_footers();
			wp_mail($email_to, $subject, $body);
		}
		
		/**
		 * @Send welcome Patients email
		 *
		 * @since 1.0.0
		 */
		public function send_regular_user_email($params = '') {
			

			global $doccure_options;
			extract($params);
			  $email = $email;
			  $username = $username;
 			 // Get Redux options
			 $from_name = isset($doccure_options['emails_name']) && $doccure_options['emails_name'] 
             ? $doccure_options['emails_name'] 
             : get_bloginfo('name');

$from_email = isset($doccure_options['emails_from_email']) && $doccure_options['emails_from_email'] 
              ? $doccure_options['emails_from_email'] 
              : get_bloginfo('admin_email');

			 $disable_welcome_email = $doccure_options['welcome_email_enabled'];
		 
			 $login_link = $doccure_options['header_cta_btn_link'];
			 $email_logo = $doccure_options['email_logo'];
			 $email_logo_url = $email_logo['url'];
			 $email_subject_get = $doccure_options['listing_welcome_email_subject'];
			 $email_subject = isset($email_subject_get) && $email_subject_get 
			 ? $email_subject_get 
			 : __('Welcome to {site_name}', 'dreamsrent');
			 $email_content_raw = isset($doccure_options['listing_welcome_email_content']) && $doccure_options['listing_welcome_email_content'] 
			 ? $doccure_options['listing_welcome_email_content'] 
			 : 'Hi {user_name}, Welcome to our website.';

			 // Only send the welcome email if it’s not disabled
			 if ($disable_welcome_email) {
				 // Replace placeholders in the email content
				 $email_subject = str_replace('{site_name}', get_bloginfo('name'), $email_subject);
				 $email_content = str_replace(
					 ['{user_mail}', '{user_name}', '{site_name}','{login}'],
					 [$email, $username, get_bloginfo('name'), $login_link],
					 $email_content_raw
				 );
  
 
				 $message = '<!DOCTYPE html>
				 <html>
				 <head>
					 <meta charset="UTF-8">
					 <meta name="viewport" content="width=device-width, initial-scale=1.0">
					 <style>
						 body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
						 .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; border: 1px solid #ddd; }
						 .header { text-align: center; margin-bottom: 30px; }
						 .header img { max-width: 150px; }
						 .content { background: #fff; padding: 20px; border-radius: 5px; }
						 .content h2 { color: #333; }
						 .content p { margin-bottom: 20px; }
						 .content ul { padding-left:0px; }
						 .button { text-align: center; margin-top: 20px; }
						 .button a { background-color: #0073e6; color: #fff; padding: 12px 20px; border-radius: 5px; text-decoration: none; font-weight: bold; }
						 .footer { margin-top: 30px; text-align: center; color: #999; font-size: 12px; }
					 </style>
				 </head>
				 <body>
					 <div class="container">
						 <div class="header">';
				 if (!empty($email_logo_url)) {
					 $message .= '<img src="' . esc_url($email_logo_url) . '" alt="Logo">';
				 }
				 $message .= '</div>
 
						 <div class="content">' . $email_content . '</div>
 
						 <div class="footer">
							 &copy; ' . date("Y") . ' ' . get_bloginfo('name') . '. All rights reserved.
						 </div>
					 </div>
				 </body>
				 </html>';
 
				 // Prepare email headers
				 $headers = [
					 'Content-Type: text/html; charset=UTF-8',
					 'From: ' . $from_name . ' <' . $from_email . '>',
				 ];
 
				 $email_sent = wp_mail($email, $email_subject, $message, $headers);

			// extract($params);
			// global $doccure_options;
			// $email_to 			= $email;
			// $subject_default 	= esc_html__('Thank you for registering', 'doccure_core');
			// $contact_default 	= 'Hello %name%!<br/>

			// 						Thanks for registering at %site%. You can now login to manage your account using the following credentials:<br/>
			// 						Email: %email%<br/>
			// 						Password: %password%<br/><br/>
			// 						%signature%';
			
			// $subject		= !empty( $doccure_options['regular_registration_subject'] ) ? $doccure_options['regular_registration_subject'] : $subject_default;
			
			// $email_content	= !empty( $doccure_options['regular_registration_content'] ) ? $doccure_options['regular_registration_content'] : $contact_default;
			                     
			// //Email Sender information
			// $sender_info = $this->process_sender_information();
			
			// $email_content = str_replace("%name%", $name, $email_content); 
			// $email_content = str_replace("%password%", $password, $email_content); 
			// $email_content = str_replace("%email%", $email, $email_content);
			// $email_content = str_replace("%site%", $site, $email_content); 
			// $email_content = str_replace("%verification_link%", $verification_link, $email_content);
			// $email_content = str_replace("%signature%", $sender_info, $email_content);

			// $body = '';
			// $body .= $this->prepare_email_headers();

			// $body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			// $body .= '<div style="width: 100%; float: left;">';
			// $body .= wpautop( $email_content );
			// $body .= '</div>';
			// $body .= '</div>';

			// $body .= $this->prepare_email_footers();
			
			// wp_mail($email_to, $subject, $body);
		}
	}
		/**
		 * @Send welcome Patients email
		 *
		 * @since 1.0.0
		 */
		public function send_seller_user_email($params = '') {
			
			extract($params);
			global $doccure_options;
			$email_to 			= $email;
			$subject_default 	= esc_html__('Thank you for registering', 'doccure_core');
			$contact_default 	= 'Hello %name%!
									Thank you for the registeration on our %site%. You can now login to manage your account using the below details credentials:
									Email: %email%
									Password: %password%
									%signature%';
			
			$subject		= !empty( $doccure_options['seller_subject'] ) ? $doccure_options['seller_subject'] : $subject_default;
			$email_content	= !empty( $doccure_options['seller_content'] ) ? $doccure_options['seller_content'] : $contact_default;
			                     
			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%name%", $name, $email_content); 
			$email_content = str_replace("%password%", $password, $email_content); 
			$email_content = str_replace("%email%", $email, $email_content);
			$email_content = str_replace("%verification_link%", $verification_link, $email_content);
			$email_content = str_replace("%site%", $site, $email_content); 
			$email_content = str_replace("%signature%", $sender_info, $email_content);

			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= wpautop( $email_content );
			$body .= '</div>';
			$body .= '</div>';

			$body .= $this->prepare_email_footers();
			
			wp_mail($email_to, $subject, $body);
		}
		
		/**
		 * @Send welcome admin email
		 *
		 * @since 1.0.0
		 */
		public function send_admin_email($params = '') {
			global $doccure_options;
			extract($params);
			$subject_default = esc_html__('New user registration', 'doccure_core');
			$contact_default = 'Hello!<br/>
								A new user "%name%" with email address "%email%" has registered on your website. Please login to check user detail.
								<br/>
								%signature%';
			
			$email_to		= !empty( $doccure_options['admin_email'] ) ? $doccure_options['admin_email'] : get_option('admin_email', 'info@example.com');
			
			$subject		= !empty( $doccure_options['admin_register_subject'] ) ? $doccure_options['admin_register_subject'] : $subject_default;
			
			$email_content	= !empty( $doccure_options['admin_register_content'] ) ? $doccure_options['admin_register_content'] : $contact_default;
			

			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%name%", $name, $email_content); 
			$email_content = str_replace("%email%", $email, $email_content); 
			$email_content = str_replace("%verification_link%", $verification_link, $email_content);
			$email_content = str_replace("%signature%", $sender_info, $email_content);

			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= wpautop( $email_content );
			$body .= '</div>';
			$body .= '</div>';

			$body .= $this->prepare_email_footers();	
			
			wp_mail($email_to, $subject, $body);
		}

		/**
		 * @Send verification email
		 *
		 * @since 1.0.0
		 */
		public function send_verification($params = '') {
			extract($params);
			global $doccure_options;
			$email_to = $email;
			
			$subject_default = esc_html__('Email Verification Link', 'doccure_core');
			$contact_default = 'Hello %name%!<br/>

								Your account has created on %site%. Verification is required, To verify your account please use below link:<br> 
								Verification Link: %verification_link%<br/>

								%signature%';

			$subject		= !empty( $doccure_options['resend_subject'] ) ? $doccure_options['resend_subject'] : $subject_default;
			$email_content	= !empty( $doccure_options['resend_content'] ) ? $doccure_options['resend_content'] : $contact_default;
			                     
			
			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%name%", $name, $email_content);
			
			if(!empty($password)){
				$email_content = str_replace("%password%", $password, $email_content);
			}
			
			$email_content = str_replace("%email%", $email, $email_content);
			$email_content = str_replace("%verification_link%", $verification_link, $email_content);
			$email_content = str_replace("%site%", $site, $email_content); 
			$email_content = str_replace("%signature%", $sender_info, $email_content);

			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= wpautop( $email_content );
			$body .= '</div>';
			$body .= '</div>';
			$body .= $this->prepare_email_footers();
			wp_mail($email_to, $subject, $body);
		}		
		
	}

	new doccureRegisterNotify();
}
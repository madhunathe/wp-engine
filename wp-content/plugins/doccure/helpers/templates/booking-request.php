<?php
/**
 * Email Helper To Send Email
 * @since    1.0.0
 */
if (!class_exists('doccureBookingNotify')) {

    class doccureBookingNotify extends doccure_Email_helper{

        public function __construct() {
			//do stuff here
        }	
		
		/**
		 * @Send verification email
		 *
		 * @since 1.0.0
		 */
		public function send_verification($params = '') {
			
			global $doccure_options;
			extract($params);
			$email_to = $email;
			
			$subject_default = esc_html__('Email Verification code', 'doccure_core');
			$contact_default = 'Hello %name%!<br/>
								Verification is required, To verify your account for appointment please use below code:<br> 
								Verification Link: %verification_code%<br/>

								%signature%';

			$subject		= !empty( $doccure_options['booking_verify_subject'] ) ? $doccure_options['booking_verify_subject'] : $subject_default;
			$email_content	= !empty( $doccure_options['booking_verify_content'] ) ? $doccure_options['booking_verify_content'] : $contact_default;
			                     
			
			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%name%", $name, $email_content);  
			$email_content = str_replace("%email%", $email, $email_content);
			$email_content = str_replace("%verification_code%", $verification_code, $email_content);
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
		 * @Send user email
		 *
		 * @since 1.0.0
		 */
		public function send_request_email($params = '') {
			
		
			global $doccure_options;
			extract($params);
			 $email_to 			= $email;
            // Retrieve Redux options
            $is_enabled = $doccure_options['new_order_email_enabled_patient'];
            $subject = $doccure_options['new_order_email_subject_patient'];
            $email_content = $doccure_options['new_order_email_content_patient'];

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
        $product_details .= '<th>Description</th>';
         $product_details .= '</tr>';
             // Loop through each order item to get metadata and product details
 			$product_details .= '<tr>';
			$product_details .= '<td >' . esc_html($user_name) . '</td>';
			$product_details .= '<td >' . esc_html($doctor_name) . '</td>';
			$product_details .= '<td >' . esc_html($appointment_date) . '</td>';
			$product_details .= '<td >' . esc_html($appointment_time) . '</td>';
			
			
			$product_details .= '<td >' . esc_html($consultant_fee) . '</td>';
			$product_details .= '<td >' . esc_html($price) . '</td>';
			$product_details .= '<td >' . esc_html($description) . '</td>';
 			$product_details .= '</tr>';
			
          
            $product_details .= '</table>';
           
            // Replace placeholders in the email content
            $replacements = array(
 				'{patient_name}'    => esc_html($user_name),
				'{doctor_name}'    => esc_html($doctor_name),
				'{appointment_date}'    => esc_html($appointment_date),
				'{appointment_time}'    => esc_html($appointment_time),
				'{consultant_fee}'    => esc_html($consultant_fee),
				'{total_price}'    => esc_html($price),
				'{description}'    => esc_html($description),

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
                                .content  table td{padding: 8px; border: 1px solid #ddd;text-align:center;}
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
            wp_mail($email_to, $subject, $message, $headers);

 
		}
		
		/**
		 * @Send doctor email
		 *
		 * @since 1.0.0
		 */
		public function send_doctor_email($params = '') {
			
			global $doccure_options;
			extract($params);
			  $email_to 			= $email;
			 

             // Retrieve Redux options
            $is_enabled = $doccure_options['new_order_email_enabled'];
            $subject = $doccure_options['new_order_email_subject'];
            $email_content = $doccure_options['new_order_email_content'];

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
        $product_details .= '<th>Description</th>';
         $product_details .= '</tr>';
             // Loop through each order item to get metadata and product details
 			$product_details .= '<tr>';
			$product_details .= '<td >' . esc_html($user_name) . '</td>';
			$product_details .= '<td >' . esc_html($doctor_name) . '</td>';
			$product_details .= '<td >' . esc_html($appointment_date) . '</td>';
			$product_details .= '<td >' . esc_html($appointment_time) . '</td>';
			
			
			$product_details .= '<td >' . esc_html($consultant_fee) . '</td>';
			$product_details .= '<td >' . esc_html($price) . '</td>';
			$product_details .= '<td >' . esc_html($description) . '</td>';
 			$product_details .= '</tr>';
			
          
            $product_details .= '</table>';
           
            // Replace placeholders in the email content
            $replacements = array(
				'{patient_name}'    => esc_html($user_name),
				'{doctor_name}'    => esc_html($doctor_name),
				'{appointment_date}'    => esc_html($appointment_date),
				'{appointment_time}'    => esc_html($appointment_time),
				'{consultant_fee}'    => esc_html($consultant_fee),
				'{total_price}'    => esc_html($price),
				'{description}'    => esc_html($description),
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
                                .content  table td{padding: 8px; border: 1px solid #ddd;text-align:center;}
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
            wp_mail($email_to, $subject, $message, $headers);

		}
		
		/**
		 * @Send hospital approved request
		 *
		 * @since 1.0.0
		 */
		public function send_approved_email($params = '') {
 			global $doccure_options;
			extract($params);
           $email_to 			= $email;
			 

			$is_enabled = $doccure_options['new_order_email_enabled_approve'];
            $subject = $doccure_options['new_order_email_subject_approve'];
            $email_content = $doccure_options['new_order_email_content_approve'];

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
          $product_details .= '</tr>';
             // Loop through each order item to get metadata and product details
 			$product_details .= '<tr>';
			$product_details .= '<td >' . esc_html($user_name) . '</td>';
			$product_details .= '<td >' . esc_html($doctor_name) . '</td>';
			$product_details .= '<td >' . esc_html($appointment_date) . '</td>';
			$product_details .= '<td >' . esc_html($appointment_time) . '</td>';
			
			
			$product_details .= '<td >' . esc_html($consultant_fee) . '</td>';
			$product_details .= '<td >' . esc_html($price) . '</td>';
  			$product_details .= '</tr>';
			
          
            $product_details .= '</table>';
           
            // Replace placeholders in the email content
            $replacements = array(
				'{patient_name}'    => esc_html($user_name),
				'{doctor_name}'    => esc_html($doctor_name),
				'{appointment_date}'    => esc_html($appointment_date),
				'{appointment_time}'    => esc_html($appointment_time),
				'{consultant_fee}'    => esc_html($consultant_fee),
				'{total_price}'    => esc_html($price),
				'{description}'    => esc_html($description),
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
                                .content  table td{padding: 8px; border: 1px solid #ddd; text-align:center;}
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
            wp_mail($email_to, $subject, $message, $headers);

		}
		
		/**
		 * @Send hospital cancelled request
		 *
		 * @since 1.0.0
		 */
		public function send_cancelled_email($params = '') {
			
			global $doccure_options;
			extract($params);
			$email_to 			= $email;
  			$is_enabled = $doccure_options['new_order_email_enabled_cancel'];
            $subject = $doccure_options['new_order_email_subject_cancel'];
            $email_content = $doccure_options['new_order_email_content_cancel'];

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
          $product_details .= '</tr>';
             // Loop through each order item to get metadata and product details
 			$product_details .= '<tr>';
			$product_details .= '<td >' . esc_html($user_name) . '</td>';
			$product_details .= '<td >' . esc_html($doctor_name) . '</td>';
			$product_details .= '<td >' . esc_html($appointment_date) . '</td>';
			$product_details .= '<td >' . esc_html($appointment_time) . '</td>';
			
			
			$product_details .= '<td >' . esc_html($consultant_fee) . '</td>';
			$product_details .= '<td >' . esc_html($price) . '</td>';
  			$product_details .= '</tr>';
			
          
            $product_details .= '</table>';
           
            // Replace placeholders in the email content
            $replacements = array(
				'{patient_name}'    => esc_html($user_name),
				'{doctor_name}'    => esc_html($doctor_name),
				'{appointment_date}'    => esc_html($appointment_date),
				'{appointment_time}'    => esc_html($appointment_time),
				'{consultant_fee}'    => esc_html($consultant_fee),
				'{total_price}'    => esc_html($price),
				'{description}'    => esc_html($description),
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
                                .content  table td{padding: 8px; border: 1px solid #ddd;text-align:center;}
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
            wp_mail($email_to, $subject, $message, $headers);
			
		}
	}

	new doccureBookingNotify();
}
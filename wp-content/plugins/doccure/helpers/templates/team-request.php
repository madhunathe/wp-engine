<?php
/**
 * Email Helper To Send Email
 * @since    1.0.0
 */
if (!class_exists('doccureHospitalTeamNotify')) {

    class doccureHospitalTeamNotify extends doccure_Email_helper{

        public function __construct() {
			//do stuff here
        }	

		/**
		 * @Send hospital request
		 *
		 * @since 1.0.0
		 */
		public function send_request_email($params = '') {
			
			global $doccure_options;
			extract($params);
			$email_to 			= $email;
			$subject_default 	= esc_html__('Send request', 'doccure_core');
			$contact_default 	= wp_kses(__('Hello %hospital_name%,<br/>
									<a href="%doctor_link%">%doctor_name%</a> has sent you a new request to join your hospital.<br/>
									%signature%,<br/>', 'doccure_core'),array(
										'a' => array(
											'href' => array(),
											'title' => array()
										),
										'br' => array(),
										'em' => array(),
										'strong' => array(),
									));
			
			$subject		= !empty( $doccure_options['doctor_hospital_request_subject'] ) ? $doccure_options['doctor_hospital_request_subject'] : $subject_default;
			
			$email_content	= !empty( $doccure_options['doctor_hospital_request_content'] ) ? $doccure_options['doctor_hospital_request_content'] : $contact_default;

			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%doctor_name%", $doctor_name, $email_content); 
			$email_content = str_replace("%hospital_name%", $hospital_name, $email_content); 
			$email_content = str_replace("%doctor_link%", $doctor_link, $email_content);
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
		 * @Send hospital approved request
		 *
		 * @since 1.0.0
		 */
		public function send_approved_email($params = '') {
			
			global $doccure_options;
			extract($params);
			$email_to 			= $email;
			$subject_default 	= esc_html__('Approved request to join hospital', 'doccure_core');
			$contact_default 	= wp_kses(__('Hello %doctor_name%,<br/>
									Your request to join <a href="%hospital_link%">%hospital_name%</a> is <b>Approved</b>.<br/>
									%signature%,<br/>', 'doccure_core'),array(
										'a' => array(
											'href' => array(),
											'title' => array()
										),
										'br' => array(),
										'em' => array(),
										'strong' => array(),
									));
			
			$subject		= !empty( $doccure_options['doctor_approved_request_subject'] ) ? $doccure_options['doctor_approved_request_subject'] : $subject_default;
			
			$email_content	= !empty( $doccure_options['doctor_approved_request_content'] ) ? $doccure_options['doctor_approved_request_content'] : $contact_default;

			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%doctor_name%", $doctor_name, $email_content); 
			$email_content = str_replace("%hospital_name%", $hospital_name, $email_content); 
			$email_content = str_replace("%hospital_link%", $hospital_link, $email_content);
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
		 * @Send hospital cancelled request
		 *
		 * @since 1.0.0
		 */
		public function send_cancelled_email($params = '') {
			
			global $doccure_options;
			extract($params);
			$email_to 			= $email;
			$subject_default 	= esc_html__('Cancelled request to join hospital', 'doccure_core');
			$contact_default 	= wp_kses(__('Hello %doctor_name%,<br/>
									Your request to join <a href="%hospital_link%">%hospital_name%</a> is <b>Cancelled</b>.<br/>
									%signature%,<br/>', 'doccure_core'),array(
										'a' => array(
											'href' => array(),
											'title' => array()
										),
										'br' => array(),
										'em' => array(),
										'strong' => array(),
									));
			
			$subject		= !empty( $doccure_options['hospital_request_cancelled_subject'] ) ? $doccure_options['hospital_request_cancelled_subject'] : $subject_default;
			
			$email_content	= !empty( $doccure_options['hospital_request_cancelled_content'] ) ? $doccure_options['hospital_request_cancelled_content'] : $contact_default;

			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%doctor_name%", $doctor_name, $email_content); 
			$email_content = str_replace("%hospital_name%", $hospital_name, $email_content); 
			$email_content = str_replace("%hospital_link%", $hospital_link, $email_content);
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

	new doccureHospitalTeamNotify();
}
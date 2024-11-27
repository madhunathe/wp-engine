<?php
/**
 * Email Helper To Send Email
 * @since    1.0.0
 */
if (!class_exists('doccureInvitationsNotify')) {

    class doccureInvitationsNotify extends doccure_Email_helper{

        public function __construct() {
			//do stuff here
        }	

		/**
		 * @Send hospital for invitation
		 *
		 * @since 1.0.0
		 */
		public function send_hospitals_email($params = '') {
			
			global $doccure_options;
			extract($params);
			$email_to 			= $email;
			$subject_default 	= esc_html__('Invitation to signup', 'doccure_core');
			$contact_default 	= wp_kses(__('Hello,<br>
			You have an invitation from %doctor_name% to register on the site. He wants to list yourself in your hospital onboard doctors.<br>
			Create your profile and get listed on the site. He have leave a message for you<br>
			%invitation_content% <br>
			%signature%', 'doccure_core'),array(
										'a' => array(
											'href' => array(),
											'title' => array()
										),
										'br' => array(),
										'em' => array(),
										'strong' => array(),
									));
			
			$subject		= !empty( $doccure_options['invite_hospitals_subject'] ) ? $doccure_options['invite_hospitals_subject'] : $subject_default;
			
			$email_content	= !empty( $doccure_options['invite_hospitalss_content'] ) ? $doccure_options['invite_hospitalss_content'] : $contact_default;

			//Email Sender information
			$sender_info = $this->process_sender_information();
			$email_content = str_replace("%doctor_name%", $doctor_name, $email_content); 
			$email_content = str_replace("%doctor_profile_url%", $doctor_profile_url, $email_content); 
			$email_content = str_replace("%doctor_email%", $doctor_email, $email_content); 
			$email_content = str_replace("%invited_hospital_email%", $invited_hospital_email, $email_content); 
			$email_content = str_replace("%invitation_content%", $invitation_content, $email_content); 

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
		 * @Send doctors for invitation
		 *
		 * @since 1.0.0
		 */
		public function send_doctors_email($params = '') {
			
			global $doccure_options;
			extract($params);
			$email_to 			= $email;
			$subject_default 	= esc_html__('Invitation to signup', 'doccure_core');
			$contact_default 	= wp_kses(__('Hello,<br>
			You have an invitation from %hospital_name% to register on the site. They wants to list you as their onboard doctors<br>
			Create your profile and get listed on the site. They have leave a message for you<br>
			%invitation_content% <br>
			%signature%
			', 'doccure_core'),array(
										'a' => array(
											'href' => array(),
											'title' => array()
										),
										'br' => array(),
										'em' => array(),
										'strong' => array(),
									));
			
			$subject		= !empty( $doccure_options['invite_doctors_subject'] ) ? $doccure_options['invite_doctors_subject'] : $subject_default;
			$email_content	= !empty( $doccure_options['invite_doctors_content'] ) ? $doccure_options['invite_doctors_content'] : $contact_default;

			//Email Sender information
			$sender_info = $this->process_sender_information();
			$email_content = str_replace("%hospital_name%", $hospital_name, $email_content); 
			$email_content = str_replace("%hospital_email%", $hospital_email, $email_content); 
			$email_content = str_replace("%hospital_profile_url%", $hospital_profile_url, $email_content); 
			$email_content = str_replace("%invited_docor_email%", $invited_docor_email, $email_content); 
			$email_content = str_replace("%invitation_content%", $invitation_content, $email_content); 
			$email_content = str_replace("%invitation_link", $invitation_link, $email_content); 

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

	new doccureInvitationsNotify();
}
<?php
/**
 * Email Helper To Send Feedback Email 
 * @since    1.0.0
 */
if (!class_exists('doccureFeedbackNotify')) {

    class doccureFeedbackNotify extends doccure_Email_helper{

        public function __construct() {
			//do stuff here
        }			
		
		/**
		 * @Send doctor email
		 *
		 * @since 1.0.0
		 */
		public function send_feedback_email_doctor($params = '') {
			
			global $doccure_options;
			extract($params);
			$email_to 			= $email;
			$subject_default 	= esc_html__('Feedback received', 'doccure_core');
			$contact_default 	= wp_kses(__('Hello %doctor_name%<br>
			%user_name% has given the feedback with the following details :<br>
Recommend 			: %recommend% <br>
Waiting time 		: %waiting_time% <br>
Rating				: %rating% <br>
Description 		: %description% <br>
%signature%,<br/>
			%signature%,', 'doccure_core'),array(
										'a' => array(
											'href' => array(),
											'title' => array()
										),
										'br' => array(),
										'em' => array(),
										'strong' => array(),
									));
			
			$subject		= !empty( $doccure_options['feedback_subject'] ) ? $doccure_options['feedback_subject'] : $subject_default;
			$email_content	= !empty( $doccure_options['feedback_content'] ) ? $doccure_options['feedback_content'] : $contact_default;

			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%user_name%", $user_name, $email_content); 
			$email_content = str_replace("%doctor_name%", $doctor_name, $email_content); 
			$email_content = str_replace("%rating%", $rating, $email_content); 
			$email_content = str_replace("%recommend%", $recommend, $email_content); 
			$email_content = str_replace("%waiting_time%", $waiting_time, $email_content); 
			$email_content = str_replace("%description%", $description, $email_content); 
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

	new doccureFeedbackNotify();
}
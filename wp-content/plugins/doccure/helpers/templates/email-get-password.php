<?php
/**
 * Email Helper To Send Email for Getting Password
 * @since    1.0.0
 */
if (!class_exists('doccureGetPasswordNotify')) {

    class doccureGetPasswordNotify extends doccure_Email_helper{

        public function __construct() {
			//do stuff here
        }

		/**
		 * @Send Generat Password Link
		 *
		 * @since 1.0.0
		 */
		public function send($params = '') {
			global $doccure_options;

			extract($params);

			$subject_default = esc_html__('Forgot Password', 'doccure_core');
			$contact_default = 'Hi %name%,<br/>
								Someone requested to reset the password of following account:<br/><br/>
								Email Address: %email%<br>
								If this was a mistake, just ignore this email and nothing will happen.

								To reset your password, click reset link below:<br/>
								<a href="%link%">Reset</a>

								%signature%';
			$subject			= !empty( $doccure_options['change_password_subject'] ) ? $doccure_options['change_password_subject'] : $subject_default;
			$email_content		= !empty( $doccure_options['change_password_content'] ) ? $doccure_options['change_password_content'] : $subject_default;
			                    
			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%username%", $username, $email_content); 
			$email_content = str_replace("%email%", $email, $email_content); 
			$email_content = str_replace("%name%", $username, $email_content); 
			$email_content = str_replace("%email%", $email, $email_content); 
			$email_content = str_replace("%link%", $link, $email_content); 
			$email_content = str_replace("%signature%", $sender_info, $email_content);

			$body = '';
			$body .= $this->prepare_email_headers();

			$body .= '<div style="width: 100%; float: left; padding: 0 0 60px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">';
			$body .= '<div style="width: 100%; float: left;">';
			$body .= wpautop( $email_content );
			$body .= '</div>';
			$body .= '</div>';
			$body .= $this->prepare_email_footers();	
			$email_to = $email;											          
			wp_mail($email_to, $subject, $body);
		}
		
	}

	new doccureGetPasswordNotify();
}
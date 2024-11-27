<?php
/**
 * Email Helper To Send Receiver Chat Notifications
 * @since    1.0.0
 */
if (!class_exists('doccureRecChatNotification')) {

    class doccureRecChatNotification extends doccure_Email_helper{

        public function __construct() {
			//do stuff here
        }

		/**
		 * @Send chat message notification to receiver
		 *
		 * @since 1.0.0
		 */
		public function send_chat_notification($params = '') {
			global $current_user,$doccure_options;

			extract($params);

			$subject_default = esc_html__('A new message received', 'doccure_core');
			$contact_default = 'Hi %username%!<br/>

								<p>You have received a new message from %sender_name%, below is the message</p>
								<p>%message%</p>
								%signature%';
			
			$subject		= !empty( $doccure_options['chat_notify_subject'] ) ? $doccure_options['chat_notify_subject'] : $subject_default;
			$email_content	= !empty( $doccure_options['chat_notify_content'] ) ? $doccure_options['chat_notify_content'] : $contact_default;

			//Set Default Subject
			if( empty( $subject ) ){
				$subject = $subject_default;
			}

			//set defalt contents
			if (empty($email_content)) {
				$email_content = $contact_default;
			}                       

			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%username%", $username, $email_content); 
			$email_content = str_replace("%sender_name%", $sender_name, $email_content); 
			$email_content = str_replace("%message%", $message, $email_content); 
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

	new doccureRecChatNotification();
}
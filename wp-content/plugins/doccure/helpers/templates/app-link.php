<?php
/**
 * Email Helper To Send Email
 * @since    1.0.0
 */
if (!class_exists('doccureAppLinkNotify')) {

    class doccureAppLinkNotify extends doccure_Email_helper{

        public function __construct() {
			//do stuff here
        }	

		/**
		 * @Send hospital request
		 *
		 * @since 1.0.0
		 */
		public function send_applink_email($params = '') {
			
			global $doccure_options;
			extract($params);
			$email_to 			= $email;
			$subject_default 	= esc_html__('Send App link', 'doccure_core');
			$contact_default 	= wp_kses(__('Hello,<br/>
									Your should be download the app from our site.<br/>
									%signature%,<br/>', 'doccure_core'),array(
										'a' => array(
											'href' => array(),
											'title' => array()
										),
										'br' => array(),
										'em' => array(),
										'strong' => array(),
									));
			
			$subject		= !empty( $doccure_options['subject_app_link'] ) ? $doccure_options['subject_app_link'] : $subject_default;
			
			$email_content	= !empty( $doccure_options['app_link_content'] ) ? $doccure_options['app_link_content'] : $contact_default;

			//Email Sender information
			$sender_info = $this->process_sender_information();
			
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

	new doccureAppLinkNotify();
}
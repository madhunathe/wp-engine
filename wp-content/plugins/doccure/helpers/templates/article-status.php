<?php
/**
 * Email Helper To Send Email
 * @since    1.0.0
 */
if (!class_exists('doccureArticleNotify')) {

    class doccureArticleNotify extends doccure_Email_helper{

        public function __construct() {
			//do stuff here
        }	
		
		
		
		/**
		 * @Send doctor email
		 *
		 * @since 1.0.0
		 */
		public function send_article_pending_email($params = '') {
			
			global $doccure_options;
			extract($params);
			$email_to 			= $email;
			$subject_default 	= esc_html__('Your Article is pending status', 'doccure_core');
			$contact_default 	= wp_kses(__('Hello %doctor_name%<br/>

							Your article %article_title% has been received with pending status. <br/>
							%signature%,<br/>', 'doccure_core'),array(
										'a' => array(
											'href' => array(),
											'title' => array()
										),
										'br' => array(),
										'em' => array(),
										'strong' => array(),
									));
			
			$subject		= !empty( $doccure_options['article_pending_subject'] ) ? $doccure_options['article_pending_subject'] : $subject_default;
			
			$email_content	= !empty( $doccure_options['article_pending_content'] ) ? $doccure_options['article_pending_content'] : $contact_default;

			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%doctor_name%", $doctor_name, $email_content); 
			$email_content = str_replace("%article_title%", $article_title, $email_content); 
			$email_content = str_replace("%email%", $email, $email_content); 
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
		 * @Send doctor email
		 *
		 * @since 1.0.0
		 */
		public function send_admin_pending_email($params = '') {
			
			global $doccure_options;
			extract($params);
			$subject_default 	= esc_html__('%doctor_name% send article', 'doccure_core');
			$contact_default 	= wp_kses(__('Hello !<br/>

							%doctor_name% send article %article_title% with pending status.<br/>
							%signature%,<br/>', 'doccure_core'),array(
										'a' => array(
											'href' => array(),
											'title' => array()
										),
										'br' => array(),
										'em' => array(),
										'strong' => array(),
									));
			
			$subject		= !empty( $doccure_options['admin_article_pending_subject'] ) ? $doccure_options['admin_article_pending_subject'] : $subject_default;
			
			$email_content	= !empty( $doccure_options['admin_article_pending_content'] ) ? $doccure_options['admin_article_pending_content'] : $contact_default;
			
			$email_to		= !empty( $doccure_options['admin_email'] ) ? $doccure_options['admin_email'] : get_option('admin_email', 'info@example.com');
			
			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%doctor_name%", $doctor_name, $email_content); 
			$email_content = str_replace("%article_title%", $article_title, $email_content); 
			$email_content = str_replace("%email%", $email, $email_content); 
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
		 * @Send doctor email
		 *
		 * @since 1.0.0
		 */
		public function send_article_publish_email($params = '') {
			
			global $doccure_options;
			extract($params);
			$email_to 			= $email;
			$subject_default 	= esc_html__('Your Article is publish status', 'doccure_core');
			$contact_default 	= wp_kses(__('Hello %doctor_name%<br/>

							Your article %article_title% has been received with publish status. <br/>
							%signature%,<br/>', 'doccure_core'),array(
										'a' => array(
											'href' => array(),
											'title' => array()
										),
										'br' => array(),
										'em' => array(),
										'strong' => array(),
									));
			
			$subject		= !empty( $doccure_options['article_publish_subject'] ) ? $doccure_options['article_publish_subject'] : $subject_default;
			
			$email_content	= !empty( $doccure_options['article_publish_content'] ) ? $doccure_options['article_publish_content'] : $contact_default;

			//Email Sender information
			$sender_info = $this->process_sender_information();
			
			$email_content = str_replace("%doctor_name%", $doctor_name, $email_content); 
			$email_content = str_replace("%article_title%", $article_title, $email_content); 
			$email_content = str_replace("%email%", $email, $email_content); 
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

	new doccureArticleNotify();
}
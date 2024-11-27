<?php
if (!class_exists('doccure_MailChimp')) {

    class doccure_MailChimp {

        function __construct() {
            add_action('wp_ajax_nopriv_doccure_subscribe_mailchimp', array(&$this, 'doccure_subscribe_mailchimp'));
            add_action('wp_ajax_doccure_subscribe_mailchimp', array(&$this, 'doccure_subscribe_mailchimp'));
        }
		
		public function doccure_mailchimp_form() {
			$counter = 0;
            $counter++;
            ?>
            <form class="dc-formtheme dc-formnewsletter comingsoon-newsletter" id="mailchimpwidget_<?php echo intval($counter); ?>">
				<fieldset>
					<div class="form-group">
						<input type="email" name="email" value="" class="form-control"  placeholder="<?php esc_attr_e('Enter your email', 'doccure_core'); ?>" required="">
						<button type="submit" class="subscribe_me" data-counter="<?php echo intval($counter); ?>"><i class="lnr lnr-arrow-right"></i></button>
					</div>
				</fieldset>
			</form>
            <?php
        }
		
        /**
         * @get Mail chimp list
         *
         */
        public function doccure_mailchimp_list($apikey) {
			if ( $apikey <> '' && $apikey !== 'Add your key here' ) {
				$apikey	= $apikey;
			} else{
				return '';
			}
			
            $MailChimp = new doccure_OATH_MailChimp($apikey);
            $mailchimp_list = $MailChimp->doccure_call('lists/list');
            return $mailchimp_list;
        }

        /**
         * @get Mail chimp list
         *
         */
        public function doccure_subscribe_mailchimp() {
            global $counter,$doccure_options;
            $mailchimp_key 		= '';
            $mailchimp_list 	= '';
            $json 				= array();

			$mailchimp_key = !empty( $doccure_options['mailchimp_key'] ) ? $doccure_options['mailchimp_key']  : '';
			$mailchimp_list = !empty( $doccure_options['mailchimp_list'] ) ? $doccure_options['mailchimp_list']  : '';
			

            if (empty($_POST['email'])) {
                $json['type'] 		= 'error';
                $json['message'] 	= esc_html__('Email address is required.', 'doccure_core');
                wp_send_json($json);
            }
			
			if (isset($_POST['email']) && !empty($_POST['email']) && $mailchimp_key != '') {
                
				if ($mailchimp_key <> '' && $mailchimp_key !== 'Add your key here') {
                    $MailChimp = new doccure_OATH_MailChimp($mailchimp_key);
                } else{
					$json['type'] 		= 'error';
                	$json['message'] 	= esc_html__('Some error occur,please try again later.', 'doccure_core');
					wp_send_json($json);
				}

                $email = $_POST['email'];

                if (isset($_POST['fname']) && !empty($_POST['fname'])) {
                    $fname = $_POST['fname'];
                } else {
                    $fname = '';
                }

                if (isset($_POST['lname']) && !empty($_POST['lname'])) {
                    $lname = $_POST['lname'];
                } else {
                    $lname = '';
                }

                if (trim($mailchimp_list) == '') {
                    $json['type'] = 'error';
                    $json['message'] = esc_html__('No list selected yet! please contact administrator', 'doccure_core');
                    wp_send_json($json);
                }

                //https://apidocs.mailchimp.com/api/1.3/listsubscribe.func.php
                $result = $MailChimp->doccure_call('lists/subscribe', array(
                    'id' => $mailchimp_list,
                    'email' => array('email' => $email),
                    'merge_vars' => array('FNAME' => $fname, 'LNAME' => $lname),
                    'double_optin' => false,
                    'update_existing' => false,
                    'replace_interests' => false,
                    'send_welcome' => true,
                ));
				
                if ($result <> '') {
                    if (isset($result['status']) and $result['status'] == 'error') {
                        $json['type'] 		= 'error';
                        $json['message'] 	= $result['error'];
                    } else {
                        $json['type'] 		= 'success';
                        $json['message'] 	= esc_html__('Subscribe Successfully', 'doccure_core');
                    }
                }
				
            } else {
                $json['type'] = 'error';
                $json['message'] = esc_html__('Some error occur,please try again later.', 'doccure_core');
            }
			
            wp_send_json($json);
        }

    }

    new doccure_MailChimp();
}
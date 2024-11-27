<?php
/*
* Template Name: Forgot Password
 * @package doccure
 */

 require_once(ABSPATH . 'wp-load.php');
get_header();
			global $doccure_options,$post,$current_user;
			$is_auth		= !empty( $doccure_options['user_registration'] ) ? $doccure_options['user_registration'] : '';
			$is_register	= !empty( $doccure_options['registration_form'] ) ? $doccure_options['registration_form'] : '';
			$is_login		= !empty( $doccure_options['login_form'] ) ? $doccure_options['login_form'] : '';
			$redirect		= !empty( $_GET['redirect'] ) ? esc_url( $_GET['redirect'] ) : '';

			$current_page	= '';
			if ( is_singular('doctors')){
				$current_page	= !empty( $post->ID ) ? intval( $post->ID ) : '';
			}

			$signup_page_slug   = doccure_get_signup_page_url();  
			$user_identity 	= !empty($current_user->ID) ? $current_user->ID : 0;
			$user_type		= apply_filters('doccure_get_user_type', $user_identity );

			if ( is_user_logged_in() ) {
				if ( !empty($menu) && $menu === 'yes' && ( $user_type === 'doctors' || $user_type === 'hospitals' || $user_type === 'regular_users')  ) {
					echo '<div class="dc-afterauth-buttons">';
					do_action('doccure_print_user_nav');
					echo '</div>';
				}
			} else{

				if( !empty( $is_auth ) ){
					
					
					

$error_message = ''; // Initialize the variable
$success_message = ''; // Initialize the success message variable

if ($_POST) {
    $email = sanitize_email($_POST['email']);
    $user = get_user_by('email', $email);

   
    if ($user) {
        if ($doccure_options['forgot_password_email_enabled']) {
            
             $reset_key = get_password_reset_key($user);
    
            if (!is_wp_error($reset_key)) {
                $reset_link = esc_url(add_query_arg([
                    'action' => 'rp',
                    'key'    => $reset_key,
                    'login'  => rawurlencode($user->user_login),
                ], wp_login_url()));

                $email_content = $doccure_options['forgot_password_email_content'];

                // Replace placeholders with actual data
                $email_content = str_replace('{user_name}', $user->display_name, $email_content);
                $email_content = str_replace('{reset_link}', $reset_link, $email_content);

                
    
				$subject = isset($doccure_options['forgot_password_email_subject']) 
				? $doccure_options['forgot_password_email_subject'] 
				: __('Password Reset Request', 'dreamsrent');
	 
	 $from_email = isset($doccure_options['emails_from_email']) 
				   ? $doccure_options['emails_from_email'] 
				   : get_bloginfo('admin_email');


                $email_logo = $doccure_options['email_logo'];
                $email_logo_url = $email_logo['url'];
                // Build the HTML message
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
    
                // Email headers
                $headers = [
                    'Content-Type: text/html; charset=UTF-8',
                    'From: ' . get_bloginfo('name') . ' <' . $from_email . '>'
                ];
    
                // Send the email
                wp_mail($email, $subject, $message, $headers);
                $success_message = __('Password reset link sent successfully!', 'dreamsrent');
            } else {
                $error_message = __('Could not generate a reset link. Please try again later.', 'dreamsrent');
            }
        } else {
            $error_message = __('Password reset email is disabled.', 'dreamsrent');
        }
    } else {
        $error_message = __('Email is not registered. Please enter a valid email address.', 'dreamsrent');
    }
    
    
}

?>
				<div class="content">
				<div class="container-fluid">
				<div class="row">
				<div class="col-md-8 offset-md-2">
				<div class="account-content">
				<div class="row align-items-center justify-content-center">
				<div class="col-md-7 col-lg-6 login-left">
                <img src="<?php echo get_template_directory_uri();?>/assets/images/login-banner.png" class="img-fluid" alt="Doccure Register">	
				</div>
				<div class="col-md-5 col-lg-6 login-right">
                <div>
					<?php if( !empty( $is_login ) ) {?>
						<div class="dc-loginoption">
							<?php //echo do_shortcode('[reset_password]'); ?>

							<?php if (!empty($error_message)) { ?>
                    <div class="error-message alert alert-danger"><?php echo esc_attr($error_message); ?></div>
                <?php } ?>
                <?php if (!empty($success_message)) { ?>
                    <div class="success-message alert alert-success"><?php echo esc_attr($success_message); ?></div>
                <?php } ?>
            

 
                <div class="dc-loginheader">
									<span class="titlelogin"><?php echo get_the_title(); ?></span>
								</div>
                <p class="account-subtitle"><?php echo esc_html__('Enter your email and we will send you a link to reset your password.', 'dreamsrent'); ?></p>								
                <form method="post">
                    <?php wp_nonce_field('reset_password_nonce', 'reset_password_nonce'); ?>
                    <div class="input-block">
                        <label class="form-label"><?php echo esc_html__('Email Address ', 'dreamsrent'); ?><span class="text-danger"><?php echo esc_html__('*', 'dreamsrent'); ?></span></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <button type="submit" class="mt-3" id="reset-pass-submit-new"><?php echo esc_html__('Send Reset Link', 'dreamsrent'); ?></button>
                </form>	

						</div>
				</div>
					<?php } ?>
				</div>
				</div>
				</div>
			    </div>
				</div>
				</div>
				</div>
				<?php }
			}
		
get_footer();

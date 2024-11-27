<?php
/*
* Template Name: Login Form
 * @package doccure
 */
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

			$user_identity 	= !empty($current_user->ID) ? $current_user->ID : 0;
			$user_type		= apply_filters('doccure_get_user_type', $user_identity );

			if ( is_user_logged_in() ) {
				if ( !empty($menu) && $menu === 'yes' && ( $user_type === 'doctors' || $user_type === 'hospitals' || $user_type === 'regular_users')  ) {
					echo '<div class="dc-afterauth-buttons">';
					do_action('doccure_print_user_nav');
					echo '</div>';
				}
			} else{

				if( !empty( $is_auth ) ){?>
                <div class="content">
				<div class="container-fluid">
				<div class="row">
				<div class="col-md-8 offset-md-2">
				<div class="account-content">
				<div class="row align-items-center justify-content-center">
				<div class="col-md-7 col-lg-6 login-left">
                <img src="<?php echo get_template_directory_uri();?>/assets/images/login-banner.png" class="img-fluid" alt="Doccure Register">	
				</div>
				<?php if( !empty( $is_login ) ) {?>
				<div class="col-md-5 col-lg-6 login-right">
				<div>
					
						<div class="dc-loginoption">
							<div class="dc-loginformhold">
								<div class="dc-loginheader">
									<span class="titlelogin"><?php esc_html_e('Doctor/Patient Login','doccure');?></span>
								</div>
								<form class="dc-formtheme dc-loginform do-login-form">
										<div class="form-group form-focus">
											<input type="text" name="username" class="form-control" placeholder="<?php esc_html_e('Username', 'doccure'); ?>">
										</div>
										<div class="form-group form-focus">
											<input type="password" name="password" class="form-control" placeholder="<?php esc_html_e('Password', 'doccure'); ?>">
										</div>
										<div class="dc-footerinfo">
										<a  href="<?php echo home_url(); ?>/forgot-password"><?php esc_html_e('Forgot password?','doccure');?></a>
										</div>
										<div class="dc-logininfo">
											<input type="submit" class="dc-btn do-login-button" data-id="<?php echo intval($current_page);?>" value="<?php esc_html_e('Login','doccure');?>">
										</div>
										<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect );?>">
										<input type="hidden" name="redirect_id" value="<?php echo intval($current_page);?>">
								
									
									<?php if( !is_user_logged_in() ){ ?>
									<div class="dc-registerformfooter">
										<span><?php esc_html_e('Don’t have an account?', 'doccure' ); ?><a   href="<?php echo home_url();?>/register">&nbsp;<?php esc_html_e('Register', 'doccure'); ?></a></span>
									</div>
								<?php } ?>
								</form>
								
							</div>
						</div>
						</div>
					
				</div>
				</div>
				<?php } ?>
				</div>
				</div>
				</div>
				</div>
				</div>
				<?php }
 }
		
get_footer();

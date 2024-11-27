<?php
/*
* Template Name: Forgot Password -old
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
				<div class="col-md-5 col-lg-6 login-right">
                <div>
					<?php if( !empty( $is_login ) ) {?>
						<div class="dc-loginoption">
							<?php echo do_shortcode('[reset_password]'); ?>
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

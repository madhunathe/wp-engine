<?php
/**
 * File Type : Authentication
 */
if (!class_exists('SC_doccure_Authentication')) {

    class SC_doccure_Authentication {

        /**
         * Construct Shortcode
         */
        public function __construct() {
            add_shortcode('doccure_authentication', array(&$this, 'shortCodeCallBack'));
        }

		
        /**
         * Return Authentication Result
         */
        public function shortCodeCallBack($atts) {
			global $current_user, $wp_roles,$doccure_options;		
			$atts = shortcode_atts( array(
						'title' 		=> '',
					), $atts, 'doccure_authentication' );
			
			ob_start();			                     
                      
            $site_key 				= '';
            $protocol 				= is_ssl() ? 'https' : 'http';

            $login_register 		= !empty( $doccure_options['user_registration'] ) ? $doccure_options['user_registration'] : '';       
			$registration_form 		= !empty( $doccure_options['registration_form'] ) ? $doccure_options['registration_form'] : '';       
			$login_form 			= !empty( $doccure_options['login_form'] ) ? $doccure_options['login_form'] : '';           
            $redirect   			= !empty( $_GET['redirect'] ) ? esc_url( $_GET['redirect'] ) : '';
			
			$reg_option 	= !empty( $doccure_options['user_type_registration'] ) ? $doccure_options['user_type_registration'] : array();
			$step_image 	= !empty( $doccure_options['step_image']['url'] ) ? $doccure_options['step_image']['url'] : ''; 
			$step_title 	= !empty( $doccure_options['step_title'] ) ? $doccure_options['step_title'] : esc_html__('Join For a Good Start', 'doccure_core');
			$step_desc 		= !empty( $doccure_options['step_description'] ) ? $doccure_options['step_description'] : ''; 
			$term_text 		= !empty( $doccure_options['term_text'] ) ? $doccure_options['term_text'] : '';
			$remove_location 		= !empty( $doccure_options['remove_location'] ) ? $doccure_options['remove_location'] : 'no';
			$terms_link 	= !empty( $doccure_options['term_page'] ) ? get_permalink( intval( $doccure_options['term_page'] ) ) : '';
			
			$user_types		= array();      
			if( function_exists( 'doccure_list_user_types' ) ) {
				$user_types	= doccure_list_user_types();
			}
			
		    if (!is_user_logged_in()) {?>
			 <div class="dc-haslayout dc-parent-section">
				<div class="row justify-content-md-center">
					<div class="col-xs-12 col-sm-12 col-md-10 push-md-1 col-lg-8 push-lg-2">
						<div class="dc-registerformhold">
							<form class="dc-formtheme dc-formregister">
								<div class="tab-content dc-registertabcontent">
									<div class="dc-registerformmain">
										<?php if( !empty( $step_image ) ){?>
											<figure class="dc-joinformsimg">
												<img src="<?php echo esc_url( $step_image ); ?>" alt="<?php esc_html_e('Registration', 'doccure_core'); ?>">
											</figure>
										<?php }?>
										<?php if( !empty( $step_title ) || !empty( $step_desc ) ) { ?>
											<div class="dc-registerhead">
												<?php if( !empty( $step_title ) ) { ?>
													<div class="dc-title">
														<h3><?php echo esc_attr( $step_title ); ?></h3>
													</div>
												<?php } ?>
												<?php if( !empty( $step_desc ) ) { ?>
													<div class="description">
														<?php echo do_shortcode( $step_desc ); ?>
													</div>
												<?php } ?>
											</div>
										<?php } ?>
										<div class="dc-joinforms">
											<fieldset class="dc-registerformgroup">
												<div class="form-group form-group-half">
													<input type="text" name="first_name" class="form-control" value="" placeholder="<?php esc_attr_e('First Name', 'doccure_core'); ?>">
												</div>
												<div class="form-group form-group-half">
													<input type="text" name="last_name" value="" class="form-control" placeholder="<?php esc_attr_e('Last Name', 'doccure_core'); ?>">
												</div>
												<div class="form-group form-group-half">
													<input type="text" name="username" class="form-control" value="" placeholder="<?php esc_attr_e('username', 'doccure_core'); ?>">
												</div>

												<div class="form-group form-group-half">
													<input type="email" name="email" class="form-control" value="" placeholder="<?php esc_attr_e('Email', 'doccure_core'); ?>">
												</div>
											</fieldset>
											
											<fieldset class="dc-registerformgroup">
												<?php if(!empty($remove_location) && $remove_location == 'no'){?>
													<div class="form-group">
														<span class="dc-select">
															<?php do_action('doccure_get_locations_list','location',''); ?>	
														</span>
													</div>
												<?php }?>
												<div class="form-group form-group-half">
													<input type="password" name="password" class="form-control" placeholder="<?php esc_html_e('Password*', 'doccure_core' ); ?>">
												</div>
												<div class="form-group form-group-half">
													<input type="password" name="verify_password" class="form-control" placeholder="<?php esc_html_e('Retype Password*', 'doccure_core' ); ?>">
												</div>
											</fieldset>
											
											<fieldset class="dc-formregisterstart">
												<div class="dc-title dc-formtitle"><h4><?php esc_html_e('Start as :', 'doccure_core' ); ?></h4></div>
												<?php if( !empty( $user_types ) ){ ?>
													<ul class="dc-startoption">
														<?php
															foreach( $user_types as $key => $val) {
															
																$checked	= !empty( $key ) && $key === 'doctors' ? 'checked=""' : '';
																$display	= !empty( $key ) && $key === 'seller' ? esc_html__('Store name','doccure_core') : esc_html__('Display name','doccure_core');
																 if( !empty($reg_option) && in_array($key,$reg_option)){?>
																<li>
																	<span class="dc-radio" data-display="<?php echo esc_attr($display);?>">
																		<input id="dc-<?php echo esc_attr($key);?>" type="radio" name="user_type" value="<?php echo esc_attr($key);?>" <?php echo esc_attr($checked);?>>
																		<label for="dc-<?php echo esc_attr($key);?>"><?php echo esc_html($val);?></label>
																	</span>
																</li>
															<?php } ?>
														<?php } ?>
													</ul>
												<?php } ?>
											</fieldset>
											<fieldset class="dc-termsconditions">
												<div class="dc-checkboxholder">
													<div class="form-group form-group-half wt-display-type">
														<input type="text" name="display_name" class="form-control" value="" placeholder="<?php esc_attr_e('Display Name', 'doccure_core'); ?>">
													</div>								
													<span class="dc-checkbox">
														<input id="termsconditions" type="checkbox" name="termsconditions" value="checked">
														<label for="termsconditions">
															<span>
																<?php echo esc_html( $term_text ); ?>
																<?php if( !empty( 	$terms_link ) ) { ?>
																	<a target="_blank" href="<?php echo esc_url( $terms_link ); ?>">
																		<?php esc_html_e('Terms & Conditions', 'doccure_core'); ?>
																	</a>
																<?php } ?>
															</span>
														</label>
													</span>	
													<div class="form-group">
														<button class="dc-btn rg-step-start" type="submit"><?php esc_html_e('Signup Now', 'doccure_core'); ?></button>
													</div>							
												</div>
											</fieldset>
										</div>
									</div>
								</div>    
								<?php if( !is_user_logged_in() ){ ?>
									<div class="dc-registerformfooter">
										<span><?php esc_html_e('Already Have an Account?', 'doccure_core' ); ?><a class="dc-loginbtn" data-toggle="modal" data-target="#dc-loginpopup" href="javascript:;">&nbsp;<?php esc_html_e('Login Now', 'doccure_core'); ?></a></span>
									</div>
								<?php } ?>
							</form>                                        
						</div>                                        
					</div>
				</div>
			</div>
			<?php
			}
			echo ob_get_clean();
        }
      
    }
    new SC_doccure_Authentication();
}
<?php
/**
 *
 * The template used for doctors bookings
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post,$current_user,$doccure_options;
$post_id 				= $post->ID; 
$user_id				= doccure_get_linked_profile_id( $post_id ,'post');
$relationship			= doccure_patient_relationship();
$doctor_location		= !empty($doccure_options['doctor_location']) ? $doccure_options['doctor_location'] : '';
$location_id			= get_post_meta($post_id, '_doctor_location', true);
$location_id			= !empty( $location_id ) ? intval( $location_id ) : '';
$booking_option			= apply_filters('doccure_doctor_appointment_settings',$post_id);
$payment_type			= !empty( $doccure_options['payment_type'] ) ? $doccure_options['payment_type'] : '';
$enable_checkout_page	= !empty( $doccure_options['enable_checkout_page'] ) ? $doccure_options['enable_checkout_page'] : '';
$offline_success_title 	= !empty( $doccure_options['success_title'] ) ? $doccure_options['success_title'] : '';
$offline_success_desc 	= !empty( $doccure_options['success_desc'] ) ? $doccure_options['success_desc'] : '';
$user 					= wp_get_current_user();
$user_email	 			= !empty($current_user->user_email) ? $current_user->user_email : '';

$mobile_number			= get_user_meta( $current_user->ID, 'mobile_number', true );

$mobile_number			= !empty($mobile_number) ? $mobile_number : '';
$full_name				= !empty($current_user->ID) ? doccure_get_username($current_user->ID) : '';

$success_title		= esc_html__('Please wait...', 'doccure');
$success_desc		= esc_html__('To confirm your booking please pay your payment via online. otherwise booking not conformed.', 'doccure');
$timezone_string	= get_option('timezone_string');
if(!empty($payment_type) && $payment_type == 'offline' && ( empty($enable_checkout_page) || $enable_checkout_page == 'hide' ) ){	
	$success_title	= $offline_success_title;
	$success_desc	= $offline_success_desc;
}
?>
<div class="modal fade dc-appointmentpopup" tabindex="-1" role="dialog" id="appointment" data-backdrop="static"> 

	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="dc-modalcontent modal-content">
			<?php if( !empty($booking_option) ){ ?>   
				<section id="sec1" class="sec1">
					<div class="dc-popuptitle">
						<h3><?php esc_html_e('Book Appointment','doccure');?></h3>
 						<a href="javascript:;" class="dc-closebtn close dc-close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e('Close','doccure');?>"><i class="fas fa-times"></i></a>
					</div>
					<div id="dcModalBody" class="modal-body dc-modal-content-one dc-haslayout">
						<ul class="dc-joinsteps">
							<li class="dc-active"><a href="javascrip:;"><?php esc_html_e('01','doccure');?></a></li>
							<li><a href="javascrip:;"><?php esc_html_e('02','doccure');?></a></li>
							<li><a href="javascrip:;"><?php esc_html_e('03','doccure');?></a></li>
							<li><a href="javascrip:;"><?php esc_html_e('04','doccure');?></a></li>
						</ul>
						<div id="dcModalBody1" class="dc-visitingdoctor">
							<form class="dc-booking-step1">
								<div class="dc-title">
								<label><span><?php esc_html_e('Who is Visiting To Doctor?','doccure');?></span></label>
									<div class="dc-tabbtns">
										<span class="dc-radio next-step">
											<input type="radio" name="myself" class="myself" value="myself" id="myself" checked>
											<label for="myself"><?php esc_html_e('Myself','doccure');?></label>
										</span>
										<span class="dc-radio next-step">
											<input type="radio" name="myself" class="myself" value="someelse" id="someelse">
											<label for="someelse"><?php esc_html_e('Someone Else','doccure');?></label>
										</span>
									</div>
								</div>
								<div class="dc-formtheme dc-docinfoform">
									<fieldset>
										<div class="form-group form-group-half">
 											<label><?php esc_html_e('Email','doccure');?></label>
											<input type="text" name="bk_email" class="form-control" value="<?php echo esc_attr($user_email);?>" >
										</div>
										<div class="form-group form-group-half">
										<label><?php esc_html_e('Mobile number','doccure');?></label>
											<input type="text" name="bk_phone" class="form-control" value="<?php echo esc_attr($mobile_number);?>" >
										</div>
										<div class="form-group form-group-half">
										<label><?php esc_html_e('Patient Name','doccure');?></label>
											<input type="text" name="other_name" value="<?php echo esc_attr($full_name);?>" class="form-control" >
										</div>
										<div class="form-group form-group-half form-group-relation">
										<label><?php esc_html_e('Relation with you?','doccure');?></label>
											<span class="dc-select">
												<select data-placeholder="<?php esc_attr_e('Relation with you? *','doccure');?>" name="relation">
													<option value=""><?php esc_html_e('Relation with you?','doccure');?></option>
													<?php foreach( $relationship as $key => $val ){?>
														<option value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $val );?></option>
													<?php } ?>
												</select>
											</span>
										</div>
									</fieldset>
								</div>
								<div class="dc-formtheme dc-vistingdocinfo">
									<fieldset>
										<div class="form-group">
										<label><?php esc_html_e('Where to visit?','doccure');?></label>
											<span class="dc-select">
												<select name="booking_hospitals" data-doctor_id="<?php echo intval( $post_id );?>" class="dc-booking-hospitals">
													<?php
											   			if(!empty($doctor_location) && $doctor_location === 'both'){
															echo '<option value="">'.esc_html__('Where to visit?*','doccure').'</option>';
															echo '<option value="'.$location_id.'">'.get_the_title($location_id).'</option>';
															doccure_get_list_hospital('hospitals_team',$user_id);
														}else if(!empty($doctor_location) && $doctor_location === 'clinic'){
															echo '<option value="">'.esc_html__('Where to visit?*','doccure').'</option>';
															echo '<option value="'.$location_id.'">'.get_the_title($location_id).'</option>';
														}else if(!empty($doctor_location) && $doctor_location === 'hospitals'){
															echo '<option value="">'.esc_html__('Where to visit?*','doccure').'</option>';
															doccure_get_list_hospital('hospitals_team',$user_id);
														}
													?>
												</select>
											</span>
										</div>
										<div class="form-group" id="booking_service_select"></div>
										<div class="form-group" id="booking_fee"></div>
										<div class="form-group">
										<label><?php esc_html_e('Comments','doccure');?></label>
											<textarea class="form-control" name="booking_content"></textarea>
										</div>
									</fieldset>
								</div>
								<div class="dc-appointment-holder">
									<?php if(!empty($timezone_string)){?>
										<div class="dc-title">
											<h4><?php esc_html_e('Select best time for appointment with time zone','doccure');?></h4>
											<em><?php esc_html_e('*These time slots are based on the timezone','doccure');?>&nbsp;<?php echo esc_html($timezone_string);?></em>
										</div>
									<?php }?>
									<div class="dc-appointment-content">
										<div class="dc-appointment-calendar">
											<div id="dc-calendar" class="dc-calendar"></div>
										</div>
										<div class="dc-timeslots dc-update-timeslots">
											<?php do_action('doccure_empty_records_html','dc-empty-articls dc-emptyholder-sm',esc_html__( 'There are no any slot available.', 'doccure' ));?>
										</div>
										<input type="hidden" value="<?php echo date('Y-m-d');?>" name="appointment_date" id="appointment_date">
									</div>
								</div>
							</form>
						</div>
					</div>
					<div id="dcModalBody2" class="modal-body dc-modal-content-two dc-haslayout">
						<div class="dc-visitingdoctor dc-popup-doc">
							<ul class="dc-joinsteps">
								<li class="dc-done-next"><a href="javascrip:;"><i class="fa fa-check"></i></a></li>
								<li class="dc-active"><a href="javascrip:;"><?php esc_html_e('02','doccure');?></a></li>
								<li><a href="javascrip:;"><?php esc_html_e('03','doccure');?></a></li>
								<li><a href="javascrip:;"><?php esc_html_e('04','doccure');?></a></li>
							</ul>
							<form class="dc-booking-step2">
								<?php if( $current_user->ID ) { ?>
									<div class="dc-visit">
										<label><span><?php esc_html_e('Verify is that you?','doccure');?></span></label>
									</div>
									<div class="form-row dc-popup-row">
										<div class="form-group col-6">
										<label><?php esc_html_e('Password','doccure');?></label>
											<input type="password" class="form-control" name="password" >
										</div>
										<div class="form-group col-6">
										<label><?php esc_html_e('Retype Password','doccure');?></label>
											<input type="password" class="form-control" name="retype_password" >
										</div>
									</div>							
								<?php } else { ?>
									<div class="dc-visit">
									<label><span><?php esc_html_e('Verify is that you?','doccure');?></span></label>
									</div>	
									<div class="form-row dc-popup-row">
										<div class="form-group col-6">
										<label><?php esc_html_e('Name','doccure');?></label>
											<input type="text" class="form-control" name="full_name" >
										</div>
										<div class="form-group col-6">
										<label><?php esc_html_e('Phone Number','doccure');?></label>
											<input type="text" class="form-control" name="phone_number" >
										</div>
										<div class="form-group col-12">
										<label><?php esc_html_e('Email','doccure');?></label>
											<input type="email" class="form-control" name="email">
										</div>
									</div>
								<?php } ?>
							</form>
						</div>
					</div>
					<div id="dcModalBody3" class="modal-body dc-modal-content-three dc-haslayout">
						<ul class="dc-joinsteps">
							<li class="dc-done-next"><a href="javascrip:;"><i class="fa fa-check"></i></a></li>
							<li class="dc-done-next"><a href="javascrip:;"><i class="fa fa-check"></i></a></li>
							<li class="dc-active"><a href="javascrip:;"><?php esc_html_e('03','doccure');?></a></li>
							<li><a href="javascrip:;"><?php esc_html_e('04','doccure');?></a></li>
						</ul>
						<h5><?php esc_html_e('Enter Authentication Code','doccure');?></h5>
						<p><?php esc_html_e('We’ve sent verification code on your email at','doccure');?>&nbsp;<a class="email_address" href="javascript:;"></a></p>
						<form class="dc-booking-step3">
							<input type="text" placeholder="<?php esc_attr_e('Add authentication code','doccure');?>" name="authentication_code">
							<label><a href="javascript:;" class="dc-resend-booking-code"><?php esc_html_e('Resend Verification Code', 'doccure'); ?></a></label>
						</form>
						
					</div>
					<div id="dcModalBody4" class="modal-body dc-modal-content-four dc-haslayout">
						<ul class="dc-joinsteps">
							<li class="dc-done-next"><a href="javascrip:;"><i class="fa fa-check"></i></a></li>
							<li class="dc-done-next"><a href="javascrip:;"><i class="fa fa-check"></i></a></li>
							<li class="dc-done-next"><a href="javascrip:;"><i class="fa fa-check"></i></a></li>
							<li class="dc-done-next"><a href="javascrip:;"><i class="fa fa-check"></i></a></li>
						</ul>
						<div class="dc-modal-body4-title">
							<h4><?php echo do_shortcode($success_title);?></h6>
							<p><?php echo do_shortcode($success_desc);?></p>
							<?php if(!empty($payment_type) && $payment_type == 'offline' && ( empty($enable_checkout_page) || $enable_checkout_page == 'hide' ) ){ ?>
								<div class="dc-offline-checkout"></div>
								<a href="javascript:;" class="dc-btn finish-appointment"><?php esc_html_e('Finish','doccure');?></a>
							<?php } ?>
						</div>
					</div>
					<div class="modal-footer dc-modal-footer">
						<a href="javascript:;" id="dcbtn" class="btn dc-btn btn-primary dc-booking-step1-btn" data-id="<?php echo intval($user_id);?>" data-toggle="modal" data-target="#appointment2"><?php esc_html_e('Book Now','doccure');?></a>
						<a href="javascript:;" id="dcbtn2" data-id="<?php echo intval($user_id);?>" class="btn dc-btn btn-primary dc-booking-step2-btn" data-toggle="modal" data-target="#appointment2"><?php esc_html_e('Book Now','doccure');?></a>
						<a href="javascript:;" id="dcbtn3" class="btn dc-btn btn-primary dc-booking-step3-btn" data-toggle="modal" data-target="#appointment2"><?php esc_html_e('Verify Now','doccure');?></a>
					</div>
				</section>			
			<?php } else { ?>
				<div class="dc-popuptitle">
					<h3><?php esc_html_e('Book Appointment','doccure');?></h3>
					<a href="javascript:;" class="dc-closebtn close dc-close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e('Close','doccure');?>"><i class="fas fa-times"></i></a>
				</div>
				<?php do_action('doccure_empty_records_html','dc-empty-articls dc-emptyholder-sm',esc_html__( 'Doctor has not updated his booking settings.', 'doccure' ));?>
			<?php }?>	
		</div>
	</div>
</div> 
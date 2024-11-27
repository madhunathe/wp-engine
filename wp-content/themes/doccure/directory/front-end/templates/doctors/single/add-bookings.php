<?php
/**
 *
 * The template used for add doctors bookings
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post,$current_user,$doccure_options;
$user_id		= $current_user->ID;
$relationship	= doccure_patient_relationship();
$doctor_location	= !empty($doccure_options['doctor_location']) ? $doccure_options['doctor_location'] : '';
$post_id		= doccure_get_linked_profile_id($user_id);
$location_id	= get_post_meta($post_id, '_doctor_location', true);
$location_id	= !empty($location_id) ? $location_id : 0;
$timezone_string	= get_option('timezone_string');
?>
<div class="modal fade dc-appointmentpopup dc-feedbackpopup dc-bookappointment" role="dialog" id="appointment"> 
 	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="dc-modalcontent modal-content">	
			<div class="dc-popuptitle">
				<h3><?php esc_html_e('Reschedule Appointment','doccure');?></h3>
 				<a href="javascript:;" class="dc-closebtn close dc-close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e('Close','doccure');?>"><i class="fas fa-times"></i></a>
			</div>
			<div id="dcModalBody" class="modal-body dc-modal-content-one dc-haslayout">
				<div id="dcModalBody1" class="dc-visitingdoctor">
					<form class="dc-booking-doctor dc-formfeedback">
  
						<input type="hidden" name="order_post_id" value="" >
						<input type="hidden" name="booking_hospitals" value="<?php echo $location_id; ?>"  data-doctor_id="<?php echo intval( $post_id );?>" class="dc-booking-hospitals" >

						<div class="dc-formtheme dc-vistingdocinfo">
							<fieldset>
								 
								<div class="form-group" id="booking_service_select"></div>
								<div class="form-group" id="booking_fee"></div>
 							</fieldset>
						</div>
						<div class="dc-appointment-holder">
							 
							<div class="dc-appointment-content">
								<div class="dc-appointment-calendar">
									<div id="dc-calendar" class="dc-calendar"></div>
								</div>
								<div class="dc-timeslots dc-update-timeslots"><?php do_action('doccure_empty_records_html','dc-empty-articls dc-emptyholder-sm',esc_html__( 'There are no any slot available.', 'doccure' ));?></div>
								<input type="hidden" value="<?php echo date('Y-m-d');?>" name="appointment_date" id="appointment_date">
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="modal-footer dc-modal-footer">
				<a href="javascript:;" id="dcbtn" class="btn dc-btn btn-primary dc-booking-doctor-reschedule" data-id="<?php echo intval($user_id);?>" data-toggle="modal" data-target="#appointment2"><?php esc_html_e('Continue','doccure');?></a>
			</div>			
		</div>
	</div>
</div> 
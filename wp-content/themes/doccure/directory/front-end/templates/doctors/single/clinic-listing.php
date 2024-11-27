<?php 
/**
 *
 * The template part for displaying doctors in listing
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $post,$doccure_options;
$post_id 		= !empty( $args['post_id']) ? $args['post_id'] : '';
$user_id		= doccure_get_linked_profile_id($post->ID,'post');
$location_id	= get_post_meta($post_id, '_doctor_location',true);
$doctor_location	= !empty($doccure_options['doctor_location']) ? $doccure_options['doctor_location'] : '';
$location		= doccure_get_location($location_id);
$location		= !empty( $location['_country'] ) ? $location['_country'] : '';
$bookig_days	= doccure_get_booking_clinic_days( $user_id );
$bookig_days	= !empty( $bookig_days ) ? $bookig_days : array();
$tagline		= doccure_get_post_meta( $post_id, 'am_sub_heading');
$starting_price	= doccure_get_post_meta( $post_id, 'am_starting_price');
$feedback			= get_post_meta($post_id,'review_data',true);
$feedback			= !empty( $feedback ) ? $feedback : array();
$total_rating		= !empty( $feedback['dc_total_rating'] ) ? $feedback['dc_total_rating'] : 0 ;
$total_percentage	= !empty( $feedback['dc_total_percentage'] ) ? $feedback['dc_total_percentage'] : 0 ;
if ( 'publish' !== get_post_status ( $location_id ) ) {
    return;
}

$id				= !empty( $location_id ) ? intval( $location_id ) : '';

$am_slots_data 		= get_post_meta( $id,'am_slots_data',true);
$am_slots_data		= !empty( $am_slots_data ) ? $am_slots_data : array();

$days		= doccure_get_week_array();

$time_format = get_option('time_format');

if(!empty($doctor_location) && $doctor_location !== 'hospitals' && !empty($location_id)){?>
<div class="dc-docpostholder dc-search-hospitals locationtab_data">
	<div class="row dc-docpostcontent">
		<div class="col-lg-6 dc-searchvtwo">
			<?php do_action('doccure_get_doctor_clinic_details',$location_id);?>
		</div>
	</div>
		<div class="row">
		<div class="col-lg-12 dc-doclocation dc-doclocationvtwo single_doc pt-0">
				<div class="availabletimings row single_doctor_available">
				
				<?php 
				foreach( $days as $key => $day ) { 
					$day_slots	= !empty( $am_slots_data[$key] ) ? $am_slots_data[$key] : array();
											$day_start	= !empty( $day_slots['start_time'] ) ? $day_slots['start_time'] : '';
											$day_end	= !empty( $day_slots['end_time'] ) ? $day_slots['end_time'] : '';
											$slots		= !empty( $day_slots['slots'] ) ? $day_slots['slots'] : '';	
									?>
										<div class="day_name col-md-4">
										<?php if( !empty( $day ) ) {?>
											<span class="day_txt"><i class="fa-regular fa-calendar"></i><?php echo esc_html( $day );?></span><?php } 
											
											?>
											
											
											
										<div class="time_txt">
										<?php 
											if( !empty( $slots ) ){
												foreach( $slots as $slot_key => $slot_val ) { 
													$slot_key_val = explode('-', $slot_key);
												?>
												<div class="timeslot_available">
												
														<span><?php echo date_i18n($time_format, strtotime('2016-01-01' . $slot_key_val[0]));
														?>
														
													</span>
												</div>
											<?php } ?>
										<?php } else{?>
											<div class="timeslot_available">
												<span class="not_available">Not available</span>
										</div>
											
									<?php 	}?>
										</div>
										</div>
				<?php 
										
					}
				?>
			
			</div>
		</div>
		
	</div>
		
</div>
<?php }
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
global $post;
$specialities 	= !empty( $_GET['specialities']) ? $_GET['specialities'] : '';
?>
<div class="dc-docpostholder dc-search-hospitals">
	<div class="dc-docpostcontent 2">
		<div class="dc-searchvtwo">
			<?php do_action('doccure_get_doctor_thumnail',$post->ID);?>
			<?php do_action('doccure_get_doctor_details',$post->ID);?>
			<?php do_action('doccure_get_doctor_services',$post->ID,'services');?>
		</div>
		<?php do_action('doccure_get_hospital_booking_information',$post->ID);?>
	</div>
</div>
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
<div class="dc-docpostholder dc-search-doctors">
	<div class="dc-docpostcontent 5">
		<div class="dc-searchvtwo">
			<div class="doctor-img">
			<a href="<?php echo esc_url(get_the_permalink($post->ID));?>"><?php do_action('doccure_get_doctor_thumnail',$post->ID);?></a>
			</div>
			<?php do_action('doccure_get_doctor_details',$post->ID);?>
		</div>
		<?php do_action('doccure_get_doctor_booking_information',$post->ID);?>
		
	</div>
</div>
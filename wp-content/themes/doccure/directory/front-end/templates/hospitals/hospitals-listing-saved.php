<?php 
/**
 *
 * The template part for displaying hosptials in listing for saved
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $post,$current_user;
 	$user_identity   = $current_user->ID;
	$link_id		 = doccure_get_linked_profile_id( $user_identity );
?>
<div class="dc-docpostholder">
	<div class="dc-docpostcontent 1">
		<div class="dc-searchvtwo">
			<?php do_action('doccure_get_doctor_thumnail',$post->ID);?>
			<?php do_action('doccure_get_doctor_details',$post->ID);?>
		</div>
		<div class="dc-actions">
			<a href="#" data-id="<?php echo intval($link_id);?>" data-itme-type="_saved_hospitals" data-item-id="<?php echo intval($post->ID);?>" class="dc-removesingle_saved">
				<span class="fa fa-trash"></span>
			</a>
		</div>
	</div>
</div>
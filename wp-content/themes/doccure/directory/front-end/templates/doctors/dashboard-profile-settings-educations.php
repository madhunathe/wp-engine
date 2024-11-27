<?php
/**
 *
 * The template part for displaying the dashboard menu
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post;
$user_identity 	 = $current_user->ID;
$linked_profile  = doccure_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;
?>
<div class="">		
	<form class="dc-haslayout dc-user-profile-educations" method="post">	
	<?php get_template_part('directory/front-end/templates/doctors/dashboard', 'profile-settings-tabs'); ?>
		<div class="dc-dashboardbox dc-dashboardtabsholder">
			
			<div class="dc-tabscontent tab-content pt-0 pb-0">
				<div class="dc-personalskillshold tab-pane active fade show" id="dc-skills">	
					<?php get_template_part('directory/front-end/templates/doctors/dashboard', 'education'); ?>	
					<?php get_template_part('directory/front-end/templates/doctors/dashboard', 'experience'); ?>	
				</div>
			</div>
		</div>
		<div class="dc-updatall">
			<i class="ti-announcement"></i>
			<a class="dc-btn dc-update-doctors-education" data-id="<?php echo esc_attr( $user_identity ); ?>" data-post="<?php echo esc_attr( $post_id ); ?>" href="javascript:;"><?php esc_html_e('Save Changes', 'doccure'); ?></a>
		</div>	
	</form>		
</div>
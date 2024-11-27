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

if (is_active_sidebar('user-dashboard-sidebar-right')) {?>
	<div class="dc-companyad">
		<figure><?php dynamic_sidebar('user-dashboard-sidebar-right'); ?></figure>
	</div>
<?php }?>
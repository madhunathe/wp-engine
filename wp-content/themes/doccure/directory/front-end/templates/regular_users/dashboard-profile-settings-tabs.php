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
$profile_details_url 		= doccure_Profile_Menu::doccure_profile_menu_link('profile', $user_identity, true,'settings');
$mode 			 			= !empty($_GET['mode']) ? esc_html( $_GET['mode'] ) : 'settings';
?>
<div class="dc-dashboardboxtitle">
	<h2><?php esc_html_e('Profile Settings','doccure');?></h2>
</div>
<div class="dc-dashboardtabs">
	<ul class="dc-tabstitle nav navbar-nav">
		<li class="nav-item">
			<a class="<?php echo !empty( $mode ) && $mode === 'settings' ? 'active' : '';?>" href="<?php echo esc_url( $profile_details_url );?>">
				<?php esc_html_e('Personal Details', 'doccure'); ?>
			</a>
		</li>
	</ul>
</div>


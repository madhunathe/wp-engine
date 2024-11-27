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

$reference 		 = (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : '';
$mode 			 = (isset($_GET['mode']) && $_GET['mode'] <> '') ? $_GET['mode'] : '';
$user_identity 	 = $current_user->ID;
$user_type		 = apply_filters('doccure_get_user_type', $user_identity );
$mode_slug		= !empty($user_type) && $user_type!='regular_users' ? 'manage' : 'password';
?>
<li class="<?php echo esc_attr( $reference  === 'account-settings' ? 'dc-active' : ''); ?>">
	<a href="<?php doccure_Profile_Menu::doccure_profile_menu_link('account-settings', $user_identity,false,$mode_slug); ?>">
		<i class="fas fa-lock"></i>
		<span><?php esc_html_e('Change Password','doccure');?></span>
	</a>
</li>

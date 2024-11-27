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
$role = $current_user->roles[0];
$role_name = $role ? wp_roles()->get_names()[ $role ] : '';
?>
<li id="<?php echo esc_html($role_name);?>" class="<?php echo esc_attr( $reference === 'saved' ? 'dc-active' : ''); ?>">
	<a href="<?php doccure_Profile_Menu::doccure_profile_menu_link('saved', $user_identity); ?>">
		<i class="fa fa-heart"></i>
		<span><?php esc_html_e('Favourites','doccure');?></span>
	</a>
</li>

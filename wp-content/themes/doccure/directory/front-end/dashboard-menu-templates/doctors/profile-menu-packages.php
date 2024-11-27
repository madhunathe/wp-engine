<?php
/**
 *
 * The template part for displaying the dashboard menu
 *
 * @package   Doccure
 * @since 1.0
 */

global $current_user, $wp_roles, $userdata, $post;

$reference 		 = (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : '';
$mode 			 = (isset($_GET['mode']) && $_GET['mode'] <> '') ? $_GET['mode'] : '';
$user_identity 	 = $current_user->ID;
?>
<li class="<?php echo esc_attr( $reference === 'packages' ? 'dc-active' : ''); ?>">
	<a href="<?php doccure_Profile_Menu::doccure_profile_menu_link('package', $user_identity); ?>">
		<i class="fas fa-check"></i>
		<span><?php esc_html_e('Packages','doccure');?></span>
	</a>
</li>

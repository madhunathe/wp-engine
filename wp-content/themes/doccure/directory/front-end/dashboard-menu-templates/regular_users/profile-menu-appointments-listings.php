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
$url_identity 	 = $current_user->ID;
?>
<li class="<?php echo esc_attr( $reference === 'appointment' && $mode ==='listing' ? 'dc-active' : ''); ?>">
	<a href="<?php doccure_Profile_Menu::doccure_profile_menu_link('appointment', $url_identity,'','listing'); ?>">
		<i class="fas fa-calendar-check"></i>
		<span><?php esc_html_e('Appointment List','doccure');?></span>
	</a>
</li>
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
$user_identity   = $current_user->ID;
?>
<li class="<?php echo esc_attr( $reference === 'invoices' ? 'dc-active' : ''); ?>">
	<a href="<?php doccure_Profile_Menu::doccure_profile_menu_link('invoices', $user_identity); ?>">
		<i class="fas fa-file-invoice"></i>
		<span><?php esc_html_e('Invoices','doccure');?></span>
	</a>
</li>

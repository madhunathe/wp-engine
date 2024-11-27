<?php
/**
 *
 * The template part for displaying the dashboard menu
 *
 * @package   Doccure
 
 * @since 1.0
 */

global $current_user;

$reference 		 = (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : '';
$mode 			 = (isset($_GET['mode']) && $_GET['mode'] <> '') ? $_GET['mode'] : '';
$user_identity 	 = $current_user->ID;
?>
<li class="<?php echo esc_attr( $reference  === 'payouts' ? 'dc-active' : ''); ?>">
	<a href="<?php Doccure_Profile_Menu::doccure_profile_menu_link('payouts', $user_identity,'','settings'); ?>">
		<i class="fas fa-money-bill-alt"></i>
		<span><?php esc_html_e('Payouts Settings','doccure');?></span>
	</a>
</li>
